@extends('layout')

@section('title', 'Dashboard Saya')

@section('content')
    <h1 class="rose-page-title">Status Pesanan Saya</h1>

    <div class="rose-stat-grid">
        <div class="rose-stat-card">
            <p class="rose-stat-label">Belum Dibayar</p>
            <p class="rose-stat-value">{{ $totalBelumDibayar }}</p>
        </div>
        <div class="rose-stat-card">
            <p class="rose-stat-label">Diproses</p>
            <p class="rose-stat-value">{{ $totalDiproses }}</p>
        </div>
        <div class="rose-stat-card">
            <p class="rose-stat-label">Dikemas</p>
            <p class="rose-stat-value">{{ $totalDikemas }}</p>
        </div>
        <div class="rose-stat-card">
            <p class="rose-stat-label">Dalam Perjalanan</p>
            <p class="rose-stat-value">{{ $totalDalamPerjalanan }}</p>
        </div>
        <div class="rose-stat-card">
            <p class="rose-stat-label">Selesai</p>
            <p class="rose-stat-value">{{ $totalSelesai }}</p>
        </div>
        <div class="rose-stat-card">
            <p class="rose-stat-label">Sudah Dirating</p>
            <p class="rose-stat-value">{{ $totalSudahDirating }}</p>
        </div>
        <div class="rose-stat-card">
            <p class="rose-stat-label">Dibatalkan</p>
            <p class="rose-stat-value">{{ $totalDibatalkan }}</p>
        </div>
    </div>

    <a href="{{ route('orders.index') }}" class="rose-back-link">Lihat Semua Pesanan Saya →</a>
@endsection
