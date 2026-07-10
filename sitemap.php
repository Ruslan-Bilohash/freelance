<?php
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/includes/region-lib.php';
require_once __DIR__ . '/includes/solution-lib.php';
header('Content-Type: application/xml; charset=UTF-8');

$base = 'https://bilohash.com/freelance';
$site_langs = ['en', 'uk', 'ru'];

$urls = [
    [
        'loc' => $base . '/',
        'priority' => '1.0',
        'changefreq' => 'weekly',
        'hreflang' => true,
        'path' => '/freelance/',
    ],
    [
        'loc' => $base . '/site/',
        'priority' => '0.95',
        'changefreq' => 'weekly',
        'hreflang' => true,
        'path' => '/freelance/site/',
    ],
    [
        'loc' => $base . '/contact.php',
        'priority' => '0.88',
        'changefreq' => 'monthly',
        'hreflang' => true,
        'path' => '/freelance/contact.php',
    ],
    [
        'loc' => $base . '/site/contact.php',
        'priority' => '0.87',
        'changefreq' => 'monthly',
    ],
    [
        'loc' => $base . '/llms.txt',
        'priority' => '0.5',
        'changefreq' => 'monthly',
    ],
];

foreach ($site_langs as $lng) {
    $urls[] = [
        'loc' => $base . '/site/?lang=' . $lng,
        'priority' => '0.85',
        'changefreq' => 'weekly',
    ];
    $urls[] = [
        'loc' => $base . '/?lang=' . $lng,
        'priority' => '0.85',
        'changefreq' => 'weekly',
    ];
    $urls[] = [
        'loc' => $base . '/contact.php?lang=' . $lng,
        'priority' => '0.83',
        'changefreq' => 'monthly',
    ];
    $urls[] = [
        'loc' => $base . '/site/contact.php?lang=' . $lng,
        'priority' => '0.82',
        'changefreq' => 'monthly',
    ];
}

foreach (fl_freelancers() as $freelancer) {
    $fid = $freelancer['id'] ?? '';
    if ($fid === '') {
        continue;
    }
    $urls[] = [
        'loc' => $base . '/freelancer.php?id=' . urlencode($fid),
        'priority' => '0.78',
        'changefreq' => 'weekly',
        'freelancer_hreflang' => $fid,
    ];
    foreach ($site_langs as $lng) {
        $urls[] = [
            'loc' => $base . '/freelancer.php?id=' . urlencode($fid) . '&lang=' . $lng,
            'priority' => '0.74',
            'changefreq' => 'weekly',
        ];
    }
}

$urls[] = ['loc' => $base . '/freelancers.php', 'priority' => '0.88', 'changefreq' => 'weekly', 'hreflang' => true, 'path' => '/freelance/freelancers.php'];
$urls[] = ['loc' => $base . '/become-freelancer.php', 'priority' => '0.92', 'changefreq' => 'weekly', 'hreflang' => true, 'path' => '/freelance/become-freelancer.php'];
$urls[] = ['loc' => $base . '/regions.php', 'priority' => '0.9', 'changefreq' => 'weekly', 'hreflang' => true, 'path' => '/freelance/regions.php'];
$urls[] = ['loc' => $base . '/solutions.php', 'priority' => '0.91', 'changefreq' => 'weekly', 'hreflang' => true, 'path' => '/freelance/solutions.php'];

foreach (fl_solution_slugs() as $sslug) {
    $urls[] = [
        'loc' => $base . '/' . $sslug . '/',
        'priority' => '0.89',
        'changefreq' => 'weekly',
        'solution_hreflang' => $sslug,
    ];
    foreach ($site_langs as $lng) {
        $urls[] = [
            'loc' => $base . '/' . $sslug . '/?lang=' . $lng,
            'priority' => '0.86',
            'changefreq' => 'weekly',
        ];
    }
}
$urls[] = ['loc' => $base . '/register.php', 'priority' => '0.7', 'changefreq' => 'monthly'];
$urls[] = ['loc' => $base . '/login.php', 'priority' => '0.65', 'changefreq' => 'monthly'];

foreach (fl_region_slugs() as $rslug) {
    $urls[] = [
        'loc' => $base . '/' . $rslug . '/',
        'priority' => ($rslug === 'europe' || $rslug === 'ukraine') ? '0.87' : '0.85',
        'changefreq' => 'weekly',
        'region_hreflang' => $rslug,
    ];
    foreach ($site_langs as $lng) {
        $urls[] = [
            'loc' => $base . '/' . $rslug . '/?lang=' . $lng,
            'priority' => '0.82',
            'changefreq' => 'weekly',
        ];
    }
}

