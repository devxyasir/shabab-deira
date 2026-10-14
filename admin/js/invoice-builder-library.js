/* ==========================================================================
   Invoice Builder — 3/4 Template library
   Sidebar list, full library dialog, text search and category filtering.
   Category keys are the backend keys from templates_data.php / templates API
   so saved data never changes; only the on-screen labels are simplified.
   Depends on: core, table.
   ========================================================================== */
(function () {
  'use strict';
  var B = window.Builder = window.Builder || {};
  var $ = function (id) { return document.getElementById(id); };

  B.CATEGORY_LABELS = {
    'All': 'All templates',
    'Invoicing & Tax': 'Tax invoices',
    'Timesheets & Labour': 'Timesheets',
    'Payroll & Cash': 'Payroll',
    'Operations & Logistics': 'Manpower supply',
    'Custom Created': 'Saved by office'
  };
  B.activeModalCat = 'All';

  function matchesSearch(t, q) {
    if (!q) return true;
    q = q.toLowerCase();
    return (t.title || '').toLowerCase().indexOf(q) !== -1 ||
      (t.category || '').toLowerCase().indexOf(q) !== -1 ||
      (t.description || '').toLowerCase().indexOf(q) !== -1;
  }

  function openButton() {
    var b = document.createElement('button');
    b.type = 'button';
    b.className = 'ib-btn ib-btn--small';
    b.textContent = 'Open';
    return b;
  }

  B.populateSidebarTemplates = function (searchTerm) {
    var container = $('sbTemplatesList');
    if (!container) return;
    container.innerHTML = '';
    searchTerm = (searchTerm || '').toLowerCase();

    Object.keys(B.SYSTEM_TEMPLATES).forEach(function (k) {
      var t = B.SYSTEM_TEMPLATES[k];
      if (searchTerm && !matchesSearch(t, searchTerm)) return;
      var card = document.createElement('div');
      card.className = 'ib-tpl-row' + (k === (B.CURRENT_TEMPLATE && B.CURRENT_TEMPLATE.key) ? ' active' : '');
      card.dataset.key = k;

      var title = document.createElement('h4');
      title.textContent = t.title;
      var meta = document.createElement('div');
      meta.className = 'ib-tpl-meta';
      var tick = document.createElement('i');
      tick.style.setProperty('--tick', t.badgeColor || '#0F3763');
      meta.appendChild(tick);
      meta.appendChild(document.createTextNode((t.docCodePrefix || '') + '  ·  ' + (t.category || '')));
      var desc = document.createElement('p');
      desc.textContent = t.blurb || t.description || '';
      var open = openButton();
      open.style.width = '100%';
      open.style.justifyContent = 'center';
      open.addEventListener('click', function () {
        B.renderTemplate(t);
        B.showToast('Opened: ' + t.title);
      });
      card.appendChild(title);
      card.appendChild(meta);
      card.appendChild(desc);
      card.appendChild(open);
      container.appendChild(card);
    });
  };

  function renderModalGroup(grid, list) {
    list.forEach(function (t) {
      var row = document.createElement('div');
      row.className = 'ib-lib-row';
      var left = document.createElement('div');
      var code = document.createElement('div');
      code.className = 'ib-lib-code';
      code.textContent = t.docCodePrefix || t.key;
      var h = document.createElement('h4');
      h.textContent = t.title;
      var p = document.createElement('p');
      p.textContent = t.description || '';
      left.appendChild(code);
      left.appendChild(h);
      left.appendChild(p);
      var open = openButton();
      open.addEventListener('click', function () {
        B.renderTemplate(t);
        B.closeTplModal();
        B.showToast('Opened: ' + t.title);
      });
      row.appendChild(left);
      row.appendChild(open);
      grid.appendChild(row);
    });
  }

  B.renderTemplateModal = function () {
    var grid = $('tplCardsContainer');
    if (!grid) return;
    grid.innerHTML = '';
    var search = (($('modalSearchInput') && $('modalSearchInput').value) || '').toLowerCase();
    var groups = {};
    Object.keys(B.SYSTEM_TEMPLATES).forEach(function (k) {
      var t = B.SYSTEM_TEMPLATES[k];
      if (B.activeModalCat !== 'All' && t.category !== B.activeModalCat) return;
      if (search && !matchesSearch(t, search)) return;
      (groups[t.category] = groups[t.category] || []).push(t);
    });

    var cats = Object.keys(groups);
    if (cats.length === 0) {
      var empty = document.createElement('p');
      empty.className = 'ib-hint';
      empty.textContent = 'No templates match this search. Clear the search or choose another category.';
      grid.appendChild(empty);
      return;
    }
    cats.forEach(function (cat) {
      var title = document.createElement('div');
      title.className = 'ib-lib-group-title';
      title.textContent = (B.CATEGORY_LABELS[cat] || cat) + ' (' + groups[cat].length + ')';
      grid.appendChild(title);
      renderModalGroup(grid, groups[cat]);
    });
  };

  B.closeTplModal = function () {
    if ($('tplModalBackdrop')) $('tplModalBackdrop').classList.remove('show');
  };

  B.initLibraryControls = function () {
    if ($('sbSearchInput')) {
      $('sbSearchInput').addEventListener('input', function (e) { B.populateSidebarTemplates(e.target.value); });
    }
    /* NOTE: the topbar template switcher and the "All templates" button are
       owned by the chooser (invoice-builder-chooser.js), which is the start
       screen. This avoids double handlers. The dialog helpers below stay
       only for backward compatibility and no-op when absent. */
    if ($('btnCloseTplModal')) $('btnCloseTplModal').addEventListener('click', B.closeTplModal);
    if ($('tplModalBackdrop')) {
      $('tplModalBackdrop').addEventListener('click', function (e) {
        if (e.target === $('tplModalBackdrop')) B.closeTplModal();
      });
    }
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') B.closeTplModal();
    });
    if ($('tplCategoryFilter')) {
      $('tplCategoryFilter').addEventListener('click', function (e) {
        var btn = e.target.closest ? e.target.closest('.ib-cat-btn') : null;
        if (!btn) return;
        document.querySelectorAll('.ib-cat-btn').forEach(function (b) { b.classList.remove('active'); });
        btn.classList.add('active');
        B.activeModalCat = btn.dataset.cat;
        B.renderTemplateModal();
      });
    }
    if ($('modalSearchInput')) $('modalSearchInput').addEventListener('input', B.renderTemplateModal);
  };
})();
