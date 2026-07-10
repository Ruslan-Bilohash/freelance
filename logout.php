<?php
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/includes/user-auth.php';
fl_user_logout();
header('Location: ' . fl_url('index.php'), true, 302);
exit;