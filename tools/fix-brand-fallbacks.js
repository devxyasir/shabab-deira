/**
 * One-off: normalise old brand fallback strings across all pages.
 * - logo tagline fallback: "Manpower Supply" -> "Manpower & Labour Supply"
 * - copyright fallback: "Shabab Deira Manpower Supply LLC" -> "Shabab Deira Shipping & Cargo Services"
 * Run: node tools/fix-brand-fallbacks.js
 */
const fs = require('fs');
const path = require('path');

const root = path.join(__dirname, '..');
const files = fs.readdirSync(root).filter(f => f.endsWith('.html'));

const replacements = [
  ['data-i18n="company.tagline">Manpower Supply<', 'data-i18n="company.tagline">Manpower &amp; Labour Supply<'],
  ['Shabab Deira Manpower Supply LLC', 'Shabab Deira Shipping & Cargo Services']
];

let total = 0;
files.forEach(f => {
  const p = path.join(root, f);
  let html = fs.readFileSync(p, 'utf8');
  let count = 0;
  replacements.forEach(([from, to]) => {
    while (html.includes(from)) { html = html.replace(from, to); count++; }
  });
  if (count) { fs.writeFileSync(p, html, 'utf8'); console.log(f + ': ' + count + ' replacements'); total += count; }
});
console.log('total: ' + total);
