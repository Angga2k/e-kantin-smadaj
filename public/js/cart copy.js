// --- FILE: public/js/cart.js ---

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
        // Menggunakan || [] sebagai fallback yang lebih sederhana daripada try-catch
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

    if (!container) return;

    container.innerHTML = '';
    let totalItems = 0;
    let grandTotal = 0;

    if (items.length === 0) {
        container.innerHTML = '<p class="text-center text-muted my-5">Keranjang kosong. Yuk, tambahkan menu favoritmu!</p>';
    } else {
        items.forEach(item => {
            // Menggunakan kunci properti yang konsisten: item.price, item.quantity, item.name
            const totalPrice = item.price * item.quantity;
            totalItems += item.quantity;
            grandTotal += totalPrice;

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

            let photoUrl;
            let photostyle;
            if (item.photo_url === 'http://127.0.0.1:8000/') {
                photoUrl = `http://127.0.0.1:8000/icon/${item.jenis_barang}.png`;
                photostyle = 'object-fit: contain; background-color: #eee;'
            } else {
                photoUrl = item.photo_url || `http://127.0.0.1:8000/icon/${item.jenis_barang}.png`;
                photostyle = 'background-color: #eee;'
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

        // Menambahkan event listeners untuk tombol quantity dan hapus
        addCartEventListeners();
    }

    // Update total di offcanvas header dan footer
    if (countElement) countElement.textContent = totalItems + (totalItems === 1 ? ' barang' : ' barang');
    if (grandTotalElement) grandTotalElement.textContent = `Rp. ${formatRupiah(grandTotal)}`;
}

/**
 * Menambahkan item baru ke keranjang atau memperbarui kuantitas jika sudah ada.
 * @param {Object} newItem - Objek item baru yang akan ditambahkan/diperbarui.
 */
function addToCart(newItem) {
    const cart = getCartItems();
    // Menggunakan newItem.id (yang sudah unik)
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

    if (countElement) {
        countElement.textContent = totalItems + (totalItems === 1 ? ' barang' : ' barang');
    }
}


/**
 * Menambahkan event listeners ke tombol-tombol keranjang (dipanggil setelah render)
 */
function addCartEventListeners() {
    document.querySelectorAll('.btn-plus').forEach(button => {
        button.onclick = () => updateQuantity(button.dataset.id, 1);
    });

    document.querySelectorAll('.btn-minus').forEach(button => {
        button.onclick = () => updateQuantity(button.dataset.id, -1);
    });
}

// Handler utama yang dipanggil dari tombol di Blade
function handleAddToCart(event) {
    const addToCartBtn = event.currentTarget; // Tombol yang diklik

    const quantity = parseInt(document.getElementById('inputQty').value) || 1;
    const itemPrice = parseFloat(addToCartBtn.dataset.price);

    // Dapatkan Varian
    const selectedVarian = document.querySelector('input[name="varian"]:checked');
    const varianName = selectedVarian ? document.querySelector(`label[for="${selectedVarian.id}"]`).textContent : '';

    const itemId = addToCartBtn.dataset.id;

    const newItem = {
        // ID unik + varian
        id: itemId + (varianName ? `-${varianName.replace(/\s/g, '')}` : ''),
        original_id: itemId,
        name: addToCartBtn.dataset.name + (varianName ? ` (${varianName})` : ''),
        price: itemPrice,
        quantity: quantity,
        jenis_barang: addToCartBtn.dataset.jenis,
        photo_url: addToCartBtn.dataset.photo,
        varian: varianName
    };

    addToCart(newItem);

    // Tampilkan notifikasi yang lebih baik (menggunakan Bootstrap Toast jika ada, tapi console log saja)
    console.log(`[ITEM ADDED]: ${quantity}x ${newItem.name}`);

    // Opsional: Buka Offcanvas secara otomatis setelah menambah item
    const cartOffcanvas = new bootstrap.Offcanvas(document.getElementById('cartOffcanvas'));
    cartOffcanvas.show();
}

// Inisialisasi: Render keranjang dan update hitungan saat DOM siap
document.addEventListener('DOMContentLoaded', () => {
    updateCartCount();

    // 1. Pasang listener untuk tombol "Tambah Keranjang" di halaman detail
    const addToCartButton = document.getElementById('addToCartButton');
    if (addToCartButton) {
        addToCartButton.addEventListener('click', handleAddToCart);
    }

    // 2. Pasang listener untuk offcanvas agar merender keranjang saat dibuka
    const cartOffcanvas = document.getElementById('cartOffcanvas');
    if (cartOffcanvas) {
        cartOffcanvas.addEventListener('show.bs.offcanvas', renderCart);
    }
});

// Pastikan fungsi addToCart tersedia secara global di window
window.addToCart = addToCart;
