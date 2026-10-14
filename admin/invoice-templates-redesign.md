# Invoice Template Redesign — Agent Instruction File

This file is the single source of truth for redesigning the 18 invoice-builder
templates. Read it fully before touching any template code.

## 1. The problem being fixed

All 18 templates render through ONE fixed canvas
(`admin/invoice-builder-partials/canvas.php`: header → contact line → banner →
meta grid → table → totals box → 3 signatures → stamp → footer) and differ only
by theme colour. That is why every document looks like the same AI template.

Real Dubai office documents do NOT look like that. A daily attendance sheet is
a landscape ruled register. A salary voucher is a small slip with an
acknowledgement line. A Tax Invoice is a formal Article-59 document with a VAT
breakup. A delivery note has no money on it at all. The redesign gives each
template the STRUCTURE of its real-world counterpart. Colour is not used for
differentiation — structure is.

## 2. Compliance ground rules (verified Oct 2026 — re-check before release)

- Full Tax Invoice, Article 59 VAT Executive Regulation: words "Tax Invoice",
  supplier name + address + TRN, recipient name + address + TRN (mandatory for
  B2B supplies above AED 10,000), sequential invoice number, issue date,
  supply date if different from issue date, per-line description / quantity /
  unit price / VAT rate / amount in AED, discount if any, gross payable incl.
  VAT, total VAT in AED. Never remove these fields from tax documents.
- Simplified tax invoice (recipient unregistered, or B2B at AED 10,000 or
  less): title, supplier name/address/TRN, date, description, total + VAT.
- Tax Credit Note: same field rules as a tax invoice PLUS a reference to the
  original invoice number/date and the reason for the credit.
- E-invoicing (MOF/FTA, Peppol PINT AE via Accredited Service Provider):
  voluntary from 1 Jul 2026, mandatory 1 Jan 2027 for revenue ≥ AED 50M,
  SMEs 1 Jul 2027. PDFs are NOT e-invoices. Design implication only: keep
  sequential document numbers and structured line data (already the case).
  Do NOT hardcode these dates anywhere in UI copy — timelines change.
- Payroll: monthly registers follow MOHRE WPS shape (basic, allowances,
  overtime, gross, deductions, net + SIF reference). No deductions beyond what
  the law allows — the data already reflects this; do not invent new columns.
- Overtime multipliers in the data (1.25x / 1.50x) are business data. Do not
  change, reinterpret, or relabel them.

## 3. Architecture — the layout registry

- `config/template_layouts.php` maps each template key to a STRUCTURAL layout
  plus a short factual blurb. It never changes prices, columns, VAT math, or
  wording of the data in `config/templates_data.php` (that file is frozen).
- The builder merges the registry into templates at runtime
  (`admin/invoice-builder.php`). The API (`api/templates.php`) is untouched.
- Layout = 6 independent axes. Every template gets one value per axis, and no
  two templates share the full combination:

| Axis          | Values                                                              |
|---------------|---------------------------------------------------------------------|
| `title`       | `banner` (solid bar) / `ruled` (classic ruled invoice title) /      |
|               | `boxed` (double-bordered official title) / `slip` (small voucher   |
|               | title)                                                              |
| `meta`        | `parties` (Billed-to + particulars grid) / `strip` (field strip +   |
|               | recipient line: registers, orders, handover docs) / `subject`       |
|               | (person card + document lines: vouchers, receipts, advances)        |
| `totals`      | `vat` (subtotal + 5% + grand + words) / `grand` (one total line) /  |
|               | `receipt` (amount-in-words prominent + payment-mode line) / `none`  |
|               | (notes/compliance text full width, no money box)                    |
| `header`      | `full` (letterhead) / `compact` (small letterhead, internal docs)   |
| `orientation` | `portrait` / `landscape` (wide registers ONLY — see §5)             |
| `tone`        | `default` / `red` (credit note ONLY — red means credit)             |

- Canvas CSS classes: `.doc--title-{ruled,boxed,slip}`, `.doc--meta-{strip,
  subject}`, `.doc--totals-{grand,receipt,none}`, `.doc--header-compact`,
  `.doc--landscape`, `.doc--tone-red`. The default (no class) is the current
  formal-invoice look and stays assigned to the standard manpower invoice.
