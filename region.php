<?php
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/includes/region-lib.php';

$slug = trim($_GET['slug'] ?? '');
$region = fl_region_by_slug($slug);
if (!$region) {
    header('Location: ' . fl_url('regions.php'), true, 302);
    exit;
}

$GLOBALS['region_slug'] = $slug;
$v = fl_region_lang($region, $lang);
$page_title = $v['title'] ?? FL_SITE_NAME;
$page_desc  = $v['description'] ?? '';
$canonical  = fl_region_canonical($slug);
$canon_abs  = fl_absolute_url($canonical);
$GLOBALS['region_keywords'] = $v['keywords'] ?? null;

$hub_label = $t['regions']['hub_title'] ?? 'Regions';
$seo_schemas = [
    fl_seo_organization(),
    fl_seo_webpage($canon_abs, $page_title, $page_desc),
    fl_seo_breadcrumbs([
        ['name' => $t['breadcrumb_home'], 'url' => fl_absolute_url(fl_url('index.php'))],
        ['name' => $hub_label, 'url' => fl_absolute_url(fl_url('regions.php'))],
        ['name' => $v['h1'] ?? $slug, 'url' => $canon_abs],
    ]),
    fl_seo_professional_service(),
    fl_seo_software_app($canon_abs, $page_desc),
];
if (!empty($v['faq'])) {
    $faq_items = [];
    foreach ($v['faq'] as $item) {
        $faq_items[] = [
            '@type' => 'Question',
            'name' => $item['q'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['a']],
        ];
    }
    $seo_schemas[] = [
        '@type' => 'FAQPage',
        'mainEntity' => $faq_items,
    ];
}

require __DIR__ . '/includes/region-template.php';