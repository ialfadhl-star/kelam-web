/* KELAM — interaksi front-end (vanilla JS, tanpa build step) */
(function () {
    'use strict';

    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    /* ---------- Toast ---------- */
    function toast(message) {
        let el = document.getElementById('kelam-toast');
        if (!el) {
            el = document.createElement('div');
            el.id = 'kelam-toast';
            el.className = 'toast';
            document.body.appendChild(el);
        }
        el.textContent = message;
        requestAnimationFrame(() => el.classList.add('show'));
        clearTimeout(el._t);
        el._t = setTimeout(() => el.classList.remove('show'), 2600);
    }

    /* ---------- Update badge keranjang di navbar ---------- */
    function updateCartCount(count) {
        document.querySelectorAll('[data-cart-count]').forEach((el) => {
            el.textContent = count;
            el.classList.toggle('hidden', !count || count <= 0);
        });
    }

    /* ---------- Mobile menu ---------- */
    const menu = document.getElementById('mobile-menu');
    document.querySelectorAll('[data-menu-open]').forEach((b) =>
        b.addEventListener('click', () => menu && menu.classList.add('open'))
    );
    document.querySelectorAll('[data-menu-close]').forEach((b) =>
        b.addEventListener('click', () => menu && menu.classList.remove('open'))
    );

    /* ---------- Filter toggle (katalog mobile) ---------- */
    document.querySelectorAll('[data-filter-toggle]').forEach((b) =>
        b.addEventListener('click', () => {
            document.querySelector('.filters')?.classList.toggle('open');
        })
    );

    /* ---------- AJAX: tambah ke keranjang ---------- */
    async function postCart(url, method, payload) {
        const res = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest',
                Accept: 'application/json',
            },
            body: JSON.stringify(payload),
        });
        if (!res.ok) throw new Error('request failed');
        return res.json();
    }

    document.querySelectorAll('[data-add-to-cart]').forEach((form) => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const variantId = form.querySelector('[name="variant_id"]').value;
            const qtyEl = form.querySelector('[name="quantity"]');
            const quantity = qtyEl ? parseInt(qtyEl.value, 10) || 1 : 1;

            if (!variantId) {
                toast('Pilih warna & ukuran dulu');
                return;
            }
            const btn = form.querySelector('[type="submit"]');
            const original = btn ? btn.textContent : '';
            if (btn) { btn.disabled = true; btn.textContent = 'Menambah...'; }

            try {
                const data = await postCart(form.action, 'POST', { variant_id: variantId, quantity });
                updateCartCount(data.count);
                toast(data.message || 'Ditambahkan ke keranjang');
            } catch (err) {
                toast('Gagal menambah. Coba lagi.');
            } finally {
                if (btn) { btn.disabled = false; btn.textContent = original; }
            }
        });
    });

    /* ---------- Product detail: pilih varian ---------- */
    const pdp = document.getElementById('pdp-options');
    if (pdp) {
        const variants = JSON.parse(pdp.dataset.variants || '[]');
        const state = { color: null, size: null };
        const colorBtns = pdp.querySelectorAll('.opt-color');
        const sizeBtns = pdp.querySelectorAll('.opt-size');
        const variantInput = document.getElementById('selected-variant-id');
        const stockLine = document.getElementById('stock-line');
        const addBtn = document.getElementById('add-btn');
        const colorLabel = document.getElementById('selected-color-label');

        function variantFor(color, size) {
            return variants.find((v) => v.color === color && v.size === size);
        }
        function colorHasStock(color) {
            return variants.some((v) => v.color === color && v.stock > 0);
        }
        function sizeAvailableForColor(color, size) {
            const v = variantFor(color, size);
            return v && v.stock > 0;
        }

        function refresh() {
            // Update ketersediaan ukuran berdasarkan warna terpilih
            sizeBtns.forEach((b) => {
                const size = b.dataset.size;
                if (state.color) {
                    const ok = sizeAvailableForColor(state.color, size);
                    b.disabled = !ok;
                    if (!ok && state.size === size) { state.size = null; b.classList.remove('selected'); }
                } else {
                    b.disabled = false;
                }
            });

            if (colorLabel) colorLabel.textContent = state.color || '—';

            const v = state.color && state.size ? variantFor(state.color, state.size) : null;
            if (v && v.stock > 0) {
                variantInput.value = v.id;
                addBtn.disabled = false;
                let cls = 'stock-in', txt = 'Tersedia';
                if (v.stock <= 3) { cls = 'stock-low'; txt = 'Stok tinggal ' + v.stock; }
                stockLine.className = 'stock-line ' + cls;
                stockLine.innerHTML = '<span class="stock-dot"></span>' + txt;
            } else {
                variantInput.value = '';
                addBtn.disabled = true;
                if (state.color && state.size) {
                    stockLine.className = 'stock-line stock-out';
                    stockLine.innerHTML = '<span class="stock-dot"></span>Kombinasi ini habis';
                } else {
                    stockLine.className = 'stock-line stock-out';
                    stockLine.innerHTML = '<span class="stock-dot"></span>Pilih warna & ukuran';
                }
            }
        }

        colorBtns.forEach((b) => {
            const color = b.dataset.color;
            if (!colorHasStock(color)) b.dataset.disabled = '1';
            b.addEventListener('click', () => {
                if (b.dataset.disabled === '1') return;
                colorBtns.forEach((x) => x.classList.remove('selected'));
                b.classList.add('selected');
                state.color = color;
                refresh();
            });
        });
        sizeBtns.forEach((b) => {
            b.addEventListener('click', () => {
                if (b.disabled) return;
                sizeBtns.forEach((x) => x.classList.remove('selected'));
                b.classList.add('selected');
                state.size = b.dataset.size;
                refresh();
            });
        });

        // Qty stepper
        const qtyInput = document.getElementById('qty-input');
        pdp.querySelectorAll('[data-qty]').forEach((b) =>
            b.addEventListener('click', () => {
                let v = parseInt(qtyInput.value, 10) || 1;
                v += b.dataset.qty === 'inc' ? 1 : -1;
                qtyInput.value = Math.max(1, Math.min(99, v));
            })
        );

        refresh();
    }

    /* ---------- Checkout: pilih metode bayar ---------- */
    document.querySelectorAll('.pay-option input[type="radio"]').forEach((r) => {
        r.addEventListener('change', () => {
            document.querySelectorAll('.pay-option').forEach((o) => o.classList.remove('selected'));
            r.closest('.pay-option').classList.add('selected');
        });
        if (r.checked) r.closest('.pay-option').classList.add('selected');
    });

    /* ---------- Admin: variant editor (tambah/hapus baris) ---------- */
    const vEditor = document.getElementById('variant-editor');
    if (vEditor) {
        const body = vEditor.querySelector('#variant-rows');
        let idx = body.querySelectorAll('.variant-row').length;
        function rowHtml(i) {
            return (
                '<div class="variant-row">' +
                '<input name="variants[' + i + '][color]" placeholder="Nama warna (mis. Onyx Black)">' +
                '<input type="color" name="variants[' + i + '][color_hex]" value="#0A0A0A">' +
                '<input name="variants[' + i + '][size]" placeholder="Ukuran (S/M/L/XL/All Size)">' +
                '<input type="number" name="variants[' + i + '][stock]" placeholder="Stok" min="0" value="0">' +
                '<button type="button" class="v-remove" title="Hapus">&times;</button>' +
                '</div>'
            );
        }
        document.getElementById('add-variant')?.addEventListener('click', () => {
            body.insertAdjacentHTML('beforeend', rowHtml(idx++));
        });
        vEditor.addEventListener('click', (e) => {
            if (e.target.classList.contains('v-remove')) {
                e.target.closest('.variant-row').remove();
            }
        });
    }

    /* ---------- Admin: live gradient preview ---------- */
    const gFrom = document.getElementById('gradient_from');
    const gTo = document.getElementById('gradient_to');
    const gPrev = document.getElementById('gradient-preview');
    function paintGradient() {
        if (gPrev && gFrom && gTo) {
            gPrev.style.background = 'linear-gradient(135deg,' + gFrom.value + ',' + gTo.value + ')';
        }
    }
    gFrom?.addEventListener('input', paintGradient);
    gTo?.addEventListener('input', paintGradient);
    paintGradient();
})();
