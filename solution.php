<?php
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/includes/solution-lib.php';

$slug = trim($_GET['slug'] ?? '');
$solution = fl_solution_by_slug($slug);
if (!$solution) {
    header('Location: ' . fl_url('solutions.php'), true, 302);
    exit;
}

$GLOBALS['solution_slug'] = $slug;
$v = fl_solution_lang($solution, $lang);
$page_title = $v['title'] ?? FL_SITE_NAME;
$page_desc  = $v['description'] ?? '';
$canonical  = fl_solution_canonical($slug);
$canon_abs  = fl_absolute_url($canonical);
$GLOBALS['solution_keywords'] = $v['keywords'] ?? null;

$hub_label = $t['solutions']['hub_title'] ?? 'Use cases';
$seo_schemas = [
    fl_seo_organization(),
    fl_seo_webpage($canon_abs, $page_title, $page_desc),
    fl_seo_breadcrumbs([
        ['name' => $t['breadcrumb_home'], 'url' => fl_absolute_url(fl_url('index.php'))],
        ['name' => $hub_label, 'url' => fl_absolute_url(fl_url('solutions.php'))],
        ['name' => $v['h1'] ?? $slug, 'url' => $canon_abs],
    ]),
    fl_seo_professional_service(),
    fl_seo_software_app($canon_abs, $page_desc),
    fl_seo_solution_service($canon_abs, $page_title, $page_desc, fl_solution_name($slug, $lang)),
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

require __DIR__ . '/includes/solution-template.php';