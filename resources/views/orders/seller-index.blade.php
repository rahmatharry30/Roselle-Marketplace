@extends('layout')

@section('title', 'Kelola Pesanan')

@section('content')
    <h1 class="rose-page-title">Kelola Pesanan</h1>

    @if (session('success'))
        <p style="color:#15803d; font-size:13px;">{{ session('success') }}</p>
    @endif

    <div class="rose-table-wrap">
        <table class="rose-table">
            <tr>
                <th>ID</th>
                <th>Pembeli</th>
                <th>Total</th>
                <th>Metode</th>
                <th>Status</th>
                <th>Ubah Status</th>
            </tr>
            @forelse ($orders as $order)
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
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td>Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td>{{ strtoupper($order->payment_method) }}</td>
                    <td><span class="rose-badge {{ $badgeClass }}">{{ str_replace('_', ' ', $order->status) }}</span></td>
                    <td>
                        <form action="{{ route('orders.updateStatus', $order) }}" method="POST" class="inline-form">
                            @csrf
                            @method('PATCH')
                            <select name="status" onchange="this.form.submit()" style="margin:0; width:auto;">
                                <option value="belum_dibayar" @selected($order->status === 'belum_dibayar')>Belum Dibayar</option>
                                <option value="diproses" @selected($order->status === 'diproses')>Diproses</option>
                                <option value="dikemas" @selected($order->status === 'dikemas')>Dikemas</option>
                                <option value="dalam_perjalanan" @selected($order->status === 'dalam_perjalanan')>Dalam Perjalanan</option>
                                <option value="selesai" @selected($order->status === 'selesai')>Selesai</option>
                                <option value="dibatalkan" @selected($order->status === 'dibatalkan')>Dibatalkan</option>
                            </select>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center; color:#d1a3b8;">Belum ada pesanan masuk.</td></tr>
            @endforelse
        </table>
    </div>
@endsection
