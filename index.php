<?php
require_once __DIR__ . '/init.php';
require_once dirname(__DIR__) . '/includes/cms-contact.php';
$current_page = 'home';
$search_params = fl_search_params();
$featured      = fl_featured_projects(6);
$top_freelancers = fl_featured_freelancers(6);
$urgent        = fl_urgent_projects(4);
$cat_counts    = fl_category_counts();
$stats         = fl_platform_stats();
$canonical     = $site_url . '/';
$page_title    = $t['meta']['title'];
$page_desc     = $t['meta']['description'];
$canon_abs     = fl_absolute_url($canonical);
$seo_schemas   = [
    fl_seo_organization(),
    fl_seo_website($canon_abs),
    fl_seo_software_app($canon_abs, $page_desc),
    fl_seo_webpage($canon_abs, $page_title, $page_desc),
    fl_seo_professional_service(),
];
require __DIR__ . '/includes/header.php';
?>

<section class="fl-hero">
    <div class="fl-hero-bg"></div>
    <div class="fl-hero-inner">
        <span class="fl-demo-badge"><i class="fas fa-briefcase"></i> <?= htmlspecialchars($t['hero']['badge']) ?></span>
        <h1><?= htmlspecialchars($t['hero']['title']) ?></h1>
        <p><?= htmlspecialchars($t['hero']['subtitle']) ?></p>
        <?php require __DIR__ . '/includes/search-form.php'; ?>
    </div>
</section>

<section class="fl-about-script">
    <div class="fl-container fl-about-script-inner">
        <div class="fl-about-script-text">
            <h2><?= htmlspecialchars($t['about_script']['title']) ?></h2>
            <p><?= htmlspecialchars($t['about_script']['text']) ?></p>
            <?php
            $fl_seo_sell = trim(fl_load_settings()['seo_selling_text'] ?? '');
            if ($fl_seo_sell !== ''): ?>
            <p class="fl-seo-selling"><?= htmlspecialchars($fl_seo_sell) ?></p>
            <?php endif; ?>
            <?php
            require_once __DIR__ . '/includes/solution-lib.php';
            if (fl_solution_slugs()): ?>
            <p class="fl-about-use-label"><?= htmlspecialchars($t['about_script']['use_label'] ?? '') ?></p>
            <div class="fl-about-usecases fl-skills">
                <?php fl_render_solution_tags(); ?>
            </div>
            <p class="fl-about-use-more"><a href="<?= fl_url('solutions.php') ?>"><?= htmlspecialchars($t['solutions']['hub_title'] ?? 'All use cases') ?> →</a></p>
            <?php endif; ?>
            <ul class="fl-about-features">
                <li><i class="fas fa-check-circle"></i> <?= htmlspecialchars($t['about_script']['f1']) ?></li>
                <li><i class="fas fa-check-circle"></i> <?= htmlspecialchars($t['about_script']['f2']) ?></li>
                <li><i class="fas fa-check-circle"></i> <?= htmlspecialchars($t['about_script']['f3']) ?></li>
            </ul>
            <div class="fl-about-actions">
                <a href="<?= fl_url('index.php') ?>" class="fl-btn-primary"><i class="fas fa-play-circle"></i> <?= htmlspecialchars($t['about_script']['demo_btn']) ?></a>
                <a href="<?= fl_url('admin/login.php') ?>" class="fl-btn-outline-dark"><i class="fas fa-user-shield"></i> <?= htmlspecialchars($t['about_script']['admin_btn']) ?></a>
                <a href="https://bilohash.com/freelance/site/" class="fl-btn-outline-dark"><i class="fas fa-book"></i> <?= htmlspecialchars($t['about_script']['product_btn']) ?></a>
                <a href="https://bilohash.com/news/freelance-cms.html" class="fl-btn-outline-dark" target="_blank" rel="noopener noreferrer"><i class="fas fa-newspaper"></i> <?= htmlspecialchars($t['about_script']['news_btn'] ?? 'News') ?></a>
                <a href="<?= fl_url('contact.php') ?>" class="fl-btn-primary"><i class="fas fa-comments"></i> <?= htmlspecialchars(cms_contact_texts('freelance', $lang)['nav_discuss']) ?></a>
            </div>
            <p class="fl-about-creds"><i class="fas fa-key"></i> <?= htmlspecialchars($t['about_script']['creds']) ?></p>
        </div>
        <div class="fl-about-script-visual">
            <div class="fl-about-mock">
                <div class="fl-mock-header"><span></span><span></span><span></span></div>
                <div class="fl-mock-body">
                    <div class="fl-mock-project"></div>
                    <div class="fl-mock-project"></div>
                    <div class="fl-mock-project"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="fl-stats-strip">
    <div class="fl-container fl-stats-grid">
        <div class="fl-stat-item">
            <i class="fas fa-folder-open"></i>
            <strong><?= (int)$stats['projects'] ?></strong>
            <span><?= htmlspecialchars($t['home']['stats_projects']) ?></span>
        </div>
        <div class="fl-stat-item">
            <i class="fas fa-bolt"></i>
            <strong><?= (int)$stats['open'] ?></strong>
            <span><?= htmlspecialchars($t['home']['stats_open']) ?></span>
        </div>
        <div class="fl-stat-item">
            <i class="fas fa-paper-plane"></i>
            <strong><?= (int)$stats['proposals'] ?></strong>
            <span><?= htmlspecialchars($t['home']['stats_proposals']) ?></span>
        </div>
        <div class="fl-stat-item">
            <i class="fas fa-users"></i>
            <strong><?= (int)$stats['freelancers'] ?></strong>
            <span><?= htmlspecialchars($t['home']['stats_freelancers'] ?? 'Freelancers') ?></span>
        </div>
        <div class="fl-stat-item">
            <i class="fas fa-crown"></i>
            <strong><?= (int)$stats['freelancers_pro'] ?></strong>
            <span><?= htmlspecialchars($t['home']['stats_pro'] ?? 'Pro') ?></span>
        </div>
    </div>
