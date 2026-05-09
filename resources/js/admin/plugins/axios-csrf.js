/**
 * Wires axios with the per-request CSRF token from the <meta> tag.
 * Also sets a generic global error handler for 419 (token mismatch).
 */
import axios from 'axios';

export function setupAxiosCsrf() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    if (meta) axios.defaults.headers.common['X-CSRF-TOKEN'] = meta.getAttribute('content');

    axios.interceptors.response.use(
        (res) => res,
        (err) => {
            if (err?.response?.status === 419) window.location.reload();
            return Promise.reject(err);
        },
    );
}
