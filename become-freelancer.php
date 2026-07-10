<?php
require_once __DIR__ . '/init.php';
require_once dirname(__DIR__) . '/includes/cms-contact.php';

$current_page = 'become';
$bf = $t['become_freelancer'];
$page_title   = $bf['meta_title'];
$page_desc    = $bf['meta_desc'];
$canonical    = $site_url . '/become-freelancer.php';
$canon_abs    = fl_absolute_url($canonical);
$body_class   = 'fl-become-page';

$seo_schemas = [
    fl_seo_organization(),
    fl_seo_webpage($canon_abs, $page_title, $page_desc),
    fl_seo_breadcrumbs([
        ['name' => $t['breadcrumb_home'], 'url' => fl_absolute_url(fl_url('index.php'))],
        ['name' => $bf['nav'], 'url' => $canon_abs],
    ]),
    fl_seo_software_app($canon_abs, $page_desc),
];
if (!empty($bf['faq'])) {
    $faq_items = [];
    foreach ($bf['faq'] as $item) {
        $faq_items[] = [
            '@type' => 'Question',
            'name' => $item['q'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['a']],
        ];
    }
    $seo_schemas[] = ['@type' => 'FAQPage', 'mainEntity' => $faq_items];
}

require __DIR__ . '/includes/header.php';
?>

<section class="fl-become-hero">
    <div class="fl-become-hero-bg"></div>
    <div class="fl-become-hero-inner">
        <span class="fl-demo-badge"><i class="fas fa-user-plus"></i> <?= htmlspecialchars($bf['badge']) ?></span>
        <h1><?= htmlspecialchars($bf['hero_title']) ?></h1>
        <p><?= htmlspecialchars($bf['hero_sub']) ?></p>
        <div class="fl-become-cta-row">
            <a href="<?= fl_url('register.php') ?>" class="fl-btn-primary"><i class="fas fa-rocket"></i> <?= htmlspecialchars($bf['cta_register']) ?></a>
            <a href="<?= fl_url('freelancers.php') ?>" class="fl-btn-outline-dark"><i class="fas fa-users"></i> <?= htmlspecialchars($bf['cta_browse']) ?></a>
            <a href="<?= fl_url('login.php') ?>" class="fl-btn-outline-dark"><i class="fas fa-sign-in-alt"></i> <?= htmlspecialchars($t['nav']['signin']) ?></a>
        </div>
        <div class="fl-become-stats">
            <?php foreach ($bf['stats'] as $stat): ?>
            <div class="fl-become-stat">
                <strong><?= htmlspecialchars($stat['value']) ?></strong>
                <span><?= htmlspecialchars($stat['label']) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="fl-become-section fl-container">
    <h2><?= htmlspecialchars($bf['steps_title']) ?></h2>
    <p class="fl-section-sub"><?= htmlspecialchars($bf['steps_sub']) ?></p>
    <div class="fl-steps-grid">
        <?php foreach ($bf['steps'] as $i => $step): ?>
        <article class="fl-step-card">
            <span class="fl-step-num"><?= $i + 1 ?></span>
            <i class="fas fa-<?= htmlspecialchars($step['icon']) ?> fl-step-icon"></i>
            <h3><?= htmlspecialchars($step['title']) ?></h3>
            <p><?= htmlspecialchars($step['desc']) ?></p>
        </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="fl-become-section fl-container" style="background:var(--fl-navy-mid);">
    <h2><?= htmlspecialchars($bf['tiers_title']) ?></h2>
    <p class="fl-section-sub"><?= htmlspecialchars($bf['tiers_sub']) ?></p>
    <div class="fl-tier-grid">
        <article class="fl-tier-card">
            <h3><i class="fas fa-user"></i> <?= htmlspecialchars($t['tiers']['simple']) ?></h3>
            <p class="fl-tier-price"><?= htmlspecialchars($bf['simple_price']) ?></p>
            <p style="font-size:13px;color:var(--fl-text-muted);margin:0;"><?= htmlspecialchars($t['tiers']['simple_desc']) ?></p>
            <ul>
                <?php foreach ($bf['simple_features'] as $f): ?>
                <li><i class="fas fa-check"></i> <?= htmlspecialchars($f) ?></li>
                <?php endforeach; ?>
            </ul>
            <a href="<?= fl_url('register.php?tier=simple') ?>" class="fl-btn-outline-dark fl-btn-block"><?= htmlspecialchars($bf['cta_simple']) ?></a>
        </article>
        <article class="fl-tier-card featured">
            <h3><i class="fas fa-star"></i> <?= htmlspecialchars($t['tiers']['pro']) ?></h3>
            <p class="fl-tier-price"><?= htmlspecialchars($bf['pro_price']) ?></p>
            <p style="font-size:13px;color:var(--fl-text-muted);margin:0;"><?= htmlspecialchars($t['tiers']['pro_desc']) ?></p>
            <ul>
                <?php foreach ($bf['pro_features'] as $f): ?>
                <li><i class="fas fa-check"></i> <?= htmlspecialchars($f) ?></li>
                <?php endforeach; ?>
            </ul>
            <a href="<?= fl_url('register.php?tier=pro') ?>" class="fl-btn-primary fl-btn-block"><?= htmlspecialchars($bf['cta_pro']) ?></a>
        </article>
    </div>