</section>

<div class="fl-container">
    <div class="fl-section-head">
        <div>
            <h2 class="fl-section-title"><?= htmlspecialchars($t['home']['featured']) ?></h2>
            <p class="fl-section-sub"><?= htmlspecialchars($t['home']['featured_sub']) ?></p>
        </div>
        <a href="<?= fl_url('search.php?status=open') ?>" class="fl-link-more"><?= htmlspecialchars($t['home']['view_all']) ?> →</a>
    </div>
    <div class="fl-project-grid">
        <?php foreach ($featured as $project):
            require __DIR__ . '/includes/project-card.php';
        endforeach; ?>
    </div>
</div>

<div class="fl-container fl-section-tight">
    <div class="fl-section-head">
        <div>
            <h2 class="fl-section-title"><?= htmlspecialchars($t['home']['featured_freelancers'] ?? 'Top freelancers') ?></h2>
            <p class="fl-section-sub"><?= htmlspecialchars($t['home']['featured_freelancers_sub'] ?? '') ?></p>
        </div>
        <a href="<?= fl_url('freelancers.php') ?>" class="fl-link-more"><?= htmlspecialchars($t['home']['view_all']) ?> →</a>
    </div>
    <div class="fl-freelancer-grid">
        <?php foreach ($top_freelancers as $freelancer):
            require __DIR__ . '/includes/freelancer-card.php';
        endforeach; ?>
    </div>
</div>

