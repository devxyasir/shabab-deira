<?php
/**
 * Shabab Deira Operations CRM — Invoice Template Builder.
 *
 * Thin orchestrator. Data bootstrap stays here so the server contract
 * (system templates + optional custom template by id) is unchanged.
 * Flow is chooser-first: the start screen lists every template, and the
 * full editor is revealed only after the user opens one specific template.
 * Markup lives in admin/invoice-builder-partials/, styling in
 * admin/css/invoice-builder.css, behaviour in five ordered JS files:
 * core → table → library → chooser → actions.
 */

require_once __DIR__ . '/../config/auth.php';
require_admin_auth();
require_once __DIR__ . '/../config/templates_data.php';
require_once __DIR__ . '/../config/template_layouts.php';

$db = get_db_connection();
$systemTemplates = get_system_templates();

// Merge structural layouts + list blurbs (data file stays frozen).
$__layouts = get_template_layouts();
$__blurbs = get_template_blurbs();
$__defaultLayout = get_default_template_layout();
foreach ($systemTemplates as $__k => $__t) {
    $systemTemplates[$__k]['layout'] = $__layouts[$__k] ?? $__defaultLayout;
    if (!empty($__blurbs[$__k])) {
        $systemTemplates[$__k]['blurb'] = $__blurbs[$__k];
    }
}
unset($__layouts, $__blurbs, $__defaultLayout, $__k, $__t);

$initialKey = $_GET['template'] ?? 'manpower_supply_invoice';
$customId = intval($_GET['id'] ?? 0);

$activeTemplate = null;

if ($customId > 0) {
    try {
        $stmt = $db->prepare("SELECT * FROM custom_templates WHERE id = ? LIMIT 1");
        $stmt->execute([$customId]);
        $row = $stmt->fetch();
        if ($row && !empty($row['layout_json'])) {
            $activeTemplate = json_decode($row['layout_json'], true);
        }
    } catch (Exception $e) {}
}

if (!$activeTemplate && isset($systemTemplates[$initialKey])) {
    $activeTemplate = $systemTemplates[$initialKey];
}

if (!$activeTemplate) {
    $activeTemplate = reset($systemTemplates);
}

$pageTitle = 'Template Builder | Shabab Deira CRM';
include __DIR__ . '/header.php';
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700;800&family=Cairo:wght@600;700;800&family=Source+Sans+3:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@500;600&family=Caveat:wght@700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/invoice-builder.css?v=3.1">

<div class="ib-wrap">
  <?php include __DIR__ . '/invoice-builder-partials/chooser.php'; ?>

  <div id="editorView" class="ib-hidden">
    <?php include __DIR__ . '/invoice-builder-partials/topbar.php'; ?>

    <div class="ib-body">
      <?php include __DIR__ . '/invoice-builder-partials/inspector.php'; ?>
      <?php include __DIR__ . '/invoice-builder-partials/canvas.php'; ?>
    </div>
  </div>
</div>

<div class="ib-toast" id="studioToast"></div>

<script>
  window.BUILDER_INITIAL_TEMPLATE = <?php echo json_encode($activeTemplate); ?>;
  window.BUILDER_SYSTEM_TEMPLATES = <?php echo json_encode($systemTemplates); ?>;
  window.BUILDER_INITIAL_KEY = <?php echo json_encode($initialKey); ?>;
</script>
<script src="js/invoice-builder-core.js?v=3.1"></script>
<script src="js/invoice-builder-table.js?v=3.1"></script>
<script src="js/invoice-builder-library.js?v=3.1"></script>
<script src="js/invoice-builder-chooser.js?v=3.1"></script>
<script src="js/invoice-builder-actions.js?v=3.1"></script>

<?php include __DIR__ . '/footer.php'; ?>
