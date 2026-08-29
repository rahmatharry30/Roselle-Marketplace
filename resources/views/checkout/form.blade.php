@extends('layout')

@section('title', 'Checkout')

@section('content')
    <h1 class="rose-page-title">Checkout</h1>

    <div class="rose-panel">
        <h3 class="rose-panel-title">Ringkasan Pesanan</h3>

        <div class="rose-table-wrap" style="margin-bottom:20px;">
            <table class="rose-table">
                <tr>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
                @foreach ($cart->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp{{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </table>
        </div>

        <div style="display:flex; justify-content:space-between; align-items:center; padding:0 5px;">
            <span style="color:#a78b7a; font-weight:600;">Total Pembayaran</span>
            <span class="rose-detail-price" style="margin:0; font-size:22px;">Rp{{ number_format($total, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="rose-panel" style="max-width:450px;">
        @if (session('error'))
            <p style="color:#dc2626; font-size:13px; margin-top:0;">{{ session('error') }}</p>
        @endif

        <form action="{{ route('checkout.process') }}" method="POST" class="rose-form">
            @csrf

            <label>Metode Pembayaran</label>
            <select name="payment_method" required>
                <option value="cod">COD (Bayar di Tempat)</option>
                <option value="transfer">Transfer (QR Konfirmasi)</option>
            </select>

            <button type="submit" class="rose-btn-pink" style="width:100%;">Buat Pesanan</button>
        </form>
    </div>

    <a href="{{ route('cart.index') }}" class="rose-back-link">← Kembali ke keranjang</a>
@endsection
