<?php
$p = $search_params ?? fl_search_params();
?>
<form class="fl-search" action="<?= fl_url('search.php') ?>" method="get" role="search">
    <div class="fl-search-field" style="flex:2 1 240px">
        <label for="q"><?= htmlspecialchars($t['search']['keyword']) ?></label>
        <input type="search" id="q" name="q" value="<?= htmlspecialchars($p['q']) ?>"
               placeholder="<?= htmlspecialchars($t['search']['placeholder']) ?>" autocomplete="off">
    </div>
    <div class="fl-search-field">
        <label for="category"><?= htmlspecialchars($t['search']['category']) ?></label>
        <select id="category" name="category">
            <option value=""><?= htmlspecialchars($t['search']['all_cats']) ?></option>
            <?php foreach (fl_categories() as $cat): ?>
            <option value="<?= htmlspecialchars($cat) ?>" <?= $p['category'] === $cat ? 'selected' : '' ?>>
                <?= htmlspecialchars($t['categories'][$cat] ?? $cat) ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="fl-search-field">
        <label for="status"><?= htmlspecialchars($t['search_page']['status']) ?></label>
        <select id="status" name="status">
            <option value=""><?= htmlspecialchars($t['search_page']['status_all']) ?></option>
            <option value="open" <?= $p['status'] === 'open' ? 'selected' : '' ?>><?= htmlspecialchars($t['search_page']['status_open']) ?></option>
            <option value="urgent" <?= $p['status'] === 'urgent' ? 'selected' : '' ?>><?= htmlspecialchars($t['search_page']['status_urgent']) ?></option>
        </select>
    </div>
    <div class="fl-search-btn-wrap">
        <button type="submit" class="fl-search-btn"><i class="fas fa-search"></i> <?= htmlspecialchars($t['search']['search_btn']) ?></button>
    </div>
</form>