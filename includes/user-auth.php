<?php
/**
 * Frontend user auth — demo freelancers
 * Demo login: anna@demo.no / demo2026 (also ole@demo.no)
 */
define('FL_USER_SESSION_KEY', 'fl_user_id');
define('FL_DEMO_USER_PASS', 'demo2026');

function fl_user_start(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function fl_current_user(): ?array
{
    fl_user_start();
    $id = $_SESSION[FL_USER_SESSION_KEY] ?? '';
    if ($id === '') {
        return null;
    }
    require_once __DIR__ . '/storage.php';
    return fl_user_by_id($id);
}

function fl_user_logged(): bool
{
    return fl_current_user() !== null;
}

function fl_user_login(string $email, string $pass): bool
{
    require_once __DIR__ . '/storage.php';
    $user = fl_user_by_email($email);
    if ($user === null) {
        return false;
    }
    if (!fl_verify_user_password($user, $pass)) {
        return false;
    }
    if (($user['activation_status'] ?? 'pending') !== 'active') {
        return false;
    }
    fl_user_start();
    $_SESSION[FL_USER_SESSION_KEY] = $user['id'];
    $users = fl_load_users_raw();
    foreach ($users as &$u) {
        if (($u['id'] ?? '') === $user['id']) {
            $u['last_login'] = date('c');
            break;
        }
    }
    unset($u);
    fl_save_users($users);
    return true;
}

function fl_verify_user_password(array $user, string $pass): bool
{
    if (FL_DEMO_MODE && $pass === FL_DEMO_USER_PASS) {
        return true;
    }
    $hash = $user['password_hash'] ?? '';
    return $hash !== '' && password_verify($pass, $hash);
}

function fl_user_logout(): void
{
    fl_user_start();
    unset($_SESSION[FL_USER_SESSION_KEY]);
}

function fl_user_require(): void
{
    if (!fl_user_logged()) {
        $redirect = urlencode($_SERVER['REQUEST_URI'] ?? fl_url('login.php'));
        header('Location: ' . fl_url('login.php?redirect=' . $redirect), true, 302);
        exit;
    }
}

function fl_register_user(array $data): array
{
    require_once __DIR__ . '/storage.php';
    $settings = fl_load_settings();
    $email = bh_str_lower(trim($data['email'] ?? ''));
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['ok' => false, 'error' => 'invalid_email'];
    }
    if (fl_user_by_email($email) !== null) {
        return ['ok' => false, 'error' => 'email_exists'];
    }

    $tier = in_array($data['tier'] ?? '', ['pro', 'simple'], true) ? $data['tier'] : 'simple';
    $userId = 'usr-' . bin2hex(random_bytes(4));
    $freelancerId = 'flr-' . preg_replace('/[^a-z0-9-]/', '-', bh_str_lower(trim($data['slug'] ?? $data['firstname'] ?? 'user')));

    $requireActivation = !empty($settings['require_admin_activation']);
    $activationStatus = $requireActivation ? 'pending' : 'active';

    $user = [
        'id' => $userId,
        'email' => $email,
        'password_hash' => password_hash($data['password'] ?? FL_DEMO_USER_PASS, PASSWORD_DEFAULT),
        'role' => 'freelancer',
        'tier' => $tier,
        'activation_status' => $activationStatus,
        'freelancer_id' => $freelancerId,
        'firstname' => trim($data['firstname'] ?? ''),
        'lastname' => trim($data['lastname'] ?? ''),
        'phone' => trim($data['phone'] ?? ''),
        'created_at' => date('c'),
        'last_login' => null,
    ];

    $freelancer = [
        'id' => $freelancerId,
        'user_id' => $userId,
        'active' => !$requireActivation,
        'featured' => false,
        'tier' => $tier,
        'activation_status' => $activationStatus,
        'verified' => false,
        'availability' => 'available',
        'hourly_rate_min' => max(0, (int)($data['hourly_min'] ?? 500)),
        'hourly_rate_max' => max(0, (int)($data['hourly_max'] ?? 800)),
        'rating' => 0,
        'reviews_count' => 0,
        'completed_projects' => 0,
        'member_since' => date('c'),
        'categories' => array_values(array_filter([(string)($data['category'] ?? '')])),
        'skills' => array_values(array_filter(array_map('trim', explode(',', $data['skills'] ?? '')))),
        'experience_level' => in_array($data['level'] ?? '', fl_experience_levels(), true) ? $data['level'] : 'intermediate',
        'languages_spoken' => [$data['lang_pref'] ?? 'en'],
        'city' => ['en' => trim($data['city'] ?? 'Oslo'), 'no' => trim($data['city'] ?? 'Oslo'), 'uk' => trim($data['city'] ?? 'Осло'), 'ru' => trim($data['city'] ?? 'Осло')],
        'country' => ['en' => 'Norway', 'no' => 'Norge', 'uk' => 'Норвегія', 'ru' => 'Норвегия'],
        'name' => [
            'en' => trim(($data['firstname'] ?? '') . ' ' . ($data['lastname'] ?? '')),
            'no' => trim(($data['firstname'] ?? '') . ' ' . ($data['lastname'] ?? '')),
            'uk' => trim(($data['firstname'] ?? '') . ' ' . ($data['lastname'] ?? '')),
            'ru' => trim(($data['firstname'] ?? '') . ' ' . ($data['lastname'] ?? '')),
        ],
        'title' => [
            'en' => trim($data['title'] ?? 'Freelancer'),
            'no' => trim($data['title'] ?? 'Frilanser'),
            'uk' => trim($data['title'] ?? 'Фрілансер'),
            'ru' => trim($data['title'] ?? 'Фрилансер'),
        ],
        'bio' => [
            'en' => trim($data['bio'] ?? ''),
            'no' => trim($data['bio'] ?? ''),
            'uk' => trim($data['bio'] ?? ''),
            'ru' => trim($data['bio'] ?? ''),
        ],
        'image' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=400&q=80',
        'cover_image' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=1200&q=80',
        'social' => [],
    ];

    fl_add_user($user);
    fl_add_freelancer($freelancer);

    if (!$requireActivation) {
        fl_user_start();
        $_SESSION[FL_USER_SESSION_KEY] = $userId;
    }

    return ['ok' => true, 'user_id' => $userId, 'freelancer_id' => $freelancerId, 'pending' => $requireActivation];
}

function fl_user_freelancer_profile(?array $user = null): ?array
{
    $user = $user ?? fl_current_user();
    if ($user === null) {
        return null;
    }
    require_once __DIR__ . '/storage.php';
    $fid = $user['freelancer_id'] ?? '';
    return $fid !== '' ? fl_freelancer_by_id($fid, true) : null;
}

function fl_user_can(string $capability, ?array $user = null): bool
{
    $user = $user ?? fl_current_user();
    if ($user === null) {
        return false;
    }
    require_once __DIR__ . '/storage.php';
    $settings = fl_load_settings();
    $tier = $user['tier'] ?? 'simple';
    return match ($capability) {
        'featured_listing' => $tier === 'pro',
        'unlimited_proposals' => $tier === 'pro',
        'public_contact' => $tier === 'pro',
        default => ($user['activation_status'] ?? '') === 'active',
    };
}