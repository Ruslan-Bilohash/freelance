<?php
require_once __DIR__ . '/init.php';
fl_admin_require();
$admin_page = 'freelancers';

$id = $_GET['id'] ?? '';
require_once dirname(__DIR__) . '/includes/storage.php';
$list = fl_load_freelancers_raw();
$idx = null;
$freelancer = null;
foreach ($list as $i => $f) {
    if (($f['id'] ?? '') === $id) {
        $idx = $i;
        $freelancer = $f;
        break;
    }
}
if ($freelancer === null) {
    header('Location: ' . fl_admin_url('freelancers.php'), true, 302);
    exit;
}

$flash = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $list[$idx]['active'] = !empty($_POST['active']);
    $list[$idx]['featured'] = !empty($_POST['featured']);
    $list[$idx]['verified'] = !empty($_POST['verified']);
    $list[$idx]['tier'] = ($_POST['tier'] ?? 'simple') === 'pro' ? 'pro' : 'simple';
    $list[$idx]['activation_status'] = in_array($_POST['activation_status'] ?? '', ['active', 'pending', 'suspended'], true) ? $_POST['activation_status'] : 'pending';
    $list[$idx]['hourly_rate_min'] = max(0, (int)($_POST['hourly_min'] ?? 0));
    $list[$idx]['hourly_rate_max'] = max(0, (int)($_POST['hourly_max'] ?? 0));
    foreach (['en', 'no', 'uk', 'ru'] as $lc) {
        if (isset($_POST['name_' . $lc])) {
            $list[$idx]['name'][$lc] = trim($_POST['name_' . $lc]);
        }
        if (isset($_POST['title_' . $lc])) {
            $list[$idx]['title'][$lc] = trim($_POST['title_' . $lc]);
        }
        if (isset($_POST['bio_' . $lc])) {
            $list[$idx]['bio'][$lc] = trim($_POST['bio_' . $lc]);
        }
    }
    $list[$idx]['skills'] = array_values(array_filter(array_map('trim', explode(',', $_POST['skills'] ?? ''))));

    if (fl_save_freelancers($list)) {
        $users = fl_load_users_raw();
        foreach ($users as &$u) {
            if (($u['freelancer_id'] ?? '') === $id) {
                $u['tier'] = $list[$idx]['tier'];
                $u['activation_status'] = $list[$idx]['activation_status'];
                break;
            }
        }
        unset($u);
        fl_save_users($users);
        $flash = 'success';
        $freelancer = $list[$idx];
    } else {
        $flash = 'error';
    }
}

$page_title = ($ta['edit'] ?? 'Edit') . ': ' . ($freelancer['name']['en'] ?? $id);
require __DIR__ . '/includes/layout.php';
?>

<?php if ($flash === 'success'): ?>
<div class="adm-alert adm-alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($ta['saved']) ?></div>
<?php elseif ($flash === 'error'): ?>
<div class="adm-alert adm-alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($ta['error']) ?></div>
<?php endif; ?>

<div class="adm-card">
    <div class="adm-card-head">
        <h2><?= htmlspecialchars($freelancer['name']['en'] ?? $id) ?></h2>
        <a href="<?= fl_admin_url('freelancers.php') ?>" class="adm-btn adm-btn-outline adm-btn-sm"><?= htmlspecialchars($ta['cancel']) ?></a>
    </div>
    <div class="adm-card-body padded">
        <form method="post" class="adm-form-grid">
            <div class="adm-field">
                <label>Name (EN)</label>
                <input type="text" name="name_en" value="<?= htmlspecialchars($freelancer['name']['en'] ?? '') ?>">
            </div>
            <div class="adm-field">
                <label>Title (EN)</label>
                <input type="text" name="title_en" value="<?= htmlspecialchars($freelancer['title']['en'] ?? '') ?>">
            </div>
            <div class="adm-field adm-field-full">
                <label>Bio (EN)</label>
                <textarea name="bio_en" rows="3"><?= htmlspecialchars($freelancer['bio']['en'] ?? '') ?></textarea>
            </div>
            <div class="adm-field">
                <label><?= htmlspecialchars($ta['tier'] ?? 'Plan') ?></label>
                <select name="tier">
                    <option value="simple" <?= ($freelancer['tier'] ?? '') === 'simple' ? 'selected' : '' ?>>Simple</option>
                    <option value="pro" <?= ($freelancer['tier'] ?? '') === 'pro' ? 'selected' : '' ?>>Pro</option>
                </select>
            </div>
            <div class="adm-field">
                <label><?= htmlspecialchars($ta['activation'] ?? 'Activation') ?></label>
                <select name="activation_status">
                    <option value="active" <?= ($freelancer['activation_status'] ?? '') === 'active' ? 'selected' : '' ?>>active</option>
                    <option value="pending" <?= ($freelancer['activation_status'] ?? '') === 'pending' ? 'selected' : '' ?>>pending</option>
                    <option value="suspended" <?= ($freelancer['activation_status'] ?? '') === 'suspended' ? 'selected' : '' ?>>suspended</option>
                </select>
            </div>
            <div class="adm-field">
                <label>Hourly min</label>
                <input type="number" name="hourly_min" value="<?= (int)($freelancer['hourly_rate_min'] ?? 0) ?>">
            </div>
            <div class="adm-field">
                <label><?= htmlspecialchars($ta['hourly_max'] ?? 'Hourly max') ?></label>
                <input type="number" name="hourly_max" value="<?= (int)($freelancer['hourly_rate_max'] ?? 0) ?>">
            </div>
            <div class="adm-field adm-field-full">
                <label><?= htmlspecialchars($ta['skills'] ?? 'Skills') ?></label>
                <input type="text" name="skills" value="<?= htmlspecialchars(implode(', ', $freelancer['skills'] ?? [])) ?>">
            </div>
            <div class="adm-field adm-field-check">
                <label><input type="checkbox" name="active" value="1" <?= !empty($freelancer['active']) ? 'checked' : '' ?>> <?= htmlspecialchars($ta['active']) ?></label>
            </div>
            <div class="adm-field adm-field-check">
                <label><input type="checkbox" name="featured" value="1" <?= !empty($freelancer['featured']) ? 'checked' : '' ?>> <?= htmlspecialchars($ta['featured_toggle'] ?? 'Featured') ?></label>
            </div>
            <div class="adm-field adm-field-check">
                <label><input type="checkbox" name="verified" value="1" <?= !empty($freelancer['verified']) ? 'checked' : '' ?>> <?= htmlspecialchars($ta['verified'] ?? 'Verified') ?></label>
            </div>
            <div class="adm-field adm-field-full">
                <button type="submit" class="adm-btn adm-btn-primary"><i class="fas fa-save"></i> <?= htmlspecialchars($ta['save']) ?></button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/includes/layout-end.php'; ?>