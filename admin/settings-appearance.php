<?php
require_once __DIR__ . '/init.php';
fl_admin_require();
require_once dirname(__DIR__) . '/includes/storage.php';
require_once __DIR__ . '/includes/settings-lib.php';

$admin_page = 'settings';
$settings_tab = 'appearance';
$page_title = $ta['settings_tab_appearance'] ?? ($ta['settings_appearance'] ?? 'Appearance');
$settings = fl_load_settings();
$flash = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settings = fl_settings_apply_post('appearance', $_POST, $settings);
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
        <div class="adm-card-head"><h2><?= htmlspecialchars($ta['settings_appearance'] ?? 'Appearance') ?></h2></div>
        <div class="adm-card-body padded">
            <p class="adm-help"><?= htmlspecialchars($ta['appearance_help'] ?? '') ?></p>
            <div class="adm-form-grid adm-color-grid">
                <div class="adm-field">
                    <label><?= htmlspecialchars($ta['color_primary'] ?? 'Site accent colour') ?></label>
                    <input type="color" name="color_primary" value="<?= htmlspecialchars($settings['color_primary'] ?? '#10b981') ?>">
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars($ta['color_button'] ?? 'Button colour') ?></label>
                    <input type="color" name="color_button" value="<?= htmlspecialchars($settings['color_button'] ?? '#10b981') ?>">
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars($ta['color_button_hover'] ?? 'Button hover') ?></label>
                    <input type="color" name="color_button_hover" value="<?= htmlspecialchars($settings['color_button_hover'] ?? '#059669') ?>">
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars($ta['bg_color'] ?? 'Background colour') ?></label>
                    <input type="color" name="bg_color" value="<?= htmlspecialchars($settings['bg_color'] ?? '#0c1222') ?>">
                </div>
                <div class="adm-field adm-field-full">
                    <label><?= htmlspecialchars($ta['bg_image'] ?? 'Background image URL') ?></label>
                    <input type="url" name="bg_image" value="<?= htmlspecialchars($settings['bg_image'] ?? '') ?>" placeholder="https://...">
                </div>
            </div>
        </div>
    </div>
    <div class="adm-form-actions adm-form-actions-sticky">
        <button type="submit" class="adm-btn adm-btn-primary"><i class="fas fa-save"></i> <?= htmlspecialchars($ta['save']) ?></button>
    </div>
</form>

<?php require __DIR__ . '/includes/layout-end.php'; ?>