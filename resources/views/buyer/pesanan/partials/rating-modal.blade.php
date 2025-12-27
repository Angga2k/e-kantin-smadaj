<div class="modal fade" id="ratingModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold">Beri Ulasan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <form id="ratingForm">
                    <div class="mb-3 text-start" id="productSelectContainer">
                        <label class="form-label fw-bold small text-muted">Pilih Produk untuk Diulas:</label>
                        <select class="form-select" id="productSelect"></select>
                    </div>

                    <div id="reviewUnavailableMessage" class="alert alert-warning d-none" role="alert">
                        <i class="bi bi-exclamation-circle d-block fs-1 mb-2 text-warning opacity-75"></i>
                        <h6 class="fw-bold mb-1">Ups, belum bisa diulas!</h6>
                        <small>Tidak dapat memberi ulasan pada barang ini karena status barang <strong>belum diambil</strong>.</small>
                    </div>

                    <div id="activeReviewSection">
                        <div class="mb-3">
                            <label class="form-label fw-bold d-block">Rating Anda</label>
                            <div id="starContainer">
                                <i class="bi bi-star star-rating" data-value="1"></i>
                                <i class="bi bi-star star-rating" data-value="2"></i>
                                <i class="bi bi-star star-rating" data-value="3"></i>
                                <i class="bi bi-star star-rating" data-value="4"></i>
                                <i class="bi bi-star star-rating" data-value="5"></i>
                            </div>
                            <input type="hidden" name="rating" id="ratingValue">
                        </div>

                        <div class="mb-3 text-start">
                            <label for="reviewText" class="form-label fw-bold small text-muted">Ulasan (Opsional)</label>
                            <textarea class="form-control" id="reviewText" name="ulasan" rows="3" placeholder="Bagaimana rasa makanannya?"></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success fw-bold py-2" id="btnSubmitRating">
                                <span id="btnText">Kirim Ulasan</span>
                                <span id="btnLoading" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>