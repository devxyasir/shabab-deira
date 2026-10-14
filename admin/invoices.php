<?php
$pageTitle = 'Invoices & Timesheets';
require_once __DIR__ . '/header.php';

$db = get_db_connection();

// Status update action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $id = intval($_POST['id'] ?? 0);
    $newStatus = trim($_POST['status'] ?? '');
    if ($id > 0 && !empty($newStatus)) {
        $db->prepare("UPDATE invoices SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?")->execute([$newStatus, $id]);
        $successMsg = "Invoice status updated to " . htmlspecialchars($newStatus);
    }
}

// Single delete action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = intval($_POST['id'] ?? 0);
    if ($id > 0) {
        $db->prepare("DELETE FROM invoices WHERE id = ?")->execute([$id]);
        $successMsg = "Invoice record deleted successfully.";
    }
}

// Bulk delete action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'bulk_delete') {
    $rawIds = $_POST['selected_ids'] ?? [];
    if (!is_array($rawIds)) {
        $ids = array_filter(array_map('intval', explode(',', (string)$rawIds)));
    } else {
        $ids = array_filter(array_map('intval', $rawIds));
    }
    if (!empty($ids)) {
        $in = implode(',', array_fill(0, count($ids), '?'));
        $db->prepare("DELETE FROM invoices WHERE id IN ($in)")->execute($ids);
        $successMsg = count($ids) . " invoice(s) permanently deleted.";
    }
}

// Bulk status update action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'bulk_status') {
    $rawIds = $_POST['selected_ids'] ?? [];
    $newStatus = trim($_POST['bulk_status_val'] ?? '');
    if (!is_array($rawIds)) {
        $ids = array_filter(array_map('intval', explode(',', (string)$rawIds)));
    } else {
        $ids = array_filter(array_map('intval', $rawIds));
    }
    if (!empty($ids) && in_array($newStatus, ['Draft', 'Issued', 'Paid'])) {
        $in = implode(',', array_fill(0, count($ids), '?'));
        $params = array_merge([$newStatus], $ids);
        $db->prepare("UPDATE invoices SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id IN ($in)")->execute($params);
        $successMsg = count($ids) . " invoice(s) marked as " . htmlspecialchars($newStatus) . ".";
    }
}

// Search & filter
$search = trim($_GET['search'] ?? '');
$statusFilter = trim($_GET['status'] ?? '');
$templateFilter = trim($_GET['template'] ?? '');

$where = ["1=1"];
$params = [];

if (!empty($statusFilter)) {
    $where[] = "status = ?";
    $params[] = $statusFilter;
}

if (!empty($templateFilter)) {
    $where[] = "template_type = ?";
    $params[] = $templateFilter;
}

if (!empty($search)) {
    $where[] = "(invoice_no LIKE ? OR client_name LIKE ? OR client_company LIKE ? OR notes LIKE ?)";
    $term = "%$search%";
    $params = array_merge($params, [$term, $term, $term, $term]);
}

$sql = "SELECT * FROM invoices WHERE " . implode(" AND ", $where) . " ORDER BY id DESC";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$invoices = $stmt->fetchAll();

// Aggregated metrics
$totalInvoicesCount = 0;
$totalHoursSum = 0;
$totalIssuedCount = 0;
$totalPaidCount = 0;

try {
    $allInv = $db->query("SELECT status, total_hours FROM invoices")->fetchAll();
    $totalInvoicesCount = count($allInv);
    foreach ($allInv as $inv) {
        if ($inv['status'] === 'Issued') $totalIssuedCount++;
        if ($inv['status'] === 'Paid') $totalPaidCount++;
        // Parse hours (e.g. "152:00" -> 152)
        if (preg_match('/^(\d+)/', $inv['total_hours'], $m)) {
            $totalHoursSum += intval($m[1]);
        }
    }
} catch (Exception $e) {}
?>

