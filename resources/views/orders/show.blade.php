@extends('layout')

@section('title', 'Detail Pesanan')

@section('content')
    <h1 class="rose-page-title">Pesanan #{{ $order->id }}</h1>

    @php
        $badgeClass = match($order->status) {
            'belum_dibayar' => 'rose-badge-waiting',
            'diproses' => 'rose-badge-processing',
            'dikemas' => 'rose-badge-packed',
            'dalam_perjalanan' => 'rose-badge-shipping',
            'selesai' => 'rose-badge-done',
            'dibatalkan' => 'rose-badge-cancel',
            default => 'rose-badge-waiting',
        };
    @endphp

    <div class="rose-panel">
        <span class="rose-badge {{ $badgeClass }}">{{ str_replace('_', ' ', $order->status) }}</span>
        <p style="margin:12px 0 0; color:#a78b7a; font-size:13px;">Metode Bayar: <strong>{{ strtoupper($order->payment_method) }}</strong></p>
    </div>

    @if (session('success'))
        <p style="color:#15803d; font-size:13px;">{{ session('success') }}</p>
    @endif
    @if (session('error'))
        <p style="color:#dc2626; font-size:13px;">{{ session('error') }}</p>
    @endif

    <div class="rose-table-wrap" style="margin-bottom:20px;">
        <table class="rose-table">
            <tr>
                <th>Produk</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Subtotal</th>
                @if ($order->status === 'selesai')
                    <th>Review</th>
                @endif
            </tr>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                    @if ($order->status === 'selesai')
                        <td>
                            @php
                                $review = $item->product->reviews()
                                    ->where('user_id', auth()->id())
                                    ->where('order_id', $order->id)
                                    ->first();
                            @endphp

                            @if ($review)
                                <span style="color:#f59e0b;">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                            @else
                                <details>
                                    <summary style="cursor:pointer; color:var(--rose-pink-dark); font-size:13px;">Beri Review</summary>
                                    <form action="{{ route('reviews.store', $order) }}" method="POST" class="rose-form" style="margin-top:10px; max-width:250px;">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $item->product_id }}">

                                        <label>Rating</label>
                                        <select name="rating" required>
                                            <option value="5">★★★★★ (5)</option>
                                            <option value="4">★★★★☆ (4)</option>
                                            <option value="3">★★★☆☆ (3)</option>
                                            <option value="2">★★☆☆☆ (2)</option>
                                            <option value="1">★☆☆☆☆ (1)</option>
                                        </select>

                                        <label>Komentar</label>
                                        <textarea name="comment" rows="2" placeholder="Bagaimana produknya?"></textarea>

                                        <button type="submit" class="rose-btn-pink" style="padding:8px 16px; font-size:12px;">Kirim</button>
                                    </form>
                                </details>
                            @endif
                        </td>
                    @endif
                </tr>
            @endforeach
        </table>
    </div>

    <div class="rose-panel" style="max-width:400px; display:flex; justify-content:space-between; align-items:center;">
        <span style="color:#a78b7a; font-weight:600;">Total</span>
        <span class="rose-detail-price" style="margin:0; font-size:20px;">Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
    </div>

    @if ($order->status === 'belum_dibayar')
        <form action="{{ route('orders.cancel', $order) }}" method="POST" class="inline-form">
            @csrf
            @method('DELETE')
            <button type="submit" class="rose-btn-delete" onclick="return confirm('Yakin batalkan pesanan ini?')">Batalkan Pesanan</button>
        </form>
    @endif

    <br><br>
    <a href="{{ route('orders.index') }}" class="rose-back-link">← Kembali ke daftar pesanan</a>
@endsection
