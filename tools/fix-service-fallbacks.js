/**
 * One-off: replace "Service" footer fallback text with real service names in all pages.
 * Run: node tools/fix-service-fallbacks.js
 */
const fs = require('fs');
const path = require('path');

const root = path.join(__dirname, '..');
const data = JSON.parse(fs.readFileSync(path.join(root, 'data', 'site-content.json'), 'utf8'));
const names = data.services.map(s => (typeof s.title === 'object' ? s.title.en : s.title));

const files = fs.readdirSync(root).filter(f => f.endsWith('.html'));
let total = 0;
files.forEach(f => {
  const p = path.join(root, f);
  let html = fs.readFileSync(p, 'utf8');
  let count = 0;
  names.forEach((name, i) => {
    const from = 'data-i18n="services.' + i + '.title">Service<';
    const to = 'data-i18n="services.' + i + '.title">' + name.replace(/&/g, '&amp;') + '<';
    while (html.includes(from)) { html = html.replace(from, to); count++; }
  });
  if (count) { fs.writeFileSync(p, html, 'utf8'); console.log(f + ': ' + count); total += count; }
});
console.log('total: ' + total);
