@extends('layout')

@section('title', 'Edit Kategori')

@section('content')
    <div class="rose-panel" style="max-width:450px;">
        <h2 class="rose-panel-title">Edit Kategori</h2>

        @if ($category->image)
            <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="preview">
        @endif

        <form action="{{ route('categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="rose-form">
            @csrf
            @method('PUT')

            <label>Nama Kategori</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" required>

            <label>Ganti Gambar (opsional)</label>
            <input type="file" name="image" accept="image/*">

            @error('name')
                <div class="rose-form-error">{{ $message }}</div>
            @enderror
            @error('image')
                <div class="rose-form-error">{{ $message }}</div>
            @enderror

            <button type="submit" class="rose-btn-pink">Update</button>
        </form>
    </div>

    <a href="{{ route('categories.index') }}" class="rose-back-link">← Kembali ke daftar</a>
@endsection
