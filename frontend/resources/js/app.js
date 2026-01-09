import './bootstrap';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

document.addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-delete');
    if (!btn) return;

    const form = btn.closest('.form-delete');
    const text = btn.dataset.text || 'Data ini akan dihapus permanen';

    Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
});

