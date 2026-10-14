<?php
$pageTitle = 'Invoice Workspace';
require_once __DIR__ . '/header.php';

$db = get_db_connection();

$invId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$templateParam = isset($_GET['template']) ? trim($_GET['template']) : 'timesheet';
$actionParam = isset($_GET['action']) ? trim($_GET['action']) : '';
$autoPrint = isset($_GET['print']) && $_GET['print'] == 1;

$currentInvoice = null;
if ($invId > 0) {
    try {
        $stmt = $db->prepare("SELECT * FROM invoices WHERE id = ? LIMIT 1");
        $stmt->execute([$invId]);
        $currentInvoice = $stmt->fetch();
        if ($currentInvoice && !empty($currentInvoice['template_type'])) {
            $templateParam = $currentInvoice['template_type'];
        }
    } catch (Exception $e) {}
}

// Generate next invoice number if new
$nextInvNo = '0101';
if (!$currentInvoice) {
    try {
        $lastRow = $db->query("SELECT invoice_no FROM invoices ORDER BY id DESC LIMIT 1")->fetch();
        if ($lastRow && !empty($lastRow['invoice_no'])) {
            $cleanNum = intval(preg_replace('/[^0-9]/', '', $lastRow['invoice_no']));
            if ($cleanNum > 0) {
                $nextInvNo = str_pad($cleanNum + 1, 4, '0', STR_PAD_LEFT);
            }
        }
    } catch (Exception $e) {}
} else {
    $nextInvNo = $currentInvoice['invoice_no'];
}
?>

<!-- Google Fonts for High-Fidelity Invoice Typography -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Anton&family=Cairo:wght@400;700;900&family=Amiri:wght@400;700&family=Roboto+Slab:wght@400;700;900&family=Great+Vibes&family=Poppins:wght@400;600;700&family=Oswald:wght@400;600;700&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<style>
/* ── INVOICE WORKSPACE MERGED DESIGN ── */
.invoice-workspace-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 16px;
  background: #FFFFFF;
  border: 1px solid #E2E8F0;
  border-radius: 14px;
  padding: 14px 20px;
  margin-bottom: 18px;
  box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
}

.invoice-workspace-header__left {
  display: flex;
  align-items: center;
  gap: 14px;
  flex-wrap: wrap;
}

.invoice-badge-pill {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 4px 12px;
  border-radius: 9999px;
  font-size: 0.8rem;
  font-weight: 700;
  background: #EBF7EE;
  color: #076835;
  border: 1px solid #C4E7CE;
}

.invoice-workspace-header__right {
  display: flex;
  align-items: center;
  gap: 8px;
  flex-wrap: wrap;
}

/* ── TOOLBAR ── */
#invoiceToolbar {
  position: sticky;
  top: 72px;
  z-index: 40;
  background: #FFFFFF;
  border: 1px solid #CBD5E1;
  border-radius: 12px;
  box-shadow: 0 4px 18px rgba(15, 23, 42, 0.08);
  padding: 8px 12px;
  display: flex;
  flex-wrap: wrap;
  gap: 8px 12px;
  align-items: center;
  font: 13px 'Plus Jakarta Sans', Arial, sans-serif;
  margin-bottom: 20px;
}

#invoiceToolbar .grp {
  display: flex;
  align-items: center;
  gap: 4px;
  padding-right: 10px;
  border-right: 1px solid #E2E8F0;
}
#invoiceToolbar .grp:last-child {
  border-right: 0;
}

#invoiceToolbar button {
  height: 30px;
  min-width: 30px;
  padding: 0 9px;
  border: 1px solid #CBD5E1;
  background: #F8FAFC;
  border-radius: 6px;
  cursor: pointer;
  font: 13px inherit;
  color: #1E293B;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 4px;
  transition: all 0.15s ease;
}
#invoiceToolbar button:hover {
  background: #E2E8F0;
  color: #0F172A;
}
#invoiceToolbar button.primary {
  background: #076835;
  border-color: #076835;
  color: #FFFFFF;
  font-weight: 600;
}
#invoiceToolbar button.primary:hover {
  background: #055229;
}
#invoiceToolbar button.btn-amber {
  background: #F59E0B;
  border-color: #D97706;
  color: #FFFFFF;
  font-weight: 600;
}
#invoiceToolbar button.btn-amber:hover {
  background: #D97706;
}
#invoiceToolbar button.danger {
  color: #DC2626;
  border-color: #FECACA;
  background: #FEF2F2;
}
#invoiceToolbar button.danger:hover {
  background: #FEE2E2;
}

#invoiceToolbar select, 
#invoiceToolbar input[type=number],
#invoiceToolbar input[type=text] {
  height: 30px;
  border: 1px solid #CBD5E1;
  border-radius: 6px;
  background: #FFFFFF;
  font: 13px inherit;
  padding: 0 6px;
  color: #0F172A;
}
#invoiceToolbar select {
  max-width: 140px;
}
#invoiceToolbar input[type=number] {
  width: 50px;
}
#invoiceToolbar input[type=color] {
  width: 30px;
  height: 30px;
  padding: 1px;
  border: 1px solid #CBD5E1;
  border-radius: 6px;
  background: #FFFFFF;
  cursor: pointer;
}
#invoiceToolbar label {
  display: flex;
  align-items: center;
  gap: 4px;
  color: #475569;
  white-space: nowrap;
  font-size: 12px;
  cursor: pointer;
}
#invoiceToolbar .lbl {
  color: #64748B;
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
}
#savedStatus {
  color: #076835;
  font-size: 12px;
  font-weight: 600;
  margin-left: auto;
  display: flex;
  align-items: center;
  gap: 4px;
}

/* ── STAGE & SHEET (A4 INVOICE SPEC) ── */
.invoice-stage {
  padding: 10px 0 40px;
  display: flex;
  justify-content: center;
  overflow-x: auto;
}

.sheet {
  --blue: #2b44bd;
  --red: #e0472c;
  width: 794px;
  min-height: 1122px;
  display: flex;
  flex-direction: column;
  flex: none;
  background: #fffafc;
  padding: 28px 26px 26px;
  box-shadow: 0 8px 32px rgba(15, 23, 42, 0.15);
  color: var(--blue);
  font-family: Arial, Helvetica, sans-serif;
  border-radius: 2px;
  position: relative;
  transition: transform 0.2s ease;
}

.ed {
  outline: 0;
  min-width: 2px;
  cursor: text;
  word-break: break-word;
}
.ed:hover {
  background: rgba(43,68,189,.06);
}
.ed:focus {
  background: rgba(255,214,0,.2);
  box-shadow: 0 0 0 1.5px #f2a900;
}

.hd {
  display: grid;
  grid-template-columns: 1fr 100px 1fr;
  align-items: center;
  gap: 6px;
  margin-bottom: 8px;
}
.brand {
  font-family: Anton, Impact, 'Arial Narrow', sans-serif;
  font-size: 46px;
  line-height: 1.05;
  letter-spacing: .5px;
  text-transform: uppercase;
}
.sub {
  font-size: 17.5px;
  font-weight: 700;
  line-height: 1.2;
  margin-top: 3px;
}
.logo {
  width: 92px;
  height: 92px;
  margin: 0 auto;
  color: var(--blue);
}
.logo svg, .logo img {
  width: 100%;
  height: 100%;
  object-fit: contain;
  display: block;
}
.hd-right {
  direction: rtl;
  text-align: right;
}
.ar1 {
  font-family: Cairo, 'Segoe UI', Tahoma, sans-serif;
  font-weight: 900;
  font-size: 34px;
  line-height: 1.25;
}
.ar2 {
  font-family: Cairo, 'Segoe UI', Tahoma, sans-serif;
  font-weight: 700;
  font-size: 20px;
  line-height: 1.2;
}

.band {
  background: var(--blue);
  color: #fff;
  border-radius: 8px;
  padding: 5px 12px;
  text-align: center;
  font-weight: 700;
  font-size: 13px;
  line-height: 1.35;
  margin-bottom: 12px;
  border: 1px solid var(--blue);
}

