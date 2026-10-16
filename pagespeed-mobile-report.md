# PageSpeed Insights Mobile Report: shababdeira.com

- **Analyzed URL:** https://shababdeira.com
- **Environment:** Emulated Moto G Power with Lighthouse 13.5.0 (HeadlessChromium 153.0.8010.36)
- **Network Conditions:** Slow 4G throttling, single page session
- **Capture Time:** Oct 16, 2026, 6:13 PM GMT+4

## 1. High-Level Scores

| Category | Score | Status |
|---|---|---|
| Performance | 71 / 100 | Needs Improvement |
| Accessibility | 89 / 100 | Needs Improvement |
| Best Practices | 100 / 100 | Passed |
| SEO | 100 / 100 | Passed |
| Agentic Browsing | 1 / 2 | Experimental / In Development |

## 2. Core Metrics Breakdown

| Metric | Measured Value | Scoring Weight Impact |
|---|---|---|
| First Contentful Paint (FCP) | 3.1 s | +5 |
| Largest Contentful Paint (LCP) | 4.8 s | +8 |
| Total Blocking Time (TBT) | 10 ms | +30 |
| Cumulative Layout Shift (CLS) | 0.057 | +25 |
| Speed Index (SI) | 6.1 s | +5 |

### LCP Subpart Breakdown

- **Time to First Byte (TTFB):** 40 ms
- **Element Render Delay:** 3,250 ms
- **LCP Element:** `<p class="hero-mockup__desc" data-i18n="home.heroDesc">` ("Since 2012, Shabab Deira has supplied businesses across the UAE with dependable...")

### Cumulative Layout Shift (CLS) Culprits

| Element / Source | Shift Score |
|---|---|
| `<div class="hero-mockup__actions">` (Book Manpower / WhatsApp Us) | 0.040 |
| `<div class="hero-mockup__actions">` (Book Manpower / WhatsApp Us) | 0.017 |
| Web Font: fonts.gstatic.com/...woff2 | Layout Shift contributor |
| Web Font: fonts.gstatic.com/...woff2 | Layout Shift contributor |

## 3. Performance Diagnostics & Opportunities

### A. Render-Blocking Resources (Estimated Savings: 2,920 ms)

| Resource URL | Origin | Transfer Size | Duration |
|---|---|---|---|
| /js/lucide.min.js | 1st Party | 96.8 KiB | 2,910 ms |
| /css/style.css?v=3.0 | 1st Party | 25.2 KiB | 1,370 ms |
| /css2?family=... | Google Fonts CDN | 1.9 KiB | 750 ms |

### B. Improve Image Delivery (Estimated Savings: 931 KiB)

| Asset | Resource Size | Est. Savings | Issues Identified |
|---|---|---|---|
| warehouse-worker.jpg | 828.4 KiB | 739.9 KiB | Modern format/compression: save 699.0 KiB; Resize from 896x887 to 637x853: save 262.0 KiB |
| hero-supervisor.jpg | 269.1 KiB | 190.8 KiB | Modern format/compression: save 190.8 KiB |

### C. Critical Path Latency & Network Dependency Chain

Maximum critical path latency: 3,720 ms

- shababdeira.com (1,238 ms, 12.0 KiB)
- fonts.googleapis.com/css2?... (1,249 ms, 1.87 KiB)
- fonts.gstatic.com/...woff2 (3,259 ms, 32.26 KiB & 3,258 ms, 27.42 KiB)
- /css/style.css?v=3.0 (3,114 ms, 25.23 KiB)
- /js/lucide.min.js (2,842 ms, 96.82 KiB)
- /js/i18n.js?v=2.2 (2,616 ms, 32.62 KiB)
- /js/main.js?v=2.2 (1,796 ms, 8.07 KiB)
- /data/site-content.json (3,720 ms, 27.00 KiB)

### D. Code & Script Optimization

- **Reduce Unused CSS:** Est. savings of 17.2 KiB out of 24.5 KiB on /css/style.css?v=3.0.
- **Minify JavaScript:** Est. savings of 10.7 KiB (/js/i18n.js?v=2.2 – 8.4 KiB, /js/main.js?v=2.2 – 2.3 KiB).
- **Minify CSS:** Est. savings of 6.1 KiB on /css/style.css?v=3.0.
- **Long Tasks on Main Thread:** /js/lucide.min.js: 59 ms duration at 4,486 ms; shababdeira.com: 50 ms duration at 1,322 ms.
- **DOM Size:** 873 total elements, max depth of 12.

## 4. Accessibility Audits (Score: 89)

### Failing Checks

**Prohibited ARIA attributes on generic containers:**

- `<div class="stars-row" aria-label="5 out of 5 stars">` (Found across 3 elements in testimonials).

**Color Contrast Failures:**

- Section eyebrows: OUR SERVICES, INDUSTRIES WE SERVE, HOW IT WORKS, TESTIMONIALS.
- Action links: View All Services, Explore All Industries.
- Testimonial sector tags: Logistics Partner — Dubai, Facilities & Operations — UAE, Retail Chain — Abu Dhabi.
- Mobile fixed actions:
  - WhatsApp inside `<a href="https://wa.me/971561359305" class="mobile-bar__btn mobile-bar__btn--wa">`
  - Book Manpower inside `<a href="request-manpower.html" class="mobile-bar__btn mobile-bar__btn--req">`

**Non-sequential heading hierarchy:**

- Heading structure skips directly from `<h3 data-i18n="home.trustCard1Title">` (10+ Years Experience) to `<h4 data-i18n="home.whyCard1Title">` (100% MOL & MOHRE Compliance).

## 5. Trust, Security & Best Practices (Score: 100)

All standard automated criteria passed, but Lighthouse highlighted the following security configuration recommendations:

- **Missing Content Security Policy (CSP):** Lacks script-src and object-src directives.
- **Missing HSTS Header:** HTTP Strict Transport Security not configured.
- **Missing COOP Header:** Cross-Origin-Opener-Policy missing.
- **Missing Trusted Types:** CSP lacks require-trusted-types-for 'script'.
- **Broken Source Map:** /js/lucide.min.js.map returns an invalid JSON document (HTML `<!DOCTYPE...`).

## 6. Agentic Browsing (Score: 1/2)

- **Failed Check:** Accessibility tree is not well-formed due to prohibited ARIA attributes on `<div class="stars-row" aria-label="5 out of 5 stars">`.
- **Passed Check:** Cumulative Layout Shift (0.057).
- **Unscored / Not Implemented:** WebMCP form coverage, registered WebMCP tools, WebMCP schemas, llms.txt, and ai-catalog.json.
