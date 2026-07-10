<?php

function fl_solution_sprintf(string $fmt, string ...$args): string
{
    if (!str_contains($fmt, '%')) {
        return $fmt;
    }
    $needed = substr_count($fmt, '%s');
    if ($needed === 0) {
        return $fmt;
    }
    while (count($args) < $needed) {
        $args[] = $args[0] ?? '';
    }
    return sprintf($fmt, ...array_slice($args, 0, $needed));
}

function fl_solution_defs(): array
{
    static $defs = null;
    if ($defs === null) {
        $path = __DIR__ . '/../data/solution-defs.php';
        $defs = is_file($path) ? (require $path) : [];
    }
    return is_array($defs) ? $defs : [];
}

function fl_solution_slugs(): array
{
    return array_keys(fl_solution_defs());
}

function fl_solution_by_slug(string $slug): ?array
{
    $all = fl_solutions_all();
    return $all[$slug] ?? null;
}

function fl_solution_name(string $slug, string $lang): string
{
    $def = fl_solution_defs()[$slug] ?? null;
    if (!$def) {
        return $slug;
    }
    return $def['names'][$lang] ?? $def['names']['en'] ?? $slug;
}

function fl_solution_url(string $slug): string
{
    return fl_url($slug . '/');
}

function fl_solution_canonical(string $slug): string
{
    global $site_url;
    return rtrim($site_url, '/') . '/' . $slug . '/';
}

function fl_solution_icon(string $icon): string
{
    if ($icon === 'wordpress') {
        return 'wordpress';
    }
    return $icon;
}

function fl_solution_demo_url(array $def): string
{
    $cat = $def['search_category'] ?? '';
    if ($cat !== '') {
        return fl_url('search.php?category=' . urlencode($cat));
    }
    return fl_url('search.php');
}

