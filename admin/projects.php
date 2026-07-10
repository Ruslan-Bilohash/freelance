<?php
require_once __DIR__ . '/init.php';
fl_admin_require();
$admin_page = 'projects';
$page_title = $ta['projects'];

$filter = $_GET['filter'] ?? 'all';
$projects = fl_projects_all();

if ($filter === 'open') {
    $projects = array_values(array_filter($projects, fn($p) => ($p['active'] ?? true) !== false && fl_project_is_open($p)));
} elseif ($filter === 'featured') {
    $projects = array_values(array_filter($projects, fn($p) => !empty($p['featured'])));
}

$base = fl_admin_url('projects.php');

require __DIR__ . '/includes/layout.php';
?>

<div class="adm-card">
    <div class="adm-card-head">
        <h2><?= htmlspecialchars($ta['projects']) ?> (<?= count($projects) ?>)</h2>
        <a href="<?= fl_url('search.php') ?>" class="adm-btn adm-btn-outline adm-btn-sm" target="_blank">
            <i class="fas fa-external-link-alt"></i> <?= htmlspecialchars($ta['preview'] ?? 'Preview') ?>
        </a>
    </div>
    <div class="adm-filter-bar">
        <a href="<?= htmlspecialchars($base) ?>" class="<?= $filter === 'all' ? 'active' : '' ?>"><?= htmlspecialchars($ta['filter_all'] ?? 'All') ?></a>
        <a href="<?= htmlspecialchars($base . '?filter=open') ?>" class="<?= $filter === 'open' ? 'active' : '' ?>"><?= htmlspecialchars($ta['filter_open'] ?? 'Open only') ?></a>
        <a href="<?= htmlspecialchars($base . '?filter=featured') ?>" class="<?= $filter === 'featured' ? 'active' : '' ?>"><?= htmlspecialchars($ta['filter_featured'] ?? 'Featured') ?></a>
    </div>
    <div class="adm-card-body">
        <div class="adm-table-wrap">
        <table class="adm-table adm-table--cards">
            <thead>
                <tr>
                    <th></th>
                    <th><?= htmlspecialchars($ta['name_en']) ?></th>
                    <th><?= htmlspecialchars($ta['category']) ?></th>
                    <th><?= htmlspecialchars($ta['amount']) ?></th>
                    <th><?= htmlspecialchars($ta['proposals_count']) ?></th>
                    <th><?= htmlspecialchars($ta['deadline'] ?? 'Deadline') ?></th>
                    <th><?= htmlspecialchars($ta['status']) ?></th>
                    <th><?= htmlspecialchars($ta['actions']) ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($projects as $p): ?>
                <tr>
                    <td data-label=""><img src="<?= htmlspecialchars(fl_project_image($p)) ?>" alt="" class="adm-thumb" loading="lazy" onerror="this.onerror=null;this.src='<?= htmlspecialchars(fl_placeholder_image()) ?>';"></td>
                    <td data-label="<?= htmlspecialchars($ta['name_en']) ?>">
                        <strong><?= htmlspecialchars($p['name']['en'] ?? $p['id']) ?></strong><br>
                        <small style="color:var(--adm-muted)"><?= htmlspecialchars($p['city']['en'] ?? '') ?>, <?= htmlspecialchars($p['country']['en'] ?? '') ?></small>
                        <?php if (!empty($p['featured'])): ?>
                        <span class="adm-badge adm-badge-pending" style="margin-top:4px"><i class="fas fa-star"></i></span>
                        <?php endif; ?>
                    </td>
                    <td data-label="<?= htmlspecialchars($ta['category']) ?>"><?= htmlspecialchars($t['categories'][$p['category'] ?? ''] ?? $p['category']) ?></td>
                    <td data-label="<?= htmlspecialchars($ta['amount']) ?>"><strong><?= fl_budget_label($p) ?></strong></td>
                    <td data-label="<?= htmlspecialchars($ta['proposals_count']) ?>"><?= (int)($p['proposals_count'] ?? 0) ?></td>
                    <td data-label="<?= htmlspecialchars($ta['deadline'] ?? 'Deadline') ?>"><?= date('d.m.Y H:i', fl_project_deadline($p)) ?></td>
                    <td data-label="<?= htmlspecialchars($ta['status']) ?>">
                        <?php if (($p['active'] ?? true) !== false): ?>
                        <span class="adm-badge adm-badge-active"><?= htmlspecialchars($ta['active']) ?></span>
                        <?php else: ?>
                        <span class="adm-badge adm-badge-hidden"><?= htmlspecialchars($ta['inactive']) ?></span>
                        <?php endif; ?>
                    </td>
                    <td data-label="<?= htmlspecialchars($ta['actions']) ?>">
                        <a href="<?= fl_admin_url('project.php?id=' . urlencode($p['id'])) ?>" class="adm-btn adm-btn-outline adm-btn-sm">
                            <i class="fas fa-pen"></i> <?= htmlspecialchars($ta['edit']) ?>
                        </a>
                        <a href="<?= fl_url('project.php?id=' . urlencode($p['id'])) ?>" class="adm-btn adm-btn-outline adm-btn-sm" target="_blank">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>

<?php require __DIR__ . '/includes/layout-end.php'; ?>