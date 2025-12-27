/**
 * KONFIGURASI & VARIABEL GLOBAL
 */
const CART_STORAGE_KEY = "e_kantin_cart";
let isSubmitting = false; // Flag untuk mencegah double-click

/**
 * HELPER FUNCTIONS
 */
function formatRupiah(number) {
    if (number === null || number === undefined) return "0";
    return "Rp " + number.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function getCartItems() {
    try {
        const cartString = localStorage.getItem(CART_STORAGE_KEY);
        return cartString ? JSON.parse(cartString) : [];
    } catch (e) {
        console.error("Error reading cart:", e);
        return [];
    }
}

function saveCartItems(items) {
    try {
        localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(items));
        renderCart();
        updateCartCount();
        calculateFinalTotal(); // Memastikan rincian biaya terupdate saat item berubah
    } catch (e) {
        console.error("Error saving cart:", e);
    }
}

/**
 * FUNGSI KALKULASI GABUNGAN (DARI BLADE)
 */
function calculateFinalTotal() {
    const selectPayment = document.getElementById("selectPaymentMethod");
    const displaySubtotal = document.getElementById("displaySubtotal");
    const displayAdminFee = document.getElementById("displayAdminFee");
    const displayGrandTotal = document.getElementById("displayGrandTotal");

    let cartItems = getCartItems();
    const subtotal = cartItems.reduce(
        (sum, item) => sum + item.price * item.quantity,
        0
    );

    let adminFee = 0;
    if (selectPayment && selectPayment.selectedIndex > 0) {
        const selectedOption =
            selectPayment.options[selectPayment.selectedIndex];
        const feeType = selectedOption.getAttribute("data-fee-type");
        const feeValue =
            parseFloat(selectedOption.getAttribute("data-fee")) || 0;

        if (feeType === "flat") {
            adminFee = feeValue;
        } else if (feeType === "percent") {
            adminFee = subtotal * feeValue;
        }
    }

    const grandTotal = subtotal + adminFee;

    if (displaySubtotal) displaySubtotal.innerText = formatRupiah(subtotal);
    if (displayAdminFee) displayAdminFee.innerText = formatRupiah(adminFee);
    if (displayGrandTotal)
        displayGrandTotal.innerText = formatRupiah(grandTotal);

    // Update input hidden untuk total murni barang
    const totalInput = document.getElementById("inputTotalBayar");
    if (totalInput) totalInput.value = subtotal;
}

/**
 * UI RENDERING (OFFCANVAS)
 */
function renderCart() {
    const items = getCartItems();
    const container = document.getElementById("cartItemsContainer");
    const totalEl = document.getElementById("cartGrandTotal");
    const btn = document.getElementById("btnKonfirmasi");
    const countSpan = document.querySelector(".cart-count-span");

    if (countSpan) {
        const totalQty = items.reduce(
            (sum, item) => sum + parseInt(item.quantity),
            0
        );
        countSpan.innerText = totalQty + " barang";
    }

    if (!container) return;

    if (items.length === 0) {
        container.innerHTML = `
            <div class="text-center my-5 empty-cart-placeholder">
                <i class="bi bi-basket3 fs-1 text-muted opacity-50"></i>
                <p class="text-muted mt-3">Keranjang kosong. Yuk, jajan dulu!</p>
            </div>
        `;
        if (btn) btn.disabled = true;
        if (totalEl) totalEl.innerText = "Rp 0";
        calculateFinalTotal();
        return;
    }

    if (btn) btn.disabled = false;
    let total = 0;
    let html = '<ul class="list-group list-group-flush">';

    items.forEach((item) => {
        let subtotal = item.price * item.quantity;
        total += subtotal;
        let imageSrc =
            item.photo_url && item.photo_url !== "undefined"
                ? item.photo_url
                : "/icon/Makanan.png";

        html += `
            <li class="list-group-item px-0 py-3 border-bottom d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center" style="max-width: 70%;">
                    <img src="${imageSrc}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;" class="me-3 border">
                    <div>
                        <h6 class="mb-1 text-truncate fw-bold" style="font-size: 0.9rem;">${
                            item.name
                        }</h6>
                        <div class="text-muted small">Rp ${formatRupiah(
                            item.price
                        ).replace("Rp ", "")} x ${item.quantity}</div>
                        ${
                            item.varian
                                ? `<span class="badge bg-light text-dark border">${item.varian}</span>`
                                : ""
                        }
                    </div>
                </div>
                <div class="text-end">
                    <span class="fw-bold text-dark d-block mb-2">${formatRupiah(
                        subtotal
                    )}</span>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-secondary btn-minus" data-id="${
                            item.id
                        }">-</button>
                        <button type="button" class="btn btn-outline-secondary btn-plus" data-id="${
                            item.id
                        }">+</button>
                    </div>
                </div>
            </li>`;
    });
    html += "</ul>";
    container.innerHTML = html;
    if (totalEl) totalEl.innerText = formatRupiah(total);
    addCartEventListeners();
    calculateFinalTotal();
}

