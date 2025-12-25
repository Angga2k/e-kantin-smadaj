const CART_STORAGE_KEY = 'e_kantin_cart';

/**
 * Helper: Format angka menjadi format Rupiah tanpa desimal.
 */
function formatRupiah(number) {
    if (number === null || number === undefined) return '0';
    return number.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

/**
 * Mengambil data keranjang dari Local Storage.
 */
function getCartItems() {
    try {
        const cartString = localStorage.getItem(CART_STORAGE_KEY);
        return cartString ? JSON.parse(cartString) : [];
    } catch (e) {
        console.error("Error reading cart from Local Storage:", e);
        return [];
    }
}

/**
 * Menyimpan array item ke Local Storage dan memicu update UI.
 */
function saveCartItems(items) {
    try {
        localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(items));
        renderCart();
        updateCartCount();
    } catch (e) {
        console.error("Error saving cart to Local Storage:", e);
    }
}

/**
 * Merender item keranjang di dalam Offcanvas.
 */
function renderCart() {
    const items = getCartItems();
    const container = document.getElementById('cartItemsContainer');
    const totalEl = document.getElementById('cartGrandTotal');
    const btn = document.getElementById('btnKonfirmasi');
    const countSpan = document.querySelector('.cart-count-span');

    // Update Badge Count di Header Offcanvas
    if (countSpan) {
        const totalQty = items.reduce((sum, item) => sum + parseInt(item.quantity), 0);
        countSpan.innerText = totalQty + ' barang';
    }

    if (!container) return;

    // Jika Kosong
    if (items.length === 0) {
        container.innerHTML = `
            <div class="text-center my-5 empty-cart-placeholder">
                <i class="bi bi-basket3 fs-1 text-muted opacity-50"></i>
                <p class="text-muted mt-3">Keranjang kosong. Yuk, jajan dulu!</p>
            </div>
        `;
        if (btn) btn.disabled = true;
        if (totalEl) totalEl.innerText = 'Rp 0';
        return;
    }

    // Jika Ada Isi
    if (btn) btn.disabled = false;
    let total = 0;
    let html = '<ul class="list-group list-group-flush">';

    items.forEach(item => {
        let subtotal = item.price * item.quantity;
        total += subtotal;

        // Cek gambar (fallback jika tidak ada)
        let imageSrc = item.photo_url;
        if (!imageSrc || imageSrc === 'undefined') {
            imageSrc = '/icon/Makanan.png'; // Gambar default
        }

        html += `
            <li class="list-group-item px-0 py-3 border-bottom d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center" style="max-width: 70%;">
                    <img src="${imageSrc}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;" class="me-3 border">
                    <div>
                        <h6 class="mb-1 text-truncate fw-bold" style="font-size: 0.9rem;">${item.name}</h6>
                        <div class="text-muted small">
                            Rp ${formatRupiah(item.price)} x ${item.quantity}
                        </div>
                        ${item.varian ? `<span class="badge bg-light text-dark border">${item.varian}</span>` : ''}
                    </div>
                </div>
                <div class="text-end">
                    <span class="fw-bold text-dark d-block mb-2">Rp ${formatRupiah(subtotal)}</span>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-secondary btn-minus" data-id="${item.id}" style="padding: 0.1rem 0.4rem;">-</button>
                        <button type="button" class="btn btn-outline-secondary btn-plus" data-id="${item.id}" style="padding: 0.1rem 0.4rem;">+</button>
                    </div>
                </div>
            </li>
        `;
    });
    html += '</ul>';

    container.innerHTML = html;
    if (totalEl) totalEl.innerText = 'Rp ' + formatRupiah(total);

    // Re-attach event listeners untuk tombol +/-
    addCartEventListeners();
}

/**
 * Menambahkan listener ke tombol +/- di dalam keranjang
 */
function addCartEventListeners() {
    document.querySelectorAll('.btn-plus').forEach(button => {
        button.onclick = (e) => {
            e.stopPropagation(); // Mencegah event bubbling
            updateQuantity(button.dataset.id, 1);
        };
    });

    document.querySelectorAll('.btn-minus').forEach(button => {
        button.onclick = (e) => {
            e.stopPropagation();
            updateQuantity(button.dataset.id, -1);
        };
    });
}

/**
 * Update Kuantitas Item
 */
function updateQuantity(itemId, amount) {
    const cart = getCartItems();
    const itemIndex = cart.findIndex(item => item.id === itemId);

    if (itemIndex !== -1) {
        cart[itemIndex].quantity += amount;

        if (cart[itemIndex].quantity <= 0) {
            cart.splice(itemIndex, 1); // Hapus jika 0
        }
    }
    saveCartItems(cart);
}

/**
 * Menambah Item Baru (Dipanggil dari tombol 'Tambah Keranjang')
 */
