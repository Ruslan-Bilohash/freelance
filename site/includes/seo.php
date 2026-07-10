<?php

function fls_render_seo_head(string $page_title, string $page_desc, string $canonical, array $schemas = []): void
{
    global $lang_meta, $t;
    $canonical_abs = fls_absolute_url($canonical);
    $keywords = $t['meta']['keywords'] ?? '';
    ?>
    <title><?= htmlspecialchars($page_title) ?></title>
    <meta name="description" content="<?= htmlspecialchars($page_desc) ?>">
    <?php if ($keywords): ?><meta name="keywords" content="<?= htmlspecialchars($keywords) ?>"><?php endif; ?>
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= htmlspecialchars($canonical_abs) ?>">
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= htmlspecialchars($page_title) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($page_desc) ?>">
    <meta property="og:url" content="<?= htmlspecialchars($canonical_abs) ?>">
    <meta property="og:site_name" content="<?= htmlspecialchars(FLS_PRODUCT_NAME) ?>">
    <?php if (!empty($schemas)): ?>
    <script type="application/ld+json"><?= json_encode(['@context'=>'https://schema.org','@graph'=>$schemas], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) ?></script>
    <?php endif;
}

function fls_seo_schemas(string $canonical, string $title, string $desc): array
{
    return [
        ['@type'=>'Organization','name'=>FLS_PRODUCT_NAME,'url'=>$canonical],
        ['@type'=>'SoftwareApplication','name'=>FLS_PRODUCT_NAME,'description'=>$desc,'url'=>$canonical,'applicationCategory'=>'BusinessApplication'],
        ['@type'=>'WebPage','name'=>$title,'description'=>$desc,'url'=>$canonical],
    ];
}