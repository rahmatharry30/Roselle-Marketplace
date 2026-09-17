<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran QR</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ time() }}">
</head>
<body>
    <div class="qr-page">
        <div class="qr-card">
            <div class="qr-card-brand">
                <div class="brand-name">MARKETPLACE TAS</div>
                <div class="brand-sub">Scan untuk konfirmasi</div>
            </div>

            <div class="qr-card-body">
                <p class="qr-card-title">Scan QR di HP Anda</p>
                <p class="qr-card-order">Order #{{ $payment->order_id }}</p>

                <div class="qr-frame">
                    <div class="qr-corner tl"></div>
                    <div class="qr-corner tr"></div>
                    <div class="qr-corner bl"></div>
                    <div class="qr-corner br"></div>
                    {!! $qrImage !!}
                </div>

                @if ($payment->order)
                    <div class="qr-total">Rp{{ number_format($payment->order->total_price, 0, ',', '.') }}</div>
                    <div class="qr-total-label">Total Pembayaran</div>
                @endif
            </div>

            <div class="qr-status">
                <span class="qr-dot"></span>
                <span id="status-text">Menunggu konfirmasi dari HP...</span>
            </div>

            <div class="qr-card-footer">
                QR AKAN KADALUARSA DALAM 10 MENIT
            </div>
        </div>
    </div>

    <script>
        const statusUrl = "{{ route('payments.status', $payment->id) }}";

        const interval = setInterval(async () => {
            try {
                const res = await fetch(statusUrl);
                const data = await res.json();
                const statusText = document.getElementById('status-text');

                if (data.status === 'disetujui') {
                    statusText.innerText = 'Pembayaran disetujui! Mengalihkan...';
                    clearInterval(interval);
                    setTimeout(() => window.location.href = '/orders/{{ $payment->order_id }}?paid=1', 1500);
                } else if (data.status === 'ditolak') {
                    statusText.innerText = 'Pembayaran ditolak oleh pembeli.';
                    clearInterval(interval);
                } else if (data.status === 'kadaluarsa') {
                    statusText.innerText = 'QR sudah kadaluarsa, silakan ulangi.';
                    clearInterval(interval);
                }
            } catch (err) {
                console.error('Gagal cek status:', err);
            }
        }, 3000);
    </script>
</body>
</html>
