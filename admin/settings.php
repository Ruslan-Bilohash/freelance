<?php
require_once __DIR__ . '/init.php';
fl_admin_require();
header('Location: ' . fl_admin_url('settings-general.php'), true, 302);
exit;