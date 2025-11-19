// --- FILE: public/js/cart.js ---

const CART_STORAGE_KEY = 'e_kantin_cart';

/**
 * Helper: Format angka menjadi format Rupiah tanpa desimal.
 */
function formatRupiah(number) {
    if (number === null || number === undefined) return '0';
    // Menggunakan toFixed(0) untuk menghilangkan desimal, lalu menambahkan titik pemisah ribuan
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
        renderCart(); // Memperbarui tampilan keranjang
        updateCartCount(); // Memperbarui badge di header
    } catch (e) {
        console.error("Error saving cart to Local Storage:", e);
    }
}

/**
 * Merender (menampilkan) item keranjang di offcanvas.
 */
function renderCart() {
    const items = getCartItems();
    const container = document.getElementById('cartItemsContainer');
    const countElement = document.querySelector('.cart-count-span');
    const grandTotalElement = document.getElementById('cartGrandTotal');
    const paymentMethodElement = document.querySelector('.payment-method');
    const confirmButton = document.querySelector('.btn-confirm');


    if (!container) return;

    // Reset container sebelum me-render
    container.innerHTML = '';
    let totalItems = 0;
    let grandTotal = 0;

    if (items.length === 0) {
        container.innerHTML = '<p class="text-center text-muted my-5">Keranjang kosong. Yuk, tambahkan menu favoritmu!</p>';
        // Sembunyikan footer dan jadwal jika kosong
        if (paymentMethodElement) paymentMethodElement.style.display = 'none';
        if (confirmButton) confirmButton.disabled = true;

    } else {
        // Tampilkan footer dan jadwal
        if (paymentMethodElement) paymentMethodElement.style.display = 'flex';
        if (confirmButton) confirmButton.disabled = false;

        items.forEach(item => {
            const totalPrice = item.price * item.quantity;
            grandTotal += totalPrice;
            totalItems += item.quantity;

            // Logika penentuan class card (menggunakan jenis_barang)
            let cardClass;
            const jenis = item.jenis_barang ? item.jenis_barang.toLowerCase() : '';
            if (jenis.includes('makanan')) {
                cardClass = 'card1';
            } else if (jenis.includes('minuman')) {
                cardClass = 'card2';
            } else if (jenis.includes('snack')) {
                cardClass = 'card3';
            } else {
                cardClass = 'card-default';
            }

            // LOGIKA IMAGE PATH
            let photoUrl;
            let photostyle;
            const baseUrl = 'http://127.0.0.1:8000/';

            // Cek jika photo_url null atau hanya base URL
            if (!item.photo_url || item.photo_url === baseUrl) {
                photoUrl = `${baseUrl}icon/${item.jenis_barang}.png`;
                photostyle = 'object-fit: contain; background-color: #eee;';
            } else {
                photoUrl = item.photo_url;
                photostyle = 'background-color: #eee; object-fit: cover;';
            }

            const itemElement = document.createElement('div');
            itemElement.className = `cart-item ${cardClass}`;
            itemElement.innerHTML = `
                <img src="${photoUrl}" alt="${item.name}" style="${photostyle}">
                <div class="cart-item-details">
                    <h6>${item.name}</h6>
                    <p class="cart-item-price">Harga : Rp. ${formatRupiah(item.price)}<br>
                    <span class="cart-item-price-total">Total : Rp. ${formatRupiah(totalPrice)}</span></p>
                </div>
                <div class="cart-quantity-selector">
                    <button class="btn btn-light btn-sm btn-minus" data-id="${item.id}">-</button>
                    <span class="fw-bold">${item.quantity}</span>
                    <button class="btn btn-light btn-sm btn-plus" data-id="${item.id}">+</button>
                </div>
            `;
            container.appendChild(itemElement);
        });

        // Menambahkan event listeners untuk tombol quantity (harus setelah elemen dirender)
        addCartEventListeners();
    }

    // Update total di offcanvas header dan footer
    if (countElement) countElement.textContent = totalItems + ' barang';
    if (grandTotalElement) grandTotalElement.textContent = `Rp. ${formatRupiah(grandTotal)}`;
}

