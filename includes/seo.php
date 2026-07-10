<?php

function fl_protocol(): string
{
    return (
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')
    ) ? 'https' : 'http';
}

function fl_host(): string
{
    return $_SERVER['HTTP_HOST'] ?? FL_DOMAIN;
}

function fl_absolute_url(string $path): string
{
    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
        return $path;
    }
    return fl_protocol() . '://' . fl_host() . (str_starts_with($path, '/') ? $path : '/' . $path);
}

function fl_full_lang_url(string $code): string
{
    return fl_absolute_url(fl_lang_url($code, true));
}

function fl_seo_og_image(): string
{
    return fl_absolute_url(fl_asset('images/placeholder.svg'));
}

function fl_seo_author(): array
{
    return [
        '@type' => 'Organization',
        'name'  => FL_SITE_NAME,
        'url'   => 'https://bilohash.com/freelance/',
    ];
}

function fl_seo_organization(): array
{
    return [
        '@type' => 'Organization',
        '@id'   => 'https://bilohash.com/freelance/#organization',
        'name'  => FL_SITE_NAME,
        'url'   => 'https://bilohash.com/freelance/',
        'logo'  => 'https://bilohash.com/favicon.ico',
        'areaServed' => ['NO', 'UA', 'RU', 'EU'],
        'knowsAbout' => [
            'PHP freelance marketplace scripts',
            'Online job board platforms',
            'Freelance website development Norway',
            'Multilingual SEO',
            'Schema.org structured data',
        ],
    ];
}

function fl_seo_software_app(string $canonical, string $description): array
{
    return [
        '@type'               => 'SoftwareApplication',
        '@id'                 => $canonical . '#software',
        'name'                => FL_SITE_NAME,
        'applicationCategory' => 'BusinessApplication',
        'applicationSubCategory' => 'Freelance marketplace and job board software',
        'operatingSystem'     => 'Web',
        'description'         => $description,
        'url'                 => $canonical,
        'image'               => fl_seo_og_image(),
        'inLanguage'          => ['nb-NO', 'en-GB', 'uk-UA', 'ru-RU'],
        'offers'              => [
            '@type'         => 'Offer',
            'price'         => '0',
            'priceCurrency' => 'NOK',
            'availability'  => 'https://schema.org/InStock',
            'url'           => 'https://bilohash.com/freelance/',
        ],
        'author'    => fl_seo_author(),
        'publisher' => fl_seo_organization(),
        'featureList' => 'Multilingual frontend, project search, proposal form, admin panel, JSON storage, Schema.org SEO, responsive UI',
    ];
}

function fl_seo_website(string $canonical): array
{
    global $site_url;
    return [
        '@type' => 'WebSite',
        '@id'   => rtrim($site_url, '/') . '/#website',
        'name'  => FL_SITE_NAME,
        'url'   => rtrim($site_url, '/') . '/',
        'inLanguage' => ['nb-NO', 'en-GB', 'uk-UA', 'ru-RU'],
        'publisher' => ['@id' => 'https://bilohash.com/freelance/#organization'],
        'potentialAction' => [
            '@type'       => 'SearchAction',
            'target'      => [
                '@type'       => 'EntryPoint',
                'urlTemplate' => rtrim($site_url, '/') . '/search.php?q={search_term_string}',
            ],
            'query-input' => 'required name=search_term_string',
        ],
    ];
}

function fl_seo_webpage(string $canonical, string $title, string $description): array
{
    global $lang;
    return [
        '@type'       => 'WebPage',
        '@id'         => $canonical . '#webpage',
        'url'         => $canonical,
        'name'        => $title,
        'description' => $description,
        'isPartOf'    => ['@id' => rtrim($GLOBALS['site_url'], '/') . '/#website'],
        'about'       => ['@id' => rtrim($GLOBALS['site_url'], '/') . '/#software'],
        'inLanguage'  => fl_langs()[$lang]['locale'] ?? 'en-GB',
    ];
}

function fl_seo_breadcrumbs(array $items): array
{
    $list = [];
    $pos = 1;
    foreach ($items as $item) {
        $entry = [
            '@type'    => 'ListItem',
            'position' => $pos++,
            'name'     => $item['name'],
        ];
        if (!empty($item['url'])) {
            $entry['item'] = $item['url'];
        }
        $list[] = $entry;
    }
    return [
        '@type'           => 'BreadcrumbList',
        'itemListElement' => $list,
    ];
}

