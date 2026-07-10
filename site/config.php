<?php
/**
 * Freelance CMS — product marketing site
 * /freelance/site
 */
require_once dirname(__DIR__) . '/config.php';

define('FLS_BASE_PATH', '/freelance/site');
define('FLS_PRODUCT_NAME', 'Freelance CMS');
define('FLS_PARENT_PATH', '/freelance');

$detected = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$base_path = (strpos($host, FL_DOMAIN) !== false) ? FLS_BASE_PATH : ($detected ?: FLS_BASE_PATH);

$protocol = (
    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')
) ? 'https' : 'http';

$site_url   = rtrim($protocol . '://' . $host . $base_path, '/');
$assets_url = $base_path . '/assets';
$screen_url = FLS_PARENT_PATH . '/screen';

function fls_url(string $path = ''): string
{
    global $base_path;
    return rtrim($base_path, '/') . '/' . ltrim($path, '/');
}

function fls_asset(string $file): string
{
    global $assets_url;
    return $assets_url . '/' . ltrim($file, '/');
}

function fls_screen(string $file): string
{
    global $screen_url;
    return $screen_url . '/' . ltrim($file, '/');
}

function fls_demo_url(string $path = ''): string
{
    global $host, $protocol;
    return rtrim($protocol . '://' . $host . FLS_PARENT_PATH, '/') . '/' . ltrim($path, '/');
}