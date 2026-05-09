# Test Plan — PR #1: Restaurant POS Phase 1 admin scaffold

PR: https://github.com/lymanata-code/restaurant-pos/pull/1
App URL: http://127.0.0.1:8000/admin/login (already authed via prior browser session if cookies persist; otherwise log in with admin / admin@123)

The PR is an initial scaffold. The user explicitly asked for a video proving:
- Login → dashboard renders
- Khmer/English switching with NO page reload
- A working CRUD module end-to-end (create + list + delete with SweetAlert2 confirm + PHPFlasher toast)
- Server-side DataTables with Bootstrap 5 fixed pagination
- flatpickr & Tom Select used in forms

I'll cover that in **one continuous browser flow**. Each step has concrete pass/fail criteria.

## Code references behind the plan
- Login → dashboard: `app/Http/Controllers/Admin/Auth/LoginController.php` + `app/Http/Controllers/Admin/DashboardController.php`
- Locale switch: `resources/js/admin/i18n/index.js:35-57` (no reload, dispatches `pos:locale-changed`); server endpoint `app/Http/Controllers/Admin/LocaleController.php`
- Header language flag buttons: `resources/views/admin/layouts/admin_partials/header.blade.php:42-61` (`[data-set-locale="en"]` / `[data-set-locale="km"]`)
- DataTable defaults (Bootstrap 5 fixed pagination, live i18n): `resources/js/admin/plugins/datatable-defaults.js`
- SweetAlert2 confirm + DataTable reload after delete: `resources/js/admin/plugins/confirm-delete.js`
- Promotions form (date fields → flatpickr): `app/Http/Controllers/Admin/Promotions/PromotionController.php:88-95`
- Menu-items form (status select → Tom Select): `app/Http/Controllers/Admin/MenuItems/MenuItemController.php:79-89`
- Menu-categories CRUD with low coupling for create/list/delete: `app/Http/Controllers/Admin/MenuCategories/MenuCategoryController.php`

## Primary flow (one recording)

### 1. Login
1. Navigate to `http://127.0.0.1:8000/admin/login`.
2. Fill `login=admin`, `password=admin@123`, click "Sign in".
- **PASS**: URL becomes `/admin/dashboard`, sidebar shows "Dashboard / System / Branches / Menu / …" in English, top-right shows "Super Admin" / username.
- **FAIL**: Stays on login page or 500 error.

### 2. Dashboard renders cleanly
- **PASS**: No JS errors in console; Bootstrap layout (sidebar + topbar + main) intact.
- **FAIL**: Console shows "Uncaught ReferenceError" / 500 banner / blank page.

### 3. Live language switch (no page reload)
1. Open the language dropdown (top-right) and click "ខ្មែរ".
2. Observe sidebar labels switch from English to Khmer (e.g. "Dashboard" → "ផ្ទាំងគ្រប់គ្រង", "Branches" → "សាខា").
3. Verify the URL stays `/admin/dashboard` and the network tab shows ONLY a single `POST /admin/locale` (not a full page reload) — confirmed by the browser tab spinner not refreshing.
4. Switch back to English via the dropdown.
- **PASS**: Sidebar text updates instantly; URL doesn't change; no full-page navigation/spinner; switching back returns to English text.
- **FAIL**: Page reloads (URL flicker, full repaint, or sidebar resets to default-collapsed); text doesn't change; or `POST /admin/locale` returns non-200.

### 4. DataTable server-side pagination (Branches)
1. Navigate to `/admin/branches`.
2. Observe table loads via AJAX (single GET to `/admin/branches?draw=1&...` visible in network tab).
3. Pagination shows "First / Prev / 1 / Next / Last" buttons — that is the `pagingType: 'full_numbers'` Bootstrap-5 fixed pagination.
4. Search for `MAIN`.
- **PASS**: Initial table renders 2 rows (MAIN + TKM). Search filters to 1 row server-side (verifiable via the `recordsFiltered: 1` payload in network response). Bootstrap `.pagination` element has the four boundary controls.
- **FAIL**: Table renders empty / errors out, search doesn't fire a new request, or pagination only shows prev/next without numbered pages.

### 5. Create + Delete (full CRUD) on Menu Categories with SweetAlert2 + PHPFlasher
1. Navigate to `/admin/menu-categories`.
2. Click the "Create" button → form loads at `/admin/menu-categories/create`.
3. Fill `code = TEST-DELETE`, `name = Test Category To Delete`, `sort_order = 99`, leave `is_active` checked.
4. Click "Save".
- **PASS**: Redirects to index `/admin/menu-categories`. The DataTable reloads and the new row "TEST-DELETE / Test Category To Delete" is visible. A green PHPFlasher SweetAlert toast briefly appears (top-right, "Saved successfully.").
- **FAIL**: Stays on form with validation errors, or saves silently with no toast.

5. On the index, click the red trash icon for the new "TEST-DELETE" row.
- **PASS**: A SweetAlert2 modal appears with title "Are you sure?" / red "Yes, delete it!" / grey "Cancel" buttons.
- **FAIL**: Browser native `confirm()` shown instead, or no confirm at all and the row vanishes immediately.

6. Click "Yes, delete it!".
- **PASS**: Modal closes, DataTable refreshes WITHOUT a full page reload, and the "TEST-DELETE" row is gone. (Optional: a green "Deleted successfully." toast.)
- **FAIL**: Row remains after refresh, or full page reload occurs (URL flicker), or 500 error.

### 6. flatpickr + Tom Select on the Promotions form
1. Navigate to `/admin/promotions/create`.
2. Click into the `start_date` field.
- **PASS**: A flatpickr calendar popover opens (the input is read-only-styled with the picker UI); pick today's date → input fills as `YYYY-MM-DD`.
- **FAIL**: Native browser date picker / plain text input only / no popover.
3. Click into the `promotion_type` select.
- **PASS**: Tom Select wrapper renders (rounded pill, search-as-you-type filter inside the option list, instead of a native `<select>` dropdown).
- **FAIL**: Native browser `<select>` dropdown.

This single recording proves: login, layout, locale switch with no reload, server-side DataTable + Bootstrap 5 fixed pagination, create + delete with SweetAlert2 confirm + flasher toast, flatpickr, and Tom Select — i.e. every UX claim in the PR description.

## Out of scope of this run (already verified via curl in setup)
- Migration runs cleanly (`php artisan migrate:fresh --seed` exited 0 in setup).
- All admin routes return 200 (curl-tested: `/admin/branches`, `/admin/menu-items`, `/admin/customers`, `/admin/orders`, `/admin/audit-logs`).
- npm build succeeds (verified, ~8s).
- Multi-branch dropdown switching (will check that the dropdown is present and rendered with Tom Select; full isolation testing across two restaurants is left for a follow-up because seeded TKM branch has no demo records yet).
