<?php
/** @var array $ta @var string $settings_tab */
require_once __DIR__ . '/settings-lib.php';
?>
<nav class="adm-settings-tabs" aria-label="Settings sections">
    <?php foreach (fl_settings_tabs() as $key => $tab):
        $label = $ta['settings_tab_' . $key] ?? ucfirst($key);
    ?>
    <a href="<?= fl_admin_url($tab['file']) ?>"
       class="adm-settings-tab <?= fl_settings_is_active($key) ? 'active' : '' ?>">
        <i class="fas fa-<?= htmlspecialchars($tab['icon']) ?>"></i>
        <span><?= htmlspecialchars($label) ?></span>
    </a>
    <?php endforeach; ?>
</nav>