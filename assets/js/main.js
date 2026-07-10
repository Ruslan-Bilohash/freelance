(function () {
    'use strict';

    // Header dropdowns — inline script in includes/header.php (window.FL_HEADER_DROPDOWNS)

    // Countdown timers
    function pad(n) {
        return n < 10 ? '0' + n : String(n);
    }

    function formatShort(ms) {
        if (ms <= 0) return '00:00:00';
        var s = Math.floor(ms / 1000);
        var d = Math.floor(s / 86400);
        s %= 86400;
        var h = Math.floor(s / 3600);
        s %= 3600;
        var m = Math.floor(s / 60);
        s %= 60;
        if (d > 0) return d + 'd ' + pad(h) + ':' + pad(m) + ':' + pad(s);
        return pad(h) + ':' + pad(m) + ':' + pad(s);
    }

    function updateCountdowns() {
        var now = Date.now();
        document.querySelectorAll('[data-countdown]').forEach(function (el) {
            var end = parseInt(el.getAttribute('data-countdown'), 10) * 1000;
            var left = end - now;

            if (el.classList.contains('fl-countdown-lg')) {
                var dEl = el.querySelector('.cd-d');
                var hEl = el.querySelector('.cd-h');
                var mEl = el.querySelector('.cd-m');
                var sEl = el.querySelector('.cd-s');
                if (left <= 0) {
                    if (dEl) dEl.textContent = '00';
                    if (hEl) hEl.textContent = '00';
                    if (mEl) mEl.textContent = '00';
                    if (sEl) sEl.textContent = '00';
                    return;
                }
                var sec = Math.floor(left / 1000);
                var days = Math.floor(sec / 86400);
                sec %= 86400;
                var hours = Math.floor(sec / 3600);
                sec %= 3600;
                var mins = Math.floor(sec / 60);
                sec %= 60;
                if (dEl) dEl.textContent = pad(days);
                if (hEl) hEl.textContent = pad(hours);
                if (mEl) mEl.textContent = pad(mins);
                if (sEl) sEl.textContent = pad(sec);
            } else {
                var val = el.querySelector('.fl-countdown-val');
                if (val) {
                    val.textContent = left <= 0 ? '00:00:00' : formatShort(left);
                }
            }
        });
    }

    updateCountdowns();
    setInterval(updateCountdowns, 1000);

    // Project detail tabs
    document.querySelectorAll('.fl-tabs').forEach(function (tablist) {
        var tabs = tablist.querySelectorAll('.fl-tab[data-tab]');
        if (!tabs.length) return;

        var container = tablist.parentElement;

        function activate(tabId) {
            tabs.forEach(function (btn) {
                var on = btn.getAttribute('data-tab') === tabId;
                btn.classList.toggle('active', on);
                btn.setAttribute('aria-selected', on ? 'true' : 'false');
            });
            container.querySelectorAll('.fl-tab-panel').forEach(function (panel) {
                var match = panel.id === 'tab-' + tabId;
                panel.classList.toggle('active', match);
                panel.hidden = !match;
            });
        }

        tablist.setAttribute('role', 'tablist');
        tabs.forEach(function (btn, i) {
            btn.setAttribute('role', 'tab');
            btn.setAttribute('aria-controls', 'tab-' + btn.getAttribute('data-tab'));
            if (btn.classList.contains('active')) {
                btn.setAttribute('aria-selected', 'true');
            } else {
                btn.setAttribute('aria-selected', 'false');
                btn.setAttribute('tabindex', '-1');
            }
            btn.addEventListener('click', function () {
                activate(btn.getAttribute('data-tab'));
                tabs.forEach(function (b) { b.setAttribute('tabindex', '-1'); });
                btn.setAttribute('tabindex', '0');
            });
            btn.addEventListener('keydown', function (e) {
                var idx = Array.prototype.indexOf.call(tabs, btn);
                if (e.key === 'ArrowRight') {
                    e.preventDefault();
                    tabs[(idx + 1) % tabs.length].click();
                    tabs[(idx + 1) % tabs.length].focus();
                } else if (e.key === 'ArrowLeft') {
                    e.preventDefault();
                    tabs[(idx - 1 + tabs.length) % tabs.length].click();
                    tabs[(idx - 1 + tabs.length) % tabs.length].focus();
                }
            });
        });
    });
})();
