<?php
require_once __DIR__ . '/init.php';
$id = $_GET['id'] ?? '';
$freelancer = fl_freelancer_by_id($id);
if ($freelancer === null) {
    http_response_code(404);
    $page_title = '404';
    require __DIR__ . '/includes/header.php';
    echo '<div class="fl-container"><p>' . htmlspecialchars($t['freelancers_page']['not_found']) . '</p></div>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

$name = fl_localized($freelancer, 'name', $lang);
$current_page = 'freelancer';
$page_title = $name . ' — ' . $t['meta']['site_name'];
$page_desc = bh_str_sub(fl_localized($freelancer, 'bio', $lang), 0, 160);
$canonical = $site_url . '/freelancer.php?id=' . urlencode($id);
$seo_schemas = [fl_seo_person_profile($freelancer, $canonical, $lang)];
require __DIR__ . '/includes/header.php';
?>

<div class="fl-container">
    <div class="fl-breadcrumb">
        <a href="<?= fl_url('index.php') ?>"><?= htmlspecialchars($t['breadcrumb_home']) ?></a> /
        <a href="<?= fl_url('freelancers.php') ?>"><?= htmlspecialchars($t['nav']['freelancers']) ?></a> /
        <?= htmlspecialchars($name) ?>
    </div>

    <div class="fl-profile-hero">
        <div class="fl-profile-cover"><img src="<?= htmlspecialchars(fl_freelancer_cover($freelancer)) ?>" alt=""></div>
        <div class="fl-profile-head">
            <img src="<?= htmlspecialchars(fl_freelancer_image($freelancer)) ?>" alt="<?= htmlspecialchars($name) ?>" class="fl-profile-avatar">
            <div class="fl-profile-meta">
                <h1><?= htmlspecialchars($name) ?></h1>
                <p style="color:var(--fl-text-muted);margin:0 0 8px"><?= htmlspecialchars(fl_localized($freelancer, 'title', $lang)) ?></p>
                <div class="fl-location"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars(fl_localized($freelancer, 'city', $lang)) ?>, <?= htmlspecialchars(fl_localized($freelancer, 'country', $lang)) ?></div>
                <div style="margin-top:12px;display:flex;gap:8px;flex-wrap:wrap;align-items:center">
                    <span class="fl-tier-badge fl-tier-<?= htmlspecialchars($freelancer['tier'] ?? 'simple') ?>"><?= htmlspecialchars($t['tiers'][$freelancer['tier'] ?? 'simple'] ?? '') ?></span>
                    <?php if ((float)($freelancer['rating'] ?? 0) > 0): ?>
                    <span class="fl-rating"><i class="fas fa-star"></i> <?= number_format((float)$freelancer['rating'], 1) ?> (<?= (int)($freelancer['reviews_count'] ?? 0) ?>)</span>
                    <?php endif; ?>
                    <span class="fl-freelancer-rate"><?= fl_freelancer_rate_label($freelancer) ?></span>
                </div>
            </div>
            <div class="fl-profile-actions">
                <a href="<?= fl_url('search.php') ?>" class="fl-btn-primary"><i class="fas fa-folder-open"></i> <?= htmlspecialchars($t['freelancer_page']['browse_projects']) ?></a>
            </div>
        </div>
    </div>

    <div class="fl-detail-layout">
        <div>
            <h2 class="fl-subhead"><?= htmlspecialchars($t['freelancer_page']['about']) ?></h2>
            <p class="fl-detail-desc"><?= nl2br(htmlspecialchars(fl_localized($freelancer, 'bio', $lang))) ?></p>
            <?php if (!empty($freelancer['skills'])): ?>
            <h3 class="fl-subhead"><?= htmlspecialchars($t['project']['skills']) ?></h3>
            <div class="fl-skills fl-skills-lg">
                <?php require_once __DIR__ . '/includes/skill-tags.php'; fl_render_skill_tags($freelancer['skills'], 'freelancers'); ?>
            </div>
            <?php endif; ?>
        </div>
        <aside class="fl-propose-box">
            <h3 style="margin-top:0"><?= htmlspecialchars($t['freelancer_page']['stats']) ?></h3>
            <dl class="fl-spec-grid">
                <div><dt><?= htmlspecialchars($t['freelancer_page']['completed']) ?></dt><dd><?= (int)($freelancer['completed_projects'] ?? 0) ?></dd></div>
                <div><dt><?= htmlspecialchars($t['card']['level'] ?? 'Level') ?></dt><dd><?= htmlspecialchars($t['levels'][$freelancer['experience_level'] ?? 'intermediate'] ?? '') ?></dd></div>
                <div><dt><?= htmlspecialchars($t['auth']['availability']) ?></dt><dd><?= htmlspecialchars($t['auth']['avail_' . ($freelancer['availability'] ?? 'available')] ?? '') ?></dd></div>
            </dl>
            <?php if (($freelancer['tier'] ?? '') === 'pro'): ?>
            <div class="fl-alert fl-alert-info" style="margin-top:16px"><i class="fas fa-crown"></i> <?= htmlspecialchars($t['tiers']['pro_badge']) ?></div>
            <?php endif; ?>
        </aside>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>