- Stable canvas contract — these IDs always exist and keep their meaning:
  `docCanvas coNameEn coSubEn coNameAr coSubAr coTrnDisplay coAddr coPhone
  coEmail docTypeBanner docNoBadge clientName clientAttention clientAddress
  clientTrn clientContact metaFieldsTbody docTableThead docTableTbody
  docNotesArea subRow vatRow calcSubtotal calcVat calcGrandTotal calcWords
  receiptModeLine receiptModeVal sigSlot1-3 sigVal1-3 sigTitle1-3 sigSub1-3
  officialStampSvg`. Strip/subject/totals variants are ADDITIONAL nodes with
  their own IDs (see §6). Never rename or remove an ID without updating
  `invoice-builder-core.js`, `invoice-builder-table.js`, AND
  `invoice-builder-actions.js` together.

## 4. Per-template assignments (normative)

| # | Template key              | title  | meta    | totals  | header  | orient. | tone |
|---|---------------------------|--------|---------|---------|---------|---------|------|
| 1 | manpower_supply_invoice   | banner | parties | vat     | full    | portr.  | def  |
| 2 | vat_tax_invoice (FTA)     | boxed  | parties | vat     | full    | portr.  | def  |
| 3 | service_invoice           | ruled  | parties | vat     | full    | portr.  | def  |
| 4 | credit_note               | boxed  | parties | vat     | full    | portr.  | red  |
| 5 | quotation                 | ruled  | parties | none    | full    | portr.  | def  |
| 6 | payment_receipt           | slip   | subject | receipt | compact | portr.  | def  |
| 7 | timesheet_invoice         | banner | strip   | vat     | compact | portr.  | def  |
| 8 | labour_attendance         | ruled  | strip   | none    | compact | portr.  | def  |
| 9 | overtime_records          | banner | strip   | grand   | compact | portr.  | def  |
| 10| daily_cash_register       | ruled  | strip   | grand   | compact | portr.  | def  |
| 11| monthly_payroll (WPS)     | ruled  | strip   | grand   | full    | portr.  | def  |
| 12| salary_receipt            | slip   | subject | receipt | full    | portr.  | def  |
| 13| employee_advance          | slip   | subject | grand   | full    | portr.  | def  |
| 14| petty_cash                | slip   | subject | vat     | compact | portr.  | def  |
| 15| worker_deployment         | banner | strip   | none    | compact | portr.  | def  |
| 16| delivery_note             | ruled  | strip   | none    | compact | portr.  | def  |
| 17| job_completion (JCR)      | boxed  | parties | none    | full    | portr.  | def  |
| 18| purchase_order            | boxed  | strip   | vat     | full    | portr.  | def  |

Why these shapes (design rationale per family):

- Formal money-in documents (1,2,3,4,18): full letterhead, Billed-to +
  particulars, VAT breakup. They differ by TITLE treatment: standard banner
  (manpower invoice), double-bordered official title (FTA invoice, credit
  note, purchase order), classic ruled title (service invoice). Credit note
  additionally carries the red tone and always shows the original-invoice
  reference from its meta fields.
- Rate/quote document (5): ruled title, parties, NO money box — a quotation
  carries validity + terms, not a payable total. Notes go full width.
- Handover/completion documents (15,16,17): no money box at all (a delivery
  note or JCR with a grand total on it is wrong). Strip meta (order/site/
  reference fields + recipient line) for deployment and delivery; parties meta
  for the certificate-style JCR with boxed title.
- Registers (7,8,9,10,11): LANDSCAPE, compact letterhead, field strip
  (period / shift / site / SIF), ruled or banner title. Attendance has no
  money box (summary notes full width); cash/OT/payroll show one grand line.
- Person documents (6,12,13,14): slip title, subject card (the person + the
  document lines), compact or full header. Receipts use the words-prominent
  totals with payment-mode line; advance uses one total line (repayment
  schedule lives in its table, which already exists in data).

## 5. Orientation rules

