<?php
require_once dirname(__DIR__, 2) . '/includes/cms-contact.php';
/** @var string $slug @var array $region @var array $v @var string $canonical */
$current_page = 'region';
$body_class = 'fl-region-page';
$hub_label = $t['regions']['hub_title'] ?? 'Regions';
$all = fl_regions_all();
$demo_url = fl_url('search.php');
require __DIR__ . '/header.php';
?>

<main class="fl-container fl-vertical-main">
    <nav class="fl-breadcrumb fl-vertical-crumb">
        <a href="<?= fl_url('index.php') ?>"><?= htmlspecialchars($t['breadcrumb_home']) ?></a>
        → <a href="<?= fl_url('regions.php') ?>"><?= htmlspecialchars($hub_label) ?></a>
        → <?= htmlspecialchars($v['h1']) ?>
    </nav>

    <section class="fl-vertical-hero">
        <div class="fl-vertical-hero-icon"><i class="fas fa-<?= htmlspecialchars(fl_region_icon($region['icon'] ?? 'city')) ?>"></i></div>
        <h1><?= htmlspecialchars($v['h1']) ?></h1>
        <p class="fl-vertical-subtitle"><?= htmlspecialchars($v['subtitle']) ?></p>
        <p class="fl-detail-desc fl-vertical-intro"><?= htmlspecialchars($v['intro']) ?></p>
        <div class="fl-vertical-cta-row">
            <a href="<?= htmlspecialchars($demo_url) ?>" class="fl-btn-primary"><i class="fas fa-play-circle"></i> <?= htmlspecialchars($t['about_script']['demo_btn'] ?? 'Live demo') ?></a>
            <a href="<?= fl_url('become-freelancer.php') ?>" class="fl-btn-outline-dark"><i class="fas fa-user-plus"></i> <?= htmlspecialchars($t['nav']['become'] ?? $t['nav']['register']) ?></a>
            <a href="https://bilohash.com/freelance/site/" class="fl-btn-outline-dark"><i class="fas fa-book"></i> <?= htmlspecialchars($t['about_script']['product_btn'] ?? 'Product page') ?></a>
            <a href="<?= fl_url('contact.php') ?>" class="fl-btn-outline-dark"><i class="fas fa-comments"></i> <?= htmlspecialchars(cms_contact_texts('freelance', $lang)['nav_discuss']) ?></a>
        </div>
    </section>

    <section class="fl-vertical-section">
        <h2 class="fl-section-title"><?= htmlspecialchars($t['solutions']['benefits_title'] ?? 'Benefits') ?></h2>
        <div class="fl-vertical-benefits">
            <?php foreach ($v['benefits'] ?? [] as $b): ?>
            <article class="fl-step-card">
                <h3><?= htmlspecialchars($b['title']) ?></h3>
                <p><?= htmlspecialchars($b['text']) ?></p>
            </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="fl-vertical-section">
        <h2 class="fl-section-title"><?= htmlspecialchars($t['solutions']['features_title'] ?? 'Platform features') ?></h2>
        <ul class="fl-highlight-list">
            <?php foreach ($v['features'] ?? [] as $f): ?>
            <li><i class="fas fa-check"></i> <?= htmlspecialchars($f) ?></li>
            <?php endforeach; ?>
        </ul>
    </section>

    <?php if (!empty($v['faq'])): ?>
    <section class="fl-vertical-section fl-faq-section">
        <h2 class="fl-section-title"><?= htmlspecialchars($t['faq']['title']) ?></h2>
        <div class="fl-faq-list">
            <?php foreach ($v['faq'] as $i => $item): ?>
            <details class="fl-faq-item"<?= $i === 0 ? ' open' : '' ?>>
                <summary><?= htmlspecialchars($item['q']) ?></summary>
                <p><?= htmlspecialchars($item['a']) ?></p>
            </details>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <section class="fl-vertical-section fl-related">
        <h2 class="fl-section-title"><?= htmlspecialchars($t['regions']['more_regions']) ?></h2>
        <div class="fl-vertical-links">
            <?php foreach ($all as $s => $item):
                if ($s === $slug) continue;
                $lv = fl_region_lang($item, $lang);
            ?>
            <a href="<?= htmlspecialchars(fl_region_url($s)) ?>" class="fl-vertical-link-card">
                <i class="fas fa-<?= htmlspecialchars(fl_region_icon($item['icon'] ?? 'city')) ?>"></i>
                <span><?= htmlspecialchars($item['names'][$lang] ?? $s) ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="fl-vertical-cta-band">
        <h2 class="fl-section-title"><?= htmlspecialchars($v['cta'] ?? '') ?></h2>
        <a href="<?= fl_url('contact.php') ?>" class="fl-btn-primary fl-btn-block"><?= htmlspecialchars(cms_contact_texts('freelance', $lang)['nav_discuss']) ?></a>
    </section>
</main>

<?php require __DIR__ . '/footer.php'; ?>