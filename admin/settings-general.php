<?php
require_once __DIR__ . '/init.php';
fl_admin_require();
require_once dirname(__DIR__) . '/includes/storage.php';
require_once __DIR__ . '/includes/settings-lib.php';

$admin_page = 'settings';
$settings_tab = 'general';
$page_title = $ta['settings_tab_general'] ?? ($ta['settings_general'] ?? 'General');
$settings = fl_load_settings();
$flash = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settings = fl_settings_apply_post('general', $_POST, $settings);
    $flash = fl_save_settings($settings) ? 'success' : 'error';
    $settings = fl_load_settings();
}

require __DIR__ . '/includes/layout.php';
require __DIR__ . '/includes/settings-tabs.php';
?>

<?php if ($flash === 'success'): ?>
<div class="adm-alert adm-alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($ta['settings_saved'] ?? 'Saved') ?></div>
<?php elseif ($flash === 'error'): ?>
<div class="adm-alert adm-alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($ta['error']) ?></div>
<?php endif; ?>

<form method="post" class="adm-settings-form">
    <div class="adm-card">
        <div class="adm-card-head"><h2><?= htmlspecialchars($ta['settings_general'] ?? 'General') ?></h2></div>
        <div class="adm-card-body padded">
            <div class="adm-form-grid">
                <div class="adm-field">
                    <label><?= htmlspecialchars($ta['default_tier_label'] ?? 'Default tier') ?></label>
                    <select name="default_tier">
                        <option value="simple" <?= ($settings['default_tier'] ?? '') === 'simple' ? 'selected' : '' ?>><?= htmlspecialchars($ta['tier_simple'] ?? 'Simple') ?></option>
                        <option value="pro" <?= ($settings['default_tier'] ?? '') === 'pro' ? 'selected' : '' ?>><?= htmlspecialchars($ta['tier_pro'] ?? 'Pro') ?></option>
                    </select>
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars($ta['pro_fee'] ?? 'Pro fee') ?></label>
                    <input type="number" name="pro_monthly_fee" value="<?= (int)($settings['pro_monthly_fee'] ?? 299) ?>">
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars($ta['simple_limit'] ?? 'Simple limit') ?></label>
                    <input type="number" name="simple_proposal_limit" value="<?= (int)($settings['simple_proposal_limit'] ?? 5) ?>">
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars($ta['site_tagline_label'] ?? 'Site tagline') ?></label>
                    <input type="text" name="site_tagline" value="<?= htmlspecialchars($settings['site_tagline'] ?? '') ?>">
                </div>
                <div class="adm-field adm-field-check">
                    <label><input type="checkbox" name="require_admin_activation" value="1" <?= !empty($settings['require_admin_activation']) ? 'checked' : '' ?>> <?= htmlspecialchars($ta['require_activation'] ?? 'Require activation') ?></label>
                </div>
                <div class="adm-field adm-field-check">
                    <label><input type="checkbox" name="pro_featured_listing" value="1" <?= !empty($settings['pro_featured_listing']) ? 'checked' : '' ?>> <?= htmlspecialchars($ta['pro_featured_label'] ?? 'Pro featured listing') ?></label>
                </div>
            </div>
        </div>
    </div>
    <div class="adm-form-actions adm-form-actions-sticky">
        <button type="submit" class="adm-btn adm-btn-primary"><i class="fas fa-save"></i> <?= htmlspecialchars($ta['save']) ?></button>
    </div>
</form>

<?php require __DIR__ . '/includes/layout-end.php'; ?>