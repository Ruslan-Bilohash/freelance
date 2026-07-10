<?php
require_once dirname(__DIR__, 2) . '/includes/cms-contact.php';
/** @var string $slug @var array $solution @var array $v @var string $canonical */
$current_page = 'solution';
$body_class = 'fl-solution-page';
$hub_label = $t['solutions']['hub_title'] ?? 'Use cases';
$all = fl_solutions_all();
$demo_url = fl_solution_demo_url($solution);
require __DIR__ . '/header.php';
?>

<main class="fl-container fl-vertical-main" itemscope itemtype="https://schema.org/WebPage">
    <meta itemprop="url" content="<?= htmlspecialchars(fl_absolute_url($canonical)) ?>">
    <meta itemprop="name" content="<?= htmlspecialchars($v['h1']) ?>">
    <meta itemprop="description" content="<?= htmlspecialchars($v['intro']) ?>">

    <nav class="fl-breadcrumb fl-vertical-crumb" aria-label="Breadcrumb">
        <a href="<?= fl_url('index.php') ?>"><?= htmlspecialchars($t['breadcrumb_home']) ?></a>
        → <a href="<?= fl_url('solutions.php') ?>"><?= htmlspecialchars($hub_label) ?></a>
        → <?= htmlspecialchars($v['h1']) ?>
    </nav>

    <section class="fl-vertical-hero">
        <div class="fl-vertical-hero-icon">
            <?php if (($solution['icon'] ?? '') === 'wordpress'): ?>
            <i class="fab fa-wordpress" aria-hidden="true"></i>
            <?php else: ?>
            <i class="fas fa-<?= htmlspecialchars(fl_solution_icon($solution['icon'] ?? 'briefcase')) ?>" aria-hidden="true"></i>
            <?php endif; ?>
        </div>
        <h1 itemprop="headline"><?= htmlspecialchars($v['h1']) ?></h1>
        <p class="fl-vertical-subtitle"><?= htmlspecialchars($v['subtitle']) ?></p>
        <p class="fl-detail-desc fl-vertical-intro" itemprop="about"><?= htmlspecialchars($v['intro']) ?></p>
        <div class="fl-vertical-cta-row">
            <a href="<?= htmlspecialchars($demo_url) ?>" class="fl-btn-primary"><i class="fas fa-play-circle" aria-hidden="true"></i> <?= htmlspecialchars($t['about_script']['demo_btn'] ?? 'Live demo') ?></a>
            <a href="<?= fl_url('contact.php') ?>" class="fl-btn-outline-dark"><i class="fas fa-comments" aria-hidden="true"></i> <?= htmlspecialchars(cms_contact_texts('freelance', $lang)['nav_discuss']) ?></a>
            <a href="https://bilohash.com/freelance/site/" class="fl-btn-outline-dark"><i class="fas fa-book" aria-hidden="true"></i> <?= htmlspecialchars($t['about_script']['product_btn'] ?? 'Product page') ?></a>
        </div>
    </section>

    <section class="fl-vertical-section">
        <h2 class="fl-section-title"><?= htmlspecialchars($t['solutions']['benefits_title'] ?? 'Why Freelance CMS') ?></h2>
        <div class="fl-vertical-benefits">
            <?php foreach ($v['benefits'] ?? [] as $b): ?>
            <article class="fl-step-card" itemprop="mainEntity" itemscope itemtype="https://schema.org/Thing">
                <h3 itemprop="name"><?= htmlspecialchars($b['title']) ?></h3>
                <p itemprop="description"><?= htmlspecialchars($b['text']) ?></p>
            </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="fl-vertical-section">
        <h2 class="fl-section-title"><?= htmlspecialchars($t['solutions']['features_title'] ?? 'Platform features') ?></h2>
        <ul class="fl-highlight-list">
            <?php foreach ($v['features'] ?? [] as $f): ?>
            <li><i class="fas fa-check" aria-hidden="true"></i> <?= htmlspecialchars($f) ?></li>
            <?php endforeach; ?>
        </ul>
    </section>

    <?php if (!empty($v['faq'])): ?>
    <section class="fl-vertical-section fl-faq-section" itemscope itemtype="https://schema.org/FAQPage">
        <h2 class="fl-section-title"><?= htmlspecialchars($t['faq']['title']) ?></h2>
        <div class="fl-faq-list">
            <?php foreach ($v['faq'] as $i => $item): ?>
            <details class="fl-faq-item"<?= $i === 0 ? ' open' : '' ?> itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                <summary itemprop="name"><?= htmlspecialchars($item['q']) ?></summary>
                <p itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"><span itemprop="text"><?= htmlspecialchars($item['a']) ?></span></p>
            </details>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <section class="fl-vertical-section fl-related">
        <h2 class="fl-section-title"><?= htmlspecialchars($t['solutions']['more_solutions'] ?? 'More use cases') ?></h2>
        <div class="fl-vertical-links">
            <?php foreach ($all as $s => $item):
                if ($s === $slug) continue;
                $lv = fl_solution_lang($item, $lang);
            ?>
            <a href="<?= htmlspecialchars(fl_solution_url($s)) ?>" class="fl-vertical-link-card">
                <?php if (($item['icon'] ?? '') === 'wordpress'): ?>
                <i class="fab fa-wordpress" aria-hidden="true"></i>
                <?php else: ?>
                <i class="fas fa-<?= htmlspecialchars(fl_solution_icon($item['icon'] ?? 'briefcase')) ?>" aria-hidden="true"></i>
                <?php endif; ?>
                <span><?= htmlspecialchars($lv['h1'] ?? fl_solution_name($s, $lang)) ?></span>
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