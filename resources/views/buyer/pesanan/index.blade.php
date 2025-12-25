@extends('layouts.Buyer')

@section('title', 'Pesanan')

@section('content')
    {{-- 1. Load Styles --}}
    @include('buyer.pesanan.partials.styles')

    <div class="container my-5" style="max-width: 800px;">
        <h3 class="fw-bold mb-4">Riwayat Pesanan</h3>

        @if($orders->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-receipt text-muted opacity-25" style="font-size: 4rem;"></i>
                <p class="text-muted mt-3">Belum ada riwayat pesanan.</p>
                <a href="{{ route('buyer.menu.index') ?? '#' }}" class="btn btn-primary btn-sm rounded-pill px-4">Pesan Sekarang</a>
            </div>
        @else
            {{-- 2. Loop Card Component --}}
            @foreach($orders as $order)
                @include('buyer.pesanan.partials.card', ['order' => $order])
            @endforeach
        @endif
    </div>

    {{-- 3. Include Modal --}}
    @include('buyer.pesanan.partials.rating-modal')

    {{-- 4. Include Scripts --}}
    @include('buyer.pesanan.partials.scripts')
@endsection