.meta {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr;
  gap: 34px;
  margin-bottom: 12px;
}
.box {
  border: 1.6px solid var(--blue);
  border-radius: 10px;
  height: 54px;
  display: flex;
  align-items: center;
  padding: 0 12px;
}
.box.no {
  gap: 8px;
}
.script {
  font-family: 'Great Vibes', 'Brush Script MT', cursive;
  font-size: 36px;
  line-height: 1;
}
.red {
  color: var(--red);
  font-family: 'Roboto Slab', Georgia, serif;
  font-size: 30px;
  line-height: 1;
}
.box.inv {
  border: 4px double var(--blue);
  flex-direction: column;
  justify-content: center;
  gap: 0;
  padding: 0 6px;
}
.inv .a {
  font-family: Cairo, 'Segoe UI', sans-serif;
  font-weight: 700;
  font-size: 19px;
  line-height: 1.1;
}
.inv .e {
  font-family: 'Roboto Slab', Georgia, serif;
  font-weight: 900;
  font-size: 21px;
  letter-spacing: 4px;
  line-height: 1.1;
}
.box.date {
  gap: 4px;
  font-weight: 700;
  font-size: 14px;
}
.leader {
  flex: 1;
  border-bottom: 1.5px dotted var(--blue);
  min-height: 18px;
  align-self: flex-end;
  margin-bottom: 6px;
}
.arlbl {
  font-family: Cairo, 'Segoe UI', sans-serif;
  font-weight: 700;
  font-size: 15px;
}

.tablewrap {
  border: 4px double var(--blue);
  display: flex;
  flex: 1 1 auto;
  min-height: 0;
}
table.inv-grid {
  width: 100%;
  border-collapse: collapse;
  table-layout: fixed;
}
.sig {
  flex: none;
}
col.w-sl { width: 5.5%; }
col.w-name { width: 19.5%; }
col.w-in, col.w-out, col.w-job { width: 6%; }
col.w-total { width: 7%; }

table.inv-grid th {
  font-size: 12.5px;
  font-weight: 700;
  line-height: 1.15;
  color: var(--blue);
  border-left: 1.3px solid var(--blue);
  border-bottom: 1.5px solid var(--blue);
  padding: 6px 1px;
  text-align: center;
  vertical-align: middle;
}
table.inv-grid td {
  border-left: 1.3px solid var(--blue);
  border-bottom: 1.2px dotted rgba(43,68,189,.6);
  padding: 0;
  height: var(--inv-row-h, 28px);
  vertical-align: middle;
}
table.inv-grid th:first-child, table.inv-grid td:first-child {
  border-left: 0;
}
table.inv-grid th.mid, table.inv-grid td.mid {
  border-left: 2.4px solid var(--blue);
}
table.inv-grid td .ed {
  display: block;
  min-height: calc(var(--inv-row-h, 28px) - 2px);
  padding: 2px 4px;
  font-size: 12.5px;
  line-height: calc(var(--inv-row-h, 28px) - 4px);
  text-align: center;
  color: #17204f;
}
table.inv-grid td [data-col=name] {
  text-align: left;
}
table.inv-grid th .ed {
  display: block;
  padding: 0 1px;
}
table.inv-grid tbody tr:last-child td {
  border-bottom: 0;
}

.totalrow {
  display: none;
  justify-content: flex-end;
  align-items: center;
  gap: 8px;
  margin-top: 8px;
  font-weight: 700;
  font-size: 14px;
}
.totalrow .val {
  min-width: 80px;
  border: 1.5px solid var(--blue);
  border-radius: 6px;
  padding: 3px 8px;
  text-align: center;
}

.sig {
  display: flex;
  justify-content: space-between;
  gap: 40px;
  margin-top: 34px;
  font-weight: 700;
  font-size: 13.5px;
}
.sig .s {
  flex: 1;
  display: flex;
  align-items: flex-end;
  gap: 5px;
}
.sig .leader {
  margin-bottom: 4px;
}

/* ── COMMERCIAL TAX INVOICE TEMPLATE (ALTERNATE) ── */
.tax-sheet {
  --blue: #076835;
  --red: #d97706;
}
.tax-meta-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
  margin-bottom: 16px;
  font-size: 13px;
  border: 1.5px solid var(--blue);
  border-radius: 10px;
  padding: 12px 16px;
}
.tax-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
  border: 2px solid var(--blue);
}
.tax-table th {
  background: var(--blue);
  color: #fff;
  padding: 8px;
  font-size: 12px;
  font-weight: 700;
  border: 1px solid var(--blue);
}
.tax-table td {
  padding: var(--tax-cell-pad, 4px 8px);
  border: 1px solid #CBD5E1;
  font-size: 12px;
  height: var(--tax-row-h, 26px);
}
.tax-table td .ed {
  min-height: calc(var(--tax-row-h, 26px) - 2px);
  line-height: calc(var(--tax-row-h, 26px) - 4px);
}
.tax-totals-box {
  margin-top: 14px;
  margin-left: auto;
  width: 320px;
  border: 1.5px solid var(--blue);
  border-radius: 8px;
  overflow: hidden;
}
.tax-totals-row {
  display: flex;
  justify-content: space-between;
  padding: 6px 12px;
  font-size: 13px;
  border-bottom: 1px solid #E2E8F0;
}
.tax-totals-row:last-child {
  border-bottom: 0;
  background: #EBF7EE;
  font-weight: 800;
  color: var(--blue);
}

/* ── TOAST ── */
#invToast {
  position: fixed;
  left: 50%;
  bottom: 24px;
  transform: translateX(-50%);
  background: #0F172A;
  color: #FFFFFF;
  padding: 9px 18px;
  border-radius: 8px;
  font: 13px 'Plus Jakarta Sans', Arial, sans-serif;
  opacity: 0;
  pointer-events: none;
  transition: opacity .25s ease;
  z-index: 999;
  box-shadow: 0 4px 16px rgba(0,0,0,0.25);
  display: flex;
  align-items: center;
  gap: 8px;
}
#invToast.on {
  opacity: 1;
}

/* ── STRICT PRINT ISOLATION (A4 ONLY) ── */
@media print {
  @page {
    size: A4 portrait;
    margin: 0;
  }
  * {
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
  }
  html, body, .crm-body {
    background: #FFFFFF !important;
    margin: 0 !important;
    padding: 0 !important;
  }
  #crmSidebar, .crm-topbar, .crm-footer, #invoiceToolbar, .invoice-workspace-header, #invToast, .crm-sidebar-backdrop {
    display: none !important;
  }
  .crm-shell, .crm-viewport, .crm-main-content {
    margin: 0 !important;
    padding: 0 !important;
    display: block !important;
    width: 100% !important;
    background: #FFFFFF !important;
  }
  .invoice-stage {
    padding: 0 !important;
    display: block !important;
    margin: 0 !important;
  }
  .sheet {
    width: 210mm !important;
    min-height: 296mm !important;
    height: auto !important;
    padding: 10mm 10mm 9mm !important;
    margin: 0 auto !important;
    box-shadow: none !important;
    background: #FFFFFF !important;
    zoom: 1 !important;
    border: none !important;
  }
  .ed:hover, .ed:focus {
    background: none !important;
    box-shadow: none !important;
  }
}
</style>

