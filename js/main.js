/**
 * Shabab Deira — Main JS Engine
 * Handles Lucide icons, mobile navigation drawer, sticky header,
 * bilingual FAQ accordion & search, validated form submission,
 * bilingual toast notifications, and WhatsApp pre-filled link builder.
 */
(function () {
  'use strict';

  // ── Lucide Icons ──
  function initIcons() {
    if (window.lucide && typeof window.lucide.createIcons === 'function') {
      window.lucide.createIcons();
    }
  }

  // Note: the global image-error fallback listener lives in an inline
  // <script> at the very top of <head> in every page (not here) — it has
  // to be registered before the parser reaches any <img> tags, otherwise
  // a fast-failing request can fire its error event before this file
  // (loaded at the end of <body>) ever attaches a listener.

  // ── Mobile Navigation Drawer ──
  function initMobileNav() {
    var toggle = document.getElementById('mobileNavToggle');
    var drawer = document.getElementById('mobileDrawer');
    var overlay = document.getElementById('drawerOverlay');
    var close = document.getElementById('drawerClose');
    if (!toggle || !drawer || !overlay) return;

    function open() {
      drawer.classList.add('open');
      overlay.classList.add('open');
      document.body.style.overflow = 'hidden';
      if (close) close.focus();
    }
    function shut() {
      drawer.classList.remove('open');
      overlay.classList.remove('open');
      document.body.style.overflow = '';
    }

    toggle.addEventListener('click', open);
    if (close) close.addEventListener('click', shut);
    overlay.addEventListener('click', shut);
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && drawer.classList.contains('open')) shut();
    });
  }

  // ── Sticky Header ──
  function initStickyHeader() {
    var header = document.querySelector('.header');
    if (!header) return;
    function check() {
      var scrolled = window.scrollY > 80;
      header.classList.toggle('scrolled', window.scrollY > 40);
      document.body.classList.toggle('header-scrolled', scrolled);
    }
    window.addEventListener('scroll', check, { passive: true });
    check();
  }

  // ── Stat Count-Up (stat band numerals) ──
  function initStatCountUp() {
    var numbers = document.querySelectorAll('.stats-band__number, .stat-mockup-num');
    if (!numbers.length) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    var animate = function (el) {
      var target = parseInt(el.textContent.replace(/[^\d]/g, ''), 10);
      if (isNaN(target) || !el.textContent.match(/\d/)) return;
      var start = null;
      var duration = 800;
      var prefix = el.textContent.match(/^[^\d]*/)[0];
      var suffix = el.textContent.match(/[^\d]*$/)[0];
      var step = function (ts) {
        if (!start) start = ts;
        var p = Math.min((ts - start) / duration, 1);
        var eased = 1 - Math.pow(1 - p, 3);
        el.textContent = prefix + Math.round(target * eased) + suffix;
        if (p < 1) requestAnimationFrame(step);
      };
      requestAnimationFrame(step);
    };

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          animate(entry.target);
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.4 });

    numbers.forEach(function (el) { observer.observe(el); });
  }

  // ── FAQ Accordion & Search ──
  function initFaq() {
    var faqContainer = document.querySelector('.faq-list');
    if (!faqContainer) return;

    // Use event delegation for dynamically rendered FAQs
    faqContainer.onclick = function (e) {
      var btn = e.target.closest('.faq-question');
      if (!btn) return;
      var item = btn.closest('.faq-item');
      if (!item) return;

      var wasActive = item.classList.contains('active');
      faqContainer.querySelectorAll('.faq-item').forEach(function (o) {
        if (o !== item) o.classList.remove('active');
      });
      item.classList.toggle('active', !wasActive);
    };

    var search = document.getElementById('faqSearch');
    if (search && !search.dataset.bound) {
      search.dataset.bound = 'true';
      search.addEventListener('input', function () {
        var term = search.value.toLowerCase().trim();
        faqContainer.querySelectorAll('.faq-item').forEach(function (item) {
          var text = item.textContent.toLowerCase();
          item.style.display = (!term || text.indexOf(term) !== -1) ? '' : 'none';
        });
      });
    }
  }

  // ── Toast Notification (Bilingual Support) ──
  function showToast(messageEn, messageAr, type) {
    var isAr = (localStorage.getItem('shabab_lang') || 'en') === 'ar';
    var message = (isAr && messageAr) ? messageAr : messageEn;

    var container = document.querySelector('.toast-container');
    if (!container) {
      container = document.createElement('div');
      container.className = 'toast-container';
      document.body.appendChild(container);
    }

    var toast = document.createElement('div');
    toast.className = 'toast' + (type ? ' toast-' + type : '');
    toast.textContent = message;
    container.appendChild(toast);

    requestAnimationFrame(function () {
      toast.classList.add('show');
    });

    setTimeout(function () {
      toast.classList.remove('show');
      setTimeout(function () { toast.remove(); }, 300);
    }, 4000);
  }

  // ── Form Validation & Submission ──
  function initForms() {
    // 1. Manpower Intake Form (Direct to WhatsApp & Notification)
    var mpForm = document.getElementById('mpForm');
    if (mpForm && !mpForm.dataset.bound) {
      mpForm.dataset.bound = 'true';
      mpForm.addEventListener('submit', function (e) {
        e.preventDefault();
        var valid = true;

        mpForm.querySelectorAll('[required]').forEach(function (field) {
          var group = field.closest('.form-group');
          if (!field.value.trim()) {
            valid = false;
            field.classList.add('error');
            if (group) group.classList.add('has-error');
          } else {
            field.classList.remove('error');
            if (group) group.classList.remove('has-error');
          }
        });

        if (!valid) {
          showToast(
            'Please complete all required fields.',
            'يرجى ملء جميع الحقول المطلوبة.',
            'error'
          );
          return;
        }

        var isAr = (localStorage.getItem('shabab_lang') || 'en') === 'ar';
        var company = mpForm.querySelector('[name="company"]');
        var contact = mpForm.querySelector('[name="contact"]');
        var emailPhone = mpForm.querySelector('[name="email_or_phone"]');
        var whatsapp = mpForm.querySelector('[name="whatsapp"]');
        var trade = mpForm.querySelector('[name="trade"]');
        var tradeLabel = trade && trade.selectedIndex >= 0 ? trade.options[trade.selectedIndex].text : '';
        var workers = mpForm.querySelector('[name="workers"]');
        var location = mpForm.querySelector('[name="location"]');
        var locationLabel = location && location.selectedIndex >= 0 ? location.options[location.selectedIndex].text : '';
        var duration = mpForm.querySelector('[name="duration"]');
        var durationLabel = duration && duration.selectedIndex >= 0 ? duration.options[duration.selectedIndex].text : '';
        var message = mpForm.querySelector('[name="message"]');

        var data = window.ShababI18n ? window.ShababI18n.getData() : null;
        var waNum = (data && data.company && data.company.whatsappNum) ? data.company.whatsappNum : '971500000000';

        var text = '';
        if (isAr) {
          text = 'طلب توريد عمالة — شباب ديرة\n\n' +
            'الشركة: ' + (company ? company.value : '') + '\n' +
            'المسؤول: ' + (contact ? contact.value : '') + '\n' +
            'وسيلة التواصل: ' + (emailPhone ? emailPhone.value : '') + '\n' +
            (whatsapp && whatsapp.value ? 'واتساب: ' + whatsapp.value + '\n' : '') +
            'الخدمة المطلوبة: ' + tradeLabel + '\n' +
            'العدد: ' + (workers ? workers.value : '') + '\n' +
            'الموقع: ' + locationLabel + '\n' +
            'المدة: ' + durationLabel + '\n' +
            'تفاصيل العمل: ' + (message ? message.value : '');
        } else {
          text = 'Manpower Requirement — Shabab Deira\n\n' +
            'Company: ' + (company ? company.value : '') + '\n' +
            'Contact Person: ' + (contact ? contact.value : '') + '\n' +
            'Contact Info: ' + (emailPhone ? emailPhone.value : '') + '\n' +
            (whatsapp && whatsapp.value ? 'WhatsApp: ' + whatsapp.value + '\n' : '') +
            'Service Required: ' + tradeLabel + '\n' +
            'Workers Count: ' + (workers ? workers.value : '') + '\n' +
            'Work Location: ' + locationLabel + '\n' +
            'Duration: ' + durationLabel + '\n' +
            'Scope / Details: ' + (message ? message.value : '');
        }

        var url = 'https://wa.me/' + waNum + '?text=' + encodeURIComponent(text);
        window.open(url, '_blank');

        showToast(
          'Your requirement has been sent. Our operations team will respond promptly.',
          'تم إرسال متطلباتكم بنجاح. سيتواصل معكم فريق العمليات قريباً.',
          'success'
        );
        mpForm.reset();
      });
    }

    // 2. General Contact Form
    var contactForm = document.getElementById('contactForm');
    if (contactForm && !contactForm.dataset.bound) {
      contactForm.dataset.bound = 'true';
      contactForm.addEventListener('submit', function (e) {
        e.preventDefault();
        var valid = true;

        contactForm.querySelectorAll('[required]').forEach(function (field) {
          var group = field.closest('.form-group');
          if (!field.value.trim()) {
            valid = false;
            field.classList.add('error');
            if (group) group.classList.add('has-error');
          } else {
            field.classList.remove('error');
            if (group) group.classList.remove('has-error');
          }
        });

        if (!valid) {
          showToast(
            'Please complete all required fields.',
            'يرجى ملء جميع الحقول المطلوبة.',
            'error'
          );
          return;
        }

        showToast(
          'Your message has been sent to our operations desk. We will get in touch shortly.',
          'تم إرسال رسالتكم بنجاح إلى مكتب العمليات. سنتواصل معكم قريباً.',
          'success'
        );
        contactForm.reset();
      });
    }

    // Dynamic field error clearance
    document.querySelectorAll('form[data-validate] [required]').forEach(function (field) {
      field.addEventListener('input', function () {
        if (field.value.trim()) {
          field.classList.remove('error');
          var group = field.closest('.form-group');
          if (group) group.classList.remove('has-error');
        }
      });
    });
  }

  // ── Scroll Reveal ──
  // Only the services image-panel wipe uses this now — one deliberate
  // motion moment, not a blanket per-section treatment. A short timeout
  // fallback guarantees it can never get stuck hidden if the observer
  // misses it (fast scroll, slow JS, etc.) — content always wins over
  // the animation.
  function initReveal() {
    var els = document.querySelectorAll('.reveal--wipe');
    if (!els.length) return;

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      els.forEach(function (el) { el.classList.add('visible'); });
      return;
    }

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

    els.forEach(function (el) {
      observer.observe(el);
      // Safety net: force-reveal after 2.5s no matter what.
      setTimeout(function () { el.classList.add('visible'); }, 2500);
    });
  }

  // ── Interactive Services List (Homepage) ──
  function initServicesInteractive() {
    var list = document.querySelector('.services__list');
    var panel = document.getElementById('servicesPreviewImg');
    if (!list) return;

    var rows = list.querySelectorAll('.services__row');
    var isMobile = function () { return window.innerWidth <= 1024; };

    rows.forEach(function (row) {
      // Desktop: hover swaps image
      row.addEventListener('mouseenter', function () {
        if (isMobile()) return;
        rows.forEach(function (r) { r.classList.remove('active'); });
        row.classList.add('active');
        if (panel) {
          var src = row.getAttribute('data-image');
          if (src && panel.src !== src) {
            panel.style.opacity = '0';
            setTimeout(function () {
              panel.src = src;
              panel.style.opacity = '1';
            }, 200);
          }
        }
      });

      // Mobile: tap toggles accordion
      row.addEventListener('click', function () {
        if (!isMobile()) return;
        var wasExpanded = row.classList.contains('expanded');
        rows.forEach(function (r) { r.classList.remove('expanded'); });
        if (!wasExpanded) {
          row.classList.add('expanded');
        }
        rows.forEach(function (r) { r.classList.remove('active'); });
        row.classList.add('active');
      });

      // Keyboard: enter/space activates
      row.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          row.click();
          if (!isMobile() && panel) {
            var src = row.getAttribute('data-image');
            if (src) {
              rows.forEach(function (r) { r.classList.remove('active'); });
              row.classList.add('active');
              panel.style.opacity = '0';
              setTimeout(function () {
                panel.src = src;
                panel.style.opacity = '1';
              }, 200);
            }
          }
        }
      });
    });
  }

  // ── Testimonials Carousel Navigation ──
  function initTestimonialsNav() {
    var prev = document.getElementById('testimonialPrev');
    var next = document.getElementById('testimonialNext');
    var grid = document.querySelector('.testimonials-grid');
    if (!prev || !next || !grid) return;

    prev.addEventListener('click', function () {
      var item = grid.querySelector('.testimonial-card-item');
      var step = item ? item.offsetWidth + 24 : 360;
      grid.scrollBy({ left: -step, behavior: 'smooth' });
    });
    next.addEventListener('click', function () {
      var item = grid.querySelector('.testimonial-card-item');
      var step = item ? item.offsetWidth + 24 : 360;
      grid.scrollBy({ left: step, behavior: 'smooth' });
    });
  }

  // ── Initialize All Components ──
  document.addEventListener('DOMContentLoaded', function () {
    initIcons();
    initMobileNav();
    initStickyHeader();
    initFaq();
    initForms();
    initReveal();
    initServicesInteractive();
    initStatCountUp();
    initTestimonialsNav();
  });

  // Re-run on language change
  window.addEventListener('shabab:langChanged', function () {
    initIcons();
    initFaq();
    initServicesInteractive();
  });
})();
