/**
 * Generates the 5 group company pages from data/site-content.json groupCompanies[]
 * and tools/group-company-template.html.
 * Re-run after editing groupCompanies in the JSON: node tools/build-group-pages.js
 */
const fs = require('fs');
const path = require('path');

const root = path.join(__dirname, '..');
const data = JSON.parse(fs.readFileSync(path.join(root, 'data', 'site-content.json'), 'utf8'));
const tpl = fs.readFileSync(path.join(root, 'tools', 'group-company-template.html'), 'utf8');

const MAPS_URL = data.company.mapsUrl;

data.groupCompanies.forEach(function (c, idx) {
  const content = c.content;
  const titleKey = ['pageTitles.groupShipping', 'pageTitles.groupContainerLoading', 'pageTitles.groupCargoPackaging', 'pageTitles.groupSupplyLabour', 'pageTitles.groupMultanMount'][idx];

  const servicesList = content.services.map(function (s, i) {
    return '<li><i data-lucide="check"></i><span data-i18n="groupCompanies.' + idx + '.content.services.' + i + '">' + s.en + '</span></li>';
  }).join('\n          ');

  let html = tpl
    .replace(/{{PAGE}}/g, c.page)
    .replace(/{{TITLE_KEY}}/g, titleKey)
    .replace(/{{TITLE_EN}}/g, content.h1.en)
    .replace(/{{TITLE_AR}}/g, content.h1.ar)
    .replace(/{{META_DESC}}/g, (content.intro.en.length > 155 ? content.intro.en.slice(0, 152) + '...' : content.intro.en))
    .replace(/{{H1_KEY}}/g, 'groupCompanies.' + idx + '.content.h1')
    .replace(/{{H1_EN}}/g, content.h1.en)
    .replace(/{{INTRO_KEY}}/g, 'groupCompanies.' + idx + '.content.intro')
    .replace(/{{INTRO_EN}}/g, content.intro.en)
    .replace(/{{IMAGE}}/g, c.image)
    .replace(/{{IMAGE_ALT}}/g, content.h1.en)
    .replace(/{{ABOUT_TITLE_KEY}}/g, 'groupCompanies.' + idx + '.content.aboutTitle')
    .replace(/{{ABOUT_TITLE_EN}}/g, content.aboutTitle.en)
    .replace(/{{ABOUT_P_KEY}}/g, 'groupCompanies.' + idx + '.content.aboutP')
    .replace(/{{ABOUT_P_EN}}/g, content.aboutP.en)
    .replace(/{{SERVICES_TITLE_KEY}}/g, 'groupCompanies.' + idx + '.content.servicesTitle')
    .replace(/{{SERVICES_TITLE_EN}}/g, content.servicesTitle.en)
    .replace(/{{SERVICES_LIST}}/g, servicesList)
    .replace(/{{COVERAGE_TITLE_KEY}}/g, 'groupCompanies.' + idx + '.content.coverageTitle')
    .replace(/{{COVERAGE_TITLE_EN}}/g, content.coverageTitle.en)
    .replace(/{{COVERAGE_P_KEY}}/g, 'groupCompanies.' + idx + '.content.coverageP')
    .replace(/{{COVERAGE_P_EN}}/g, content.coverageP.en)
    .replace(/{{MAPS_URL}}/g, MAPS_URL)
    .replace(/{{ORG_NAME}}/g, content.h1.en);

  fs.writeFileSync(path.join(root, c.page), html, 'utf8');
  console.log('generated ' + c.page);
});
