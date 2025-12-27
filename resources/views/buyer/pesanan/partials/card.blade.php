@php
    $transaksiTime = \Carbon\Carbon::parse($order->waktu_transaksi);
    $deadline = $transaksiTime->copy()->addMinutes(30);
    $now = \Carbon\Carbon::now();
    $isExpiredByTime = $now->greaterThan($deadline);
    $status = strtolower($order->status_pembayaran);

    $cardClass = 'card-soft-danger';
    $badgeClass = 'bg-danger';
    $statusLabel = 'Gagal';
    $isSuccess = $status == 'success';
    $isPending = $status == 'pending' && !$isExpiredByTime;

    if ($isSuccess) {
        $cardClass = 'card-soft-success';
        $badgeClass = 'bg-success text-white';
        $statusLabel = 'Berhasil';
    } elseif ($status == 'pending') {
        if ($isExpiredByTime) {
            $statusLabel = 'Expired';
        } else {
            $cardClass = 'card-soft-warning';
            $badgeClass = 'bg-warning text-dark';
            $statusLabel = 'Menunggu';
        }
    }
@endphp

<div class="card card-history {{ $cardClass }}">
    {{-- 1. Header: Waktu & Status --}}
    <div class="card-header-custom">
        <div>
            <div class="fw-bold text-dark" style="font-size: 0.85rem;">
                {{ $transaksiTime->translatedFormat('d M Y, H:i') }}
            </div>
            <small class="text-muted" style="font-size: 0.7rem;">{{ $order->kode_transaksi }}</small>
        </div>
        <span class="badge {{ $badgeClass }} status-badge">{{ $statusLabel }}</span>
    </div>

    {{-- 2. Body: Daftar Produk --}}
    <ul class="item-list">
        @foreach($order->detailTransaksi as $detail)
            <li>
                <div class="d-flex flex-column">
                    <span class="item-name">
                        {{ $detail->jumlah }}x {{ $detail->barang->nama_barang ?? 'Produk' }}
                        
                        @php $statusItem = strtolower(trim($detail->status_barang)); @endphp
                        @if(!$isExpiredByTime || $isSuccess)
                            @if($statusItem == 'sudah_diambil')
                                <small class="item-status text-success"><i class="bi bi-check2-circle"></i></small>
                            @elseif($statusItem == 'belum_diambil')
                                <small class="item-status text-primary"><i class="bi bi-box-seam"></i></small>
                            @else
                                <small class="item-status text-warning"><i class="bi bi-hourglass-split"></i></small>
                            @endif
                        @endif
                    </span>
                    
                    @if($isSuccess && $detail->ratingUlasan)
                        <div class="static-rating">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star-fill {{ $i <= $detail->ratingUlasan->rating ? 'filled' : '' }}"></i>
                            @endfor
                        </div>
                    @endif
                </div>
                <span class="fw-bold text-dark" style="font-size: 0.9rem;">
                    Rp {{ number_format($detail->harga_saat_transaksi * $detail->jumlah, 0, ',', '.') }}
                </span>
            </li>
        @endforeach
    </ul>

    {{-- 3. Footer: Total & Action --}}
    <div class="card-footer-custom">
        <div class="d-flex justify-content-between align-items-center">
            <span class="text-muted" style="font-size: 0.8rem;">Total Pembayaran</span>
            <span class="fw-bold fs-5 text-dark">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
        </div>

        @if ($isPending)
            <div class="btn-action-group">
                <button type="button" class="btn btn-outline-danger" onclick="cancelOrder('{{ $order->id_transaksi }}')">
                    Batal
                </button>
                <button type="button" class="btn btn-warning text-dark shadow-sm"
                        onclick="showPaymentOptions('{{ $order->id_transaksi }}', '{{ $order->metode_pembayaran }}', '{{ $order->payment_link }}', 'Rp {{ number_format($order->total_harga, 0, ',', '.') }}')">
                    Bayar
                </button>
            </div>
            <div class="timer-container countdown-timer" data-deadline="{{ $deadline->timestamp * 1000 }}">
                <i class="bi bi-stopwatch"></i> Menghitung...
            </div>
        @elseif ($isSuccess)
            <div class="btn-action-group">
                <button class="btn btn-success shadow-sm w-100" 
                    data-transaction="{{ $order->kode_transaksi }}"
                    data-items="{{ $order->detailTransaksi->map(fn($d) => ['id_detail' => $d->id_detail, 'name' => $d->barang->nama_barang, 'status' => $d->status_barang, 'rating' => $d->ratingUlasan?->rating, 'ulasan' => $d->ratingUlasan?->ulasan])->toJson() }}" 
                    onclick="handleRatingButton(this)">
                    <i class="bi bi-star-fill me-1"></i> Beri Ulasan
                </button>
            </div>
        @else
            <div class="text-center mt-3 text-muted fst-italic" style="font-size: 0.75rem;">
                Transaksi ini telah berakhir.
            </div>
        @endif
    </div>
</div>