<?php
require_once __DIR__ . '/init.php';
require_once dirname(__DIR__) . '/includes/cms-contact.php';
require_once __DIR__ . '/includes/solution-lib.php';

$current_page = 'solutions';
$page_title   = $t['solutions']['meta_title'] ?? 'Freelance CMS — Use cases';
$page_desc    = $t['solutions']['meta_desc'] ?? '';
$canonical    = $site_url . '/solutions.php';
$canon_abs    = fl_absolute_url($canonical);
$body_class   = 'fl-solutions-hub';

$seo_schemas = [
    fl_seo_organization(),
    fl_seo_webpage($canon_abs, $page_title, $page_desc),
    fl_seo_breadcrumbs([
        ['name' => $t['breadcrumb_home'], 'url' => fl_absolute_url(fl_url('index.php'))],
        ['name' => $t['solutions']['hub_title'] ?? 'Use cases', 'url' => $canon_abs],
    ]),
    fl_seo_item_list_solutions($lang, $canon_abs),
];

$all = fl_solutions_all();
require __DIR__ . '/includes/header.php';
?>

<main class="fl-container fl-solutions-hub">
    <nav class="fl-breadcrumb fl-vertical-crumb" aria-label="Breadcrumb">
        <a href="<?= fl_url('index.php') ?>"><?= htmlspecialchars($t['breadcrumb_home']) ?></a>
        → <?= htmlspecialchars($t['solutions']['hub_title']) ?>
    </nav>

    <section class="fl-vertical-hero">
        <div class="fl-vertical-hero-icon"><i class="fas fa-layer-group" aria-hidden="true"></i></div>
        <h1><?= htmlspecialchars($t['solutions']['hub_h1']) ?></h1>
        <p class="fl-vertical-subtitle"><?= htmlspecialchars($t['solutions']['hub_sub']) ?></p>
        <p class="fl-detail-desc fl-vertical-intro"><?= htmlspecialchars($t['solutions']['hub_intro']) ?></p>
        <div class="fl-skills fl-solutions-hub-tags">
            <?php fl_render_solution_tags(); ?>
        </div>
    </section>

    <section class="fl-vertical-section">
        <h2 class="fl-section-title"><?= htmlspecialchars($t['solutions']['grid_title']) ?></h2>
        <div class="fl-solutions-grid">
            <?php foreach ($all as $slug => $item):
                $lv = fl_solution_lang($item, $lang);
            ?>
            <a href="<?= htmlspecialchars(fl_solution_url($slug)) ?>" class="fl-vertical-link-card fl-solution-card">
                <?php if (($item['icon'] ?? '') === 'wordpress'): ?>
                <i class="fab fa-wordpress" aria-hidden="true"></i>
                <?php else: ?>
                <i class="fas fa-<?= htmlspecialchars(fl_solution_icon($item['icon'] ?? 'briefcase')) ?>" aria-hidden="true"></i>
                <?php endif; ?>
                <strong><?= htmlspecialchars(fl_solution_name($slug, $lang)) ?></strong>
                <span><?= htmlspecialchars(function_exists('mb_substr') ? mb_substr($lv['intro'] ?? '', 0, 120) : substr($lv['intro'] ?? '', 0, 120)) ?>…</span>
            </a>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="fl-vertical-cta-band">
        <h2 class="fl-section-title"><?= htmlspecialchars($t['solutions']['hub_cta']) ?></h2>
        <a href="<?= fl_url('contact.php') ?>" class="fl-btn-primary fl-btn-block"><i class="fas fa-comments" aria-hidden="true"></i> <?= htmlspecialchars(cms_contact_texts('freelance', $lang)['nav_discuss']) ?></a>
    </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>