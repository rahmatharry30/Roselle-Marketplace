@extends('layout')

@section('title', 'Dashboard')

@section('content')
    <h1 class="rose-page-title">Dashboard Toko</h1>

    <div class="rose-stat-grid">
        <div class="rose-stat-card">
            <p class="rose-stat-label">Total Produk</p>
            <p class="rose-stat-value">{{ $totalProduk }}</p>
        </div>
        <div class="rose-stat-card">
            <p class="rose-stat-label">Sisa Stok</p>
            <p class="rose-stat-value">{{ $totalStok }}</p>
        </div>
        <div class="rose-stat-card">
            <p class="rose-stat-label">Total Terjual</p>
            <p class="rose-stat-value">{{ $totalTerjual }}</p>
        </div>
        <div class="rose-stat-card">
            <p class="rose-stat-label">Total Pendapatan</p>
            <p class="rose-stat-value" style="font-size:18px;">Rp{{ number_format($totalPendapatan, 0, ',', '.') }}</p>
        </div>
        <div class="rose-stat-card">
            <p class="rose-stat-label">Pendapatan Bulan Ini</p>
            <p class="rose-stat-value" style="font-size:18px;">Rp{{ number_format($pendapatanBulanIni, 0, ',', '.') }}</p>
        </div>
        <div class="rose-stat-card">
            <p class="rose-stat-label">Pesanan Menunggu</p>
            <p class="rose-stat-value">{{ $orderMasuk }}</p>
        </div>
    </div>

    <div class="rose-panel">
        <h3 class="rose-panel-title">Produk Terlaris</h3>
        <div class="rose-table-wrap">
            <table class="rose-table">
                <tr>
                    <th>Produk</th>
                    <th>Total Terjual</th>
                </tr>
                @forelse ($produkTerlaris as $item)
                    <tr>
                        <td>{{ $item->product->name ?? '-' }}</td>
                        <td>{{ $item->total_qty }}</td>
                    </tr>
                @empty
                    <tr><td colspan="2" style="text-align:center; color:#d1a3b8;">Belum ada penjualan.</td></tr>
                @endforelse
            </table>
        </div>
    </div>

    <a href="{{ route('orders.sellerIndex') }}" class="rose-back-link">Lihat Semua Pesanan →</a>
@endsection