<!-- ══════════════ INVOICE TOP WORKSPACE CONTROLS ══════════════ -->
<div class="invoice-workspace-header">
  <div class="invoice-workspace-header__left">
    <a href="invoices.php" class="btn btn--sm btn--outline">
      <i data-lucide="arrow-left"></i> All Invoices
    </a>
    <span class="invoice-badge-pill" id="badgeTemplateType">
      <i data-lucide="clock" style="width: 14px; height: 14px;"></i>
      <?php echo ($templateParam === 'tax') ? 'Commercial Tax Invoice' : 'Timesheet & Labour Hours (Active)'; ?>
    </span>
    <span style="font-size: 0.95rem; font-weight: 800; color: #0F172A;">
      Invoice #<span id="headerInvNo"><?php echo htmlspecialchars($nextInvNo); ?></span>
    </span>
  </div>

  <div class="invoice-workspace-header__right">
    <!-- Template Switcher -->
    <select id="templateSelector" class="form-select" style="max-width: 200px; height: 34px; font-size: 0.85rem;" onchange="switchTemplate(this.value)">
      <option value="timesheet" <?php echo ($templateParam !== 'tax') ? 'selected' : ''; ?>>Timesheet Template</option>
      <option value="tax" <?php echo ($templateParam === 'tax') ? 'selected' : ''; ?>>Commercial Tax (5% VAT)</option>
    </select>

    <!-- Status Selector -->
    <select id="invoiceStatus" class="form-select" style="max-width: 130px; height: 34px; font-size: 0.85rem;">
      <option value="Draft" <?php echo ($currentInvoice && $currentInvoice['status'] === 'Draft') ? 'selected' : ''; ?>>Draft</option>
      <option value="Issued" <?php echo ($currentInvoice && $currentInvoice['status'] === 'Issued') ? 'selected' : ''; ?>>Issued</option>
      <option value="Paid" <?php echo ($currentInvoice && $currentInvoice['status'] === 'Paid') ? 'selected' : ''; ?>>Paid</option>
    </select>

    <!-- Save to CRM DB Button -->
    <button type="button" class="btn btn--sm btn--primary" id="btnSaveToCRM" onclick="saveToCRM()">
      <i data-lucide="save"></i> Save Invoice
    </button>
  </div>
</div>

<!-- ══════════════ FLOATING EDITING TOOLBAR ══════════════ -->
<div id="invoiceToolbar">
  <div class="grp">
    <select id="fontSel" title="Font family">
      <option value="Arial" style="font-family:Arial">Arial</option>
      <option value="Verdana" style="font-family:Verdana">Verdana</option>
      <option value="Tahoma" style="font-family:Tahoma">Tahoma</option>
      <option value="Trebuchet MS" style="font-family:'Trebuchet MS'">Trebuchet MS</option>
      <option value="Times New Roman" style="font-family:'Times New Roman'">Times New Roman</option>
      <option value="Georgia" style="font-family:Georgia">Georgia</option>
      <option value="Courier New" style="font-family:'Courier New'">Courier New</option>
      <option value="Anton" style="font-family:Anton">Anton</option>
      <option value="Oswald" style="font-family:Oswald">Oswald</option>
      <option value="Roboto Slab" style="font-family:'Roboto Slab'">Roboto Slab</option>
      <option value="Poppins" style="font-family:Poppins">Poppins</option>
      <option value="Montserrat" style="font-family:Montserrat">Montserrat</option>
      <option value="Great Vibes" style="font-family:'Great Vibes'">Great Vibes</option>
      <option value="Cairo" style="font-family:Cairo">Cairo (Arabic)</option>
      <option value="Amiri" style="font-family:Amiri">Amiri (Arabic)</option>
    </select>
    <button data-act="sizeDown" title="Smaller">A−</button>
    <input type="number" id="fontSize" min="6" max="120" value="13" title="Font size (px)">
    <button data-act="sizeUp" title="Larger">A+</button>
  </div>

  <div class="grp">
    <button data-act="bold" title="Bold (Ctrl+B)"><b>B</b></button>
    <button data-act="italic" title="Italic (Ctrl+I)"><i>I</i></button>
    <button data-act="underline" title="Underline (Ctrl+U)"><u>U</u></button>
    <button data-act="strike" title="Strikethrough"><s>S</s></button>
  </div>

  <div class="grp">
    <span class="lbl">Text</span><input type="color" id="colText" value="#2b44bd" title="Text colour">
    <span class="lbl">Hi</span><input type="color" id="colHi" value="#ffe600" title="Highlight colour">
    <button data-act="noHi" title="Remove highlight">None</button>
  </div>

  <div class="grp">
    <button data-act="left" title="Align left">&#8676;</button>
    <button data-act="center" title="Centre">&#8596;</button>
    <button data-act="right" title="Align right">&#8677;</button>
    <button data-act="clear" title="Clear formatting">Clear</button>
    <button data-act="undo" title="Undo">&#8630;</button>
    <button data-act="redo" title="Redo">&#8631;</button>
  </div>

  <div class="grp">
    <span class="lbl">Theme</span><input type="color" id="colAccent" value="<?php echo htmlspecialchars($currentInvoice['accent_color'] ?? '#2b44bd'); ?>" title="Theme colour (lines, header, band)">
    <span class="lbl">No.</span><input type="color" id="colRed" value="<?php echo htmlspecialchars($currentInvoice['red_color'] ?? '#e0472c'); ?>" title="Invoice number colour">
  </div>

  <div class="grp" id="invoiceRowControlsGrp">
    <button data-act="addRow" title="Add 1 Row">+ Row</button>
    <button data-act="add5Rows" title="Add 5 Rows">+ 5 Rows</button>
    <button data-act="delRow" title="Remove Last Row">− Row</button>
    
    <span class="lbl" style="margin-left: 4px;">Height</span>
    <select id="invDensitySel" title="Adjust row height to fit 15-30+ rows on 1 A4 page">
      <option value="compact">Compact (22px - Fit 30+)</option>
      <option value="standard" selected>Standard (26px - Fit 20+)</option>
      <option value="comfortable">Spacious (32px - Fit 12+)</option>
    </select>
    <button type="button" id="btnInvRowSmaller" title="Make rows smaller">A−</button>
    <span id="invRowHDisplay" style="font-size: 11px; font-weight: 700; color: #F59E0B;">26px</span>
    <button type="button" id="btnInvRowLarger" title="Make rows larger">A+</button>
  </div>

  <div class="grp" id="timesheetAutoCalcGrp">
    <label><input type="checkbox" id="autoCalc" <?php echo (!isset($currentInvoice['auto_calc']) || $currentInvoice['auto_calc'] == 1) ? 'checked' : ''; ?>> Auto hrs</label>
    <label><input type="checkbox" id="showTotal" <?php echo (!empty($currentInvoice['show_total']) && $currentInvoice['show_total'] == 1) ? 'checked' : ''; ?>> Total</label>
  </div>

  <div class="grp">
    <button data-act="logo" title="Upload company stamp/logo">Change logo</button>
    <button data-act="logoReset" title="Reset to official SDSC logo">Reset logo</button>
    <input type="file" id="logoFile" accept="image/*" hidden>
  </div>

  <div class="grp">
    <button data-act="new" class="btn-amber" title="Increment invoice number and clear rows">New invoice</button>
    <button data-act="png" class="primary" title="Export as 300 DPI PNG">Save PNG</button>
    <button data-act="jpg" class="primary" title="Export as JPG">Save JPG</button>
    <button data-act="print" class="primary" title="Print or save as PDF (Ctrl+P)">Print A4 / PDF</button>
    <button data-act="reset" class="danger" title="Reset layout">Reset</button>
  </div>

  <span id="savedStatus"><i data-lucide="check" style="width: 14px; height: 14px;"></i> Live Auto-Save</span>
</div>

