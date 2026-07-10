
        </main>
    </div>
</div>
<script>
(function () {
    var sidebar = document.getElementById('admSidebar');
    var btn = document.getElementById('admMenuBtn');
    var overlay = document.getElementById('admOverlay');

    function closeSidebar() {
        sidebar?.classList.remove('open');
        overlay?.classList.remove('is-open');
        if (overlay) overlay.hidden = true;
        document.body.classList.remove('adm-nav-open');
    }

    function openSidebar() {
        sidebar?.classList.add('open');
        if (overlay) {
            overlay.hidden = false;
            requestAnimationFrame(function () { overlay.classList.add('is-open'); });
        }
        document.body.classList.add('adm-nav-open');
    }

    btn?.addEventListener('click', function () {
        if (sidebar?.classList.contains('open')) closeSidebar();
        else openSidebar();
    });
    overlay?.addEventListener('click', closeSidebar);
    sidebar?.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', closeSidebar);
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeSidebar();
    });
    window.addEventListener('resize', function () {
        if (window.innerWidth > 900) closeSidebar();
    });
})();
</script>
</body>
</html>