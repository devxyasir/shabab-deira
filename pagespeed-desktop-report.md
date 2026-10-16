# PageSpeed Insights Desktop Report: shababdeira.com

- **Analyzed URL:** https://shababdeira.com/
- **Environment:** Emulated Desktop with Lighthouse 13.5.0 (HeadlessChromium 153.0.8010.36)
- **Capture Time:** Oct 16, 2026, 6:13 PM GMT+4

## 1. High-Level Scores

| Category | Score | Status |
|---|---|---|
| Performance | 90 / 100 | Good |
| Accessibility | 89 / 100 | Needs Improvement |
| Best Practices | 100 / 100 | Passed |
| SEO | 100 / 100 | Passed |
| Agentic Browsing | 1 / 2 | Experimental / In Development |

## 2. Core Metrics Breakdown

| Metric | Measured Value | Scoring Weight Impact |
|---|---|---|
| First Contentful Paint (FCP) | 1.0 s | +8 |
| Largest Contentful Paint (LCP) | 1.4 s | +21 |
| Total Blocking Time (TBT) | 0 ms | +30 |
| Cumulative Layout Shift (CLS) | 0.036 | +25 |
| Speed Index (SI) | 2.2 s | +5 |

### LCP Subpart Breakdown

- **Time to First Byte (TTFB):** 40 ms
- **Resource Load Delay:** 1,220 ms
- **Resource Load Duration:** 1,970 ms
- **Element Render Delay:** 120 ms
- **LCP Element:** `<img src="assets/images/hero-supervisor.jpg" alt="Industrial and manpower supervisor holding tablet in Dubai" loading="eager" fetchpriority="high">`

## 3. Performance Diagnostics & Opportunities

### A. Improve Image Delivery (Estimated Savings: 1,272 KiB)

Serve modern formats (WebP/AVIF) and implement responsive sizing:

| Asset / Source | Resource Size | Estimated Savings | Issues Identified |
|---|---|---|---|
| assets/images/warehouse-worker.jpg | 828.4 KiB | 780.4 KiB | Needs modern format / compression (655.6 KiB saving); downscale from 896x1185 to 469x628 display size (598.3 KiB saving). |
| assets/images/hero-supervisor.jpg | 269.1 KiB | 205.1 KiB | Needs modern format / compression (187.4 KiB saving); downscale from 736x682 to 769x512 display size (58.1 KiB saving). |
| Unsplash: photo-1586528116311-ad8dd3c8310d | 93.0 KiB | 77.7 KiB | Increase compression (55.0 KiB saving); resize from 584x400 to 375x250 (55.7 KiB saving). |
| Unsplash: photo-1578916171728-46686eac8d58 | 87.2 KiB | 70.9 KiB | Increase compression (47.1 KiB saving); resize from 600x411 to 365x274 (51.7 KiB saving). |
| Unsplash: photo-1504307651254-35680f356dfd | 109.4 KiB | 59.9 KiB | Increase compression factor (59.9 KiB saving). |
| Unsplash: photo-1578575437130-527eed3abbec | 42.5 KiB | 27.3 KiB | Resize from 586x400 to 375x250 (25.5 KiB saving). |
| Unsplash: photo-1504328345606-18bbc8c9d7d1 | 34.2 KiB | 20.5 KiB | Resize from 584x400 to 375x250 (20.5 KiB saving). |
| Unsplash: photo-1555244162-803834f70033 | 33.7 KiB | 20.2 KiB | Resize from 586x400 to 375x250 (20.2 KiB saving). |
| Unsplash: photo-1581578731548-c64695cc6952 | 16.4 KiB | 9.8 KiB | Resize from 584x400 to 375x250 (9.8 KiB saving). |

### B. Eliminate Render-Blocking Resources (Estimated Savings: 2,410 ms)

- `/js/lucide.min.js`: 96.8 KiB transfer size, 2,400 ms duration
- `/css/style.css?v=3.0`: 25.2 KiB transfer size, 1,160 ms duration
- `fonts.googleapis.com/css2?family=...`: 1.9 KiB transfer size, 200 ms duration

### C. Code Optimization Opportunities

- **Reduce Unused CSS:** /css/style.css?v=3.0 has ~17.5 KiB unused rules out of 24.5 KiB.
- **Minify JavaScript:** Est. savings of 10.7 KiB (/js/i18n.js?v=2.2 – 8.4 KiB, /js/main.js?v=2.2 – 2.3 KiB).
- **Minify CSS:** Est. savings of 6.1 KiB on /css/style.css?v=3.0.
- **Source Map Error:** /js/lucide.min.js returned an invalid JSON (`<!DOCTYPE...`) response when requesting /js/lucide.min.js.map.

## 4. Accessibility Audits (Score: 89)

### Failing Checks

**Elements use prohibited ARIA attributes:**

- `<div class="stars-row" aria-label="5 out of 5 stars">` (Repeated across 3 instances). aria-label is prohibited on generic container roles (`<div>`) without explicit landmark/widget roles.

**Contrast Ratio Failures:**

- Section eyebrows: OUR SERVICES, INDUSTRIES WE SERVE, HOW IT WORKS, TESTIMONIALS.
- Links: View All Services, Explore All Industries.
- Testimonial tags: .testimonial-sector-tag (Logistics Partner — Dubai, Facilities & Operations — UAE, Retail Chain — Abu Dhabi).

**Heading order not sequentially descending:**

- Level jumping detected between `<h3 data-i18n="home.trustCard1Title">` (10+ Years Experience) and `<h4 data-i18n="home.whyCard1Title">` (100% MOL & MOHRE Compliance).

## 5. Trust, Security & Best Practices (Score: 100)

While scored 100 based on standard metrics, Lighthouse flagged the following unscored security hardening improvements:

- **Missing Content Security Policy (CSP):** Missing script-src and object-src directives to mitigate XSS attacks.
- **Missing HSTS Header:** HTTP Strict Transport Security not detected.
- **Missing COOP Header:** Cross-Origin-Opener-Policy not defined.
- **Missing Trusted Types:** CSP lacks require-trusted-types-for 'script'.

## 6. Agentic Browsing (Score: 1/2)

- **Failed Audit:** Accessibility tree is not well-formed due to prohibited ARIA attributes on `<div class="stars-row" aria-label="5 out of 5 stars">`.
- **Passed:** Cumulative Layout Shift (0.036).
- **Unscored/Not Implemented:** WebMCP form coverage, registered WebMCP tools, WebMCP schemas, llms.txt, and ai-catalog.json.
