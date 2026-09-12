/**
 * Applies shared header/footer updates across all HTML pages:
 * - clickable Maps location in top bar (data-bind="company.mapsUrl")
 * - nav: add Our Team + Our Group (Industries stays in drawer/footer)
 * - drawer: add Our Team + Our Group
 * - footer: replace "Trades We Supply" with "Our Services" (11 services)
 * - footer nav: add Team + Group links
 * - mobile bar: "Request" → "Book"
 * Run: node tools/apply-shared.js
 */
const fs = require('fs');
const path = require('path');

const root = path.join(__dirname, '..');
const pages = fs.readdirSync(root).filter(f => f.endsWith('.html'));

const MAPS_URL = 'https://www.google.com/maps/search/?api=1&query=Shabab+Deira+Shipping+%26+Cargo+Services+Al+Ittihad+Road+Deira+Dubai';

const SERVICE_ANCHORS = [
  'packing-repacking', 'loading-unloading', 'delivery-services', 'cleaning-services',
  'factory-helpers', 'catering-services', 'event-management', 'machine-operators',
  'drivers-vehicle-services', 'sea-port-helpers', 'general-helpers'
];

// 1. Top-bar location → clickable link
const OLD_LOCATION = '<div class="top-bar__item"><i data-lucide="map-pin"></i><span data-bind="company.locationShort">Deira, Dubai, UAE</span></div>';
const NEW_LOCATION = '<a class="top-bar__item" data-bind="company.mapsUrl" href="' + MAPS_URL + '" target="_blank" rel="noopener" style="color:inherit;text-decoration:none;"><i data-lucide="map-pin"></i><span data-bind="company.locationShort">Deira, Dubai, UAE</span></a>';

// 2. Desktop nav — insert Team + Group after Industries (Industries kept in nav; FAQ stays)
const OLD_NAV = '<li><a href="industries.html" class="nav__link" data-i18n="nav.industries">Industries</a></li>';
const NEW_NAV = '<li><a href="industries.html" class="nav__link" data-i18n="nav.industries">Industries</a></li>\n          <li><a href="staff.html" class="nav__link" data-i18n="nav.team">Our Team</a></li>\n          <li><a href="group.html" class="nav__link" data-i18n="nav.group">Our Group</a></li>';

// 3. Drawer links — add Team + Group after Industries
const OLD_DRAWER = '<a href="industries.html" class="active"><span data-i18n="nav.industries">Industries</span><i data-lucide="chevron-right"></i></a>';
const NEW_DRAWER = '<a href="industries.html" class="active"><span data-i18n="nav.industries">Industries</span><i data-lucide="chevron-right"></i></a>\n        <a href="staff.html"><span data-i18n="nav.team">Our Team</span><i data-lucide="chevron-right"></i></a>\n        <a href="group.html"><span data-i18n="nav.group">Our Group</span><i data-lucide="chevron-right"></i></a>';
const OLD_DRAWER_PLAIN = '<a href="industries.html"><span data-i18n="nav.industries">Industries</span><i data-lucide="chevron-right"></i></a>';
const NEW_DRAWER_PLAIN = '<a href="industries.html"><span data-i18n="nav.industries">Industries</span><i data-lucide="chevron-right"></i></a>\n        <a href="staff.html"><span data-i18n="nav.team">Our Team</span><i data-lucide="chevron-right"></i></a>\n        <a href="group.html"><span data-i18n="nav.group">Our Group</span><i data-lucide="chevron-right"></i></a>';

// 4. Footer nav column — add Team + Group
const OLD_FOOTER_NAV = '<li><a href="faq.html" data-i18n="nav.faq">FAQ</a></li>';
const NEW_FOOTER_NAV = '<li><a href="staff.html" data-i18n="nav.team">Our Team</a></li>\n            <li><a href="group.html" data-i18n="nav.group">Our Group</a></li>\n            <li><a href="faq.html" data-i18n="nav.faq">FAQ</a></li>';

// 5. Footer services column — replace trades with 11 services
const OLD_TRADES_HEADING = '<h4 class="footer__heading" data-i18n="common.tradesHeading">Trades We Supply</h4>';
const NEW_TRADES_HEADING = '<h4 class="footer__heading" data-i18n="common.servicesHeading">Our Services</h4>';

function buildOldTradesList() {
  const keys = ['tradeElectricians', 'tradeWelders', 'tradePlumbers', 'tradeHvac', 'tradeCarpenters', 'tradeWarehouse', 'tradeGeneral'];
  const labels = ['Electricians', 'Welders', 'Plumbers & Pipefitters', 'HVAC Technicians', 'Steel Fixers & Carpenters', 'Warehouse Staff', 'General Labour'];
  return keys.map((k, i) => '<li><a href="services.html" data-i18n="common.' + k + '">' + labels[i] + '</a></li>').join('\n            ');
}

const NEW_SERVICES_LIST = SERVICE_ANCHORS.map((id, i) =>
  '<li><a href="services.html#' + id + '" data-i18n="services.' + i + '.title">Service</a></li>'
).join('\n            ');

// 6. Mobile bar label
const OLD_REQ = '<span data-i18n="nav.requestManpower">Request</span>';
const NEW_REQ = '<span data-i18n="nav.requestManpower">Book</span>';

let report = [];
pages.forEach(function (page) {
  const p = path.join(root, page);
  let html = fs.readFileSync(p, 'utf8');
  const before = html;
  const notes = [];

  if (html.includes(OLD_LOCATION)) { html = html.split(OLD_LOCATION).join(NEW_LOCATION); notes.push('maps-link'); }
  if (html.includes(OLD_NAV)) { html = html.split(OLD_NAV).join(NEW_NAV); notes.push('nav'); }
  if (html.includes(OLD_DRAWER)) { html = html.split(OLD_DRAWER).join(NEW_DRAWER); notes.push('drawer(active)'); }
  if (html.includes(OLD_DRAWER_PLAIN)) { html = html.split(OLD_DRAWER_PLAIN).join(NEW_DRAWER_PLAIN); notes.push('drawer'); }
  if (html.includes(OLD_FOOTER_NAV)) { html = html.split(OLD_FOOTER_NAV).join(NEW_FOOTER_NAV); notes.push('footer-nav'); }
  if (html.includes(OLD_TRADES_HEADING)) {
    html = html.replace(OLD_TRADES_HEADING, NEW_TRADES_HEADING);
    const oldList = buildOldTradesList();
    if (html.includes(oldList)) html = html.replace(oldList, NEW_SERVICES_LIST);
    notes.push('footer-services');
  }
  if (html.includes(OLD_REQ)) { html = html.split(OLD_REQ).join(NEW_REQ); notes.push('mobilebar'); }

  if (html !== before) {
    fs.writeFileSync(p, html, 'utf8');
    report.push(page + ': ' + notes.join(', '));
  } else {
    report.push(page + ': (no shared changes matched)');
  }
});
console.log(report.join('\n'));
