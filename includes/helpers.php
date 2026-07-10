<?php

if (!function_exists('bh_str_lower')) {
    function bh_str_lower(string $str): string
    {
        return function_exists('mb_strtolower') ? mb_strtolower($str) : strtolower($str);
    }
}

if (!function_exists('bh_str_sub')) {
    function bh_str_sub(string $str, int $start, ?int $length = null): string
    {
        if (function_exists('mb_substr')) {
            return $length === null ? mb_substr($str, $start) : mb_substr($str, $start, $length);
        }
        return $length === null ? substr($str, $start) : substr($str, $start, $length);
    }
}

function fl_active_projects(): array
{
    return fl_projects(false);
}

function fl_projects(bool $include_inactive = false): array
{
    require_once __DIR__ . '/storage.php';
    $list = fl_load_projects_raw();
    if (!$include_inactive) {
        $list = array_values(array_filter($list, fn($p) => ($p['active'] ?? true) !== false));
    }
    return $list;
}

function fl_projects_all(): array
{
    return fl_projects(true);
}

function fl_project_by_id(string $id, bool $include_inactive = false): ?array
{
    foreach (fl_projects($include_inactive) as $p) {
        if ($p['id'] === $id) {
            return $p;
        }
    }
    return null;
}

function fl_localized(array $item, string $field, string $lang): string
{
    $val = $item[$field] ?? '';
    if (is_array($val)) {
        return $val[$lang] ?? $val['ru'] ?? $val['uk'] ?? $val['en'] ?? $val['no'] ?? '';
    }
    return (string) $val;
}

function fl_categories(): array
{
    return ['web', 'design', 'ai', 'wordpress', 'mobile', 'marketing'];
}

function fl_experience_levels(): array
{
    return ['entry', 'intermediate', 'expert'];
}

function fl_search_params(): array
{
    return [
        'q'         => trim($_GET['q'] ?? ''),
        'category'  => $_GET['category'] ?? '',
        'status'    => $_GET['status'] ?? '',
        'sort'      => $_GET['sort'] ?? 'newest',
        'min_budget'=> (int)($_GET['min_budget'] ?? 0),
        'max_budget'=> (int)($_GET['max_budget'] ?? 0),
        'level'     => $_GET['level'] ?? '',
    ];
}

function fl_project_deadline(array $project): int
{
    $raw = $project['deadline'] ?? '';
    $ts = strtotime($raw);
    return $ts !== false ? $ts : time() + 86400 * 14;
}

function fl_project_posted_at(array $project): int
{
    $raw = $project['posted_at'] ?? '';
    $ts = strtotime($raw);
    return $ts !== false ? $ts : time() - 86400;
}

function fl_project_is_open(array $project): bool
{
    return fl_project_deadline($project) > time() && ($project['status'] ?? 'open') === 'open';
}

