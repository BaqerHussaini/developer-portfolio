(function () {
    var groups = document.querySelectorAll('[data-filter-group]');

    groups.forEach(function (group) {
        var target = document.querySelector(group.getAttribute('data-target'));
        if (!target) return;

        var buttons = group.querySelectorAll('[data-filter]');
        var items = target.querySelectorAll('[data-category]');

        function applyFilter(filter) {
            buttons.forEach(function (button) {
                button.setAttribute('aria-pressed', String(button.getAttribute('data-filter') === filter));
            });

            items.forEach(function (item) {
                var categories = item.getAttribute('data-category').split(' ');
                item.hidden = filter !== 'all' && categories.indexOf(filter) === -1;
            });
        }

        group.addEventListener('click', function (event) {
            var button = event.target.closest('[data-filter]');
            if (!button || !group.contains(button)) return;
            applyFilter(button.getAttribute('data-filter'));
        });

        applyFilter('all');
    });
})();
