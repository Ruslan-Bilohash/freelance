(function () {
    'use strict';

    var header = document.getElementById('flsHeader');
    var menuBtn = document.getElementById('flsMenuBtn');
    var panel = document.getElementById('flsMobilePanel');
    var overlay = document.getElementById('flsOverlay');
    var langBtn = document.getElementById('flsLangBtn');
    var langMenu = document.getElementById('flsLangMenu');

    function closeNav() {
        if (!header) return;
        header.classList.remove('nav-open');
        document.body.classList.remove('fls-nav-open');
        if (menuBtn) menuBtn.setAttribute('aria-expanded', 'false');
        if (panel) panel.hidden = true;
        if (overlay) {
            overlay.classList.remove('is-open');
            overlay.hidden = true;
        }
    }

    function openNav() {
        if (!header) return;
        header.classList.add('nav-open');
        document.body.classList.add('fls-nav-open');
        if (menuBtn) menuBtn.setAttribute('aria-expanded', 'true');
        if (panel) panel.hidden = false;
        if (overlay) {
            overlay.hidden = false;
            requestAnimationFrame(function () { overlay.classList.add('is-open'); });
        }
    }

    if (menuBtn && header) {
        menuBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            if (header.classList.contains('nav-open')) closeNav();
            else openNav();
        });
    }
    if (overlay) overlay.addEventListener('click', closeNav);
    if (panel) {
        panel.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', closeNav);
        });
    }
    window.addEventListener('resize', function () {
        if (window.innerWidth > 900) closeNav();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeNav();
    });

    if (langBtn && langMenu) {
        langBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            var open = langMenu.hidden;
            langMenu.hidden = !open;
            langBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
        langMenu.addEventListener('click', function (e) { e.stopPropagation(); });
        document.addEventListener('click', function () {
            langMenu.hidden = true;
            langBtn.setAttribute('aria-expanded', 'false');
        });
    }
})();