<div class="crm-content-header" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; margin-bottom: 24px;">
  <div>
    <h1 class="crm-page-title" style="font-size: 1.75rem; font-weight: 800; color: #0F172A; display: flex; align-items: center; gap: 10px; margin: 0;">
      <i data-lucide="receipt" style="color: #076835; width: 28px; height: 28px;"></i> Invoices &amp; Timesheets Hub
    </h1>
    <p class="crm-page-subtitle" style="color: #64748B; font-size: 0.92rem; margin: 4px 0 0 0;">Manage, create, print and dispatch commercial invoices and workforce timesheets.</p>
  </div>
  <div class="crm-content-header__actions" style="display: flex; gap: 10px; flex-wrap: wrap;">
    <a href="invoice-view.php?template=timesheet" class="btn btn--primary">
      <i data-lucide="edit-3"></i> Open Active Timesheet Workspace
    </a>
    <a href="invoice-view.php?action=new&template=timesheet" class="btn btn--outline">
      <i data-lucide="plus"></i> New Timesheet
    </a>
  </div>
</div>

<?php if (!empty($successMsg)): ?>
  <div class="alert alert--success" style="margin-bottom: 20px;">
    <i data-lucide="check-circle"></i>
    <span><?php echo $successMsg; ?></span>
  </div>
<?php endif; ?>

<!-- ══════════════ KPI METRICS ROW ══════════════ -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 16px; margin-bottom: 24px;">
  <div class="crm-panel" style="padding: 16px 20px;">
    <div style="font-size: 0.75rem; font-weight: 700; color: #64748B; text-transform: uppercase;">Total Invoices</div>
    <div style="font-size: 1.65rem; font-weight: 800; color: #0F172A; margin: 4px 0;"><?php echo $totalInvoicesCount; ?></div>
    <div style="font-size: 0.78rem; color: #076835; font-weight: 600;"><i data-lucide="database" style="width: 14px; height: 14px; vertical-align: middle;"></i> Saved in database</div>
  </div>

  <div class="crm-panel" style="padding: 16px 20px;">
    <div style="font-size: 0.75rem; font-weight: 700; color: #64748B; text-transform: uppercase;">Total Workforce Hours</div>
    <div style="font-size: 1.65rem; font-weight: 800; color: #076835; margin: 4px 0;"><?php echo number_format($totalHoursSum); ?>+ Hrs</div>
    <div style="font-size: 0.78rem; color: #64748B;"><i data-lucide="clock" style="width: 14px; height: 14px; vertical-align: middle;"></i> Billed across UAE depots</div>
  </div>

  <div class="crm-panel" style="padding: 16px 20px;">
    <div style="font-size: 0.75rem; font-weight: 700; color: #64748B; text-transform: uppercase;">Issued &amp; Dispatched</div>
    <div style="font-size: 1.65rem; font-weight: 800; color: #D97706; margin: 4px 0;"><?php echo $totalIssuedCount; ?></div>
    <div style="font-size: 0.78rem; color: #64748B;"><i data-lucide="send" style="width: 14px; height: 14px; vertical-align: middle;"></i> Sent to corporate clients</div>
  </div>

  <div class="crm-panel" style="padding: 16px 20px;">
    <div style="font-size: 0.75rem; font-weight: 700; color: #64748B; text-transform: uppercase;">Settled &amp; Paid</div>
    <div style="font-size: 1.65rem; font-weight: 800; color: #10B981; margin: 4px 0;"><?php echo $totalPaidCount; ?></div>
    <div style="font-size: 0.78rem; color: #10B981; font-weight: 600;"><i data-lucide="check-circle" style="width: 14px; height: 14px; vertical-align: middle;"></i> Completed transactions</div>
  </div>
</div>

<!-- ══════════════ FILTER & SEARCH TOOLBAR ══════════════ -->
<div class="filter-toolbar" style="margin-bottom: 24px;">
  <form method="GET" class="filter-toolbar__form">
    <div class="input-icon-wrap" style="flex-grow: 1; max-width: 360px;">
      <i data-lucide="search"></i>
      <input type="text" name="search" class="form-input" placeholder="Search invoice #, client, company..." value="<?php echo htmlspecialchars($search); ?>">
    </div>

    <select name="template" class="form-select" style="max-width: 180px;" onchange="this.form.submit()">
      <option value="">All Templates</option>
      <option value="timesheet" <?php echo ($templateFilter === 'timesheet') ? 'selected' : ''; ?>>Timesheet Invoice</option>
      <option value="standard" <?php echo ($templateFilter === 'standard') ? 'selected' : ''; ?>>Tax Invoice (VAT)</option>
    </select>

    <select name="status" class="form-select" style="max-width: 160px;" onchange="this.form.submit()">
      <option value="">All Statuses</option>
      <option value="Draft" <?php echo ($statusFilter === 'Draft') ? 'selected' : ''; ?>>Draft</option>
      <option value="Issued" <?php echo ($statusFilter === 'Issued') ? 'selected' : ''; ?>>Issued</option>
      <option value="Paid" <?php echo ($statusFilter === 'Paid') ? 'selected' : ''; ?>>Paid</option>
    </select>

    <button type="submit" class="btn btn--outline">Filter</button>
    <?php if (!empty($search) || !empty($statusFilter) || !empty($templateFilter)): ?>
      <a href="invoices.php" class="btn btn--link">Clear</a>
    <?php endif; ?>
  </form>
