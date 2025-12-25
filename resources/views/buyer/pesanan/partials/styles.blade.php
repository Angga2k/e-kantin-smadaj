<style>
    body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
    
    /* == Custom Soft Colors untuk Card == */
    .card-soft-success { background-color: #e8f5e9; border: 1px solid #c8e6c9; }
    .card-soft-warning { background-color: #fff8e1; border: 1px solid #ffecb3; }
    .card-soft-danger { background-color: #ffebee; border: 1px solid #ffcdd2; }

    .card-history {
        border-radius: 12px;
        margin-bottom: 1.5rem;
        transition: transform 0.2s;
    }
    .card-history:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    /* Modifikasi List Barang */
    .item-list { list-style: none; padding: 0; margin: 0; }
    .item-list li {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.75rem;
        font-size: 0.95rem;
        color: #495057;
        border-bottom: 1px dashed #e0e0e0;
        padding-bottom: 0.5rem;
    }
    .item-list li:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }

    /* Badge & Text Styles */
    .status-badge { font-size: 0.8rem; padding: 0.4em 0.8em; border-radius: 20px; font-weight: 600; letter-spacing: 0.5px; }
    .item-status { font-size: 0.7rem; font-weight: bold; margin-left: 5px; }
    .text-process { color: #ff9800; }
    .text-ready { color: #0d6efd; }
    .text-done { color: #198754; }
    
    /* Rating Stars */
    .static-rating i { font-size: 0.8rem; color: #e0e0e0; }
    .static-rating i.filled { color: #ffc107; }
    .star-rating { font-size: 2rem; color: #ddd; cursor: pointer; transition: color 0.2s; }
    .star-rating.active { color: #ffc107; }
    .star-rating:hover { color: #ffd54f; }

    /* Animasi Timer */
    .countdown-timer { font-variant-numeric: tabular-nums; letter-spacing: 0.5px; }
</style>