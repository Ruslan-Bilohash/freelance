<?php

function fl_settings_apply_post(string $section, array $post, array $settings): array
{
    switch ($section) {
        case 'general':
            $settings['default_tier'] = ($post['default_tier'] ?? 'simple') === 'pro' ? 'pro' : 'simple';
            $settings['pro_monthly_fee'] = max(0, (int)($post['pro_monthly_fee'] ?? 299));
            $settings['simple_proposal_limit'] = max(1, (int)($post['simple_proposal_limit'] ?? 5));
            $settings['require_admin_activation'] = !empty($post['require_admin_activation']);
            $settings['pro_featured_listing'] = !empty($post['pro_featured_listing']);
            $settings['site_tagline'] = trim($post['site_tagline'] ?? 'Freelance CMS Demo');
            break;

        case 'appearance':
            $settings['color_primary'] = trim($post['color_primary'] ?? '#10b981');
            $settings['color_button'] = trim($post['color_button'] ?? '#10b981');
            $settings['color_button_hover'] = trim($post['color_button_hover'] ?? '#059669');
            $settings['bg_color'] = trim($post['bg_color'] ?? '#0c1222');
            $settings['bg_image'] = trim($post['bg_image'] ?? '');
            break;

        case 'recaptcha':
            $settings['recaptcha_enabled'] = !empty($post['recaptcha_enabled']);
            $settings['recaptcha_site_key'] = trim($post['recaptcha_site_key'] ?? '');
            $settings['recaptcha_secret_key'] = trim($post['recaptcha_secret_key'] ?? '');
            break;

        case 'chat':
            $settings['chat_enabled'] = !empty($post['chat_enabled']);
            $provider = $post['chat_provider'] ?? 'none';
            $settings['chat_provider'] = in_array($provider, ['none', 'grok', 'gpt'], true) ? $provider : 'none';
            $settings['chat_api_key'] = trim($post['chat_api_key'] ?? '');
            $settings['chat_instructions'] = trim($post['chat_instructions'] ?? '');
            break;

        case 'seo':
            $settings['seo_selling_text'] = trim($post['seo_selling_text'] ?? '');
            break;
    }

    return $settings;
}

function fl_settings_tabs(): array
{
    return [
        'general'    => ['file' => 'settings-general.php',    'icon' => 'sliders-h'],
        'appearance' => ['file' => 'settings-appearance.php', 'icon' => 'palette'],
        'recaptcha'  => ['file' => 'settings-recaptcha.php',  'icon' => 'shield-alt'],
        'chat'       => ['file' => 'settings-chat.php',       'icon' => 'robot'],
        'seo'        => ['file' => 'settings-seo.php',        'icon' => 'search'],
    ];
}

function fl_settings_is_active(string $tab): bool
{
    global $settings_tab;
    return ($settings_tab ?? '') === $tab;
}