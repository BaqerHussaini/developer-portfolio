(function () {
    var STORAGE_KEY = 'portfolio-theme';
    var root = document.documentElement;
    var media = window.matchMedia('(prefers-color-scheme: dark)');

    function readStoredTheme() {
        try {
            var value = localStorage.getItem(STORAGE_KEY);
            return value === 'dark' || value === 'light' ? value : null;
        } catch (error) {
            return null;
        }
    }

    function currentTheme() {
        return root.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
    }

    function updateControls(theme) {
        var isDark = theme === 'dark';

        document.querySelectorAll('[data-theme-toggle]').forEach(function (button) {
            var label = button.querySelector('.theme-toggle-label');
            button.setAttribute('aria-label', isDark ? 'Switch to light mode' : 'Switch to dark mode');

            if (label) {
                label.textContent = isDark ? 'Light mode' : 'Dark mode';
            }
        });
    }

    function applyTheme(theme, persist) {
        root.setAttribute('data-theme', theme);

        if (persist) {
            try {
                localStorage.setItem(STORAGE_KEY, theme);
            } catch (error) {}
        }

        updateControls(theme);
    }

    applyTheme(readStoredTheme() || (media.matches ? 'dark' : 'light'), false);

    document.addEventListener('click', function (event) {
        var button = event.target.closest && event.target.closest('[data-theme-toggle]');

        if (!button) {
            return;
        }

        applyTheme(currentTheme() === 'dark' ? 'light' : 'dark', true);
    });

    function syncControls() {
        updateControls(currentTheme());

        if (!document.querySelector('[data-theme-toggle]')) {
            window.requestAnimationFrame(syncControls);
        }
    }

    window.requestAnimationFrame(syncControls);
    document.addEventListener('readystatechange', syncControls);
    document.addEventListener('DOMContentLoaded', syncControls);

    function handleSystemThemeChange(event) {
        if (!readStoredTheme()) {
            applyTheme(event.matches ? 'dark' : 'light', false);
        }
    }

    if (media.addEventListener) {
        media.addEventListener('change', handleSystemThemeChange);
    } else {
        media.addListener(handleSystemThemeChange);
    }
})();
