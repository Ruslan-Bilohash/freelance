<?php
if (!function_exists('cms_contact_texts')) {
    $cms_contact_path = dirname(__DIR__, 2) . '/includes/cms-contact.php';
    if (is_file($cms_contact_path)) {
        require_once $cms_contact_path;
    }
}

$ft = $t['footer'] ?? [];
$regions_no = [];
$regions_intl = [];
$solution_slugs = [];

if (is_file(__DIR__ . '/region-lib.php')) {
    require_once __DIR__ . '/region-lib.php';
    if (function_exists('fl_regions_all')) {
        try {
            $regions_all = fl_regions_all();
            $regions_no = array_filter($regions_all, function ($r) {
                return ($r['country'] ?? '') === 'no';
            });
            $regions_intl = array_filter($regions_all, function ($r) {
                return in_array($r['country'] ?? '', ['eu', 'ua'], true);
            });
        } catch (Throwable $e) {
            $regions_no = [];
            $regions_intl = [];
        }
    }
}

if (is_file(__DIR__ . '/solution-lib.php')) {
    require_once __DIR__ . '/solution-lib.php';
    if (function_exists('fl_solution_slugs')) {
        try {
            $solution_slugs = fl_solution_slugs();
        } catch (Throwable $e) {
            $solution_slugs = [];
        }
    }
}

$fl_discuss = function_exists('cms_contact_texts')
    ? (cms_contact_texts('freelance', $lang)['nav_discuss'] ?? 'Contact')
    : 'Contact';
$fl_copyright = sprintf($ft['copyright'] ?? '© %s Freelance CMS Demo.', date('Y'));

