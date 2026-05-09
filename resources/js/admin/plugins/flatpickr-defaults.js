/**
 * flatpickr — auto-init for any input with [data-flatpickr] / [data-datetime] / [data-time].
 */
import flatpickr from 'flatpickr';

const initialised = new WeakSet();

function buildOptions(el) {
    const variant = (el.dataset.flatpickr || '').toLowerCase();
    const opts = {
        allowInput: true,
        altInput: false,
        dateFormat: el.dataset.dateFormat || 'Y-m-d',
    };

    if (variant === 'datetime' || el.dataset.datetime !== undefined) {
        opts.enableTime = true;
        opts.dateFormat = el.dataset.dateFormat || 'Y-m-d H:i';
        opts.time_24hr = true;
    } else if (variant === 'time' || el.dataset.time !== undefined) {
        opts.noCalendar = true;
        opts.enableTime = true;
        opts.dateFormat = el.dataset.dateFormat || 'H:i';
        opts.time_24hr = true;
    }

    if (el.dataset.minDate) opts.minDate = el.dataset.minDate;
    if (el.dataset.maxDate) opts.maxDate = el.dataset.maxDate;

    return opts;
}

export function initFlatpickrAll(root = document) {
    const selector = '[data-flatpickr], [data-datetime], [data-time]';
    root.querySelectorAll(selector).forEach((el) => {
        if (initialised.has(el)) return;
        flatpickr(el, buildOptions(el));
        initialised.add(el);
    });
}
