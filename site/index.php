<?php
require_once __DIR__ . '/init.php';
require_once dirname(__DIR__, 2) . '/includes/cms-contact.php';
$canonical = $site_url . '/';
require __DIR__ . '/includes/header.php';
?>

<section class="fls-hero">
    <div class="fls-hero-bg"></div>
    <div class="fls-container fls-hero-inner">
        <div class="fls-hero-content">
            <span class="fls-badge"><?= htmlspecialchars($t['hero']['badge']) ?></span>
            <h1><?= htmlspecialchars($t['hero']['title']) ?></h1>
            <p class="fls-hero-sub"><?= htmlspecialchars($t['hero']['subtitle']) ?></p>
            <div class="fls-hero-cta">
                <a href="<?= fls_demo_url() ?>" class="fls-btn-primary fls-btn-lg"><i class="fas fa-briefcase"></i> <?= htmlspecialchars($t['hero']['cta_demo']) ?></a>
                <a href="<?= fls_demo_url('admin/login.php') ?>" class="fls-btn-outline fls-btn-lg"><i class="fas fa-user-shield"></i> <?= htmlspecialchars($t['hero']['cta_admin']) ?></a>
                <a href="<?= fls_url('contact.php') ?>" class="fls-btn-ghost fls-btn-lg"><i class="fas fa-comments"></i> <?= htmlspecialchars(cms_contact_texts('freelance', $lang)['nav_discuss']) ?></a>
            </div>
            <div class="fls-stats">
                <?php foreach ($t['stats'] as $stat): ?>
                <div class="fls-stat">
                    <strong><?= htmlspecialchars($stat['value']) ?></strong>
                    <span><?= htmlspecialchars($stat['label']) ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="fls-hero-preview">
            <img src="<?= fls_screen('home.svg') ?>" alt="Freelance CMS — <?= htmlspecialchars($t['screens']['items']['home']['title']) ?>" width="1200" height="700" loading="eager">
        </div>
    </div>
</section>

<section class="fls-section fls-intro">
    <div class="fls-container">
        <h2 class="fls-section-title"><?= htmlspecialchars($t['intro']['title']) ?></h2>
        <p class="fls-lead"><?= htmlspecialchars($t['intro']['text']) ?></p>
        <p class="fls-use-label"><?= htmlspecialchars($t['intro']['use_label']) ?></p>
        <div class="fls-usecases">
            <?php foreach ($t['intro']['use_cases'] as $case): ?>
            <span class="fls-usecase"><?= htmlspecialchars($case) ?></span>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="fls-section" id="features">
    <div class="fls-container">
        <h2 class="fls-section-title"><?= htmlspecialchars($t['features']['title']) ?></h2>
        <div class="fls-features-grid">
            <?php foreach ($t['features']['items'] as $f): ?>
            <article class="fls-feature-card">
                <div class="fls-feature-icon"><i class="fas fa-<?= htmlspecialchars($f['icon']) ?>"></i></div>
                <h3><?= htmlspecialchars($f['title']) ?></h3>
                <p><?= htmlspecialchars($f['desc']) ?></p>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="fls-section fls-screens-section" id="screens">
    <div class="fls-container">
        <h2 class="fls-section-title"><?= htmlspecialchars($t['screens']['title']) ?></h2>
        <p class="fls-section-sub"><?= htmlspecialchars($t['screens']['subtitle']) ?></p>
        <div class="fls-screens-grid">
            <?php foreach (fls_screens() as $scr):
                $info = $t['screens']['items'][$scr['key']];
            ?>
            <figure class="fls-screen-card">
                <a href="<?= fls_screen($scr['file']) ?>" target="_blank" rel="noopener">
                    <img src="<?= fls_screen($scr['file']) ?>" alt="Freelance CMS — <?= htmlspecialchars($info['title']) ?>" width="1200" height="700" loading="lazy">
                </a>
                <figcaption>
                    <strong><?= htmlspecialchars($info['title']) ?></strong>
                    <span><?= htmlspecialchars($info['desc']) ?></span>
                </figcaption>
            </figure>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="fls-section fls-pages-section" id="pages">
    <div class="fls-container">
        <h2 class="fls-section-title"><?= htmlspecialchars($t['pages']['title']) ?></h2>
        <p class="fls-section-sub"><?= htmlspecialchars($t['pages']['subtitle']) ?></p>
        <div class="fls-pages-grid">
            <?php foreach ($t['pages']['items'] as $page): ?>
            <a href="<?= htmlspecialchars(fls_demo_url($page['path'])) ?>" class="fls-page-card" target="_blank" rel="noopener">
                <span class="fls-page-icon"><i class="fas fa-<?= htmlspecialchars($page['icon']) ?>"></i></span>
                <span class="fls-page-body">
                    <strong><?= htmlspecialchars($page['title']) ?></strong>
                    <span><?= htmlspecialchars($page['desc']) ?></span>
                </span>
                <span class="fls-page-arrow"><i class="fas fa-arrow-right"></i></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="fls-section fls-tech-section" id="tech">
    <div class="fls-container">
        <div class="fls-tech-layout">
            <div>
                <h2 class="fls-section-title"><?= htmlspecialchars($t['tech']['title']) ?></h2>
                <ul class="fls-tech-list">
                    <?php foreach ($t['tech']['items'] as $item): ?>
                    <li><i class="fas fa-check-circle"></i> <?= htmlspecialchars($item) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="fls-tech-visual">
                <img src="<?= fls_screen('admin-dashboard.svg') ?>" alt="Freelance CMS Admin" width="1200" height="700" loading="lazy">
            </div>
        </div>
    </div>