<!-- ══════════════ MAIN INVOICE STAGE & SHEET ══════════════ -->
<main class="invoice-stage">
  <div class="sheet <?php echo ($templateParam === 'tax') ? 'tax-sheet' : ''; ?>" id="sheet" style="--blue: <?php echo htmlspecialchars($currentInvoice['accent_color'] ?? '#2b44bd'); ?>; --red: <?php echo htmlspecialchars($currentInvoice['red_color'] ?? '#e0472c'); ?>;">

    <?php if ($templateParam === 'tax'): ?>
      <!-- ── COMMERCIAL TAX INVOICE TEMPLATE ── -->
      <div class="hd">
        <div>
          <div class="ed brand" contenteditable="true">Shabab Deira</div>
          <div class="ed sub" contenteditable="true">Shipping &amp; Cargo Services LLC</div>
        </div>
        <div class="logo" id="logo"></div>
        <div class="hd-right">
          <div class="ed ar1" contenteditable="true">شباب ديرة لخدمات</div>
          <div class="ed ar2" contenteditable="true">الملاحة والشحن ش.ذ.م.م</div>
        </div>
      </div>

      <div class="band">
        <div class="ed" contenteditable="true">Mob : +971 55 457 2414, +971 50 150 7188, P.O.Box: 91856, Naif, Deira, Dubai - U.A.E.</div>
        <div class="ed" contenteditable="true">E-mail: sdsc78682@gmail.com • TRN: 100492817200003</div>
      </div>

      <div class="meta" style="margin-bottom: 14px;">
        <div class="box no">
          <span class="ed script" contenteditable="true">No.</span>
          <span class="ed red" id="invNo" contenteditable="true"><?php echo htmlspecialchars($nextInvNo); ?></span>
        </div>
        <div class="box inv">
          <div class="ed a" contenteditable="true">فــاتــورة ضـريـبـيـة</div>
          <div class="ed e" contenteditable="true" style="font-size: 16px; letter-spacing: 2px;">TAX INVOICE</div>
        </div>
        <div class="box date">
          <span class="ed" contenteditable="true">DATE:</span>
          <span class="ed leader" id="invDate" contenteditable="true"><?php echo htmlspecialchars($currentInvoice['invoice_date'] ?? date('d/m/Y')); ?></span>
          <span class="ed arlbl" contenteditable="true">التاريخ</span>
        </div>
      </div>

      <!-- Client Details & Tax Info Grid -->
      <div class="tax-meta-grid">
        <div>
          <strong style="color: var(--blue);">BILLED TO / العميل:</strong>
          <div class="ed" id="taxClientCompany" contenteditable="true" style="font-weight: 700; font-size: 14px; margin-top: 4px;"><?php echo htmlspecialchars($currentInvoice['client_company'] ?: 'DP World Logistics LLC'); ?></div>
          <div class="ed" id="taxClientName" contenteditable="true" style="color: #475569;"><?php echo htmlspecialchars($currentInvoice['client_name'] ?: 'Attn: Procurement & Operations Manager'); ?></div>
          <div class="ed" contenteditable="true" style="color: #475569;">Jebel Ali Free Zone, Dubai, United Arab Emirates</div>
          <div class="ed" contenteditable="true" style="color: #475569; font-weight: 600;">Client TRN: 100234589100003</div>
        </div>
        <div>
          <strong style="color: var(--blue);">SUPPLY DETAILS / بيانات التوريد:</strong>
          <div style="margin-top: 4px;"><strong>Service Scope:</strong> <span class="ed" contenteditable="true">Container Packaging &amp; Warehouse Manpower</span></div>
          <div><strong>Payment Terms:</strong> <span class="ed" contenteditable="true">15 Days from Invoice Date</span></div>
          <div><strong>Site Location:</strong> <span class="ed" contenteditable="true">Naif / JAFZA Operations Depot</span></div>
          <div><strong>Currency:</strong> <span class="ed" contenteditable="true">UAE Dirham (AED)</span></div>
        </div>
      </div>

      <!-- Line Items Tax Table -->
      <table class="tax-table" id="taxGrid">
        <thead>
          <tr>
            <th style="width: 6%;">#</th>
            <th style="width: 44%;">Description &amp; Trade Specification</th>
            <th style="width: 12%;">Workers</th>
            <th style="width: 12%;">Duration</th>
            <th style="width: 12%;">Rate (AED)</th>
            <th style="width: 14%;">Total (AED)</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="text-align: center;"><div class="ed" contenteditable="true">1</div></td>
            <td><div class="ed" contenteditable="true">Warehouse Loading &amp; Unloading Helpers</div></td>
            <td style="text-align: center;"><div class="ed" contenteditable="true">8</div></td>
            <td style="text-align: center;"><div class="ed" contenteditable="true">26 Days</div></td>
            <td style="text-align: right;"><div class="ed" contenteditable="true">120.00</div></td>
            <td style="text-align: right; font-weight: 700;"><div class="ed" contenteditable="true">24,960.00</div></td>
          </tr>
          <tr>
            <td style="text-align: center;"><div class="ed" contenteditable="true">2</div></td>
            <td><div class="ed" contenteditable="true">Container Packaging &amp; Palletizing Specialists</div></td>
            <td style="text-align: center;"><div class="ed" contenteditable="true">4</div></td>
            <td style="text-align: center;"><div class="ed" contenteditable="true">26 Days</div></td>
            <td style="text-align: right;"><div class="ed" contenteditable="true">140.00</div></td>
            <td style="text-align: right; font-weight: 700;"><div class="ed" contenteditable="true">14,560.00</div></td>
          </tr>
          <tr>
            <td style="text-align: center;"><div class="ed" contenteditable="true">3</div></td>
            <td><div class="ed" contenteditable="true">Site Logistics Supervisor &amp; Safety Compliance</div></td>
            <td style="text-align: center;"><div class="ed" contenteditable="true">1</div></td>
            <td style="text-align: center;"><div class="ed" contenteditable="true">26 Days</div></td>
            <td style="text-align: right;"><div class="ed" contenteditable="true">180.00</div></td>
            <td style="text-align: right; font-weight: 700;"><div class="ed" contenteditable="true">4,680.00</div></td>
          </tr>
        </tbody>
      </table>

      <!-- Tax Totals & Bank Wire Details -->
      <div style="display: flex; justify-content: space-between; gap: 20px; margin-top: 16px; flex-wrap: wrap;">
        <div style="flex: 1; border: 1.5px solid var(--blue); border-radius: 8px; padding: 10px 14px; font-size: 12.5px;">
          <strong style="color: var(--blue);">BANK WIRE REMITTANCE DETAILS:</strong>
          <div style="margin-top: 4px;">Account Name: <span class="ed" contenteditable="true" style="font-weight: 700;">Shabab Deira Shipping &amp; Cargo Services LLC</span></div>
          <div>Bank: <span class="ed" contenteditable="true">Emirates NBD Bank PJSC, Dubai</span></div>
          <div>IBAN: <span class="ed" contenteditable="true" style="font-family: monospace; font-weight: 700;">AE540260000123456789001</span></div>
          <div>SWIFT / BIC: <span class="ed" contenteditable="true" style="font-family: monospace;">EBILAEADXXX</span></div>
        </div>

        <div class="tax-totals-box">
          <div class="tax-totals-row">
            <span>Net Taxable Subtotal:</span>
            <span class="ed" id="taxSubtotal" contenteditable="true">AED 44,200.00</span>
          </div>
          <div class="tax-totals-row">
            <span>VAT (5% FTA Standard):</span>
            <span class="ed" id="taxVat" contenteditable="true">AED 2,210.00</span>
          </div>
          <div class="tax-totals-row" style="font-size: 14px;">
            <span>Grand Total (AED):</span>
            <span class="ed" id="taxGrandTotal" contenteditable="true">AED 46,410.00</span>
          </div>
        </div>
      </div>

      <div class="sig" style="margin-top: 28px;">
        <div class="s">
          <span class="ed" contenteditable="true">Client Acceptance Signature</span>
          <span class="ed leader" contenteditable="true"></span>
          <span class="ed arlbl" contenteditable="true">توقيع العميل</span>
        </div>
        <div class="s">
          <span class="ed" contenteditable="true">Authorized Manager Stamp</span>
          <span class="ed leader" contenteditable="true"></span>
          <span class="ed arlbl" contenteditable="true">الختم والاعتماد</span>
        </div>
      </div>

    <?php else: ?>
      <!-- ── TIMESHEET & LABOUR HOURS TEMPLATE (FROM INVOICE.HTML) ── -->
      <div class="hd">
        <div>
          <div class="ed brand" contenteditable="true">Shabab Deira</div>
          <div class="ed sub" contenteditable="true">Shipping &amp; Cargo Services LLC</div>
        </div>
        <div class="logo" id="logo"></div>
        <div class="hd-right">
          <div class="ed ar1" contenteditable="true">شباب ديرة لخدمات</div>
          <div class="ed ar2" contenteditable="true">الملاحة والشحن ش.ذ.م.م</div>
        </div>
      </div>

      <div class="band">
        <div class="ed" contenteditable="true">Mob : +971 55 457 2414, +971 50 150 7188, P.O.Box: 91856, Naif, Deira, Dubai - U.A.E.</div>
        <div class="ed" contenteditable="true">E-mail: sdsc78682@gmail.com</div>
      </div>

      <div class="meta">
        <div class="box no">
          <span class="ed script" contenteditable="true">No.</span>
          <span class="ed red" id="invNo" contenteditable="true"><?php echo htmlspecialchars($nextInvNo); ?></span>
        </div>
        <div class="box inv">
          <div class="ed a" contenteditable="true">فــاتــورة</div>
          <div class="ed e" contenteditable="true">INVOICE</div>
        </div>
        <div class="box date">
          <span class="ed" contenteditable="true">DATE:</span>
          <span class="ed leader" id="invDate" contenteditable="true"><?php echo htmlspecialchars($currentInvoice['invoice_date'] ?? date('d/m/Y')); ?></span>
          <span class="ed arlbl" contenteditable="true">التاريخ</span>
        </div>
      </div>

      <div class="tablewrap"><table class="inv-grid" id="grid"></table></div>

      <div class="totalrow" id="totalRow">
        <span class="ed" contenteditable="true">TOTAL HRS:</span>
        <span class="ed val" id="grandTotal" contenteditable="true"><?php echo htmlspecialchars($currentInvoice['total_hours'] ?? ''); ?></span>
      </div>

      <div class="sig">
        <div class="s">
          <span class="ed" contenteditable="true">Receiver's Signature</span>
          <span class="ed leader" id="sigL" contenteditable="true"></span>
          <span class="ed arlbl" contenteditable="true">توقيع المستلم</span>
        </div>
        <div class="s">
          <span class="ed" contenteditable="true">Signature</span>
          <span class="ed leader" id="sigR" contenteditable="true"></span>
          <span class="ed arlbl" contenteditable="true">التوقيع</span>
        </div>
      </div>
    <?php endif; ?>

  </div>