function fl_filter_projects(array $params, string $lang): array
{
    $items = fl_projects();
    $q = bh_str_lower($params['q']);

    if ($q !== '') {
        $items = array_filter($items, function ($p) use ($q, $lang) {
            $skills = implode(' ', $p['skills'] ?? []);
            $hay = bh_str_lower(
                fl_localized($p, 'name', $lang) . ' '
                . fl_localized($p, 'desc', $lang) . ' '
                . fl_localized($p, 'city', $lang) . ' '
                . fl_localized($p, 'country', $lang) . ' '
                . fl_localized($p, 'client', $lang) . ' '
                . $skills
            );
            return str_contains($hay, $q);
        });
    }

    if ($params['category'] !== '' && in_array($params['category'], fl_categories(), true)) {
        $items = array_filter($items, fn($p) => ($p['category'] ?? '') === $params['category']);
    }

    if ($params['status'] === 'open') {
        $items = array_filter($items, fn($p) => fl_project_is_open($p));
    } elseif ($params['status'] === 'urgent') {
        $items = array_filter($items, function ($p) {
            $left = fl_project_deadline($p) - time();
            return fl_project_is_open($p) && $left > 0 && $left <= 7 * 86400;
        });
    }

    if ($params['level'] !== '' && in_array($params['level'], fl_experience_levels(), true)) {
        $items = array_filter($items, fn($p) => ($p['experience_level'] ?? '') === $params['level']);
    }

    if ($params['min_budget'] > 0) {
        $items = array_filter($items, fn($p) => (int)($p['budget_max'] ?? 0) >= $params['min_budget']);
    }
    if ($params['max_budget'] > 0) {
        $items = array_filter($items, fn($p) => (int)($p['budget_min'] ?? 0) <= $params['max_budget']);
    }

    $items = array_values($items);

    usort($items, function ($a, $b) use ($params) {
        return match ($params['sort']) {
            'budget_low'  => ($a['budget_max'] ?? 0) <=> ($b['budget_max'] ?? 0),
            'budget_high' => ($b['budget_max'] ?? 0) <=> ($a['budget_max'] ?? 0),
            'proposals'   => ($a['proposals_count'] ?? 0) <=> ($b['proposals_count'] ?? 0),
            'deadline'    => fl_project_deadline($a) <=> fl_project_deadline($b),
            default       => fl_project_posted_at($b) <=> fl_project_posted_at($a),
        };
    });

    return $items;
}

function fl_open_projects(): array
{
    return array_values(array_filter(fl_projects(), fn($p) => fl_project_is_open($p)));
}

function fl_urgent_projects(int $limit = 4): array
{
    $open = fl_open_projects();
    usort($open, fn($a, $b) => fl_project_deadline($a) <=> fl_project_deadline($b));
    return array_slice($open, 0, $limit);
}

function fl_featured_projects(int $limit = 6): array
{
    $featured = array_values(array_filter(fl_projects(), fn($p) => !empty($p['featured']) && fl_project_is_open($p)));
    return array_slice($featured, 0, $limit);
}

function fl_lang_url(string $code, bool $for_hreflang = false): string
{
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: fl_url('index.php');
    parse_str(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_QUERY) ?? '', $q);
    if ($code === 'no' && $for_hreflang) {
        unset($q['lang']);
    } else {
        $q['lang'] = $code;
    }
    $qs = http_build_query($q);
    return $path . ($qs !== '' ? '?' . $qs : '');
}

function fl_placeholder_image(): string
{
    return fl_asset('images/placeholder.svg');
}

function fl_project_image(array $project): string
{
    return trim($project['image'] ?? '') ?: fl_placeholder_image();
}

function fl_category_counts(): array
{
    $counts = array_fill_keys(fl_categories(), 0);
    foreach (fl_projects() as $p) {
        $cat = $p['category'] ?? '';
        if (isset($counts[$cat])) {
            $counts[$cat]++;
        }
    }
    return $counts;
}

function fl_project_long_desc(array $project, string $lang): string
{
    $long = fl_localized($project, 'long_desc', $lang);
    if ($long !== '') {
        return $long;
    }
    return fl_localized($project, 'desc', $lang);
}

function fl_project_client(array $project, string $lang): string
{
    $client = fl_localized($project, 'client', $lang);
    return $client !== '' ? $client : match ($lang) {
        'no' => 'Verifisert klient (demo)',
        'uk' => 'Верифікований клієнт (демо)',
        'ru' => 'Верифицированный клиент (демо)',
        default => 'Verified client (demo)',
    };
}

