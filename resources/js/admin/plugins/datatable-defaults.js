/**
 * DataTables — Bootstrap 5 with FIXED pagination (numbers + first/prev/next/last)
 * and live i18n labels (Khmer / English) that update without reload.
 */

export function applyDataTableDefaults() {
    if (!window.$ || !$.fn?.dataTable) return;

    const t = (k, fb) => (window.$t ? window.$t(k, fb) : fb || k);

    // Default options merged into every DataTable.
    $.extend(true, $.fn.dataTable.defaults, {
        responsive: true,
        processing: true,
        serverSide: true,
        pagingType: 'full_numbers', // <-- fixed Bootstrap 5 pagination
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        pageLength: 10,
        dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 d-flex justify-content-md-end'p>>",
        language: buildLanguage(t),
    });

    document.addEventListener('pos:locale-changed', () => {
        const lang = buildLanguage((k, fb) => (window.$t ? window.$t(k, fb) : fb || k));
        // Update each live DataTable's language and redraw.
        $.fn.dataTable.tables({ visible: true, api: true }).every(function () {
            const settings = this.settings()[0];
            settings.oLanguage = $.extend(true, {}, settings.oLanguage, lang);
            this.draw(false);
        });
    });
}

function buildLanguage(t) {
    return {
        search: t('datatable.search', 'Search:'),
        searchPlaceholder: t('common.search', 'Search'),
        lengthMenu: t('datatable.lengthMenu', 'Show _MENU_'),
        info: t('datatable.info', 'Showing _START_ to _END_ of _TOTAL_ entries'),
        infoEmpty: t('datatable.infoEmpty', 'Showing 0 to 0 of 0 entries'),
        infoFiltered: t('datatable.infoFiltered', '(filtered from _MAX_ total entries)'),
        zeroRecords: t('datatable.zeroRecords', 'No matching records found'),
        emptyTable: t('datatable.emptyTable', 'No data available'),
        processing: t('datatable.processing', 'Loading…'),
        paginate: {
            first: t('datatable.first', 'First'),
            last: t('datatable.last', 'Last'),
            previous: t('datatable.previous', 'Previous'),
            next: t('datatable.next', 'Next'),
        },
    };
}
