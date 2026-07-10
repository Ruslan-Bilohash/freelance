<?php

function fl_hex_color(string $hex, string $fallback = '#10b981'): string
{
    $hex = trim($hex);
    if (preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $hex)) {
        return $hex;
    }
    return $fallback;
}

function fl_theme_vars(): array
{
    static $vars = null;
    if ($vars !== null) {
        return $vars;
    }
    $s = fl_load_settings();
    $vars = [
        'primary' => fl_hex_color($s['color_primary'] ?? '#10b981'),
        'button' => fl_hex_color($s['color_button'] ?? ($s['color_primary'] ?? '#10b981')),
        'button_hover' => fl_hex_color($s['color_button_hover'] ?? '#059669'),
        'bg' => fl_hex_color($s['bg_color'] ?? '#0c1222', '#0c1222'),
        'bg_image' => trim($s['bg_image'] ?? ''),
    ];
    return $vars;
}

function fl_render_theme_styles(): void
{
    $v = fl_theme_vars();
    $bgRule = $v['bg_image'] !== ''
        ? "background-color:{$v['bg']};background-image:url('" . htmlspecialchars($v['bg_image'], ENT_QUOTES) . "');background-size:cover;background-attachment:fixed;background-position:center;"
        : "background-color:{$v['bg']};";
    ?>
<style id="fl-theme-vars">
:root {
    --fl-green: <?= $v['primary'] ?>;
    --fl-green-hover: <?= $v['button_hover'] ?>;
    --fl-green-light: <?= $v['primary'] ?>;
    --fl-gold: var(--fl-green);
    --fl-gold-hover: var(--fl-green-hover);
    --fl-gold-light: var(--fl-green-light);
    --fl-navy: <?= $v['bg'] ?>;
    --fl-btn: <?= $v['button'] ?>;
    --fl-btn-hover: <?= $v['button_hover'] ?>;
}
body { <?= $bgRule ?> }
.fl-btn-primary, .fl-btn-gold { background: linear-gradient(135deg, var(--fl-btn), var(--fl-btn-hover)) !important; }
.fl-btn-primary:hover, .fl-btn-gold:hover { box-shadow: 0 4px 16px color-mix(in srgb, var(--fl-btn) 35%, transparent); }
</style>
    <?php
}