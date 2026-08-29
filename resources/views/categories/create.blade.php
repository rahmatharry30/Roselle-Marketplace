@extends('layout')

@section('title', 'Tambah Kategori')

@section('content')
    <div class="rose-panel" style="max-width:450px;">
        <h2 class="rose-panel-title">Tambah Kategori</h2>

        <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data" class="rose-form">
            @csrf

            <label>Nama Kategori</label>
            <input type="text" name="name" value="{{ old('name') }}" required>

            <label>Gambar Kategori</label>
            <input type="file" name="image" accept="image/*">

            @error('name')
                <div class="rose-form-error">{{ $message }}</div>
            @enderror
            @error('image')
                <div class="rose-form-error">{{ $message }}</div>
            @enderror

            <button type="submit" class="rose-btn-pink">Simpan</button>
        </form>
    </div>

    <a href="{{ route('categories.index') }}" class="rose-back-link">← Kembali ke daftar</a>
@endsection
