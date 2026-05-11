/**
 * SweetAlert2-powered "confirm delete" handler.
 *
 * Any element with [data-confirm-delete] (typically a button or an <a>)
 * intercepts the click, asks for confirmation, then either:
 *   1. submits the closest <form>, or
 *   2. fires an axios DELETE to the URL in [data-url] and reloads the
 *      enclosing DataTable (if any) on success.
 */
import Swal from 'sweetalert2';

export function initConfirmDelete() {
    document.removeEventListener('click', handler, true);
    document.addEventListener('click', handler, true);
}

async function handler(e) {
    const trigger = e.target.closest('[data-confirm-delete]');
    if (!trigger) return;
    e.preventDefault();
    e.stopPropagation();

    const t = window.$t || ((k, fb) => fb || k);

    const result = await Swal.fire({
        title: t('common.confirm_delete_title', 'Are you sure?'),
        text: t('common.confirm_delete_text', 'This record will be permanently deleted.'),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: t('common.confirm_delete_button', 'Yes, delete it!'),
        cancelButtonText: t('common.cancel', 'Cancel'),
    });

    if (!result.isConfirmed) return;

    const form = trigger.closest('form');
    if (form) {
        form.submit();
        return;
    }

    const url = trigger.dataset.url;
    if (!url) return;

    try {
        const res = await window.axios.delete(url);
        if (window.$ && $.fn.dataTable) {
            $.fn.dataTable.tables({ visible: true, api: true }).ajax.reload(null, false);
        }
        if (res?.data?.message) {
            Swal.fire({
                icon: 'success',
                title: res.data.message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2200,
            });
        }
    } catch (err) {
        Swal.fire({
            icon: 'error',
            title: err?.response?.data?.message || t('common.error_generic', 'Something went wrong.'),
        });
    }
}
