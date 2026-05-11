/**
 * Locale runtime — handles language switching with NO browser refresh.
 *
 * On switch we:
 *   - POST /admin/locale (so the server session reflects the new locale).
 *   - Fetch the current URL again via XHR; the server returns the page
 *     fully rendered in the new locale.
 *   - Swap the page in place (replace <main>'s contents, sidebar, header)
 *     so all Blade `__('…')` strings update without a window reload.
 *   - Update <html lang>, vue-i18n locale and localStorage.
 *   - Re-init plugins (Tom Select, flatpickr, SweetAlert confirm, DataTables).
 *   - Dispatch a `pos:locale-changed` custom event for any listener.
 */
import { initFlatpickrAll } from '../plugins/flatpickr-defaults';
import { initTomSelectAll } from '../plugins/tom-select-defaults';
import { initConfirmDelete } from '../plugins/confirm-delete';
import { applyDataTableDefaults } from '../plugins/datatable-defaults';

const STORAGE_KEY = 'pos.locale';
const SUPPORTED = ['en', 'km'];

// Page regions whose innerHTML we swap on locale change. They cover the
// Blade-rendered admin shell from `admin_layout.blade.php`.
const SWAPPABLE_SELECTORS = [
    'aside.sidebar-wrapper',
    'header.top-header',
    'main.page-content',
];

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
        if (document.documentElement.getAttribute('lang') === locale) return;
        await applyLocale(i18n, locale);
    });
}

export async function applyLocale(i18n, locale) {
    document.documentElement.setAttribute('lang', locale);
    window.localStorage.setItem(STORAGE_KEY, locale);
    if (i18n) i18n.global.locale.value = locale;

    // Pages outside the admin shell (for example the login screen) do not
    // expose swappable regions, so use a query-param reload that SetLocale
    // will persist into the session.
    if (!hasSwappableShell()) {
        reloadWithLocale(locale);
        return;
    }

    // Tell server first so the next render uses the new locale.
    try {
        await window.axios.post('/admin/locale', { locale });
    } catch {
        reloadWithLocale(locale);
        return;
    }

    // Soft-swap the rendered Blade fragments using the same URL.
    try {
        await softReloadShell(locale);
    } catch (err) {
        console.error('[i18n] soft reload failed', err);
        reloadWithLocale(locale);
        return;
    }

    // Update [data-i18n] for any client-only static markup.
    document.querySelectorAll('[data-i18n]').forEach((el) => {
        const key = el.getAttribute('data-i18n');
        const val = window.$t ? window.$t(key) : key;
        if (val !== key) el.textContent = val;
    });

    // Re-init plugins on whatever was just swapped in.
    initFlatpickrAll(document);
    initTomSelectAll(document);
    initConfirmDelete();

    // Refresh DataTables labels + redraw rows from the new server locale.
    applyDataTableDefaults();
    if (window.$ && window.$.fn?.dataTable) {
        $.fn.dataTable.tables({ visible: true, api: true }).draw(false);
    }

    document.dispatchEvent(new CustomEvent('pos:locale-changed', { detail: { locale } }));
}

function hasSwappableShell() {
    return SWAPPABLE_SELECTORS.some((sel) => document.querySelector(sel));
}

function reloadWithLocale(locale) {
    const url = new URL(window.location.href);
    url.searchParams.set('lang', locale);
    window.location.assign(url.toString());
}

async function softReloadShell(locale) {
    const url = window.location.href;
    const res = await fetch(url, {
        credentials: 'same-origin',
        headers: {
            'Accept': 'text/html',
            'X-POS-Locale': locale,
        },
    });
    if (!res.ok) throw new Error('locale fetch failed: ' + res.status);

    const html = await res.text();
    const parser = new DOMParser();
    const next = parser.parseFromString(html, 'text/html');

    SWAPPABLE_SELECTORS.forEach((sel) => {
        const incoming = next.querySelector(sel);
        const current = document.querySelector(sel);
        if (incoming && current) {
            current.innerHTML = incoming.innerHTML;
        }
    });

    // <title>
    const incomingTitle = next.querySelector('title');
    if (incomingTitle) document.title = incomingTitle.textContent || document.title;
}
