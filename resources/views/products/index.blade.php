@extends('layout')

@section('title', 'Daftar Produk')

@section('content')
    @if (!request()->filled('search') && !request()->filled('category_id'))
        <div class="rose-slider" id="roseSlider">
            <div class="rose-slider-track" id="roseSliderTrack">
                <a href="{{ route('products.show', 27) }}" class="rose-slide">
                    <img src="{{ asset('images/banner1.jpeg') }}" alt="Banner 1">
                </a>
                <a href="{{ route('products.show', 28) }}" class="rose-slide">
                    <img src="{{ asset('images/banner2.jpeg') }}" alt="Banner 2">
                </a>
                <a href="{{ route('products.show', 29) }}" class="rose-slide">
                    <img src="{{ asset('images/banner3.jpeg') }}" alt="Banner 3">
                </a>
            </div>

            <div class="rose-slider-dots">
                <button class="active" onclick="roseGoToSlide(0)"></button>
                <button onclick="roseGoToSlide(1)"></button>
                <button onclick="roseGoToSlide(2)"></button>
            </div>
        </div>
    @endif

    @if ($categories->count() > 0)
        <h2 class="rose-featcat-title">Featured Categories</h2>
        <div class="rose-featcat-grid">
            @foreach ($categories as $category)
                <a href="{{ route('products.index', ['category_id' => $category->id]) }}" class="rose-featcat-card">
                    @if ($category->image)
                        <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="rose-featcat-img">
                    @else
                        <div class="rose-featcat-noimg">👜</div>
                    @endif
                    <div class="rose-featcat-name">{{ $category->name }}</div>
                </a>
            @endforeach
        </div>
    @endif

    @if ($bestSellers->count() > 0)
        <div class="rose-bestseller-section">
            <h2 class="rose-bestseller-title"><span class="spark">✨</span> Best Seller</h2>
            <div class="rose-bestseller-scroll">
                @foreach ($bestSellers as $bs)
                    <a href="{{ route('products.show', $bs) }}" class="rose-bs-card">
                        <div class="rose-bs-badge">⭐ Best Seller</div>
                        @if ($bs->image)
                            <img src="{{ $bs->image_url }}" alt="{{ $bs->name }}" class="rose-bs-img">
                        @else
                            <div class="rose-bs-noimg">Tidak ada gambar</div>
                        @endif
                        <div class="rose-bs-body">
                            <div class="rose-bs-name">{{ $bs->name }}</div>
                            <div class="rose-bs-price">Rp{{ number_format($bs->price, 0, ',', '.') }}</div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <h1 class="rose-page-title" id="koleksi">Koleksi Tas Kami</h1>

    @auth
        @if (auth()->user()->isSeller())
            <a class="rose-add-btn" href="{{ route('products.create') }}">+ Tambah Produk</a>
        @endif
    @endauth

    @if (request()->filled('search'))
        <p style="margin-bottom:20px; font-size:13px; color:#a78b7a;">
            Menampilkan hasil untuk "<strong>{{ request('search') }}</strong>" —
            <a href="{{ route('products.index') }}" style="color:var(--rose-pink-dark);">Reset</a>
        </p>
    @endif

    @if ($activeCategory)
        <p style="margin-bottom:20px; font-size:13px; color:#a78b7a;">
            Kategori: <strong>{{ $activeCategory->name }}</strong> —
            <a href="{{ route('products.index') }}" style="color:var(--rose-pink-dark);">Lihat Semua</a>
        </p>
    @endif

    <div class="rose-grid">
        @forelse ($products as $product)
            <div class="rose-card">
                <div class="rose-card-fav">🤍</div>

                <a href="{{ route('products.show', $product) }}">
                    @if ($product->image)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="rose-card-img">
                    @else
                        <div class="rose-card-noimg">Tidak ada gambar</div>
                    @endif
                </a>

                <div class="rose-card-body">
                    <a href="{{ route('products.show', $product) }}" class="rose-card-name">{{ $product->name }}</a>
                    <div class="rose-card-price">Rp{{ number_format($product->price, 0, ',', '.') }}</div>

                    <div class="rose-card-rating">
                        @if ($product->reviews->count() > 0)
                            ★ {{ $product->average_rating }} <span class="count">({{ $product->reviews->count() }})</span>
                        @else
                            <span class="count">Belum ada rating</span>
                        @endif
                        <span class="count" style="margin-left:6px;">· Stok {{ $product->stock }}</span>
                    </div>

                    <div class="rose-card-actions">
                        <a href="{{ route('products.show', $product) }}" class="rose-btn-view">Lihat</a>
                        @auth
                            @if (auth()->id() === $product->user_id)
                                <a href="{{ route('products.edit', $product) }}" class="rose-btn-edit">Edit</a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline-form" style="flex:1;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rose-btn-delete" style="width:100%;" onclick="return confirm('Yakin hapus?')">Hapus</button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        @empty
            <div class="rose-empty">Produk tidak ditemukan 🎀</div>
        @endforelse
    </div>

    @if (!request()->filled('search') && !request()->filled('category_id'))
        <script>
            let roseCurrentSlide = 0;
            let roseDirection = 1;
            const roseTrack = document.getElementById('roseSliderTrack');
            const roseSlides = document.querySelectorAll('#roseSlider .rose-slide');
            const roseDots = document.querySelectorAll('#roseSlider .rose-slider-dots button');

            function roseGoToSlide(index) {
                roseTrack.style.transform = `translateX(-${index * 100}%)`;
                roseDots.forEach(d => d.classList.remove('active'));
                roseDots[index].classList.add('active');
                roseCurrentSlide = index;
            }

            setInterval(() => {
                if (roseCurrentSlide === roseSlides.length - 1) {
                    roseDirection = -1;
                } else if (roseCurrentSlide === 0) {
                    roseDirection = 1;
                }
                roseGoToSlide(roseCurrentSlide + roseDirection);
            }, 4000);
        </script>
    @endif
@endsection