function addCartEventListeners() {
    document.querySelectorAll(".btn-plus").forEach((btn) => {
        btn.onclick = (e) => {
            e.stopPropagation();
            updateQuantity(btn.dataset.id, 1);
        };
    });
    document.querySelectorAll(".btn-minus").forEach((btn) => {
        btn.onclick = (e) => {
            e.stopPropagation();
            updateQuantity(btn.dataset.id, -1);
        };
    });
}

function updateQuantity(itemId, amount) {
    const cart = getCartItems();
    const idx = cart.findIndex((item) => item.id === itemId);
    if (idx !== -1) {
        cart[idx].quantity += amount;
        if (cart[idx].quantity <= 0) cart.splice(idx, 1);
    }
    saveCartItems(cart);
}

function updateCartCount() {
    const items = getCartItems();
    const total = items.reduce((sum, item) => sum + parseInt(item.quantity), 0);
    const badge = document.querySelector(".cart-count-span");
    if (badge) badge.innerText = total + " barang";
}

/**
 * LOGIKA TAMBAH KE KERANJANG
 */
function addToCart(newItem) {
    const cart = getCartItems();
    const existingIdx = cart.findIndex((item) => item.id === newItem.id);
    if (existingIdx !== -1) {
        cart[existingIdx].quantity += newItem.quantity;
    } else {
        cart.push(newItem);
    }
    saveCartItems(cart);

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

function handleAddToCart(event) {
    const btn = event.currentTarget;
    const inputQty = document.getElementById("inputQty");
    const quantity = inputQty ? parseInt(inputQty.value) || 1 : 1;

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
        id:
            btn.dataset.id +
            (varianName ? "-" + varianName.replace(/\s+/g, "") : ""),
        original_id: btn.dataset.id,
        name: btn.dataset.name,
        price: parseFloat(btn.dataset.price),
        quantity: quantity,
        jenis_barang: btn.dataset.jenis,
        photo_url: btn.dataset.photo,
        varian: varianName,
    };

    addToCart(newItem);

    const offcanvasEl = document.getElementById("cartOffcanvas");
    if (offcanvasEl && typeof bootstrap !== "undefined") {
        const bsOffcanvas =
            bootstrap.Offcanvas.getInstance(offcanvasEl) ||
            new bootstrap.Offcanvas(offcanvasEl);
        bsOffcanvas.show();
    }
}

/**
 * EKSEKUSI PEMBAYARAN
 */
function executeSubmission(cart, btnKonfirmasi, checkoutForm) {
    isSubmitting = true;
    if (btnKonfirmasi) {
        btnKonfirmasi.disabled = true;
        btnKonfirmasi.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
    }

    Swal.fire({
        title: "Memproses Pesanan...",
        html: 'Sedang mengalihkan ke pembayaran.<br><small class="text-muted">Jangan tutup halaman ini.</small>',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => Swal.showLoading(),
    });

    const hiddenContainer = document.getElementById("hiddenItemsContainer");
    const totalInput = document.getElementById("inputTotalBayar");
    hiddenContainer.innerHTML = "";
    let totalHitung = 0;

    cart.forEach((item, index) => {
        const idInp = document.createElement("input");
        idInp.type = "hidden";
        idInp.name = `items[${index}][id_barang]`;
        idInp.value = item.original_id;
        hiddenContainer.appendChild(idInp);

        const qtyInp = document.createElement("input");
        qtyInp.type = "hidden";
        qtyInp.name = `items[${index}][jumlah]`;
        qtyInp.value = item.quantity;
        hiddenContainer.appendChild(qtyInp);

        totalHitung += item.price * item.quantity;
    });

    totalInput.value = totalHitung;
    localStorage.removeItem(CART_STORAGE_KEY);
    checkoutForm.submit();
}

