<?php

function fl_data_path(string $file): string
{
    return __DIR__ . '/../data/' . $file;
}

function fl_projects_file(): string
{
    return fl_data_path('projects.json');
}

function fl_proposals_file(): string
{
    return fl_data_path('proposals.json');
}

function fl_default_projects_from_seed(): ?array
{
    $seedFile = __DIR__ . '/../data/projects.php';
    if (!is_readable($seedFile)) {
        return null;
    }
    $defaults = require $seedFile;
    if (!is_array($defaults)) {
        return null;
    }
    foreach ($defaults as &$project) {
        $project['active'] = $project['active'] ?? true;
    }
    unset($project);
    return $defaults;
}

function fl_fallback_projects(): array
{
    return [
        [
            'id' => 'demo-wordpress-site',
            'category' => 'wordpress',
            'status' => 'open',
            'featured' => true,
            'active' => true,
            'budget_type' => 'fixed',
            'budget_min' => 15000,
            'budget_max' => 28000,
            'proposals_count' => 8,
            'posted_at' => '2026-06-10T09:00:00+02:00',
            'deadline' => '2026-07-20T18:00:00+02:00',
            'experience_level' => 'intermediate',
            'skills' => ['WordPress', 'WooCommerce', 'Elementor'],
            'city' => ['en' => 'Oslo', 'no' => 'Oslo', 'uk' => 'Осло', 'ru' => 'Осло'],
            'country' => ['en' => 'Norway', 'no' => 'Norge', 'uk' => 'Норвегія', 'ru' => 'Норвегия'],
            'client' => ['en' => 'Nordic Retail AS (demo)', 'no' => 'Nordic Retail AS (demo)', 'uk' => 'Nordic Retail AS (демо)', 'ru' => 'Nordic Retail AS (демо)'],
            'name' => [
                'en' => 'WordPress + WooCommerce store rebuild',
                'no' => 'WordPress + WooCommerce butikk — oppgradering',
                'uk' => 'Перебудова магазину WordPress + WooCommerce',
                'ru' => 'Перестройка магазина WordPress + WooCommerce',
            ],
            'desc' => [
                'en' => 'Demo freelance project for Freelance CMS — migrate legacy shop to modern theme.',
                'no' => 'Demoprosjekt for Freelance CMS — migrer gammel butikk til moderne tema.',
                'uk' => 'Демо-проєкт Freelance CMS — міграція магазину на сучасну тему.',
                'ru' => 'Демо-проект Freelance CMS — миграция магазина на современную тему.',
            ],
            'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&q=80',
        ],
    ];
}

function fl_ensure_projects_json(): void
{
    $json = fl_projects_file();

    if (!is_file($json)) {
        $defaults = fl_default_projects_from_seed();
        if ($defaults !== null) {
            fl_save_projects($defaults);
        }
        return;
    }

    $defaults = fl_default_projects_from_seed();
    if ($defaults === null) {
        return;
    }

    $existing = json_decode(file_get_contents($json) ?: '[]', true);
    if (!is_array($existing)) {
        fl_save_projects($defaults);
        return;
    }

    $ids = array_column($existing, 'id');
    $merged = $existing;
    $changed = false;
    foreach ($defaults as $project) {
        if (!in_array($project['id'] ?? '', $ids, true)) {
            $merged[] = $project;
            $changed = true;
        }
    }
    if ($changed) {
        fl_save_projects($merged);
    }
}

function fl_load_projects_raw(): array
{
    $file = fl_projects_file();
    if (is_readable($file)) {
        $data = json_decode(file_get_contents($file) ?: '[]', true);
        if (is_array($data) && $data !== []) {
            return $data;
        }
    }

    fl_ensure_projects_json();
    if (is_readable($file)) {
        $data = json_decode(file_get_contents($file) ?: '[]', true);
        if (is_array($data) && $data !== []) {
            return $data;
        }
    }

    $seed = fl_default_projects_from_seed();
    if ($seed !== null) {
        return $seed;
    }

    return fl_fallback_projects();
}

