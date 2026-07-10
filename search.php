<?php
require_once __DIR__ . '/init.php';
$current_page = 'search';
$search_params = fl_search_params();
$results = fl_filter_projects($search_params, $lang);
$page_title = $t['search_page']['title'] . ($search_params['q'] ? ' — ' . $search_params['q'] : '');
$page_desc  = sprintf($t['search_page']['found'], count($results)) . '. ' . $t['meta']['description'];
$canonical = $site_url . '/search.php?' . http_build_query(array_filter($search_params));
$canon_abs = fl_absolute_url($canonical);
$seo_noindex = true;
$seo_schemas = [
    fl_seo_webpage($canon_abs, $page_title, $page_desc),
    fl_seo_item_list($results, $lang, $canon_abs),
    fl_seo_breadcrumbs([
        ['name' => $t['breadcrumb_home'], 'url' => fl_absolute_url(fl_url('index.php'))],
        ['name' => $t['search_page']['title'], 'url' => $canon_abs],
    ]),
];
require __DIR__ . '/includes/header.php';
?>

<section class="fl-hero fl-hero-compact">
    <div class="fl-hero-inner">
        <?php require __DIR__ . '/includes/search-form.php'; ?>
    </div>
</section>

<div class="fl-container">
    <div class="fl-search-layout">
        <aside class="fl-filters">
            <h3><?= htmlspecialchars($t['search_page']['filter']) ?></h3>
            <form method="get" action="<?= fl_url('search.php') ?>">
                <input type="hidden" name="q" value="<?= htmlspecialchars($search_params['q']) ?>">
                <div class="fl-filter-group">
                    <label><?= htmlspecialchars($t['search_page']['category']) ?></label>
                    <select name="category" onchange="this.form.submit()">
                        <option value=""><?= htmlspecialchars($t['search_page']['all']) ?></option>
                        <?php foreach (fl_categories() as $cat): ?>
                        <option value="<?= $cat ?>" <?= $search_params['category'] === $cat ? 'selected' : '' ?>><?= htmlspecialchars($t['categories'][$cat]) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="fl-filter-group">
                    <label><?= htmlspecialchars($t['search_page']['status']) ?></label>
                    <select name="status" onchange="this.form.submit()">
                        <option value=""><?= htmlspecialchars($t['search_page']['status_all']) ?></option>
                        <option value="open" <?= $search_params['status'] === 'open' ? 'selected' : '' ?>><?= htmlspecialchars($t['search_page']['status_open']) ?></option>
                        <option value="urgent" <?= $search_params['status'] === 'urgent' ? 'selected' : '' ?>><?= htmlspecialchars($t['search_page']['status_urgent']) ?></option>
                    </select>
                </div>
                <div class="fl-filter-group">
                    <label><?= htmlspecialchars($t['search_page']['level']) ?></label>
                    <select name="level" onchange="this.form.submit()">
                        <option value=""><?= htmlspecialchars($t['search_page']['all']) ?></option>
                        <?php foreach (fl_experience_levels() as $lvl): ?>
                        <option value="<?= $lvl ?>" <?= $search_params['level'] === $lvl ? 'selected' : '' ?>><?= htmlspecialchars($t['levels'][$lvl]) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="fl-filter-group">
                    <label><?= htmlspecialchars($t['search_page']['sort']) ?></label>
                    <select name="sort" onchange="this.form.submit()">
                        <option value="newest" <?= $search_params['sort'] === 'newest' ? 'selected' : '' ?>><?= htmlspecialchars($t['search_page']['sort_new']) ?></option>
                        <option value="budget_low" <?= $search_params['sort'] === 'budget_low' ? 'selected' : '' ?>><?= htmlspecialchars($t['search_page']['sort_budget_l']) ?></option>
                        <option value="budget_high" <?= $search_params['sort'] === 'budget_high' ? 'selected' : '' ?>><?= htmlspecialchars($t['search_page']['sort_budget_h']) ?></option>
                        <option value="proposals" <?= $search_params['sort'] === 'proposals' ? 'selected' : '' ?>><?= htmlspecialchars($t['search_page']['sort_proposals']) ?></option>
                        <option value="deadline" <?= $search_params['sort'] === 'deadline' ? 'selected' : '' ?>><?= htmlspecialchars($t['search_page']['sort_deadline']) ?></option>
                    </select>
                </div>
                <div class="fl-filter-group">
                    <label><?= htmlspecialchars($t['search_page']['budget']) ?> (min)</label>
                    <input type="number" name="min_budget" value="<?= (int)$search_params['min_budget'] ?>" min="0" step="1000" placeholder="0">
                </div>
                <div class="fl-filter-group">
                    <label><?= htmlspecialchars($t['search_page']['budget']) ?> (max)</label>
                    <input type="number" name="max_budget" value="<?= (int)$search_params['max_budget'] ?>" min="0" step="1000" placeholder="200000">
                </div>
                <button type="submit" class="fl-btn-primary" style="width:100%"><?= htmlspecialchars($t['search']['search_btn']) ?></button>
            </form>
        </aside>

        <div>
            <div class="fl-results-header">
                <h1><?= sprintf(htmlspecialchars($t['search_page']['found']), count($results)) ?></h1>
                <?php if ($search_params['q']): ?>
                <span class="fl-results-meta"><i class="fas fa-search"></i> <?= htmlspecialchars($search_params['q']) ?></span>
                <?php endif; ?>
            </div>

            <?php if (empty($results)): ?>
            <div class="fl-form-card fl-empty-state">
                <i class="fas fa-briefcase"></i>
                <p><?= htmlspecialchars($t['search_page']['no_results']) ?></p>
                <a href="<?= fl_url('index.php') ?>" class="fl-btn-primary"><?= htmlspecialchars($t['breadcrumb_home']) ?></a>
            </div>
            <?php else: ?>
            <div class="fl-project-grid fl-project-grid-list">
                <?php foreach ($results as $project):
                    require __DIR__ . '/includes/project-card.php';
                endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>