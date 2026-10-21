@php($themeKey = $themeKey ?? 'portfolio-theme')
<script>
    (() => {
        const key = @json($themeKey);
        const stored = localStorage.getItem(key);
        const theme = stored === 'light' || stored === 'dark'
            ? stored
            : (window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark');

        document.documentElement.dataset.theme = theme;
    })();
</script>
