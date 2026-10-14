<?php
// Invoice Builder partial: start screen — template chooser.
// Shown first. The editor (topbar + canvas) stays hidden until the user
// opens a specific template, so nobody lands in a full editor by accident.
?>
<section id="chooserView">
  <div class="ib-chooser-bar">
    <div>
      <h2>Choose a template</h2>
      <p id="chooserCount">18 templates</p>
    </div>
    <a href="invoices.php" class="ib-btn">Back to invoices</a>
  </div>

  <div class="ib-chooser-controls">
    <input type="text" id="chooserSearch" class="ib-search" placeholder="Search templates, e.g. VAT, cash, attendance" style="margin:0;">
    <div class="ib-catbar" id="chooserCats" style="border:none;background:none;padding:0;">
      <button type="button" class="ib-cat-btn active" data-cat="All">All</button>
      <button type="button" class="ib-cat-btn" data-cat="Invoicing & Tax">Tax invoices</button>
      <button type="button" class="ib-cat-btn" data-cat="Timesheets & Labour">Timesheets</button>
      <button type="button" class="ib-cat-btn" data-cat="Payroll & Cash">Payroll</button>
      <button type="button" class="ib-cat-btn" data-cat="Operations & Logistics">Manpower supply</button>
    </div>
  </div>

  <div id="chooserGroups"></div>
  <p id="chooserEmpty" class="ib-hint" style="display:none;">No templates match this search. Clear the search or choose another category.</p>
</section>
