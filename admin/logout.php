<?php
require_once dirname(__DIR__) . '/includes/admin-auth.php';
fl_admin_logout();
header('Location: ' . fl_admin_url('login.php'), true, 302);
exit;