<?php
require_once __DIR__ . '/init.php';
$id = $_GET['id'] ?? '';
$project = fl_project_by_id($id);
if (!$project) {
    header('Location: ' . fl_url('search.php'), true, 302);
    exit;
}
$name       = fl_localized($project, 'name', $lang);
$city       = fl_localized($project, 'city', $lang);
$country    = fl_localized($project, 'country', $lang);
$desc       = fl_localized($project, 'desc', $lang);
$long_desc  = fl_project_long_desc($project, $lang);
$client     = fl_project_client($project, $lang);
$highlights = fl_project_highlights($project, $lang);
$is_open    = fl_project_is_open($project);
$deadline_ts = fl_project_deadline($project);
$posted_ts  = fl_project_posted_at($project);
$history    = fl_demo_proposal_history($project);
$related    = fl_related_projects($project, 4);
$propose_url = fl_url('propose.php?id=' . urlencode($project['id']));
$page_title = $name . ' — ' . $t['meta']['site_name'];
$page_desc  = mb_substr(strip_tags($long_desc), 0, 160);
$canonical  = $site_url . '/project.php?id=' . urlencode($project['id']);
$canon_abs  = fl_absolute_url($canonical);
$seo_og_image = fl_project_image($project);
$seo_og_type  = 'article';
$seo_schemas = [
    fl_seo_webpage($canon_abs, $page_title, $page_desc),
    fl_seo_breadcrumbs([
        ['name' => $t['breadcrumb_home'], 'url' => fl_absolute_url(fl_url('index.php'))],
        ['name' => $t['categories'][$project['category'] ?? ''] ?? 'Projects', 'url' => fl_absolute_url(fl_url('search.php?category=' . urlencode($project['category'] ?? '')))],
        ['name' => $name, 'url' => $canon_abs],
    ]),
    fl_seo_project($project, $lang, $canon_abs),
];
require __DIR__ . '/includes/header.php';
?>

