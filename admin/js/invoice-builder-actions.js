/* ==========================================================================
   Invoice Builder — 5/5 Document actions
   Duplicate, save template, save issued document, print / PDF / PNG export.
   Server contract is unchanged: POST ../api/templates.php with
   action save_template | save_document, plus X-Admin-Token header.
   Depends on: core, table, library. Runs init on DOMContentLoaded.
   ========================================================================== */
(function () {
  'use strict';
  var B = window.Builder = window.Builder || {};
  var $ = function (id) { return document.getElementById(id); };

  B.extractTemplatePayload = function () {
    var cols = [];
    document.querySelectorAll('#docTableThead th').forEach(function (th) {
      if (th.dataset.colKey) {
        cols.push({
          key: th.dataset.colKey,
          title: th.textContent.trim(),
          width: th.style.width || 'auto',
          align: th.style.textAlign || 'left',
          type: (th.dataset.colKey === 'amount' || th.dataset.colKey === 'total' || th.dataset.colKey === 'net_pay') ? 'total' : 'text'
        });
      }
    });

    var layout = (B.CURRENT_TEMPLATE && B.CURRENT_TEMPLATE.layout) || {};
    var metaMode = layout.meta || 'parties';

    /* Meta + client are read from the VISIBLE variant so user edits in
       strip/subject arrangements survive saving. Anything not editable in
       the visible variant falls back to template data (never stale hidden
       DOM). */
    var metaFields = [];
    if (metaMode === 'strip') {
      document.querySelectorAll('#metaStripFields .strip-field').forEach(function (f, i) {
        var lab = f.querySelector('.strip-label');
        var val = f.querySelector('.strip-value');
        if (lab && val) metaFields.push({ label: lab.textContent.trim(), value: val.textContent.trim(), key: 'strip_' + i });
      });
    } else if (metaMode === 'subject') {
      var subjLabel = $('subjectLabel') ? $('subjectLabel').textContent.trim() : '';
      var subjName = $('subjectName') ? $('subjectName').textContent.trim() : '';
      if (subjLabel && subjLabel !== 'Subject' && subjName) {
        metaFields.push({ label: subjLabel, value: subjName, key: 'person' });
      }
      document.querySelectorAll('#subjectDocLines .subj-doc').forEach(function (r) {
        var lab = r.querySelector('.subj-label');
        var val = r.querySelector('.subj-value');
        if (lab && val) metaFields.push({ label: lab.textContent.trim(), value: val.textContent.trim(), key: r.dataset.mkey || ('meta_' + metaFields.length) });
      });
    } else {
      document.querySelectorAll('#metaFieldsTbody tr').forEach(function (tr) {
        var tds = tr.querySelectorAll('td');
        if (tds.length >= 2) {
          metaFields.push({
            label: tds[0].textContent.replace(/:$/, '').trim(),
            value: tds[1].textContent.trim(),
            key: 'meta_' + Math.random().toString(36).substr(2, 6)
          });
        }
      });
    }

    var baseClient = (B.CURRENT_TEMPLATE && B.CURRENT_TEMPLATE.client) || {};
    var client;
    if (metaMode === 'strip') {
      client = {
        name: ($('stripClientName') && $('stripClientName').textContent.trim()) || baseClient.name || '',
        attention: ($('stripClientAttn') && $('stripClientAttn').textContent.trim()) || baseClient.attention || '',
        address: baseClient.address || '',
        trn: baseClient.trn || '',
        phone: baseClient.phone || '',
        email: baseClient.email || ''
      };
    } else if (metaMode === 'subject') {
      client = {
        name: ($('subjectName') && $('subjectName').textContent.trim()) || baseClient.name || '',
        attention: ($('subjectSub') && $('subjectSub').textContent.trim()) || baseClient.attention || '',
        address: baseClient.address || '',
        trn: baseClient.trn || '',
        phone: baseClient.phone || '',
        email: baseClient.email || ''
      };
    } else {
      client = {
        name: $('clientName').textContent.trim(),
        attention: $('clientAttention').textContent.trim(),
        address: $('clientAddress').textContent.trim(),
        trn: $('clientTrn').textContent.trim(),
        contact: $('clientContact').textContent.trim()
      };
    }

    var canvas = $('docCanvas');
    return {
      key: (B.CURRENT_TEMPLATE && B.CURRENT_TEMPLATE.key) || ('tpl_' + Date.now()),
      title: (B.CURRENT_TEMPLATE && B.CURRENT_TEMPLATE.title) || 'Custom Template',
      category: (B.CURRENT_TEMPLATE && B.CURRENT_TEMPLATE.category) || 'Custom Created',
      badge: (B.CURRENT_TEMPLATE && B.CURRENT_TEMPLATE.badge) || 'Custom',
      badgeColor: canvas.style.getPropertyValue('--primary') || '#0F3763',
      docType: $('docTypeBanner').textContent.trim(),
      docCodePrefix: 'SD-',
      description: 'Customized office template',
      theme: {
        primary: canvas.style.getPropertyValue('--primary') || '#0F3763',
        accent: canvas.style.getPropertyValue('--accent') || '#C8AD7E',
        fontFamily: canvas.style.getPropertyValue('--doc-font') || "'Source Sans 3', sans-serif",
        rowHeight: parseInt(canvas.style.getPropertyValue('--row-h'), 10) || 28,
        currency: $('currencySelect').value,
        showVat: $('checkEnableVat').checked,
        showStamp: $('checkOfficialStamp').checked
      },
      company: {
        nameEn: $('coNameEn').textContent.trim(),
        subEn: $('coSubEn').textContent.trim(),
        nameAr: $('coNameAr').textContent.trim(),
        subAr: $('coSubAr').textContent.trim(),
        trn: $('coTrnDisplay').textContent.replace(/TRN:\s*/i, '').trim(),
        address: $('coAddr').textContent.trim(),
        phone: $('coPhone').textContent.trim(),
        email: $('coEmail').textContent.trim()
      },
      client: client,
      metaFields: metaFields,
      layout: layout,
      columns: cols.length > 0 ? cols : (B.CURRENT_TEMPLATE.columns || []),
      items: B.extractCurrentItems(),
      notes: $('docNotesArea').textContent.trim(),
      signatures: [
        { title: $('sigTitle1').textContent.trim(), name: $('sigVal1').textContent.trim(), role: $('sigSub1').textContent.trim() },
        { title: $('sigTitle2').textContent.trim(), name: $('sigVal2').textContent.trim(), role: $('sigSub2').textContent.trim() },
        { title: $('sigTitle3').textContent.trim(), name: $('sigVal3').textContent.trim(), role: $('sigSub3').textContent.trim() }
      ]
    };
  };

  function apiPost(body, onOk, onErr) {
    var token = sessionStorage.getItem('shabab_admin_token') || '';
    fetch('../api/templates.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-Admin-Token': token },
      body: JSON.stringify(body)
    })
      .then(function (res) { return res.text(); })
      .then(function (txt) {
        try {
          var j = JSON.parse(txt);
          if (j.success) { if (onOk) onOk(j); }
          else if (onErr) onErr(j.error || 'Request failed');
        } catch (e) { if (onErr) onErr('Unexpected server reply'); }
      })
      .catch(function () { if (onErr) onErr('Network unavailable'); });
  }

  B.duplicateTemplate = function (key) {
    var source = (key && B.SYSTEM_TEMPLATES[key]) ? B.SYSTEM_TEMPLATES[key] : B.CURRENT_TEMPLATE;
    if (!source) return;
    var copy = JSON.parse(JSON.stringify(source));
    copy.key = 'copy_' + Date.now();
    copy.title = (source.title || 'Template') + ' (Copy)';
    B.renderTemplate(copy);
    B.showToast('Duplicated as: ' + copy.title);
    apiPost({
      action: 'save_template',
      key: copy.key,
      title: copy.title,
      category: 'Custom Created',
      description: 'Duplicated from ' + (source.title || 'template'),
      layout: copy
    }, function () { B.showToast('Copy saved to your templates'); }, function () {});
  };

  function captureCanvas() {
    var canvas = $('docCanvas');
    var ready = (document.fonts && document.fonts.ready) ? document.fonts.ready : Promise.resolve();
    return ready.then(function () {
      return window.html2canvas(canvas, {
        scale: 3,
        backgroundColor: '#FFFFFF',
        useCORS: true,
        width: canvas.offsetWidth,
        height: canvas.offsetHeight,
        windowWidth: canvas.offsetWidth,
        onclone: function (clonedDoc) {
          var c = clonedDoc.getElementById('docCanvas');
          if (c) {
            c.style.transform = 'none';
            c.querySelectorAll('.row-del-btn, .add-row-bar').forEach(function (el) { el.style.display = 'none'; });
          }
        }
      });
    });
  }

  B.initActions = function () {
    if ($('btnDuplicateTpl')) $('btnDuplicateTpl').addEventListener('click', function () { B.duplicateTemplate(B.CURRENT_TEMPLATE.key); });

    if ($('btnSaveTemplate')) $('btnSaveTemplate').addEventListener('click', function () {
      var payload = B.extractTemplatePayload();
      var name = prompt('Template name:', payload.title);
      if (!name) return;
      payload.title = name;
      B.showToast('Saving template…');
      apiPost({
        action: 'save_template',
        key: payload.key,
        title: payload.title,
        category: payload.category,
        description: payload.description,
        layout: payload
      }, function () { B.showToast('Template saved'); }, function (err) { B.showToast(err, true); });
    });

    if ($('btnSaveDocument')) $('btnSaveDocument').addEventListener('click', function () {
      var payload = B.extractTemplatePayload();
      var calcs = B.recalculate();
      var docNo = $('docNoBadge').textContent.trim() || ('DOC-' + Date.now());
      B.showToast('Saving document…');
      apiPost({
        action: 'save_document',
        document_no: docNo,
        template_key: payload.key,
        title: payload.title,
        client_name: payload.client.name,
        client_company: payload.client.name,
        doc_date: (payload.metaFields[0] ? payload.metaFields[0].value : ''),
        total_amount: calcs.grandTotal,
        currency: $('currencySelect').value,
        status: 'Issued',
        document_json: payload
      }, function () { B.showToast('Document ' + docNo + ' saved'); }, function (err) { B.showToast(err, true); });
    });

    if ($('btnPrintA4')) $('btnPrintA4').addEventListener('click', function () { window.print(); });

    if ($('btnExportPng')) $('btnExportPng').addEventListener('click', function () {
      if (!window.html2canvas) { B.showToast('Print library not ready', true); return; }
      B.showToast('Preparing PNG…');
      captureCanvas().then(function (cv) {
        cv.toBlob(function (blob) {
          var a = document.createElement('a');
          a.href = URL.createObjectURL(blob);
          a.download = (B.CURRENT_TEMPLATE.key || 'document') + '-' + Date.now() + '.png';
          a.click();
          setTimeout(function () { URL.revokeObjectURL(a.href); }, 2000);
          B.showToast('PNG downloaded');
        }, 'image/png');
      }).catch(function () { B.showToast('PNG export failed', true); });
    });

    if ($('btnExportPdf')) $('btnExportPdf').addEventListener('click', function () {
      if (!window.html2canvas || !window.jspdf) { B.showToast('PDF library not ready', true); return; }
      B.showToast('Preparing A4 PDF…');
      var isLandscape = $('docCanvas').classList.contains('doc--landscape');
      captureCanvas().then(function (cv) {
        var pdf = new window.jspdf.jsPDF({ orientation: isLandscape ? 'landscape' : 'portrait', unit: 'mm', format: 'a4', compress: true });
        if (isLandscape) pdf.addImage(cv.toDataURL('image/jpeg', 0.98), 'JPEG', 0, 0, 297, 210);
        else pdf.addImage(cv.toDataURL('image/jpeg', 0.98), 'JPEG', 0, 0, 210, 297);
        pdf.save((B.CURRENT_TEMPLATE.key || 'document') + '-' + Date.now() + '.pdf');
        B.showToast('PDF downloaded');
      }).catch(function () { B.showToast('PDF export failed', true); });
    });
  };

  /* ── Ordered init: core → table → library → chooser → actions ──
     The builder always lands on the template chooser first; the editor
     is revealed only after the user opens one specific template. */
  B.init = function () {
    B.initCoreControls();
    B.initTableControls();
    B.initLibraryControls();
    if (B.initChooser) B.initChooser();
    B.initActions();
    B.populateSidebarTemplates();
    if (B.showChooser) B.showChooser();
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', B.init);
  } else {
    B.init();
  }
})();
