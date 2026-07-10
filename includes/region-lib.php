<?php

/** Replace every %s with $place; leaves strings without placeholders unchanged (PHP 8.3-safe). */
function fl_region_sprintf(string $fmt, string $place): string
{
    return str_contains($fmt, '%s') ? preg_replace('/%s/', $place, $fmt) : $fmt;
}

function fl_region_defs(): array
{
    static $defs = null;
    if ($defs === null) {
        $defs = require __DIR__ . '/../data/region-defs.php';
    }
    return $defs;
}

function fl_region_slugs(): array
{
    return array_keys(fl_region_defs());
}

function fl_regions_all(): array
{
    $defs = fl_region_defs();
    $tpl = [
        'no' => [
            'title' => 'Frilansplattform %s Norge | Freelance CMS',
            'description' => 'Freelance CMS — PHP frilans-markedsplass for %s og Norge. Demo med prosjekter, frilansprofiler, Simple/Pro-planer, admin og SEO.',
            'keywords' => 'frilans %s, freelance Norge, PHP markedsplass %s, Freelance CMS, bli frilanser %s',
            'subtitle' => 'Finn prosjekter og talent i %s',
            'intro' => 'Freelance CMS er en modulær PHP-løsning for frilans-markedsplasser i %s — prosjekter, tilbud, frilansprofiler med Simple og Pro-planer, adminpanel og flerspråklig SEO for Norge og Europa.',
            'cta' => 'Trenger du en frilansplattform for %s? Kontakt oss for tilbud.',
            'h1' => 'Freelance CMS — %s',
        ],
        'en' => [
            'title' => 'Freelance Platform %s Norway | Freelance CMS',
            'description' => 'Freelance CMS — PHP freelance marketplace for %s and Norway. Demo with projects, freelancer profiles, Simple/Pro tiers, admin and SEO.',
            'keywords' => 'freelance %s, freelance Norway, PHP marketplace %s, Freelance CMS, become freelancer %s',
            'subtitle' => 'Find projects and talent in %s',
            'intro' => 'Freelance CMS is a modular PHP solution for freelance marketplaces in %s — projects, proposals, freelancer profiles with Simple and Pro plans, admin panel and multilingual SEO for Norway and Europe.',
            'cta' => 'Need a freelance platform for %s? Contact us for a quote.',
            'h1' => 'Freelance CMS — %s',
        ],
        'uk' => [
            'title' => 'Фріланс-платформа %s | Freelance CMS',
            'description' => 'Freelance CMS — PHP фріланс-маркетплейс для %s та Норвегії. Демо з проєктами, профілями, тарифами Simple/Pro, адміном та SEO.',
            'keywords' => 'фріланс %s, freelance Норвегія, PHP маркетплейс %s, Freelance CMS, стати фрілансером %s',
            'subtitle' => 'Проєкти та таланти в %s',
            'intro' => 'Freelance CMS — модульне PHP-рішення для фріланс-маркетплейсів у %s: проєкти, пропозиції, профілі з тарифами Simple і Pro, адмін-панель та багатомовне SEO.',
            'cta' => 'Потрібна фріланс-платформа для %s? Зв\'яжіться з нами.',
            'h1' => 'Freelance CMS — %s',
        ],
        'ru' => [
            'title' => 'Фриланс-платформа %s | Freelance CMS',
            'description' => 'Freelance CMS — PHP фриланс-маркетплейс для %s и Норвегии. Демо с проектами, профилями, тарифами Simple/Pro, админом и SEO.',
            'keywords' => 'фриланс %s, freelance Норвегия, PHP маркетплейс %s, Freelance CMS, стать фрилансером %s',
            'subtitle' => 'Проекты и таланты в %s',
            'intro' => 'Freelance CMS — модульное PHP-решение для фриланс-маркетплейсов в %s: проекты, предложения, профили с тарифами Simple и Pro, админ-панель и мультиязычное SEO.',
            'cta' => 'Нужна фриланс-платформа для %s? Свяжитесь с нами.',
            'h1' => 'Freelance CMS — %s',
        ],
    ];
    $benefits = [
        'no' => [
            ['title' => 'Frilansprofiler', 'text' => 'Simple og Pro-planer, admin-aktivering, verifisering og featured listing.'],
            ['title' => 'Prosjekter & tilbud', 'text' => '12 demo-prosjekter, søk, kategorier og tilbudsskjema med JSON-lagring.'],
            ['title' => 'SEO for %s', 'text' => 'Dedikert landingsside, hreflang NO/EN/UA/RU, Schema.org og sitemap.'],
            ['title' => 'Mobil & nettbrett', 'text' => 'Fullt responsivt grensesnitt for telefon, nettbrett og desktop.'],
        ],
        'en' => [
            ['title' => 'Freelancer profiles', 'text' => 'Simple and Pro tiers, admin activation, verification and featured listing.'],
            ['title' => 'Projects & proposals', 'text' => '12 demo projects, search, categories and proposal form with JSON storage.'],
            ['title' => 'SEO for %s', 'text' => 'Dedicated landing page, hreflang NO/EN/UA/RU, Schema.org and sitemap.'],
            ['title' => 'Mobile & tablet', 'text' => 'Fully responsive UI for phone, tablet and desktop.'],
        ],
        'uk' => [
            ['title' => 'Профілі фрілансерів', 'text' => 'Тарифи Simple і Pro, активація адміном, верифікація та featured.'],
            ['title' => 'Проєкти та пропозиції', 'text' => '12 демо-проєктів, пошук, категорії та форма пропозицій.'],
            ['title' => 'SEO для %s', 'text' => 'Окремий лендінг, hreflang, Schema.org та sitemap.'],
            ['title' => 'Мобільний та планшет', 'text' => 'Повна адаптивність для телефону, планшета та ПК.'],
        ],
        'ru' => [
            ['title' => 'Профили фрилансеров', 'text' => 'Тарифы Simple и Pro, активация админом, верификация и featured.'],
            ['title' => 'Проекты и предложения', 'text' => '12 демо-проектов, поиск, категории и форма предложений.'],
            ['title' => 'SEO для %s', 'text' => 'Отдельный лендинг, hreflang, Schema.org и sitemap.'],
            ['title' => 'Мобильный и планшет', 'text' => 'Полная адаптивность для телефона, планшета и ПК.'],
        ],
    ];
    $features = [
        'no' => ['6+ demo-frilansprofiler', 'Registrering Simple/Pro', 'Admin: frilansere, brukere, innstillinger', 'Norske byer + Europa + Ukraina SEO', 'Mobil-først responsiv UI', 'Klar for Vipps/Stripe'],
        'en' => ['6+ demo freelancer profiles', 'Simple/Pro registration', 'Admin: freelancers, users, settings', 'Norwegian cities + Europe + Ukraine SEO', 'Mobile-first responsive UI', 'Vipps/Stripe ready'],
        'uk' => ['6+ демо-профілів', 'Реєстрація Simple/Pro', 'Адмін: фрілансери, користувачі', 'Норвезькі міста + Європа + Україна SEO', 'Mobile-first адаптивність', 'Готово до Vipps/Stripe'],
        'ru' => ['6+ демо-профилей', 'Регистрация Simple/Pro', 'Админ: фрилансеры, пользователи', 'Норвежские города + Европа + Украина SEO', 'Mobile-first адаптивность', 'Готово к Vipps/Stripe'],
    ];
    $out = [];
    foreach ($defs as $slug => $meta) {
        $name = ['no' => $meta['no'], 'en' => $meta['en'], 'uk' => $meta['uk'], 'ru' => $meta['ru']];
        $row = array_merge($meta, ['slug' => $slug, 'names' => $name]);
        foreach (['no', 'en', 'uk', 'ru'] as $lng) {
            $place = $name[$lng];
            $row['i18n'][$lng] = [];
            foreach ($tpl[$lng] as $k => $fmt) {
                $row['i18n'][$lng][$k] = fl_region_sprintf($fmt, $place);
            }
            $row['i18n'][$lng]['benefits'] = array_map(static function ($b) use ($place) {
                return [
                    'title' => fl_region_sprintf($b['title'], $place),
                    'text' => fl_region_sprintf($b['text'], $place),
                ];
            }, $benefits[$lng]);
            $row['i18n'][$lng]['features'] = $features[$lng];
            $row['i18n'][$lng]['faq'] = [
                ['q' => $lng === 'no' ? "Er dette en ekte frilansplattform i $place?" : ($lng === 'uk' ? "Це справжня платформа у $place?" : ($lng === 'ru' ? "Это реальная платформа в $place?" : "Is this a real marketplace in $place?")),
                 'a' => $lng === 'no' ? 'Nei — Freelance CMS er en universell PHP-demo. Alle data er eksempler.' : ($lng === 'uk' ? 'Ні — це PHP-демо з тестовими даними.' : ($lng === 'ru' ? 'Нет — это PHP-демо с тестовыми данными.' : 'No — Freelance CMS is a universal PHP demo with sample data.'))],
                ['q' => $lng === 'no' ? 'Kan jeg bli frilanser i demo?' : ($lng === 'uk' ? 'Чи можна зареєструватися?' : ($lng === 'ru' ? 'Можно зарегистрироваться?' : 'Can I register as freelancer?')),
                 'a' => $lng === 'no' ? 'Ja — testregistrering med Simple/Pro på /become-freelancer.php og /register.php.' : ($lng === 'uk' ? 'Так — демо-реєстрація на /become-freelancer.php та /register.php.' : ($lng === 'ru' ? 'Да — демо-регистрация на /become-freelancer.php и /register.php.' : 'Yes — demo registration at /become-freelancer.php and /register.php.'))],
            ];
        }
        $out[$slug] = $row;
    }
    return $out;
}

function fl_region_by_slug(string $slug): ?array
{
    $all = fl_regions_all();
    return $all[$slug] ?? null;
}

function fl_region_lang(array $region, string $lang): array
{
    return $region['i18n'][$lang] ?? $region['i18n']['en'] ?? [];
}

function fl_region_url(string $slug): string
{
    return fl_url($slug . '/');
}

function fl_region_canonical(string $slug): string
{
    global $site_url;
    return rtrim($site_url, '/') . '/' . $slug . '/';
}

function fl_region_icon(string $icon): string
{
    $map = [
        'city' => 'city',
        'mountain' => 'mountain',
        'oil-well' => 'industry',
        'university' => 'university',
        'water' => 'water',
        'sun' => 'sun',
        'snowflake' => 'snowflake',
        'globe-europe' => 'globe-europe',
        'flag' => 'flag',
    ];
    return $map[$icon] ?? 'map-marker-alt';
}