function fl_solution_templates(): array
{
    return [
        'en' => [
            'title' => 'Freelance marketplace for %s | Freelance CMS Norway',
            'description' => 'Order a custom PHP freelance job board for %s. Upwork-style proposals, admin panel, multilingual SEO, Schema.org — live demo from Norway & Europe.',
            'keywords' => '%s, freelance marketplace Norway, PHP job board Europe, order freelance platform, Upwork clone CMS, talent marketplace %s',
            'h1' => 'Freelance CMS for %s',
            'subtitle' => 'White-label PHP marketplace — projects, proposals, profiles and admin',
            'cta' => 'Order a custom %s marketplace — contact Bilohash',
            'benefits' => [
                ['title' => 'Niche-ready categories', 'text' => 'Pre-built category filters, skill tags and search tuned for %s workflows.'],
                ['title' => 'Multilingual SEO', 'text' => 'NO, EN, UK, RU with hreflang, Schema.org JobPosting and region landing pages.'],
                ['title' => 'Admin & JSON storage', 'text' => 'Manage projects, freelancers, proposals and settings without a heavy framework.'],
                ['title' => 'Mobile-first UI', 'text' => 'Responsive emerald theme — burger nav, cards and forms on every device.'],
            ],
            'features' => [
                '12 demo projects with budgets and deadlines',
                '6+ freelancer profiles — Simple & Pro tiers',
                'Proposal form with JSON logging',
                'Categories dropdown + skill-tag internal SEO',
                'reCAPTCHA contact form for lead capture',
                'Optional Grok / OpenAI sales chat widget',
            ],
            'faq_base' => [
                ['q' => 'Is Freelance CMS a real %s platform?', 'a' => 'It is a universal PHP demo — sample data only. Production builds add payments, contracts and your branding.'],
                ['q' => 'How fast can we launch for %s?', 'a' => 'Demo deploys in minutes on PHP 8+ hosting. Custom categories, fields and design typically take 2–6 weeks depending on scope.'],
            ],
        ],
        'no' => [
            'title' => 'Frilansmarked for %s | Freelance CMS Norge',
            'description' => 'Bestill skreddersydd PHP frilans-jobboard for %s. Upwork-lignende tilbud, admin, flerspråklig SEO og Schema.org — live demo fra Norge.',
            'keywords' => '%s, frilansmarked Norge, PHP jobboard Europa, bestill frilansplattform, Upwork clone, talentplattform %s',
            'h1' => 'Freelance CMS for %s',
            'subtitle' => 'White-label PHP-markedsplass — prosjekter, tilbud, profiler og admin',
            'cta' => 'Bestill tilpasset %s-markedsplass — kontakt Bilohash',
            'benefits' => [
                ['title' => 'Nisjekategorier', 'text' => 'Ferdige filtre, ferdighetstagger og søk tilpasset %s.'],
                ['title' => 'Flerspråklig SEO', 'text' => 'NO, EN, UK, RU med hreflang, Schema.org JobPosting og regionssider.'],
                ['title' => 'Admin & JSON', 'text' => 'Administrer prosjekter, frilansere og tilbud uten tungt rammeverk.'],
                ['title' => 'Mobil-først', 'text' => 'Responsivt emerald-tema — burgermeny, kort og skjema på alle enheter.'],
            ],
            'features' => [
                '12 demoprosjekter med budsjett og frister',
                '6+ frilansprofiler — Simple & Pro',
                'Tilbudsskjema med JSON-lagring',
                'Kategorier + klikkbare ferdighetstagger',
                'reCAPTCHA kontaktskjema',
                'Valgfri Grok / OpenAI salgschat',
            ],
            'faq_base' => [
                ['q' => 'Er dette en ekte %s-plattform?', 'a' => 'Det er en universell PHP-demo med eksempeldata. Produksjon legger til betaling, kontrakter og merkevare.'],
                ['q' => 'Hvor raskt kan vi lansere for %s?', 'a' => 'Demo på minutter på PHP 8+. Tilpassede felt og design tar vanligvis 2–6 uker.'],
            ],
        ],
        'uk' => [
            'title' => 'Фріланс-маркетплейс для %s | Freelance CMS',
            'description' => 'Замовте PHP job board для %s. Пропозиції у стилі Upwork, адмін, багатомовне SEO та Schema.org — live demo з Норвегії.',
            'keywords' => '%s, фріланс маркетплейс Норвегія, PHP job board Європа, замовити фріланс платформу, Upwork clone, %s',
            'h1' => 'Freelance CMS для %s',
            'subtitle' => 'White-label PHP-маркетплейс — проєкти, пропозиції, профілі та адмін',
            'cta' => 'Замовити маркетплейс для %s — зв\'яжіться з Bilohash',
            'benefits' => [
                ['title' => 'Нішеві категорії', 'text' => 'Готові фільтри, теги навичок і пошук для %s.'],
                ['title' => 'Багатомовне SEO', 'text' => 'NO, EN, UK, RU з hreflang, Schema.org JobPosting та регіонами.'],
                ['title' => 'Адмін і JSON', 'text' => 'Керування проєктами, фрілансерами та пропозиціями без важкого фреймворку.'],
                ['title' => 'Mobile-first', 'text' => 'Адаптивна emerald-тема на всіх пристроях.'],
            ],
            'features' => [
                '12 демо-проєктів з бюджетами',
                '6+ профілів — Simple & Pro',
                'Форма пропозицій з JSON',
                'Категорії + клікабельні теги навичок',
                'reCAPTCHA контактна форма',
                'Опційний Grok / OpenAI чат',
            ],
            'faq_base' => [
                ['q' => 'Це справжня платформа для %s?', 'a' => 'Це універсальне PHP-демо з тестовими даними. Продакшен додає оплату та брендинг.'],
                ['q' => 'Як швидко запустити для %s?', 'a' => 'Демо за хвилини на PHP 8+. Кастомізація зазвичай 2–6 тижнів.'],
            ],
        ],
        'ru' => [
            'title' => 'Фриланс-маркетплейс для %s | Freelance CMS',
            'description' => 'Закажите PHP job board для %s. Предложения в стиле Upwork, админ, мультиязычное SEO и Schema.org — live demo из Норвегии.',
            'keywords' => '%s, фриланс маркетплейс Норвегия, PHP job board Европа, заказать фриланс платформу, Upwork clone, %s',
            'h1' => 'Freelance CMS для %s',
            'subtitle' => 'White-label PHP-маркетплейс — проекты, предложения, профили и админ',
            'cta' => 'Заказать маркетплейс для %s — связаться с Bilohash',
            'benefits' => [
                ['title' => 'Нишевые категории', 'text' => 'Готовые фильтры, теги навыков и поиск для %s.'],
                ['title' => 'Мультиязычное SEO', 'text' => 'NO, EN, UK, RU с hreflang, Schema.org JobPosting и регионами.'],
                ['title' => 'Админ и JSON', 'text' => 'Управление проектами, фрилансерами и предложениями без тяжёлого фреймворка.'],
                ['title' => 'Mobile-first', 'text' => 'Адаптивная emerald-тема на всех устройствах.'],
            ],
            'features' => [
                '12 демо-проектов с бюджетами',
                '6+ профилей — Simple & Pro',
                'Форма предложений с JSON',
                'Категории + кликабельные теги навыков',
                'reCAPTCHA контактная форма',
                'Опциональный Grok / OpenAI чат',
            ],
            'faq_base' => [
                ['q' => 'Это реальная платформа для %s?', 'a' => 'Это универсальное PHP-демо с тестовыми данными. Продакшен добавляет оплату и брендинг.'],
                ['q' => 'Как быстро запустить для %s?', 'a' => 'Демо за минуты на PHP 8+. Кастомизация обычно 2–6 недель.'],
            ],
        ],
    ];
}

