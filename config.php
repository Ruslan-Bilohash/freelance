<?php
/**
 * Bilohash Freelance — universal demo marketplace
 * /freelance
 */
define('FL_BASE_PATH', '/freelance');
define('FL_DOMAIN', 'bilohash.com');
define('FL_SITE_NAME', 'Freelance CMS');
define('FL_CURRENCY', 'NOK');
define('FL_DEMO_MODE', true);

$detected = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$base_path = (strpos($host, FL_DOMAIN) !== false) ? FL_BASE_PATH : ($detected ?: FL_BASE_PATH);

$protocol = (
    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')
) ? 'https' : 'http';

$site_url   = rtrim($protocol . '://' . $host . $base_path, '/');
$assets_url = $base_path . '/assets';

function fl_url(string $path = ''): string
{
    global $base_path;
    return rtrim($base_path, '/') . '/' . ltrim($path, '/');
}

function fl_asset(string $file): string
{
    global $assets_url;
    return $assets_url . '/' . ltrim($file, '/');
}

function fl_price(int $amount): string
{
    return number_format($amount, 0, ',', ' ') . ' kr';
}

function fl_budget_label(array $project): string
{
    $type = $project['budget_type'] ?? 'fixed';
    $min  = (int)($project['budget_min'] ?? 0);
    $max  = (int)($project['budget_max'] ?? 0);
    if ($type === 'hourly') {
        $rate = (int)($project['hourly_rate_max'] ?? $max);
        return fl_price($rate) . '/t';
    }
    if ($min > 0 && $max > 0 && $min !== $max) {
        return fl_price($min) . ' – ' . fl_price($max);
    }
    return fl_price($max ?: $min);
}