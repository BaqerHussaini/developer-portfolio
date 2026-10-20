(() => {
    'use strict';

    const root = document.documentElement;
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* ---------- theme ---------- */

    const stored = localStorage.getItem('portfolio-theme');
    const preferred = stored || 'dark';
    root.dataset.theme = preferred;

    const themeToggle = document.getElementById('themeToggle');

    themeToggle?.addEventListener('click', () => {
        const next = root.dataset.theme === 'dark' ? 'light' : 'dark';
        root.dataset.theme = next;
        localStorage.setItem('portfolio-theme', next);
    });

    /* ---------- header state ---------- */

    const header = document.querySelector('.site-header');
    const navToggle = document.getElementById('navToggle');
    const mainNav = document.getElementById('mainNav');

    const onScroll = () => header?.classList.toggle('scrolled', window.scrollY > 12);
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });

    navToggle?.addEventListener('click', () => {
        const open = mainNav.classList.toggle('open');
        navToggle.setAttribute('aria-expanded', String(open));
        const [top, mid, bottom] = navToggle.querySelectorAll('span');
        top.style.transform = open ? 'translateY(7px) rotate(45deg)' : '';
        mid.style.opacity = open ? '0' : '1';
        bottom.style.transform = open ? 'translateY(-7px) rotate(-45deg)' : '';
    });

    const closeNav = () => {
        mainNav?.classList.remove('open');
        navToggle?.setAttribute('aria-expanded', 'false');
        navToggle?.querySelectorAll('span').forEach((line) => {
            line.style.transform = '';
            line.style.opacity = '1';
        });
    };

    mainNav?.querySelectorAll('a').forEach((link) => link.addEventListener('click', closeNav));

    /* ---------- active section ---------- */

    const sections = [...document.querySelectorAll('main section[id]')];
    const navLinks = [...document.querySelectorAll('.main-nav .nav-link')]
        .filter((link) => link.getAttribute('href')?.startsWith('#'));

    if (sections.length && navLinks.length) {
        const spy = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                navLinks.forEach((link) => {
                    link.classList.toggle('active', link.getAttribute('href') === `#${entry.target.id}`);
                });
            });
        }, { rootMargin: '-45% 0px -50% 0px' });

        sections.forEach((section) => spy.observe(section));
    }

    /* ---------- reveal on scroll ---------- */

    const revealables = [...document.querySelectorAll('.reveal')];

    if (reduceMotion || !('IntersectionObserver' in window)) {
        revealables.forEach((el) => el.classList.add('visible'));
    } else {
        const reveal = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (!entry.isIntersecting) return;
                setTimeout(() => entry.target.classList.add('visible'), index * 70);
                reveal.unobserve(entry.target);
            });
        }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });

        revealables.forEach((el) => reveal.observe(el));
    }

    /* ---------- animated skill bars ---------- */

    const skills = [...document.querySelectorAll('.skill-bar i')];

    if (skills.length) {
        const fill = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                entry.target.style.width = entry.target.dataset.level + '%';
                fill.unobserve(entry.target);
            });
        }, { threshold: 0.4 });

        skills.forEach((bar) => fill.observe(bar));
    }

    /* ---------- rotating hero phrases ---------- */

    const rotator = document.getElementById('typedText');

    let phrases = [];
    try {
        phrases = JSON.parse(rotator?.dataset.phrases || '[]');
    } catch (error) {
        phrases = [];
    }

    if (rotator && phrases.length) {
        // Reserve room for the tallest phrase so nothing below ever shifts.
        const probe = document.createElement('span');
        probe.style.cssText = 'position:absolute;visibility:hidden;left:-9999px;';
        rotator.parentElement.appendChild(probe);

        probe.style.width = rotator.offsetWidth + 'px';
        probe.style.font = getComputedStyle(rotator).font;
        probe.style.letterSpacing = getComputedStyle(rotator).letterSpacing;

        let tallest = 0;
        phrases.forEach((phrase) => {
            probe.textContent = phrase;
            tallest = Math.max(tallest, probe.offsetHeight);
        });

        probe.remove();
        rotator.style.minHeight = tallest + 'px';

        if (reduceMotion || phrases.length < 2) return;

        let index = 0;

        const rotate = () => {
            rotator.classList.add('is-swapping');

            window.setTimeout(() => {
                index = (index + 1) % phrases.length;
                rotator.textContent = phrases[index];
                rotator.classList.remove('is-swapping');
            }, 420);
        };

        const interval = window.setInterval(rotate, 3400);

        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                window.clearInterval(interval);
            } else {
                window.setTimeout(rotate, 1000);
            }
        });
    }

    /* ---------- stat counters ---------- */

    const counters = [...document.querySelectorAll('[data-count-to]')];

    if (counters.length && !reduceMotion) {
        const bump = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                bump.unobserve(entry.target);

                const raw = entry.target.dataset.countTo;
                const value = parseFloat(raw.replace(/[^0-9.]/g, ''));
                const suffix = raw.replace(/[0-9.]/g, '');
                if (!Number.isFinite(value)) return;

                const start = performance.now();
                const duration = 1200;

                const tick = (now) => {
                    const progress = Math.min((now - start) / duration, 1);
                    const eased = 1 - Math.pow(1 - progress, 3);
                    entry.target.textContent = Math.round(value * eased) + suffix;

                    if (progress < 1) requestAnimationFrame(tick);
                };

                requestAnimationFrame(tick);
            });
        }, { threshold: 0.6 });

        counters.forEach((el) => bump.observe(el));
    }

    /* ---------- card tilt ---------- */

    const card = document.querySelector('.profile-card');
    const heroVisual = document.querySelector('.hero-visual');

    if (card && heroVisual && !reduceMotion && window.matchMedia('(hover: hover)').matches) {
        heroVisual.addEventListener('mousemove', (event) => {
            const box = heroVisual.getBoundingClientRect();
            const x = (event.clientX - box.left) / box.width - 0.5;
            const y = (event.clientY - box.top) / box.height - 0.5;
            card.style.transform = `perspective(900px) rotateY(${x * 9}deg) rotateX(${-y * 9}deg) translateY(-4px)`;
        });

        heroVisual.addEventListener('mouseleave', () => {
            card.style.transform = '';
        });
    }

    /* ---------- contact form ---------- */

    const form = document.querySelector('.contact-form');

    form?.addEventListener('submit', () => {
        const button = form.querySelector('button[type="submit"]');

        if (!button) return;

        button.disabled = true;
        button.textContent = 'Sending…';
    });
})();
