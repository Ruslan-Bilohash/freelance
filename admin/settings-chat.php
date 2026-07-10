<?php
require_once __DIR__ . '/init.php';
fl_admin_require();
require_once dirname(__DIR__) . '/includes/storage.php';
require_once __DIR__ . '/includes/settings-lib.php';

$admin_page = 'settings';
$settings_tab = 'chat';
$page_title = $ta['settings_tab_chat'] ?? ($ta['chat_section'] ?? 'AI Chat');
$settings = fl_load_settings();
$flash = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settings = fl_settings_apply_post('chat', $_POST, $settings);
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
            <h2><i class="fas fa-robot" style="color:var(--adm-accent);margin-right:8px"></i> <?= htmlspecialchars($ta['chat_section'] ?? 'AI chat widget') ?></h2>
        </div>
        <div class="adm-card-body padded">
            <p class="adm-help"><?= htmlspecialchars($ta['chat_help'] ?? '') ?></p>
            <div class="adm-form-grid">
                <div class="adm-field adm-field-check adm-field-full">
                    <label><input type="checkbox" name="chat_enabled" value="1" <?= !empty($settings['chat_enabled']) ? 'checked' : '' ?>> <?= htmlspecialchars($ta['chat_enabled'] ?? 'Enable chat') ?></label>
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars($ta['chat_provider'] ?? 'Provider') ?></label>
                    <select name="chat_provider">
                        <option value="none" <?= ($settings['chat_provider'] ?? '') === 'none' ? 'selected' : '' ?>><?= htmlspecialchars($ta['chat_provider_none'] ?? 'Disabled') ?></option>
                        <option value="grok" <?= ($settings['chat_provider'] ?? '') === 'grok' ? 'selected' : '' ?>><?= htmlspecialchars($ta['chat_provider_grok'] ?? 'Grok xAI') ?></option>
                        <option value="gpt" <?= ($settings['chat_provider'] ?? '') === 'gpt' ? 'selected' : '' ?>><?= htmlspecialchars($ta['chat_provider_gpt'] ?? 'OpenAI GPT') ?></option>
                    </select>
                </div>
                <div class="adm-field adm-field-full">
                    <label><?= htmlspecialchars($ta['chat_api_key'] ?? 'API key') ?></label>
                    <input type="password" name="chat_api_key" value="<?= htmlspecialchars($settings['chat_api_key'] ?? '') ?>" autocomplete="off" placeholder="xai-... or sk-...">
                    <small class="adm-field-hint"><?= htmlspecialchars($ta['chat_api_key_help'] ?? 'xAI key for Grok, OpenAI key for GPT. Stored in settings JSON.') ?></small>
                </div>
                <div class="adm-field adm-field-full">
                    <label><?= htmlspecialchars($ta['chat_instructions'] ?? 'System instructions') ?></label>
                    <textarea name="chat_instructions" rows="6"><?= htmlspecialchars($settings['chat_instructions'] ?? '') ?></textarea>
                    <small class="adm-field-hint"><?= htmlspecialchars($ta['chat_instructions_help'] ?? '') ?></small>
                </div>
            </div>
        </div>
    </div>
    <div class="adm-form-actions adm-form-actions-sticky">
        <button type="submit" class="adm-btn adm-btn-primary"><i class="fas fa-save"></i> <?= htmlspecialchars($ta['save']) ?></button>
    </div>
</form>

<?php require __DIR__ . '/includes/layout-end.php'; ?>