</div>

<!-- ══════════════ FLOATING BULK ACTIONS TOOLBAR ══════════════ -->
<div id="bulkActionsToolbar" style="display: none; position: sticky; top: 72px; z-index: 30; background: #0F172A; color: #FFFFFF; border-radius: 12px; padding: 12px 20px; margin-bottom: 20px; box-shadow: 0 8px 24px rgba(15,23,42,0.25); align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
  <div style="display: flex; align-items: center; gap: 12px;">
    <span style="font-weight: 700; font-size: 0.95rem; background: #1E293B; padding: 5px 14px; border-radius: 9999px; display: inline-flex; align-items: center; gap: 6px;">
      <i data-lucide="check-square" style="width: 15px; height: 15px; color: #10B981;"></i>
      <span id="bulkSelectedCount">0</span> Invoices Selected
    </span>
    <button type="button" class="btn btn--sm" style="color: #94A3B8; background: transparent; border: 1px solid #334155;" onclick="clearAllSelections()">
      Deselect All
    </button>
  </div>

  <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
    <!-- Bulk Status Update Form -->
    <form method="POST" id="bulkStatusForm" style="display: inline-flex; align-items: center; gap: 8px;">
      <input type="hidden" name="action" value="bulk_status">
      <input type="hidden" name="selected_ids" class="bulk-selected-ids-input" value="">
      <select name="bulk_status_val" id="bulkStatusSelect" class="form-select" style="height: 32px; font-size: 0.85rem; background: #1E293B; color: #FFFFFF; border: 1px solid #334155; border-radius: 8px; padding: 0 10px;">
        <option value="">Change Status To...</option>
        <option value="Draft">Mark as Draft</option>
        <option value="Issued">Mark as Issued</option>
        <option value="Paid">Mark as Paid</option>
      </select>
      <button type="submit" class="btn btn--sm" style="background: #076835; color: #FFFFFF; border: 1px solid #055229; font-weight: 600; padding: 0 14px; height: 32px; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;" onclick="return submitBulkStatus()">
        <i data-lucide="check" style="width: 14px; height: 14px;"></i> Apply Status
      </button>
    </form>

    <!-- Bulk Delete Form -->
    <form method="POST" id="bulkDeleteForm" style="display: inline;">
      <input type="hidden" name="action" value="bulk_delete">
      <input type="hidden" name="selected_ids" class="bulk-selected-ids-input" value="">
      <button type="submit" class="btn btn--sm" style="background: #EF4444; border: 1px solid #DC2626; color: #FFFFFF; font-weight: 600; padding: 0 14px; height: 32px; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;" onclick="return confirmBulkDelete()">
        <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i> Delete Selected
      </button>
    </form>
  </div>
</div>