/**
 * Menambahkan item baru ke keranjang atau memperbarui kuantitas jika sudah ada.
 */
function addToCart(newItem) {
    const cart = getCartItems();
    // Menggunakan newItem.id (ID + Varian) sebagai kunci unik
    const existingItemIndex = cart.findIndex(item => item.id === newItem.id);

    if (existingItemIndex !== -1) {
        // Item sudah ada, tambahkan kuantitas
        cart[existingItemIndex].quantity += newItem.quantity;
    } else {
        // Item baru
        cart.push(newItem);
    }

    saveCartItems(cart); // Memicu renderCart dan updateCartCount
}

/**
 * Fungsi untuk meningkatkan atau menurunkan kuantitas
 */
function updateQuantity(itemId, amount) {
    const cart = getCartItems();
    const itemIndex = cart.findIndex(item => item.id === itemId);

    if (itemIndex !== -1) {
        cart[itemIndex].quantity += amount;

        if (cart[itemIndex].quantity <= 0) {
            // Hapus item jika kuantitas <= 0
            cart.splice(itemIndex, 1);
        }
    }

    saveCartItems(cart); // Memicu renderCart dan updateCartCount
}

/**
 * Memperbarui badge hitungan keranjang di header.
 */
function updateCartCount() {
    const items = getCartItems();
    const totalItems = items.reduce((sum, item) => sum + item.quantity, 0);
    const countElement = document.querySelector('.cart-count-span');
    const headerCountElement = document.getElementById('headerCartCount'); // Asumsi ada badge di header

    if (countElement) {
        countElement.textContent = totalItems + ' barang';
    }
    // Update badge di header (jika ada)
    if (headerCountElement) {
        headerCountElement.textContent = totalItems > 0 ? totalItems : '';
        headerCountElement.classList.toggle('d-none', totalItems === 0);
    }
}


/**
 * Menambahkan event listeners ke tombol-tombol keranjang (dipanggil setelah render)
 */
function addCartEventListeners() {
    document.querySelectorAll('.btn-plus').forEach(button => {
        // Pastikan kita hanya memasang listener baru, tidak menumpuk
        button.onclick = null;
        button.onclick = () => updateQuantity(button.dataset.id, 1);
    });

    document.querySelectorAll('.btn-minus').forEach(button => {
        button.onclick = null;
        button.onclick = () => updateQuantity(button.dataset.id, -1);
    });
}

/**
 * Handler utama yang dipanggil dari tombol "Tambah Keranjang" di halaman Detail
 */
function handleAddToCart(event) {
    const addToCartBtn = event.currentTarget;

    // Ambil Kuantitas dari input lokal
    const quantity = parseInt(document.getElementById('inputQty').value) || 1;
    const itemPrice = parseFloat(addToCartBtn.dataset.price);

    // Ambil Varian (jika ada)
    const selectedVarian = document.querySelector('input[name="varian"]:checked');
    const varianName = selectedVarian ? document.querySelector(`label[for="${selectedVarian.id}"]`).textContent : '';

    const itemId = addToCartBtn.dataset.id;

    const newItem = {
        // ID unik: ID asli + Varian (untuk membedakan pedas/tidak pedas)
        id: itemId + (varianName ? `-${varianName.replace(/\s/g, '')}` : ''),
        original_id: itemId, // ID Barang yang sesungguhnya di DB
        name: addToCartBtn.dataset.name + (varianName ? ` (${varianName})` : ''),
        price: itemPrice,
        quantity: quantity,
        jenis_barang: addToCartBtn.dataset.jenis,
        photo_url: addToCartBtn.dataset.photo,
        varian: varianName
    };

    addToCart(newItem);

    console.log(`[ITEM ADDED]: ${quantity}x ${newItem.name}`);

    // Buka Offcanvas secara otomatis setelah menambah item
    if (typeof bootstrap !== 'undefined' && document.getElementById('cartOffcanvas')) {
        const cartOffcanvas = new bootstrap.Offcanvas(document.getElementById('cartOffcanvas'));
        cartOffcanvas.show();
    }
}


