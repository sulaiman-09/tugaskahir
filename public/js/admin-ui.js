document.addEventListener('DOMContentLoaded', function() {
    // Generic delete confirm for forms with .delete-form
    document.querySelectorAll('.delete-form').forEach(function(form) {
        if (form._hasHandler) return;
        form._hasHandler = true;
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            var name = form.dataset.name || 'this record';
            if (confirm('Hapus ' + name + '? Aksi ini tidak dapat dibatalkan.')) {
                form.submit();
            }
        });
    });

    // enable bootstrap tooltips if available
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        var tipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tipTriggerList.map(function (el) { return new bootstrap.Tooltip(el); });
    }
});
