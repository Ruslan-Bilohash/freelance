<?php
require_once __DIR__ . '/init.php';
fl_admin_require();
$admin_page = 'proposals';
$page_title = $ta['proposals'];

$proposals = fl_load_proposals();
$flash = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['proposal_id']) && !empty($_POST['status'])) {
    $propId = $_POST['proposal_id'];
    $newStatus = $_POST['status'];
    if (in_array($newStatus, ['pending', 'shortlisted', 'accepted', 'rejected'], true)) {
        foreach ($proposals as &$b) {
            if (($b['id'] ?? '') === $propId) {
                $b['status'] = $newStatus;
                break;
            }
        }
        unset($b);
        if (fl_save_proposals($proposals)) {
            $flash = 'success';
        } else {
            $flash = 'error';
        }
    }
}

require __DIR__ . '/includes/layout.php';
?>

<?php if ($flash === 'success'): ?>
<div class="adm-alert adm-alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($ta['saved']) ?></div>
<?php elseif ($flash === 'error'): ?>
<div class="adm-alert adm-alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($ta['error']) ?></div>
<?php endif; ?>

<div class="adm-card">
    <div class="adm-card-head">
        <h2><?= htmlspecialchars($ta['all_proposals']) ?> (<?= count($proposals) ?>)</h2>
    </div>
    <div class="adm-card-body">
        <?php if (empty($proposals)): ?>
        <p style="padding:32px;text-align:center;color:var(--adm-muted)"><?= htmlspecialchars($ta['no_proposals']) ?></p>
        <?php else: ?>
        <div class="adm-table-wrap">
        <table class="adm-table adm-table--cards">
            <thead>
                <tr>
                    <th><?= htmlspecialchars($ta['ref']) ?></th>
                    <th><?= htmlspecialchars($ta['freelancer']) ?></th>
                    <th><?= htmlspecialchars($ta['project']) ?></th>
                    <th><?= htmlspecialchars($ta['amount']) ?></th>
                    <th><?= htmlspecialchars($ta['delivery_days'] ?? 'Days') ?></th>
                    <th><?= htmlspecialchars($ta['status']) ?></th>
                    <th><?= htmlspecialchars($ta['created']) ?></th>
                    <th><?= htmlspecialchars($ta['actions']) ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($proposals as $b):
                    $st = $b['status'] ?? 'pending';
                ?>
                <tr>
                    <td data-label="<?= htmlspecialchars($ta['ref']) ?>"><code style="font-size:11px"><?= htmlspecialchars($b['ref'] ?? $b['id'] ?? '') ?></code></td>
                    <td data-label="<?= htmlspecialchars($ta['freelancer']) ?>">
                        <strong><?= htmlspecialchars($b['freelancer'] ?? '') ?></strong><br>
                        <small style="color:var(--adm-muted)"><?= htmlspecialchars($b['email'] ?? '') ?></small>
                    </td>
                    <td data-label="<?= htmlspecialchars($ta['project']) ?>"><?= htmlspecialchars($b['project_name'] ?? '') ?></td>
                    <td data-label="<?= htmlspecialchars($ta['amount']) ?>"><strong><?= fl_price((int)($b['bid_amount'] ?? 0)) ?></strong></td>
                    <td data-label="<?= htmlspecialchars($ta['delivery_days'] ?? 'Days') ?>"><?= (int)($b['delivery_days'] ?? 0) ?>d</td>
                    <td data-label="<?= htmlspecialchars($ta['status']) ?>"><span class="adm-badge adm-badge-<?= htmlspecialchars($st) ?>"><?= htmlspecialchars($ta['status_' . $st] ?? $st) ?></span></td>
                    <td data-label="<?= htmlspecialchars($ta['created']) ?>" style="font-size:12px;color:var(--adm-muted)"><?= isset($b['created_at']) ? date('d.m.Y H:i', strtotime($b['created_at'])) : '—' ?></td>
                    <td data-label="<?= htmlspecialchars($ta['actions']) ?>">
                        <form method="post" style="display:flex;gap:4px;align-items:center">
                            <input type="hidden" name="proposal_id" value="<?= htmlspecialchars($b['id'] ?? '') ?>">
                            <select name="status" style="padding:4px 8px;border-radius:4px;border:1px solid var(--adm-border);font-size:12px">
                                <option value="pending" <?= $st === 'pending' ? 'selected' : '' ?>><?= htmlspecialchars($ta['status_pending']) ?></option>
                                <option value="shortlisted" <?= $st === 'shortlisted' ? 'selected' : '' ?>><?= htmlspecialchars($ta['status_shortlisted']) ?></option>
                                <option value="accepted" <?= $st === 'accepted' ? 'selected' : '' ?>><?= htmlspecialchars($ta['status_accepted']) ?></option>
                                <option value="rejected" <?= $st === 'rejected' ? 'selected' : '' ?>><?= htmlspecialchars($ta['status_rejected']) ?></option>
                            </select>
                            <button type="submit" class="adm-btn adm-btn-outline adm-btn-sm"><?= htmlspecialchars($ta['change_status'] ?? 'Save') ?></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/includes/layout-end.php'; ?>