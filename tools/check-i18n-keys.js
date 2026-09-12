/**
 * QA: verify every data-i18n / data-i18n-placeholder / data-bind key used in HTML
 * exists in data/site-content.json (or is a valid data-bind company field).
 * Run: node tools/check-i18n-keys.js
 */
const fs = require('fs');
const path = require('path');

const root = path.join(__dirname, '..');
const data = JSON.parse(fs.readFileSync(path.join(root, 'data', 'site-content.json'), 'utf8'));

function resolve(key) {
  const parts = key.split('.');
  let cur = data;
  for (const p of parts) {
    if (cur == null || typeof cur !== 'object' || !(p in cur)) return false;
    cur = cur[p];
  }
  return true;
}

const files = fs.readdirSync(root).filter(f => f.endsWith('.html'));
let bad = 0;
files.forEach(f => {
  const html = fs.readFileSync(path.join(root, f), 'utf8');
  const keys = new Set();
  let m;
  const reI18n = /data-i18n(?:-placeholder)?="([^"]+)"/g;
  while ((m = reI18n.exec(html))) keys.add(m[1]);
  // data-bind="company.x" -> check company.x exists
  const reBind = /data-bind="company\.([a-zA-Z]+)"/g;
  const bindKeys = new Set();
  while ((m = reBind.exec(html))) bindKeys.add('company.' + m[1]);

  const missing = [];
  keys.forEach(k => { if (!resolve(k)) missing.push(k); });
  bindKeys.forEach(k => { if (!resolve(k)) missing.push(k + ' (bind)'); });

  if (missing.length) {
    bad++;
    console.log(f + ' MISSING ' + missing.length + ' keys:');
    missing.forEach(k => console.log('  - ' + k));
  }
});
console.log(bad ? '\n' + bad + ' file(s) with missing keys' : 'All i18n keys resolve OK in ' + files.length + ' files');
