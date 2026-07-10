<?php
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/includes/user-auth.php';

if (fl_user_logged()) {
    header('Location: ' . fl_url('profile.php'), true, 302);
    exit;
}

$error = '';
$success = false;
$pending = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = fl_register_user([
        'firstname' => $_POST['firstname'] ?? '',
        'lastname' => $_POST['lastname'] ?? '',
        'email' => $_POST['email'] ?? '',
        'password' => $_POST['password'] ?? '',
        'phone' => $_POST['phone'] ?? '',
        'tier' => $_POST['tier'] ?? 'simple',
        'category' => $_POST['category'] ?? 'web',
        'title' => $_POST['title'] ?? '',
        'bio' => $_POST['bio'] ?? '',
        'skills' => $_POST['skills'] ?? '',
        'city' => $_POST['city'] ?? 'Oslo',
        'hourly_min' => $_POST['hourly_min'] ?? 500,
        'hourly_max' => $_POST['hourly_max'] ?? 800,
        'level' => $_POST['level'] ?? 'intermediate',
    ]);
    if ($result['ok']) {
        $success = true;
        $pending = !empty($result['pending']);
    } else {
        $error = $t['auth']['errors'][$result['error']] ?? $t['auth']['register_error'];
    }
}

$current_page = 'register';
$page_title = $t['auth']['register_title'];
$page_desc = $t['auth']['register_sub'];
$canonical = $site_url . '/register.php';
require __DIR__ . '/includes/header.php';
?>

<div class="fl-container" style="max-width:640px">
    <div class="fl-auth-card" style="margin:32px 0">
        <h1><?= htmlspecialchars($t['auth']['register_title']) ?></h1>
        <p class="fl-auth-sub"><?= htmlspecialchars($t['auth']['register_sub']) ?></p>

        <?php if ($success): ?>
        <div class="fl-alert fl-alert-success">
            <i class="fas fa-check-circle"></i>
            <?= htmlspecialchars($pending ? $t['auth']['register_pending'] : $t['auth']['register_success']) ?>
        </div>
        <a href="<?= fl_url($pending ? 'login.php' : 'profile.php') ?>" class="fl-btn-primary"><?= htmlspecialchars($t['auth']['continue']) ?></a>
        <?php else: ?>

        <?php if ($error): ?>
        <div class="fl-alert fl-alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="fl-form-row">
                <div class="fl-form-field">
                    <label><?= htmlspecialchars($t['propose']['firstname']) ?></label>
                    <input type="text" name="firstname" required value="<?= htmlspecialchars($_POST['firstname'] ?? '') ?>">
                </div>
                <div class="fl-form-field">
                    <label><?= htmlspecialchars($t['propose']['lastname']) ?></label>
                    <input type="text" name="lastname" required value="<?= htmlspecialchars($_POST['lastname'] ?? '') ?>">
                </div>
            </div>
            <div class="fl-form-field">
                <label><?= htmlspecialchars($t['auth']['email']) ?></label>
                <input type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
            <div class="fl-form-field">
                <label><?= htmlspecialchars($t['auth']['password']) ?></label>
                <input type="password" name="password" required minlength="6">
            </div>
            <div class="fl-form-field">
                <label><?= htmlspecialchars($t['auth']['tier_plan']) ?></label>
                <select name="tier">
                    <option value="simple"><?= htmlspecialchars($t['tiers']['simple_desc']) ?></option>
                    <option value="pro"><?= htmlspecialchars($t['tiers']['pro_desc']) ?></option>
                </select>
            </div>
            <div class="fl-form-field">
                <label><?= htmlspecialchars($t['auth']['job_title']) ?></label>
                <input type="text" name="title" placeholder="Full-stack developer — PHP & React" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
            </div>
            <div class="fl-form-field">
                <label><?= htmlspecialchars($t['admin']['category']) ?></label>
                <select name="category">
                    <?php foreach (fl_categories() as $cat): ?>
                    <option value="<?= $cat ?>"><?= htmlspecialchars($t['categories'][$cat]) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="fl-form-row">
                <div class="fl-form-field">
                    <label><?= htmlspecialchars($t['auth']['hourly_min']) ?></label>
                    <input type="number" name="hourly_min" min="0" step="50" value="<?= (int)($_POST['hourly_min'] ?? 500) ?>">
                </div>
                <div class="fl-form-field">
                    <label><?= htmlspecialchars($t['auth']['hourly_max']) ?></label>
                    <input type="number" name="hourly_max" min="0" step="50" value="<?= (int)($_POST['hourly_max'] ?? 800) ?>">
                </div>
            </div>
            <div class="fl-form-field">
                <label><?= htmlspecialchars($t['auth']['skills']) ?></label>
                <input type="text" name="skills" placeholder="PHP, React, SEO" value="<?= htmlspecialchars($_POST['skills'] ?? '') ?>">
            </div>
            <div class="fl-form-field">
                <label><?= htmlspecialchars($t['auth']['bio']) ?></label>
                <textarea name="bio" rows="3"><?= htmlspecialchars($_POST['bio'] ?? '') ?></textarea>
            </div>
            <button type="submit" class="fl-btn-primary fl-btn-block"><i class="fas fa-user-plus"></i> <?= htmlspecialchars($t['auth']['register_btn']) ?></button>
        </form>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>