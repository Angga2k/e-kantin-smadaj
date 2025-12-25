@php
    // --- LOGIKA STATUS CARD & WAKTU ---
    $transaksiTime = \Carbon\Carbon::parse($order->waktu_transaksi);
    $deadline = $transaksiTime->copy()->addMinutes(30);
    $now = \Carbon\Carbon::now();
    $isExpiredByTime = $now->greaterThan($deadline);
    $status = strtolower($order->status_pembayaran);

    $cardClass = 'card-soft-danger';
    $badgeClass = 'bg-danger';
    $statusLabel = 'Pembayaran Gagal';
    $isSuccess = false;
    $isPending = false;
    $showTimer = false;

    if ($status == 'success') {
        $cardClass = 'card-soft-success';
        $badgeClass = 'bg-success';
        $statusLabel = 'Pembayaran Berhasil';
        $isSuccess = true;
    } 
    elseif ($status == 'pending') {
        if ($isExpiredByTime) {
            $statusLabel = 'Dibatalkan (Waktu Habis)';
        } else {
            $cardClass = 'card-soft-warning';
            $badgeClass = 'bg-warning text-dark';
            $statusLabel = 'Menunggu Pembayaran';
            $isPending = true;
            $showTimer = true;
        }
    }
    elseif ($status == 'expired' || $status == 'failed') {
        $statusLabel = $status == 'expired' ? 'Kadaluarsa' : 'Dibatalkan';
    }
@endphp

<div class="card card-history {{ $cardClass }}">
    <div class="card-body p-4">
        {{-- Header Card --}}
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <h6 class="fw-bold mb-1">
                    {{ $transaksiTime->translatedFormat('d F Y, H:i') }}
                </h6>
                <small class="text-muted">{{ $order->kode_transaksi }}</small>
            </div>
            <span class="badge {{ $badgeClass }} status-badge">{{ $statusLabel }}</span>
        </div>

        <hr class="opacity-25 border-dark">

        {{-- List Item Barang --}}
        <ul class="item-list mb-3">
            @foreach($order->detailTransaksi as $detail)
                <li>
                    <div class="d-flex flex-column">
                        <span>
                            {{ $detail->jumlah }}x {{ $detail->barang->nama_barang ?? 'Produk Dihapus' }}
                            
                            @php $statusItem = strtolower(trim($detail->status_barang)); @endphp

                            @if($status != 'expired' && $status != 'failed' && !($status == 'pending' && $isExpiredByTime))
                                @if($statusItem == 'sudah_diambil')
                                    <small class="item-status text-done"><i class="bi bi-check-circle-fill"></i> Diambil</small>
                                @elseif($statusItem == 'belum_diambil')
                                    <small class="item-status text-ready"><i class="bi bi-box-seam-fill"></i> Siap Diambil</small>
                                @else
                                    <small class="item-status text-process"><i class="bi bi-hourglass-split"></i> Proses</small>
                                @endif
                            @endif
                        </span>

                        @if($isSuccess && $detail->ratingUlasan)
                            <div class="static-rating mt-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star-fill {{ $i <= $detail->ratingUlasan->rating ? 'filled' : '' }}"></i>
                                @endfor
                                <span class="ms-1 text-muted" style="font-size: 0.75rem;">({{ number_format($detail->ratingUlasan->rating, 1) }})</span>
                            </div>
                        @endif
                    </div>
                    <span class="fw-bold">Rp {{ number_format($detail->harga_saat_transaksi * $detail->jumlah, 0, ',', '.') }}</span>
                </li>
            @endforeach
        </ul>

        <hr class="opacity-25 border-dark">

        {{-- Footer Card (Total & Action Button) --}}
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                <small class="text-muted d-block">Total Pembayaran</small>
                <h5 class="fw-bold {{ $isPending ? 'text-dark' : ($isSuccess ? 'text-success' : 'text-danger') }} mb-0">
                    Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                </h5>
            </div>
            
            @if ($isSuccess)
                {{-- Tombol Ulasan --}}
                @php
                    $itemsForJs = $order->detailTransaksi->map(function($d) {
                        return [
                            'id_detail' => $d->id_detail,
                            'name' => $d->barang->nama_barang ?? 'Produk',
                            'status' => strtolower(trim($d->status_barang)),
                            'rating' => $d->ratingUlasan ? $d->ratingUlasan->rating : null,
                            'ulasan' => $d->ratingUlasan ? $d->ratingUlasan->ulasan : ''
                        ];
                    })->toJson();
                @endphp

                <button class="btn btn-success btn-sm px-3 rounded-pill fw-bold shadow-sm" 
                    data-transaction="{{ $order->kode_transaksi }}"
                    data-items="{{ $itemsForJs }}"
                    onclick="handleRatingButton(this)">
                    <i class="bi bi-star-fill me-1"></i> Beri/Edit Ulasan
                </button>

            @elseif ($isPending)
                {{-- Tombol Bayar & Batalkan --}}
                <div class="d-flex flex-column align-items-end">
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-danger btn-sm px-3 rounded-pill fw-bold shadow-sm"
                                onclick="cancelOrder('{{ $order->id_transaksi }}')">
                            <i class="bi bi-x-circle me-1"></i> Batalkan
                        </button>

                        <button type="button" class="btn btn-warning btn-sm px-3 rounded-pill fw-bold text-dark shadow-sm"
                                onclick="showPaymentOptions('{{ $order->id_transaksi }}', '{{ $order->metode_pembayaran }}', '{{ $order->payment_link }}', 'Rp {{ number_format($order->total_harga, 0, ',', '.') }}')">
                            <i class="bi bi-wallet2 me-1"></i> Bayar Sekarang
                        </button>
                    </div>

                    @if($showTimer)
                        <small class="text-danger fw-bold countdown-timer mt-1" 
                               data-deadline="{{ $deadline->timestamp * 1000 }}">
                               <i class="bi bi-stopwatch"></i> Menghitung...
                        </small>
                    @endif
                </div>
            
            @else
                {{-- Status Akhir (Gagal/Expired) --}}
                <span class="text-muted small fst-italic text-end">
                    @if($isExpiredByTime && $status == 'pending')
                        Waktu pembayaran (30 menit) habis.<br>Transaksi dibatalkan otomatis.
                    @else
                        {{ $status == 'expired' ? 'Waktu pembayaran habis' : 'Transaksi dibatalkan' }}
                    @endif
                </span>
            @endif
        </div>
    </div>
</div>