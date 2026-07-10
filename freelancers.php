<?php
require_once __DIR__ . '/init.php';
$params = fl_freelancer_search_params();
$results = fl_filter_freelancers($params, $lang);
$current_page = 'freelancers';
$page_title = $t['freelancers_page']['title'];
$page_desc = $t['freelancers_page']['meta'];
$canonical = $site_url . '/freelancers.php';
require __DIR__ . '/includes/header.php';
?>

<section class="fl-hero fl-hero-compact">
    <div class="fl-hero-inner">
        <h1><?= htmlspecialchars($t['freelancers_page']['title']) ?></h1>
        <p><?= htmlspecialchars($t['freelancers_page']['subtitle']) ?></p>
    </div>
</section>

<div class="fl-container">
    <div class="fl-search-layout">
        <aside class="fl-filters">
            <h3><?= htmlspecialchars($t['search_page']['filter']) ?></h3>
            <form method="get">
                <div class="fl-filter-group">
                    <label><?= htmlspecialchars($t['search']['keyword']) ?></label>
                    <input type="text" name="q" value="<?= htmlspecialchars($params['q']) ?>">
                </div>
                <div class="fl-filter-group">
                    <label><?= htmlspecialchars($t['search_page']['category']) ?></label>
                    <select name="category">
                        <option value=""><?= htmlspecialchars($t['search']['all_cats']) ?></option>
                        <?php foreach (fl_categories() as $cat): ?>
                        <option value="<?= $cat ?>" <?= $params['category'] === $cat ? 'selected' : '' ?>><?= htmlspecialchars($t['categories'][$cat]) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="fl-filter-group">
                    <label><?= htmlspecialchars($t['auth']['tier_plan']) ?></label>
                    <select name="tier">
                        <option value=""><?= htmlspecialchars($t['search_page']['all']) ?></option>
                        <option value="pro" <?= $params['tier'] === 'pro' ? 'selected' : '' ?>><?= htmlspecialchars($t['tiers']['pro']) ?></option>
                        <option value="simple" <?= $params['tier'] === 'simple' ? 'selected' : '' ?>><?= htmlspecialchars($t['tiers']['simple']) ?></option>
                    </select>
                </div>
                <button type="submit" class="fl-btn-primary fl-btn-block"><i class="fas fa-search"></i> <?= htmlspecialchars($t['search']['search_btn']) ?></button>
            </form>
        </aside>
        <div>
            <div class="fl-results-header">
                <h1><?= sprintf($t['freelancers_page']['found'], count($results)) ?></h1>
            </div>
            <?php if ($results === []): ?>
            <p class="fl-no-results"><?= htmlspecialchars($t['freelancers_page']['no_results']) ?></p>
            <?php else: ?>
            <div class="fl-freelancer-grid">
                <?php foreach ($results as $freelancer):
                    require __DIR__ . '/includes/freelancer-card.php';
                endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>