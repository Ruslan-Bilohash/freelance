<?php
/** @var array $freelancer @var array $t @var string $lang */
$url = fl_url('freelancer.php?id=' . urlencode($freelancer['id']));
$name = fl_localized($freelancer, 'name', $lang);
$title = fl_localized($freelancer, 'title', $lang);
$city = fl_localized($freelancer, 'city', $lang);
$tier = $freelancer['tier'] ?? 'simple';
$tierClass = 'fl-tier-' . $tier;
?>
<article class="fl-freelancer-card">
    <div class="fl-freelancer-head">
        <img src="<?= htmlspecialchars(fl_freelancer_cover($freelancer)) ?>" alt="" class="cover" loading="lazy" onerror="this.style.display='none'">
        <img src="<?= htmlspecialchars(fl_freelancer_image($freelancer)) ?>" alt="<?= htmlspecialchars($name) ?>" class="fl-freelancer-avatar" loading="lazy" onerror="this.onerror=null;this.src='<?= htmlspecialchars(fl_placeholder_image()) ?>';">
    </div>
    <div class="fl-freelancer-body">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;flex-wrap:wrap">
            <span class="fl-tier-badge <?= $tierClass ?>">
                <i class="fas fa-<?= $tier === 'pro' ? 'crown' : 'user' ?>"></i>
                <?= htmlspecialchars($t['tiers'][$tier] ?? strtoupper($tier)) ?>
            </span>
            <?php if ((float)($freelancer['rating'] ?? 0) > 0): ?>
            <span class="fl-rating"><i class="fas fa-star"></i> <?= number_format((float)$freelancer['rating'], 1) ?></span>
            <?php endif; ?>
        </div>
        <h3><a href="<?= htmlspecialchars($url) ?>"><?= htmlspecialchars($name) ?></a></h3>
        <p class="fl-freelancer-title"><?= htmlspecialchars($title) ?></p>
        <div class="fl-location"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($city) ?></div>
        <div class="fl-freelancer-rate"><?= fl_freelancer_rate_label($freelancer) ?></div>
        <?php if (!empty($freelancer['skills'])): ?>
        <div class="fl-skills">
            <?php require_once __DIR__ . '/skill-tags.php'; fl_render_skill_tags($freelancer['skills'], 'freelancers', 4); ?>
        </div>
        <?php endif; ?>
        <a href="<?= htmlspecialchars($url) ?>" class="fl-btn-primary fl-btn-block" style="margin-top:14px"><?= htmlspecialchars($t['freelancer_card']['view']) ?></a>
    </div>
</article>