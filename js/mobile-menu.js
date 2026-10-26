(function () {
    var sidebar = document.getElementById('sidebar');
    var overlay = document.getElementById('sidebarOverlay');
    var openBtn = document.getElementById('menuToggle');
    var closeBtn = document.getElementById('sidebarClose');

    if (!sidebar || !overlay || !openBtn || !closeBtn) return;

    var main = document.querySelector('.main-menu');
    var topbar = document.querySelector('.mobile-topbar');
    var desktop = window.matchMedia('(min-width: 680px)');
    var isOpen = false;
    var lastFocused = null;

    function focusableItems() {
        return Array.prototype.filter.call(
            sidebar.querySelectorAll('a[href], button:not([disabled])'),
            function (el) { return el.offsetParent !== null; }
        );
    }

    function syncSidebarInert() {
        sidebar.inert = !desktop.matches && !isOpen;
    }

    function openMenu() {
        if (isOpen) return;

        lastFocused = document.activeElement;
        isOpen = true;
        sidebar.classList.add('open');
        overlay.classList.add('visible');
        document.body.classList.add('menu-open');
        openBtn.setAttribute('aria-expanded', 'true');

        sidebar.inert = false;
        if (main) main.inert = true;
        if (topbar) topbar.inert = true;

        closeBtn.focus();
    }

    function closeMenu(restoreFocus) {
        if (!isOpen) return;

        isOpen = false;
        sidebar.classList.remove('open');
        overlay.classList.remove('visible');
        document.body.classList.remove('menu-open');
        openBtn.setAttribute('aria-expanded', 'false');

        if (main) main.inert = false;
        if (topbar) topbar.inert = false;
        syncSidebarInert();

        if (restoreFocus === false) return;
        if (lastFocused && lastFocused !== document.body && document.contains(lastFocused)) {
            lastFocused.focus();
        } else {
            openBtn.focus();
        }
    }

    function handleKeydown(e) {
        if (!isOpen) return;

        if (e.key === 'Escape') {
            e.preventDefault();
            closeMenu();
            return;
        }

        if (e.key !== 'Tab') return;

        var items = focusableItems();
        if (!items.length) return;

        var first = items[0];
        var last = items[items.length - 1];
        var active = document.activeElement;

        if (e.shiftKey && (active === first || !sidebar.contains(active))) {
            e.preventDefault();
            last.focus();
        } else if (!e.shiftKey && (active === last || !sidebar.contains(active))) {
            e.preventDefault();
            first.focus();
        }
    }

    openBtn.addEventListener('click', openMenu);
    closeBtn.addEventListener('click', function () { closeMenu(); });
    overlay.addEventListener('click', function () { closeMenu(); });
    document.addEventListener('keydown', handleKeydown);

    sidebar.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', function () { closeMenu(false); });
    });

    function handleBreakpointChange() {
        if (desktop.matches) {
            closeMenu(false);
        }
        syncSidebarInert();
    }

    if (desktop.addEventListener) {
        desktop.addEventListener('change', handleBreakpointChange);
    } else {
        desktop.addListener(handleBreakpointChange);
    }

    syncSidebarInert();
})();
