<?php

/**
 * Clickable country / region tags for contact and marketing pages (same style as skill tags).
 */
function fl_render_contact_region_tags(): void
{
    global $t, $lang;

    if (!function_exists('fl_region_url')) {
        $lib = __DIR__ . '/region-lib.php';
        if (is_file($lib)) {
            require_once $lib;
        }
    }

    $tc = $t['contact'] ?? [];
    $tags = [];

    $tags[] = [
        'label' => $tc['norway'] ?? 'Norway',
        'url'   => fl_url('regions.php'),
        'title' => $tc['norway_title'] ?? ($tc['norway'] ?? 'Norway'),
    ];
    $tags[] = [
        'label' => $tc['europe'] ?? 'Europe',
        'url'   => fl_region_url('europe'),
        'title' => sprintf($tc['region_title'] ?? '%s — Freelance CMS', $tc['europe'] ?? 'Europe'),
    ];
    $tags[] = [
        'label' => $tc['ukraine'] ?? 'Ukraine',
        'url'   => fl_region_url('ukraine'),
        'title' => sprintf($tc['region_title'] ?? '%s — Freelance CMS', $tc['ukraine'] ?? 'Ukraine'),
    ];

    if (function_exists('fl_regions_all')) {
        foreach (fl_regions_all() as $slug => $item) {
            if (($item['country'] ?? '') !== 'no') {
                continue;
            }
            $name = $item['names'][$lang] ?? $item['names']['en'] ?? $slug;
            $tags[] = [
                'label' => $name,
                'url'   => fl_region_url($slug),
                'title' => sprintf($tc['city_title'] ?? '%s — freelance marketplace SEO', $name),
            ];
        }
    }

    ?>
    <div class="fl-contact-aside-block">
        <h3 class="fl-contact-aside-title"><i class="fas fa-globe-europe" aria-hidden="true"></i> <?= htmlspecialchars($tc['markets_label'] ?? 'Countries & regions') ?></h3>
        <p class="fl-contact-aside-hint"><?= htmlspecialchars($tc['markets_hint'] ?? '') ?></p>
        <div class="fl-skills fl-contact-tags">
            <?php foreach ($tags as $tag): ?>
            <a href="<?= htmlspecialchars($tag['url']) ?>" class="fl-skill-tag" title="<?= htmlspecialchars($tag['title']) ?>"><?= htmlspecialchars($tag['label']) ?></a>
            <?php endforeach; ?>
        </div>
        <a href="<?= fl_url('regions.php') ?>" class="fl-contact-aside-link"><?= htmlspecialchars($tc['all_regions'] ?? ($t['footer']['all_regions'] ?? 'All regions')) ?> →</a>
    </div>
    <?php
}