function addToCart(newItem) {
    const cart = getCartItems();
    const existingItemIndex = cart.findIndex(item => item.id === newItem.id);

    if (existingItemIndex !== -1) {
        cart[existingItemIndex].quantity += newItem.quantity;
    } else {
        cart.push(newItem);
    }

    saveCartItems(cart);

    // Tampilkan notifikasi kecil (Opsional)
    if (typeof Swal !== 'undefined') {
        const Toast = Swal.mixin({
            toast: true, position: 'top-end', showConfirmButton: false, timer: 1500, timerProgressBar: true
        });
        Toast.fire({ icon: 'success', title: 'Masuk keranjang!' });
    }
}

/**
 * Handler Tombol "Tambah Keranjang" di Halaman Detail
 */
function handleAddToCart(event) {
    const btn = event.currentTarget;
    const inputQty = document.getElementById('inputQty');

    const quantity = inputQty ? (parseInt(inputQty.value) || 1) : 1;
    const itemPrice = parseFloat(btn.dataset.price);
    const itemId = btn.dataset.id;

    // Cek Varian Radio Button
    const selectedVarian = document.querySelector('input[name="varian"]:checked');
    // Ambil nama varian dari Label yang sesuai dengan ID radio
    let varianName = '';
    if (selectedVarian) {
        const label = document.querySelector(`label[for="${selectedVarian.id}"]`);
        if (label) varianName = label.innerText.trim();
    }

    const newItem = {
        id: itemId + (varianName ? '-' + varianName.replace(/\s+/g, '') : ''), // ID Unik kombinasi
        original_id: itemId,
        name: btn.dataset.name,
        price: itemPrice,
        quantity: quantity,
        jenis_barang: btn.dataset.jenis,
        photo_url: btn.dataset.photo,
        varian: varianName
    };

    addToCart(newItem);

    // Buka Offcanvas Otomatis
    const offcanvasEl = document.getElementById('cartOffcanvas');
    if (offcanvasEl && typeof bootstrap !== 'undefined') {
        const bsOffcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl) || new bootstrap.Offcanvas(offcanvasEl);
        bsOffcanvas.show();
    }
}

/**
 * Update Badge Cart di Navbar Utama
 */
function updateCartCount() {
    const items = getCartItems();
    const totalItems = items.reduce((sum, item) => sum + parseInt(item.quantity), 0);

    // Selector untuk span 'Rp 0' atau badge di header layout
    const headerCountElements = document.querySelectorAll('.header-cart-count'); // Tambahkan class ini di navbar jika perlu

    // Update badge di dalam offcanvas title juga
    const offcanvasBadge = document.querySelector('.cart-count-span');
    if (offcanvasBadge) offcanvasBadge.innerText = totalItems + ' barang';
}

// --- INISIALISASI SAAT DOM READY ---
document.addEventListener('DOMContentLoaded', () => {
    updateCartCount();

    // 1. Render saat offcanvas dibuka
    const cartOffcanvas = document.getElementById('cartOffcanvas');
    if (cartOffcanvas) {
        cartOffcanvas.addEventListener('show.bs.offcanvas', renderCart);
    }

    // 2. Listener Tombol Tambah (Halaman Detail)
    const addToCartButton = document.getElementById('addToCartButton');
    if (addToCartButton) {
        addToCartButton.addEventListener('click', handleAddToCart);
    }

    // 3. Listener FORM CHECKOUT (Pengganti fungsi checkout() lama)
    const checkoutForm = document.getElementById('checkoutForm');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Stop submit standar

            const cart = getCartItems();

            // Validasi Kosong
            if (cart.length === 0) {
                if(typeof Swal !== 'undefined') Swal.fire('Ops!', 'Keranjang kosong.', 'warning');
                else alert('Keranjang kosong!');
                return;
            }

            // Validasi Input Tanggal (Browser 'required' attribute handles empty, but logic check here is safer)
            const dateInput = document.getElementById('tanggalPicker');
            if (dateInput && !dateInput.value) {
                if(typeof Swal !== 'undefined') Swal.fire('Lengkapi Data', 'Mohon pilih tanggal pengambilan.', 'warning');
                else alert('Pilih tanggal pengambilan!');
                return;
            }

            // Populate Input Hidden
            const hiddenContainer = document.getElementById('hiddenItemsContainer');
            const totalInput = document.getElementById('inputTotalBayar');
            hiddenContainer.innerHTML = '';

            let totalHitung = 0;

            cart.forEach((item, index) => {
                // Input: items[0][id_barang]
                const inputId = document.createElement('input');
                inputId.type = 'hidden';
                inputId.name = `items[${index}][id_barang]`;
                inputId.value = item.original_id; // ID asli database
                hiddenContainer.appendChild(inputId);

                // Input: items[0][jumlah]
                const inputQty = document.createElement('input');
                inputQty.type = 'hidden';
                inputQty.name = `items[${index}][jumlah]`;
                inputQty.value = item.quantity;
                hiddenContainer.appendChild(inputQty);

                totalHitung += (item.price * item.quantity);
            });

            totalInput.value = totalHitung;

            // Submit Form ke Laravel (akan redirect ke Xendit)
            // Opsional: Hapus keranjang setelah submit agar pas balik sudah kosong
            localStorage.removeItem(CART_STORAGE_KEY);

            this.submit();
        });
    }
});
