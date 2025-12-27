<style>
    body { font-family: 'Poppins', sans-serif; background-color: #f4f7f6; }

    /* Container Riwayat */
    .container { padding-bottom: 30px; }

    /* Card Utama - Lebih Elegan */
    .card-history {
        border: none !important;
        border-radius: 16px !important;
        margin-bottom: 1.2rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    /* Akses Garis Warna di Samping (Left Border Accent) */
    .card-soft-success { background-color: #ffffff; border-left: 5px solid #198754 !important; }
    .card-soft-warning { background-color: #ffffff; border-left: 5px solid #ffc107 !important; }
    .card-soft-danger { background-color: #ffffff; border-left: 5px solid #dc3545 !important; }

    /* Header Card */
    .card-header-custom {
        padding: 15px 20px 10px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    /* Badge Status Modern */
    .status-badge {
        font-size: 0.65rem;
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    /* List Item */
    .item-list { list-style: none; padding: 0 20px; margin: 0; }
    .item-list li {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f8f9fa;
    }
    .item-list li:last-child { border-bottom: none; }

    /* Text & Label */
    .item-name { font-size: 0.9rem; font-weight: 600; color: #333; }
    .item-status { font-size: 0.75rem; font-weight: 600; margin-left: 4px; }
    
    /* Footer Card */
    .card-footer-custom {
        padding: 15px 20px 20px;
        background-color: #fafafa;
    }

    /* Tombol Sejajar di Mobile */
    .btn-action-group {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }
    .btn-action-group .btn {
        flex: 1; /* Membuat tombol lebar 50:50 */
        padding: 10px;
        font-size: 0.85rem;
        font-weight: 700;
        border-radius: 12px;
    }

    /* Timer Countdown */
    .timer-container {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        margin-top: 8px;
        color: #dc3545;
        font-size: 0.8rem;
        font-weight: 600;
    }

    /* Star Rating */
    .static-rating i { font-size: 0.75rem; color: #e0e0e0; }
    .static-rating i.filled { color: #ffc107; }
</style>