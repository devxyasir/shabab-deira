/* ==========================================================================
   Invoice Builder — 4/5 Template chooser (start screen)
   The builder always lands here first. The full editor (topbar + canvas)
   stays hidden until the user opens one specific template.
   Depends on: core, table, library. Required by: actions (init).
   ========================================================================== */
(function () {
  'use strict';
  var B = window.Builder = window.Builder || {};
  var $ = function (id) { return document.getElementById(id); };

  B.chooserCat = 'All';
  B.preselectedKey = window.BUILDER_INITIAL_KEY || '';

  function matchesSearch(t, q) {
    if (!q) return true;
    q = q.toLowerCase();
    return (t.title || '').toLowerCase().indexOf(q) !== -1 ||
      (t.category || '').toLowerCase().indexOf(q) !== -1 ||
      (t.description || '').toLowerCase().indexOf(q) !== -1 ||
      ((t.docCodePrefix || '').toLowerCase().indexOf(q) !== -1);
  }

  B.renderChooser = function () {
    var box = $('chooserGroups');
    if (!box) return;
    box.innerHTML = '';
    var search = (($('chooserSearch') && $('chooserSearch').value) || '').toLowerCase();
    var groups = {};
    Object.keys(B.SYSTEM_TEMPLATES).forEach(function (k) {
      var t = B.SYSTEM_TEMPLATES[k];
      if (B.chooserCat !== 'All' && t.category !== B.chooserCat) return;
      if (search && !matchesSearch(t, search)) return;
      (groups[t.category] = groups[t.category] || []).push(t);
    });

    var cats = Object.keys(groups);
    var empty = $('chooserEmpty');
    if (empty) empty.style.display = cats.length === 0 ? '' : 'none';
    if ($('chooserCount')) {
      var n = 0;
      cats.forEach(function (c) { n += groups[c].length; });
      $('chooserCount').textContent = n + ' of ' + Object.keys(B.SYSTEM_TEMPLATES).length + ' templates';
    }

    cats.forEach(function (cat) {
      var title = document.createElement('div');
      title.className = 'ib-lib-group-title';
      title.textContent = ((B.CATEGORY_LABELS && B.CATEGORY_LABELS[cat]) || cat) + ' (' + groups[cat].length + ')';
      box.appendChild(title);
      groups[cat].forEach(function (t) {
        var row = document.createElement('div');
        row.className = 'ib-lib-row' + (t.key === B.preselectedKey ? ' is-preselected' : '');
        var left = document.createElement('div');
        var code = document.createElement('div');
        code.className = 'ib-lib-code';
        code.textContent = (t.docCodePrefix || t.key) + '  ·  ' + (t.category || '');
        var h = document.createElement('h4');
        h.textContent = t.title;
      var p = document.createElement('p');
      p.textContent = t.blurb || t.description || '';
        left.appendChild(code);
        left.appendChild(h);
        left.appendChild(p);
        var open = document.createElement('button');
        open.type = 'button';
        open.className = 'ib-btn ib-btn--small';
        open.textContent = t.key === B.preselectedKey ? 'Continue' : 'Open';
        open.addEventListener('click', function () { B.openTemplate(t.key); });
        row.appendChild(left);
        row.appendChild(open);
        box.appendChild(row);
      });
    });
  };

  /* Show the start screen; hide the editor. */
  B.showChooser = function () {
    if ($('chooserView')) $('chooserView').classList.remove('ib-hidden');
    if ($('editorView')) $('editorView').classList.add('ib-hidden');
    B.renderChooser();
  };

  /* Open one template: build the editor, then reveal it. */
  B.openTemplate = function (key) {
    var t = (key && B.SYSTEM_TEMPLATES[key]) ? B.SYSTEM_TEMPLATES[key] : null;
    if (!t) return;
    B.preselectedKey = key;
    B.renderTemplate(t);
    if (B.populateSidebarTemplates) B.populateSidebarTemplates();
    if ($('chooserView')) $('chooserView').classList.add('ib-hidden');
    if ($('editorView')) $('editorView').classList.remove('ib-hidden');
    if (window.innerWidth < 1200 && $('studioInspector')) {
      $('studioInspector').classList.add('collapsed');
    }
    B.showToast('Opened: ' + t.title);
  };

  B.initChooser = function () {
    if ($('chooserSearch')) $('chooserSearch').addEventListener('input', B.renderChooser);
    if ($('chooserCats')) {
      $('chooserCats').addEventListener('click', function (e) {
        var btn = e.target.closest ? e.target.closest('.ib-cat-btn') : null;
        if (!btn) return;
        document.querySelectorAll('#chooserCats .ib-cat-btn').forEach(function (b) { b.classList.remove('active'); });
        btn.classList.add('active');
        B.chooserCat = btn.dataset.cat;
        B.renderChooser();
      });
    }
    /* Topbar button becomes the way back to the start screen. */
    if ($('btnOpenTemplates')) {
      $('btnOpenTemplates').addEventListener('click', function () { B.showChooser(); });
    }
    if ($('topQuickTemplateSelect')) {
      $('topQuickTemplateSelect').addEventListener('change', function (e) {
        if (B.SYSTEM_TEMPLATES[e.target.value]) B.openTemplate(e.target.value);
      });
    }
  };
})();
