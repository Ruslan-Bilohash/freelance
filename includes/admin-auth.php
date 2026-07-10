<?php
/**
 * Demo admin auth — session based
 * Login: demo / bilofreelance2026
 */
define('FL_ADMIN_USER', 'demo');
define('FL_ADMIN_PASS', 'bilofreelance2026');
define('FL_ADMIN_SESSION_KEY', 'fl_admin_logged');

function fl_admin_start(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function fl_admin_logged(): bool
{
    fl_admin_start();
    return !empty($_SESSION[FL_ADMIN_SESSION_KEY]);
}

function fl_admin_login(string $user, string $pass): bool
{
    if ($user === FL_ADMIN_USER && $pass === FL_ADMIN_PASS) {
        fl_admin_start();
        $_SESSION[FL_ADMIN_SESSION_KEY] = true;
        $_SESSION['fl_admin_user'] = $user;
        return true;
    }
    return false;
}

function fl_admin_logout(): void
{
    fl_admin_start();
    unset($_SESSION[FL_ADMIN_SESSION_KEY], $_SESSION['fl_admin_user']);
}

function fl_admin_require(): void
{
    if (!fl_admin_logged()) {
        header('Location: ' . fl_admin_url('login.php'), true, 302);
        exit;
    }
}

function fl_admin_url(string $path = ''): string
{
    return fl_url('admin/' . ltrim($path, '/'));
}