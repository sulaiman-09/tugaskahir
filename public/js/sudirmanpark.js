document.addEventListener('DOMContentLoaded', function() {
    // column toggle (works with index table)
    document.querySelectorAll('.column-toggle').forEach(function(cb) {
        cb.addEventListener('change', function() {
            var colIndex = parseInt(cb.getAttribute('data-column')) + 1;
            document.querySelectorAll('table tr').forEach(function(row) {
                var cell = row.querySelector('*:nth-child(' + colIndex + ')');
                if (cell) cell.style.display = cb.checked ? '' : 'none';
            });
        });
    });

    // status change select
    document.querySelectorAll('.status-change').forEach(function(sel) {
        sel.addEventListener('change', function() {
            var id = this.getAttribute('data-id');
            var value = this.value;
            var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch('/sudirmanpark/sudirmanpark/' + id + '/status', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: value })
            }).then(function(res) {
                if (!res.ok) throw new Error('Network response was not ok');
                return res.json();
            }).then(function(data) {
                // optional: show toast
                console.log('status updated', data);
            }).catch(function(err) {
                console.error(err);
                alert('Gagal mengubah status');
            });
        });
    });
});
