(() => {
    'use strict';

    /* ---------- sidebar toggle ---------- */

    const sidebar = document.getElementById('sidebar');
    const menuBtn = document.getElementById('menuBtn');
    const scrim = document.getElementById('sidebarScrim');

    const setSidebar = (open) => {
        sidebar?.classList.toggle('open', open);
        scrim?.toggleAttribute('hidden', !open);
        menuBtn?.setAttribute('aria-expanded', String(open));
    };

    menuBtn?.addEventListener('click', () => setSidebar(!sidebar?.classList.contains('open')));
    scrim?.addEventListener('click', () => setSidebar(false));
    sidebar?.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => setSidebar(false)));

    window.matchMedia('(min-width: 901px)').addEventListener('change', (event) => {
        if (event.matches) setSidebar(false);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') setSidebar(false);
    });

    /* ---------- settings tabs ---------- */

    const tabs = [...document.querySelectorAll('.tabs button')];
    const panels = [...document.querySelectorAll('.tab-panel')];

    const showTab = (name) => {
        tabs.forEach((tab) => tab.classList.toggle('active', tab.dataset.tab === name));
        panels.forEach((panel) => panel.classList.toggle('active', panel.dataset.panel === name));
    };

    tabs.forEach((tab) => tab.addEventListener('click', () => showTab(tab.dataset.tab)));

    if (tabs.length) showTab(tabs[0].dataset.tab);

    /* ---------- repeaters (add / remove rows) ---------- */

    document.querySelectorAll('[data-repeater]').forEach((repeater) => {
        const id = repeater.dataset.repeater;

        const addRow = () => {
            const template = document.getElementById(id);
            if (!template) return;

            const indices = [...repeater.querySelectorAll('[name]')]
                .map((field) => field.name.match(/\[(\d+)\]/))
                .filter(Boolean)
                .map((match) => Number(match[1]));
            const index = indices.length ? Math.max(...indices) + 1 : 0;
            const holder = document.createElement('div');
            holder.innerHTML = template.innerHTML.replace(/__INDEX__/g, index);

            const row = holder.firstElementChild;
            if (!row) return;

            repeater.querySelector('.repeater-empty')?.remove();
            row.querySelectorAll('input, textarea').forEach((input) => { input.value = ''; });
            repeater.appendChild(row);
            row.querySelector('input')?.focus();
        };

        document.querySelector(`[data-add="${id}"]`)?.addEventListener('click', addRow);

        repeater.addEventListener('click', (event) => {
            const remove = event.target.closest('.row-remove');
            if (!remove) return;

            remove.closest('.repeater-row')?.remove();

            if (!repeater.querySelector('.repeater-row')) {
                const empty = document.createElement('p');
                empty.className = 'repeater-empty';
                empty.textContent = 'Nothing here yet — use the button below to add one.';
                repeater.appendChild(empty);
            }
        });
    });

    /* ---------- confirm destructive actions ---------- */

    document.querySelectorAll('[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (!window.confirm(form.dataset.confirm)) event.preventDefault();
        });
    });

    /* ---------- image preview ---------- */

    const previews = new Map();

    document.querySelectorAll('input[type="file"][id]').forEach((input) => {
        const box = input.closest('.image-field')?.querySelector('.image-preview');
        if (!box) return;

        input.addEventListener('change', () => {
            const file = input.files?.[0];
            if (!file) return;

            previews.get(input.id)?.remove();
            const preview = document.createElement('img');
            preview.alt = 'Selected image preview';
            preview.src = URL.createObjectURL(file);
            box.replaceChildren(preview);
            previews.set(input.id, preview);
        });
    });

    document.querySelectorAll('[data-preview]').forEach((button) => {
        button.addEventListener('click', () => {
            document.getElementById(button.dataset.preview)?.click();
        });
    });

    /* ---------- slug helper ---------- */

    const title = document.querySelector('[data-slug-source]');
    const slug = document.querySelector('[data-slug-target]');

    if (title && slug && !slug.value) {
        title.addEventListener('input', () => {
            slug.value = title.value
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-|-$/g, '');
        });
    }
})();
