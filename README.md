# Shabab Deira — Static Company Website

Bilingual (English / Arabic) static website for Shabab Deira Shipping & Cargo Services, a UAE manpower and workforce services company based in Deira, Dubai (est. 2012).

No build system. Open `index.html` directly, or serve the folder with any static server.

## Pages

| File | Purpose |
|---|---|
| `index.html` | Home — hero, services, industries, partners marquee, group preview, process, FAQ preview |
| `services.html` | 11 services with detail sections + at-a-glance table |
| `industries.html` | 6 industries served |
| `about.html` | Company story, stats, approach, values, group section |
| `staff.html` | Our Team (Zubair Khan + team members) |
| `group.html` | Our Group — 5 companies overview |
| `group-shipping-cargo.html`, `group-container-loading.html`, `group-cargo-packaging.html`, `group-supply-labour.html`, `group-multan-mount-technical.html` | Group company pages (generated) |
| `request-manpower.html` | Book Manpower form (submits to WhatsApp) |
| `faq.html` | 14 FAQs with search |
| `contact.html` | Contact channels + general inquiry form |
| `privacy-policy.html`, `terms.html` | Legal pages |

## Content & Translation

All text lives in **`data/site-content.json`** — every value is either a plain string or an `{ "en": "...", "ar": "..." }` object. HTML elements carry `data-i18n="path.to.key"` (or `data-i18n-placeholder`) and `js/i18n.js` hydrates them on load and on language toggle. Dynamic sections are filled by renderers keyed on `data-render` attributes (`services-grid`, `services-detail`, `industries-grid`, `trades-tbody`, `process-steps`, `faq-list`, `partners-row-1/2`, `staff-grid`, `group-grid`).

**After editing the JSON, always run:**

```
node tools/sync-i18n-fallback.js
```

This regenerates the embedded `fallbackData` inside `js/i18n.js` so the site still renders when opened via `file://` (where `fetch` of the JSON is blocked).

### Things commonly edited in the JSON

- `company` — name, phone, email, WhatsApp number, address, hours, `mapsUrl` (Google Maps link used by the clickable header location)
- `partners[]` — marquee logos/names (currently placeholders)
- `staff[]` — team member cards
- `groupCompanies[]` — the 5 group companies (each with its own `page` filename and bilingual `content`)

## Domain Setting

The domain is **not** hardcoded anywhere. Set it once in `data/site-content.json`:

```json
"company": { "siteUrl": "https://your-domain.com" }
```

`js/i18n.js` rewrites every page's `<link rel="canonical">` and `og:url` at runtime from this value. HTML files contain only relative canonicals. When the domain changes, update `company.siteUrl` only.

## Tools

| Script | What it does |
|---|---|
| `node tools/sync-i18n-fallback.js` | Regenerate `fallbackData` in `js/i18n.js` from the JSON (run after every JSON edit) |
| `node tools/build-group-pages.js` | Regenerate the 5 group company pages from `groupCompanies[]` + `tools/group-company-template.html` (run after editing group companies) |
| `node tools/check-i18n-keys.js` | QA — verify every `data-i18n` key used in HTML exists in the JSON |
| `node tools/check-links.js` | QA — verify all internal links/files resolve |

## Forms

Both forms open a pre-filled WhatsApp message (`request-manpower.html` includes the selected service/location/duration labels in the visitor's current language; the general contact form shows a confirmation toast).

## Notes

- `data/parts/` contains the source part files the main JSON was assembled from; the merged `data/site-content.json` is what the site reads. Edit the merged JSON directly.
- Language toggle persists in `localStorage` (`shabab_lang`); RTL is applied via `dir="rtl"` on `<html>`.
- Icons: Lucide (`data-lucide` attributes). Images: Unsplash placeholders — replace with real company photos when available.