<div class="fl-container">
    <nav class="fl-breadcrumb">
        <a href="<?= fl_url('index.php') ?>"><?= htmlspecialchars($t['breadcrumb_home']) ?></a>
        → <a href="<?= fl_url('search.php?category=' . urlencode($project['category'] ?? '')) ?>"><?= htmlspecialchars($t['categories'][$project['category'] ?? ''] ?? '') ?></a>
        → <?= htmlspecialchars($name) ?>
    </nav>

    <div class="fl-detail-layout">
        <div class="fl-detail">
            <div class="fl-gallery">
                <img src="<?= htmlspecialchars(fl_project_image($project)) ?>" alt="<?= htmlspecialchars($name) ?>" onerror="this.onerror=null;this.src='<?= htmlspecialchars(fl_placeholder_image()) ?>';">
            </div>
            <div class="fl-detail-head">
                <span class="fl-cat-tag"><?= htmlspecialchars($t['categories'][$project['category'] ?? ''] ?? '') ?></span>
                <span class="fl-project-id"><?= htmlspecialchars($t['project']['project_id']) ?>: <?= htmlspecialchars($project['id']) ?></span>
                <?php if (!empty($project['featured'])): ?>
                <span class="fl-cat-tag fl-cat-tag-accent"><i class="fas fa-star"></i> Featured</span>
                <?php endif; ?>
            </div>
            <h1><?= htmlspecialchars($name) ?></h1>
            <div class="fl-location fl-location-lg">
                <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($city) ?>, <?= htmlspecialchars($country) ?>
            </div>

            <?php if (!empty($project['skills'])): ?>
            <div class="fl-skills fl-skills-lg">
                <?php require_once __DIR__ . '/includes/skill-tags.php'; fl_render_skill_tags($project['skills'], 'projects'); ?>
            </div>
            <?php endif; ?>

            <div class="fl-tabs" role="tablist">
                <button type="button" class="fl-tab active" data-tab="overview"><?= htmlspecialchars($t['project']['tab_overview']) ?></button>
                <button type="button" class="fl-tab" data-tab="activity"><?= htmlspecialchars($t['project']['tab_activity']) ?></button>
                <button type="button" class="fl-tab" data-tab="specs"><?= htmlspecialchars($t['project']['tab_specs']) ?></button>
            </div>

            <div class="fl-tab-panel active" id="tab-overview">
                <p class="fl-detail-desc"><?= htmlspecialchars($desc) ?></p>
                <?php if ($long_desc !== $desc): ?>
                <p class="fl-detail-desc fl-detail-long"><?= htmlspecialchars($long_desc) ?></p>
                <?php endif; ?>
                <?php if (!empty($highlights)): ?>
                <h3 class="fl-subhead"><?= htmlspecialchars($t['project']['highlights']) ?></h3>
                <ul class="fl-highlight-list">
                    <?php foreach ($highlights as $h): ?>
                    <li><i class="fas fa-check"></i> <?= htmlspecialchars($h) ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </div>

            <div class="fl-tab-panel" id="tab-activity" hidden>
                <p class="fl-demo-hint"><?= htmlspecialchars($t['project']['demo_history']) ?></p>
                <div class="fl-proposal-history">
                    <table>
                        <thead>
                            <tr>
                                <th><?= htmlspecialchars($t['project']['freelancer']) ?></th>
                                <th><?= htmlspecialchars($t['project']['amount']) ?></th>
                                <th><?= htmlspecialchars($t['project']['delivery']) ?></th>
                                <th><?= htmlspecialchars($t['project']['time']) ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($history as $h): ?>
                            <tr>
                                <td><?= htmlspecialchars($h['freelancer']) ?></td>
                                <td><strong><?= fl_price((int)$h['bid_amount']) ?></strong></td>
                                <td><?= (int)$h['delivery_days'] ?>d</td>
                                <td><?= date('d.m.Y H:i', strtotime($h['time'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="fl-tab-panel" id="tab-specs" hidden>
                <dl class="fl-spec-grid">
                    <div><dt><?= htmlspecialchars($t['project']['client']) ?></dt><dd><?= htmlspecialchars($client) ?></dd></div>
                    <div><dt><?= htmlspecialchars($t['project']['category']) ?></dt><dd><?= htmlspecialchars($t['categories'][$project['category'] ?? ''] ?? '') ?></dd></div>
                    <div><dt><?= htmlspecialchars($t['project']['level']) ?></dt><dd><?= htmlspecialchars(fl_experience_label($project['experience_level'] ?? 'intermediate', $lang)) ?></dd></div>
                    <div><dt><?= htmlspecialchars($t['project']['duration']) ?></dt><dd><?= htmlspecialchars($project['duration'] ?? '—') ?></dd></div>
                    <div><dt><?= htmlspecialchars($t['project']['posted_on']) ?></dt><dd><?= date('d.m.Y H:i', $posted_ts) ?></dd></div>
                    <div><dt><?= htmlspecialchars($t['project']['deadline_on']) ?></dt><dd><?= date('d.m.Y H:i', $deadline_ts) ?></dd></div>
                </dl>
            </div>
        </div>

        <aside class="fl-propose-box">
            <?php if ($is_open): ?>
            <div class="fl-countdown-lg" data-countdown="<?= (int)$deadline_ts ?>">
                <span class="fl-countdown-label"><?= htmlspecialchars($t['card']['deadline']) ?></span>
                <div class="fl-countdown-blocks">
                    <span><b class="cd-d">--</b><small><?= htmlspecialchars($t['countdown']['days']) ?></small></span>
                    <span><b class="cd-h">--</b><small><?= htmlspecialchars($t['countdown']['hours']) ?></small></span>
                    <span><b class="cd-m">--</b><small><?= htmlspecialchars($t['countdown']['minutes']) ?></small></span>
                    <span><b class="cd-s">--</b><small><?= htmlspecialchars($t['countdown']['seconds']) ?></small></span>
                </div>
            </div>
            <?php else: ?>
            <div class="fl-closed-banner"><?= htmlspecialchars($t['card']['closed']) ?></div>
            <?php endif; ?>

            <div class="fl-budget-current">
                <div class="fl-budget-label"><?= htmlspecialchars($t['project']['budget']) ?></div>
                <div class="fl-budget-price-lg"><?= fl_budget_label($project) ?></div>
                <div class="fl-budget-note"><?= sprintf($t['project']['proposals_count'], (int)($project['proposals_count'] ?? 0)) ?></div>
            </div>

            <dl class="fl-propose-details">
                <div><dt><?= htmlspecialchars($t['project']['budget_type']) ?></dt><dd><?= htmlspecialchars(($project['budget_type'] ?? 'fixed') === 'hourly' ? $t['card']['hourly'] : $t['card']['fixed']) ?></dd></div>
                <div><dt><?= htmlspecialchars($t['project']['level']) ?></dt><dd><?= htmlspecialchars(fl_experience_label($project['experience_level'] ?? 'intermediate', $lang)) ?></dd></div>
                <div><dt><?= htmlspecialchars($t['project']['duration']) ?></dt><dd><?= htmlspecialchars($project['duration'] ?? '—') ?></dd></div>
            </dl>

            <?php if ($is_open): ?>
            <a href="<?= htmlspecialchars($propose_url) ?>" class="fl-btn-primary fl-btn-block">
                <i class="fas fa-paper-plane"></i> <?= htmlspecialchars($t['project']['send_proposal']) ?>
            </a>
            <?php endif; ?>
            <div class="fl-demo-alert"><?= htmlspecialchars($t['project']['demo_note']) ?></div>
        </aside>
    </div>

    <?php if (!empty($related)): ?>
    <section class="fl-related">
        <h2 class="fl-section-title"><?= htmlspecialchars($t['project']['related']) ?></h2>
        <div class="fl-project-grid fl-project-grid-compact">
            <?php foreach ($related as $rproject):
                $project = $rproject;
                require __DIR__ . '/includes/project-card.php';
            endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>