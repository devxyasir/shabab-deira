<?php
/**
 * Shabab Deira Operations CRM — Per-template document layouts.
 *
 * Structural identity for each of the 18 builder templates. This file
 * changes HOW a document is arranged (title treatment, meta arrangement,
 * totals treatment, header density, page orientation) — never its data.
 * config/templates_data.php stays frozen; api/templates.php is untouched.
 *
 * Axes: title (banner|ruled|boxed|slip), meta (parties|strip|subject),
 * totals (vat|grand|receipt|none), header (full|compact),
 * orientation (portrait|landscape), tone (default|red).
 * Full spec: admin/invoice-templates-redesign.md (§3–§4).
 */

function get_template_layouts() {
    return [
        'manpower_supply_invoice' => ['title' => 'banner', 'meta' => 'parties', 'totals' => 'vat',     'header' => 'full',    'orientation' => 'portrait',  'tone' => 'default'],
        'vat_tax_invoice'         => ['title' => 'boxed',  'meta' => 'parties', 'totals' => 'vat',     'header' => 'full',    'orientation' => 'portrait',  'tone' => 'default'],
        'service_invoice'         => ['title' => 'ruled',  'meta' => 'parties', 'totals' => 'vat',     'header' => 'full',    'orientation' => 'portrait',  'tone' => 'default'],
        'credit_note'             => ['title' => 'boxed',  'meta' => 'parties', 'totals' => 'vat',     'header' => 'full',    'orientation' => 'portrait',  'tone' => 'red'],
        'quotation'               => ['title' => 'ruled',  'meta' => 'parties', 'totals' => 'none',    'header' => 'full',    'orientation' => 'portrait',  'tone' => 'default'],
        'payment_receipt'         => ['title' => 'slip',   'meta' => 'subject', 'totals' => 'receipt', 'header' => 'compact', 'orientation' => 'portrait',  'tone' => 'default'],
        'timesheet_invoice'       => ['title' => 'banner', 'meta' => 'strip',   'totals' => 'vat',     'header' => 'compact', 'orientation' => 'portrait', 'tone' => 'default'],
        'labour_attendance'       => ['title' => 'ruled',  'meta' => 'strip',   'totals' => 'none',    'header' => 'compact', 'orientation' => 'portrait', 'tone' => 'default'],
        'overtime_records'        => ['title' => 'banner', 'meta' => 'strip',   'totals' => 'grand',   'header' => 'compact', 'orientation' => 'portrait', 'tone' => 'default'],
        'daily_cash_register'     => ['title' => 'ruled',  'meta' => 'strip',   'totals' => 'grand',   'header' => 'compact', 'orientation' => 'portrait', 'tone' => 'default'],
        'monthly_payroll'         => ['title' => 'ruled',  'meta' => 'strip',   'totals' => 'grand',   'header' => 'full',    'orientation' => 'portrait', 'tone' => 'default'],
        'salary_receipt'          => ['title' => 'slip',   'meta' => 'subject', 'totals' => 'receipt', 'header' => 'full',    'orientation' => 'portrait',  'tone' => 'default'],
        'employee_advance'        => ['title' => 'slip',   'meta' => 'subject', 'totals' => 'grand',   'header' => 'full',    'orientation' => 'portrait',  'tone' => 'default'],
        'petty_cash'              => ['title' => 'slip',   'meta' => 'subject', 'totals' => 'vat',     'header' => 'compact', 'orientation' => 'portrait',  'tone' => 'default'],
        'worker_deployment'       => ['title' => 'banner', 'meta' => 'strip',   'totals' => 'none',    'header' => 'compact', 'orientation' => 'portrait',  'tone' => 'default'],
        'delivery_note'           => ['title' => 'ruled',  'meta' => 'strip',   'totals' => 'none',    'header' => 'compact', 'orientation' => 'portrait',  'tone' => 'default'],
        'job_completion'          => ['title' => 'boxed',  'meta' => 'parties', 'totals' => 'none',    'header' => 'full',    'orientation' => 'portrait',  'tone' => 'default'],
        'purchase_order'          => ['title' => 'boxed',  'meta' => 'strip',   'totals' => 'vat',     'header' => 'full',    'orientation' => 'portrait',  'tone' => 'default'],
    ];
}

function get_default_template_layout() {
    return ['title' => 'banner', 'meta' => 'parties', 'totals' => 'vat', 'header' => 'full', 'orientation' => 'portrait', 'tone' => 'default'];
}

/**
 * One factual line per template for the chooser and hub lists.
 * What the document IS — no adjectives, no marketing.
 */
function get_template_blurbs() {
    return [
        'manpower_supply_invoice' => 'Monthly billing for outsourced labour by trade, hours and overtime, plus 5% VAT.',
        'vat_tax_invoice'         => 'FTA Article-59 tax invoice with TRN, line-item VAT and AED totals.',
        'service_invoice'         => 'Invoice for cargo, packaging and warehousing services, plus 5% VAT.',
        'credit_note'             => 'VAT credit note against a named original invoice, with reason.',
        'quotation'               => 'Rate offer for manpower supply with validity and payment terms.',
        'payment_receipt'         => 'Receipt for money received from a client, by bank, cheque or cash.',
        'timesheet_invoice'       => 'Worker-by-worker billing for days, regular hours and overtime.',
        'labour_attendance'       => 'Daily site attendance: badge, time in/out, hours and signature.',
        'overtime_records'        => 'Authorised overtime hours by worker with rates and totals.',
        'daily_cash_register'     => 'Daily cash wage payouts with ID check and worker signature.',
        'monthly_payroll'         => 'Monthly WPS payroll: basic, allowances, overtime, deductions, net.',
        'salary_receipt'          => 'Payslip voucher for one worker with signed acknowledgement.',
        'employee_advance'        => 'Salary advance with amount, reason and payroll deduction plan.',
        'petty_cash'              => 'Site expense voucher: transport, tolls, safety items and consumables.',
        'worker_deployment'       => 'Mobilisation order: crew, gate pass, PPE and safety induction.',
        'delivery_note'           => 'Handover note for crew received at site. No amounts.',
        'job_completion'          => 'Completion certificate with scope table and client acceptance.',
        'purchase_order'          => 'Order for manpower, safety gear or tooling with delivery terms.',
    ];
}
