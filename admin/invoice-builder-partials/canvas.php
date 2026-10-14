<?php
// Invoice Builder partial: canvas area + A4 document.
// Document markup and IDs are unchanged so calculations, print and
// PDF/PNG export keep working exactly as before.
?>
<main class="ib-canvas-area" id="canvasArea">
  <div class="ib-viewbar">
    <button type="button" class="ib-btn ib-btn--small" id="btnZoomOut" title="Zoom out">−</button>
    <span id="zoomLevelDisplay" style="min-width:44px;text-align:center;">100%</span>
    <button type="button" class="ib-btn ib-btn--small" id="btnZoomIn" title="Zoom in">+</button>
    <button type="button" class="ib-btn ib-btn--small" id="btnFitPage">Fit page</button>
    <span>A4 portrait (210 × 297 mm) — click any text on the page to edit it</span>
  </div>

  <div class="a4-document" id="docCanvas">
    <!-- 1. Brand header -->
    <div class="doc-section" id="secHeader">
      <div class="doc-header-brand">
        <div class="doc-brand-left">
          <svg class="doc-logo-svg" viewBox="0 0 100 100" aria-label="Shabab Deira logo">
            <circle cx="50" cy="50" r="48" fill="#0f3763"/>
            <circle cx="50" cy="50" r="44.5" fill="none" stroke="#fff" stroke-width="1.6"/>
            <circle cx="50" cy="36" fill="none" stroke="#8fb2de" stroke-width="1"/>
            <ellipse cx="50" cy="50" rx="17" ry="36" fill="none" stroke="#8fb2de" stroke-width="1"/>
            <ellipse cx="50" cy="50" rx="30" ry="36" fill="none" stroke="#8fb2de" stroke-width=".8"/>
            <line x1="14" y1="50" x2="86" y2="50" stroke="#8fb2de" stroke-width="1"/>
            <path d="M18 34 Q50 40 82 34 M18 66 Q50 60 82 66" fill="none" stroke="#8fb2de" stroke-width=".9"/>
            <g transform="rotate(-7 50 50)">
              <rect x="12" y="38" width="76" height="24" rx="2" fill="#0f3763" stroke="#fff" stroke-width="1.6"/>
              <text x="50" y="56" text-anchor="middle" font-family="Georgia,serif" font-style="italic" font-weight="700" font-size="19" fill="#fff" letter-spacing="1">SDSC</text>
            </g>
          </svg>
          <div class="doc-brand-titles">
            <h1 contenteditable="true" spellcheck="false" id="coNameEn">SHABAB DEIRA</h1>
            <p contenteditable="true" spellcheck="false" id="coSubEn">SHIPPING &amp; CARGO SERVICES LLC</p>
            <span contenteditable="true" spellcheck="false" id="coDivision">Manpower Supply &amp; Technical Logistics Division</span>
          </div>
        </div>
        <div class="doc-brand-right">
          <div class="ar-main" contenteditable="true" spellcheck="false" id="coNameAr">شباب ديرة لخدمات</div>
          <div class="ar-sub" contenteditable="true" spellcheck="false" id="coSubAr">الملاحة والشحن ش.ذ.م.م</div>
          <div style="font-size:11px;font-weight:700;color:#64748B;margin-top:3px;" contenteditable="true" spellcheck="false" id="coTrnDisplay">TRN: 100592837400003</div>
        </div>
      </div>
    </div>

    <!-- 2. Contact line -->
    <div class="doc-section" id="secContact">
      <div class="doc-contact-bar">
        <span contenteditable="true" spellcheck="false" id="coAddr">Office M33, Alphamed Bldg, Abu Hail, Hor Al Anz East, Dubai, UAE</span>
        <span contenteditable="true" spellcheck="false" id="coPhone">+971 56 135 9305</span>
        <span contenteditable="true" spellcheck="false" id="coEmail">info@shababdeira.com</span>
      </div>
    </div>

    <!-- 3. Title banner -->
    <div class="doc-section" id="secBanner">
      <div class="doc-banner">
        <h2 contenteditable="true" spellcheck="false" id="docTypeBanner">TAX INVOICE / فاتورة ضريبية</h2>
        <div class="doc-code-badge" contenteditable="true" spellcheck="false" id="docNoBadge">SD-MSI-2026-041</div>
      </div>
    </div>

    <!-- 4. Client and details -->
    <div class="doc-section" id="secMeta">
      <div class="doc-meta-grid">
        <div class="doc-client-info">
          <div class="doc-box-title"><span>BILLED TO / الجهة المستلمة</span><span style="font-size:9.5px;color:#64748B;">CLIENT</span></div>
          <p><strong contenteditable="true" spellcheck="false" id="clientName">DP World Logistics FZE</strong></p>
          <p contenteditable="true" spellcheck="false" id="clientAttention">Attn: Eng. Rashid Al Nuaimi (Site Director)</p>
          <p contenteditable="true" spellcheck="false" id="clientAddress">Gate 4, Jebel Ali Free Zone South, Dubai, UAE</p>
          <p contenteditable="true" spellcheck="false" id="clientTrn">TRN: 100349281000003</p>
          <p contenteditable="true" spellcheck="false" id="clientContact">Tel: +971 4 881 2222 | procurement@dpworldlogistics.ae</p>
        </div>
        <div>
          <div class="doc-box-title"><span>DOCUMENT PARTICULARS</span><span style="font-size:9.5px;color:#64748B;">DETAILS</span></div>
          <table class="doc-meta-table"><tbody id="metaFieldsTbody"></tbody></table>
        </div>
      </div>
      <!-- Strip variant: field boxes + recipient line (registers, orders, handover docs) -->
      <div class="doc-meta-strip" id="metaStrip">
        <div class="doc-strip-client"><strong id="stripClientName" contenteditable="true" spellcheck="false"></strong><span id="stripClientAttn" contenteditable="true" spellcheck="false"></span></div>
        <div class="doc-strip-fields" id="metaStripFields"></div>
      </div>
      <!-- Subject variant: person card + document lines (vouchers, receipts, advances) -->
      <div class="doc-subject-card" id="subjectCard">
        <div class="doc-subject-label" id="subjectLabel">Subject</div>
        <div class="doc-subject-name" id="subjectName" contenteditable="true" spellcheck="false"></div>
        <div class="doc-subject-sub" id="subjectSub" contenteditable="true" spellcheck="false"></div>
        <div class="doc-subject-doclines" id="subjectDocLines"></div>
      </div>
    </div>

    <!-- 5. Items table -->
    <div class="doc-section" id="secTable">
      <div class="doc-table-wrap">
        <table class="doc-table" id="mainDocTable">
          <thead id="docTableThead"></thead>
          <tbody id="docTableTbody"></tbody>
        </table>
      </div>
      <div class="add-row-bar">
        <button type="button" class="add-row-btn" id="btnAddRowBtn">+ Add row</button>
        <button type="button" class="add-row-btn" id="btnAdd5RowsBtn">+ Add 5 rows</button>
      </div>
    </div>

    <!-- 6. Notes and totals -->
    <div class="doc-section" id="secTotals">
      <div class="doc-calc-grid">
        <div class="doc-notes-box">
          <strong>TERMS, BANK DETAILS &amp; COMPLIANCE:</strong>
          <div contenteditable="true" spellcheck="false" id="docNotesArea" style="min-height:70px;white-space:pre-wrap;"></div>
        </div>
        <div class="doc-totals-box">
          <div class="doc-tot-row" id="subRow"><span style="font-weight:600;color:#64748B;">Subtotal:</span><span style="font-weight:700;color:#0F172A;" id="calcSubtotal">0.00 AED</span></div>
          <div class="doc-tot-row" id="vatRow"><span style="font-weight:600;color:#64748B;">VAT (5%):</span><span style="font-weight:700;color:#0F172A;" id="calcVat">0.00 AED</span></div>
          <div class="doc-tot-row grand"><span>TOTAL:</span><span id="calcGrandTotal">0.00 AED</span></div>
          <div class="doc-tot-row receipt-mode" id="receiptModeLine"><span style="font-weight:600;color:#64748B;">Payment mode:</span><span style="font-weight:700;color:#0F172A;" id="receiptModeVal" contenteditable="true" spellcheck="false"></span></div>
          <div class="doc-tot-row words"><span id="calcWords">Zero Dirhams Only</span></div>
        </div>
      </div>
    </div>

    <!-- 7. Signatures and stamp -->
    <div class="doc-section" id="secSignatures">
      <div class="doc-signatures-wrap">
        <div class="sig-slot" id="sigSlot1">
          <div class="sig-line" contenteditable="true" spellcheck="false" id="sigVal1">Zubair Khan</div>
          <div class="sig-title" contenteditable="true" spellcheck="false" id="sigTitle1">PREPARED BY (ACCOUNTS)</div>
          <div class="sig-sub" contenteditable="true" spellcheck="false" id="sigSub1">Shabab Deira Billing Specialist</div>
        </div>
        <div class="sig-slot" id="sigSlot2">
          <div class="sig-line" contenteditable="true" spellcheck="false" id="sigVal2">Farhan Siddiqui</div>
          <div class="sig-title" contenteditable="true" spellcheck="false" id="sigTitle2">VERIFIED BY OPERATIONS</div>
          <div class="sig-sub" contenteditable="true" spellcheck="false" id="sigSub2">Site Workforce Supervisor</div>
        </div>
        <div class="sig-slot" id="sigSlot3">
          <div class="sig-line" contenteditable="true" spellcheck="false" id="sigVal3">Authorized Signatory</div>
          <div class="sig-title" contenteditable="true" spellcheck="false" id="sigTitle3">CLIENT ACCEPTANCE &amp; STAMP</div>
          <div class="sig-sub" contenteditable="true" spellcheck="false" id="sigSub3">Received &amp; Certified Correct</div>
        </div>
        <svg class="official-stamp-seal" id="officialStampSvg" viewBox="0 0 200 200" aria-label="Official company stamp">
          <circle cx="100" cy="100" r="94" fill="none" stroke="#991B1B" stroke-width="3.5" stroke-dasharray="8 4"/>
          <circle cx="100" cy="100" r="86" fill="none" stroke="#991B1B" stroke-width="2"/>
          <circle cx="100" cy="100" r="54" fill="none" stroke="#991B1B" stroke-width="1.5"/>
          <path id="stampCurveTop" d="M 30,100 A 70,70 0 0,1 170,100" fill="none"/>
          <path id="stampCurveBot" d="M 170,100 A 70,70 0 0,1 30,100" fill="none"/>
          <text font-family="'Source Sans 3', sans-serif" font-weight="800" font-size="11.5" fill="#991B1B" letter-spacing="2"><textPath href="#stampCurveTop" startOffset="50%" text-anchor="middle">SHABAB DEIRA SERVICES LLC</textPath></text>
          <text font-family="'Cairo', sans-serif" font-weight="800" font-size="11" fill="#991B1B" letter-spacing="1"><textPath href="#stampCurveBot" startOffset="50%" text-anchor="middle">دبي - DUBAI - UAE</textPath></text>
          <g transform="translate(100, 100)">
            <rect x="-38" y="-12" width="76" height="24" rx="3" fill="#991B1B"/>
            <text x="0" y="5" text-anchor="middle" font-family="'Source Sans 3', sans-serif" font-weight="900" font-size="13" fill="#FFFFFF" letter-spacing="1">APPROVED</text>
          </g>
        </svg>
      </div>
    </div>

    <!-- 8. Footer -->
    <div class="doc-section" id="secFooter">
      <div class="doc-footer-bar">
        <span contenteditable="true" spellcheck="false" id="footLicense">Commercial License No: CN-829104 | Dubai Economy &amp; Tourism</span>
        <span class="slogan" contenteditable="true" spellcheck="false" id="footSlogan">Your Cargo Our Priority</span>
        <span contenteditable="true" spellcheck="false" id="footWeb">https://shababdeira.com</span>
      </div>
    </div>
  </div>
</main>
