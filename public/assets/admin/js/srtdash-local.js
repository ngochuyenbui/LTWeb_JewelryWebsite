(function () {
    var toggle = document.querySelector('[data-sidebar-toggle]');
    var sidebar = document.querySelector('[data-sidebar]');

    if (toggle && sidebar) {
        toggle.addEventListener('click', function () {
            sidebar.classList.toggle('is-open');
        });
    }

    document.querySelectorAll('[data-confirm]').forEach(function (link) {
        link.addEventListener('click', function (event) {
            if (!window.confirm(link.getAttribute('data-confirm'))) {
                event.preventDefault();
            }
        });
    });

    document.querySelectorAll('[data-admin-form]').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            var invalid = false;
            form.querySelectorAll('[required]').forEach(function (field) {
                if (!String(field.value || '').trim()) {
                    invalid = true;
                }
            });
            if (invalid) {
                event.preventDefault();
                window.alert('Vui lòng nhập đầy đủ các trường bắt buộc.');
            }
        });
    });

    document.querySelectorAll('[data-image-form]').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            var input = form.querySelector('input[type="file"]');
            if (!input || !input.files || !input.files.length) {
                event.preventDefault();
                window.alert('Vui lòng chọn hình ảnh.');
                return;
            }
            var file = input.files[0];
            var allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (allowed.indexOf(file.type) === -1 || file.size > 2 * 1024 * 1024) {
                event.preventDefault();
                window.alert('Hình ảnh phải là jpg, png, gif hoặc webp và tối đa 2MB.');
            }
        });
    });
})();
