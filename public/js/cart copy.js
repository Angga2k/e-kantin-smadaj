const CART_STORAGE_KEY = "e_kantin_cart";
// Variabel Flag Global: Ditaruh di luar agar bisa diakses oleh 'submit' dan 'pageshow'
let isSubmitting = false;

/**
 * Helper: Format angka menjadi format Rupiah tanpa desimal.
 */
function formatRupiah(number) {
    if (number === null || number === undefined) return "0";
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
    const container = document.getElementById("cartItemsContainer");
    const totalEl = document.getElementById("cartGrandTotal");
    const btn = document.getElementById("btnKonfirmasi");
    const countSpan = document.querySelector(".cart-count-span");

    // Update Badge Count di Header Offcanvas
    if (countSpan) {
        const totalQty = items.reduce(
            (sum, item) => sum + parseInt(item.quantity),
            0
        );
        countSpan.innerText = totalQty + " barang";
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
        if (totalEl) totalEl.innerText = "Rp 0";
        return;
    }

    // Jika Ada Isi
    if (btn) btn.disabled = false;
    let total = 0;
    let html = '<ul class="list-group list-group-flush">';

    items.forEach((item) => {
        let subtotal = item.price * item.quantity;
        total += subtotal;

        // Cek gambar (fallback jika tidak ada)
        let imageSrc = item.photo_url;
        if (!imageSrc || imageSrc === "undefined") {
            imageSrc = "/icon/Makanan.png"; // Gambar default
        }

        html += `
            <li class="list-group-item px-0 py-3 border-bottom d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center" style="max-width: 70%;">
                    <img src="${imageSrc}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;" class="me-3 border">
                    <div>
                        <h6 class="mb-1 text-truncate fw-bold" style="font-size: 0.9rem;">${
                            item.name
                        }</h6>
                        <div class="text-muted small">
                            Rp ${formatRupiah(item.price)} x ${item.quantity}
                        </div>
                        ${
                            item.varian
                                ? `<span class="badge bg-light text-dark border">${item.varian}</span>`
                                : ""
                        }
                    </div>
                </div>
                <div class="text-end">
                    <span class="fw-bold text-dark d-block mb-2">Rp ${formatRupiah(
                        subtotal
                    )}</span>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-secondary btn-minus" data-id="${
                            item.id
                        }" style="padding: 0.1rem 0.4rem;">-</button>
                        <button type="button" class="btn btn-outline-secondary btn-plus" data-id="${
                            item.id
                        }" style="padding: 0.1rem 0.4rem;">+</button>
                    </div>
                </div>
            </li>
        `;
    });
    html += "</ul>";

    container.innerHTML = html;
    if (totalEl) totalEl.innerText = "Rp " + formatRupiah(total);

    // Re-attach event listeners untuk tombol +/-
    addCartEventListeners();
}

/**
 * Menambahkan listener ke tombol +/- di dalam keranjang
 */
function addCartEventListeners() {
    document.querySelectorAll(".btn-plus").forEach((button) => {
        button.onclick = (e) => {
            e.stopPropagation(); // Mencegah event bubbling
            updateQuantity(button.dataset.id, 1);
        };
    });

    document.querySelectorAll(".btn-minus").forEach((button) => {
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
    const itemIndex = cart.findIndex((item) => item.id === itemId);

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
    const existingItemIndex = cart.findIndex((item) => item.id === newItem.id);

    if (existingItemIndex !== -1) {
        cart[existingItemIndex].quantity += newItem.quantity;
    } else {
        cart.push(newItem);
    }

    saveCartItems(cart);

    // Tampilkan notifikasi kecil
    if (typeof Swal !== "undefined") {
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true,
        });
        Toast.fire({ icon: "success", title: "Masuk keranjang!" });
    }
}

/**
 * Handler Tombol "Tambah Keranjang" di Halaman Detail
 */
