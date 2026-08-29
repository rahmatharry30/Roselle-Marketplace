@extends('layout')

@section('title', 'Edit Produk')

@section('content')
    <div class="rose-panel" style="max-width:550px;">
        <h2 class="rose-panel-title">Edit Produk</h2>

        @if ($product->image)
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="preview">
        @endif

        <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="rose-form">
            @csrf
            @method('PUT')

            <label>Nama Tas</label>
            <input type="text" name="name" value="{{ old('name', $product->name) }}" required>

            <label>Deskripsi</label>
            <textarea name="description" rows="3">{{ old('description', $product->description) }}</textarea>

            <label>Harga</label>
            <input type="number" name="price" value="{{ old('price', $product->price) }}" required>

            <label>Stok</label>
            <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required>

            <label>Kategori</label>
            <select name="category_id">
                <option value="">-- Tanpa Kategori --</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <label>Ganti Gambar (opsional)</label>
            <input type="file" name="image" accept="image/*">

            <label style="display:flex; align-items:center; gap:8px; margin-bottom:20px;">
                <input type="checkbox" name="is_best_seller" value="1" @checked(old('is_best_seller', $product->is_best_seller)) style="width:auto; margin:0;">
                <span style="font-size:13px; font-weight:600; color:var(--rose-brown);">Tandai sebagai Best Seller ⭐</span>
            </label>

            @if ($errors->any())
                <ul class="rose-form-error">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <button type="submit" class="rose-btn-pink">Update Produk</button>
        </form>
    </div>

    <a href="{{ route('products.index') }}" class="rose-back-link">← Kembali ke daftar</a>
@endsection