foreach (fl_projects() as $project) {
    $id = $project['id'] ?? '';
    if ($id === '') {
        continue;
    }
    $urls[] = [
        'loc' => $base . '/project.php?id=' . urlencode($id),
        'priority' => '0.8',
        'changefreq' => 'weekly',
        'project_hreflang' => $id,
    ];
    foreach ($site_langs as $lng) {
        $urls[] = [
            'loc' => $base . '/project.php?id=' . urlencode($id) . '&lang=' . $lng,
            'priority' => '0.75',
            'changefreq' => 'weekly',
        ];
    }
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml">
<?php foreach ($urls as $u): ?>
    <url>
        <loc><?= htmlspecialchars($u['loc']) ?></loc>
        <changefreq><?= $u['changefreq'] ?? 'monthly' ?></changefreq>
        <priority><?= $u['priority'] ?? '0.5' ?></priority>
        <?php if (!empty($u['hreflang']) && !empty($u['path'])):
            $path = $u['path'];
        ?>
        <xhtml:link rel="alternate" hreflang="x-default" href="https://bilohash.com<?= htmlspecialchars($path) ?>"/>
        <xhtml:link rel="alternate" hreflang="no" href="https://bilohash.com<?= htmlspecialchars($path) ?>"/>
        <xhtml:link rel="alternate" hreflang="en" href="https://bilohash.com<?= htmlspecialchars($path) ?>?lang=en"/>
        <xhtml:link rel="alternate" hreflang="uk" href="https://bilohash.com<?= htmlspecialchars($path) ?>?lang=uk"/>
        <xhtml:link rel="alternate" hreflang="ru" href="https://bilohash.com<?= htmlspecialchars($path) ?>?lang=ru"/>
        <?php elseif (!empty($u['freelancer_hreflang'])):
            $id = urlencode($u['freelancer_hreflang']);
        ?>
        <xhtml:link rel="alternate" hreflang="x-default" href="https://bilohash.com/freelance/freelancer.php?id=<?= $id ?>"/>
        <xhtml:link rel="alternate" hreflang="no" href="https://bilohash.com/freelance/freelancer.php?id=<?= $id ?>"/>
        <xhtml:link rel="alternate" hreflang="en" href="https://bilohash.com/freelance/freelancer.php?id=<?= $id ?>&amp;lang=en"/>
        <xhtml:link rel="alternate" hreflang="uk" href="https://bilohash.com/freelance/freelancer.php?id=<?= $id ?>&amp;lang=uk"/>
        <xhtml:link rel="alternate" hreflang="ru" href="https://bilohash.com/freelance/freelancer.php?id=<?= $id ?>&amp;lang=ru"/>
        <?php elseif (!empty($u['project_hreflang'])):
            $id = urlencode($u['project_hreflang']);
        ?>
        <xhtml:link rel="alternate" hreflang="x-default" href="https://bilohash.com/freelance/project.php?id=<?= $id ?>"/>
        <xhtml:link rel="alternate" hreflang="no" href="https://bilohash.com/freelance/project.php?id=<?= $id ?>"/>
        <xhtml:link rel="alternate" hreflang="en" href="https://bilohash.com/freelance/project.php?id=<?= $id ?>&amp;lang=en"/>
        <xhtml:link rel="alternate" hreflang="uk" href="https://bilohash.com/freelance/project.php?id=<?= $id ?>&amp;lang=uk"/>
        <xhtml:link rel="alternate" hreflang="ru" href="https://bilohash.com/freelance/project.php?id=<?= $id ?>&amp;lang=ru"/>
        <?php elseif (!empty($u['region_hreflang'])):
            $rs = htmlspecialchars($u['region_hreflang']);
        ?>
        <xhtml:link rel="alternate" hreflang="x-default" href="https://bilohash.com/freelance/<?= $rs ?>/"/>
        <xhtml:link rel="alternate" hreflang="no" href="https://bilohash.com/freelance/<?= $rs ?>/"/>
        <xhtml:link rel="alternate" hreflang="en" href="https://bilohash.com/freelance/<?= $rs ?>/?lang=en"/>
        <xhtml:link rel="alternate" hreflang="uk" href="https://bilohash.com/freelance/<?= $rs ?>/?lang=uk"/>
        <xhtml:link rel="alternate" hreflang="ru" href="https://bilohash.com/freelance/<?= $rs ?>/?lang=ru"/>
        <?php elseif (!empty($u['solution_hreflang'])):
            $ss = htmlspecialchars($u['solution_hreflang']);
        ?>
        <xhtml:link rel="alternate" hreflang="x-default" href="https://bilohash.com/freelance/<?= $ss ?>/"/>
        <xhtml:link rel="alternate" hreflang="no" href="https://bilohash.com/freelance/<?= $ss ?>/"/>
        <xhtml:link rel="alternate" hreflang="en" href="https://bilohash.com/freelance/<?= $ss ?>/?lang=en"/>
        <xhtml:link rel="alternate" hreflang="uk" href="https://bilohash.com/freelance/<?= $ss ?>/?lang=uk"/>
        <xhtml:link rel="alternate" hreflang="ru" href="https://bilohash.com/freelance/<?= $ss ?>/?lang=ru"/>
        <?php endif; ?>
    </url>
<?php endforeach; ?>
</urlset>