function handleAddToCart(event) {
    const btn = event.currentTarget;
    const inputQty = document.getElementById("inputQty");

    const quantity = inputQty ? parseInt(inputQty.value) || 1 : 1;
    const itemPrice = parseFloat(btn.dataset.price);
    const itemId = btn.dataset.id;

    // Cek Varian Radio Button
    const selectedVarian = document.querySelector(
        'input[name="varian"]:checked'
    );
    let varianName = "";
    if (selectedVarian) {
        const label = document.querySelector(
            `label[for="${selectedVarian.id}"]`
        );
        if (label) varianName = label.innerText.trim();
    }

    const newItem = {
        id: itemId + (varianName ? "-" + varianName.replace(/\s+/g, "") : ""),
        original_id: itemId,
        name: btn.dataset.name,
        price: itemPrice,
        quantity: quantity,
        jenis_barang: btn.dataset.jenis,
        photo_url: btn.dataset.photo,
        varian: varianName,
    };

    addToCart(newItem);

    // Buka Offcanvas Otomatis
    const offcanvasEl = document.getElementById("cartOffcanvas");
    if (offcanvasEl && typeof bootstrap !== "undefined") {
        const bsOffcanvas =
            bootstrap.Offcanvas.getInstance(offcanvasEl) ||
            new bootstrap.Offcanvas(offcanvasEl);
        bsOffcanvas.show();
    }
}

/**
 * Update Badge Cart di Navbar Utama
 */
function updateCartCount() {
    const items = getCartItems();
    const totalItems = items.reduce(
        (sum, item) => sum + parseInt(item.quantity),
        0
    );

    const offcanvasBadge = document.querySelector(".cart-count-span");
    if (offcanvasBadge) offcanvasBadge.innerText = totalItems + " barang";
}

