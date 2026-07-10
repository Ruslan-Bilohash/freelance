<?php
/** Main navigation — compact desktop, full drawer on mobile */
$cat_page = in_array($current_page ?? '', ['search', 'project'], true);
$more_active = in_array($current_page ?? '', ['become', 'regions', 'region'], true);
?>
<nav class="fl-nav" aria-label="Main">
    <a href="<?= fl_url('index.php') ?>" class="<?= ($current_page ?? '') === 'home' ? 'active' : '' ?>">
        <span class="fl-nav-label-full"><?= htmlspecialchars($t['nav']['browse']) ?></span>
        <span class="fl-nav-label-short"><?= htmlspecialchars($t['nav']['browse_short'] ?? $t['nav']['browse']) ?></span>
    </a>
    <a href="<?= fl_url('freelancers.php') ?>" class="<?= ($current_page ?? '') === 'freelancers' ? 'active' : '' ?>">
        <span class="fl-nav-label-full"><?= htmlspecialchars($t['nav']['freelancers']) ?></span>
        <span class="fl-nav-label-short"><?= htmlspecialchars($t['nav']['freelancers_short'] ?? $t['nav']['freelancers']) ?></span>
    </a>

    <div class="fl-nav-dropdown" id="flNavDropdown">
        <button type="button" class="fl-nav-dropdown-btn <?= $cat_page ? 'active' : '' ?>" id="flNavDropdownBtn" aria-expanded="false" aria-haspopup="true">
            <?= htmlspecialchars($t['nav']['categories_menu'] ?? $t['nav']['categories']) ?>
            <i class="fas fa-chevron-down" aria-hidden="true"></i>
        </button>
        <div class="fl-nav-dropdown-menu" id="flNavDropdownMenu" hidden>
            <a href="<?= fl_url('search.php') ?>" class="fl-nav-dropdown-all"><?= htmlspecialchars($t['nav']['all_projects'] ?? 'All projects') ?></a>
            <?php foreach (fl_categories() as $cat): ?>
            <a href="<?= fl_url('search.php?category=' . urlencode($cat)) ?>"><?= htmlspecialchars($t['categories'][$cat] ?? $cat) ?></a>
            <?php endforeach; ?>
            <span class="fl-nav-dropdown-divider"></span>
            <a href="<?= fl_url('search.php?status=urgent') ?>"><i class="fas fa-clock"></i> <?= htmlspecialchars($t['nav']['urgent']) ?></a>
        </div>
    </div>

    <div class="fl-nav-dropdown fl-nav-dropdown-more" id="flNavMore">
        <button type="button" class="fl-nav-dropdown-btn <?= $more_active ? 'active' : '' ?>" id="flNavMoreBtn" aria-expanded="false" aria-haspopup="true">
            <?= htmlspecialchars($t['nav']['more'] ?? 'More') ?>
            <i class="fas fa-chevron-down" aria-hidden="true"></i>
        </button>
        <div class="fl-nav-dropdown-menu" id="flNavMoreMenu" hidden>
            <a href="<?= fl_url('solutions.php') ?>" class="<?= in_array($current_page ?? '', ['solutions', 'solution'], true) ? 'active' : '' ?>"><i class="fas fa-layer-group"></i> <?= htmlspecialchars($t['nav']['solutions'] ?? ($t['solutions']['hub_title'] ?? 'Use cases')) ?></a>
            <a href="<?= fl_url('become-freelancer.php') ?>" class="<?= ($current_page ?? '') === 'become' ? 'active' : '' ?>"><i class="fas fa-user-plus"></i> <?= htmlspecialchars($t['nav']['become'] ?? $t['nav']['register']) ?></a>
            <a href="<?= fl_url('regions.php') ?>" class="<?= in_array($current_page ?? '', ['regions', 'region'], true) ? 'active' : '' ?>"><i class="fas fa-map-marked-alt"></i> <?= htmlspecialchars($t['nav']['regions'] ?? 'Regions') ?></a>
            <a href="https://bilohash.com/freelance/site/"><i class="fas fa-book"></i> <?= htmlspecialchars($t['demo_strip']['cms'] ?? 'Product') ?></a>
            <span class="fl-nav-dropdown-divider"></span>
            <?php if (!empty($t['ecosystem']['items'])): ?>
                <?php foreach ($t['ecosystem']['items'] as $eco): ?>
            <a href="<?= htmlspecialchars($eco['demo']) ?>" rel="related">
                <?php if (($eco['icon'] ?? '') === 'wordpress'): ?>
                <i class="fab fa-wordpress" aria-hidden="true"></i>
                <?php else: ?>
                <i class="fas fa-<?= htmlspecialchars($eco['icon']) ?>" aria-hidden="true"></i>
                <?php endif; ?>
                <?= htmlspecialchars($eco['name']) ?>
            </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</nav>