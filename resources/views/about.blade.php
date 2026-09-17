@extends('layout')

@section('title', 'Tentang Kami')

@section('content')
    <div class="rose-about-hero">
        <h1>Tentang Roselle</h1>
        <p>
            Roselle Fashion Bag lahir dari kecintaan pada tas-tas cantik yang menemani
            momen manis sehari-hari. Kami percaya setiap tas punya cerita, dan kami di sini
            untuk membantumu menemukan yang paling cocok denganmu.
        </p>
    </div>

    <div class="rose-about-grid">
        <div class="rose-about-card">
            <div class="icon">🎀</div>
            <h3>Didirikan</h3>
            <p>2024</p>
        </div>
        <div class="rose-about-card">
            <div class="icon">👜</div>
            <h3>Koleksi</h3>
            <p>Tas wanita segala gaya, dari kasual hingga formal</p>
        </div>
        <div class="rose-about-card">
            <div class="icon">💗</div>
            <h3>Nilai Kami</h3>
            <p>Kualitas, keramahan, dan kepuasan pelanggan di setiap pesanan</p>
        </div>
    </div>

    <div class="rose-panel" style="max-width:500px;">
        <h2 class="rose-panel-title">Hubungi Kami</h2>
        <ul class="rose-contact-list">
            <li>📧 hello@roselle.id</li>
            <li>📱 +62 812-3456-7890</li>
            <li>📍 Jakarta, Indonesia</li>
            <li>📷 @roselle.fashionbag</li>
        </ul>
    </div>
@endsection
