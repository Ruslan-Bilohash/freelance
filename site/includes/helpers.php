<?php

function fls_lang_url(string $code): string
{
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: fls_url('index.php');
    parse_str(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_QUERY) ?? '', $q);
    // Always set lang in switcher links (incl. NO) so fls_detect_lang() updates the cookie.
    $q['lang'] = $code;
    $qs = http_build_query($q);
    return $path . ($qs !== '' ? '?' . $qs : '');
}

function fls_absolute_url(string $path): string
{
    global $site_url;
    if (str_starts_with($path, 'http')) return $path;
    return rtrim($site_url, '/') . '/' . ltrim($path, '/');
}

function fls_screens(): array
{
    return [
        ['file' => 'home.svg', 'key' => 'home'],
        ['file' => 'search.svg', 'key' => 'search'],
        ['file' => 'project.svg', 'key' => 'project'],
        ['file' => 'freelancers.svg', 'key' => 'freelancers'],
        ['file' => 'admin-dashboard.svg', 'key' => 'admin_dash'],
        ['file' => 'admin-projects.svg', 'key' => 'admin_projects'],
        ['file' => 'admin-proposals.svg', 'key' => 'admin_props'],
    ];
}