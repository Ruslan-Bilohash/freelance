<?php
require_once dirname(__DIR__, 2) . '/includes/cms-contact.php';
require_once __DIR__ . '/user-auth.php';
$fl_user = fl_current_user();
$cms_nav_discuss = cms_contact_texts('freelance', $lang)['nav_discuss'];
$page_title = $page_title ?? $t['meta']['title'];
$page_desc  = $page_desc ?? $t['meta']['description'];
$canonical  = $canonical ?? $site_url . '/';
$body_class = $body_class ?? '';
$seo_schemas = $seo_schemas ?? [];
$seo_og_image = $seo_og_image ?? null;
$seo_og_type  = $seo_og_type ?? 'website';
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang_meta['html']) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php fl_render_seo_head($page_title, $page_desc, $canonical, $seo_schemas, $seo_og_image, $seo_og_type, !empty($seo_noindex)); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= htmlspecialchars(fl_asset('css/style.css')) ?>?v=14">
    <?php
    require_once __DIR__ . '/theme.php';
    fl_render_theme_styles();
    ?>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect fill='%230c1222' width='100' height='100' rx='2'/><text x='50' y='62' font-size='36' text-anchor='middle' fill='%2310b981' font-family='sans-serif' font-weight='bold'>F</text></svg>">
</head>
<body class="<?= htmlspecialchars($body_class) ?>">

<div class="fl-top-bar">
    <div class="fl-demo-strip" role="status">
        <i class="fas fa-briefcase" aria-hidden="true"></i>
        <span><?= htmlspecialchars($t['demo_strip']['text']) ?></span>
        <a href="https://bilohash.com/freelance/site/"><?= htmlspecialchars($t['demo_strip']['cms']) ?> →</a>
        <?php if (!empty($t['ecosystem']['items'])): ?>
        <span class="fl-demo-strip-sep" aria-hidden="true">·</span>
        <?php foreach ($t['ecosystem']['items'] as $eco): ?>
        <a href="<?= htmlspecialchars($eco['demo']) ?>" rel="related" class="fl-demo-strip-eco"><?= htmlspecialchars($eco['short'] ?? $eco['name']) ?></a>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <header class="fl-header" id="flHeader" itemscope itemtype="https://schema.org/WPHeader">
        <div class="fl-header-inner">
            <a href="<?= fl_url('index.php') ?>" class="fl-logo" itemprop="url">
                <span class="fl-logo-icon"><i class="fas fa-briefcase"></i></span>
                <span class="fl-logo-text" itemprop="name"><?= htmlspecialchars($t['meta']['site_name']) ?></span>
            </a>

            <button type="button" class="fl-menu-toggle" id="flMenuBtn" aria-label="Menu" aria-expanded="false" aria-controls="flHeaderPanel">
                <i class="fas fa-bars fl-menu-icon-open" aria-hidden="true"></i>
                <i class="fas fa-times fl-menu-icon-close" aria-hidden="true"></i>
            </button>

            <div class="fl-header-panel" id="flHeaderPanel" aria-hidden="true">
                <div class="fl-panel-head">
                    <span class="fl-panel-title"><?= htmlspecialchars($t['meta']['site_name']) ?></span>
                    <button type="button" class="fl-menu-close" id="flMenuClose" aria-label="Close menu">
                        <i class="fas fa-times" aria-hidden="true"></i>
                    </button>
                </div>
                <?php require __DIR__ . '/nav-menu.php'; ?>
                <div class="fl-header-actions">
                    <?php require __DIR__ . '/lang-dropdown.php'; ?>
                    <a href="<?= fl_url('contact.php') ?>" class="fl-btn-primary fl-btn-discuss <?= ($current_page ?? '') === 'contact' ? 'active' : '' ?>"><i class="fas fa-comments"></i> <span><?= htmlspecialchars($cms_nav_discuss) ?></span></a>
                    <?php if ($fl_user): ?>
                    <a href="<?= fl_url('profile.php') ?>" class="fl-btn-outline"><i class="fas fa-user"></i> <span><?= htmlspecialchars($fl_user['firstname'] ?? $t['nav']['profile']) ?></span></a>
                    <a href="<?= fl_url('logout.php') ?>" class="fl-btn-outline fl-btn-compact" title="<?= htmlspecialchars($t['auth']['logout'] ?? 'Logout') ?>"><i class="fas fa-sign-out-alt"></i></a>
                    <?php else: ?>
                    <a href="<?= fl_url('login.php') ?>" class="fl-btn-outline"><i class="fas fa-sign-in-alt"></i> <span><?= htmlspecialchars($t['nav']['signin']) ?></span></a>
                    <a href="<?= fl_url('register.php') ?>" class="fl-btn-outline fl-nav-drawer-only"><i class="fas fa-user-plus"></i> <span><?= htmlspecialchars($t['nav']['register']) ?></span></a>
                    <?php endif; ?>
                    <a href="<?= fl_url('admin/login.php') ?>" class="fl-btn-outline fl-btn-compact" title="Admin"><i class="fas fa-user-shield"></i></a>
                </div>
            </div>
        </div>
    </header>
