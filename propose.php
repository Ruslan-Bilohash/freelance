<?php
require_once __DIR__ . '/init.php';
$id = $_GET['id'] ?? $_POST['id'] ?? '';
$project = $id ? fl_project_by_id($id) : null;
$success = false;
$ref = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $project) {
    require_once __DIR__ . '/includes/storage.php';
    $amount = (int)($_POST['amount'] ?? 0);
    $days   = (int)($_POST['delivery_days'] ?? 0);
    if ($amount <= 0 || $days <= 0) {
        $error = $t['propose']['invalid'];
    } else {
        $success = true;
        $ref = 'FL-DEMO-' . strtoupper(substr(md5(uniqid((string)mt_rand(), true)), 0, 8));
        fl_add_proposal([
            'ref'           => $ref,
            'project_id'    => $project['id'],
            'project_name'  => fl_localized($project, 'name', $lang),
            'freelancer'    => trim(($_POST['firstname'] ?? '') . ' ' . ($_POST['lastname'] ?? '')),
            'email'         => trim($_POST['email'] ?? ''),
            'phone'         => trim($_POST['phone'] ?? ''),
            'bid_amount'    => $amount,
            'delivery_days' => $days,
            'cover_letter'  => trim($_POST['cover_letter'] ?? ''),
            'status'        => 'pending',
        ]);
        $project = fl_project_by_id($id);
    }
}

$name    = $project ? fl_localized($project, 'name', $lang) : '';
$page_title = $success ? $t['propose']['success'] : $t['propose']['title'];
$page_desc  = ($project ? $name . ' — ' : '') . $t['meta']['description'];
$canonical  = $project
    ? $site_url . '/propose.php?id=' . urlencode($project['id'])
    : $site_url . '/propose.php';
$canon_abs  = fl_absolute_url($canonical);
$seo_schemas = [fl_seo_webpage($canon_abs, $page_title, $page_desc)];
if ($project) {
    $seo_schemas[] = fl_seo_breadcrumbs([
        ['name' => $t['breadcrumb_home'], 'url' => fl_absolute_url(fl_url('index.php'))],
        ['name' => $name, 'url' => fl_absolute_url(fl_url('project.php?id=' . urlencode($project['id'])))],
        ['name' => $t['propose']['title'], 'url' => $canon_abs],
    ]);
}
require __DIR__ . '/includes/header.php';

if (!$project && !$success):
?>
<div class="fl-container">
    <div class="fl-form-card fl-empty-state">
        <p><?= htmlspecialchars($t['search_page']['no_results']) ?></p>
        <a href="<?= fl_url('index.php') ?>" class="fl-btn-primary"><?= htmlspecialchars($t['breadcrumb_home']) ?></a>
    </div>
</div>
<?php
require __DIR__ . '/includes/footer.php';
exit;
endif;
?>

<?php if ($success): ?>
<div class="fl-success">
    <i class="fas fa-circle-check"></i>
    <h1><?= htmlspecialchars($t['propose']['success']) ?></h1>
    <p><?= htmlspecialchars($t['propose']['success_msg']) ?></p>
    <div class="fl-ref"><?= htmlspecialchars($ref) ?></div>
    <p class="fl-success-meta"><?= htmlspecialchars($name) ?> · <?= fl_price((int)($_POST['amount'] ?? 0)) ?> · <?= (int)($_POST['delivery_days'] ?? 0) ?> days</p>
    <div class="fl-success-actions">
        <a href="<?= fl_url('index.php') ?>" class="fl-btn-primary"><?= htmlspecialchars($t['propose']['back_home']) ?></a>
        <a href="<?= fl_url('project.php?id=' . urlencode($project['id'])) ?>" class="fl-btn-outline-accent"><?= htmlspecialchars($t['propose']['view_project']) ?></a>
    </div>
</div>
<?php else: ?>

<div class="fl-container">
    <h1 class="fl-page-title"><?= htmlspecialchars($t['propose']['title']) ?></h1>
    <div class="fl-form-layout">
        <div class="fl-form-card">
            <h2><?= htmlspecialchars($t['propose']['details']) ?></h2>
            <?php if ($error): ?>
            <div class="fl-form-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="post" action="">
                <input type="hidden" name="id" value="<?= htmlspecialchars($project['id']) ?>">
                <div class="fl-form-row">
                    <div class="fl-field">
                        <label for="amount"><?= htmlspecialchars($t['propose']['amount']) ?> *</label>
                        <input type="number" id="amount" name="amount" required min="1000" step="500" value="<?= (int)($project['budget_max'] ?? 20000) ?>">
                        <small><?= sprintf($t['propose']['amount_hint'], fl_budget_label($project)) ?></small>
                    </div>
                    <div class="fl-field">
                        <label for="delivery_days"><?= htmlspecialchars($t['propose']['delivery']) ?> *</label>
                        <input type="number" id="delivery_days" name="delivery_days" required min="1" max="365" value="14">
                    </div>
                </div>
                <div class="fl-field">
                    <label for="cover_letter"><?= htmlspecialchars($t['propose']['cover']) ?></label>
                    <textarea id="cover_letter" name="cover_letter" rows="5" placeholder="Demo proposal text…">I have relevant experience for this demo project and can deliver on time.</textarea>
                </div>
                <div class="fl-form-row">
                    <div class="fl-field">
                        <label for="firstname"><?= htmlspecialchars($t['propose']['firstname']) ?> *</label>
                        <input type="text" id="firstname" name="firstname" required value="Demo">
                    </div>
                    <div class="fl-field">
                        <label for="lastname"><?= htmlspecialchars($t['propose']['lastname']) ?> *</label>
                        <input type="text" id="lastname" name="lastname" required value="Freelancer">
                    </div>
                </div>
                <div class="fl-field">
                    <label for="email"><?= htmlspecialchars($t['propose']['email']) ?> *</label>
                    <input type="email" id="email" name="email" required value="demo@bilohash.com">
                </div>
                <div class="fl-field">
                    <label for="phone"><?= htmlspecialchars($t['propose']['phone']) ?></label>
                    <input type="tel" id="phone" name="phone" value="+4746255885">
                </div>
                <button type="submit" class="fl-btn-primary fl-btn-block">
                    <i class="fas fa-paper-plane"></i> <?= htmlspecialchars($t['propose']['submit']) ?>
                </button>
            </form>
        </div>

        <aside class="fl-propose-box">
            <h3><?= htmlspecialchars($t['propose']['summary']) ?></h3>
            <img src="<?= htmlspecialchars(fl_project_image($project)) ?>" alt="" class="fl-propose-summary-img" onerror="this.onerror=null;this.src='<?= htmlspecialchars(fl_placeholder_image()) ?>';">
            <strong class="fl-propose-summary-title"><?= htmlspecialchars($name) ?></strong>
            <div class="fl-propose-summary-meta">
                <?= htmlspecialchars($t['project']['budget']) ?>: <strong><?= fl_budget_label($project) ?></strong><br>
                <?= (int)($project['proposals_count'] ?? 0) ?> <?= htmlspecialchars($t['card']['proposals']) ?>
            </div>
            <div class="fl-demo-alert"><?= htmlspecialchars($t['project']['demo_note']) ?></div>
        </aside>
    </div>
</div>
<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>