function fl_project_highlights(array $project, string $lang): array
{
    if (!empty($project['highlights'][$lang]) && is_array($project['highlights'][$lang])) {
        return $project['highlights'][$lang];
    }
    if (!empty($project['highlights']['en']) && is_array($project['highlights']['en'])) {
        return $project['highlights']['en'];
    }
    $cat = $project['category'] ?? '';
    return match ($lang) {
        'no' => match ($cat) {
            'web' => ['Responsivt design', 'Moderne stack', 'Dokumentasjon inkludert'],
            'design' => ['Figma-kildefiler', 'Merkevareguide', 'Ubegrensede revisjoner (demo)'],
            'ai' => ['LLM-integrasjon', 'GDPR-vennlig', 'Evalueringspipeline'],
            default => ['Milepælsbasert levering', 'Sikker demoflyt', 'NOK-budsjetter'],
        },
        'uk' => match ($cat) {
            'web' => ['Адаптивний дизайн', 'Сучасний стек', 'Документація включена'],
            'design' => ['Figma-файли', 'Брендбук', 'Необмежені правки (демо)'],
            'ai' => ['Інтеграція LLM', 'GDPR-сумісність', 'Пайплайн оцінки'],
            default => ['Доставка за етапами', 'Безпечне демо', 'Бюджети в NOK'],
        },
        'ru' => match ($cat) {
            'web' => ['Адаптивный дизайн', 'Современный стек', 'Документация включена'],
            'design' => ['Figma-файлы', 'Брендбук', 'Неограниченные правки (демо)'],
            'ai' => ['Интеграция LLM', 'GDPR-совместимость', 'Пайплайн оценки'],
            default => ['Поэтапная сдача', 'Безопасное демо', 'Бюджеты в NOK'],
        },
        default => match ($cat) {
            'web' => ['Responsive design', 'Modern stack', 'Documentation included'],
            'design' => ['Figma source files', 'Brand guidelines', 'Unlimited revisions (demo)'],
            'ai' => ['LLM integration', 'GDPR-friendly', 'Evaluation pipeline'],
            default => ['Milestone-based delivery', 'Secure demo flow', 'NOK budgets'],
        },
    };
}

function fl_related_projects(array $project, int $limit = 4): array
{
    $cat = $project['category'] ?? '';
    $related = array_values(array_filter(fl_projects(), fn($p) => ($p['id'] ?? '') !== ($project['id'] ?? '') && ($p['category'] ?? '') === $cat && fl_project_is_open($p)));
    if (count($related) < $limit) {
        $extra = array_values(array_filter(fl_projects(), fn($p) => ($p['id'] ?? '') !== ($project['id'] ?? '') && fl_project_is_open($p)));
        $related = array_merge($related, $extra);
    }
    return array_slice($related, 0, $limit);
}

function fl_platform_stats(): array
{
    require_once __DIR__ . '/storage.php';
    $projects = fl_projects();
    $proposals = fl_load_proposals();
    $freelancers = fl_freelancers();
    return [
        'projects'     => count($projects),
        'open'         => count(fl_open_projects()),
        'proposals'    => count($proposals),
        'volume'       => array_sum(array_map(fn($b) => (int)($b['bid_amount'] ?? 0), $proposals)),
        'featured'     => count(array_filter($projects, fn($p) => !empty($p['featured']))),
        'freelancers'  => count($freelancers),
        'freelancers_pro' => count(array_filter($freelancers, fn($f) => ($f['tier'] ?? '') === 'pro')),
    ];
}

function fl_admin_category_chart(): array
{
    $counts = fl_category_counts();
    $max = max(1, max($counts));
    $chart = [];
    foreach ($counts as $cat => $n) {
        $chart[] = ['cat' => $cat, 'count' => $n, 'pct' => round(($n / $max) * 100)];
    }
    usort($chart, fn($a, $b) => $b['count'] <=> $a['count']);
    return $chart;
}

function fl_demo_proposal_history(array $project): array
{
    $count = min(6, max(2, (int)($project['proposals_count'] ?? 4)));
    $budget = (int)($project['budget_max'] ?? 20000);
    $history = [];
    $amount = $budget;
    $names = ['Ole N.', 'Anna H.', 'Maria K.', 'Erik S.', 'Lars B.', 'Ingrid T.'];
    for ($i = 0; $i < $count; $i++) {
        $history[] = [
            'freelancer' => $names[$i % count($names)],
            'bid_amount' => $amount,
            'delivery_days' => 14 + ($i * 3),
            'time'   => date('c', time() - ($i * 7200 + rand(120, 900))),
        ];
        $amount = max((int)($project['budget_min'] ?? 0), $amount - rand(500, 2000));
    }
    return $history;
}

