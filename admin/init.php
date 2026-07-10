<?php
require_once dirname(__DIR__) . '/init.php';
require_once dirname(__DIR__) . '/includes/admin-auth.php';
require_once dirname(__DIR__) . '/includes/storage.php';

$ta = $t['admin'] ?? [];
$admin_page = $admin_page ?? 'dashboard';

function fl_admin_lang_url(string $code): string
{
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: fl_admin_url('index.php');
    parse_str(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_QUERY) ?? '', $q);
    $q['lang'] = $code;
    $qs = http_build_query($q);
    return $path . ($qs !== '' ? '?' . $qs : '');
}