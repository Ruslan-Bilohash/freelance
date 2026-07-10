<?php
require_once __DIR__ . '/init.php';
fl_admin_require();
require_once dirname(__DIR__) . '/includes/storage.php';
require_once __DIR__ . '/includes/settings-lib.php';

$admin_page = 'settings';
$settings_tab = 'recaptcha';
$page_title = $ta['settings_tab_recaptcha'] ?? ($ta['recaptcha_section'] ?? 'reCAPTCHA');
$settings = fl_load_settings();
$flash = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settings = fl_settings_apply_post('recaptcha', $_POST, $settings);
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
        <div class="adm-card-head">
            <h2><i class="fas fa-shield-alt" style="color:var(--adm-accent);margin-right:8px"></i> <?= htmlspecialchars($ta['recaptcha_section'] ?? 'reCAPTCHA') ?></h2>
        </div>
        <div class="adm-card-body padded">
            <p class="adm-help"><?= htmlspecialchars($ta['recaptcha_help'] ?? 'Google reCAPTCHA v2 keys for the contact form. Get keys at google.com/recaptcha/admin.') ?></p>
            <div class="adm-form-grid">
                <div class="adm-field adm-field-check adm-field-full">
                    <label><input type="checkbox" name="recaptcha_enabled" value="1" <?= !empty($settings['recaptcha_enabled']) ? 'checked' : '' ?>> <?= htmlspecialchars($ta['recaptcha_enabled'] ?? 'Enable reCAPTCHA') ?></label>
                </div>
                <div class="adm-field adm-field-full">
                    <label><?= htmlspecialchars($ta['recaptcha_site_key'] ?? 'Site key') ?></label>
                    <input type="text" name="recaptcha_site_key" value="<?= htmlspecialchars($settings['recaptcha_site_key'] ?? '') ?>" autocomplete="off" placeholder="6Lc...">
                </div>
                <div class="adm-field adm-field-full">
                    <label><?= htmlspecialchars($ta['recaptcha_secret_key'] ?? 'Secret key') ?></label>
                    <input type="password" name="recaptcha_secret_key" value="<?= htmlspecialchars($settings['recaptcha_secret_key'] ?? '') ?>" autocomplete="off" placeholder="6Lc...">
                </div>
            </div>
        </div>
    </div>
    <div class="adm-form-actions adm-form-actions-sticky">
        <button type="submit" class="adm-btn adm-btn-primary"><i class="fas fa-save"></i> <?= htmlspecialchars($ta['save']) ?></button>
    </div>
</form>

<?php require __DIR__ . '/includes/layout-end.php'; ?>