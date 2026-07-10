<?php $current = fls_langs()[$lang] ?? fls_langs()['no']; ?>
<div class="fls-lang-dropdown" id="flsLangDropdown">
    <button type="button" class="fls-lang-btn" id="flsLangBtn" aria-expanded="false">
        <?= $current['flag'] ?> <?= htmlspecialchars($current['label']) ?> <i class="fas fa-chevron-down"></i>
    </button>
    <ul class="fls-lang-menu" id="flsLangMenu" hidden>
        <?php foreach (fls_langs() as $code => $info): ?>
        <li><a href="<?= htmlspecialchars(fls_lang_url($code)) ?>" class="<?= $lang === $code ? 'active' : '' ?>"><?= $info['flag'] ?> <?= htmlspecialchars($info['name']) ?></a></li>
        <?php endforeach; ?>
    </ul>
</div>