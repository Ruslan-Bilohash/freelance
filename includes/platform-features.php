<?php
/** Platform features grid — expects $t['platform_features'] */
if (empty($t['platform_features']['items'])) {
    return;
}
$pf = $t['platform_features'];
?>
<section class="fl-platform-section">
    <div class="fl-container">
        <div class="fl-section-head fl-section-head-center">
            <div>
                <h2 class="fl-section-title"><?= htmlspecialchars($pf['title']) ?></h2>
                <p class="fl-section-sub"><?= htmlspecialchars($pf['subtitle']) ?></p>
            </div>
        </div>
        <div class="fl-platform-grid">
            <?php foreach ($pf['items'] as $item): ?>
            <article class="fl-platform-card">
                <div class="fl-platform-icon"><i class="fas fa-<?= htmlspecialchars($item['icon']) ?>" aria-hidden="true"></i></div>
                <h3><?= htmlspecialchars($item['title']) ?></h3>
                <p><?= htmlspecialchars($item['desc']) ?></p>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>