</section>

<section class="fls-section fls-demo-section" id="demo">
    <div class="fls-container">
        <h2 class="fls-section-title"><?= htmlspecialchars($t['demo']['title']) ?></h2>
        <div class="fls-demo-grid">
            <article class="fls-demo-card">
                <div class="fls-demo-icon"><i class="fas fa-globe"></i></div>
                <h3><?= htmlspecialchars($t['demo']['frontend']) ?></h3>
                <p><?= htmlspecialchars($t['demo']['frontend_desc']) ?></p>
                <a href="<?= fls_demo_url() ?>" class="fls-btn-primary"><?= htmlspecialchars($t['demo']['open']) ?> →</a>
            </article>
            <article class="fls-demo-card">
                <div class="fls-demo-icon admin"><i class="fas fa-user-shield"></i></div>
                <h3><?= htmlspecialchars($t['demo']['admin']) ?></h3>
                <p><?= htmlspecialchars($t['demo']['admin_desc']) ?></p>
                <code class="fls-creds"><?= htmlspecialchars($t['demo']['creds']) ?></code>
                <code class="fls-creds fls-creds-user"><?= htmlspecialchars($t['demo']['user_creds']) ?></code>
                <a href="<?= fls_demo_url('admin/login.php') ?>" class="fls-btn-outline"><?= htmlspecialchars($t['demo']['open']) ?> →</a>
            </article>
        </div>
    </div>
</section>

<section class="fls-section fls-related-section" id="related">
    <div class="fls-container">
        <h2 class="fls-section-title"><?= htmlspecialchars($t['related']['title']) ?></h2>
        <p class="fls-section-sub"><?= htmlspecialchars($t['related']['subtitle']) ?></p>
        <div class="fls-related-grid">
            <?php foreach ($t['related']['items'] as $rel): ?>
            <article class="fls-related-card">
                <div class="fls-related-icon"><i class="fas fa-<?= htmlspecialchars($rel['icon']) ?>"></i></div>
                <h3><?= htmlspecialchars($rel['name']) ?></h3>
                <p><?= htmlspecialchars($rel['desc']) ?></p>
                <div class="fls-related-links">
                    <a href="<?= htmlspecialchars($rel['url']) ?>" class="fls-btn-outline fls-btn-sm" rel="related"><?= htmlspecialchars($t['related']['product_btn']) ?> →</a>
                    <a href="<?= htmlspecialchars($rel['demo']) ?>" class="fls-btn-ghost fls-btn-sm" rel="related"><?= htmlspecialchars($t['related']['demo_btn']) ?> →</a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="fls-cta-band">
    <div class="fls-container">
        <h2><?= htmlspecialchars($t['cta']['title']) ?></h2>
        <p><?= htmlspecialchars($t['cta']['text']) ?></p>
        <a href="<?= fls_url('contact.php') ?>" class="fls-btn-primary fls-btn-lg"><?= htmlspecialchars($t['cta']['btn']) ?></a>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>