/**
 * INITIALIZATION
 */
document.addEventListener("DOMContentLoaded", () => {
    updateCartCount();

    const addToCartButton = document.getElementById("addToCartButton");
    if (addToCartButton) {
        addToCartButton.addEventListener("click", handleAddToCart);
    }

    const cartOffcanvas = document.getElementById("cartOffcanvas");
    if (cartOffcanvas) {
        cartOffcanvas.addEventListener("show.bs.offcanvas", renderCart);
        // Listener gabungan dari Blade
        cartOffcanvas.addEventListener(
            "shown.bs.offcanvas",
            calculateFinalTotal
        );
    }

    // Listener gabungan dari Blade untuk dropdown pembayaran
    const selectPayment = document.getElementById("selectPaymentMethod");
    if (selectPayment) {
        selectPayment.addEventListener("change", calculateFinalTotal);
    }

    // MutationObserver gabungan dari Blade
    const observerTarget = document.getElementById("cartItemsContainer");
    if (observerTarget) {
        const observer = new MutationObserver(calculateFinalTotal);
        observer.observe(observerTarget, { childList: true, subtree: true });
    }

    const checkoutForm = document.getElementById("checkoutForm");
    if (checkoutForm) {
        checkoutForm.addEventListener("submit", function (e) {
            e.preventDefault();
            if (isSubmitting) return false;

            const cart = getCartItems();
            const dateInp = document.getElementById("tanggalPicker");
            const paymentSelect = document.getElementById(
                "selectPaymentMethod"
            );

            if (cart.length === 0)
                return Swal.fire("Ops!", "Keranjang kosong.", "warning");
            if (!dateInp?.value || !paymentSelect?.value) {
                return Swal.fire(
                    "Lengkapi Data",
                    "Pilih tanggal dan metode bayar.",
                    "warning"
                );
            }

            if (this.dataset.hasPending === "true") {
                return Swal.fire({
                    title: "Transaksi Pending!",
                    icon: "warning",
                    text: "Selesaikan pembayaran sebelumnya terlebih dahulu.",
                    showCancelButton: true,
                    confirmButtonText: "Lihat Pesanan",
                }).then((res) => {
                    if (res.isConfirmed)
                        window.location.href = this.dataset.ordersUrl;
                });
            }

            const paymentName =
                paymentSelect.options[paymentSelect.selectedIndex].text;
            const grandTotalText =
                document.getElementById("displayGrandTotal").innerText;

            let itemsHtml =
                '<div class="text-start border p-2 rounded mb-3" style="font-size: 0.85rem; background:#f8f9fa; max-height:150px; overflow-y:auto;">';
            cart.forEach((item) => {
                itemsHtml += `<div class="d-flex justify-content-between"><span>${
                    item.quantity
                }x ${item.name}</span><b>${formatRupiah(
                    item.price * item.quantity
                )}</b></div>`;
            });
            itemsHtml += "</div>";

            Swal.fire({
                title: "Konfirmasi Pesanan",
                html: `<p class="small text-muted mb-2">Apakah rincian pesanan sudah benar?</p>${itemsHtml}<div class="text-start small"><div class="d-flex justify-content-between"><span>Metode:</span> <b>${paymentName}</b></div><div class="d-flex justify-content-between text-primary fs-5 mt-2"><span>Total:</span> <b>${grandTotalText}</b></div></div>`,
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Ya, Bayar Sekarang",
                cancelButtonText: "Cek Lagi",
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed)
                    executeSubmission(
                        cart,
                        document.getElementById("btnKonfirmasi"),
                        checkoutForm
                    );
            });
        });
    }
});

/**
 * BFCACHE FIX
 */
window.addEventListener("pageshow", function (event) {
    isSubmitting = false;
    if (typeof Swal !== "undefined") Swal.close();
    const btn = document.getElementById("btnKonfirmasi");
    if (btn) {
        btn.disabled = false;
        btn.innerHTML = "Konfirmasi & Bayar";
    }
    renderCart();
    updateCartCount();
});
