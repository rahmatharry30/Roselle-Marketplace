@extends('layout')

@section('title', 'Daftar Kategori')

@section('content')
    <h1 class="rose-page-title">Kategori Tas</h1>

    @auth
        @if (auth()->user()->isSeller())
            <a class="rose-add-btn" href="{{ route('categories.create') }}">+ Tambah Kategori</a>
        @endif
    @endauth

    <div class="rose-cat-grid">
        @forelse ($categories as $category)
            <div class="rose-cat-card">
                <a href="{{ route('products.index', ['category_id' => $category->id]) }}" style="text-decoration:none; display:block;">
                    <div class="rose-cat-name">{{ $category->name }}</div>
                    <div class="rose-cat-count">{{ $category->products_count }} produk</div>
                </a>

                @auth
                    @if (auth()->user()->isSeller())
                        <div class="rose-cat-actions">
                            <a href="{{ route('categories.edit', $category) }}" class="rose-btn-edit" style="flex:1; padding:8px; border-radius:8px; font-size:12px; font-weight:600;">Edit</a>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline-form" style="flex:1;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rose-btn-delete" style="width:100%; padding:8px; border-radius:8px; font-size:12px;" onclick="return confirm('Yakin hapus?')">Hapus</button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>
        @empty
            <div class="rose-empty">Belum ada kategori.</div>
        @endforelse
    </div>
@endsection
