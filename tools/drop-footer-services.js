// One-off: drop the itemized "Our Services" (11-trade) column from every footer.
// The v2 spec replaces it with top-level links only — the Navigation column
// already carries those, so the column is removed outright.
const fs = require('fs');
const path = require('path');

const root = __dirname + '/..';
const files = fs.readdirSync(root)
  .filter(f => f.endsWith('.html'))
  .map(f => path.join(root, f));
files.push(path.join(root, 'tools', 'group-company-template.html'));

// Matches the whole footer column:
// <div> <h4 ... servicesHeading>…</h4> <ul class="footer__links">…</ul> </div>
const pattern = /[ \t]*<div>\s*\n\s*<h4 class="footer__heading" data-i18n="common\.servicesHeading">[\s\S]*?<\/ul>\s*\n\s*<\/div>\s*\n(?=\s*<div>|\s*<\/div>)/;

let total = 0;
files.forEach(file => {
  const src = fs.readFileSync(file, 'utf8');
  const cleaned = src.replace(pattern, '');
  if (cleaned !== src) {
    fs.writeFileSync(file, cleaned, 'utf8');
    console.log('cleaned: ' + path.relative(root, file));
    total++;
  }
});
console.log('Done — ' + total + ' files updated.');
