<template>
    <div class="pos-terminal" :data-currency="currency">
        <div class="pos-grid">
            <!-- ============================================================
                 LEFT PANEL — cart / customer / search / totals
            ============================================================ -->
            <section class="pos-cart card">
                <!-- Toolbar -->
                <div class="card-body pos-toolbar">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <div class="pos-quick-actions d-flex align-items-center gap-1 me-2">
                            <button type="button" class="btn btn-sm btn-light" :title="$t('pos.tooltip_calendar')">
                                <i class="bi bi-calendar3"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-light" :title="$t('pos.tooltip_table')">
                                <i class="bi bi-geo-alt"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-light" :title="$t('pos.tooltip_customer')">
                                <i class="bi bi-person"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-light" :title="$t('pos.tooltip_cash')">
                                <i class="bi bi-currency-dollar"></i>
                            </button>
                        </div>

                        <div class="pos-field flex-grow-1" style="min-width:180px;">
                            <label class="form-label form-label-xs text-muted mb-0">{{ $t('pos.customer') }}</label>
                            <select class="form-select form-select-sm" v-model="customerId">
                                <option value="">{{ $t('pos.walk_in_customer') }}</option>
                                <option v-for="c in customers" :key="c.id" :value="c.id">
                                    {{ c.name }}<span v-if="c.phone"> — {{ c.phone }}</span>
                                </option>
                            </select>
                        </div>

                        <div class="pos-field" style="min-width:140px;">
                            <label class="form-label form-label-xs text-muted mb-0">{{ $t('pos.price_option') }}</label>
                            <select class="form-select form-select-sm" v-model="priceOption">
                                <option v-for="(label, key) in priceOptions" :key="key" :value="key">{{ label }}</option>
                            </select>
                        </div>

                        <button type="button" class="btn btn-sm btn-light" :title="$t('pos.add_customer')">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-light" :title="$t('common.more')">
                            <i class="bi bi-three-dots"></i>
                        </button>
                    </div>
                </div>

                <!-- Search -->
                <div class="card-body pt-0">
                    <div class="input-group input-group-sm pos-search">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text"
                               v-model="search"
                               @keydown.enter.prevent="addByCode"
                               class="form-control"
                               :placeholder="$t('pos.search_placeholder')">
                        <button type="button" class="btn btn-outline-secondary" :title="$t('pos.scan')">
                            <i class="bi bi-upc-scan"></i>
                        </button>
                    </div>
                </div>

                <!-- Cart -->
                <div class="pos-cart-body">
                    <table class="table table-sm pos-cart-table mb-0">
                        <thead>
                            <tr>
                                <th>{{ $t('pos.product') }}</th>
                                <th class="text-end">{{ $t('pos.price') }}</th>
                                <th class="text-center" style="width:130px;">{{ $t('pos.quantity') }}</th>
                                <th class="text-end">{{ $t('pos.subtotal') }}</th>
                                <th style="width:32px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="!cart.length" class="pos-cart-empty">
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="bi bi-cart3 d-block fs-1 mb-2 opacity-25"></i>
                                    {{ $t('pos.cart_empty') }}
                                </td>
                            </tr>
                            <tr v-for="(line, idx) in cart" :key="line.id">
                                <td>
                                    <div class="fw-semibold">{{ line.name }}</div>
                                    <div class="small text-muted">[{{ line.code }}]</div>
                                </td>
                                <td class="text-end">{{ format(line.price) }}</td>
                                <td>
                                    <div class="input-group input-group-sm pos-qty">
                                        <button class="btn btn-outline-secondary" type="button" @click="decQty(idx)">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <input type="number" min="1" v-model.number="line.qty" class="form-control text-center">
                                        <button class="btn btn-outline-secondary" type="button" @click="incQty(idx)">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="text-end fw-semibold">{{ format(line.qty * line.price) }}</td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-link text-danger p-0"
                                            @click="removeLine(idx)" :title="$t('common.remove')">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Totals -->
                <div class="pos-totals">
                    <div class="row g-0 align-items-center">
                        <div class="col-6 col-md-4">
                            <div class="pos-total-cell">
                                <span class="label">{{ $t('pos.discount') }}</span>
                                <i class="bi bi-pencil-square text-info ms-1"></i>
                                <span class="value">{{ format(discountAmount) }}</span>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="pos-total-cell">
                                <span class="label">{{ $t('pos.coupon') }}</span>
                                <i class="bi bi-pencil-square text-info ms-1"></i>
                                <span class="value">{{ format(couponAmount) }}</span>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="pos-total-cell">
                                <span class="label">{{ $t('pos.items') }}</span>
                                <span class="value">{{ totalQty }} ({{ cart.length }})</span>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="pos-total-cell">
                                <span class="label">{{ $t('pos.total') }}</span>
                                <span class="value">{{ format(subtotal) }}</span>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="pos-total-cell">
                                <span class="label">{{ $t('pos.tax') }}</span>
                                <i class="bi bi-pencil-square text-info ms-1"></i>
                                <span class="value">{{ format(taxAmount) }}</span>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="pos-total-cell">
                                <span class="label">{{ $t('pos.shipping') }}</span>
                                <span class="value">{{ format(0) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="pos-grand-total">
                        {{ $t('pos.grand_total') }} <span class="value ms-2">{{ format(grandTotal) }}</span>
                    </div>
                </div>
            </section>

            <!-- ============================================================
                 RIGHT PANEL — product browser
            ============================================================ -->
            <section class="pos-products card">
                <div class="card-body pos-products-header">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-sm btn-primary"
                                    :title="$t('pos.home')"
                                    @click="goHome">
                                <i class="bi bi-house-fill"></i>
                            </button>
                        </div>
                        <div class="pos-toolbar-icons d-flex align-items-center gap-2 text-muted">
                            <i class="bi bi-keyboard" :title="$t('pos.tooltip_keyboard')"></i>
                            <i class="bi bi-calculator" :title="$t('pos.tooltip_calculator')"></i>
                            <i class="bi bi-arrow-counterclockwise" :title="$t('common.undo')"></i>
                            <i class="bi bi-arrows-fullscreen" :title="$t('pos.tooltip_fullscreen')"></i>
                            <i class="bi bi-display" :title="$t('pos.tooltip_customer_screen')"></i>
                            <i class="bi bi-printer" :title="$t('common.print')"></i>
                            <i class="bi bi-grid" :title="$t('pos.tooltip_layout')"></i>
                            <span class="position-relative">
                                <i class="bi bi-bell"></i>
                                <span v-if="openTabs > 0"
                                      class="badge bg-danger position-absolute top-0 start-100 translate-middle"
                                      style="font-size:0.55rem;">{{ openTabs }}</span>
                            </span>
                            <i class="bi bi-person-circle" :title="$t('pos.tooltip_user')"></i>
                        </div>
                    </div>

                    <div class="pos-tabs">
                        <button type="button" class="pos-tab"
                                :class="{active: activeTab==='category'}"
                                @click="activeTab='category'; activeCategoryId=null">
                            {{ $t('pos.tab_category') }}
                        </button>
                        <button type="button" class="pos-tab pos-tab-teal"
                                :class="{active: activeTab==='all'}"
                                @click="activeTab='all'">
                            {{ $t('pos.tab_all') }}
                        </button>
                        <button type="button" class="pos-tab pos-tab-red"
                                :class="{active: activeTab==='featured'}"
                                @click="activeTab='featured'">
                            {{ $t('pos.tab_featured') }}
                        </button>
                    </div>
                </div>

                <div class="pos-products-body">
                    <!-- Category tab — pick a category first -->
                    <div v-if="activeTab==='category' && !activeCategoryId" class="pos-product-grid">
                        <button v-for="cat in categories" :key="cat.id"
                                type="button"
                                class="pos-product-card pos-category-card"
                                @click="activeCategoryId = cat.id">
                            <div class="pos-product-image">
                                <img v-if="cat.image" :src="cat.image" :alt="cat.name">
                                <div v-else class="pos-image-placeholder">
                                    <i class="bi bi-grid-3x3-gap"></i>
                                    <span>{{ $t('pos.no_image') }}</span>
                                </div>
                            </div>
                            <div class="pos-product-name">{{ cat.name }}</div>
                            <div class="pos-product-meta">{{ cat.items_count }} {{ $t('pos.items_label') }}</div>
                        </button>
                    </div>

                    <!-- Items grid for all / featured / category-drill -->
                    <div v-if="filteredItems.length" class="pos-product-grid">
                        <button v-for="item in filteredItems" :key="item.id"
                                type="button"
                                class="pos-product-card"
                                @click="addItem(item)">
                            <div class="pos-product-image">
                                <img v-if="item.image" :src="item.image" :alt="item.name">
                                <div v-else class="pos-image-placeholder">
                                    <i class="bi bi-image"></i>
                                    <span>{{ $t('pos.no_image') }}</span>
                                </div>
                            </div>
                            <div class="pos-product-name">{{ item.name }}</div>
                            <div class="pos-product-meta">[{{ item.code }}]</div>
                            <div class="pos-product-price">{{ format(item.price) }}</div>
                        </button>
                    </div>

                    <div v-if="activeTab !== 'category' && !filteredItems.length"
                         class="text-muted text-center py-5">
                        <i class="bi bi-box-seam d-block fs-1 mb-2 opacity-25"></i>
                        {{ $t('pos.no_items_match') }}
                    </div>

                    <div v-if="activeTab === 'category' && activeCategoryId" class="mt-2">
                        <button type="button" class="btn btn-sm btn-link" @click="activeCategoryId=null">
                            <i class="bi bi-arrow-left"></i> {{ $t('pos.back_to_categories') }}
                        </button>
                    </div>
                </div>
            </section>
        </div>

        <!-- ============================================================
             BOTTOM — payment / draft / cancel / recent action bar
        ============================================================ -->
        <footer class="pos-action-bar">
            <button type="button" class="pos-action-btn pos-action-blue"
                    :disabled="!cart.length" @click="payWith('card')">
                <i class="bi bi-credit-card-fill"></i>{{ $t('pos.pay_card') }}
            </button>
            <button type="button" class="pos-action-btn pos-action-teal"
                    :disabled="!cart.length" @click="payWith('cash')">
                <i class="bi bi-cash-stack"></i>{{ $t('pos.pay_cash') }}
            </button>
            <button type="button" class="pos-action-btn pos-action-pink"
                    :disabled="!cart.length" @click="payWith('credit_sale')">
                <i class="bi bi-journal-text"></i>{{ $t('pos.pay_credit_sale') }}
            </button>
            <button type="button" class="pos-action-btn pos-action-grey"
                    :disabled="!cart.length" @click="payWith('multiple')">
                <i class="bi bi-wallet2"></i>{{ $t('pos.pay_multiple') }}
            </button>
            <button type="button" class="pos-action-btn pos-action-grey"
                    :disabled="!cart.length" @click="payWith('deposit')">
                <i class="bi bi-piggy-bank"></i>{{ $t('pos.pay_deposit') }}
            </button>
            <button type="button" class="pos-action-btn pos-action-grey"
                    :disabled="!cart.length" @click="payWith('points')">
                <i class="bi bi-star-fill"></i>{{ $t('pos.pay_points') }}
            </button>
            <div class="btn-group">
                <button type="button" class="pos-action-btn pos-action-grey dropdown-toggle"
                        data-bs-toggle="dropdown">
                    <i class="bi bi-three-dots"></i>{{ $t('common.more') }}
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" :href="links.register">
                        <i class="bi bi-cash-coin me-2"></i>{{ $t('menu.pos_register') }}
                    </a></li>
                    <li><a class="dropdown-item" :href="links.kitchen">
                        <i class="bi bi-fire me-2"></i>{{ $t('menu.kitchen_tickets') }}
                    </a></li>
                    <li><a class="dropdown-item" :href="links.orders">
                        <i class="bi bi-receipt me-2"></i>{{ $t('menu.orders') }}
                    </a></li>
                </ul>
            </div>
            <button type="button" class="pos-action-btn pos-action-yellow"
                    :disabled="!cart.length" @click="saveDraft">
                <i class="bi bi-bookmark"></i>{{ $t('pos.draft') }}
            </button>
            <button type="button" class="pos-action-btn pos-action-red"
                    :disabled="!cart.length" @click="cancelOrder">
                <i class="bi bi-x-circle"></i>{{ $t('common.cancel') }}
            </button>
            <a :href="links.orders" class="pos-action-btn pos-action-orange ms-auto">
                <i class="bi bi-clock-history"></i>{{ $t('pos.recent_transactions') }}
            </a>
        </footer>
    </div>
</template>

<script>
function readPayload(id) {
    const el = document.getElementById(id);
    if (!el) return {};
    try { return JSON.parse(el.textContent || '{}'); } catch (_e) { return {}; }
}

export default {
    name: 'PosTerminal',
    data() {
        const payload = readPayload('pos-bootstrap-data');
        return {
            currency: payload.currency || '$',
            customers: payload.customers || [],
            categories: payload.categories || [],
            items: payload.items || [],
            featured: payload.featured || [],
            priceOptions: payload.priceOptions || {},
            links: payload.links || {},
            openTabs: payload.openTabs || 0,

            customerId: '',
            priceOption: Object.keys(payload.priceOptions || { retail: 'Retail' })[0] || 'retail',
            search: '',
            activeTab: 'category',
            activeCategoryId: null,
            cart: [],
            discountAmount: 0,
            couponAmount: 0,
            taxAmount: 0,
        };
    },
    computed: {
        filteredItems() {
            let src = [];
            if (this.activeTab === 'category' && this.activeCategoryId) {
                src = this.items.filter(i => i.category_id === this.activeCategoryId);
            } else if (this.activeTab === 'featured') {
                src = this.featured;
            } else if (this.activeTab === 'all') {
                src = this.items;
            }
            const q = (this.search || '').trim().toLowerCase();
            if (!q) return src;
            return src.filter(i =>
                (i.name || '').toLowerCase().includes(q) ||
                (i.code || '').toLowerCase().includes(q)
            );
        },
        totalQty() { return this.cart.reduce((s, l) => s + Number(l.qty || 0), 0); },
        subtotal() { return this.cart.reduce((s, l) => s + Number(l.qty || 0) * Number(l.price || 0), 0); },
        grandTotal() {
            return Math.max(0,
                this.subtotal
                - Number(this.discountAmount || 0)
                - Number(this.couponAmount || 0)
                + Number(this.taxAmount || 0));
        },
    },
    methods: {
        format(n) {
            const v = Number(n || 0);
            return `${this.currency}${v.toFixed(2)}`;
        },
        addItem(item) {
            const existing = this.cart.find(l => l.id === item.id);
            if (existing) { existing.qty += 1; return; }
            this.cart.push({ ...item, qty: 1 });
        },
        addByCode() {
            const q = (this.search || '').trim().toLowerCase();
            if (!q) return;
            const m = this.items.find(i => (i.code || '').toLowerCase() === q);
            if (m) { this.addItem(m); this.search = ''; }
        },
        incQty(idx) { this.cart[idx].qty += 1; },
        decQty(idx) {
            if (this.cart[idx].qty > 1) { this.cart[idx].qty -= 1; }
            else { this.removeLine(idx); }
        },
        removeLine(idx) { this.cart.splice(idx, 1); },
        goHome() { this.activeTab = 'category'; this.activeCategoryId = null; this.search = ''; },
        payWith(method) {
            if (window.Swal) {
                window.Swal.fire({
                    icon: 'info',
                    title: this.$t('pos.checkout_coming_soon'),
                    text: `${method}: ${this.format(this.grandTotal)} (${this.totalQty} ${this.$t('pos.items_label')})`,
                    confirmButtonText: 'OK',
                });
            }
        },
        saveDraft() {
            if (window.flasher && window.flasher.success) {
                window.flasher.success(this.$t('pos.draft'));
            }
        },
        cancelOrder() {
            if (!window.Swal) { this.cart = []; return; }
            window.Swal.fire({
                title: this.$t('pos.cancel_confirm_title'),
                text: this.$t('pos.cancel_confirm_text'),
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: this.$t('common.cancel'),
            }).then(r => { if (r.isConfirmed) { this.cart = []; } });
        },
    },
};
</script>
