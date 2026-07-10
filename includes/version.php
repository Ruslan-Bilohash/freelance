<?php
/**
 * Freelance CMS — single source of truth for script version.
 */
define('FL_VERSION', '1.0.0');
define('FL_VERSION_DATE', '2026-07-10');

function fl_version(): string
{
    return FL_VERSION;
}

function fl_version_label(): string
{
    return 'v' . FL_VERSION;
}