<?php
require_once __DIR__ . '/init.php';
fl_admin_require();
$admin_page = 'dashboard';
$page_title = $ta['dashboard'];

$projects = fl_projects_all();
$proposals = fl_load_proposals();
$stats   = fl_platform_stats();
$chart   = fl_admin_category_chart();
$active  = count(array_filter($projects, fn($p) => ($p['active'] ?? true) !== false));
$urgent  = array_values(array_filter($projects, function ($p) {
    if (($p['active'] ?? true) === false || !fl_project_is_open($p)) return false;
    $left = fl_project_deadline($p) - time();
    return $left > 0 && $left <= 7 * 86400;
}));
usort($urgent, fn($a, $b) => fl_project_deadline($a) <=> fl_project_deadline($b));
$urgent = array_slice($urgent, 0, 6);
$recent  = array_slice($proposals, 0, 8);

require __DIR__ . '/includes/layout.php';
?>

<div class="adm-alert adm-alert-info">
    <i class="fas fa-flask"></i> <?= htmlspecialchars($ta['add_note']) ?>
</div>

<div class="adm-stats">
    <div class="adm-stat">
        <div class="adm-stat-icon indigo"><i class="fas fa-folder-open"></i></div>
        <div>
            <div class="adm-stat-val"><?= $active ?></div>
            <div class="adm-stat-label"><?= htmlspecialchars($ta['stats_projects']) ?></div>
        </div>
    </div>
    <div class="adm-stat">
        <div class="adm-stat-icon green"><i class="fas fa-paper-plane"></i></div>
        <div>
            <div class="adm-stat-val"><?= count($proposals) ?></div>
            <div class="adm-stat-label"><?= htmlspecialchars($ta['stats_proposals']) ?></div>
        </div>
    </div>
    <div class="adm-stat">
        <div class="adm-stat-icon blue"><i class="fas fa-coins"></i></div>
        <div>
            <div class="adm-stat-val"><?= fl_price((int)$stats['volume']) ?></div>
            <div class="adm-stat-label"><?= htmlspecialchars($ta['stats_vol']) ?></div>
        </div>
    </div>
    <div class="adm-stat">
        <div class="adm-stat-icon orange"><i class="fas fa-bolt"></i></div>
        <div>
            <div class="adm-stat-val"><?= (int)$stats['open'] ?></div>
            <div class="adm-stat-label"><?= htmlspecialchars($ta['stats_open']) ?></div>
        </div>
    </div>
    <div class="adm-stat">
        <div class="adm-stat-icon indigo"><i class="fas fa-star"></i></div>
        <div>
            <div class="adm-stat-val"><?= (int)$stats['featured'] ?></div>
            <div class="adm-stat-label"><?= htmlspecialchars($ta['featured_count'] ?? 'Featured') ?></div>
        </div>
    </div>
</div>

<div class="adm-dash-grid">
    <div class="adm-card">
        <div class="adm-card-head">
            <h2><?= htmlspecialchars($ta['by_category'] ?? 'By category') ?></h2>
        </div>
        <div class="adm-card-body padded">
            <div class="adm-bar-chart">
                <?php foreach (array_slice($chart, 0, 6) as $row): ?>
                <div class="adm-bar-row">
                    <span class="adm-bar-label"><?= htmlspecialchars($t['categories'][$row['cat']] ?? $row['cat']) ?></span>
                    <div class="adm-bar-track"><div class="adm-bar-fill" style="width:<?= (int)$row['pct'] ?>%"></div></div>
                    <span class="adm-bar-val"><?= (int)$row['count'] ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="adm-card">
        <div class="adm-card-head">
            <h2><?= htmlspecialchars($ta['urgent_soon'] ?? 'Closing within 7 days') ?></h2>
            <a href="<?= fl_url('search.php?status=urgent') ?>" class="adm-btn adm-btn-outline adm-btn-sm" target="_blank"><?= htmlspecialchars($ta['view_site']) ?></a>
        </div>
        <div class="adm-card-body padded adm-ending-list">
            <?php if (empty($urgent)): ?>
            <p style="color:var(--adm-muted);margin:0">No urgent projects.</p>
            <?php else: foreach ($urgent as $up): ?>
            <a href="<?= fl_admin_url('project.php?id=' . urlencode($up['id'])) ?>" class="adm-ending-item">
                <img src="<?= htmlspecialchars(fl_project_image($up)) ?>" alt="" loading="lazy" onerror="this.onerror=null;this.src='<?= htmlspecialchars(fl_placeholder_image()) ?>';">
                <div>
                    <strong><?= htmlspecialchars($up['name']['en'] ?? $up['id']) ?></strong>
                    <span><?= fl_budget_label($up) ?> · <?= date('d.m H:i', fl_project_deadline($up)) ?></span>
                </div>
            </a>
            <?php endforeach; endif; ?>
        </div>
    </div>
