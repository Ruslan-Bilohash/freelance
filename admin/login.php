<?php
require_once __DIR__ . '/init.php';
$ta = $t['admin'] ?? [];

if (fl_admin_logged()) {
    header('Location: ' . fl_admin_url('index.php'));
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (fl_admin_login(trim($_POST['username'] ?? ''), $_POST['password'] ?? '')) {
        header('Location: ' . fl_admin_url('index.php'));
        exit;
    }
    $error = $ta['login_error'] ?? 'Invalid username or password';
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang_meta['html']) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?= htmlspecialchars($ta['login_title']) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= htmlspecialchars(fl_asset('css/admin.css')) ?>?v=5">
</head>
<body>
<div class="adm-login-wrap">
    <div class="adm-login-box">
        <div class="logo">
            <div class="icon">F</div>
            <h1><?= htmlspecialchars($ta['login_title']) ?></h1>
            <p class="sub"><?= htmlspecialchars($ta['login_sub']) ?></p>
        </div>
        <div class="adm-demo-hint"><i class="fas fa-info-circle"></i> <?= htmlspecialchars($ta['demo_creds']) ?></div>
        <?php if ($error): ?>
        <div class="adm-login-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="adm-field">
                <label for="username"><?= htmlspecialchars($ta['username']) ?></label>
                <input type="text" id="username" name="username" required autocomplete="username" value="demo">
            </div>
            <div class="adm-field">
                <label for="password"><?= htmlspecialchars($ta['password']) ?></label>
                <input type="password" id="password" name="password" required autocomplete="current-password" value="bilofreelance2026">
            </div>
            <button type="submit" class="adm-btn adm-btn-primary" style="width:100%;justify-content:center;padding:12px;margin-top:8px">
                <i class="fas fa-sign-in-alt"></i> <?= htmlspecialchars($ta['login_btn']) ?>
            </button>
        </form>
        <p style="text-align:center;margin-top:20px;font-size:12px">
            <a href="<?= fl_url('index.php') ?>">← <?= htmlspecialchars($t['breadcrumb_home'] ?? 'Home') ?></a>
        </p>
        <div class="adm-lang-mini" style="margin-top:16px">
            <?php foreach (fl_langs() as $code => $info): ?>
            <a href="<?= htmlspecialchars(fl_admin_lang_url($code)) ?>" class="<?= $lang === $code ? 'active' : '' ?>" style="color:var(--adm-muted);border-color:var(--adm-border)"><?= $info['flag'] ?> <?= $info['label'] ?></a>
            <?php endforeach; ?>
        </div>
    </div>
</div>
</body>
</html>