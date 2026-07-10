<?php
/** Language dropdown — expects $lang, fl_langs(), fl_lang_url() */
$current = fl_langs()[$lang] ?? fl_langs()['no'];
?>
<div class="fl-lang-dropdown" id="flLangDropdown">
    <button type="button" class="fl-lang-dropdown-btn" id="flLangBtn" aria-expanded="false" aria-haspopup="listbox" aria-controls="flLangMenu">
        <span class="fl-lang-dropdown-current"><?= $current['flag'] ?> <?= htmlspecialchars($current['label']) ?></span>
        <i class="fas fa-chevron-down" aria-hidden="true"></i>
    </button>
    <ul class="fl-lang-dropdown-menu" id="flLangMenu" role="listbox" hidden>
        <?php foreach (fl_langs() as $code => $info): ?>
        <li role="option">
            <a href="<?= htmlspecialchars(fl_lang_url($code)) ?>" class="<?= $lang === $code ? 'active' : '' ?>" <?= $lang === $code ? 'aria-current="true"' : '' ?>>
                <span class="fl-lang-flag"><?= $info['flag'] ?></span>
                <span class="fl-lang-name"><?= htmlspecialchars($info['name']) ?></span>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
</div>