function fl_seo_project(array $project, string $lang, string $canonical): array
{
    $name = fl_localized($project, 'name', $lang);
    $desc = fl_localized($project, 'desc', $lang);
    $city = fl_localized($project, 'city', $lang);
    $country = fl_localized($project, 'country', $lang);
    $budget = (int)($project['budget_max'] ?? $project['budget_min'] ?? 0);
    $deadline = fl_project_deadline($project);

    return [
        '@type' => 'JobPosting',
        '@id'   => $canonical . '#job',
        'title' => $name,
        'description' => $desc,
        'url'   => $canonical,
        'image' => fl_project_image($project),
        'datePosted' => date('c', fl_project_posted_at($project)),
        'validThrough' => date('c', $deadline),
        'employmentType' => 'CONTRACTOR',
        'jobLocation' => [
            '@type' => 'Place',
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => $city,
                'addressCountry' => $country,
            ],
        ],
        'hiringOrganization' => fl_seo_organization(),
        'baseSalary' => [
            '@type' => 'MonetaryAmount',
            'currency' => FL_CURRENCY,
            'value' => [
                '@type' => 'QuantitativeValue',
                'value' => (string) $budget,
                'unitText' => ($project['budget_type'] ?? 'fixed') === 'hourly' ? 'HOUR' : 'PROJECT',
            ],
        ],
        'identifier' => [
            '@type' => 'PropertyValue',
            'name'  => 'Project ID',
            'value' => $project['id'] ?? '',
        ],
        'applicantLocationRequirements' => [
            '@type' => 'Country',
            'name'  => 'Remote / EU',
        ],
    ];
}

function fl_seo_item_list(array $projects, string $lang, string $listUrl): array
{
    $elements = [];
    $pos = 1;
    foreach (array_slice($projects, 0, 20) as $p) {
        $url = fl_absolute_url(fl_url('project.php?id=' . urlencode($p['id'])));
        $elements[] = [
            '@type'    => 'ListItem',
            'position' => $pos++,
            'url'      => $url,
            'name'     => fl_localized($p, 'name', $lang),
        ];
    }
    return [
        '@type'           => 'ItemList',
        'url'             => $listUrl,
        'numberOfItems'   => count($projects),
        'itemListElement' => $elements,
    ];
}

function fl_seo_professional_service(): array
{
    return [
        '@type' => 'ProfessionalService',
        '@id'   => 'https://bilohash.com/freelance/#service',
        'name'  => 'Custom freelance marketplace development — Norway & Europe',
        'url'   => 'https://bilohash.com/freelance/',
        'description' => 'Order development of freelance job boards and PHP marketplace scripts. Upwork/Fiverr style platforms for web, design, AI and more. Norway and Europe.',
        'areaServed' => ['Norway', 'Ukraine', 'Lithuania', 'Europe'],
        'provider' => fl_seo_organization(),
    ];
}

function fl_seo_solution_service(string $canonical, string $title, string $description, string $audience): array
{
    return [
        '@type' => 'Service',
        '@id'   => $canonical . '#service',
        'name'  => $title,
        'description' => $description,
        'url'   => $canonical,
        'serviceType' => 'Freelance marketplace software development',
        'audience' => [
            '@type' => 'Audience',
            'audienceType' => $audience,
        ],
        'areaServed' => ['Norway', 'Europe', 'Ukraine'],
        'provider' => fl_seo_organization(),
        'offers' => [
            '@type' => 'Offer',
            'url' => 'https://bilohash.com/freelance/contact.php',
            'priceCurrency' => 'NOK',
            'availability' => 'https://schema.org/InStock',
        ],
    ];
}

function fl_seo_item_list_solutions(string $lang, string $listUrl): array
{
    if (!function_exists('fl_solutions_all')) {
        require_once __DIR__ . '/solution-lib.php';
    }
    $elements = [];
    $pos = 1;
    foreach (fl_solutions_all() as $slug => $item) {
        $lv = fl_solution_lang($item, $lang);
        $elements[] = [
            '@type'    => 'ListItem',
            'position' => $pos++,
            'url'      => fl_absolute_url(fl_solution_url($slug)),
            'name'     => $lv['h1'] ?? fl_solution_name($slug, $lang),
        ];
    }
    return [
        '@type'           => 'ItemList',
        'url'             => $listUrl,
        'numberOfItems'   => count($elements),
        'itemListElement' => $elements,
    ];
}

