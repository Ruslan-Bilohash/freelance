<?php
/** Bilohash ecosystem product cards — expects $t['ecosystem'] */
if (empty($t['ecosystem']['items'])) {
    return;
}
$eco = $t['ecosystem'];
?>
<section class="fl-ecosystem-section">
    <div class="fl-container">
        <div class="fl-section-head fl-section-head-center">
            <div>
                <h2 class="fl-section-title"><?= htmlspecialchars($eco['title']) ?></h2>
                <p class="fl-section-sub"><?= htmlspecialchars($eco['subtitle']) ?></p>
            </div>
        </div>
        <div class="fl-ecosystem-grid">
            <?php foreach ($eco['items'] as $item): ?>
            <article class="fl-ecosystem-card">
                <div class="fl-ecosystem-icon">
                    <?php if (($item['icon'] ?? '') === 'wordpress'): ?>
                    <i class="fab fa-wordpress" aria-hidden="true"></i>
                    <?php else: ?>
                    <i class="fas fa-<?= htmlspecialchars($item['icon']) ?>" aria-hidden="true"></i>
                    <?php endif; ?>
                </div>
                <h3><?= htmlspecialchars($item['name']) ?></h3>
                <p><?= htmlspecialchars($item['desc']) ?></p>
                <div class="fl-ecosystem-links">
                    <a href="<?= htmlspecialchars($item['url']) ?>" class="fl-btn-outline-dark fl-btn-sm" rel="related"><?= htmlspecialchars($eco['product_btn']) ?></a>
                    <a href="<?= htmlspecialchars($item['demo']) ?>" class="fl-btn-outline fl-btn-sm" rel="related"><?= htmlspecialchars($eco['demo_btn']) ?></a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>