<?php
require_once __DIR__ . '/init.php';
fl_admin_require();
$admin_page = 'users';
$page_title = $ta['users'] ?? 'Users';

require_once dirname(__DIR__) . '/includes/storage.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['user_id'])) {
    $users = fl_load_users_raw();
    foreach ($users as &$u) {
        if (($u['id'] ?? '') !== $_POST['user_id']) {
            continue;
        }
        if (!empty($_POST['activate'])) {
            $u['activation_status'] = 'active';
        }
        if (!empty($_POST['suspend'])) {
            $u['activation_status'] = 'suspended';
        }
        if (($_POST['tier'] ?? '') === 'pro' || ($_POST['tier'] ?? '') === 'simple') {
            $u['tier'] = $_POST['tier'];
        }
        $fid = $u['freelancer_id'] ?? '';
        if ($fid !== '') {
            $freelancers = fl_load_freelancers_raw();
            foreach ($freelancers as &$f) {
                if (($f['id'] ?? '') === $fid) {
                    $f['activation_status'] = $u['activation_status'];
                    $f['tier'] = $u['tier'];
                    $f['active'] = $u['activation_status'] === 'active';
                    break;
                }
            }
            unset($f);
            fl_save_freelancers($freelancers);
        }
        break;
    }
    unset($u);
    fl_save_users($users);
    header('Location: ' . fl_admin_url('users.php?ok=1'), true, 302);
    exit;
}

$users = fl_load_users_raw();
require __DIR__ . '/includes/layout.php';
?>

<?php if (!empty($_GET['ok'])): ?>
<div class="adm-alert adm-alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($ta['saved']) ?></div>
<?php endif; ?>

<div class="adm-card">
    <div class="adm-card-head"><h2><?= htmlspecialchars($ta['users'] ?? 'Users') ?> (<?= count($users) ?>)</h2></div>
    <div class="adm-card-body">
        <div class="adm-table-wrap">
        <table class="adm-table adm-table--cards">
            <thead>
                <tr>
                    <th><?= htmlspecialchars($ta['email'] ?? 'Email') ?></th>
                    <th><?= htmlspecialchars($ta['name'] ?? 'Name') ?></th>
                    <th><?= htmlspecialchars($ta['tier'] ?? 'Plan') ?></th>
                    <th><?= htmlspecialchars($ta['activation'] ?? 'Activation') ?></th>
                    <th><?= htmlspecialchars($ta['actions']) ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['email'] ?? '') ?></td>
                    <td><?= htmlspecialchars(trim(($u['firstname'] ?? '') . ' ' . ($u['lastname'] ?? ''))) ?></td>
                    <td><span class="adm-badge adm-badge-<?= ($u['tier'] ?? '') === 'pro' ? 'accepted' : 'pending' ?>"><?= strtoupper($u['tier'] ?? 'simple') ?></span></td>
                    <td><?= htmlspecialchars($u['activation_status'] ?? '') ?></td>
                    <td>
                        <form method="post" style="display:inline-flex;gap:6px;flex-wrap:wrap">
                            <input type="hidden" name="user_id" value="<?= htmlspecialchars($u['id']) ?>">
                            <?php if (($u['activation_status'] ?? '') !== 'active'): ?>
                            <button type="submit" name="activate" value="1" class="adm-btn adm-btn-primary adm-btn-sm"><?= htmlspecialchars($ta['activate'] ?? 'Activate') ?></button>
                            <?php endif; ?>
                            <select name="tier" class="adm-btn adm-btn-outline adm-btn-sm" style="padding:6px">
                                <option value="simple" <?= ($u['tier'] ?? '') === 'simple' ? 'selected' : '' ?>>Simple</option>
                                <option value="pro" <?= ($u['tier'] ?? '') === 'pro' ? 'selected' : '' ?>>Pro</option>
                            </select>
                            <button type="submit" class="adm-btn adm-btn-outline adm-btn-sm"><?= htmlspecialchars($ta['save']) ?></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>

<?php require __DIR__ . '/includes/layout-end.php'; ?>