- Every template is portrait A4 (794×1123). No template may use landscape —
  wide register tables fit via `table-layout: fixed` with ellipsis (already
  in the table CSS), which is how real offices keep registers on portrait A4.
- The landscape machinery (`.doc--landscape`, named `@page`, orientation
  branch in export) is retained in code but currently unassigned. Do not
  assign it without owner approval.
- After any change to export code, test print AND PDF for a strip-meta
  template (e.g. attendance) and a VAT totals template — single portrait A4.

## 6. DOM / JS rules for variants

- Title variants are pure CSS on the existing `#secBanner` nodes. No new IDs.
- Meta `strip`: `#metaStrip` (`#stripClientLine` + `#metaStripFields`,
  label/value pairs, both contenteditable). Meta `subject`: `#subjectCard`
  (`#subjectName` + `#subjectDocLines`, label/value pairs, contenteditable).
  `renderTemplate` fills ALL THREE meta DOMs from the same template data;
  CSS shows exactly one. `extractTemplatePayload` reads the VISIBLE variant
  and falls back to template data — user edits in any variant must survive
  save (verify by editing each variant once).
- Totals: `subRow`/`vatRow` are hidden by CSS for `grand`/`receipt`/`none`;
  `none` additionally hides the totals box and stretches notes full width.
  `receipt` shows `#receiptModeLine`, filled from the template's payment-mode
  meta field when present, hidden otherwise. `recalculate()` always updates
  every total span regardless of visibility — never branch the math by layout.
- Header `compact` is pure CSS (smaller logo/type, tighter spacing). It must
  never hide the company name, Arabic name, TRN, or contact line.
- The Sections pane toggles whole sections only; it must not fight layout
  classes. The Design pane keeps working (colours, font, rows, currency,
  VAT checkbox) for every layout.
- Document numbers: meta values already contain the full number
  (e.g. "SD-TSI-0101"). Prepend `docCodePrefix` only when missing
  (`B.docNo`) — never render a doubled number like "SD-TSI-SD-TSI-0101".
- Document ink is always brand navy `#0F3763` + gold `#C8AD7E`
  (`B.BRAND_PRIMARY` / `B.BRAND_ACCENT`); per-template theme colours are
  ignored at render so documents never clash. Credit-note red comes only
  from the tone class.

## 7. Copy rules — no fluff

- Blurbs (chooser + hub list) are one factual line each from the registry:
  what the document IS, not marketing. No adjectives like "comprehensive",
  "official", "formal", "complete", "seamless".
- Do not add explanatory paragraphs, slogans, or new notes text to any
  template. Data file `templates_data.php` stays frozen except reviewed
  factual corrections.
- Keep every Arabic string exactly as in the data. Never invent Arabic copy.

## 8. Hard don'ts

- No new hue per template. Ink is navy `#0F3763`; credit red `#991B1B` is the
  single exception. Gold `#C8AD7E` is a marker/focus colour, never a fill.
- No gradients, pills, glow shadows, decorative icons, or oversized headings
  on documents. Chrome (topbar/inspector/chooser) keeps the existing plain
  business theme untouched.
- No touching VAT math, `toWords`, column definitions, item data, API
  endpoints, or save payload field names.
- No layout that hides legally required fields (§2). When in doubt, the
  formal `parties`+`vat` combination wins for anything that collects money
  with VAT.

## 9. Verification checklist (all must pass)

1. `node --check` on every builder JS file.
2. Every template key has a registry entry; every registry key exists in data.
3. No two templates share a full layout combination (script-check the table).
4. All §3 contract IDs present in `canvas.php`; every JS-referenced ID
   resolves (script-check).
5. Open each of the 18 templates: no JS errors, table + totals render,
   totals math unchanged (spot-check one VAT and one grand-total doc).
6. Print preview + PDF export for a strip-meta template (e.g. attendance)
   and a VAT totals template — single portrait A4, correct number format.
7. Edit text in a strip layout and a subject layout, save, confirm edits
   persist via `extractTemplatePayload`.
8. Narrow viewport: chooser and editor usable, no horizontal page scroll
   outside the canvas scroller.