</div>

<div class="fl-overlay" id="flOverlay" hidden aria-hidden="true"></div>

<script>
(function () {
    var header = document.getElementById('flHeader');
    var btn = document.getElementById('flMenuBtn');
    var closeBtn = document.getElementById('flMenuClose');
    var overlay = document.getElementById('flOverlay');
    var panel = document.getElementById('flHeaderPanel');
    if (!header || !btn) return;

    function setOpen(open) {
        header.classList.toggle('nav-open', open);
        document.body.classList.toggle('fl-nav-open', open);
        btn.setAttribute('aria-expanded', open ? 'true' : 'false');
        if (panel) panel.setAttribute('aria-hidden', open ? 'false' : 'true');
        if (overlay) {
            overlay.hidden = !open;
            overlay.setAttribute('aria-hidden', open ? 'false' : 'true');
            overlay.classList.toggle('is-open', open);
        }
    }

    btn.addEventListener('click', function (e) {
        e.stopPropagation();
        setOpen(!header.classList.contains('nav-open'));
    });
    if (closeBtn) closeBtn.addEventListener('click', function () { setOpen(false); });
    if (overlay) overlay.addEventListener('click', function () { setOpen(false); });
    if (panel) {
        panel.querySelectorAll('a').forEach(function (a) {
            a.addEventListener('click', function () { setOpen(false); });
        });
    }
    function syncDesktopPanel() {
        if (!panel) return;
        if (window.innerWidth >= 1024) {
            setOpen(false);
            panel.setAttribute('aria-hidden', 'false');
        } else if (!header.classList.contains('nav-open')) {
            panel.setAttribute('aria-hidden', 'true');
        }
    }
    syncDesktopPanel();
    window.addEventListener('resize', syncDesktopPanel);

    // Language + nav dropdowns (must live in header — footer may fail to render on server)
    var langDropdown = document.getElementById('flLangDropdown');
    var langBtn = document.getElementById('flLangBtn');
    var langMenu = document.getElementById('flLangMenu');
    var navDropdown = document.getElementById('flNavDropdown');
    var navDropdownBtn = document.getElementById('flNavDropdownBtn');
    var navDropdownMenu = document.getElementById('flNavDropdownMenu');
    var navMore = document.getElementById('flNavMore');
    var navMoreBtn = document.getElementById('flNavMoreBtn');
    var navMoreMenu = document.getElementById('flNavMoreMenu');

    function closeLangDropdown() {
        if (!langDropdown) return;
        langDropdown.classList.remove('is-open');
        if (langBtn) langBtn.setAttribute('aria-expanded', 'false');
        if (langMenu) langMenu.hidden = true;
    }
    function closeNavDropdown() {
        if (!navDropdown) return;
        navDropdown.classList.remove('is-open');
        if (navDropdownBtn) navDropdownBtn.setAttribute('aria-expanded', 'false');
        if (navDropdownMenu) navDropdownMenu.hidden = true;
    }
    function closeNavMore() {
        if (!navMore) return;
        navMore.classList.remove('is-open');
        if (navMoreBtn) navMoreBtn.setAttribute('aria-expanded', 'false');
        if (navMoreMenu) navMoreMenu.hidden = true;
    }
    function closeAllDropdowns() {
        closeLangDropdown();
        closeNavDropdown();
        closeNavMore();
    }

    if (langBtn && langMenu && langDropdown) {
        langBtn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var open = !langDropdown.classList.contains('is-open');
            closeNavDropdown();
            closeNavMore();
            langDropdown.classList.toggle('is-open', open);
            langMenu.hidden = !open;
            langBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
        langMenu.addEventListener('click', function (e) { e.stopPropagation(); });
    }
    if (navDropdownBtn && navDropdownMenu && navDropdown) {
        navDropdownBtn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var open = !navDropdown.classList.contains('is-open');
            closeLangDropdown();
            closeNavMore();
            navDropdown.classList.toggle('is-open', open);
            navDropdownMenu.hidden = !open;
            navDropdownBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
        navDropdownMenu.addEventListener('click', function (e) { e.stopPropagation(); });
    }
    if (navMoreBtn && navMoreMenu && navMore) {
        navMoreBtn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var open = !navMore.classList.contains('is-open');
            closeLangDropdown();
            closeNavDropdown();
            navMore.classList.toggle('is-open', open);
            navMoreMenu.hidden = !open;
            navMoreBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
        navMoreMenu.addEventListener('click', function (e) { e.stopPropagation(); });
    }

    document.addEventListener('click', function (e) {
        if (!e.target.closest('#flLangDropdown')) closeLangDropdown();
        if (!e.target.closest('#flNavDropdown')) closeNavDropdown();
        if (!e.target.closest('#flNavMore')) closeNavMore();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeAllDropdowns();
    });
    window.addEventListener('resize', function () {
        if (window.innerWidth >= 1024) closeAllDropdowns();
    });

    window.FL_HEADER_DROPDOWNS = true;
})();
</script>