// --- LOGIKA CHECKOUT ---

async function checkout() {
    const cart = getCartItems();
    const grandTotalElement = document.getElementById('cartGrandTotal');
    const grandTotalText = grandTotalElement ? grandTotalElement.textContent : 'Rp. 0';

    // 1. Validasi Keranjang
    if (cart.length === 0) {
        alert('Keranjang kosong! Tidak bisa melakukan checkout.');
        return;
    }

    // 2. Kumpulkan Data Pengambilan
    const pickUpDate = document.getElementById('tanggalPicker')?.value;
    const pickUpTime = document.querySelector('.schedule-section select')?.value;

    if (!pickUpDate || !pickUpTime) {
        alert('Mohon lengkapi Tanggal dan Waktu Pengambilan.');
        return;
    }

    // 3. Kumpulkan Data Final
    const payload = {
        _token: document.querySelector('meta[name="csrf-token"]')?.content, // Dapatkan CSRF Token
        cart_items: cart.map(item => ({
            id_barang: item.original_id,
            kuantitas: item.quantity,
            harga_satuan: item.price,
            varian: item.varian || null
        })),
        total_harga: parseFloat(grandTotalText.replace(/[^0-9]/g, '')) || 0,
        waktu_pengambilan: pickUpDate,
        detail_pengambilan: pickUpTime,
        // Untuk sementara, abaikan metode pembayaran
        metode_pembayaran: 'TES_BELUM_DITENTUKAN'
    };

    // Tampilkan loading, nonaktifkan tombol
    const confirmButton = document.querySelector('.btn-confirm');
    if (confirmButton) {
        confirmButton.textContent = 'Memproses...';
        confirmButton.disabled = true;
    }

    // 4. Kirim Data ke Laravel Controller
    try {
        const response = await fetch('/checkout/process', { // Ganti dengan nama route POST yang benar
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                // 'X-CSRF-TOKEN': payload._token // Jika tidak pakai _token di body
            },
            body: JSON.stringify(payload)
        });

        const result = await response.json();

        if (response.ok && result.success) {
            alert('Checkout Berhasil! Kode Transaksi Anda: ' + result.kode_transaksi);
            localStorage.removeItem(CART_STORAGE_KEY); // Kosongkan keranjang
            saveCartItems([]); // Reset UI

            // Tutup offcanvas
            if (typeof bootstrap !== 'undefined' && document.getElementById('cartOffcanvas')) {
                 bootstrap.Offcanvas.getInstance(document.getElementById('cartOffcanvas')).hide();
            }

        } else {
            // Error dari validasi Laravel atau logika Controller
            alert('Checkout Gagal: ' + (result.message || 'Terjadi kesalahan pada server.'));
            console.error('Server Error:', result);
        }

    } catch (error) {
        alert('Terjadi kesalahan jaringan atau koneksi.');
        console.error('Network Error:', error);
    } finally {
        // Kembalikan status tombol
        if (confirmButton) {
            confirmButton.textContent = 'Konfirmasi Pembelian';
            confirmButton.disabled = false;
        }
    }
}

// --- INISIALISASI UTAMA ---

document.addEventListener('DOMContentLoaded', () => {
    updateCartCount();

    // 1. Pasang listener untuk tombol "Tambah Keranjang"
    const addToCartButton = document.getElementById('addToCartButton');
    if (addToCartButton) {
        addToCartButton.addEventListener('click', handleAddToCart);
    }

    // 2. Pasang listener untuk offcanvas agar merender keranjang saat dibuka
    const cartOffcanvas = document.getElementById('cartOffcanvas');
    if (cartOffcanvas) {
        cartOffcanvas.addEventListener('show.bs.offcanvas', renderCart);
    }

    // 3. Pasang listener untuk tombol CHECKOUT
    const confirmButton = document.querySelector('.btn-confirm');
    if (confirmButton) {
        confirmButton.addEventListener('click', checkout);
    }
});
