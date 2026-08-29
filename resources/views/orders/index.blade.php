@extends('layout')

@section('title', 'Pesanan Saya')

@section('content')
    <h1 class="rose-page-title">Pesanan Saya</h1>

    <div class="rose-table-wrap">
        <table class="rose-table">
            <tr>
                <th>ID</th>
                <th>Total</th>
                <th>Metode</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            @forelse ($orders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td>{{ strtoupper($order->payment_method) }}</td>
                    <td>
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
                        <span class="rose-badge {{ $badgeClass }}">{{ str_replace('_', ' ', $order->status) }}</span>
                    </td>
                    <td><a href="{{ route('orders.show', $order) }}" class="rose-btn-view" style="padding:6px 14px; border-radius:8px;">Detail</a></td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center; color:#d1a3b8;">Belum ada pesanan.</td></tr>
            @endforelse
        </table>
    </div>
@endsection
