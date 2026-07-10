<?php
require_once __DIR__ . '/init.php';
require_once dirname(__DIR__) . '/includes/cms-contact.php';
require_once __DIR__ . '/includes/region-lib.php';

$current_page = 'regions';
$page_title   = $t['regions']['meta_title'] ?? 'Freelance CMS — Regions';
$page_desc    = $t['regions']['meta_desc'] ?? '';
$canonical    = $site_url . '/regions.php';
$canon_abs    = fl_absolute_url($canonical);
$body_class   = 'fl-regions-hub';

$seo_schemas = [
    fl_seo_organization(),
    fl_seo_webpage($canon_abs, $page_title, $page_desc),
    fl_seo_breadcrumbs([
        ['name' => $t['breadcrumb_home'], 'url' => fl_absolute_url(fl_url('index.php'))],
        ['name' => $t['regions']['hub_title'] ?? 'Regions', 'url' => $canon_abs],
    ]),
];

$all = fl_regions_all();
$norway = array_filter($all, fn($r) => ($r['country'] ?? '') === 'no');
$intl   = array_filter($all, fn($r) => in_array($r['country'] ?? '', ['eu', 'ua'], true));

require __DIR__ . '/includes/header.php';
?>

<main class="fl-container fl-solutions-hub">
    <nav class="fl-breadcrumb fl-vertical-crumb">
        <a href="<?= fl_url('index.php') ?>"><?= htmlspecialchars($t['breadcrumb_home']) ?></a>
        → <?= htmlspecialchars($t['regions']['hub_title']) ?>
    </nav>

    <section class="fl-vertical-hero">
        <div class="fl-vertical-hero-icon"><i class="fas fa-map-marked-alt"></i></div>
        <h1><?= htmlspecialchars($t['regions']['hub_h1']) ?></h1>
        <p class="fl-vertical-subtitle"><?= htmlspecialchars($t['regions']['hub_sub']) ?></p>
        <p class="fl-detail-desc fl-vertical-intro"><?= htmlspecialchars($t['regions']['hub_intro']) ?></p>
        <div class="fl-vertical-cta-row">
            <a href="<?= fl_url('become-freelancer.php') ?>" class="fl-btn-primary"><i class="fas fa-user-plus"></i> <?= htmlspecialchars($t['nav']['become'] ?? $t['nav']['register']) ?></a>
            <a href="<?= fl_url('freelancers.php') ?>" class="fl-btn-outline-dark"><i class="fas fa-users"></i> <?= htmlspecialchars($t['nav']['freelancers']) ?></a>
            <a href="<?= fl_url('contact.php') ?>" class="fl-btn-outline-dark"><i class="fas fa-comments"></i> <?= htmlspecialchars(cms_contact_texts('freelance', $lang)['nav_discuss']) ?></a>
        </div>
    </section>

    <section class="fl-vertical-section">
        <h2 class="fl-section-title"><?= htmlspecialchars($t['regions']['norway_cities']) ?></h2>
        <div class="fl-solutions-grid">
            <?php foreach ($norway as $s => $item):
                $lv = fl_region_lang($item, $lang);
            ?>
            <a href="<?= htmlspecialchars(fl_region_url($s)) ?>" class="fl-vertical-link-card">
                <i class="fas fa-<?= htmlspecialchars(fl_region_icon($item['icon'] ?? 'city')) ?>"></i>
                <strong><?= htmlspecialchars($item['names'][$lang] ?? $s) ?></strong>
                <span><?= htmlspecialchars($lv['subtitle'] ?? '') ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="fl-vertical-section">
        <h2 class="fl-section-title"><?= htmlspecialchars($t['regions']['europe_ukraine']) ?></h2>
        <div class="fl-solutions-grid">
            <?php foreach ($intl as $s => $item):
                $lv = fl_region_lang($item, $lang);
            ?>
            <a href="<?= htmlspecialchars(fl_region_url($s)) ?>" class="fl-vertical-link-card">
                <i class="fas fa-<?= htmlspecialchars(fl_region_icon($item['icon'] ?? 'globe')) ?>"></i>
                <strong><?= htmlspecialchars($item['names'][$lang] ?? $s) ?></strong>
                <span><?= htmlspecialchars($lv['subtitle'] ?? '') ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>