<!-- ══════════════ INVOICES DIRECTORY TABLE ══════════════ -->
<div class="crm-panel">
  <div class="crm-panel__header" style="display: flex; align-items: center; justify-content: space-between;">
    <div>
      <h2 class="crm-panel__title">Invoice Directory (<?php echo count($invoices); ?>)</h2>
      <p class="crm-panel__sub">Select checkboxes to perform bulk actions, or click any invoice to open the workspace</p>
    </div>
    <a href="invoice-view.php?template=timesheet" class="btn btn--sm btn--primary">
      <i data-lucide="plus"></i> Open Invoicing Sheet
    </a>
  </div>

  <div class="crm-table-wrap">
    <table class="crm-table" id="invoicesTable">
      <thead>
        <tr>
          <th style="width: 44px; text-align: center;">
            <input type="checkbox" id="selectAllCheckbox" title="Select / Deselect All Invoices" style="width: 17px; height: 17px; cursor: pointer; accent-color: #076835;">
          </th>
          <th>Invoice #</th>
          <th>Template</th>
          <th>Client &amp; Company</th>
          <th>Date</th>
          <th>Hours / Amount</th>
          <th>Status</th>
          <th style="text-align: right;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($invoices)): ?>
          <tr>
            <td colspan="8" class="crm-table-empty" style="text-align: center; padding: 48px; color: #94A3B8;">
              <i data-lucide="receipt" style="width: 36px; height: 36px; margin-bottom: 8px;"></i>
              <p style="margin: 0; font-size: 1rem; color: #475569;">No invoices found in your directory.</p>
              <p style="margin: 4px 0 14px 0; font-size: 0.85rem; color: #94A3B8;">All previous invoices were removed, or no records matched your filter criteria.</p>
              <a href="invoice-view.php?action=new&template=timesheet" class="btn btn--sm btn--primary">Create First Invoice</a>
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($invoices as $inv): ?>
            <tr data-row-id="<?php echo $inv['id']; ?>">
              <td style="text-align: center;">
                <input type="checkbox" class="invoice-row-checkbox" value="<?php echo $inv['id']; ?>" style="width: 17px; height: 17px; cursor: pointer; accent-color: #076835;">
              </td>
              <td>
                <a href="invoice-view.php?id=<?php echo $inv['id']; ?>&template=<?php echo htmlspecialchars($inv['template_type']); ?>" style="text-decoration: none; display: flex; align-items: center; gap: 6px;">
                  <strong style="color: #076835; font-size: 1rem;">#INV-<?php echo htmlspecialchars($inv['invoice_no']); ?></strong>
                  <i data-lucide="external-link" style="width: 14px; height: 14px; color: #94A3B8;"></i>
                </a>
              </td>
              <td>
                <?php if ($inv['template_type'] === 'timesheet'): ?>
                  <span class="crm-tag" style="background: #EBF7EE; color: #076835; border: 1px solid #C4E7CE;">
                    <i data-lucide="clock" style="width: 12px; height: 12px; vertical-align: middle;"></i> Timesheet
                  </span>
                <?php else: ?>
                  <span class="crm-tag" style="background: #FEF3C7; color: #D97706; border: 1px solid #FDE68A;">
                    <i data-lucide="file-text" style="width: 12px; height: 12px; vertical-align: middle;"></i> Tax (VAT)
                  </span>
                <?php endif; ?>
              </td>
              <td>
                <div class="crm-cell-title"><?php echo htmlspecialchars($inv['client_company'] ?: 'Direct Workforce Deployment'); ?></div>
                <div class="crm-cell-sub"><?php echo htmlspecialchars($inv['client_name'] ?: 'Shabab Deira Client'); ?></div>
              </td>
              <td style="font-size: 0.85rem; color: #64748B;">
                <?php echo htmlspecialchars($inv['invoice_date'] ?: date('d/m/Y', strtotime($inv['created_at']))); ?>
              </td>
              <td>
                <div style="font-weight: 700; color: #0F172A;">
                  <?php echo !empty($inv['total_hours']) ? htmlspecialchars($inv['total_hours']) : '-'; ?>
                </div>
                <?php if (!empty($inv['total_amount']) && floatval($inv['total_amount']) > 0): ?>
                  <div style="font-size: 0.78rem; color: #076835; font-weight: 600;">
                    AED <?php echo number_format($inv['total_amount'], 2); ?>
                  </div>
                <?php endif; ?>
              </td>
              <td>
                <form method="POST" style="display:inline;">
                  <input type="hidden" name="action" value="update_status">
                  <input type="hidden" name="id" value="<?php echo $inv['id']; ?>">
                  <select name="status" class="status-select" onchange="this.form.submit()">
                    <option value="Draft" <?php echo ($inv['status'] === 'Draft') ? 'selected' : ''; ?>>Draft</option>
                    <option value="Issued" <?php echo ($inv['status'] === 'Issued') ? 'selected' : ''; ?>>Issued</option>
                    <option value="Paid" <?php echo ($inv['status'] === 'Paid') ? 'selected' : ''; ?>>Paid</option>
                  </select>
                </form>
              </td>
              <td style="text-align: right; white-space: nowrap;">
                <div style="display: flex; gap: 6px; justify-content: flex-end;">
                  <a href="invoice-view.php?id=<?php echo $inv['id']; ?>&template=<?php echo htmlspecialchars($inv['template_type']); ?>" class="btn btn--sm btn--primary" title="Open & Edit in Dashboard">
                    <i data-lucide="edit-2"></i> Edit
                  </a>
                  <a href="invoice-view.php?id=<?php echo $inv['id']; ?>&template=<?php echo htmlspecialchars($inv['template_type']); ?>&print=1" class="btn btn--sm btn--outline" target="_blank" title="Print A4 / PDF">
                    <i data-lucide="printer"></i>
                  </a>
                  <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete Invoice #INV-<?php echo htmlspecialchars($inv['invoice_no']); ?>?');">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?php echo $inv['id']; ?>">
                    <button type="submit" class="btn btn--sm btn--outline" style="color: #EF4444; border-color: #FECACA;" title="Delete Invoice">
                      <i data-lucide="trash-2"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
