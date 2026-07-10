<?php
/** @var string $admin_page @var array $ta @var string $page_title */
$layout_title = $page_title ?? ($ta['dashboard'] ?? 'Admin');
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang_meta['html']) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?= htmlspecialchars($layout_title) ?> — Freelance CMS Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= htmlspecialchars(fl_asset('css/admin.css')) ?>?v=5">
</head>
<body class="adm-body">
<div class="adm-sidebar-overlay" id="admOverlay" hidden></div>
<div class="adm-layout">
    <aside class="adm-sidebar" id="admSidebar">
        <a href="<?= fl_admin_url('index.php') ?>" class="adm-sidebar-brand">
            <div class="icon">F</div>
            <div>
                <span>Freelance CMS</span>
                <small><?= htmlspecialchars($ta['title'] ?? 'Admin') ?></small>
            </div>
        </a>
        <nav class="adm-nav">
            <a href="<?= fl_admin_url('index.php') ?>" class="<?= $admin_page === 'dashboard' ? 'active' : '' ?>">
                <i class="fas fa-chart-pie"></i> <?= htmlspecialchars($ta['dashboard']) ?>
            </a>
            <a href="<?= fl_admin_url('projects.php') ?>" class="<?= $admin_page === 'projects' ? 'active' : '' ?>">
                <i class="fas fa-folder-open"></i> <?= htmlspecialchars($ta['projects']) ?>
            </a>
            <a href="<?= fl_admin_url('proposals.php') ?>" class="<?= $admin_page === 'proposals' ? 'active' : '' ?>">
                <i class="fas fa-paper-plane"></i> <?= htmlspecialchars($ta['proposals']) ?>
            </a>
            <a href="<?= fl_admin_url('freelancers.php') ?>" class="<?= $admin_page === 'freelancers' ? 'active' : '' ?>">
                <i class="fas fa-users"></i> <?= htmlspecialchars($ta['freelancers'] ?? 'Freelancers') ?>
            </a>
            <a href="<?= fl_admin_url('users.php') ?>" class="<?= $admin_page === 'users' ? 'active' : '' ?>">
                <i class="fas fa-user-check"></i> <?= htmlspecialchars($ta['users'] ?? 'Users') ?>
            </a>
            <a href="<?= fl_admin_url('settings-general.php') ?>" class="<?= ($admin_page ?? '') === 'settings' ? 'active' : '' ?>">
                <i class="fas fa-cog"></i> <?= htmlspecialchars($ta['settings'] ?? 'Settings') ?>
            </a>
            <a href="<?= fl_url('index.php') ?>" target="_blank">
                <i class="fas fa-external-link-alt"></i> <?= htmlspecialchars($ta['view_site']) ?>
            </a>
        </nav>
        <div class="adm-sidebar-foot">
            <div class="adm-lang-mini">
                <?php foreach (fl_langs() as $code => $info): ?>
                <a href="<?= htmlspecialchars(fl_admin_lang_url($code)) ?>" class="<?= $lang === $code ? 'active' : '' ?>"><?= $info['label'] ?></a>
                <?php endforeach; ?>
            </div>
            <a href="<?= fl_admin_url('logout.php') ?>" class="adm-logout-link">
                <i class="fas fa-sign-out-alt"></i> <?= htmlspecialchars($ta['logout']) ?>
            </a>
        </div>
    </aside>

    <div class="adm-main">
        <header class="adm-topbar">
            <div style="display:flex;align-items:center;gap:12px">
                <button type="button" class="adm-menu-toggle" id="admMenuBtn" aria-label="Menu"><i class="fas fa-bars"></i></button>
                <h1><?= htmlspecialchars($layout_title) ?></h1>
            </div>
            <div class="adm-topbar-actions">
                <span class="adm-topbar-welcome" style="font-size:13px;color:var(--adm-muted)"><?= htmlspecialchars($ta['welcome']) ?>, <strong>demo</strong></span>
                <a href="<?= fl_url('index.php') ?>" class="adm-btn adm-btn-outline adm-btn-sm" target="_blank">
                    <i class="fas fa-globe"></i> <span><?= htmlspecialchars($ta['view_site']) ?></span>
                </a>
            </div>
        </header>
        <main class="adm-content">