function fl_save_projects(array $list): bool
{
    $json = json_encode(array_values($list), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    return file_put_contents(fl_projects_file(), $json, LOCK_EX) !== false;
}

function fl_load_proposals(): array
{
    $file = fl_proposals_file();
    if (!is_file($file)) {
        fl_seed_demo_proposals();
    }
    $raw = file_get_contents($file);
    $data = json_decode($raw ?: '[]', true);
    return is_array($data) ? $data : [];
}

function fl_save_proposals(array $list): bool
{
    $json = json_encode(array_values($list), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    return file_put_contents(fl_proposals_file(), $json, LOCK_EX) !== false;
}

function fl_add_proposal(array $proposal): string
{
    $list = fl_load_proposals();
    $proposal['id']         = $proposal['id'] ?? ('fl-' . bin2hex(random_bytes(4)));
    $proposal['created_at'] = $proposal['created_at'] ?? date('c');
    $proposal['status']     = $proposal['status'] ?? 'pending';
    array_unshift($list, $proposal);

    $projects = fl_load_projects_raw();
    foreach ($projects as &$p) {
        if (($p['id'] ?? '') === ($proposal['project_id'] ?? '')) {
            $p['proposals_count'] = (int)($p['proposals_count'] ?? 0) + 1;
            break;
        }
    }
    unset($p);
    fl_save_projects($projects);
    fl_save_proposals($list);
    return $proposal['ref'] ?? $proposal['id'];
}

function fl_freelancers_file(): string
{
    return fl_data_path('freelancers.json');
}

function fl_users_file(): string
{
    return fl_data_path('users.json');
}

function fl_settings_file(): string
{
    return fl_data_path('settings.json');
}

function fl_default_freelancers_from_seed(): ?array
{
    $seedFile = __DIR__ . '/../data/freelancers.php';
    if (!is_readable($seedFile)) {
        return null;
    }
    $defaults = require $seedFile;
    return is_array($defaults) ? $defaults : null;
}

function fl_default_users(): array
{
    return [
        [
            'id' => 'usr-anna-demo',
            'email' => 'anna@demo.no',
            'password_hash' => password_hash('demo2026', PASSWORD_DEFAULT),
            'role' => 'freelancer',
            'tier' => 'pro',
            'activation_status' => 'active',
            'freelancer_id' => 'anna-hansen-dev',
            'firstname' => 'Anna',
            'lastname' => 'Hansen',
            'phone' => '+4798765432',
            'created_at' => '2024-03-15T10:00:00+02:00',
            'last_login' => null,
        ],
        [
            'id' => 'usr-ole-demo',
            'email' => 'ole@demo.no',
            'password_hash' => password_hash('demo2026', PASSWORD_DEFAULT),
            'role' => 'freelancer',
            'tier' => 'simple',
            'activation_status' => 'active',
            'freelancer_id' => 'ole-nordmann-design',
            'firstname' => 'Ole',
            'lastname' => 'Nordmann',
            'phone' => '+4744455566',
            'created_at' => '2025-01-10T10:00:00+02:00',
            'last_login' => null,
        ],
        [
            'id' => 'usr-viktor-pending',
            'email' => 'viktor@demo.ua',
            'password_hash' => password_hash('demo2026', PASSWORD_DEFAULT),
            'role' => 'freelancer',
            'tier' => 'simple',
            'activation_status' => 'pending',
            'freelancer_id' => 'viktor-petrenko-wp',
            'firstname' => 'Viktor',
            'lastname' => 'Petrenko',
            'phone' => '+380501234567',
            'created_at' => '2026-06-20T10:00:00+02:00',
            'last_login' => null,
        ],
    ];
}

function fl_default_settings(): array
{
    return [
        'default_tier' => 'simple',
        'pro_monthly_fee' => 299,
        'simple_proposal_limit' => 5,
        'require_admin_activation' => true,
        'pro_featured_listing' => true,
        'site_tagline' => 'Freelance CMS Demo',
        'color_primary' => '#10b981',
        'color_button' => '#10b981',
        'color_button_hover' => '#059669',
        'bg_color' => '#0c1222',
        'bg_image' => '',
        'recaptcha_enabled' => true,
        'recaptcha_site_key' => '',
        'recaptcha_secret_key' => '',
        'chat_enabled' => false,
        'chat_provider' => 'none',
        'chat_api_key' => '',
        'chat_instructions' => 'You are a sales assistant for Freelance CMS — a PHP freelance marketplace script from Norway. Help visitors understand demo features: projects, freelancers, Simple/Pro tiers, admin panel, SEO regions. Suggest ordering custom development at bilohash.com.',
        'seo_selling_text' => '',
    ];
}

function fl_ensure_json_seed(string $file, callable $seedFn, callable $mergeFn): void
{
    if (!is_file($file)) {
        $seed = $seedFn();
        if ($seed !== null) {
            $mergeFn($seed);
        }
        return;
    }
    $seed = $seedFn();
    if ($seed === null) {
        return;
    }
    $existing = json_decode(file_get_contents($file) ?: '[]', true);
    if (!is_array($existing)) {
        $mergeFn($seed);
        return;
    }
    $ids = array_column($existing, 'id');
    $merged = $existing;
    $changed = false;
    foreach ($seed as $row) {
        if (!in_array($row['id'] ?? '', $ids, true)) {
            $merged[] = $row;
            $changed = true;
        }
    }
    if ($changed) {
        $mergeFn($merged);
    }
}

function fl_ensure_freelancers_json(): void
{
    fl_ensure_json_seed(
        fl_freelancers_file(),
        fn() => fl_default_freelancers_from_seed(),
        fn($list) => fl_save_freelancers($list)
    );
}

function fl_ensure_users_json(): void
{
    if (!is_file(fl_users_file())) {
        fl_save_users(fl_default_users());
    }
}

function fl_ensure_settings_json(): void
{
    if (!is_file(fl_settings_file())) {
        fl_save_settings(fl_default_settings());
    }
}

function fl_bootstrap_data(): void
{
    fl_ensure_projects_json();
    fl_ensure_freelancers_json();
    fl_ensure_users_json();
    fl_ensure_settings_json();
}

function fl_load_freelancers_raw(): array
{
    fl_ensure_freelancers_json();
    $file = fl_freelancers_file();
    if (!is_readable($file)) {
        return fl_default_freelancers_from_seed() ?? [];
    }
    $data = json_decode(file_get_contents($file) ?: '[]', true);
    return is_array($data) ? $data : [];
}

function fl_save_freelancers(array $list): bool
{
    $json = json_encode(array_values($list), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    return file_put_contents(fl_freelancers_file(), $json, LOCK_EX) !== false;
}

function fl_add_freelancer(array $freelancer): string
{
    $list = fl_load_freelancers_raw();
    $freelancer['id'] = $freelancer['id'] ?? ('flr-' . bin2hex(random_bytes(4)));
    array_unshift($list, $freelancer);
    fl_save_freelancers($list);
    return $freelancer['id'];
}

function fl_load_users_raw(): array
{
    fl_ensure_users_json();
    $file = fl_users_file();
    $data = json_decode(file_get_contents($file) ?: '[]', true);
    return is_array($data) ? $data : [];
}

function fl_save_users(array $list): bool
{
    $json = json_encode(array_values($list), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    return file_put_contents(fl_users_file(), $json, LOCK_EX) !== false;
}

function fl_add_user(array $user): string
{
    $list = fl_load_users_raw();
    $user['id'] = $user['id'] ?? ('usr-' . bin2hex(random_bytes(4)));
    array_unshift($list, $user);
    fl_save_users($list);
    return $user['id'];
}

function fl_user_by_id(string $id): ?array
{
    foreach (fl_load_users_raw() as $u) {
        if (($u['id'] ?? '') === $id) {
            return $u;
        }
    }
    return null;
}

function fl_user_by_email(string $email): ?array
{
    $email = bh_str_lower(trim($email));
    foreach (fl_load_users_raw() as $u) {
        if (bh_str_lower($u['email'] ?? '') === $email) {
            return $u;
        }
    }
    return null;
}

function fl_load_settings(): array
{
    fl_ensure_settings_json();
    $data = json_decode(file_get_contents(fl_settings_file()) ?: '{}', true);
    return is_array($data) ? array_merge(fl_default_settings(), $data) : fl_default_settings();
}

function fl_save_settings(array $settings): bool
{
    $json = json_encode($settings, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    return file_put_contents(fl_settings_file(), $json, LOCK_EX) !== false;
}

function fl_seed_demo_proposals(): void
{
    $projects = fl_load_projects_raw();
    $samples = [];
    if (count($projects) >= 2) {
        $samples = [
            [
                'id' => 'demo-001', 'ref' => 'FL-DEMO-7F3A9B2C', 'status' => 'pending',
                'project_id' => $projects[0]['id'], 'project_name' => $projects[0]['name']['en'] ?? 'Project',
                'freelancer' => 'Anna Hansen', 'email' => 'anna@demo.no', 'phone' => '+4798765432',
                'bid_amount' => (int)($projects[0]['budget_max'] ?? 25000) - 2000,
                'delivery_days' => 21, 'created_at' => date('c', strtotime('-2 hours')),
            ],
            [
                'id' => 'demo-002', 'ref' => 'FL-DEMO-4E8D1A0F', 'status' => 'shortlisted',
                'project_id' => $projects[1]['id'], 'project_name' => $projects[1]['name']['en'] ?? 'Project',
                'freelancer' => 'Ole Nordmann', 'email' => 'ole@demo.no', 'phone' => '+4744455566',
                'bid_amount' => (int)($projects[1]['budget_max'] ?? 18000),
                'delivery_days' => 14, 'created_at' => date('c', strtotime('-5 hours')),
            ],
            [
                'id' => 'demo-003', 'ref' => 'FL-DEMO-9C2B5E7D', 'status' => 'accepted',
                'project_id' => $projects[2]['id'] ?? $projects[0]['id'], 'project_name' => $projects[2]['name']['en'] ?? 'Project',
                'freelancer' => 'Maria Kowalska', 'email' => 'maria@demo.pl', 'phone' => '+48123456789',
                'bid_amount' => (int)($projects[2]['budget_max'] ?? 35000),
                'delivery_days' => 30, 'created_at' => date('c', strtotime('-1 day')),
            ],
        ];
    }
    fl_save_proposals($samples);
}