</main>

<div id="invToast"></div>

<!-- ══════════════ INVOICE INTERACTIVE SCRIPT ENGINE ══════════════ -->
<script>
(function(){
  var KEY = 'sd_invoice_crm_' + <?php echo json_encode($invId ? 'id_' . $invId : 'active'); ?>;
  var SERVER_INVOICE_ID = <?php echo intval($invId); ?>;
  var CURRENT_TEMPLATE = <?php echo json_encode($templateParam); ?>;
  var AUTO_PRINT = <?php echo $autoPrint ? 'true' : 'false'; ?>;

  var $ = function(id){ return document.getElementById(id); };
  var sheet = $('sheet'), toolbar = $('invoiceToolbar');
  var getGrid = function(){ return sheet.querySelector('#grid'); };
  var COLS = [['sl','SI.No'],['name','LABOUR NAME'],['in','TIME<br>IN'],['out','TIME<br>OUT'],['job','JOB<br>No.'],['total','TOTAL<br>Hrs.']];
  var DEFAULT_ROWS = 19;
  var LOGO_SVG = '<svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><g fill="none" stroke="currentColor"><circle cx="50" cy="50" r="46" stroke-width="4"/><circle cx="50" cy="50" r="40" stroke-width="1.5"/><ellipse cx="50" cy="50" rx="17" ry="40" stroke-width="1.5"/><ellipse cx="50" cy="50" rx="31" ry="40" stroke-width="1.5"/><path d="M50 10V90M14 28H86M14 72H86" stroke-width="1.5"/></g><rect x="10" y="39" width="80" height="22" rx="11" fill="currentColor"/><text x="50" y="55.5" text-anchor="middle" font-size="16" font-weight="700" font-style="italic" fill="#fff" font-family="Georgia,serif">SDSC</text></svg>';

  /* ---------- table builder ---------- */
  function cell(col,half,txt){
    return '<td class="'+(half&&col==='sl'?'mid':'')+'"><div class="ed" contenteditable="true" data-col="'+col+'" data-half="'+half+'">'+(txt||'')+'</div></td>';
  }
  function rowHTML(i,n){
    return '<tr>'+[0,1].map(function(h){return COLS.map(function(c){return cell(c[0],h,c[0]==='sl'?(h?n+i+1:i+1):'')}).join('')}).join('')+'</tr>';
  }
  function buildTable(n){
    if (!getGrid()) return;
    var cg='<colgroup>'+[0,1].map(function(){return COLS.map(function(c){return '<col class="w-'+c[0]+'">'}).join('')}).join('')+'</colgroup>';
    var th='<thead><tr>'+[0,1].map(function(h){return COLS.map(function(c){return '<th class="'+(h&&c[0]==='sl'?'mid':'')+'"><div class="ed" contenteditable="true">'+c[1]+'</div></th>'}).join('')}).join('')+'</tr></thead>';
    var b='<tbody>';for(var i=0;i<n;i++)b+=rowHTML(i,n);b+='</tbody>';
    getGrid().innerHTML=cg+th+b;
  }
  function renumber(){
    if (!getGrid()) return;
    var rows=getGrid().tBodies[0].rows,n=rows.length;
    Array.prototype.forEach.call(rows,function(tr,i){
      tr.querySelector('[data-col=sl][data-half="0"]').textContent=i+1;
      tr.querySelector('[data-col=sl][data-half="1"]').textContent=n+i+1;
    });
  }
  function handleAddRow(){
    if (CURRENT_TEMPLATE === 'tax') {
      taxAddRow();
    } else {
      addRow();
    }
  }

  function handleAdd5Rows(){
    for(var k = 0; k < 5; k++){
      if (CURRENT_TEMPLATE === 'tax') taxAddRow();
      else addRow();
    }
    toast('Added 5 rows. Current: ' + getRowCount() + ' rows.');
  }

  function handleDelRow(){
    if (CURRENT_TEMPLATE === 'tax') {
      taxDelRow();
    } else {
      delRow();
    }
  }

  function getRowCount(){
    if (CURRENT_TEMPLATE === 'tax') {
      var tb = document.querySelector('#taxGrid tbody');
      return tb ? tb.rows.length : 0;
    }
    return (getGrid() && getGrid().tBodies[0]) ? getGrid().tBodies[0].rows.length : 0;
  }

  function addRow(){
    if (!getGrid()) return;
    var tb=getGrid().tBodies[0];
    if(tb.rows.length>=70){toast('Max rows reached');return}
    tb.insertAdjacentHTML('beforeend',rowHTML(tb.rows.length,tb.rows.length+1));
    renumber();schedule();
  }

  function delRow(){
    if (!getGrid()) return;
    var tb=getGrid().tBodies[0];
    if(tb.rows.length<=1)return;
    tb.deleteRow(tb.rows.length-1);renumber();sumTotals();schedule();
  }

  function taxAddRow(){
    var tb = document.querySelector('#taxGrid tbody');
    if (!tb) return;
    if (tb.rows.length >= 50) { toast('Max tax rows reached'); return; }
    var n = tb.rows.length + 1;
    var tr = document.createElement('tr');
    tr.innerHTML = '<td style="text-align: center;"><div class="ed" contenteditable="true">' + n + '</div></td>' +
      '<td><div class="ed" contenteditable="true">Logistics Crew / Warehouse Manpower Supply</div></td>' +
      '<td style="text-align: center;"><div class="ed" contenteditable="true">1</div></td>' +
      '<td style="text-align: center;"><div class="ed" contenteditable="true">1 Day</div></td>' +
      '<td style="text-align: right;"><div class="ed" contenteditable="true">120.00</div></td>' +
      '<td style="text-align: right; font-weight: 700;"><div class="ed" contenteditable="true">120.00</div></td>';
    tb.appendChild(tr);
    taxRenumber();
    calcTaxTotals();
    schedule();
  }

  function taxDelRow(){
    var tb = document.querySelector('#taxGrid tbody');
    if (!tb || tb.rows.length <= 1) return;
    tb.deleteRow(tb.rows.length - 1);
    taxRenumber();
    calcTaxTotals();
    schedule();
  }

  function taxRenumber(){
    var tb = document.querySelector('#taxGrid tbody');
    if (!tb) return;
    Array.prototype.forEach.call(tb.rows, function(tr, i){
      var c = tr.cells[0].querySelector('.ed');
      if (c) c.textContent = i + 1;
    });
  }

  function calcTaxTotals(){
    var tb = document.querySelector('#taxGrid tbody');
    if (!tb) return;
    var sum = 0;
    Array.prototype.forEach.call(tb.rows, function(tr){
      var totCell = tr.cells[5] ? tr.cells[5].querySelector('.ed') : null;
      if (totCell) {
        var numVal = parseFloat(totCell.textContent.replace(/[^0-9.]/g, '')) || 0;
        sum += numVal;
      }
    });
    sum = Math.round(sum * 100) / 100;
    var subEl = $('taxSubtotal');
    var vatEl = $('taxVat');
    var grandEl = $('taxGrandTotal');
    if (subEl) subEl.textContent = 'AED ' + sum.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    var vat = Math.round(sum * 0.05 * 100) / 100;
    if (vatEl) vatEl.textContent = 'AED ' + vat.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    var grand = Math.round((sum + vat) * 100) / 100;
    if (grandEl) grandEl.textContent = 'AED ' + grand.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  var currentInvRowH = 26;
  function setInvoiceRowHeight(px){
    px = Math.max(18, Math.min(46, px));
    currentInvRowH = px;
    sheet.style.setProperty('--inv-row-h', px + 'px');
    sheet.style.setProperty('--tax-row-h', px + 'px');
    var pad = Math.max(1, Math.floor((px - 18) / 3));
    sheet.style.setProperty('--tax-cell-pad', pad + 'px 8px');
    var disp = $('invRowHDisplay');
    if (disp) disp.textContent = px + 'px';
  }

  /* ---------- time arithmetic ---------- */
  function parseT(s){
    s=s.trim().toLowerCase().replace(/\s+/g,'');
    var m=s.match(/^(\d{1,2})(?:[:.h](\d{2}))?(am|pm)?$/);if(!m)return null;
    var h=+m[1],mi=m[2]?+m[2]:0;
    if(m[3]==='pm'&&h<12)h+=12;if(m[3]==='am'&&h===12)h=0;
    if(h>24||mi>59)return null;return h*60+mi;
  }
  function parseDur(s){
    s=s.trim();var m=s.match(/^(\d+):(\d{2})$/);if(m)return +m[1]*60+ +m[2];
    if(/^\d+(\.\d+)?$/.test(s))return Math.round(parseFloat(s)*60);return 0;
  }
  function fmt(m){var h=Math.floor(m/60),r=m%60;return r?h+':'+('0'+r).slice(-2):''+h}
  function sanitizeNum(el){
    var t=el.textContent,c=t.replace(/[^0-9.]/g,'');
    var p=c.split('.');if(p.length>2)c=p[0]+'.'+p.slice(1).join('');
    if(c!==t){el.textContent=c;caretEnd(el);}
    return c;
  }
  function calcRow(ed){
    if(!$('autoCalc') || !$('autoCalc').checked)return;
    var tr=ed.closest('tr'),h=ed.dataset.half;
    if (!tr || !h) return;
    var q=function(c){return tr.querySelector('[data-col="'+c+'"][data-half="'+h+'"]')};
    var tot=q('total');
    if(!tot) return;
    if(tot.textContent.trim()&&!tot.dataset.auto)return;
    var inEl = q('in'), outEl = q('out');
    if (!inEl || !outEl) return;
    var a=parseT(inEl.textContent),b=parseT(outEl.textContent);
    if(a===null||b===null){if(tot.dataset.auto){tot.textContent='';delete tot.dataset.auto}return}
    var d=b-a;if(d<0)d+=1440;
    tot.textContent=fmt(d);tot.dataset.auto='1';
  }
  function sumTotals(){
    if(!$('showTotal') || !$('showTotal').checked)return;
    var g=$('grandTotal');if(!g || g.dataset.manual)return;
    var m=0;
    if (getGrid()) {
      getGrid().querySelectorAll('[data-col=total]').forEach(function(e){m+=parseDur(e.textContent)});
    }
    g.textContent=m?fmt(m):'';
  }

  /* ---------- editing events ---------- */
  sheet.addEventListener('input',function(e){
    var ed=e.target.closest&&e.target.closest('.ed');if(!ed)return;
    var col=ed.dataset.col;
    var taxTd=ed.closest?ed.closest('#taxGrid tbody td'):null;
    if(taxTd&&(taxTd.cellIndex===4||taxTd.cellIndex===5))sanitizeNum(ed);
    if(col==='total')delete ed.dataset.auto;
    if(ed.id==='grandTotal')ed.dataset.manual='1';
    if(col==='in'||col==='out')calcRow(ed);
    if(col==='total'||col==='in'||col==='out')sumTotals();
    if(ed.id==='invNo'){
      var t=ed.textContent.trim();
      $('headerInvNo').textContent=t;
    }
    if (CURRENT_TEMPLATE === 'tax') calcTaxTotals();
    schedule();
  });

  sheet.addEventListener('paste',function(e){
    var ed=e.target.closest&&e.target.closest('.ed');if(!ed)return;
    e.preventDefault();
    var t=(e.clipboardData||window.clipboardData).getData('text/plain');
    if(ed.closest('td'))t=t.replace(/\s*[\r\n]+\s*/g,' ');
    document.execCommand('insertText',false,t);
  });

  function caretEnd(el){
    var r=document.createRange();r.selectNodeContents(el);r.collapse(false);
    var s=getSelection();s.removeAllRanges();s.addRange(r);
  }
  function moveV(ed,dir){
    var td=ed.closest('td');if(!td)return false;
    var tr=td.parentElement,idx=td.cellIndex;
    var tgt=dir>0?tr.nextElementSibling:tr.previousElementSibling;
    if(!tgt&&dir>0){
      if (CURRENT_TEMPLATE === 'tax') taxAddRow();
      else addRow();
      tgt=tr.nextElementSibling;
    }
    if(!tgt)return true;
    var n=tgt.cells[idx].querySelector('.ed');if(n){n.focus();caretEnd(n);return true;}
    return false;
  }
  sheet.addEventListener('keydown',function(e){
    var ed=e.target.closest&&e.target.closest('.ed');if(!ed)return;
    if(e.key==='Enter'){
      e.preventDefault();
      if(!e.shiftKey&&moveV(ed,1))return;
      document.execCommand('insertLineBreak');
    }else if((e.key==='ArrowDown'||e.key==='ArrowUp')&&!e.shiftKey&&!e.ctrlKey&&!e.altKey&&ed.closest('td')){
      e.preventDefault();moveV(ed,e.key==='ArrowDown'?1:-1);
    }
  });

  /* ---------- selection tracking for toolbar styling ---------- */
  var lastRange=null,lastEd=null;
  function edOf(node){
    if(!node)return null;node=node.nodeType===3?node.parentElement:node;
    return node&&node.closest?node.closest('.ed[contenteditable]'):null;
  }
  document.addEventListener('selectionchange',function(){
    var s=getSelection();if(!s.rangeCount)return;
    var r=s.getRangeAt(0),ed=edOf(r.commonAncestorContainer);
    if(ed&&sheet.contains(ed)){lastRange=r.cloneRange();lastEd=ed;
      if(!toolbar.contains(document.activeElement))syncToolbar(ed,s)}
  });
  function syncToolbar(ed,s){
    var el=edOf(s.anchorNode)||ed;
    var cs=getComputedStyle(s.anchorNode&&s.anchorNode.nodeType===3?s.anchorNode.parentElement:el);
    $('fontSize').value=Math.round(parseFloat(cs.fontSize));
    var fam=cs.fontFamily.split(',')[0].replace(/["']/g,'').trim(),sel=$('fontSel');
    sel.value=fam;if(sel.value!==fam)sel.selectedIndex=-1;
  }
  function restore(){
    if(!lastEd)return false;
    lastEd.focus({preventScroll:true});
    if(lastRange){var s=getSelection();s.removeAllRanges();s.addRange(lastRange)}
    return true;
  }
  function withSel(fn){
    if(!restore()){toast('Click an editable invoice field first');return}
    var s=getSelection();fn(s.isCollapsed,lastEd);
    var r=s.rangeCount?s.getRangeAt(0):null;if(r)lastRange=r.cloneRange();
    schedule();
  }
  function css(){document.execCommand('styleWithCSS',false,true)}
  function clearChildProp(ed,prop){ed.querySelectorAll('[style]').forEach(function(n){n.style[prop]=''})}

  function setFamily(v){withSel(function(c,ed){
    if(c){ed.style.fontFamily="'"+v+"'";clearChildProp(ed,'fontFamily')}
    else{css();document.execCommand('fontName',false,v)}
  })}
  function setSize(px){px=Math.max(6,Math.min(120,Math.round(px)));$('fontSize').value=px;withSel(function(c,ed){
    if(c){ed.style.fontSize=px+'px';clearChildProp(ed,'fontSize')}
    else{
      css();document.execCommand('fontSize',false,'7');
      sheet.querySelectorAll('font[size="7"],span[style*="xxx-large"]').forEach(function(n){
        if(n.tagName==='FONT'){var sp=document.createElement('span');sp.innerHTML=n.innerHTML;sp.style.fontSize=px+'px';n.replaceWith(sp)}
        else n.style.fontSize=px+'px';
      });
    }
  })}
  function toggle(cmd,prop,on,off){withSel(function(c,ed){
    if(c){
      var cur=getComputedStyle(ed);
      if(prop==='fontWeight')ed.style.fontWeight=parseInt(cur.fontWeight)>=600?'400':'700';
      else if(prop==='fontStyle')ed.style.fontStyle=cur.fontStyle==='italic'?'normal':'italic';
      else ed.style.textDecoration=(ed.style.textDecoration||'').indexOf(on)>-1?'none':on;
      clearChildProp(ed,prop);
    }else{css();document.execCommand(cmd)}
  })}
  function setColor(v){withSel(function(c,ed){
    if(c){ed.style.color=v;clearChildProp(ed,'color')}else{css();document.execCommand('foreColor',false,v)}
  })}
  function setHi(v){withSel(function(c,ed){
    if(c)ed.style.background=v;else{css();document.execCommand('hiliteColor',false,v)}
  })}
  function align(v){withSel(function(c,ed){ed.style.textAlign=v})}
  function clearFmt(){withSel(function(c,ed){
    if(c){ed.removeAttribute('style');ed.innerHTML=ed.innerHTML.replace(/<(?!br\s*\/?>)[^>]+>/gi,'')}
    else document.execCommand('removeFormat');
  })}

  /* ---------- toolbar event wiring ---------- */
  toolbar.addEventListener('mousedown',function(e){if(e.target.closest('button'))e.preventDefault()});
  $('fontSel').addEventListener('change',function(){setFamily(this.value)});
  $('fontSize').addEventListener('change',function(){setSize(+this.value||13)});
  $('colText').addEventListener('change',function(){setColor(this.value)});
  $('colHi').addEventListener('change',function(){setHi(this.value)});
  $('colAccent').addEventListener('input',function(){sheet.style.setProperty('--blue',this.value);schedule()});
  $('colRed').addEventListener('input',function(){sheet.style.setProperty('--red',this.value);schedule()});
  
  if ($('autoCalc')) $('autoCalc').addEventListener('change',schedule);
  if ($('showTotal')) $('showTotal').addEventListener('change',function(){applyTotalToggle();sumTotals();schedule()});
  function applyTotalToggle(){
    if ($('totalRow')) $('totalRow').style.display=$('showTotal')&&$('showTotal').checked?'flex':'none';
  }

  function setLogo(html){ if($('logo')) { $('logo').innerHTML=html; schedule(); } }
  $('logoFile').addEventListener('change',function(){
    var f=this.files[0];if(!f)return;
    var rd=new FileReader();
    rd.onload=function(){
      var img=new Image();
      img.onload=function(){
        var s=Math.min(1,320/Math.max(img.width,img.height)),cv=document.createElement('canvas');
        cv.width=Math.round(img.width*s);cv.height=Math.round(img.height*s);
        cv.getContext('2d').drawImage(img,0,0,cv.width,cv.height);
        setLogo('<img alt="Logo" src="'+cv.toDataURL('image/png')+'">');
      };
      img.src=rd.result;
    };
    rd.readAsDataURL(f);this.value='';
  });

  var ACT={
    sizeDown:function(){setSize((+$('fontSize').value||13)-1)},
    sizeUp:function(){setSize((+$('fontSize').value||13)+1)},
    bold:function(){toggle('bold','fontWeight')},
    italic:function(){toggle('italic','fontStyle')},
    underline:function(){toggle('underline','textDecoration','underline')},
    strike:function(){toggle('strikeThrough','textDecoration','line-through')},
    noHi:function(){setHi('transparent')},
    left:function(){align('left')},center:function(){align('center')},right:function(){align('right')},
    clear:clearFmt,
    undo:function(){restore();document.execCommand('undo')},
    redo:function(){restore();document.execCommand('redo')},
    addRow:handleAddRow,
    add5Rows:handleAdd5Rows,
    delRow:handleDelRow,
    logo:function(){$('logoFile').click()},
    logoReset:function(){setLogo(LOGO_SVG)},
    print:function(){window.print()},
    png:function(){saveImage('png')},
    jpg:function(){saveImage('jpg')},
    'new':function(){
      if(!confirm('Start a new invoice?\nRows will be cleared and the invoice number incremented.'))return;
      if (getGrid()) {
        getGrid().querySelectorAll('tbody [data-col]:not([data-col=sl])').forEach(function(e){e.textContent='';delete e.dataset.auto});
      }
      var g=$('grandTotal');if(g){g.textContent='';delete g.dataset.manual;}
      if($('sigL'))$('sigL').textContent='';
      if($('sigR'))$('sigR').textContent='';
      var n=$('invNo');
      if (n) {
        var t=n.textContent.trim(),m=t.match(/(\d+)(\D*)$/);
        if(m)n.textContent=t.slice(0,m.index)+String(+m[1]+1).padStart(m[1].length,'0')+m[2];
        $('headerInvNo').textContent=n.textContent;
      }
      if($('invDate'))$('invDate').textContent=today();
      SERVER_INVOICE_ID = 0; // Create as new record on server
      save();toast('New invoice ready');
    },
    reset:function(){
      if(!confirm('Reset everything to original default template?'))return;
      try{localStorage.removeItem(KEY)}catch(e){}
      location.reload();
    }
  };

  toolbar.addEventListener('click',function(e){
    var b=e.target.closest('button[data-act]');if(b&&ACT[b.dataset.act])ACT[b.dataset.act]();
  });
  if ($('invDensitySel')) {
    $('invDensitySel').addEventListener('change', function() {
      var v = this.value;
      if (v === 'compact') setInvoiceRowHeight(22);
      else if (v === 'comfortable') setInvoiceRowHeight(32);
      else setInvoiceRowHeight(26);
    });
  }
  if ($('btnInvRowSmaller')) $('btnInvRowSmaller').addEventListener('click', function() { setInvoiceRowHeight(currentInvRowH - 2); });
  if ($('btnInvRowLarger')) $('btnInvRowLarger').addEventListener('click', function() { setInvoiceRowHeight(currentInvRowH + 2); });
  document.addEventListener('keydown',function(e){
    if((e.ctrlKey||e.metaKey)&&e.key.toLowerCase()==='p'){e.preventDefault();window.print()}
  });

  /* ---------- save as image (html2canvas) ---------- */
  function saveImage(type){
    if(!window.html2canvas){toast('Image library not ready');return}
    if(document.activeElement&&document.activeElement.blur)document.activeElement.blur();
    var sel=getSelection();if(sel)sel.removeAllRanges();
    sheet.style.zoom='';toast('Generating high-res '+type.toUpperCase()+'...');
    var ready=(document.fonts&&document.fonts.ready)?document.fonts.ready:Promise.resolve();
    ready.then(function(){
      return html2canvas(sheet,{scale:3,backgroundColor:'#ffffff',useCORS:true,logging:false});
    }).then(function(cv){
      var mime=type==='jpg'?'image/jpeg':'image/png';
      cv.toBlob(function(b){
        var invNum = $('invNo') ? $('invNo').textContent.trim().replace(/[^\w-]+/g,'') : '0101';
        var name='ShababDeira-Invoice-'+(invNum||'draft')+'.'+(type==='jpg'?'jpg':'png');
        var a=document.createElement('a');a.href=URL.createObjectURL(b);a.download=name;
        document.body.appendChild(a);a.click();a.remove();
        setTimeout(function(){URL.revokeObjectURL(a.href)},2500);
        toast('Saved '+name);
      },mime,0.95);
    }).catch(function(){toast('Could not render image')}).then(fit);
  }

  /* ---------- save to database / server CRM ---------- */
  window.saveToCRM = function(){
    var btn = $('btnSaveToCRM');
    btn.disabled = true;
    btn.innerHTML = '<i data-lucide="loader-2" class="spin"></i> Saving...';
    if(window.lucide) lucide.createIcons();

    var invNum = $('invNo') ? $('invNo').textContent.trim() : '0101';
    var invDate = $('invDate') ? $('invDate').textContent.trim() : today();
    var grandTotal = $('grandTotal') ? $('grandTotal').textContent.trim() : '';
    var status = $('invoiceStatus') ? $('invoiceStatus').value : 'Draft';
    var clientComp = $('taxClientCompany') ? $('taxClientCompany').textContent.trim() : 'Manpower Client';
    var clientName = $('taxClientName') ? $('taxClientName').textContent.trim() : 'Operations Supervisor';

    var payload = {
      id: SERVER_INVOICE_ID,
      invoice_no: invNum,
      template_type: CURRENT_TEMPLATE,
      client_name: clientName,
      client_company: clientComp,
      invoice_date: invDate,
      total_hours: grandTotal,
      status: status,
      accent_color: sheet.style.getPropertyValue('--blue').trim(),
      red_color: sheet.style.getPropertyValue('--red').trim(),
      auto_calc: ($('autoCalc') && $('autoCalc').checked) ? 1 : 0,
      show_total: ($('showTotal') && $('showTotal').checked) ? 1 : 0,
      sheet_data: sheet.innerHTML
    };

    fetch('../api/invoices.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-Admin-Token': sessionStorage.getItem('shabab_admin_token') || ''
      },
      body: JSON.stringify(payload)
    })
    .then(function(res){ return res.json(); })
    .then(function(data){
      btn.disabled = false;
      btn.innerHTML = '<i data-lucide="check"></i> Saved to CRM';
      if(window.lucide) lucide.createIcons();
      if (data.success) {
        if (data.id) SERVER_INVOICE_ID = data.id;
        toast('Invoice #' + invNum + ' saved to database!');
        saveLocal();
      } else {
        toast('Server notice: ' + (data.error || 'Failed to persist'));
      }
      setTimeout(function(){
        btn.innerHTML = '<i data-lucide="save"></i> Save Invoice';
        if(window.lucide) lucide.createIcons();
      }, 2000);
    })
    .catch(function(err){
      btn.disabled = false;
      btn.innerHTML = '<i data-lucide="save"></i> Save Invoice';
      if(window.lucide) lucide.createIcons();
      toast('Network error - saved in browser locally');
      saveLocal();
    });
  };

  /* ---------- save / load local storage ---------- */
  var timer;
  function schedule(){
    $('savedStatus').innerHTML='<i data-lucide="loader-2" class="spin" style="width:14px;height:14px;"></i> Editing…';
    clearTimeout(timer);
    timer=setTimeout(saveLocal, 600);
  }
  function saveLocal(){
    try{
      localStorage.setItem(KEY, JSON.stringify({
        html: sheet.innerHTML,
        blue: sheet.style.getPropertyValue('--blue').trim(),
        red: sheet.style.getPropertyValue('--red').trim(),
        auto: $('autoCalc') ? $('autoCalc').checked : true,
        total: $('showTotal') ? $('showTotal').checked : false,
        template: CURRENT_TEMPLATE
      }));
      $('savedStatus').innerHTML='<i data-lucide="check" style="width:14px;height:14px;"></i> Live Auto-Saved';
    }catch(e){
      $('savedStatus').textContent='Local storage full';
    }
  }

  function toast(m){
    var t=$('invToast');
    t.textContent=m;
    t.classList.add('on');
    clearTimeout(toast.t);
    toast.t=setTimeout(function(){ t.classList.remove('on'); }, 2200);
  }
  function today(){
    var d=new Date();
    return ('0'+d.getDate()).slice(-2)+'/'+('0'+(d.getMonth()+1)).slice(-2)+'/'+d.getFullYear();
  }

  /* ---------- template switcher ---------- */
  window.switchTemplate = function(val){
    if (val === CURRENT_TEMPLATE) return;
    if (confirm('Switch to ' + (val==='tax'?'Commercial Tax Invoice':'Timesheet Invoice') + ' template? Unsaved changes will be replaced.')) {
      window.location.href = 'invoice-view.php?' + (SERVER_INVOICE_ID ? 'id='+SERVER_INVOICE_ID+'&' : '') + 'template=' + val;
    } else {
      $('templateSelector').value = CURRENT_TEMPLATE;
    }
  };

  /* ---------- initialize ---------- */
  var serverSheetData = <?php echo json_encode($currentInvoice['sheet_data'] ?? ''); ?>;
  var saved = null;
  
  if (serverSheetData && serverSheetData.trim() !== '') {
    // Loaded from database
    sheet.innerHTML = serverSheetData;
    if ($('logo') && !sheet.querySelector('#logo svg') && !sheet.querySelector('#logo img')) {
      $('logo').innerHTML = LOGO_SVG;
    }
  } else {
    // Check localStorage fallback
    try { saved = JSON.parse(localStorage.getItem(KEY)); } catch(e){}
    if (saved && saved.html && saved.template === CURRENT_TEMPLATE) {
      sheet.innerHTML = saved.html;
      if (saved.blue) { sheet.style.setProperty('--blue', saved.blue); $('colAccent').value = saved.blue; }
      if (saved.red) { sheet.style.setProperty('--red', saved.red); $('colRed').value = saved.red; }
      if ($('autoCalc')) $('autoCalc').checked = saved.auto !== false;
      if ($('showTotal')) $('showTotal').checked = !!saved.total;
    } else if (CURRENT_TEMPLATE === 'timesheet') {
      buildTable(DEFAULT_ROWS);
      if ($('logo')) $('logo').innerHTML = LOGO_SVG;
      if ($('invDate') && !$('invDate').textContent.trim()) $('invDate').textContent = today();
    } else {
      if ($('logo')) $('logo').innerHTML = LOGO_SVG;
      if ($('invDate') && !$('invDate').textContent.trim()) $('invDate').textContent = today();
    }
  }

  applyTotalToggle();
  if (CURRENT_TEMPLATE === 'timesheet') {
    sumTotals();
  }

  /* fit to screen */
  function fit(){
    var viewportWidth = window.innerWidth;
    var maxW = Math.min(viewportWidth - 40, 814);
    var z = Math.min(1, maxW / 794);
    if (viewportWidth < 880) {
      sheet.style.transform = 'scale(' + z + ')';
      sheet.style.transformOrigin = 'top center';
      sheet.parentElement.style.height = (1122 * z + 40) + 'px';
    } else {
      sheet.style.transform = '';
      sheet.style.transformOrigin = '';
      sheet.parentElement.style.height = '';
    }
  }
  window.addEventListener('resize', fit);
  fit();

  window.addEventListener('beforeprint', function(){
    sheet.style.transform = '';
    sheet.parentElement.style.height = '';
  });
  window.addEventListener('afterprint', fit);

  // Auto trigger print if requested in URL
  if (AUTO_PRINT) {
    setTimeout(function(){ window.print(); }, 800);
  }

})();
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
