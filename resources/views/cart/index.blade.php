@extends('layout')

@section('title', 'Keranjang Saya')

@section('content')
    <h1 class="rose-page-title">Keranjang Saya</h1>

    <div class="rose-table-wrap" style="margin-bottom:20px;">
        <table class="rose-table">
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Subtotal</th>
                <th>Aksi</th>
            </tr>
            @forelse ($cart->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>Rp{{ number_format($item->product->price, 0, ',', '.') }}</td>
                    <td>
                        <form action="{{ route('cart.updateQuantity', $item) }}" method="POST" class="inline-form">
                            @csrf
                            @method('PUT')
                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" style="width:60px; display:inline; margin:0;">
                            <button type="submit" class="rose-btn-outline" style="padding:5px 12px; font-size:12px;">Update</button>
                        </form>
                    </td>
                    <td>Rp{{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</td>
                    <td>
                        <form action="{{ route('cart.remove', $item) }}" method="POST" class="inline-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rose-btn-delete" onclick="return confirm('Hapus item ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center; color:#d1a3b8;">Keranjang masih kosong 🎀</td></tr>
            @endforelse
        </table>
    </div>

    <div class="rose-panel" style="max-width:400px; display:flex; justify-content:space-between; align-items:center;">
        <span style="color:#a78b7a; font-weight:600;">Total</span>
        <span class="rose-detail-price" style="margin:0; font-size:22px;">Rp{{ number_format($total, 0, ',', '.') }}</span>
    </div>

    @if ($cart->items->count() > 0)
        <a class="rose-btn-pink" href="{{ route('checkout.form') }}">Lanjut ke Checkout</a>
    @endif

    <br><br>
    <a href="{{ route('products.index') }}" class="rose-back-link">← Lanjut belanja</a>
@endsection