</div>

<div class="adm-card">
    <div class="adm-card-head">
        <h2><?= htmlspecialchars($ta['recent_proposals']) ?></h2>
        <a href="<?= fl_admin_url('proposals.php') ?>" class="adm-btn adm-btn-outline adm-btn-sm"><?= htmlspecialchars($ta['all_proposals']) ?> →</a>
    </div>
    <div class="adm-card-body">
        <?php if (empty($recent)): ?>
        <p style="padding:24px;text-align:center;color:var(--adm-muted)"><?= htmlspecialchars($ta['no_proposals']) ?></p>
        <?php else: ?>
        <div class="adm-table-wrap">
        <table class="adm-table adm-table--cards">
            <thead>
                <tr>
                    <th><?= htmlspecialchars($ta['ref']) ?></th>
                    <th><?= htmlspecialchars($ta['freelancer']) ?></th>
                    <th><?= htmlspecialchars($ta['project']) ?></th>
                    <th><?= htmlspecialchars($ta['amount']) ?></th>
                    <th><?= htmlspecialchars($ta['status']) ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent as $b):
                    $st = $b['status'] ?? 'pending';
                ?>
                <tr>
                    <td data-label="<?= htmlspecialchars($ta['ref']) ?>"><code style="font-size:11px"><?= htmlspecialchars($b['ref'] ?? '') ?></code></td>
                    <td data-label="<?= htmlspecialchars($ta['freelancer']) ?>"><?= htmlspecialchars($b['freelancer'] ?? '') ?></td>
                    <td data-label="<?= htmlspecialchars($ta['project']) ?>"><?= htmlspecialchars($b['project_name'] ?? '') ?></td>
                    <td data-label="<?= htmlspecialchars($ta['amount']) ?>"><strong><?= fl_price((int)($b['bid_amount'] ?? 0)) ?></strong></td>
                    <td data-label="<?= htmlspecialchars($ta['status']) ?>"><span class="adm-badge adm-badge-<?= htmlspecialchars($st) ?>"><?= htmlspecialchars($ta['status_' . $st] ?? $st) ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="adm-card">
    <div class="adm-card-head"><h2><?= htmlspecialchars($ta['quick_actions']) ?></h2></div>
    <div class="adm-card-body padded adm-quick-actions">
        <a href="<?= fl_admin_url('projects.php') ?>" class="adm-btn adm-btn-primary"><i class="fas fa-folder-open"></i> <?= htmlspecialchars($ta['projects']) ?></a>
        <a href="<?= fl_admin_url('proposals.php') ?>" class="adm-btn adm-btn-outline"><i class="fas fa-list"></i> <?= htmlspecialchars($ta['proposals']) ?></a>
        <a href="<?= fl_url('index.php') ?>" class="adm-btn adm-btn-outline" target="_blank"><i class="fas fa-globe"></i> <?= htmlspecialchars($ta['view_site']) ?></a>
    </div>
</div>

<?php require __DIR__ . '/includes/layout-end.php'; ?>