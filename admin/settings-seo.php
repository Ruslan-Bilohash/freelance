<?php
require_once __DIR__ . '/init.php';
fl_admin_require();
require_once dirname(__DIR__) . '/includes/storage.php';
require_once __DIR__ . '/includes/settings-lib.php';

$admin_page = 'settings';
$settings_tab = 'seo';
$page_title = $ta['settings_tab_seo'] ?? ($ta['settings_seo'] ?? 'SEO');
$settings = fl_load_settings();
$flash = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settings = fl_settings_apply_post('seo', $_POST, $settings);
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
        <div class="adm-card-head"><h2><?= htmlspecialchars($ta['settings_seo'] ?? 'SEO') ?></h2></div>
        <div class="adm-card-body padded">
            <div class="adm-field adm-field-full">
                <label><?= htmlspecialchars($ta['seo_selling_text'] ?? 'Selling paragraph') ?></label>
                <textarea name="seo_selling_text" rows="5" placeholder="<?= htmlspecialchars($ta['seo_selling_placeholder'] ?? '') ?>"><?= htmlspecialchars($settings['seo_selling_text'] ?? '') ?></textarea>
                <small class="adm-field-hint"><?= htmlspecialchars($ta['seo_selling_help'] ?? '') ?></small>
            </div>
        </div>
    </div>

    <div class="adm-card">
        <div class="adm-card-head"><h2><?= htmlspecialchars($ta['instructions_title'] ?? 'Instructions') ?></h2></div>
        <div class="adm-card-body padded adm-instructions">
            <ol>
                <?php foreach ($ta['setup_steps'] ?? [] as $step): ?>
                <li><?= htmlspecialchars($step) ?></li>
                <?php endforeach; ?>
            </ol>
        </div>
    </div>

    <div class="adm-form-actions adm-form-actions-sticky">
        <button type="submit" class="adm-btn adm-btn-primary"><i class="fas fa-save"></i> <?= htmlspecialchars($ta['save']) ?></button>
    </div>
</form>

<?php require __DIR__ . '/includes/layout-end.php'; ?>