<?php
require_once __DIR__ . '/seo.php';
require_once dirname(__DIR__, 3) . '/includes/cms-contact.php';
$cms_nav_discuss = cms_contact_texts('freelance', $lang)['nav_discuss'];
$page_title = $page_title ?? $t['meta']['title'];
$page_desc  = $page_desc ?? $t['meta']['description'];
$canonical  = $canonical ?? $site_url . '/';
$seo_schemas = $seo_schemas ?? fls_seo_schemas(fls_absolute_url($canonical), $page_title, $page_desc);
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang_meta['html']) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php fls_render_seo_head($page_title, $page_desc, $canonical, $seo_schemas); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= htmlspecialchars(fls_asset('css/site.css')) ?>?v=2">
    <?php if (!empty($cms_prefix) && $cms_prefix !== 'fl'): ?>
    <link rel="stylesheet" href="<?= htmlspecialchars(cms_contact_stylesheet_href()) ?>">
    <?php endif; ?>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect fill='%230c1222' width='100' height='100' rx='2'/><text x='50' y='62' font-size='36' text-anchor='middle' fill='%2310b981' font-family='sans-serif' font-weight='bold'>F</text></svg>">
</head>
<body>
<?php require dirname(__DIR__, 3) . '/includes/ecosystem-top-bar.php'; ?>
<header class="fls-header" id="flsHeader">
    <div class="fls-header-inner">
        <a href="<?= fls_url('index.php') ?>" class="fls-logo">
            <span class="fls-logo-icon">F</span>
            <span class="fls-logo-text">Freelance <em>CMS</em></span>
        </a>
        <nav class="fls-nav" aria-label="Main">
            <a href="<?= fls_url('index.php#features') ?>"><?= htmlspecialchars($t['nav']['features']) ?></a>
            <a href="<?= fls_url('index.php#screens') ?>"><?= htmlspecialchars($t['nav']['screens']) ?></a>
            <a href="<?= fls_url('index.php#pages') ?>"><?= htmlspecialchars($t['nav']['pages']) ?></a>
            <a href="<?= fls_url('index.php#tech') ?>"><?= htmlspecialchars($t['nav']['tech']) ?></a>
            <a href="<?= fls_url('index.php#demo') ?>"><?= htmlspecialchars($t['nav']['demo']) ?></a>
            <a href="<?= fls_url('index.php#related') ?>"><?= htmlspecialchars($t['nav']['related']) ?></a>
        </nav>
        <div class="fls-header-tools">
            <?php require __DIR__ . '/lang-dropdown.php'; ?>
            <button type="button" class="fls-menu-toggle" id="flsMenuBtn" aria-label="Menu" aria-expanded="false" aria-controls="flsMobilePanel">
                <i class="fas fa-bars fls-menu-icon-open" aria-hidden="true"></i>
                <i class="fas fa-times fls-menu-icon-close" aria-hidden="true"></i>
            </button>
        </div>
        <div class="fls-header-actions">
            <a href="<?= fls_demo_url() ?>" class="fls-btn-ghost"><i class="fas fa-play-circle"></i> <span><?= htmlspecialchars($t['demo']['frontend']) ?></span></a>
            <a href="<?= fls_demo_url('admin/login.php') ?>" class="fls-btn-ghost"><i class="fas fa-lock"></i> <span><?= htmlspecialchars($t['nav']['admin']) ?></span></a>
            <a href="<?= fls_url('contact.php') ?>" class="fls-btn-primary"><i class="fas fa-comments"></i> <span><?= htmlspecialchars($cms_nav_discuss) ?></span></a>
        </div>
    </div>
    <div class="fls-mobile-panel" id="flsMobilePanel" hidden>
        <nav class="fls-nav-mobile" aria-label="Mobile">
            <a href="<?= fls_url('index.php#features') ?>"><?= htmlspecialchars($t['nav']['features']) ?></a>
            <a href="<?= fls_url('index.php#screens') ?>"><?= htmlspecialchars($t['nav']['screens']) ?></a>
            <a href="<?= fls_url('index.php#pages') ?>"><?= htmlspecialchars($t['nav']['pages']) ?></a>
            <a href="<?= fls_url('index.php#tech') ?>"><?= htmlspecialchars($t['nav']['tech']) ?></a>
            <a href="<?= fls_url('index.php#demo') ?>"><?= htmlspecialchars($t['nav']['demo']) ?></a>
            <a href="<?= fls_url('index.php#related') ?>"><?= htmlspecialchars($t['nav']['related']) ?></a>
        </nav>
        <div class="fls-mobile-actions">
            <a href="<?= fls_demo_url() ?>" class="fls-btn-primary"><?= htmlspecialchars($t['hero']['cta_demo']) ?></a>
            <a href="<?= fls_demo_url('admin/login.php') ?>" class="fls-btn-outline"><?= htmlspecialchars($t['hero']['cta_admin']) ?></a>
            <a href="<?= fls_url('contact.php') ?>" class="fls-btn-ghost"><?= htmlspecialchars($cms_nav_discuss) ?></a>
        </div>
    </div>
</header>
<div class="fls-overlay" id="flsOverlay" hidden></div>