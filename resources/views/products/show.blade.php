@extends('layout')

@section('title', 'Detail Produk')

@section('content')
    <a href="{{ route('products.index') }}" class="rose-back-link">← Kembali ke Koleksi</a>

    <div class="rose-detail">
        @if ($product->image)
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="rose-detail-img">
        @else
            <div class="rose-detail-noimg">Tidak ada gambar</div>
        @endif

        <div class="rose-detail-info">
            @if ($product->category)
                <div class="rose-detail-category">{{ $product->category->name }}</div>
            @endif

            <h1 class="rose-detail-name">{{ $product->name }}</h1>

            <div class="rose-detail-rating">
                @if ($product->reviews->count() > 0)
                    {{ str_repeat('★', round($product->average_rating)) }}{{ str_repeat('☆', 5 - round($product->average_rating)) }}
                    {{ $product->average_rating }} <span class="count">({{ $product->reviews->count() }} ulasan)</span>
                @else
                    <span class="count">Belum ada ulasan</span>
                @endif
            </div>

            <div class="rose-detail-price">Rp{{ number_format($product->price, 0, ',', '.') }}</div>

            <p class="rose-detail-desc">{{ $product->description ?? 'Tidak ada deskripsi untuk produk ini.' }}</p>

            <div class="rose-detail-stock">📦 Stok tersedia: {{ $product->stock }}</div>

            @auth
                @if (auth()->user()->isBuyer())
                    <form action="{{ route('cart.add', $product) }}" method="POST" class="rose-buy-form">
                        @csrf
                        <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}">
                        <button type="submit" class="rose-buy-btn">Tambah ke Keranjang</button>
                    </form>

                    <form action="{{ route('chat.start', $product) }}" method="POST" style="margin-top:10px;">
                        @csrf
                        <button type="submit" class="rose-btn-outline">💬 Chat dengan Penjual</button>
                    </form>
                @endif
            @else
                <div class="rose-login-notice">
                    <a href="{{ route('login') }}">Login</a> dulu untuk mulai belanja ya~
                </div>
            @endauth
        </div>
    </div>

    <h3 class="rose-review-title">Ulasan Pembeli</h3>

    @forelse ($product->reviews()->with('user')->latest()->get() as $review)
        <div class="rose-review-card">
            <span class="rose-review-user">{{ $review->user->name }}</span>
            <span class="rose-review-stars">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
            <p class="rose-review-comment">{{ $review->comment ?? '-' }}</p>
        </div>
    @empty
        <div class="rose-review-empty">Belum ada ulasan untuk produk ini 🎀</div>
    @endforelse
@endsection
