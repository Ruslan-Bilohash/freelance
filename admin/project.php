<?php
require_once __DIR__ . '/init.php';
fl_admin_require();
$admin_page = 'projects';

$id = $_GET['id'] ?? '';
$list = fl_load_projects_raw();
$idx = null;
$project = null;
foreach ($list as $i => $p) {
    if ($p['id'] === $id) {
        $idx = $i;
        $project = $p;
        break;
    }
}
if ($project === null) {
    header('Location: ' . fl_admin_url('projects.php'), true, 302);
    exit;
}

$flash = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $list[$idx]['budget_min']      = max(0, (int)($_POST['budget_min'] ?? $project['budget_min']));
    $list[$idx]['budget_max']      = max(0, (int)($_POST['budget_max'] ?? $project['budget_max']));
    $list[$idx]['proposals_count'] = max(0, (int)($_POST['proposals_count'] ?? $project['proposals_count']));
    $list[$idx]['deadline']        = trim($_POST['deadline'] ?? $project['deadline']);
    $list[$idx]['active']          = !empty($_POST['active']);
    $list[$idx]['featured']        = !empty($_POST['featured']);
    $list[$idx]['status']          = ($_POST['status'] ?? 'open') === 'closed' ? 'closed' : 'open';
    $list[$idx]['category']        = in_array($_POST['category'] ?? '', fl_categories(), true) ? $_POST['category'] : $project['category'];
    $list[$idx]['budget_type']     = ($_POST['budget_type'] ?? 'fixed') === 'hourly' ? 'hourly' : 'fixed';
    foreach (['en', 'no', 'uk', 'ru'] as $lc) {
        if (!empty($_POST['name_' . $lc])) {
            $list[$idx]['name'][$lc] = trim($_POST['name_' . $lc]);
        }
    }
    if (fl_save_projects($list)) {
        $flash = 'success';
        $project = $list[$idx];
    } else {
        $flash = 'error';
    }
}

$page_title = ($ta['edit'] ?? 'Edit') . ': ' . ($project['name']['en'] ?? $id);
require __DIR__ . '/includes/layout.php';
?>

<?php if ($flash === 'success'): ?>
<div class="adm-alert adm-alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($ta['saved']) ?></div>
<?php elseif ($flash === 'error'): ?>
<div class="adm-alert adm-alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($ta['error']) ?></div>
<?php endif; ?>

<div class="adm-card">
    <div class="adm-card-head">
        <h2><?= htmlspecialchars($project['name']['en'] ?? $id) ?></h2>
        <a href="<?= fl_admin_url('projects.php') ?>" class="adm-btn adm-btn-outline adm-btn-sm"><?= htmlspecialchars($ta['cancel']) ?></a>
    </div>
    <div class="adm-card-body padded">
        <form method="post">
            <div style="display:flex;gap:20px;margin-bottom:20px;flex-wrap:wrap">
                <img src="<?= htmlspecialchars(fl_project_image($project)) ?>" alt="" style="width:200px;height:140px;object-fit:cover;border-radius:8px;border:1px solid var(--adm-border)" onerror="this.onerror=null;this.src='<?= htmlspecialchars(fl_placeholder_image()) ?>';">
                <div style="font-size:13px;color:var(--adm-muted)">
                    <strong>ID:</strong> <?= htmlspecialchars($project['id']) ?><br>
                    <?= htmlspecialchars($project['city']['en'] ?? '') ?>, <?= htmlspecialchars($project['country']['en'] ?? '') ?><br>
                    <a href="<?= fl_url('project.php?id=' . urlencode($project['id'])) ?>" target="_blank">View on site →</a>
                </div>
            </div>

            <div class="adm-form-grid">
                <div class="adm-field">
                    <label><?= htmlspecialchars($ta['name_en']) ?> (EN)</label>
                    <input type="text" name="name_en" value="<?= htmlspecialchars($project['name']['en'] ?? '') ?>">
                </div>
                <div class="adm-field">
                    <label>Name (NO)</label>
                    <input type="text" name="name_no" value="<?= htmlspecialchars($project['name']['no'] ?? '') ?>">
                </div>
                <div class="adm-field">
                    <label>Name (UK)</label>
                    <input type="text" name="name_uk" value="<?= htmlspecialchars($project['name']['uk'] ?? '') ?>">
                </div>
                <div class="adm-field">
                    <label>Name (RU)</label>
                    <input type="text" name="name_ru" value="<?= htmlspecialchars($project['name']['ru'] ?? '') ?>">
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars($ta['category']) ?></label>
                    <select name="category">
                        <?php foreach (fl_categories() as $cat): ?>
                        <option value="<?= $cat ?>" <?= ($project['category'] ?? '') === $cat ? 'selected' : '' ?>><?= htmlspecialchars($t['categories'][$cat]) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars($ta['budget_type'] ?? 'Budget type') ?></label>
                    <select name="budget_type">
                        <option value="fixed" <?= ($project['budget_type'] ?? 'fixed') === 'fixed' ? 'selected' : '' ?>><?= htmlspecialchars($ta['fixed'] ?? 'Fixed price') ?></option>
                        <option value="hourly" <?= ($project['budget_type'] ?? '') === 'hourly' ? 'selected' : '' ?>><?= htmlspecialchars($ta['hourly'] ?? 'Hourly') ?></option>
                    </select>
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars($ta['budget_min']) ?></label>
                    <input type="number" name="budget_min" min="0" step="500" value="<?= (int)($project['budget_min'] ?? 0) ?>">
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars($ta['budget_max'] ?? 'Budget max') ?></label>
                    <input type="number" name="budget_max" min="0" step="500" value="<?= (int)($project['budget_max'] ?? 0) ?>">
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars($ta['proposals_count']) ?></label>
                    <input type="number" name="proposals_count" min="0" value="<?= (int)($project['proposals_count'] ?? 0) ?>">
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars($ta['deadline']) ?></label>
                    <input type="text" name="deadline" value="<?= htmlspecialchars($project['deadline'] ?? '') ?>" placeholder="2026-07-15T18:00:00+02:00">
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars($ta['status']) ?></label>
                    <select name="status">
                        <option value="open" <?= ($project['status'] ?? 'open') === 'open' ? 'selected' : '' ?>><?= htmlspecialchars($ta['open'] ?? 'Open') ?></option>
                        <option value="closed" <?= ($project['status'] ?? '') === 'closed' ? 'selected' : '' ?>><?= htmlspecialchars($ta['closed'] ?? 'Closed') ?></option>
                    </select>
                </div>
            </div>

            <div class="adm-check-row" style="margin-top:16px">
                <label><input type="checkbox" name="active" value="1" <?= ($project['active'] ?? true) ? 'checked' : '' ?>> <?= htmlspecialchars($ta['active']) ?></label>
                <label><input type="checkbox" name="featured" value="1" <?= !empty($project['featured']) ? 'checked' : '' ?>> <?= htmlspecialchars($ta['featured']) ?></label>
            </div>

            <div style="margin-top:20px">
                <button type="submit" class="adm-btn adm-btn-primary"><i class="fas fa-save"></i> <?= htmlspecialchars($ta['save']) ?></button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/includes/layout-end.php'; ?>