// ── Bulk Selection & Action Engine ──
(function(){
  const selectAll = document.getElementById('selectAllCheckbox');
  const rowCheckboxes = document.querySelectorAll('.invoice-row-checkbox');
  const bulkBar = document.getElementById('bulkActionsToolbar');
  const countEl = document.getElementById('bulkSelectedCount');
  const hiddenIdInputs = document.querySelectorAll('.bulk-selected-ids-input');

  function getSelectedIds() {
    const ids = [];
    rowCheckboxes.forEach(cb => {
      if (cb.checked) ids.push(cb.value);
    });
    return ids;
  }

  function syncBulkState() {
    const selected = getSelectedIds();
    const count = selected.length;

    if (countEl) countEl.textContent = count;
    hiddenIdInputs.forEach(inp => {
      inp.value = selected.join(',');
    });

    if (bulkBar) {
      bulkBar.style.display = count > 0 ? 'flex' : 'none';
    }

    if (selectAll) {
      if (count === 0) {
        selectAll.checked = false;
        selectAll.indeterminate = false;
      } else if (count === rowCheckboxes.length) {
        selectAll.checked = true;
        selectAll.indeterminate = false;
      } else {
        selectAll.checked = false;
        selectAll.indeterminate = true;
      }
    }

    // Highlight selected rows
    rowCheckboxes.forEach(cb => {
      const tr = cb.closest('tr');
      if (tr) {
        if (cb.checked) {
          tr.style.backgroundColor = 'rgba(7, 104, 53, 0.05)';
        } else {
          tr.style.backgroundColor = '';
        }
      }
    });
  }

  if (selectAll) {
    selectAll.addEventListener('change', function() {
      rowCheckboxes.forEach(cb => {
        cb.checked = selectAll.checked;
      });
      syncBulkState();
    });
  }

  rowCheckboxes.forEach(cb => {
    cb.addEventListener('change', syncBulkState);
  });

  window.clearAllSelections = function() {
    if (selectAll) selectAll.checked = false;
    rowCheckboxes.forEach(cb => { cb.checked = false; });
    syncBulkState();
  };

  window.confirmBulkDelete = function() {
    const ids = getSelectedIds();
    if (ids.length === 0) {
      alert('Please select at least one invoice to delete.');
      return false;
    }
    return confirm(`Are you sure you want to PERMANENTLY delete ${ids.length} selected invoice(s)? This action cannot be undone.`);
  };

  window.submitBulkStatus = function() {
    const ids = getSelectedIds();
    const val = document.getElementById('bulkStatusSelect').value;
    if (ids.length === 0) {
      alert('Please select at least one invoice to update.');
      return false;
    }
    if (!val) {
      alert('Please choose a status from the dropdown (Draft, Issued, or Paid).');
      return false;
    }
    return confirm(`Update status of ${ids.length} invoice(s) to "${val}"?`);
  };

  // Initial sync
  syncBulkState();
})();
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
