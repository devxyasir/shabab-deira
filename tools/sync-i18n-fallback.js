/**
 * Rebuilds the embedded fallbackData in js/i18n.js from data/site-content.json.
 * Run after any edit to data/site-content.json:  node tools/sync-i18n-fallback.js
 */
const fs = require('fs');
const path = require('path');

const root = path.join(__dirname, '..');
const jsonPath = path.join(root, 'data', 'site-content.json');
const i18nPath = path.join(root, 'js', 'i18n.js');

const data = fs.readFileSync(jsonPath, 'utf8');
JSON.parse(data); // validate before embedding

const js = fs.readFileSync(i18nPath, 'utf8');
const lines = js.split('\n');

const start = lines.findIndex(l => l.trim().startsWith('var fallbackData = {'));
if (start === -1) { console.error('fallbackData start marker not found'); process.exit(1); }
let end = -1;
for (let i = start + 1; i < lines.length; i++) {
  if (lines[i].trim() === '};') { end = i; break; }
}
if (end === -1) { console.error('fallbackData end marker not found'); process.exit(1); }

const replacement = 'var fallbackData = ' + data.trim().replace(/\n/g, '\n  ') + ';';
const next = lines.slice(0, start).concat([replacement]).concat(lines.slice(end + 1));
fs.writeFileSync(i18nPath, next.join('\n'), 'utf8');
console.log('Synced fallbackData (' + (end - start + 1) + ' lines replaced) from data/site-content.json.');
