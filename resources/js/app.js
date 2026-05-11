/**
 * Restaurant POS — admin entry.
 *
 * - Boots Bootstrap 5 JS, jQuery + DataTables (Bootstrap 5 with fixed
 *   pagination), SweetAlert2, flatpickr, Tom Select.
 * - Provides a Vue 3 + vue-i18n instance reusable across "page islands".
 * - Wires Khmer/English live language switching with no page reload.
 */

import 'bootstrap';

import jQuery from 'jquery';
window.$ = window.jQuery = jQuery;

import axios from 'axios';
window.axios = axios;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
const csrfMeta = document.querySelector('meta[name="csrf-token"]');
if (csrfMeta) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfMeta.getAttribute('content');
}

import 'datatables.net-bs5';
import 'datatables.net-buttons-bs5';
import 'datatables.net-responsive-bs5';

import Swal from 'sweetalert2';
window.Swal = Swal;

import flatpickr from 'flatpickr';
window.flatpickr = flatpickr;

import TomSelect from 'tom-select';
window.TomSelect = TomSelect;

import SimpleBar from 'simplebar';
window.SimpleBar = SimpleBar;

// ----------------------------------------------------------------------
// Plugin bootstrap
// ----------------------------------------------------------------------

import { initFlatpickrAll } from './admin/plugins/flatpickr-defaults';
import { initTomSelectAll } from './admin/plugins/tom-select-defaults';
import { initConfirmDelete } from './admin/plugins/confirm-delete';
import { applyDataTableDefaults } from './admin/plugins/datatable-defaults';
import { setupSidebar } from './admin/plugins/sidebar';
import { setupAxiosCsrf } from './admin/plugins/axios-csrf';

// ----------------------------------------------------------------------
// Vue + i18n
// ----------------------------------------------------------------------

import { createApp } from 'vue';
import { createI18n } from 'vue-i18n';
import { setupLocaleSwitcher, getInitialLocale } from './admin/i18n';

import en from './admin/i18n/en.json';
import km from './admin/i18n/km.json';

const i18n = createI18n({
    legacy: false,
    locale: getInitialLocale(),
    fallbackLocale: 'en',
    messages: { en, km },
    missingWarn: false,
    fallbackWarn: false,
});

// jQuery-side i18n shim — used by Tom Select / DataTables / SweetAlert /
// flatpickr / vanilla JS code that lives outside Vue components.
window.$t = (key, fallback = key) => {
    const m = i18n.global.messages.value?.[i18n.global.locale.value] ?? {};
    const parts = key.split('.');
    let v = m;
    for (const p of parts) { v = v?.[p]; if (v == null) return fallback; }
    return typeof v === 'string' ? v : fallback;
};

// ----------------------------------------------------------------------
// Page-level Vue islands
// Each page can declare a Vue page component via:
//
//     <div id="app" data-page="branches.index"></div>
//
// and we lazy-load `./admin/pages/<page>.vue`.
// ----------------------------------------------------------------------

const pageLoaders = import.meta.glob('./admin/pages/**/*.vue');

let currentVueApp = null;

async function mountVueIsland() {
    if (currentVueApp) {
        try { currentVueApp.unmount(); } catch (_e) { /* ignore */ }
        currentVueApp = null;
    }
    const root = document.getElementById('app');
    if (!root) return null;
    const pageKey = root.dataset.page;
    if (!pageKey) return null;
    const path = `./admin/pages/${pageKey.replaceAll('.', '/')}.vue`;
    const loader = pageLoaders[path];
    if (!loader) {
        console.warn(`[POS] No Vue page found at ${path}`);
        return null;
    }
    const mod = await loader();
    const app = createApp(mod.default);
    app.use(i18n);
    app.config.globalProperties.$http = axios;
    app.mount(root);
    currentVueApp = app;
    return app;
}

// ----------------------------------------------------------------------
// Boot
// ----------------------------------------------------------------------

document.addEventListener('DOMContentLoaded', () => {
    setupAxiosCsrf();
    setupSidebar();
    applyDataTableDefaults();
    setupLocaleSwitcher(i18n);
    initConfirmDelete();
    initFlatpickrAll();
    initTomSelectAll();
    mountVueIsland();

    // Re-init plugins for new dynamically-injected DOM
    document.addEventListener('pos:dom-updated', () => {
        initFlatpickrAll();
        initTomSelectAll();
        initConfirmDelete();
    });

    // Re-mount Vue islands after a Blade-aware locale swap (the old #app
    // node is destroyed when <main> innerHTML is replaced).
    document.addEventListener('pos:locale-changed', () => {
        mountVueIsland();
    });
});

window.posI18n = i18n;
