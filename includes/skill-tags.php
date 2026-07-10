<?php

function fl_skill_slug(string $skill): string
{
    return rawurlencode(trim($skill));
}

function fl_skill_search_url(string $skill, string $context = 'projects'): string
{
    $q = fl_skill_slug($skill);
    if ($context === 'freelancers') {
        return fl_url('freelancers.php?q=' . $q);
    }
    return fl_url('search.php?q=' . $q);
}

function fl_render_skill_tags(array $skills, string $context = 'projects', int $limit = 0): void
{
    global $t;
    $list = $limit > 0 ? array_slice($skills, 0, $limit) : $skills;
    foreach ($list as $skill) {
        $skill = trim((string) $skill);
        if ($skill === '') {
            continue;
        }
        $url = fl_skill_search_url($skill, $context);
        $title = sprintf($t['skills']['search_title'] ?? 'Search %s', $skill);
        ?>
        <a href="<?= htmlspecialchars($url) ?>" class="fl-skill-tag" title="<?= htmlspecialchars($title) ?>"><?= htmlspecialchars($skill) ?></a>
        <?php
    }
}