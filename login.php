<?php
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/includes/user-auth.php';

if (fl_user_logged()) {
    header('Location: ' . fl_url('profile.php'), true, 302);
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (fl_user_login(trim($_POST['email'] ?? ''), $_POST['password'] ?? '')) {
        $redirect = $_GET['redirect'] ?? fl_url('profile.php');
        if (!str_starts_with($redirect, '/') && !str_contains($redirect, 'bilohash.com')) {
            $redirect = fl_url('profile.php');
        }
        header('Location: ' . $redirect, true, 302);
        exit;
    }
    $error = $t['auth']['login_error'];
}

$current_page = 'login';
$page_title = $t['auth']['login_title'];
$page_desc = $t['auth']['login_sub'];
$canonical = $site_url . '/login.php';
require __DIR__ . '/includes/header.php';
?>

<div class="fl-auth-wrap">
    <div class="fl-auth-card">
        <h1><?= htmlspecialchars($t['auth']['login_title']) ?></h1>
        <p class="fl-auth-sub"><?= htmlspecialchars($t['auth']['login_sub']) ?></p>

        <?php if ($error): ?>
        <div class="fl-alert fl-alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="fl-form-field">
                <label><?= htmlspecialchars($t['auth']['email']) ?></label>
                <input type="email" name="email" required autocomplete="email" value="<?= htmlspecialchars($_POST['email'] ?? 'anna@demo.no') ?>">
            </div>
            <div class="fl-form-field">
                <label><?= htmlspecialchars($t['auth']['password']) ?></label>
                <input type="password" name="password" required autocomplete="current-password">
            </div>
            <button type="submit" class="fl-btn-primary fl-btn-block"><i class="fas fa-sign-in-alt"></i> <?= htmlspecialchars($t['auth']['login_btn']) ?></button>
        </form>

        <p style="margin-top:20px;font-size:14px;color:var(--fl-text-muted)">
            <?= htmlspecialchars($t['auth']['no_account']) ?>
            <a href="<?= fl_url('register.php') ?>"><?= htmlspecialchars($t['auth']['register_link']) ?></a>
        </p>

        <div class="fl-demo-creds">
            <i class="fas fa-key"></i> <?= htmlspecialchars($t['auth']['demo_creds']) ?><br>
            <code>anna@demo.no</code> / <code>demo2026</code> (<?= htmlspecialchars($t['tiers']['pro']) ?>)<br>
            <code>ole@demo.no</code> / <code>demo2026</code> (<?= htmlspecialchars($t['tiers']['simple']) ?>)
        </div>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>