<?php
/**
 * Shabab Deira - Public Blog & Industry Insights Directory
 */
require_once __DIR__ . '/config/db.php';
$db = get_db_connection();

$selectedCategory = trim($_GET['category'] ?? '');
$searchQuery = trim($_GET['search'] ?? '');
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 9;
$offset = ($page - 1) * $limit;

// Fetch Categories
$categories = $db->query("SELECT c.*, COUNT(p.id) as post_count 
                         FROM blog_categories c 
                         LEFT JOIN blog_posts p ON c.id = p.category_id AND p.status = 'Published' 
                         GROUP BY c.id ORDER BY c.name ASC")->fetchAll();

// Where clause
$where = ["p.status = 'Published'"];
$params = [];

if (!empty($selectedCategory)) {
    $where[] = "(c.slug = ? OR c.name = ?)";
    $params[] = $selectedCategory;
    $params[] = $selectedCategory;
}

if (!empty($searchQuery)) {
    $where[] = "(p.title LIKE ? OR p.description LIKE ? OR p.tags LIKE ?)";
    $term = "%$searchQuery%";
    $params = array_merge($params, [$term, $term, $term]);
}

$whereSql = implode(" AND ", $where);

// Total Count
$countStmt = $db->prepare("SELECT COUNT(*) as total FROM blog_posts p LEFT JOIN blog_categories c ON p.category_id = c.id WHERE $whereSql");
$countStmt->execute($params);
$totalPosts = $countStmt->fetch()['total'];
$totalPages = ceil($totalPosts / $limit);

// Fetch Featured Post for Hero Spotlight (if on page 1 with no search)
$featuredPost = null;
if ($page === 1 && empty($searchQuery) && empty($selectedCategory)) {
    $featStmt = $db->query("SELECT p.*, c.name as category_name, c.slug as category_slug 
                           FROM blog_posts p 
                           LEFT JOIN blog_categories c ON p.category_id = c.id 
                           WHERE p.status = 'Published' AND p.is_featured = 1 
                           ORDER BY p.id DESC LIMIT 1");
    $featuredPost = $featStmt->fetch();
}

// Fetch Posts Grid
$sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
        FROM blog_posts p 
        LEFT JOIN blog_categories c ON p.category_id = c.id 
        WHERE $whereSql ";

if ($featuredPost) {
    $sql .= " AND p.id != " . intval($featuredPost['id']);
}

$sql .= " ORDER BY p.id DESC LIMIT $limit OFFSET $offset";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$posts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <script>(function(){var l=localStorage.getItem('shabab_lang')||'en';document.documentElement.lang=l;document.documentElement.dir=(l==='ar'?'rtl':'ltr');document.documentElement.setAttribute('data-lang',l);})();</script>
  <meta charset="UTF-8">
  <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
  <meta name="author" content="Shabab Deira Shipping and Cargo Services LLC">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Industry Insights and Manpower Guides | Shabab Deira Dubai</title>
  <meta name="description" content="Explore expert guides, UAE labor law updates, workforce case studies, and practical manpower supply insights from Shabab Deira operations specialists in Dubai.">
  <meta name="keywords" content="manpower blog Dubai, labour supply guides UAE, MOHRE compliance updates, warehouse logistics articles, temporary staffing tips">
  <link rel="canonical" href="https://shababdeira.com/blog.php">
  <link rel="icon" type="image/svg+xml" href="favicon.svg">
  <link rel="icon" type="image/png" sizes="32x32" href="favicon.svg">

  <meta property="og:type" content="website">
  <meta property="og:title" content="Industry Insights and Manpower Guides | Shabab Deira Dubai">
  <meta property="og:description" content="Explore expert guides, UAE labor law updates, workforce case studies, and practical manpower supply insights from Shabab Deira operations specialists in Dubai.">
  <meta property="og:image" content="https://shababdeira.com/assets/images/og-cover.jpg">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css?v=3.0">
  <script src="js/lucide.min.js"></script>
</head>
<body>

  <!-- Top Bar -->
  <div class="top-bar">
    <div class="container top-bar__inner">
      <div class="top-bar__group">
        <a class="top-bar__item" data-bind="company.mapsUrl" href="https://www.google.com/maps/search/?api=1&query=Office+M33+Alphamed+Building+Abu+Hail+Road+Hor+Al+Anz+East+Dubai+UAE" target="_blank" rel="noopener" style="color:inherit;text-decoration:none;"><i data-lucide="map-pin"></i><span data-bind="company.locationShort">Hor Al Anz East, Dubai, UAE</span></a>
        <div class="top-bar__item"><i data-lucide="clock"></i><span data-bind="company.hours">Mon – Sat: 8 AM – 7 PM</span></div>
      </div>
      <div class="top-bar__group">
        <div class="top-bar__item"><i data-lucide="phone"></i><a href="tel:+971561359305" style="color:inherit;" data-bind="company.phone">+971 56 135 9305</a></div>
        <div class="top-bar__item"><i data-lucide="mail"></i><a href="mailto:info@shababdeira.com" style="color:inherit;" data-bind="company.email">info@shababdeira.com</a></div>
        <button class="lang-switch" aria-label="Toggle language">عربي</button>
      </div>
    </div>
  </div>

  <!-- Header -->
  <header class="header">
    <div class="container header__inner">
      <a href="index.html" class="logo" aria-label="Shabab Deira Homepage">
        <div class="logo__mark">
          <svg viewBox="20 18 172 150" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
            <path d="M 52 24.795 C 52 25.323, 52.698 27.426, 53.551 29.468 C 54.986 32.903, 58.841 48.015, 68.582 88.395 L 72.416 104.289 68.996 107.145 C 67.115 108.715, 65.056 110, 64.421 110 C 63.785 110, 60.877 102.688, 57.958 93.750 C 51.585 74.235, 45.711 61.107, 38.819 50.977 C 31.728 40.552, 25.520 36.158, 29.266 44.214 C 32.224 50.578, 38.816 71.615, 40.949 81.500 C 46.466 107.067, 48.449 114.130, 53.363 125.720 C 54.813 129.141, 56 132.741, 56 133.720 C 56 135.992, 48.528 142.488, 40.722 147.003 C 37.390 148.930, 34.476 151.073, 34.245 151.765 C 33.714 153.358, 40.269 160, 42.371 160 C 43.235 160, 46.092 157.967, 48.721 155.482 C 59.369 145.417, 70.556 140.610, 97.500 134.522 C 104.100 133.031, 114.149 130.729, 119.830 129.406 C 125.512 128.083, 131.024 127, 132.080 127 C 135.906 127, 133.871 125.104, 128.250 123.429 C 117.667 120.277, 100.092 120.531, 90 123.982 C 88.625 124.453, 86.243 125.112, 84.707 125.448 C 81.920 126.058, 81.918 126.054, 83.580 123.518 C 84.496 122.120, 86.315 120.708, 87.623 120.380 C 91.046 119.521, 90.547 117.338, 86.750 116.566 C 78.307 114.849, 77.381 114.836, 76.072 116.413 C 75.348 117.286, 74.135 118, 73.378 118 C 72.620 118, 72 118.450, 72 119 C 72 119.550, 71.213 119.981, 70.250 119.957 C 69.015 119.927, 69.785 118.786, 72.866 116.081 C 76.225 113.130, 79.567 111.533, 87.366 109.150 C 97.137 106.165, 113.786 102.013, 130.500 98.394 L 138.500 96.661 134.055 94.708 C 126.980 91.599, 115.275 91.301, 104.662 93.959 C 99.808 95.175, 95.592 95.925, 95.292 95.626 C 94.993 95.326, 96.380 94.119, 98.374 92.942 C 100.368 91.765, 102 90.448, 102 90.015 C 102 89.582, 99.907 88.913, 97.349 88.530 C 93.888 88.011, 91.706 88.280, 88.816 89.583 C 86.355 90.693, 84.585 90.985, 83.981 90.381 C 83.456 89.856, 80.042 80.669, 76.393 69.964 C 69.088 48.533, 63.933 37.063, 58.243 29.575 C 54.626 24.816, 52 22.805, 52 24.795 M 137.500 64.021 C 129.493 66.567, 122.113 71.368, 114.329 79.093 C 106.661 86.704, 107.656 88.343, 115.559 81.120 C 128.124 69.636, 143.084 65.536, 156.332 69.944 C 161.741 71.744, 163.818 73.115, 167.892 77.573 C 173.905 84.151, 176.598 90.878, 177.498 101.559 C 179.340 123.428, 169.817 142.210, 152.408 151.047 C 139.926 157.382, 124.735 156.113, 112.867 147.744 C 109.660 145.482, 106.797 143.870, 106.506 144.161 C 105.416 145.251, 116.473 155.084, 121.620 157.603 C 125.608 159.554, 129.101 160.352, 135.348 160.742 C 149.406 161.619, 159.219 158.222, 169.335 148.977 C 181.119 138.207, 186.107 125.358, 186.139 105.692 C 186.160 93.336, 184.713 86.147, 180.609 78.211 C 178.187 73.528, 169.401 66.294, 163.414 64.054 C 156.371 61.419, 145.724 61.405, 137.500 64.021 M 103 139.878 C 103 141.181, 105.583 143.084, 106.333 142.333 C 107.084 141.583, 105.181 139, 103.878 139 C 103.395 139, 103 139.395, 103 139.878" fill="#076835" fill-rule="evenodd"/>
          </svg>
        </div>
        <div class="logo__text">
          <span class="logo__name" data-i18n="company.name">Shabab Deira</span>
          <span class="logo__sub" data-i18n="company.tagline">Your Workforce Partner</span>
        </div>
      </a>

      <nav aria-label="Main Navigation">
        <ul class="nav">
          <li class="nav__item"><a href="index.html" class="nav__link" data-i18n="nav.home">Home</a></li>
          <li class="nav__item"><a href="services.html" class="nav__link" data-i18n="nav.services">Services</a></li>
          <li class="nav__item"><a href="industries.html" class="nav__link" data-i18n="nav.industries">Industries</a></li>
          <li class="nav__item"><a href="about.html" class="nav__link" data-i18n="nav.aboutUs">About Us</a></li>
          <li class="nav__item"><a href="staff.html" class="nav__link" data-i18n="nav.leadership">Our Team</a></li>
          <li class="nav__item"><a href="group.html" class="nav__link" data-i18n="nav.group">Our Group</a></li>
          <li class="nav__item"><a href="blog.php" class="nav__link active">Blog</a></li>
          <li class="nav__item"><a href="contact.html" class="nav__link" data-i18n="nav.contact">Contact</a></li>
        </ul>
      </nav>

      <div class="header__actions">
        <a href="request-manpower.html" class="btn btn--primary btn--pill header__cta">
          <span data-i18n="nav.requestManpower">Book Manpower</span> <i data-lucide="arrow-right"></i>
        </a>
        <button class="mobile-toggle" id="mobileNavToggle" aria-label="Open menu">
          <i data-lucide="menu"></i>
        </button>
      </div>
    </div>
  </header>

  <!-- Mobile Drawer -->
  <div class="drawer-overlay" id="drawerOverlay"></div>
  <div class="mobile-drawer" id="mobileDrawer">
    <div>
      <div class="drawer__head">
        <div class="logo">
          <div class="logo__mark">
            <svg viewBox="20 18 172 150" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
            <path d="M 52 24.795 C 52 25.323, 52.698 27.426, 53.551 29.468 C 54.986 32.903, 58.841 48.015, 68.582 88.395 L 72.416 104.289 68.996 107.145 C 67.115 108.715, 65.056 110, 64.421 110 C 63.785 110, 60.877 102.688, 57.958 93.750 C 51.585 74.235, 45.711 61.107, 38.819 50.977 C 31.728 40.552, 25.520 36.158, 29.266 44.214 C 32.224 50.578, 38.816 71.615, 40.949 81.500 C 46.466 107.067, 48.449 114.130, 53.363 125.720 C 54.813 129.141, 56 132.741, 56 133.720 C 56 135.992, 48.528 142.488, 40.722 147.003 C 37.390 148.930, 34.476 151.073, 34.245 151.765 C 33.714 153.358, 40.269 160, 42.371 160 C 43.235 160, 46.092 157.967, 48.721 155.482 C 59.369 145.417, 70.556 140.610, 97.500 134.522 C 104.100 133.031, 114.149 130.729, 119.830 129.406 C 125.512 128.083, 131.024 127, 132.080 127 C 135.906 127, 133.871 125.104, 128.250 123.429 C 117.667 120.277, 100.092 120.531, 90 123.982 C 88.625 124.453, 86.243 125.112, 84.707 125.448 C 81.920 126.058, 81.918 126.054, 83.580 123.518 C 84.496 122.120, 86.315 120.708, 87.623 120.380 C 91.046 119.521, 90.547 117.338, 86.750 116.566 C 78.307 114.849, 77.381 114.836, 76.072 116.413 C 75.348 117.286, 74.135 118, 73.378 118 C 72.620 118, 72 118.450, 72 119 C 72 119.550, 71.213 119.981, 70.250 119.957 C 69.015 119.927, 69.785 118.786, 72.866 116.081 C 76.225 113.130, 79.567 111.533, 87.366 109.150 C 97.137 106.165, 113.786 102.013, 130.500 98.394 L 138.500 96.661 134.055 94.708 C 126.980 91.599, 115.275 91.301, 104.662 93.959 C 99.808 95.175, 95.592 95.925, 95.292 95.626 C 94.993 95.326, 96.380 94.119, 98.374 92.942 C 100.368 91.765, 102 90.448, 102 90.015 C 102 89.582, 99.907 88.913, 97.349 88.530 C 93.888 88.011, 91.706 88.280, 88.816 89.583 C 86.355 90.693, 84.585 90.985, 83.981 90.381 C 83.456 89.856, 80.042 80.669, 76.393 69.964 C 69.088 48.533, 63.933 37.063, 58.243 29.575 C 54.626 24.816, 52 22.805, 52 24.795 M 137.500 64.021 C 129.493 66.567, 122.113 71.368, 114.329 79.093 C 106.661 86.704, 107.656 88.343, 115.559 81.120 C 128.124 69.636, 143.084 65.536, 156.332 69.944 C 161.741 71.744, 163.818 73.115, 167.892 77.573 C 173.905 84.151, 176.598 90.878, 177.498 101.559 C 179.340 123.428, 169.817 142.210, 152.408 151.047 C 139.926 157.382, 124.735 156.113, 112.867 147.744 C 109.660 145.482, 106.797 143.870, 106.506 144.161 C 105.416 145.251, 116.473 155.084, 121.620 157.603 C 125.608 159.554, 129.101 160.352, 135.348 160.742 C 149.406 161.619, 159.219 158.222, 169.335 148.977 C 181.119 138.207, 186.107 125.358, 186.139 105.692 C 186.160 93.336, 184.713 86.147, 180.609 78.211 C 178.187 73.528, 169.401 66.294, 163.414 64.054 C 156.371 61.419, 145.724 61.405, 137.500 64.021 M 103 139.878 C 103 141.181, 105.583 143.084, 106.333 142.333 C 107.084 141.583, 105.181 139, 103.878 139 C 103.395 139, 103 139.395, 103 139.878" fill="#076835" fill-rule="evenodd"/>
          </svg>
          </div>
          <div class="logo__text">
            <span class="logo__name" data-i18n="company.name">Shabab Deira</span>
            <span class="logo__sub" data-i18n="company.tagline">Your Workforce Partner</span>
          </div>
        </div>
        <button class="drawer__close" id="drawerClose" aria-label="Close menu"><i data-lucide="x"></i></button>
      </div>
      <div class="drawer__links">
        <a href="index.html"><span data-i18n="nav.home">Home</span> <i data-lucide="chevron-right"></i></a>
        <a href="services.html"><span data-i18n="nav.services">Services (All 11)</span> <i data-lucide="chevron-right"></i></a>
        <a href="industries.html"><span data-i18n="nav.industries">Industries</span> <i data-lucide="chevron-right"></i></a>
        <a href="about.html"><span data-i18n="nav.aboutUs">About Us</span> <i data-lucide="chevron-right"></i></a>
        <a href="staff.html"><span data-i18n="nav.leadership">Our Team</span> <i data-lucide="chevron-right"></i></a>
        <a href="group.html"><span data-i18n="nav.ourGroup">Our Group</span> <i data-lucide="chevron-right"></i></a>
        <a href="index.html#why-us"><span data-i18n="nav.whyUs">Why Us</span> <i data-lucide="chevron-right"></i></a>
        <a href="faq.html"><span data-i18n="nav.faqShort">FAQ</span> <i data-lucide="chevron-right"></i></a>
        <a href="blog.php"><span data-i18n="nav.blog">Blog</span> <i data-lucide="chevron-right"></i></a>
        <a href="contact.html"><span data-i18n="nav.contact">Contact</span> <i data-lucide="chevron-right"></i></a>
      </div>
    </div>
    <div class="drawer__footer">
      <button class="lang-switch" style="width:100%; justify-content:center; padding:12px;">عربي</button>
      <a href="request-manpower.html" class="btn btn--primary btn--pill btn--lg" data-i18n="nav.requestManpower">Book Manpower</a>
      <a href="https://wa.me/971561359305" class="btn btn--whatsapp btn--pill" target="_blank" rel="noopener" data-bind="company.whatsapp">
        <i data-lucide="message-circle"></i> <span data-i18n="common.whatsappUs">WhatsApp Us</span>
      </a>
    </div>
  </div>

  <main>
    <!-- LUXURY HERO -->
    <section class="page-hero-luxury">
      <div class="container">
        <div class="page-hero-luxury__breadcrumb">
          <a href="index.html" data-i18n="nav.home">Home</a>
          <i data-lucide="chevron-right"></i>
          <span>Blog &amp; Insights</span>
        </div>
        <span class="page-hero-luxury__eyebrow"><i data-lucide="book-open"></i> <span>Workforce Insights</span></span>
        <h1 class="page-hero-luxury__title">UAE Workforce &amp; Logistics Intelligence</h1>
        <p class="page-hero-luxury__desc">Expert perspectives on MOHRE labor laws, Wages Protection System (WPS) compliance, warehouse supply chain optimization, and facility maintenance standards across Dubai.</p>
      </div>
    </section>

    <!-- BLOG SECTION -->
    <section class="section section--white" style="padding-top: 40px;">
      <div class="container">

        <!-- Search & Category Filters Bar -->
        <div class="blog-filters-bar">
          <div class="blog-category-pills">
            <a href="blog.php" class="blog-cat-pill <?php echo empty($selectedCategory) ? 'active' : ''; ?>">All Articles</a>
            <?php foreach ($categories as $cat): ?>
              <a href="blog.php?category=<?php echo urlencode($cat['slug']); ?>" class="blog-cat-pill <?php echo ($selectedCategory === $cat['slug'] || $selectedCategory === $cat['name']) ? 'active' : ''; ?>">
                <?php echo htmlspecialchars($cat['name']); ?>
              </a>
            <?php endforeach; ?>
          </div>

          <form method="GET" action="blog.php" class="blog-search-form">
            <i data-lucide="search"></i>
            <input type="text" name="search" placeholder="Search insights..." value="<?php echo htmlspecialchars($searchQuery); ?>">
          </form>
        </div>

        <!-- Featured Spotlight Hero Card (if available) -->
        <?php if ($featuredPost): ?>
          <div class="featured-post-hero">
            <div class="featured-post-hero__image-wrap">
              <img src="<?php echo htmlspecialchars($featuredPost['featured_image']); ?>" alt="<?php echo htmlspecialchars($featuredPost['title']); ?>" loading="lazy">
              <span class="featured-badge"><i data-lucide="sparkles"></i> Featured Insight</span>
            </div>
            <div class="featured-post-hero__content">
              <div class="blog-card__meta">
                <span class="tag-category"><?php echo htmlspecialchars($featuredPost['category_name'] ?: 'UAE Compliance'); ?></span>
                <span>•</span>
                <span><?php echo date('M d, Y', strtotime($featuredPost['created_at'])); ?></span>
                <span>•</span>
                <span><?php echo max(3, ceil(str_word_count(strip_tags($featuredPost['content'])) / 200)); ?> min read</span>
              </div>
              <h2 class="featured-post-hero__title">
                <a href="blog-post.php?slug=<?php echo urlencode($featuredPost['slug']); ?>"><?php echo htmlspecialchars($featuredPost['title']); ?></a>
              </h2>
              <p class="featured-post-hero__desc"><?php echo htmlspecialchars($featuredPost['description']); ?></p>
              <div class="featured-post-hero__footer">
                <div class="author-info">
                  <div class="author-avatar"><i data-lucide="user"></i></div>
                  <span class="author-name"><?php echo htmlspecialchars($featuredPost['author']); ?></span>
                </div>
                <a href="blog-post.php?slug=<?php echo urlencode($featuredPost['slug']); ?>" class="btn btn--primary btn--pill">
                  <span>Read Full Article</span> <i data-lucide="arrow-right"></i>
                </a>
              </div>
            </div>
          </div>
        <?php endif; ?>

        <!-- Articles Grid -->
        <div class="blog-grid" style="margin-top: 40px;">
          <?php if (empty($posts)): ?>
            <div style="grid-column: 1/-1; text-align: center; padding: 60px 20px; background: #F8FAFC; border-radius: 18px; border: 1px dashed #CBD5E1;">
              <i data-lucide="book-x" style="width: 48px; height: 48px; color: #94A3B8; margin-bottom: 12px;"></i>
              <h3 style="font-size: 1.25rem; font-weight: 700; color: #0F172A; margin-bottom: 6px;">No articles found</h3>
              <p style="color: #64748B; font-size: 0.95rem; margin-bottom: 16px;">Try adjusting your search query or selecting a different category.</p>
              <a href="blog.php" class="btn btn--outline btn--pill">View All Articles</a>
            </div>
          <?php else: ?>
            <?php foreach ($posts as $post): ?>
              <?php $readTime = max(2, ceil(str_word_count(strip_tags($post['content'])) / 200)); ?>
              <article class="blog-card">
                <a href="blog-post.php?slug=<?php echo urlencode($post['slug']); ?>" class="blog-card__thumb-link">
                  <img src="<?php echo htmlspecialchars($post['featured_image'] ?: 'https://shababdeira.com/assets/images/og-cover.jpg'); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" loading="lazy" class="blog-card__img">
                </a>
                <div class="blog-card__body">
                  <div class="blog-card__meta">
                    <span class="tag-category"><?php echo htmlspecialchars($post['category_name'] ?: 'Workforce'); ?></span>
                    <span>•</span>
                    <span><?php echo date('M d, Y', strtotime($post['created_at'])); ?></span>
                    <span>•</span>
                    <span><?php echo $readTime; ?> min read</span>
                  </div>
                  <h3 class="blog-card__title">
                    <a href="blog-post.php?slug=<?php echo urlencode($post['slug']); ?>"><?php echo htmlspecialchars($post['title']); ?></a>
                  </h3>
                  <p class="blog-card__excerpt"><?php echo htmlspecialchars($post['description']); ?></p>
                  <div class="blog-card__footer">
                    <span class="author-name" style="font-size: 0.8rem; color: #64748B;">By <?php echo htmlspecialchars($post['author']); ?></span>
                    <a href="blog-post.php?slug=<?php echo urlencode($post['slug']); ?>" class="blog-read-link">
                      <span>Read More</span> <i data-lucide="arrow-right"></i>
                    </a>
                  </div>
                </div>
              </article>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
          <div class="pagination-bar" style="margin-top: 48px; display: flex; justify-content: center; gap: 8px;">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
              <a href="blog.php?page=<?php echo $i; ?><?php echo !empty($selectedCategory) ? '&category=' . urlencode($selectedCategory) : ''; ?><?php echo !empty($searchQuery) ? '&search=' . urlencode($searchQuery) : ''; ?>" 
                 class="page-num-btn <?php echo ($i === $page) ? 'active' : ''; ?>">
                <?php echo $i; ?>
              </a>
            <?php endfor; ?>
          </div>
        <?php endif; ?>

      </div>
    </section>

    <!-- SUNSET DUBAI SKYLINE CTA BANNER -->
    <section class="cta-skyline-section">
      <div class="container">
        <div class="cta-skyline-banner">
          <img src="assets/images/dubai-sunset.jpg" alt="Dubai skyline" class="cta-skyline-bg" loading="lazy">
          <div class="cta-skyline-banner__overlay"></div>
          <div class="cta-skyline-banner__content">
            <span class="cta-skyline-banner__eyebrow"><i data-lucide="sparkles"></i> GET STARTED</span>
            <h2 class="cta-skyline-banner__title">Ready to scale your workforce?</h2>
            <p class="cta-skyline-banner__sub">Speak with our Deira operations desk to receive fully compliant rates and confirmed availability within 2 hours.</p>
            <div class="cta-skyline-banner__actions">
              <a href="request-manpower.html" class="btn btn--primary btn--pill btn--lg">Book Manpower</a>
              <a href="contact.html" class="btn btn--pill btn--pill-outline">Contact Operations</a>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <!-- FOOTER (DARK MOCKUP VARIANT) -->
  <footer class="footer-mockup" id="footer">
    <div class="container">
      <div class="footer-mockup__main">
        <a href="index.html" class="logo" aria-label="Shabab Deira Homepage">
          <div class="logo__mark">
            <svg viewBox="20 18 172 150" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
            <path d="M 52 24.795 C 52 25.323, 52.698 27.426, 53.551 29.468 C 54.986 32.903, 58.841 48.015, 68.582 88.395 L 72.416 104.289 68.996 107.145 C 67.115 108.715, 65.056 110, 64.421 110 C 63.785 110, 60.877 102.688, 57.958 93.750 C 51.585 74.235, 45.711 61.107, 38.819 50.977 C 31.728 40.552, 25.520 36.158, 29.266 44.214 C 32.224 50.578, 38.816 71.615, 40.949 81.500 C 46.466 107.067, 48.449 114.130, 53.363 125.720 C 54.813 129.141, 56 132.741, 56 133.720 C 56 135.992, 48.528 142.488, 40.722 147.003 C 37.390 148.930, 34.476 151.073, 34.245 151.765 C 33.714 153.358, 40.269 160, 42.371 160 C 43.235 160, 46.092 157.967, 48.721 155.482 C 59.369 145.417, 70.556 140.610, 97.500 134.522 C 104.100 133.031, 114.149 130.729, 119.830 129.406 C 125.512 128.083, 131.024 127, 132.080 127 C 135.906 127, 133.871 125.104, 128.250 123.429 C 117.667 120.277, 100.092 120.531, 90 123.982 C 88.625 124.453, 86.243 125.112, 84.707 125.448 C 81.920 126.058, 81.918 126.054, 83.580 123.518 C 84.496 122.120, 86.315 120.708, 87.623 120.380 C 91.046 119.521, 90.547 117.338, 86.750 116.566 C 78.307 114.849, 77.381 114.836, 76.072 116.413 C 75.348 117.286, 74.135 118, 73.378 118 C 72.620 118, 72 118.450, 72 119 C 72 119.550, 71.213 119.981, 70.250 119.957 C 69.015 119.927, 69.785 118.786, 72.866 116.081 C 76.225 113.130, 79.567 111.533, 87.366 109.150 C 97.137 106.165, 113.786 102.013, 130.500 98.394 L 138.500 96.661 134.055 94.708 C 126.980 91.599, 115.275 91.301, 104.662 93.959 C 99.808 95.175, 95.592 95.925, 95.292 95.626 C 94.993 95.326, 96.380 94.119, 98.374 92.942 C 100.368 91.765, 102 90.448, 102 90.015 C 102 89.582, 99.907 88.913, 97.349 88.530 C 93.888 88.011, 91.706 88.280, 88.816 89.583 C 86.355 90.693, 84.585 90.985, 83.981 90.381 C 83.456 89.856, 80.042 80.669, 76.393 69.964 C 69.088 48.533, 63.933 37.063, 58.243 29.575 C 54.626 24.816, 52 22.805, 52 24.795 M 137.500 64.021 C 129.493 66.567, 122.113 71.368, 114.329 79.093 C 106.661 86.704, 107.656 88.343, 115.559 81.120 C 128.124 69.636, 143.084 65.536, 156.332 69.944 C 161.741 71.744, 163.818 73.115, 167.892 77.573 C 173.905 84.151, 176.598 90.878, 177.498 101.559 C 179.340 123.428, 169.817 142.210, 152.408 151.047 C 139.926 157.382, 124.735 156.113, 112.867 147.744 C 109.660 145.482, 106.797 143.870, 106.506 144.161 C 105.416 145.251, 116.473 155.084, 121.620 157.603 C 125.608 159.554, 129.101 160.352, 135.348 160.742 C 149.406 161.619, 159.219 158.222, 169.335 148.977 C 181.119 138.207, 186.107 125.358, 186.139 105.692 C 186.160 93.336, 184.713 86.147, 180.609 78.211 C 178.187 73.528, 169.401 66.294, 163.414 64.054 C 156.371 61.419, 145.724 61.405, 137.500 64.021 M 103 139.878 C 103 141.181, 105.583 143.084, 106.333 142.333 C 107.084 141.583, 105.181 139, 103.878 139 C 103.395 139, 103 139.395, 103 139.878" fill="#FFFFFF" fill-rule="evenodd"/>
          </svg>
          </div>
          <div class="logo__text">
            <span class="logo__name" style="color:#FFF;" data-i18n="company.name">Shabab Deira</span>
            <span class="logo__sub" style="color:rgba(247,242,234,0.6);" data-i18n="company.tagline">Your Workforce Partner</span>
          </div>
        </a>

        <ul class="footer-mockup__nav">
          <li><a href="index.html" data-i18n="nav.home">Home</a></li>
          <li><a href="about.html" data-i18n="nav.about">About</a></li>
          <li><a href="services.html" data-i18n="nav.services">Services</a></li>
          <li><a href="industries.html" data-i18n="nav.industries">Industries</a></li>
          <li><a href="staff.html" data-i18n="nav.team">Our Team</a></li>
          <li><a href="group.html" data-i18n="nav.group">Our Group</a></li>
          <li><a href="index.html#why-us" data-i18n="nav.whyUs">Why Us</a></li>
          <li><a href="faq.html" data-i18n="nav.faq">FAQ</a></li>
          <li><a href="request-manpower.html" data-i18n="nav.requestManpower">Book Manpower</a></li>
          <li><a href="blog.php" data-i18n="nav.blog">Blog</a></li>
          <li><a href="contact.html" data-i18n="nav.contact">Contact</a></li>
        </ul>

        <div class="footer-mockup__socials">
          <a href="https://linkedin.com" target="_blank" rel="noopener" class="footer-mockup__social-btn" aria-label="LinkedIn"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg></a>
          <a href="https://wa.me/971561359305" target="_blank" rel="noopener" class="footer-mockup__social-btn" aria-label="WhatsApp" data-bind="company.whatsapp"><svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2m.01 1.67c2.2 0 4.26.86 5.82 2.42a8.23 8.23 0 0 1 2.41 5.83c0 4.54-3.7 8.24-8.24 8.24-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.19 8.19 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.24-8.24m4.52 11.66c-.25-.13-1.47-.72-1.7-.81-.23-.08-.39-.13-.56.13-.17.25-.64.81-.79.97-.14.17-.29.19-.54.06-.25-.13-1.06-.39-2.02-1.25-.75-.67-1.26-1.5-1.4-1.76-.15-.25-.02-.39.11-.51.11-.11.25-.29.38-.44.13-.14.17-.25.25-.42.08-.17.04-.31-.02-.44-.06-.13-.56-1.35-.77-1.85-.2-.49-.41-.42-.56-.43h-.48c-.17 0-.44.06-.67.31-.23.25-.87.85-.87 2.08 0 1.22.89 2.41 1.02 2.58.12.17 1.76 2.68 4.25 3.76.59.26 1.06.41 1.42.53.6.19 1.14.16 1.57.1.48-.07 1.47-.6 1.68-1.18.21-.58.21-1.08.15-1.18-.06-.1-.23-.17-.48-.29"/></svg></a>
          <a href="https://instagram.com" target="_blank" rel="noopener" class="footer-mockup__social-btn" aria-label="Instagram"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg></a>
        </div>
      </div>

      <div class="footer-mockup__bottom">
        <div>&copy; 2026 Shabab Deira. All rights reserved.</div>
        <div class="footer-mockup__bottom-links">
          <a href="privacy-policy.html" data-i18n="common.privacy">Privacy Policy</a>
          <a href="terms.html" data-i18n="common.terms">Terms &amp; Conditions</a>
          <span>Stronger Businesses</span>
        </div>
      </div>
    </div>
  </footer>

  <!-- Mobile Bottom Bar -->
  <div class="mobile-bar">
    <div class="mobile-bar__inner">
      <a href="tel:+971561359305" class="mobile-bar__btn mobile-bar__btn--call" data-bind="company.phone"><i data-lucide="phone"></i><span data-i18n="common.call">Call</span></a>
      <a href="https://wa.me/971561359305" class="mobile-bar__btn mobile-bar__btn--wa" target="_blank" rel="noopener" data-bind="company.whatsapp"><svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2m.01 1.67c2.2 0 4.26.86 5.82 2.42a8.23 8.23 0 0 1 2.41 5.83c0 4.54-3.7 8.24-8.24 8.24-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.19 8.19 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.24-8.24m4.52 11.66c-.25-.13-1.47-.72-1.7-.81-.23-.08-.39-.13-.56.13-.17.25-.64.81-.79.97-.14.17-.29.19-.54.06-.25-.13-1.06-.39-2.02-1.25-.75-.67-1.26-1.5-1.4-1.76-.15-.25-.02-.39.11-.51.11-.11.25-.29.38-.44.13-.14.17-.25.25-.42.08-.17.04-.31-.02-.44-.06-.13-.56-1.35-.77-1.85-.2-.49-.41-.42-.56-.43h-.48c-.17 0-.44.06-.67.31-.23.25-.87.85-.87 2.08 0 1.22.89 2.41 1.02 2.58.12.17 1.76 2.68 4.25 3.76.59.26 1.06.41 1.42.53.6.19 1.14.16 1.57.1.48-.07 1.47-.6 1.68-1.18.21-.58.21-1.08.15-1.18-.06-.1-.23-.17-.48-.29"/></svg><span>WhatsApp</span></a>
      <a href="request-manpower.html" class="mobile-bar__btn mobile-bar__btn--req"><i data-lucide="clipboard-list"></i><span data-i18n="nav.requestManpower">Book</span></a>
    </div>
  </div>

  <!-- Floating Contact Buttons (Call & WhatsApp) -->
  <div class="floating-contact">
    <a href="https://wa.me/971561359305" class="floating-btn floating-btn--wa" target="_blank" rel="noopener" aria-label="Chat on WhatsApp" data-bind="company.whatsapp">
      <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" aria-hidden="true"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2m.01 1.67c2.2 0 4.26.86 5.82 2.42a8.23 8.23 0 0 1 2.41 5.83c0 4.54-3.7 8.24-8.24 8.24-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.19 8.19 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.24-8.24m4.52 11.66c-.25-.13-1.47-.72-1.7-.81-.23-.08-.39-.13-.56.13-.17.25-.64.81-.79.97-.14.17-.29.19-.54.06-.25-.13-1.06-.39-2.02-1.25-.75-.67-1.26-1.5-1.4-1.76-.15-.25-.02-.39.11-.51.11-.11.25-.29.38-.44.13-.14.17-.25.25-.42.08-.17.04-.31-.02-.44-.06-.13-.56-1.35-.77-1.85-.2-.49-.41-.42-.56-.43h-.48c-.17 0-.44.06-.67.31-.23.25-.87.85-.87 2.08 0 1.22.89 2.41 1.02 2.58.12.17 1.76 2.68 4.25 3.76.59.26 1.06.41 1.42.53.6.19 1.14.16 1.57.1.48-.07 1.47-.6 1.68-1.18.21-.58.21-1.08.15-1.18-.06-.1-.23-.17-.48-.29"/></svg>
      <span data-i18n="common.whatsappUs">WhatsApp Us</span>
    </a>
    <a href="tel:+971561359305" class="floating-btn floating-btn--call" aria-label="Call Shabab Deira" data-bind="company.phone">
      <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
      <span data-bind="company.phone">+971 56 135 9305</span>
    </a>
  </div>

  <script src="js/i18n.js?v=2.2"></script>
  <script src="js/main.js?v=2.2"></script>

  <style>
    /* Public Blog Specific Styles */
    .blog-filters-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 20px;
      flex-wrap: wrap;
      margin-bottom: 32px;
      padding-bottom: 24px;
      border-bottom: 1px solid #E2E8F0;
    }
    .blog-category-pills {
      display: flex;
      align-items: center;
      gap: 10px;
      flex-wrap: wrap;
    }
    .blog-cat-pill {
      display: inline-block;
      padding: 8px 16px;
      border-radius: 9999px;
      background: #F1F5F9;
      color: #334155;
      font-size: 0.85rem;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.2s;
    }
    .blog-cat-pill:hover,
    .blog-cat-pill.active {
      background: #0F172A;
      color: #FFFFFF;
    }
    .blog-search-form {
      position: relative;
      display: flex;
      align-items: center;
      min-width: 260px;
    }
    .blog-search-form i {
      position: absolute;
      left: 14px;
      width: 16px;
      height: 16px;
      color: #94A3B8;
    }
    .blog-search-form input {
      width: 100%;
      padding: 9px 14px 9px 38px;
      border: 1px solid #CBD5E1;
      border-radius: 9999px;
      font-size: 0.88rem;
      outline: none;
      transition: border-color 0.2s;
    }
    .blog-search-form input:focus {
      border-color: #FF9F1C;
    }

    /* Featured Post Spotlight */
    .featured-post-hero {
      display: grid;
      grid-template-columns: 1.1fr 1fr;
      background: #FFFFFF;
      border: 1px solid rgba(226, 232, 240, 0.9);
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.08);
      margin-bottom: 40px;
    }
    .featured-post-hero__image-wrap {
      position: relative;
      min-height: 380px;
      background: #0F172A;
    }
    .featured-post-hero__image-wrap img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .featured-badge {
      position: absolute;
      top: 18px;
      left: 18px;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 14px;
      background: rgba(15, 23, 42, 0.85);
      backdrop-filter: blur(8px);
      border: 1px solid rgba(255, 159, 28, 0.6);
      color: #FF9F1C;
      font-size: 0.78rem;
      font-weight: 700;
      border-radius: 9999px;
      text-transform: uppercase;
      letter-spacing: 0.04em;
    }
    .featured-post-hero__content {
      padding: 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }
    .featured-post-hero__title {
      font-family: var(--font-display);
      font-size: 1.85rem;
      font-weight: 800;
      line-height: 1.25;
      color: #0F172A;
      margin: 12px 0 16px;
    }
    .featured-post-hero__title a {
      color: inherit;
      text-decoration: none;
      transition: color 0.2s;
    }
    .featured-post-hero__title a:hover {
      color: #D97706;
    }
    .featured-post-hero__desc {
      color: #64748B;
      font-size: 0.98rem;
      line-height: 1.65;
      margin-bottom: 24px;
    }
    .featured-post-hero__footer {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding-top: 20px;
      border-top: 1px solid #F1F5F9;
    }
    .author-info {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .author-avatar {
      width: 34px;
      height: 34px;
      border-radius: 50%;
      background: #F1F5F9;
      color: #64748B;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .author-avatar i { width: 18px; height: 18px; }
    .author-name {
      font-size: 0.88rem;
      font-weight: 700;
      color: #334155;
    }

    /* Blog Grid */
    .blog-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 30px;
    }
    .blog-card {
      background: #FFFFFF;
      border: 1px solid rgba(226, 232, 240, 0.9);
      border-radius: 18px;
      overflow: hidden;
      box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05);
      display: flex;
      flex-direction: column;
      transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease;
    }
    .blog-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 16px 36px -6px rgba(15, 23, 42, 0.12);
      border-color: rgba(255, 159, 28, 0.5);
    }
    .blog-card__thumb-link {
      display: block;
      height: 220px;
      overflow: hidden;
      background: #0F172A;
    }
    .blog-card__img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.4s ease;
    }
    .blog-card:hover .blog-card__img {
      transform: scale(1.05);
    }
    .blog-card__body {
      padding: 24px;
      display: flex;
      flex-direction: column;
      flex-grow: 1;
    }
    .blog-card__meta {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 0.78rem;
      color: #94A3B8;
      margin-bottom: 10px;
      font-weight: 500;
    }
    .blog-card__title {
      font-family: var(--font-display);
      font-size: 1.25rem;
      font-weight: 700;
      line-height: 1.35;
      color: #0F172A;
      margin-bottom: 10px;
    }
    .blog-card__title a {
      color: inherit;
      text-decoration: none;
      transition: color 0.2s;
    }
    .blog-card__title a:hover {
      color: #D97706;
    }
    .blog-card__excerpt {
      color: #64748B;
      font-size: 0.9rem;
      line-height: 1.6;
      margin-bottom: 20px;
      flex-grow: 1;
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }
    .blog-card__footer {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding-top: 14px;
      border-top: 1px solid #F1F5F9;
    }
    .blog-read-link {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      color: #D97706;
      font-weight: 700;
      font-size: 0.85rem;
      text-decoration: none;
    }
    .blog-read-link:hover {
      text-decoration: underline;
    }

    .page-num-btn {
      width: 40px;
      height: 40px;
      border-radius: 10px;
      border: 1px solid #E2E8F0;
      background: #FFFFFF;
      color: #334155;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      text-decoration: none;
      transition: all 0.2s;
    }
    .page-num-btn:hover,
    .page-num-btn.active {
      background: #FF9F1C;
      color: #0F172A;
      border-color: #FF9F1C;
    }

    @media (max-width: 991px) {
      .featured-post-hero {
        grid-template-columns: 1fr;
      }
      .blog-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }
    @media (max-width: 640px) {
      .blog-grid {
        grid-template-columns: 1fr;
      }
      .featured-post-hero__content {
        padding: 24px;
      }
      .featured-post-hero__title {
        font-size: 1.4rem;
      }
    }
  </style>

  <script>
    if (window.lucide) lucide.createIcons();
  </script>
</body>
</html>
