<?php
/** @var array $project */
/** @var array $t */
/** @var string $lang */
$detail_url = fl_url('project.php?id=' . urlencode($project['id']));
$propose_url = fl_url('propose.php?id=' . urlencode($project['id']));
$name       = fl_localized($project, 'name', $lang);
$city       = fl_localized($project, 'city', $lang);
$country    = fl_localized($project, 'country', $lang);
$desc       = fl_localized($project, 'desc', $lang);
$is_open    = fl_project_is_open($project);
$deadline_ts = fl_project_deadline($project);
$cat        = $project['category'] ?? '';
$budget_type = ($project['budget_type'] ?? 'fixed') === 'hourly' ? $t['card']['hourly'] : $t['card']['fixed'];
?>
<article class="fl-project-card" data-deadline="<?= (int)$deadline_ts ?>">
    <div class="fl-project-img">
        <a href="<?= htmlspecialchars($detail_url) ?>">
            <img src="<?= htmlspecialchars(fl_project_image($project)) ?>" alt="<?= htmlspecialchars($name) ?>" loading="lazy" width="320" height="220" onerror="this.onerror=null;this.src='<?= htmlspecialchars(fl_placeholder_image()) ?>';">
        </a>
        <?php if ($is_open): ?>
        <span class="fl-open-badge"><i class="fas fa-circle"></i> <?= htmlspecialchars($t['card']['open']) ?></span>
        <?php endif; ?>
        <?php if (!empty($project['featured'])): ?>
        <span class="fl-featured-badge"><i class="fas fa-star"></i></span>
        <?php endif; ?>
    </div>
    <div class="fl-project-body">
        <div class="fl-project-meta">
            <span class="fl-cat-tag"><?= htmlspecialchars($t['categories'][$cat] ?? $cat) ?></span>
            <span class="fl-budget-type"><?= htmlspecialchars($budget_type) ?></span>
            <?php if ($is_open): ?>
            <span class="fl-countdown" data-countdown="<?= (int)$deadline_ts ?>">
                <i class="fas fa-clock"></i> <span class="fl-countdown-val">--</span>
            </span>
            <?php else: ?>
            <span class="fl-closed-tag"><?= htmlspecialchars($t['card']['closed']) ?></span>
            <?php endif; ?>
        </div>
        <h3><a href="<?= htmlspecialchars($detail_url) ?>"><?= htmlspecialchars($name) ?></a></h3>
        <div class="fl-location"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($city) ?>, <?= htmlspecialchars($country) ?></div>
        <p class="fl-desc-snippet"><?= htmlspecialchars(bh_str_sub($desc, 0, 100)) ?>…</p>
        <?php if (!empty($project['skills'])): ?>
        <div class="fl-skills">
            <?php require_once __DIR__ . '/skill-tags.php'; fl_render_skill_tags($project['skills'], 'projects', 3); ?>
        </div>
        <?php endif; ?>
    </div>
    <div class="fl-project-side">
        <div class="fl-budget-block">
            <div class="fl-budget-label"><?= htmlspecialchars($t['card']['budget']) ?></div>
            <div class="fl-budget-price"><?= fl_budget_label($project) ?></div>
            <div class="fl-budget-note">
                <?= (int)($project['proposals_count'] ?? 0) ?> <?= htmlspecialchars($t['card']['proposals']) ?>
            </div>
            <a href="<?= htmlspecialchars($propose_url) ?>" class="fl-btn-primary"><?= htmlspecialchars($t['card']['apply']) ?></a>
            <a href="<?= htmlspecialchars($detail_url) ?>" class="fl-btn-ghost"><?= htmlspecialchars($t['card']['view_project']) ?></a>
        </div>
    </div>
</article>