/**
 * Sidebar — open/close on mobile, has-arrow submenu toggling.
 */

export function setupSidebar() {
    const sidebar = document.querySelector('.sidebar-wrapper');
    if (!sidebar) return;

    const toggleButtons = document.querySelectorAll('.mobile-toggle-icon, .toggle-icon, .nav-toggle-icon');
    toggleButtons.forEach((btn) => {
        btn.addEventListener('click', () => sidebar.classList.toggle('show'));
    });

    sidebar.querySelectorAll('a.has-arrow').forEach((trigger) => {
        trigger.addEventListener('click', (e) => {
            e.preventDefault();
            const li = trigger.parentElement;
            li.classList.toggle('mm-active');
            const sub = li.querySelector(':scope > ul');
            if (sub) sub.style.display = li.classList.contains('mm-active') ? 'block' : 'none';
        });
    });
}
