/* ==========================================================================
   Invoice Builder — 1/4 Core
   Shared state, number helpers, template rendering, theme, sections, zoom.
   Calculation and rendering logic is unchanged from the working builder so
   invoice generation, VAT totals and exports keep behaving identically.
   Depends on: nothing. Required by: table, library, actions.
   ========================================================================== */
(function () {
  'use strict';

  window.Builder = window.Builder || {};
  var B = window.Builder;

  B.$ = function (id) { return document.getElementById(id); };
  B.currentZoom = 1;
  B.CURRENT_TEMPLATE = window.BUILDER_INITIAL_TEMPLATE || {};
  B.SYSTEM_TEMPLATES = window.BUILDER_SYSTEM_TEMPLATES || {};

  /* ── Numbers: identical engine to the proven builder ── */
  var ONES = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
  var TENS = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

  function below1000(n) {
    var s = '';
    if (n >= 100) { s += ONES[Math.floor(n / 100)] + ' Hundred'; n %= 100; if (n) s += ' and '; }
    if (n >= 20) { s += TENS[Math.floor(n / 10)]; if (n % 10) s += '-' + ONES[n % 10]; }
    else if (n > 0) { s += ONES[n]; }
    return s;
  }
  function intWords(n) {
    if (n === 0) return 'Zero';
    var parts = [];
    [[1e6, 'Million'], [1e3, 'Thousand']].forEach(function (u) {
      if (n >= u[0]) { parts.push(below1000(Math.floor(n / u[0])) + ' ' + u[1]); n %= u[0]; }
    });
    if (n > 0) parts.push(below1000(n));
    return parts.join(' ');
  }
  B.toWords = function (a, curr) {
    curr = curr || 'AED';
    var d = Math.floor(a), f = Math.round((a - d) * 100);
    var unit = (curr === 'AED') ? 'Dirham' : curr;
    var frac = (curr === 'AED') ? 'Fils' : 'Cents';
    var s = intWords(d) + ' ' + unit + (d === 1 ? '' : 's');
    if (f > 0) s += ' and ' + intWords(f) + ' ' + frac;
    return s + ' Only';
  };
  B.parseNum = function (v) {
    var n = parseFloat(String(v).replace(/,/g, '').replace(/[^0-9.-]/g, ''));
    return isNaN(n) ? 0 : n;
  };
  B.fmt = function (n) {
    return Number(n).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  };

  /* Brand ink: every document prints in Shabab Deira navy + gold.
     Per-template theme colours are ignored so documents never clash
     (blue bar + red badge etc.). Credit-note red comes only from the
     layout tone class. Users can still recolour via the Design pane. */
  B.BRAND_PRIMARY = '#0F3763';
  B.BRAND_ACCENT = '#C8AD7E';

  /* Document number without the doubled prefix (meta values in the data
     already contain the full number, e.g. "SD-TSI-0101"). */
  B.docNo = function (tpl) {
    var prefix = tpl.docCodePrefix || 'SD-';
    var raw = (tpl.metaFields && tpl.metaFields[0] && tpl.metaFields[0].value) ? String(tpl.metaFields[0].value) : '0101';
    return (raw.indexOf(prefix) === 0) ? raw : prefix + raw;
  };
  var toastTimer;
  B.showToast = function (msg, isErr) {
    var t = B.$('studioToast');
    if (!t) return;
    t.textContent = msg;
    t.className = 'ib-toast show' + (isErr ? ' err' : '');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(function () { t.className = 'ib-toast'; }, 3200);
  };

  /* ── Render a template onto the A4 canvas ── */
  B.renderTemplate = function (tpl) {
    B.CURRENT_TEMPLATE = tpl;
    var canvas = B.$('docCanvas');
    if (!canvas) return;

    if (B.$('topQuickTemplateSelect') && tpl.key) B.$('topQuickTemplateSelect').value = tpl.key;
    if (B.$('topCategoryBadge')) {
      B.$('topCategoryBadge').textContent = tpl.badge || tpl.category || 'General';
      B.$('topCategoryBadge').style.borderColor = tpl.badgeColor || '#0F3763';
    }
    if (B.$('topDocNo')) {
      B.$('topDocNo').textContent = B.docNo(tpl);
    }

    var theme = tpl.theme || {};
    var primary = B.BRAND_PRIMARY;
    var accent = B.BRAND_ACCENT;
    var rowH = theme.rowHeight || 28;
    var fontF = theme.fontFamily || "'Source Sans 3', sans-serif";

    canvas.style.setProperty('--primary', primary);
    canvas.style.setProperty('--accent', accent);
    canvas.style.setProperty('--row-h', rowH + 'px');
    canvas.style.setProperty('--doc-font', fontF);

    /* Structural layout (registry): title/meta/totals/header/orientation/tone.
       Defaults reproduce the formal-invoice look; colour is never the
       differentiator. */
    var L = tpl.layout || {};
    canvas.className = 'a4-document'
      + ((L.title && L.title !== 'banner') ? ' doc--title-' + L.title : '')
      + ((L.meta && L.meta !== 'parties') ? ' doc--meta-' + L.meta : '')
      + ((L.totals && L.totals !== 'vat') ? ' doc--totals-' + L.totals : '')
      + ((L.header === 'compact') ? ' doc--header-compact' : '')
      + ((L.orientation === 'landscape') ? ' doc--landscape' : '')
      + ((L.tone === 'red') ? ' doc--tone-red' : '');

    if (B.$('primaryColorPicker')) B.$('primaryColorPicker').value = primary;
    if (B.$('accentColorPicker')) B.$('accentColorPicker').value = accent;
    if (B.$('rowHeightSelect')) B.$('rowHeightSelect').value = String(rowH);
    if (B.$('fontFamilySelect')) B.$('fontFamilySelect').value = fontF;
    if (B.$('currencySelect')) B.$('currencySelect').value = theme.currency || 'AED';
    if (B.$('checkEnableVat')) B.$('checkEnableVat').checked = (theme.showVat !== false);
    if (B.$('checkZebraStriping')) B.$('checkZebraStriping').checked = true;
    if (B.$('checkOfficialStamp')) B.$('checkOfficialStamp').checked = (theme.showStamp !== false);

    var co = tpl.company || {};
    if (B.$('coNameEn')) B.$('coNameEn').textContent = co.nameEn || 'SHABAB DEIRA';
    if (B.$('coSubEn')) B.$('coSubEn').textContent = co.subEn || 'SHIPPING & CARGO SERVICES LLC';
    if (B.$('coNameAr')) B.$('coNameAr').textContent = co.nameAr || 'شباب ديرة لخدمات';
    if (B.$('coSubAr')) B.$('coSubAr').textContent = co.subAr || 'الملاحة والشحن ش.ذ.م.م';
    if (B.$('coTrnDisplay')) B.$('coTrnDisplay').textContent = co.trn ? ('TRN: ' + co.trn) : '';
    if (B.$('coAddr')) B.$('coAddr').textContent = co.address || '';
    if (B.$('coPhone')) B.$('coPhone').textContent = co.phone || '';
    if (B.$('coEmail')) B.$('coEmail').textContent = co.email || '';

    if (B.$('docTypeBanner')) B.$('docTypeBanner').textContent = tpl.docType || 'TAX INVOICE';
    if (B.$('docNoBadge')) B.$('docNoBadge').textContent = B.docNo(tpl);

    var cl = tpl.client || {};
    if (B.$('clientName')) B.$('clientName').textContent = cl.name || '';
    if (B.$('clientAttention')) B.$('clientAttention').textContent = cl.attention ? ('Attn: ' + cl.attention) : '';
    if (B.$('clientAddress')) B.$('clientAddress').textContent = cl.address || '';
    if (B.$('clientTrn')) B.$('clientTrn').textContent = cl.trn ? ('TRN: ' + cl.trn) : '';
    if (B.$('clientContact')) B.$('clientContact').textContent = (cl.phone || '') + (cl.email ? (' | ' + cl.email) : '');

    var metaTbody = B.$('metaFieldsTbody');
    if (metaTbody) {
      metaTbody.innerHTML = '';
      (tpl.metaFields || []).forEach(function (mf) {
        var tr = document.createElement('tr');
        tr.innerHTML = '<td contenteditable="true" spellcheck="false">' + mf.label + ':</td>' +
          '<td contenteditable="true" spellcheck="false" style="text-align: right;">' + mf.value + '</td>';
        metaTbody.appendChild(tr);
      });
    }

    if (B.$('docNotesArea')) B.$('docNotesArea').textContent = tpl.notes || '';

    var sigs = tpl.signatures || [];
    if (sigs[0]) {
      if (B.$('sigVal1')) B.$('sigVal1').textContent = sigs[0].name || '';
      if (B.$('sigTitle1')) B.$('sigTitle1').textContent = sigs[0].title || '';
      if (B.$('sigSub1')) B.$('sigSub1').textContent = sigs[0].role || '';
    }
    if (sigs[1]) {
      if (B.$('sigVal2')) B.$('sigVal2').textContent = sigs[1].name || '';
      if (B.$('sigTitle2')) B.$('sigTitle2').textContent = sigs[1].title || '';
      if (B.$('sigSub2')) B.$('sigSub2').textContent = sigs[1].role || '';
    }
    if (sigs[2]) {
      if (B.$('sigVal3')) B.$('sigVal3').textContent = sigs[2].name || '';
      if (B.$('sigTitle3')) B.$('sigTitle3').textContent = sigs[2].title || '';
      if (B.$('sigSub3')) B.$('sigSub3').textContent = sigs[2].role || '';
    }

    /* Strip + subject variants are filled from the SAME template data;
       CSS shows exactly one meta arrangement (see doc--meta-*). */
    if (B.$('stripClientName')) B.$('stripClientName').textContent = cl.name || '';
    if (B.$('stripClientAttn')) B.$('stripClientAttn').textContent = cl.attention || '';
    var stripBox = B.$('metaStripFields');
    if (stripBox) {
      stripBox.innerHTML = '';
      (tpl.metaFields || []).forEach(function (mf) {
        var f = document.createElement('div');
        f.className = 'strip-field';
        var lab = document.createElement('span');
        lab.className = 'strip-label';
        lab.setAttribute('contenteditable', 'true');
        lab.setAttribute('spellcheck', 'false');
        lab.textContent = mf.label;
        var val = document.createElement('span');
        val.className = 'strip-value';
        val.setAttribute('contenteditable', 'true');
        val.setAttribute('spellcheck', 'false');
        val.textContent = mf.value;
        f.appendChild(lab);
        f.appendChild(val);
        stripBox.appendChild(f);
      });
    }

    var personMatch = null;
    (tpl.metaFields || []).forEach(function (mf) {
      if (!personMatch && /employee|received from|paid to|applicant|worker/i.test(mf.label || '')) personMatch = mf;
    });
    if (B.$('subjectLabel')) B.$('subjectLabel').textContent = personMatch ? personMatch.label : 'Subject';
    if (B.$('subjectName')) B.$('subjectName').textContent = personMatch ? personMatch.value : (cl.name || '');
    if (B.$('subjectSub')) B.$('subjectSub').textContent = cl.attention || '';
    var docLines = B.$('subjectDocLines');
    if (docLines) {
      docLines.innerHTML = '';
      (tpl.metaFields || []).forEach(function (mf, i) {
        if (personMatch && mf.label === personMatch.label && mf.value === personMatch.value) return;
        var r = document.createElement('div');
        r.className = 'subj-doc';
        r.dataset.mkey = mf.key || ('meta_' + i);
        var lab = document.createElement('span');
        lab.className = 'subj-label';
        lab.setAttribute('contenteditable', 'true');
        lab.setAttribute('spellcheck', 'false');
        lab.textContent = mf.label;
        var val = document.createElement('span');
        val.className = 'subj-value';
        val.setAttribute('contenteditable', 'true');
        val.setAttribute('spellcheck', 'false');
        val.textContent = mf.value;
        r.appendChild(lab);
        r.appendChild(val);
        docLines.appendChild(r);
      });
    }

    var modeMf = null;
    (tpl.metaFields || []).forEach(function (mf) {
      if (!modeMf && /payment mode|payment method|\bmode\b|method/i.test(mf.label || '')) modeMf = mf;
    });
    if (B.$('receiptModeVal')) B.$('receiptModeVal').textContent = modeMf ? modeMf.value : '';

    if (B.buildTable) B.buildTable(tpl.columns || [], tpl.items || []);
    if (B.recalculate) B.recalculate();

    document.querySelectorAll('.ib-tpl-row').forEach(function (c) {
      c.classList.toggle('active', c.dataset.key === tpl.key);
    });
  };

  /* ── Inspector tabs + theme + sections + zoom (wired on init) ── */
  B.initCoreControls = function () {
    document.querySelectorAll('.ib-tab').forEach(function (tab) {
      tab.addEventListener('click', function () {
        document.querySelectorAll('.ib-tab').forEach(function (t) { t.classList.remove('active'); });
        document.querySelectorAll('.ib-pane').forEach(function (p) { p.classList.remove('active'); });
        tab.classList.add('active');
        var pane = B.$(tab.dataset.pane);
        if (pane) pane.classList.add('active');
      });
    });

    if (B.$('btnToggleInspector')) {
      B.$('btnToggleInspector').addEventListener('click', function () {
        B.$('studioInspector').classList.toggle('collapsed');
      });
    }

    document.querySelectorAll('.ib-swatch').forEach(function (sw) {
      sw.addEventListener('click', function () {
        document.querySelectorAll('.ib-swatch').forEach(function (s) { s.classList.remove('active'); });
        sw.classList.add('active');
        var canvas = B.$('docCanvas');
        canvas.style.setProperty('--primary', sw.dataset.color);
        canvas.style.setProperty('--accent', sw.dataset.accent);
        B.$('primaryColorPicker').value = sw.dataset.color;
        B.$('accentColorPicker').value = sw.dataset.accent;
      });
    });

    if (B.$('primaryColorPicker')) B.$('primaryColorPicker').addEventListener('input', function (e) { B.$('docCanvas').style.setProperty('--primary', e.target.value); });
    if (B.$('accentColorPicker')) B.$('accentColorPicker').addEventListener('input', function (e) { B.$('docCanvas').style.setProperty('--accent', e.target.value); });
    if (B.$('fontFamilySelect')) B.$('fontFamilySelect').addEventListener('change', function (e) { B.$('docCanvas').style.setProperty('--doc-font', e.target.value); });
    if (B.$('rowHeightSelect')) B.$('rowHeightSelect').addEventListener('change', function (e) { B.$('docCanvas').style.setProperty('--row-h', e.target.value + 'px'); });
    if (B.$('checkZebraStriping')) B.$('checkZebraStriping').addEventListener('change', function (e) {
      var on = e.target.checked;
      Array.prototype.forEach.call(B.$('docTableTbody').rows, function (tr, i) {
        tr.classList.toggle('striped', on && (i % 2 === 1));
      });
    });
    if (B.$('currencySelect') && B.recalculate) B.$('currencySelect').addEventListener('change', B.recalculate);
    if (B.$('checkEnableVat') && B.recalculate) B.$('checkEnableVat').addEventListener('change', B.recalculate);

    if (B.$('checkOfficialStamp')) B.$('checkOfficialStamp').addEventListener('change', function (e) {
      B.$('officialStampSvg').style.display = e.target.checked ? 'block' : 'none';
    });
    if (B.$('stampColorSelect')) B.$('stampColorSelect').addEventListener('change', function (e) {
      var c = e.target.value;
      B.$('officialStampSvg').querySelectorAll('circle, path, rect').forEach(function (el) {
        if (el.getAttribute('stroke')) el.setAttribute('stroke', c);
        if (el.getAttribute('fill') && el.getAttribute('fill') !== 'none' && el.getAttribute('fill') !== '#FFFFFF') el.setAttribute('fill', c);
      });
    });
    ['checkSig1', 'checkSig2', 'checkSig3'].forEach(function (id, i) {
      if (B.$(id)) B.$(id).addEventListener('change', function (e) {
        B.$('sigSlot' + (i + 1)).style.display = e.target.checked ? 'block' : 'none';
      });
    });

    document.querySelectorAll('.sec-toggle').forEach(function (cb) {
      cb.addEventListener('change', function () {
        var sec = B.$(cb.dataset.target);
        if (sec) sec.style.display = cb.checked ? 'block' : 'none';
      });
    });

    if (window.Sortable && B.$('sectionOrderList')) {
      window.Sortable.create(B.$('sectionOrderList'), {
        animation: 150,
        onEnd: function () {
          var canvas = B.$('docCanvas');
          document.querySelectorAll('#sectionOrderList .ib-section-row').forEach(function (item) {
            var secEl = B.$(item.dataset.section);
            if (secEl) canvas.appendChild(secEl);
          });
          B.showToast('Section order updated');
        }
      });
    }

    var applyZoom = function (z) {
      B.currentZoom = Math.max(0.4, Math.min(1.5, z));
      var canvas = B.$('docCanvas');
      canvas.style.transform = (B.currentZoom === 1) ? 'none' : 'scale(' + B.currentZoom + ')';
      if (B.$('zoomLevelDisplay')) B.$('zoomLevelDisplay').textContent = Math.round(B.currentZoom * 100) + '%';
    };
    B.applyZoom = applyZoom;
    if (B.$('btnZoomIn')) B.$('btnZoomIn').addEventListener('click', function () { applyZoom(B.currentZoom + 0.1); });
    if (B.$('btnZoomOut')) B.$('btnZoomOut').addEventListener('click', function () { applyZoom(B.currentZoom - 0.1); });
    if (B.$('btnFitPage')) B.$('btnFitPage').addEventListener('click', function () {
      var availW = B.$('canvasArea').clientWidth - 48;
      var pageW = (B.$('docCanvas') && B.$('docCanvas').offsetWidth) || 794;
      applyZoom(availW / (pageW + 26));
    });
  };
})();
