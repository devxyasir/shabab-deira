/**
 * Removes hardcoded site domains from all HTML pages:
 * - canonical href  → relative filename (i18n.js rewrites it from company.siteUrl at runtime)
 * - og:url content  → removed (i18n.js sets it)
 * - JSON-LD "url": "https://shababdeira.ae/" lines → removed
 * The domain now lives ONLY in data/site-content.json (company.siteUrl).
 * Run: node tools/strip-domain.js
 */
const fs = require('fs');
const path = require('path');
const root = path.join(__dirname, '..');
const pages = fs.readdirSync(root).filter(f => f.endsWith('.html'));

pages.forEach(function (page) {
  const p = path.join(root, page);
  let html = fs.readFileSync(p, 'utf8');
  const before = html;
  const file = page === 'index.html' ? 'index.html' : page;

  // canonical: absolute → relative (JS fills absolute)
  html = html.replace(/<link rel="canonical" href="https:\/\/shababdeira\.ae\/?[^"]*"/,
    '<link rel="canonical" href="' + file + '"');

  // og:url: drop the hardcoded absolute URL (JS sets it)
  html = html.replace(/\s*<meta property="og:url" content="https:\/\/shababdeira\.ae[^"]*">/g, '');

  // JSON-LD url field
  html = html.replace(/\s*"url": "https:\/\/shababdeira\.ae\/?",?/g, '');

  if (html !== before) {
    fs.writeFileSync(p, html, 'utf8');
    console.log(page + ': stripped');
  } else {
    console.log(page + ': no domain found');
  }
});
