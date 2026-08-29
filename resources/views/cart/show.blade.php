@extends('layout')

@section('title', 'Detail Produk')

@section('content')
    <h1>{{ $product->name }}</h1>
    <p><strong>Deskripsi:</strong> {{ $product->description ?? '-' }}</p>
    <p><strong>Harga:</strong> Rp{{ number_format($product->price, 0, ',', '.') }}</p>
    <p><strong>Stok:</strong> {{ $product->stock }}</p>
    <p><strong>Kategori:</strong> {{ $product->category->name ?? '-' }}</p>

    @auth
        @if (auth()->user()->isBuyer())
            <form action="{{ route('cart.add', $product) }}" method="POST">
                @csrf
                <label>Jumlah</label>
                <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}">
                <button type="submit">Tambah ke Keranjang</button>
            </form>
        @endif
    @else
        <p><a href="{{ route('login') }}">Login</a> dulu buat bisa belanja.</p>
    @endauth

    <br>
    <a href="{{ route('products.index') }}">← Kembali ke daftar</a>
@endsection