if (empty($fl_skip_ecosystem) && is_file(__DIR__ . '/ecosystem-strip.php')) {
    require __DIR__ . '/ecosystem-strip.php';
}
?>
<footer class="fl-footer" itemscope itemtype="https://schema.org/WPFooter">
    <div class="fl-footer-cta">
        <div class="fl-footer-cta-inner">
            <div class="fl-footer-cta-text">
                <strong><?= htmlspecialchars($ft['cta_title'] ?? 'Order a custom freelance marketplace') ?></strong>
                <span><?= htmlspecialchars($ft['cta_sub'] ?? 'PHP · multilingual SEO · admin panel · Norway & Europe') ?></span>
            </div>
            <div class="fl-footer-cta-actions">
                <a href="<?= fl_url('contact.php') ?>" class="fl-btn-primary"><i class="fas fa-comments" aria-hidden="true"></i> <?= htmlspecialchars($fl_discuss) ?></a>
                <a href="<?= fl_url('solutions.php') ?>" class="fl-btn-outline-dark"><i class="fas fa-layer-group" aria-hidden="true"></i> <?= htmlspecialchars($t['solutions']['hub_title'] ?? ($ft['use_cases'] ?? 'Use cases')) ?></a>
            </div>
        </div>
    </div>

    <div class="fl-footer-inner">
        <div class="fl-footer-grid">
            <div class="fl-footer-brand">
                <a href="<?= fl_url('index.php') ?>" class="fl-footer-logo">
                    <span class="fl-logo-icon"><i class="fas fa-briefcase" aria-hidden="true"></i></span>
                    <span><?= htmlspecialchars($t['meta']['site_name'] ?? 'Freelance CMS') ?></span>
                </a>
                <p class="fl-footer-text"><?= htmlspecialchars($ft['demo'] ?? '') ?></p>
                <div class="fl-footer-trust">
                    <span class="fl-footer-badge"><i class="fas fa-flask" aria-hidden="true"></i> <?= htmlspecialchars($ft['trust_demo'] ?? 'Demo only') ?></span>
                    <span class="fl-footer-badge"><i class="fas fa-mobile-alt" aria-hidden="true"></i> <?= htmlspecialchars($ft['trust_responsive'] ?? 'Mobile · tablet · desktop') ?></span>
                    <span class="fl-footer-badge"><i class="fas fa-search" aria-hidden="true"></i> <?= htmlspecialchars($ft['trust_seo'] ?? 'Schema.org SEO') ?></span>
                    <span class="fl-footer-badge"><i class="fas fa-language" aria-hidden="true"></i> <?= htmlspecialchars($ft['trust_langs'] ?? '4 languages') ?></span>
                </div>
            </div>

            <?php if (!empty($solution_slugs)): ?>
            <div>
                <h4><?= htmlspecialchars($ft['use_cases'] ?? ($t['solutions']['hub_title'] ?? 'Use cases')) ?></h4>
                <ul>
                    <?php foreach ($solution_slugs as $sslug): ?>
                    <li><a href="<?= htmlspecialchars(fl_solution_url($sslug)) ?>"><?= htmlspecialchars(fl_solution_name($sslug, $lang)) ?></a></li>
                    <?php endforeach; ?>
                    <li><a href="<?= fl_url('solutions.php') ?>"><strong><?= htmlspecialchars($ft['all_solutions'] ?? 'All use cases') ?></strong></a></li>
                </ul>
            </div>
            <?php endif; ?>

            <div>
                <h4><?= htmlspecialchars($ft['marketplace'] ?? 'Marketplace') ?></h4>
                <ul>
                    <li><a href="<?= fl_url('search.php') ?>"><?= htmlspecialchars($ft['projects'] ?? $t['nav']['browse'] ?? 'Projects') ?></a></li>
                    <li><a href="<?= fl_url('freelancers.php') ?>"><?= htmlspecialchars($ft['freelancers'] ?? $t['nav']['freelancers'] ?? 'Freelancers') ?></a></li>
                    <li><a href="<?= fl_url('become-freelancer.php') ?>"><?= htmlspecialchars($ft['become'] ?? $t['nav']['register'] ?? 'Register') ?></a></li>
                    <li><a href="<?= fl_url('contact.php') ?>"><?= htmlspecialchars($fl_discuss) ?></a></li>
                    <li><a href="https://bilohash.com/freelance/site/" rel="related"><?= htmlspecialchars($ft['product_page'] ?? 'Product page') ?></a></li>
                </ul>
            </div>

            <div>
                <h4><?= htmlspecialchars($ft['cities'] ?? 'Norwegian cities') ?></h4>
                <ul>
                    <?php foreach ($regions_no as $slug => $item): ?>
                    <li><a href="<?= htmlspecialchars(fl_region_url($slug)) ?>"><?= htmlspecialchars($item['names'][$lang] ?? $slug) ?></a></li>
                    <?php endforeach; ?>
                    <li><a href="<?= fl_url('regions.php') ?>"><strong><?= htmlspecialchars($ft['all_regions'] ?? 'All regions') ?></strong></a></li>
                </ul>
            </div>

            <div>
                <h4><?= htmlspecialchars($ft['ecosystem'] ?? 'Bilohash ecosystem') ?></h4>
                <ul>
                    <?php if (!empty($t['ecosystem']['items'])): ?>
                        <?php foreach ($t['ecosystem']['items'] as $eco): ?>
                    <li><a href="<?= htmlspecialchars($eco['url']) ?>" rel="related"><?= htmlspecialchars($eco['name']) ?></a></li>
                        <?php endforeach; ?>
                    <?php else: ?>
                    <li><a href="https://bilohash.com/booking/site/" rel="related"><?= htmlspecialchars($ft['booking'] ?? 'Booking CMS') ?></a></li>
                    <li><a href="https://bilohash.com/auction/site/" rel="related"><?= htmlspecialchars($ft['auction'] ?? 'Auction CMS') ?></a></li>
                    <li><a href="https://bilohash.com/ai/" rel="related"><?= htmlspecialchars($ft['ai'] ?? 'AI Platform') ?></a></li>
                    <?php endif; ?>
                    <li><a href="https://bilohash.com/" rel="author"><?= htmlspecialchars($ft['portfolio'] ?? 'Portfolio') ?></a></li>
                    <li><a href="https://bilohash.com/news/freelance-cms.html"><?= htmlspecialchars($ft['news'] ?? 'News') ?></a></li>
                </ul>
            </div>

            <div>
                <h4><?= htmlspecialchars($ft['legal'] ?? 'Legal') ?></h4>
                <ul>
                    <li><a href="<?= fl_url('index.php') ?>"><?= htmlspecialchars($t['breadcrumb_home'] ?? 'Home') ?></a></li>
                    <li><a href="https://bilohash.com/website/privacy-policy.php"><?= htmlspecialchars($ft['privacy'] ?? 'Privacy') ?></a></li>
                    <li><a href="https://bilohash.com/website/cookies.php"><?= htmlspecialchars($ft['terms'] ?? 'Terms') ?></a></li>
                    <li><a href="<?= fl_url('sitemap.php') ?>"><?= htmlspecialchars($ft['sitemap'] ?? 'Sitemap') ?></a></li>
                    <li><a href="<?= fl_url('llms.txt') ?>"><?= htmlspecialchars($ft['llms'] ?? 'llms.txt') ?></a></li>
                    <li><a href="<?= fl_url('admin/login.php') ?>"><?= htmlspecialchars($ft['admin_demo'] ?? 'Admin demo') ?></a></li>
                </ul>
            </div>
        </div>

        <?php if (!empty($t['ecosystem']['items'])): ?>
        <div class="fl-footer-ecosystem">
            <span class="fl-footer-eco-label"><?= htmlspecialchars($ft['related_products'] ?? 'Related products') ?>:</span>
            <?php foreach ($t['ecosystem']['items'] as $eco): ?>
            <a href="<?= htmlspecialchars($eco['demo']) ?>" rel="related" title="<?= htmlspecialchars($eco['name']) ?>"><?= htmlspecialchars($eco['short'] ?? $eco['name']) ?></a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="fl-footer-bottom">
            <div class="fl-footer-bottom-links">
                <a href="<?= fl_url('solutions.php') ?>"><?= htmlspecialchars($t['solutions']['hub_title'] ?? 'Use cases') ?></a>
                <a href="<?= fl_url('regions.php') ?>"><?= htmlspecialchars($t['regions']['hub_title'] ?? 'Regions') ?></a>
                <a href="<?= fl_url('become-freelancer.php') ?>"><?= htmlspecialchars($ft['become'] ?? 'Become a freelancer') ?></a>
                <a href="<?= fl_url('contact.php') ?>"><?= htmlspecialchars($fl_discuss) ?></a>
            </div>
            <?= htmlspecialchars($fl_copyright) ?>
        </div>
    </div>
</footer>
<script src="<?= htmlspecialchars(fl_asset('js/main.js')) ?>?v=5" defer></script>
<?php
if (is_file(__DIR__ . '/integrations.php')) {
    require_once __DIR__ . '/integrations.php';
    if (function_exists('fl_render_chat_widget')) {
        fl_render_chat_widget();
    }
}
?>
</body>
</html>