function fl_solutions_all(): array
{
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }

    $defs = fl_solution_defs();
    $tpls = fl_solution_templates();
    $out = [];

    foreach ($defs as $slug => $meta) {
        $row = array_merge($meta, ['slug' => $slug, 'i18n' => []]);
        foreach (['no', 'en', 'uk', 'ru'] as $lng) {
            $name = $meta['names'][$lng] ?? $meta['names']['en'];
            $tpl = $tpls[$lng] ?? $tpls['en'];
            $benefits = array_map(static function ($b) use ($name) {
                return [
                    'title' => fl_solution_sprintf($b['title'], $name),
                    'text'  => fl_solution_sprintf($b['text'], $name),
                ];
            }, $tpl['benefits']);

            $faq = array_map(static function ($f) use ($name) {
                return [
                    'q' => fl_solution_sprintf($f['q'], $name),
                    'a' => fl_solution_sprintf($f['a'], $name),
                ];
            }, $tpl['faq_base']);
            foreach ($meta['faq_extra'][$lng] ?? $meta['faq_extra']['en'] ?? [] as $extra) {
                $faq[] = $extra;
            }

            $row['i18n'][$lng] = [
                'title'       => $meta['seo_title'][$lng] ?? fl_solution_sprintf($tpl['title'], $name),
                'description' => $meta['seo_description'][$lng] ?? fl_solution_sprintf($tpl['description'], $name),
                'keywords'    => $meta['seo_keywords'][$lng] ?? fl_solution_sprintf($tpl['keywords'], $name),
                'h1'          => fl_solution_sprintf($tpl['h1'], $name),
                'subtitle'    => $tpl['subtitle'],
                'intro'       => $meta['intro'][$lng] ?? $meta['intro']['en'],
                'cta'         => fl_solution_sprintf($tpl['cta'], $name),
                'benefits'    => $benefits,
                'features'    => array_merge(
                    $tpl['features'],
                    $meta['features_extra'][$lng] ?? $meta['features_extra']['en'] ?? []
                ),
                'faq'         => $faq,
            ];
        }
        $out[$slug] = $row;
    }

    $cache = $out;
    return $out;
}

function fl_solution_lang(array $solution, string $lang): array
{
    return $solution['i18n'][$lang] ?? $solution['i18n']['en'] ?? [];
}

function fl_render_solution_tags(int $limit = 0): void
{
    global $lang, $t;
    $slugs = fl_solution_slugs();
    if ($limit > 0) {
        $slugs = array_slice($slugs, 0, $limit);
    }
    foreach ($slugs as $slug) {
        $name = fl_solution_name($slug, $lang);
        $title = fl_solution_sprintf($t['solutions']['tag_title'] ?? '%s — Freelance CMS', $name);
        ?>
        <a href="<?= htmlspecialchars(fl_solution_url($slug)) ?>" class="fl-skill-tag" title="<?= htmlspecialchars($title) ?>"><?= htmlspecialchars($name) ?></a>
        <?php
    }
}