<?php
// Invoice Builder partial: top action bar.
// Expects: $systemTemplates (array), $activeTemplate (array).
?>
<div class="ib-topbar">
  <div class="ib-topbar__group">
    <a href="invoices.php" class="ib-btn" title="Back to invoices">Back</a>
    <span class="ib-topbar__sep"></span>
    <label for="topQuickTemplateSelect" style="font-size:12px;font-weight:700;color:#5B6B7F;">Template</label>
    <select class="ib-select" id="topQuickTemplateSelect" title="Switch template">
      <optgroup label="Tax invoices">
        <option value="manpower_supply_invoice">Manpower Supply Invoice</option>
        <option value="vat_tax_invoice">VAT Tax Invoice (FTA)</option>
        <option value="credit_note">Tax Credit Note</option>
        <option value="service_invoice">Cargo &amp; Service Invoice</option>
        <option value="quotation">Quotation / Proposal</option>
        <option value="payment_receipt">Payment Receipt</option>
      </optgroup>
      <optgroup label="Timesheets">
        <option value="timesheet_invoice">Timesheet &amp; Hours Invoice</option>
        <option value="labour_attendance">Daily Attendance Sheet</option>
        <option value="overtime_records">Overtime Record</option>
      </optgroup>
      <optgroup label="Payroll">
        <option value="daily_cash_register">Daily Cash Register</option>
        <option value="monthly_payroll">Monthly Payroll (WPS)</option>
        <option value="salary_receipt">Salary Receipt</option>
        <option value="employee_advance">Advance Voucher</option>
        <option value="petty_cash">Petty Cash Voucher</option>
      </optgroup>
      <optgroup label="Manpower supply">
        <option value="worker_deployment">Deployment Sheet</option>
        <option value="delivery_note">Delivery Note</option>
        <option value="job_completion">Job Completion Report</option>
        <option value="purchase_order">Purchase Order</option>
      </optgroup>
    </select>
    <button type="button" class="ib-btn" id="btnOpenTemplates" title="Back to the template list">All templates</button>
    <span class="ib-cat-label" id="topCategoryBadge"><?php echo htmlspecialchars($activeTemplate['badge'] ?? $activeTemplate['category'] ?? 'General'); ?></span>
  </div>

  <div class="ib-topbar__group">
    <span class="ib-topbar__docno">No. <strong id="topDocNo"><?php echo htmlspecialchars(($activeTemplate['docCodePrefix'] ?? 'SD-') . (($activeTemplate['metaFields'][0]['value'] ?? ''))); ?></strong></span>
    <span class="ib-topbar__sep"></span>
    <button type="button" class="ib-btn" id="btnToggleInspector" title="Show or hide settings panel">Settings</button>
    <button type="button" class="ib-btn" id="btnDuplicateTpl" title="Make a copy of this template">Duplicate</button>
    <button type="button" class="ib-btn ib-btn--primary" id="btnSaveTemplate" title="Save as reusable template">Save template</button>
    <button type="button" class="ib-btn ib-btn--save" id="btnSaveDocument" title="Save as issued document">Save document</button>
    <span class="ib-topbar__sep"></span>
    <button type="button" class="ib-btn" id="btnPrintA4" title="Print on A4 (Ctrl+P)">Print</button>
    <button type="button" class="ib-btn" id="btnExportPdf" title="Download A4 PDF">PDF</button>
    <button type="button" class="ib-btn" id="btnExportPng" title="Download PNG image">PNG</button>
  </div>
</div>
