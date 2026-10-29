(function () {
    var timeline = document.getElementById('timeline');
    var progress = document.getElementById('timelineProgress');
    if (!timeline || !progress) return;

    // Edit these stops to change the fill's color mix.
    var GRADIENT = 'linear-gradient(to bottom, #2f6fed, #14b887, #8b5cf6)';

    var firstDot = timeline.querySelector('.timeline-dot');
    if (!firstDot) return;

    var spanHeight = 0;
    var ticking = false;
    var resizeTicking = false;

    function layout() {
        // Measure where the first dot's center sits, and where #timeline's
        // own bottom edge is, relative to #timeline's top. Doing this in px
        // (not %) means the fill starts exactly at the first dot — nothing
        // shows above it — and now runs all the way to the bottom of the
        // container itself, covering the full last entry's content.
        var timelineTop = timeline.getBoundingClientRect().top;
        var startY = firstDot.getBoundingClientRect().top + firstDot.offsetHeight / 2 - timelineTop;
        var endY = timeline.scrollHeight;
        spanHeight = Math.max(0, endY - startY);

        progress.style.top = startY + 'px';
        progress.style.backgroundImage = GRADIENT;
        // Fixed px background-size anchors colors to position: as the bar
        // grows, it reveals more of one continuous gradient rather than
        // re-stretching the same colors to fit a shorter/taller bar.
        progress.style.backgroundSize = '100% ' + spanHeight + 'px';
    }

    function update() {
        var triggerLine = window.innerHeight * 0.5; // middle of the viewport
        var firstDotY = firstDot.getBoundingClientRect().top + firstDot.offsetHeight / 2;

        var progressed = triggerLine - firstDotY;
        progressed = Math.max(0, Math.min(progressed, spanHeight));
        progress.style.height = progressed + 'px';

        ticking = false;
    }

    function onScroll() {
        if (!ticking) {
            requestAnimationFrame(update);
            ticking = true;
        }
    }

    function onResize() {
        if (resizeTicking) return;
        resizeTicking = true;
        requestAnimationFrame(function () {
            layout();
            update();
            resizeTicking = false;
        });
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onResize);

    layout();
    update();

    function refresh() {
        layout();
        update();
    }

    window.addEventListener('load', refresh, { once: true });
    if (document.fonts && document.fonts.ready) {
        document.fonts.ready.then(refresh);
    }
})();
