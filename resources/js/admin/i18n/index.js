/**
 * Locale runtime — handles language switching with NO page reload.
 *
 * On switch we:
 *   - update vue-i18n locale (so Vue components reactively update)
 *   - update <html lang>
 *   - persist to localStorage
 *   - POST to /admin/locale so the server-side default for the next request
 *     matches (server response only — no reload)
 *   - re-init Tom Select / flatpickr / SweetAlert / DataTables labels
 *   - dispatch a custom event "pos:locale-changed" so any listener can react
 */

const STORAGE_KEY = 'pos.locale';
const SUPPORTED = ['en', 'km'];

export function getInitialLocale() {
    const fromHtml = document.documentElement.getAttribute('lang');
    const fromStorage = window.localStorage.getItem(STORAGE_KEY);
    const candidate = fromStorage || fromHtml || 'en';
    return SUPPORTED.includes(candidate) ? candidate : 'en';
}

export function setupLocaleSwitcher(i18n) {
    document.addEventListener('click', async (e) => {
        const btn = e.target.closest('[data-set-locale]');
        if (!btn) return;
        e.preventDefault();
        const locale = btn.getAttribute('data-set-locale');
        if (!SUPPORTED.includes(locale)) return;
        await applyLocale(i18n, locale);
    });
}

export async function applyLocale(i18n, locale) {
    i18n.global.locale.value = locale;
    document.documentElement.setAttribute('lang', locale);
    window.localStorage.setItem(STORAGE_KEY, locale);

    // Tell server (so blade trans() on next ajax request honours it).
    try {
        await window.axios.post('/admin/locale', { locale });
    } catch { /* offline / not auth — ignore */ }

    // Refresh DataTables in place.
    if (window.$ && window.$.fn?.dataTable) {
        $.fn.dataTable.tables({ visible: true, api: true }).draw(false);
    }

    // Re-render text inside data-i18n-attrs for non-Vue static markup.
    document.querySelectorAll('[data-i18n]').forEach((el) => {
        const key = el.getAttribute('data-i18n');
        const val = window.$t(key);
        if (val !== key) el.textContent = val;
    });

    document.dispatchEvent(new CustomEvent('pos:locale-changed', { detail: { locale } }));
}