function fl_seo_json(array $graphs): string
{
    $graphs = array_values(array_filter($graphs));
    if (count($graphs) === 1) {
        $data = array_merge(['@context' => 'https://schema.org'], $graphs[0]);
    } else {
        $data = [
            '@context' => 'https://schema.org',
            '@graph'   => $graphs,
        ];
    }
    return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}

function fl_seo_person_profile(array $freelancer, string $canonical, string $lang): array
{
    return [
        '@type' => 'Person',
        '@id' => $canonical . '#person',
        'name' => fl_localized($freelancer, 'name', $lang),
        'jobTitle' => fl_localized($freelancer, 'title', $lang),
        'description' => fl_localized($freelancer, 'bio', $lang),
        'image' => fl_freelancer_image($freelancer),
        'url' => $canonical,
        'address' => [
            '@type' => 'PostalAddress',
            'addressLocality' => fl_localized($freelancer, 'city', $lang),
            'addressCountry' => fl_localized($freelancer, 'country', $lang),
        ],
        'knowsAbout' => $freelancer['skills'] ?? [],
    ];
}

function fl_render_seo_head(
    string $page_title,
    string $page_desc,
    string $canonical,
    array $schema_graphs = [],
    ?string $og_image = null,
    ?string $og_type = 'website',
    bool $noindex = false
): void {
    global $lang_meta, $lang;
    $og_image = $og_image ?: fl_seo_og_image();
    $canonical_abs = fl_absolute_url($canonical);
    $keywords = $GLOBALS['solution_keywords'] ?? $GLOBALS['region_keywords'] ?? ($GLOBALS['t']['meta']['keywords'] ?? '');
    $robots = $noindex ? 'noindex, nofollow' : 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';
    ?>
    <title><?= htmlspecialchars($page_title) ?></title>
    <meta name="description" content="<?= htmlspecialchars($page_desc) ?>">
    <?php if ($keywords !== ''): ?>
    <meta name="keywords" content="<?= htmlspecialchars($keywords) ?>">
    <?php endif; ?>
    <meta name="robots" content="<?= $robots ?>">
    <meta name="author" content="Freelance CMS">
    <meta name="geo.region" content="NO">
    <meta name="geo.placename" content="Norway">
    <link rel="canonical" href="<?= htmlspecialchars($canonical_abs) ?>">
    <link rel="alternate" hreflang="x-default" href="<?= htmlspecialchars(fl_full_lang_url('no')) ?>">
    <?php foreach (fl_langs() as $code => $info): ?>
    <link rel="alternate" hreflang="<?= $code === 'uk' ? 'uk' : $code ?>" href="<?= htmlspecialchars(fl_full_lang_url($code)) ?>">
    <?php endforeach; ?>
    <meta property="og:type" content="<?= htmlspecialchars($og_type) ?>">
    <meta property="og:title" content="<?= htmlspecialchars($page_title) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($page_desc) ?>">
    <meta property="og:url" content="<?= htmlspecialchars($canonical_abs) ?>">
    <meta property="og:site_name" content="<?= htmlspecialchars(FL_SITE_NAME) ?>">
    <meta property="og:image" content="<?= htmlspecialchars($og_image) ?>">
    <meta property="og:image:alt" content="<?= htmlspecialchars(FL_SITE_NAME) ?>">
    <meta property="og:locale" content="<?= htmlspecialchars(str_replace('-', '_', $lang_meta['locale'])) ?>">
    <?php foreach (fl_langs() as $code => $info):
        if ($code === $lang) continue; ?>
    <meta property="og:locale:alternate" content="<?= htmlspecialchars(str_replace('-', '_', $info['locale'])) ?>">
    <?php endforeach; ?>
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($page_title) ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($page_desc) ?>">
    <meta name="twitter:image" content="<?= htmlspecialchars($og_image) ?>">

    <?php if (!empty($schema_graphs)): ?>
    <script type="application/ld+json"><?= fl_seo_json(array_values(array_filter($schema_graphs))) ?></script>
    <?php endif;
}