/**
 * Tom Select — auto-init for any <select data-tomselect> in the document.
 *
 * Supports:
 *   - data-tomselect (presence enables it)
 *   - data-placeholder
 *   - data-tags="true" -> creatable tags
 *   - data-multiple    -> multi-select (uses native multiple too)
 *   - data-remote-url  -> remote search endpoint (JSON {data: [{id, text}, ...]})
 */
import TomSelect from 'tom-select';

const initialised = new WeakSet();

function buildOptions(el) {
    const opts = {
        plugins: ['clear_button'],
        create: el.dataset.tags === 'true',
        allowEmptyOption: true,
        placeholder: el.dataset.placeholder || (window.$t ? window.$t('common.search') : 'Search'),
        valueField: 'id',
        labelField: 'text',
        searchField: ['text'],
    };

    if (el.dataset.remoteUrl) {
        opts.load = (query, callback) => {
            window.axios
                .get(el.dataset.remoteUrl, { params: { q: query } })
                .then((res) => callback(res.data?.data ?? res.data ?? []))
                .catch(() => callback());
        };
        opts.preload = 'focus';
    }

    return opts;
}

export function initTomSelectAll(root = document) {
    root.querySelectorAll('select[data-tomselect]').forEach((el) => {
        if (initialised.has(el)) return;
        // eslint-disable-next-line no-new
        new TomSelect(el, buildOptions(el));
        initialised.add(el);
    });
}
