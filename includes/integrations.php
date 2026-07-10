<?php

function fl_recaptcha_site_key(): string
{
    $s = fl_load_settings();
    if (!empty($s['recaptcha_site_key'])) {
        return $s['recaptcha_site_key'];
    }
    return function_exists('cms_recaptcha_site_key') ? cms_recaptcha_site_key() : '';
}

function fl_recaptcha_secret_key(): string
{
    $s = fl_load_settings();
    if (!empty($s['recaptcha_secret_key'])) {
        return $s['recaptcha_secret_key'];
    }
    return function_exists('cms_recaptcha_secret_key') ? cms_recaptcha_secret_key() : '';
}

function fl_recaptcha_enabled(): bool
{
    $s = fl_load_settings();
    if (isset($s['recaptcha_enabled'])) {
        return (bool) $s['recaptcha_enabled'];
    }
    return true;
}

function fl_verify_recaptcha(?string $response): bool
{
    if (!fl_recaptcha_enabled()) {
        return true;
    }
    $response = trim((string) $response);
    if ($response === '') {
        return false;
    }
    $payload = http_build_query([
        'secret' => fl_recaptcha_secret_key(),
        'response' => $response,
        'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '',
    ]);
    $ctx = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => $payload,
            'timeout' => 12,
        ],
    ]);
    $raw = @file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $ctx);
    if ($raw === false) {
        return false;
    }
    $data = json_decode($raw, true);
    return !empty($data['success']);
}

function fl_chat_enabled(): bool
{
    $s = fl_load_settings();
    return !empty($s['chat_enabled']) && ($s['chat_provider'] ?? 'none') !== 'none';
}

function fl_render_chat_widget(): void
{
    if (!fl_chat_enabled()) {
        return;
    }
    $s = fl_load_settings();
    $provider = $s['chat_provider'] ?? 'grok';
    ?>
<script>
window.FL_CHAT_CONFIG = {
    provider: <?= json_encode($provider) ?>,
    instructions: <?= json_encode($s['chat_instructions'] ?? '') ?>,
    product: 'Freelance CMS'
};
</script>
<?php
$bh_chat_lang = $GLOBALS['lang'] ?? ($_GET['lang'] ?? 'en');
$bh_chat_variant = 'root';
$bh_chat_require_consent = false;
$bh_chat_crm_url = 'https://bilohash.com/ai/crm/';
include dirname(__DIR__, 2) . '/includes/bh-chat-widget.php';
}