function fl_experience_label(string $level, string $lang): string
{
    global $t;
    return $t['levels'][$level] ?? $level;
}

function fl_freelancers(bool $public_only = true): array
{
    require_once __DIR__ . '/storage.php';
    $list = fl_load_freelancers_raw();
    if ($public_only) {
        $list = array_values(array_filter($list, fn($f) => ($f['active'] ?? false) && ($f['activation_status'] ?? '') === 'active'));
    }
    return $list;
}

function fl_freelancer_by_id(string $id, bool $include_inactive = false): ?array
{
    require_once __DIR__ . '/storage.php';
    foreach (fl_load_freelancers_raw() as $f) {
        if (($f['id'] ?? '') === $id) {
            if (!$include_inactive && (!(($f['active'] ?? false)) || ($f['activation_status'] ?? '') !== 'active')) {
                return null;
            }
            return $f;
        }
    }
    return null;
}

function fl_freelancer_image(array $freelancer): string
{
    return $freelancer['image'] ?? fl_placeholder_image();
}

function fl_freelancer_cover(array $freelancer): string
{
    return $freelancer['cover_image'] ?? $freelancer['image'] ?? fl_placeholder_image();
}

function fl_freelancer_rate_label(array $freelancer): string
{
    $min = (int)($freelancer['hourly_rate_min'] ?? 0);
    $max = (int)($freelancer['hourly_rate_max'] ?? 0);
    if ($min > 0 && $max > 0 && $min !== $max) {
        return fl_price($min) . ' – ' . fl_price($max) . '/t';
    }
    return fl_price($max ?: $min) . '/t';
}

function fl_freelancer_search_params(): array
{
    return [
        'q' => trim($_GET['q'] ?? ''),
        'category' => $_GET['category'] ?? '',
        'tier' => $_GET['tier'] ?? '',
        'sort' => $_GET['sort'] ?? 'featured',
    ];
}

function fl_filter_freelancers(array $params, string $lang): array
{
    $items = fl_freelancers();
    $q = bh_str_lower($params['q']);

    if ($q !== '') {
        $items = array_filter($items, function ($f) use ($q, $lang) {
            $skills = implode(' ', $f['skills'] ?? []);
            $hay = bh_str_lower(
                fl_localized($f, 'name', $lang) . ' '
                . fl_localized($f, 'title', $lang) . ' '
                . fl_localized($f, 'bio', $lang) . ' '
                . $skills
            );
            return str_contains($hay, $q);
        });
    }

    if ($params['category'] !== '' && in_array($params['category'], fl_categories(), true)) {
        $items = array_filter($items, fn($f) => in_array($params['category'], $f['categories'] ?? [], true));
    }

    if (in_array($params['tier'], ['pro', 'simple'], true)) {
        $items = array_filter($items, fn($f) => ($f['tier'] ?? '') === $params['tier']);
    }

    $items = array_values($items);
    usort($items, function ($a, $b) use ($params) {
        return match ($params['sort']) {
            'rate_low' => ($a['hourly_rate_min'] ?? 0) <=> ($b['hourly_rate_min'] ?? 0),
            'rate_high' => ($b['hourly_rate_max'] ?? 0) <=> ($a['hourly_rate_max'] ?? 0),
            'rating' => ($b['rating'] ?? 0) <=> ($a['rating'] ?? 0),
            default => (int)!empty($b['featured']) <=> (int)!empty($a['featured'])
                ?: ($b['rating'] ?? 0) <=> ($a['rating'] ?? 0),
        };
    });

    return $items;
}

function fl_featured_freelancers(int $limit = 6): array
{
    $featured = array_values(array_filter(fl_freelancers(), fn($f) => !empty($f['featured'])));
    if (count($featured) < $limit) {
        $extra = array_values(array_filter(fl_freelancers(), fn($f) => empty($f['featured'])));
        $featured = array_merge($featured, $extra);
    }
    return array_slice($featured, 0, $limit);
}

function fl_tiers(): array
{
    return ['simple', 'pro'];
}