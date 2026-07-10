<?php require_once dirname(__DIR__, 3) . '/includes/cms-contact.php'; ?>
<footer class="fls-footer">
    <div class="fls-footer-inner">
        <div class="fls-footer-grid">
            <div class="fls-footer-brand">
                <a href="<?= fls_url('index.php') ?>" class="fls-logo">
                    <span class="fls-logo-icon">F</span>
                    <span class="fls-logo-text">Freelance <em>CMS</em></span>
                </a>
                <p><?= htmlspecialchars($t['intro']['text']) ?></p>
            </div>
            <div>
                <h4><?= htmlspecialchars($t['footer']['product']) ?></h4>
                <ul>
                    <li><a href="<?= fls_url('index.php#features') ?>"><?= htmlspecialchars($t['nav']['features']) ?></a></li>
                    <li><a href="<?= fls_url('index.php#screens') ?>"><?= htmlspecialchars($t['nav']['screens']) ?></a></li>
                    <li><a href="<?= fls_url('index.php#pages') ?>"><?= htmlspecialchars($t['nav']['pages']) ?></a></li>
                    <li><a href="<?= fls_url('index.php#tech') ?>"><?= htmlspecialchars($t['nav']['tech']) ?></a></li>
                    <li><a href="<?= fls_url('index.php#demo') ?>"><?= htmlspecialchars($t['nav']['demo']) ?></a></li>
                </ul>
            </div>
            <div>
                <h4><?= htmlspecialchars($t['footer']['links']) ?></h4>
                <ul>
                    <li><a href="<?= fls_demo_url() ?>" rel="related"><?= htmlspecialchars($t['footer']['demo_link']) ?></a></li>
                    <li><a href="<?= fls_demo_url('admin/login.php') ?>"><?= htmlspecialchars($t['footer']['admin_demo']) ?></a></li>
                    <li><a href="https://bilohash.com/news/freelance-cms.html" rel="related"><?= htmlspecialchars($t['footer']['news']) ?></a></li>
                    <li><a href="<?= fls_url('contact.php') ?>"><?= htmlspecialchars(cms_contact_texts('freelance', $lang)['nav_discuss']) ?></a></li>
                    <li><a href="<?= fls_demo_url('sitemap.php') ?>"><?= htmlspecialchars($t['footer']['sitemap']) ?></a></li>
                    <li><a href="<?= fls_demo_url('llms.txt') ?>"><?= htmlspecialchars($t['footer']['llms']) ?></a></li>
                </ul>
            </div>
            <?php require dirname(__DIR__, 3) . '/includes/ecosystem-footer-column.php'; ?>
            <div>
                <h4><?= htmlspecialchars($t['footer']['related']) ?></h4>
                <ul>
                    <?php foreach ($t['related']['items'] as $rel): ?>
                    <li><a href="<?= htmlspecialchars($rel['url']) ?>" rel="related"><?= htmlspecialchars($rel['name']) ?></a></li>
                    <?php endforeach; ?>
                    <li><a href="https://bilohash.com/" rel="author"><?= htmlspecialchars($t['footer']['portfolio']) ?></a></li>
                    <li><a href="https://bilohash.com/"><?= htmlspecialchars($t['footer']['order']) ?></a></li>
                </ul>
            </div>
        </div>
        <?php require dirname(__DIR__, 3) . '/includes/ecosystem-footer-pills.php'; ?>
        <div class="fls-footer-bottom">
            <?= sprintf(htmlspecialchars($t['footer']['copyright']), date('Y')) ?>
        </div>
    </div>
</footer>
<script src="<?= htmlspecialchars(fls_asset('js/site.js')) ?>?v=2" defer></script>
</body>
</html>