</section>

<section class="fl-become-section fl-container">
    <h2><?= htmlspecialchars($bf['screens_title']) ?></h2>
    <p class="fl-section-sub"><?= htmlspecialchars($bf['screens_sub']) ?></p>
    <div class="fl-become-screens">
        <img src="<?= htmlspecialchars(fl_url('screen/home.svg')) ?>" alt="<?= htmlspecialchars($bf['screen_home_alt']) ?>" width="600" height="360" loading="lazy">
        <img src="<?= htmlspecialchars(fl_url('screen/admin-dashboard.svg')) ?>" alt="<?= htmlspecialchars($bf['screen_admin_alt']) ?>" width="600" height="360" loading="lazy">
    </div>
</section>

<section class="fl-become-section fl-container">
    <h2><?= htmlspecialchars($bf['regions_title']) ?></h2>
    <p class="fl-section-sub"><?= htmlspecialchars($bf['regions_sub']) ?></p>
    <div class="fl-vertical-links">
        <?php
        require_once __DIR__ . '/includes/region-lib.php';
        foreach (fl_regions_all() as $s => $item):
        ?>
        <a href="<?= htmlspecialchars(fl_region_url($s)) ?>" class="fl-vertical-link-card">
            <i class="fas fa-<?= htmlspecialchars(fl_region_icon($item['icon'] ?? 'city')) ?>"></i>
            <span><?= htmlspecialchars($item['names'][$lang] ?? $s) ?></span>
        </a>
        <?php endforeach; ?>
    </div>
    <p style="text-align:center;margin-top:20px;">
        <a href="<?= fl_url('regions.php') ?>" class="fl-btn-outline-dark"><i class="fas fa-map"></i> <?= htmlspecialchars($t['regions']['all_regions']) ?></a>
    </p>
</section>

<?php if (!empty($bf['faq'])): ?>
<section class="fl-become-section fl-container fl-faq-section">
    <h2><?= htmlspecialchars($t['faq']['title']) ?></h2>
    <div class="fl-faq-list">
        <?php foreach ($bf['faq'] as $i => $item): ?>
        <details class="fl-faq-item"<?= $i === 0 ? ' open' : '' ?>>
            <summary><?= htmlspecialchars($item['q']) ?></summary>
            <p><?= htmlspecialchars($item['a']) ?></p>
        </details>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<section class="fl-vertical-cta-band fl-container" style="margin-bottom:48px;">
    <h2 class="fl-section-title"><?= htmlspecialchars($bf['final_cta']) ?></h2>
    <a href="<?= fl_url('register.php') ?>" class="fl-btn-primary fl-btn-block"><i class="fas fa-user-plus"></i> <?= htmlspecialchars($bf['cta_register']) ?></a>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>