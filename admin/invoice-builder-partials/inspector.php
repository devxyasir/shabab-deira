<?php
// Invoice Builder partial: left settings panel (inspector).
// Element IDs are part of the builder contract — JS files depend on them.
?>
<aside class="ib-inspector" id="studioInspector">
  <div class="ib-tabs" role="tablist">
    <button type="button" class="ib-tab active" data-pane="paneLibrary">Templates</button>
    <button type="button" class="ib-tab" data-pane="paneDesign">Design</button>
    <button type="button" class="ib-tab" data-pane="paneSections">Sections</button>
    <button type="button" class="ib-tab" data-pane="paneTable">Table</button>
    <button type="button" class="ib-tab" data-pane="paneStamps">Signatures</button>
  </div>

  <!-- Templates -->
  <div class="ib-pane active" id="paneLibrary">
    <div class="ib-field">
      <label for="sbSearchInput">Find a template</label>
      <input type="text" id="sbSearchInput" class="ib-search" placeholder="Search name or use, e.g. VAT, cash, attendance">
    </div>
    <div id="sbTemplatesList"></div>
  </div>

  <!-- Design -->
  <div class="ib-pane" id="paneDesign">
    <div class="ib-group">
      <div class="ib-group-title">Colours</div>
      <p class="ib-hint">Brand default is navy and gold. Change only when the client asks for it.</p>
      <div class="ib-swatches">
        <button type="button" class="ib-swatch active" data-color="#0F3763" data-accent="#C8AD7E" style="background:#0F3763;" title="Navy and gold (default)"></button>
        <button type="button" class="ib-swatch" data-color="#076835" data-accent="#D97706" style="background:#076835;" title="Emerald and amber"></button>
        <button type="button" class="ib-swatch" data-color="#6366F1" data-accent="#F59E0B" style="background:#6366F1;" title="Indigo"></button>
        <button type="button" class="ib-swatch" data-color="#0F172A" data-accent="#38BDF8" style="background:#0F172A;" title="Slate"></button>
        <button type="button" class="ib-swatch" data-color="#991B1B" data-accent="#FBBF24" style="background:#991B1B;" title="Crimson"></button>
        <button type="button" class="ib-swatch" data-color="#0E7490" data-accent="#C8AD7E" style="background:#0E7490;" title="Teal"></button>
      </div>
      <div class="ib-row"><span>Heading colour</span><input type="color" id="primaryColorPicker" value="#0F3763"></div>
      <div class="ib-row"><span>Accent colour</span><input type="color" id="accentColorPicker" value="#C8AD7E"></div>
    </div>

    <div class="ib-group">
      <div class="ib-group-title">Text and rows</div>
      <div class="ib-field">
        <label for="fontFamilySelect">Font</label>
        <select class="ib-select--full ib-select" id="fontFamilySelect" style="width:100%;">
          <option value="'Source Sans 3', sans-serif">Source Sans (default)</option>
          <option value="'IBM Plex Sans', sans-serif">IBM Plex Sans</option>
          <option value="'Barlow Condensed', sans-serif">Barlow Condensed</option>
          <option value="'Cairo', sans-serif">Cairo (Arabic / English)</option>
          <option value="Georgia, serif">Georgia</option>
          <option value="'IBM Plex Mono', monospace">Mono (technical)</option>
        </select>
      </div>
      <div class="ib-field">
        <label for="rowHeightSelect">Row height</label>
        <select class="ib-select--full ib-select" id="rowHeightSelect" style="width:100%;">
          <option value="22">Compact (22px)</option>
          <option value="26">Standard (26px)</option>
          <option value="28" selected>Normal (28px)</option>
          <option value="34">Spacious (34px)</option>
        </select>
      </div>
      <div class="ib-row"><span>Alternate row shading</span><input type="checkbox" id="checkZebraStriping" class="ib-check" checked></div>
    </div>

    <div class="ib-group">
      <div class="ib-group-title">Currency and VAT</div>
      <div class="ib-field">
        <label for="currencySelect">Currency</label>
        <select class="ib-select--full ib-select" id="currencySelect" style="width:100%;">
          <option value="AED" selected>AED — UAE Dirham</option>
          <option value="USD">USD — US Dollar</option>
          <option value="EUR">EUR — Euro</option>
          <option value="SAR">SAR — Saudi Riyal</option>
        </select>
      </div>
      <div class="ib-row"><span>Apply 5% UAE VAT</span><input type="checkbox" id="checkEnableVat" class="ib-check" checked></div>
    </div>
  </div>

  <!-- Sections -->
  <div class="ib-pane" id="paneSections">
    <div class="ib-group">
      <div class="ib-group-title">Sections on this document</div>
      <p class="ib-hint">Tick to show or hide. Drag a row to change the order on the page.</p>
      <div id="sectionOrderList">
        <div class="ib-section-row" data-section="secHeader"><label><input type="checkbox" class="sec-toggle ib-check" data-target="secHeader" checked> Company header</label><span style="color:#94A3B8;">⠿</span></div>
        <div class="ib-section-row" data-section="secContact"><label><input type="checkbox" class="sec-toggle ib-check" data-target="secContact" checked> Contact line</label><span style="color:#94A3B8;">⠿</span></div>
        <div class="ib-section-row" data-section="secBanner"><label><input type="checkbox" class="sec-toggle ib-check" data-target="secBanner" checked> Title banner</label><span style="color:#94A3B8;">⠿</span></div>
        <div class="ib-section-row" data-section="secMeta"><label><input type="checkbox" class="sec-toggle ib-check" data-target="secMeta" checked> Client and details</label><span style="color:#94A3B8;">⠿</span></div>
        <div class="ib-section-row" data-section="secTable"><label><input type="checkbox" class="sec-toggle ib-check" data-target="secTable" checked> Items table</label><span style="color:#94A3B8;">⠿</span></div>
        <div class="ib-section-row" data-section="secTotals"><label><input type="checkbox" class="sec-toggle ib-check" data-target="secTotals" checked> Notes and totals</label><span style="color:#94A3B8;">⠿</span></div>
        <div class="ib-section-row" data-section="secSignatures"><label><input type="checkbox" class="sec-toggle ib-check" data-target="secSignatures" checked> Signatures and stamp</label><span style="color:#94A3B8;">⠿</span></div>
        <div class="ib-section-row" data-section="secFooter"><label><input type="checkbox" class="sec-toggle ib-check" data-target="secFooter" checked> Footer</label><span style="color:#94A3B8;">⠿</span></div>
      </div>
    </div>
  </div>

  <!-- Table -->
  <div class="ib-pane" id="paneTable">
    <div class="ib-group">
      <div class="ib-group-title">Rows</div>
      <div class="ib-grid-2">
        <button type="button" class="ib-btn" id="btnInspAddRow">Add 1 row</button>
        <button type="button" class="ib-btn" id="btnInspAdd5">Add 5 rows</button>
        <button type="button" class="ib-btn" id="btnInspDelRow">Remove last</button>
        <button type="button" class="ib-btn ib-btn--danger" id="btnInspClearRows">Clear table</button>
      </div>
    </div>
    <div class="ib-group">
      <div class="ib-group-title">Columns</div>
      <div id="inspColumnsList" style="margin-bottom:10px;"></div>
      <button type="button" class="ib-btn ib-btn--primary" id="btnInspAddCol" style="width:100%;justify-content:center;">Add column</button>
    </div>
  </div>

  <!-- Signatures -->
  <div class="ib-pane" id="paneStamps">
    <div class="ib-group">
      <div class="ib-group-title">Company stamp</div>
      <div class="ib-row"><span>Show stamp</span><input type="checkbox" id="checkOfficialStamp" class="ib-check" checked></div>
      <div class="ib-field">
        <label for="stampColorSelect">Stamp colour</label>
        <select class="ib-select--full ib-select" id="stampColorSelect" style="width:100%;">
          <option value="#991B1B" selected>Red (default)</option>
          <option value="#0F3763">Navy</option>
          <option value="#076835">Green</option>
        </select>
      </div>
    </div>
    <div class="ib-group">
      <div class="ib-group-title">Signature lines</div>
      <div class="ib-row"><span>First signature</span><input type="checkbox" id="checkSig1" class="ib-check" checked></div>
      <div class="ib-row"><span>Second signature</span><input type="checkbox" id="checkSig2" class="ib-check" checked></div>
      <div class="ib-row"><span>Client acceptance</span><input type="checkbox" id="checkSig3" class="ib-check" checked></div>
    </div>
  </div>
</aside>
