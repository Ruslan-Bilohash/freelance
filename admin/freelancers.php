<?php
require_once __DIR__ . '/init.php';
fl_admin_require();
$admin_page = 'freelancers';
$page_title = $ta['freelancers'] ?? 'Freelancers';

$filter = $_GET['filter'] ?? 'all';
require_once dirname(__DIR__) . '/includes/storage.php';
$list = fl_load_freelancers_raw();

if ($filter === 'active') {
    $list = array_values(array_filter($list, fn($f) => ($f['active'] ?? false) && ($f['activation_status'] ?? '') === 'active'));
} elseif ($filter === 'pending') {
    $list = array_values(array_filter($list, fn($f) => ($f['activation_status'] ?? '') === 'pending'));
} elseif ($filter === 'pro') {
    $list = array_values(array_filter($list, fn($f) => ($f['tier'] ?? '') === 'pro'));
}

$base = fl_admin_url('freelancers.php');
require __DIR__ . '/includes/layout.php';
?>

<div class="adm-card">
    <div class="adm-card-head">
        <h2><?= htmlspecialchars($ta['freelancers'] ?? 'Freelancers') ?> (<?= count($list) ?>)</h2>
        <a href="<?= fl_url('freelancers.php') ?>" class="adm-btn adm-btn-outline adm-btn-sm" target="_blank"><i class="fas fa-external-link-alt"></i> <?= htmlspecialchars($ta['preview'] ?? 'Preview') ?></a>
    </div>
    <div class="adm-filter-bar">
        <a href="<?= htmlspecialchars($base) ?>" class="<?= $filter === 'all' ? 'active' : '' ?>"><?= htmlspecialchars($ta['filter_all'] ?? 'All') ?></a>
        <a href="<?= htmlspecialchars($base . '?filter=active') ?>" class="<?= $filter === 'active' ? 'active' : '' ?>"><?= htmlspecialchars($ta['active']) ?></a>
        <a href="<?= htmlspecialchars($base . '?filter=pending') ?>" class="<?= $filter === 'pending' ? 'active' : '' ?>"><?= htmlspecialchars($ta['filter_pending'] ?? 'Pending') ?></a>
        <a href="<?= htmlspecialchars($base . '?filter=pro') ?>" class="<?= $filter === 'pro' ? 'active' : '' ?>">Pro</a>
    </div>
    <div class="adm-card-body">
        <div class="adm-table-wrap">
        <table class="adm-table adm-table--cards">
            <thead>
                <tr>
                    <th></th>
                    <th><?= htmlspecialchars($ta['freelancer']) ?></th>
                    <th><?= htmlspecialchars($ta['tier'] ?? 'Plan') ?></th>
                    <th><?= htmlspecialchars($ta['activation'] ?? 'Activation') ?></th>
                    <th><?= htmlspecialchars($ta['hourly_rate'] ?? 'Rate') ?></th>
                    <th><?= htmlspecialchars($ta['status']) ?></th>
                    <th><?= htmlspecialchars($ta['actions']) ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($list as $f): ?>
                <tr>
                    <td><img src="<?= htmlspecialchars(fl_freelancer_image($f)) ?>" alt="" class="adm-thumb" style="border-radius:4px" onerror="this.src='<?= htmlspecialchars(fl_placeholder_image()) ?>'"></td>
                    <td data-label="Name">
                        <strong><?= htmlspecialchars($f['name']['en'] ?? $f['id']) ?></strong><br>
                        <small style="color:var(--adm-muted)"><?= htmlspecialchars($f['title']['en'] ?? '') ?></small>
                    </td>
                    <td data-label="Tier"><span class="adm-badge adm-badge-<?= ($f['tier'] ?? '') === 'pro' ? 'accepted' : 'pending' ?>"><?= strtoupper($f['tier'] ?? 'simple') ?></span></td>
                    <td data-label="Activation"><?= htmlspecialchars($f['activation_status'] ?? '') ?></td>
                    <td data-label="Rate"><?= fl_freelancer_rate_label($f) ?></td>
                    <td data-label="Status">
                        <?php if ($f['active'] ?? false): ?><span class="adm-badge adm-badge-active"><?= htmlspecialchars($ta['active']) ?></span>
                        <?php else: ?><span class="adm-badge adm-badge-hidden"><?= htmlspecialchars($ta['inactive']) ?></span><?php endif; ?>
                    </td>
                    <td data-label="Actions">
                        <a href="<?= fl_admin_url('freelancer.php?id=' . urlencode($f['id'])) ?>" class="adm-btn adm-btn-outline adm-btn-sm"><i class="fas fa-pen"></i> <?= htmlspecialchars($ta['edit']) ?></a>
                        <a href="<?= fl_url('freelancer.php?id=' . urlencode($f['id'])) ?>" class="adm-btn adm-btn-outline adm-btn-sm" target="_blank"><i class="fas fa-eye"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>

<?php require __DIR__ . '/includes/layout-end.php'; ?>