<?php
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/includes/user-auth.php';
fl_user_require();

$user = fl_current_user();
$freelancer = fl_user_freelancer_profile($user);
$flash = '';
$saved = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $freelancer) {
    require_once __DIR__ . '/storage.php';
    $list = fl_load_freelancers_raw();
    foreach ($list as &$f) {
        if (($f['id'] ?? '') !== ($freelancer['id'] ?? '')) {
            continue;
        }
        foreach (['en', 'no', 'uk', 'ru'] as $lc) {
            if (!empty($_POST['title_' . $lc])) {
                $f['title'][$lc] = trim($_POST['title_' . $lc]);
            }
            if (!empty($_POST['bio_' . $lc])) {
                $f['bio'][$lc] = trim($_POST['bio_' . $lc]);
            }
        }
        $f['hourly_rate_min'] = max(0, (int)($_POST['hourly_min'] ?? $f['hourly_rate_min']));
        $f['hourly_rate_max'] = max(0, (int)($_POST['hourly_max'] ?? $f['hourly_rate_max']));
        $f['availability'] = in_array($_POST['availability'] ?? '', ['available', 'limited', 'busy'], true) ? $_POST['availability'] : 'available';
        $f['skills'] = array_values(array_filter(array_map('trim', explode(',', $_POST['skills'] ?? ''))));
        $freelancer = $f;
        break;
    }
    unset($f);
    $saved = fl_save_freelancers($list);
    $flash = $saved ? 'success' : 'error';
}

$current_page = 'profile';
$page_title = $t['auth']['profile_title'];
$page_desc = $t['auth']['profile_sub'];
$canonical = $site_url . '/profile.php';
require __DIR__ . '/includes/header.php';
?>

<div class="fl-container">
    <?php if ($flash === 'success'): ?>
    <div class="fl-alert fl-alert-success" style="margin-top:24px"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($t['admin']['saved']) ?></div>
    <?php elseif ($flash === 'error'): ?>
    <div class="fl-alert fl-alert-error" style="margin-top:24px"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($t['admin']['error']) ?></div>
    <?php endif; ?>

    <div class="fl-profile-hero">
        <?php if ($freelancer): ?>
        <div class="fl-profile-cover"><img src="<?= htmlspecialchars(fl_freelancer_cover($freelancer)) ?>" alt=""></div>
        <div class="fl-profile-head">
            <img src="<?= htmlspecialchars(fl_freelancer_image($freelancer)) ?>" alt="" class="fl-profile-avatar">
            <div class="fl-profile-meta">
                <h1><?= htmlspecialchars(fl_localized($freelancer, 'name', $lang)) ?></h1>
                <p style="color:var(--fl-text-muted);margin:0"><?= htmlspecialchars(fl_localized($freelancer, 'title', $lang)) ?></p>
                <div style="margin-top:10px;display:flex;gap:8px;flex-wrap:wrap">
                    <span class="fl-tier-badge fl-tier-<?= htmlspecialchars($user['tier'] ?? 'simple') ?>"><?= htmlspecialchars($t['tiers'][$user['tier'] ?? 'simple'] ?? '') ?></span>
                    <span class="fl-tier-badge fl-tier-<?= ($user['activation_status'] ?? '') === 'active' ? 'pro' : 'pending' ?>"><?= htmlspecialchars($t['auth']['status_' . ($user['activation_status'] ?? 'pending')] ?? '') ?></span>
                </div>
            </div>
            <div class="fl-profile-actions">
                <a href="<?= fl_url('freelancer.php?id=' . urlencode($freelancer['id'])) ?>" class="fl-btn-outline-dark" target="_blank"><i class="fas fa-eye"></i> <?= htmlspecialchars($t['auth']['view_public']) ?></a>
                <a href="<?= fl_url('logout.php') ?>" class="fl-btn-outline"><i class="fas fa-sign-out-alt"></i> <?= htmlspecialchars($t['auth']['logout']) ?></a>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="fl-auth-card" style="margin-bottom:48px">
        <h2 style="margin-top:0"><?= htmlspecialchars($t['auth']['edit_profile']) ?></h2>
        <?php if ($freelancer): ?>
        <form method="post">
            <div class="fl-form-field">
                <label>Title (EN)</label>
                <input type="text" name="title_en" value="<?= htmlspecialchars($freelancer['title']['en'] ?? '') ?>">
            </div>
            <div class="fl-form-field">
                <label>Bio (EN)</label>
                <textarea name="bio_en" rows="3"><?= htmlspecialchars($freelancer['bio']['en'] ?? '') ?></textarea>
            </div>
            <div class="fl-form-row">
                <div class="fl-form-field">
                    <label><?= htmlspecialchars($t['auth']['hourly_min']) ?></label>
                    <input type="number" name="hourly_min" value="<?= (int)($freelancer['hourly_rate_min'] ?? 0) ?>">
                </div>
                <div class="fl-form-field">
                    <label><?= htmlspecialchars($t['auth']['hourly_max']) ?></label>
                    <input type="number" name="hourly_max" value="<?= (int)($freelancer['hourly_rate_max'] ?? 0) ?>">
                </div>
            </div>
            <div class="fl-form-field">
                <label><?= htmlspecialchars($t['auth']['skills']) ?></label>
                <input type="text" name="skills" value="<?= htmlspecialchars(implode(', ', $freelancer['skills'] ?? [])) ?>">
            </div>
            <div class="fl-form-field">
                <label><?= htmlspecialchars($t['auth']['availability']) ?></label>
                <select name="availability">
                    <option value="available" <?= ($freelancer['availability'] ?? '') === 'available' ? 'selected' : '' ?>><?= htmlspecialchars($t['auth']['avail_available']) ?></option>
                    <option value="limited" <?= ($freelancer['availability'] ?? '') === 'limited' ? 'selected' : '' ?>><?= htmlspecialchars($t['auth']['avail_limited']) ?></option>
                    <option value="busy" <?= ($freelancer['availability'] ?? '') === 'busy' ? 'selected' : '' ?>><?= htmlspecialchars($t['auth']['avail_busy']) ?></option>
                </select>
            </div>
            <button type="submit" class="fl-btn-primary"><i class="fas fa-save"></i> <?= htmlspecialchars($t['admin']['save']) ?></button>
        </form>
        <?php else: ?>
        <div class="fl-alert fl-alert-info"><?= htmlspecialchars($t['auth']['no_profile']) ?></div>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>