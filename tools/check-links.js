/**
 * QA: check all internal href/src references in HTML resolve to real files.
 * Run: node tools/check-links.js
 */
const fs = require('fs');
const path = require('path');

const root = path.join(__dirname, '..');
const files = fs.readdirSync(root).filter(f => f.endsWith('.html'));
let bad = 0;
files.forEach(f => {
  const html = fs.readFileSync(path.join(root, f), 'utf8');
  const refs = new Set();
  let m;
  const reHref = /(?:href|src)="([^"#]+?)(#[^"]*)?"/g;
  while ((m = reHref.exec(html))) {
    const url = m[1];
    if (/^(https?:|mailto:|tel:|data:|javascript:)/.test(url)) continue;
    refs.add(url);
  }
  const missing = [];
  refs.forEach(url => {
    const p = path.join(root, url);
    if (!fs.existsSync(p)) missing.push(url);
  });
  if (missing.length) { bad++; console.log(f + ' MISSING: ' + missing.join(', ')); }
});
console.log(bad ? bad + ' file(s) with broken links' : 'All internal links OK in ' + files.length + ' files');
