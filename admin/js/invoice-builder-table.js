/* ==========================================================================
   Invoice Builder — 2/4 Line-items table
   Table rendering, row operations, column edits and live VAT recalculation.
   Totals logic is unchanged: subtotal sums the total-type column,
   VAT is a flat 5% when enabled, grand total and amount-in-words update live.
   Depends on: core. Required by: library, actions.
   ========================================================================== */
(function () {
  'use strict';
  var B = window.Builder = window.Builder || {};
  var $ = function (id) { return document.getElementById(id); };

  B.buildTable = function (cols, items) {
    var thead = $('docTableThead');
    var tbody = $('docTableTbody');
    if (!thead || !tbody) return;
    thead.innerHTML = '';
    tbody.innerHTML = '';

    var htr = document.createElement('tr');
    cols.forEach(function (c) {
      var th = document.createElement('th');
      th.style.width = c.width || 'auto';
      th.style.textAlign = c.align || 'left';
      th.setAttribute('contenteditable', 'true');
      th.setAttribute('spellcheck', 'false');
      th.dataset.colKey = c.key;
      th.textContent = c.title;
      htr.appendChild(th);
    });
    var actTh = document.createElement('th');
    actTh.style.width = '24px';
    htr.appendChild(actTh);
    thead.appendChild(htr);

    var inspColBox = $('inspColumnsList');
    if (inspColBox) {
      inspColBox.innerHTML = '';
      cols.forEach(function (c, idx) {
        var div = document.createElement('div');
        div.className = 'ib-col-row';
        var label = document.createElement('span');
        label.textContent = c.title;
        var del = document.createElement('button');
        del.type = 'button';
        del.className = 'ib-btn ib-btn--small ib-btn--danger del-col';
        del.dataset.idx = String(idx);
        del.title = 'Remove column';
        del.textContent = 'Remove';
        div.appendChild(label);
        div.appendChild(del);
        inspColBox.appendChild(div);
      });
    }

    (items || []).forEach(function (item) { B.appendTableRow(cols, item); });
  };

  B.appendTableRow = function (cols, data) {
    cols = cols || (B.CURRENT_TEMPLATE.columns || []);
    data = data || {};
    var tbody = $('docTableTbody');
    if (!tbody) return;
    var tr = document.createElement('tr');
    var zebra = $('checkZebraStriping');
    if (zebra && zebra.checked && (tbody.rows.length % 2 === 1)) tr.className = 'striped';

    cols.forEach(function (c) {
      var td = document.createElement('td');
      td.style.textAlign = c.align || 'left';
      var val = (data[c.key] !== undefined) ? data[c.key] : '';
      if (c.key === 'sr') val = (tbody.rows.length + 1);
      if ((c.type === 'currency' || c.type === 'total') && typeof val === 'number') val = B.fmt(val);
      var cell = document.createElement('div');
      cell.className = 'cell-val';
      cell.dataset.col = c.key;
      cell.dataset.type = c.type || 'text';
      cell.setAttribute('contenteditable', 'true');
      cell.setAttribute('spellcheck', 'false');
      cell.textContent = val;
      td.appendChild(cell);
      tr.appendChild(td);
    });

    var delTd = document.createElement('td');
    delTd.className = 'td-del-btn';
    delTd.innerHTML = '<button type="button" class="row-del-btn" title="Delete row">&times;</button>';
    tr.appendChild(delTd);
    tbody.appendChild(tr);
  };

  B.extractCurrentItems = function () {
    var items = [];
    var tbody = $('docTableTbody');
    if (!tbody) return items;
    Array.prototype.forEach.call(tbody.rows, function (tr) {
      var r = {};
      tr.querySelectorAll('.cell-val').forEach(function (cv) { r[cv.dataset.col] = cv.textContent.trim(); });
      items.push(r);
    });
    return items;
  };

  B.recalculate = function () {
    var tbody = $('docTableTbody');
    if (!tbody) return { subtotal: 0, vat: 0, grandTotal: 0 };
    var curr = ($('currencySelect') && $('currencySelect').value) || 'AED';
    var isVat = $('checkEnableVat') ? $('checkEnableVat').checked : false;
    var subtotal = 0;

    Array.prototype.forEach.call(tbody.rows, function (tr, i) {
      var srCell = tr.querySelector('[data-col=sr]');
      if (srCell) srCell.textContent = (i + 1);
      var qtyCell = tr.querySelector('[data-col=qty]') || tr.querySelector('[data-col=workers]') || tr.querySelector('[data-col=hours]');
      var rateCell = tr.querySelector('[data-col=rate]') || tr.querySelector('[data-col=unit_price]') || tr.querySelector('[data-col=hourly_rate]');
      var amtCell = tr.querySelector('[data-col=amount]') || tr.querySelector('[data-col=total]') || tr.querySelector('[data-col=net_pay]') || tr.querySelector('[data-col=gross]');
      var rowAmt = 0;
      if (amtCell && amtCell.textContent.trim() !== '') {
        rowAmt = B.parseNum(amtCell.textContent);
      } else if (qtyCell && rateCell) {
        rowAmt = B.parseNum(qtyCell.textContent) * B.parseNum(rateCell.textContent);
        if (amtCell) amtCell.textContent = B.fmt(rowAmt);
      }
      subtotal += rowAmt;
    });

    subtotal = Math.round(subtotal * 100) / 100;
    var vatAmt = isVat ? Math.round(subtotal * 0.05 * 100) / 100 : 0;
    var grandTotal = Math.round((subtotal + vatAmt) * 100) / 100;

    if ($('calcSubtotal')) $('calcSubtotal').textContent = B.fmt(subtotal) + ' ' + curr;
    if ($('vatRow')) $('vatRow').style.display = isVat ? 'flex' : 'none';
    if ($('calcVat')) $('calcVat').textContent = B.fmt(vatAmt) + ' ' + curr;
    if ($('calcGrandTotal')) $('calcGrandTotal').textContent = B.fmt(grandTotal) + ' ' + curr;
    if ($('calcWords')) $('calcWords').textContent = B.toWords(grandTotal, curr);
    return { subtotal: subtotal, vat: vatAmt, grandTotal: grandTotal };
  };

  /* Numbers only: digits + one decimal point. Applied to number, currency
     and total cells so letters can never be typed or pasted into money and
     quantity fields. Time/badge/text columns are exempt. */
  B.sanitizeNum = function (el) {
    var t = el.textContent;
    var c = t.replace(/[^0-9.]/g, '');
    var p = c.split('.');
    if (p.length > 2) c = p[0] + '.' + p.slice(1).join('');
    if (c !== t) {
      el.textContent = c;
      var r = document.createRange();
      r.selectNodeContents(el);
      r.collapse(false);
      var s = getSelection();
      s.removeAllRanges();
      s.addRange(r);
    }
    return c;
  };

  B.initTableControls = function () {
    var canvas = $('docCanvas');
    if (canvas) {
      canvas.addEventListener('input', function (e) {
        if (e.target && e.target.classList && e.target.classList.contains('cell-val')) {
          var dt = e.target.dataset.type;
          if (dt === 'number' || dt === 'currency' || dt === 'total') B.sanitizeNum(e.target);
          B.recalculate();
        }
      });
      canvas.addEventListener('click', function (e) {
        var delBtn = e.target.closest ? e.target.closest('.row-del-btn') : null;
        if (delBtn) {
          var tr = delBtn.closest('tr');
          if (tr) { tr.remove(); B.recalculate(); }
        }
      });
    }

    var addOne = function () { B.appendTableRow(); B.recalculate(); };
    var addFive = function () { for (var i = 0; i < 5; i++) B.appendTableRow(); B.recalculate(); };
    if ($('btnAddRowBtn')) $('btnAddRowBtn').addEventListener('click', addOne);
    if ($('btnAdd5RowsBtn')) $('btnAdd5RowsBtn').addEventListener('click', addFive);
    if ($('btnInspAddRow')) $('btnInspAddRow').addEventListener('click', addOne);
    if ($('btnInspAdd5')) $('btnInspAdd5').addEventListener('click', addFive);
    if ($('btnInspDelRow')) $('btnInspDelRow').addEventListener('click', function () {
      var tbody = $('docTableTbody');
      if (tbody && tbody.rows.length > 0) tbody.lastElementChild.remove();
      B.recalculate();
    });
    if ($('btnInspClearRows')) $('btnInspClearRows').addEventListener('click', function () {
      if (confirm('Clear all rows from this table?')) { $('docTableTbody').innerHTML = ''; B.recalculate(); }
    });

    if ($('btnInspAddCol')) $('btnInspAddCol').addEventListener('click', function () {
      var name = prompt('Column heading:', 'Specification');
      if (!name) return;
      B.CURRENT_TEMPLATE.columns = B.CURRENT_TEMPLATE.columns || [];
      B.CURRENT_TEMPLATE.columns.push({ key: 'col_' + Date.now(), title: name, width: '110px', align: 'left', type: 'text' });
      B.buildTable(B.CURRENT_TEMPLATE.columns, B.extractCurrentItems());
    });

    if ($('inspColumnsList')) $('inspColumnsList').addEventListener('click', function (e) {
      var btn = e.target.closest ? e.target.closest('.del-col') : null;
      if (!btn) return;
      var idx = parseInt(btn.dataset.idx, 10);
      if (confirm('Remove this column?')) {
        B.CURRENT_TEMPLATE.columns.splice(idx, 1);
        B.buildTable(B.CURRENT_TEMPLATE.columns, B.extractCurrentItems());
      }
    });
  };
})();