// --- INISIALISASI SAAT DOM READY ---
document.addEventListener("DOMContentLoaded", () => {
    updateCartCount();

    // 1. Render saat offcanvas dibuka
    const cartOffcanvas = document.getElementById("cartOffcanvas");
    if (cartOffcanvas) {
        cartOffcanvas.addEventListener("show.bs.offcanvas", renderCart);
    }

    // 2. Listener Tombol Tambah (Halaman Detail)
    const addToCartButton = document.getElementById("addToCartButton");
    if (addToCartButton) {
        addToCartButton.addEventListener("click", handleAddToCart);
    }

    // 3. Listener FORM CHECKOUT
    const checkoutForm = document.getElementById("checkoutForm");

    if (checkoutForm) {
        checkoutForm.addEventListener("submit", function (e) {
            e.preventDefault(); // Stop default submit

            // --- FIX 1: Cek Status Gembok ---
            if (isSubmitting) {
                return false;
            }

            const btnKonfirmasi = document.getElementById("btnKonfirmasi");

            // --- A. CEK TRANSAKSI PENDING ---
            const hasPending = this.dataset.hasPending === "true";
            const pendingDeadline = parseInt(this.dataset.pendingDeadline || 0);
            const ordersUrl = this.dataset.ordersUrl;

            if (hasPending) {
                let timerInterval;
                Swal.fire({
                    title: "Transaksi Pending!",
                    icon: "warning",
                    html: `
                        <div class="text-start fs-6">
                            <p class="mb-2">Anda tidak dapat melakukan transaksi baru karena masih ada pesanan dengan status <strong>Pending</strong>.</p>
                            <p class="small text-muted mb-3">Silakan selesaikan pembayaran atau batalkan pesanan tersebut di halaman Pesanan.</p>
                            
                            <div class="alert alert-danger d-flex flex-column align-items-center justify-content-center p-2 mb-0">
                                <span class="small fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">Sisa Waktu Pembayaran</span>
                                <div class="d-flex align-items-center mt-1">
                                    <i class="bi bi-stopwatch-fill me-2 fs-4"></i>
                                    <div id="swal-timer" class="fw-bold fs-3 lh-1" style="font-variant-numeric: tabular-nums;">--:--</div>
                                </div>
                            </div>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText:
                        '<i class="bi bi-receipt me-1"></i> Lihat Pesanan',
                    cancelButtonText: "OK",
                    confirmButtonColor: "#0d6efd",
                    cancelButtonColor: "#6c757d",
                    reverseButtons: true,
                    didOpen: () => {
                        const timerEl =
                            Swal.getHtmlContainer().querySelector(
                                "#swal-timer"
                            );
                        const updateTimer = () => {
                            const now = new Date().getTime();
                            const distance = pendingDeadline - now;
                            if (distance < 0) {
                                clearInterval(timerInterval);
                                timerEl.innerHTML = "00:00";
                                return;
                            }
                            const minutes = Math.floor(
                                (distance % (1000 * 60 * 60)) / (1000 * 60)
                            );
                            const seconds = Math.floor(
                                (distance % (1000 * 60)) / 1000
                            );
                            const m = minutes < 10 ? "0" + minutes : minutes;
                            const s = seconds < 10 ? "0" + seconds : seconds;
                            timerEl.innerHTML = `${m}:${s}`;
                        };
                        updateTimer();
                        timerInterval = setInterval(updateTimer, 1000);
                    },
                    willClose: () => {
                        clearInterval(timerInterval);
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = ordersUrl;
                    }
                });

                return; // STOP EXECUTION HERE
            }

            // --- B. CEK DATA KERANJANG ---
            const cart = getCartItems();
            if (cart.length === 0) {
                if (typeof Swal !== "undefined")
                    Swal.fire("Ops!", "Keranjang kosong.", "warning");
                else alert("Keranjang kosong!");
                return;
            }

            // --- C. CEK TANGGAL ---
            const dateInput = document.getElementById("tanggalPicker");
            if (dateInput && !dateInput.value) {
                if (typeof Swal !== "undefined")
                    Swal.fire(
                        "Lengkapi Data",
                        "Mohon pilih tanggal pengambilan.",
                        "warning"
                    );
                else alert("Pilih tanggal pengambilan!");
                return;
            }

            // --- D. PROSES DATA & SUBMIT ---

            // --- FIX 2: Kunci Proses ---
            isSubmitting = true;

            if (btnKonfirmasi) {
                btnKonfirmasi.disabled = true;
                btnKonfirmasi.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
            }

            // 1. Tampilkan Loading State
            if (typeof Swal !== "undefined") {
                Swal.fire({
                    title: "Memproses Pesanan...",
                    html: 'Mohon tunggu, sedang mengalihkan ke pembayaran.<br><small class="text-muted">Jangan tutup halaman ini.</small>',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => Swal.showLoading(),
                });
            }

            // 2. Populate Input Hidden
            const hiddenContainer = document.getElementById(
                "hiddenItemsContainer"
            );
            const totalInput = document.getElementById("inputTotalBayar");
            hiddenContainer.innerHTML = "";

            let totalHitung = 0;

            cart.forEach((item, index) => {
                // Input ID Barang
                const inputId = document.createElement("input");
                inputId.type = "hidden";
                inputId.name = `items[${index}][id_barang]`;
                inputId.value = item.original_id;
                hiddenContainer.appendChild(inputId);

                // Input Jumlah
                const inputQty = document.createElement("input");
                inputQty.type = "hidden";
                inputQty.name = `items[${index}][jumlah]`;
                inputQty.value = item.quantity;
                hiddenContainer.appendChild(inputQty);

                totalHitung += item.price * item.quantity;
            });

            totalInput.value = totalHitung;

            // 3. Bersihkan Cart & Submit Manual
            localStorage.removeItem(CART_STORAGE_KEY);

            // Gunakan variabel form langsung, bukan 'this' untuk keamanan
            checkoutForm.submit();
        });
    }
});

// --- FIX 3: RESET HALAMAN SAAT TOMBOL BACK DITEKAN (BFCACHE) ---
// Listener ini berjalan di window, terpisah dari DOMContentLoaded
window.addEventListener("pageshow", function (event) {
    // 1. Reset Status Submit agar tombol bisa diklik lagi jika user kembali
    isSubmitting = false;

    // 2. Tutup Loading SweetAlert (Jika ada)
    if (typeof Swal !== "undefined") {
        Swal.close();
    }

    // 3. Reset Tombol Konfirmasi ke Semula
    const btnKonfirmasi = document.getElementById("btnKonfirmasi");
    if (btnKonfirmasi) {
        btnKonfirmasi.disabled = false;
        btnKonfirmasi.innerHTML = "Konfirmasi & Bayar";
    }

    // 4. Update Tampilan Keranjang
    // Karena localStorage sudah dihapus saat submit sebelumnya,
    // fungsi renderCart() akan otomatis menampilkan "Keranjang Kosong"
    // Ini sesuai keinginan Anda agar keranjang terlihat kosong saat user back.
    if (typeof renderCart === "function") {
        renderCart();
        updateCartCount(); // Update juga badge angka di navbar
    }
});