<div class="fl-container fl-section-tight">
    <h2 class="fl-section-title"><?= htmlspecialchars($t['home']['categories']) ?></h2>
    <div class="fl-cat-grid">
        <?php foreach (fl_categories() as $cat):
            $icon = match ($cat) {
                'web' => 'code',
                'design' => 'palette',
                'ai' => 'robot',
                'wordpress' => 'wordpress',
                'mobile' => 'mobile-screen',
                'marketing' => 'bullhorn',
                default => 'tag',
            };
            $url = fl_url('search.php?category=' . urlencode($cat));
        ?>
        <a href="<?= htmlspecialchars($url) ?>" class="fl-cat-card">
            <i class="<?= $cat === 'wordpress' ? 'fab fa-wordpress' : 'fas fa-' . $icon ?>"></i>
            <strong><?= htmlspecialchars($t['categories'][$cat]) ?></strong>
            <span><?= (int)($cat_counts[$cat] ?? 0) ?> <?= htmlspecialchars($t['home']['projects']) ?></span>
        </a>
        <?php endforeach; ?>
    </div>
</div>

<div class="fl-container fl-section-tight">
    <div class="fl-section-head">
        <div>
            <h2 class="fl-section-title"><?= htmlspecialchars($t['home']['urgent']) ?></h2>
            <p class="fl-section-sub"><?= htmlspecialchars($t['home']['urgent_sub']) ?></p>
        </div>
        <a href="<?= fl_url('search.php?status=urgent') ?>" class="fl-link-more"><?= htmlspecialchars($t['home']['view_all']) ?> →</a>
    </div>
    <div class="fl-project-grid">
        <?php foreach ($urgent as $project):
            require __DIR__ . '/includes/project-card.php';
        endforeach; ?>
    </div>
</div>

<div class="fl-container fl-section-tight">
    <div class="fl-section-head fl-section-head-center">
        <div>
            <h2 class="fl-section-title"><?= htmlspecialchars($t['how_it_works']['title']) ?></h2>
            <p class="fl-section-sub"><?= htmlspecialchars($t['how_it_works']['subtitle']) ?></p>
        </div>
    </div>
    <div class="fl-steps-grid">
        <?php foreach ($t['how_it_works']['steps'] as $i => $step): ?>
        <article class="fl-step-card">
            <div class="fl-step-num"><?= $i + 1 ?></div>
            <div class="fl-step-icon"><i class="fas fa-<?= htmlspecialchars($step['icon']) ?>"></i></div>
            <h3><?= htmlspecialchars($step['title']) ?></h3>
            <p><?= htmlspecialchars($step['desc']) ?></p>
        </article>
        <?php endforeach; ?>
    </div>
</div>

<div class="fl-container fl-section-tight">
    <h2 class="fl-section-title"><?= htmlspecialchars($t['home']['why']) ?></h2>
    <div class="fl-why-grid">
        <div class="fl-why-item">
            <i class="fas fa-handshake"></i>
            <h4><?= htmlspecialchars($t['home']['why_1_t']) ?></h4>
            <p><?= htmlspecialchars($t['home']['why_1_d']) ?></p>
        </div>
        <div class="fl-why-item">
            <i class="fas fa-shield-halved"></i>
            <h4><?= htmlspecialchars($t['home']['why_2_t']) ?></h4>
            <p><?= htmlspecialchars($t['home']['why_2_d']) ?></p>
        </div>
        <div class="fl-why-item">
            <i class="fas fa-globe-europe"></i>
            <h4><?= htmlspecialchars($t['home']['why_3_t']) ?></h4>
            <p><?= htmlspecialchars($t['home']['why_3_d']) ?></p>
        </div>
    </div>
</div>

<?php require __DIR__ . '/includes/platform-features.php'; ?>

<div class="fl-container fl-section-tight fl-faq-section fl-section-last">
    <h2 class="fl-section-title"><?= htmlspecialchars($t['faq']['title']) ?></h2>
    <div class="fl-faq-list">
        <?php foreach ($t['faq']['items'] as $i => $item): ?>
        <details class="fl-faq-item"<?= $i === 0 ? ' open' : '' ?>>
            <summary><?= htmlspecialchars($item['q']) ?></summary>
            <p><?= htmlspecialchars($item['a']) ?></p>
        </details>
        <?php endforeach; ?>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>