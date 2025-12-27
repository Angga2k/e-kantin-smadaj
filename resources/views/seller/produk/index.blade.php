@extends('layouts.Seller')

@section('title', 'Produk')

@section('content')
<style>
    @media (max-width: 575.98px) {
        .breadcrumb {
            font-size: 0.75rem;
            margin-bottom: 0.5rem !important;
            padding: 0 !important;
        }

        .breadcrumb-item {
            padding: 0 !important;
        }

        .d-flex.flex-column.flex-md-row {
            align-items: flex-start !important;
        }

        .card-img-top {
            height: 200px !important;
        }

        .card-body {
            padding: 0.75rem !important;
        }

        .product-title {
            font-size: 0.95rem;
            margin-bottom: 0.15rem !important;
        }

        .product-price {
            font-size: 0.95rem;
            margin-bottom: 1rem !important;
        }

        .btn {
            padding: 0.45rem 0.6rem !important;
            font-size: 0.85rem !important;
        }

        .rating-badge {
            padding: 2px 8px !important;
            font-size: 0.75rem !important;
        }

        h2 {
            font-size: 1.3rem;
        }

        .btn-add-new {
            padding: 0.5rem 1rem !important;
            font-size: 0.85rem !important;
        }
    }
</style>

<main class="container my-4 pb-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
        <div class="mb-3 mb-md-0">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 small">
                    <li class="breadcrumb-item"><a href="{{ route('seller.beranda.index') }}" class="text-decoration-none text-muted">Beranda</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Produk</li>
                </ol>
            </nav>
            <h2 class="fw-bold mb-0">Produk</h2>
        </div>

        <a href="{{ route('seller.produk.create') }}" class="btn btn-add-new shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Produk Baru
        </a>
    </div>

    <div class="row g-4">
        @forelse($produk as $item)
            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                <div class="card products-card h-100">
                    <div class="card-img-wrapper">
                        {{-- PERBAIKAN: Gunakan asset('storage/...') untuk gambar upload --}}
                        <img src="{{ $item->foto_barang ? asset($item->foto_barang) : asset('icon/Makanan.png') }}"
                             class="card-img-top"
                             alt="{{ $item->nama_barang }}"
                             style="object-fit: cover; height: 200px; width: 100%;">

                        <div class="rating-badge">
                            <i class="bi bi-star-fill"></i> -
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="product-title text-truncate" title="{{ $item->nama_barang }}">
                            {{ $item->nama_barang }}
                        </h5>
                        <p class="product-price mb-4">
                            Rp {{ number_format($item->harga, 0, ',', '.') }}
                        </p>

                        <div class="mt-auto d-grid gap-2 d-flex">
                            {{-- PERBAIKAN: Link Edit menuju route yang benar --}}
                            <a href="{{ route('seller.produk.edit', $item->id_barang) }}" class="btn btn-action-edit w-100">Edit</a>

                            {{-- PERBAIKAN: Form Hapus menuju route yang benar --}}
                            <form action="{{ route('seller.produk.destroy', $item->id_barang) }}" method="POST" class="w-100" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-action-delete w-100">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <img src="{{ asset('icon/empty-box.png') }}" alt="Kosong" style="width: 100px; opacity: 0.5;" onerror="this.style.display='none'">
                    <p class="text-muted mt-3">Belum ada produk yang ditambahkan.</p>
                </div>
            </div>
        @endforelse
    </div>
</main>
@endsection
