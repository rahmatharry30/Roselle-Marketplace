<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Pembayaran</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body style="background:#f4f6f8;">
    <div class="pay-page">
        <div class="pay-header">
            <a href="{{ route('products.index') }}">←</a>
            <h1>Verifikasi Pembayaran</h1>
        </div>

        <div class="pay-intro">
            <div class="pay-intro-icon">📄</div>
            <div>
                <h2>Verifikasi Pembayaran</h2>
                <p>Periksa detail transaksi dari pesanan berikut sebelum menyetujui.</p>
            </div>
        </div>

        <div class="pay-card">
            <h3>Detail Transaksi</h3>

            <div class="pay-row">
                <div class="pay-row-icon">👤</div>
                <div class="pay-row-label">Nama Pembeli</div>
                <div class="pay-row-value">{{ $payment->order->user->name }}</div>
            </div>

            <div class="pay-row">
                <div class="pay-row-icon">📅</div>
                <div class="pay-row-label">Tanggal Transaksi</div>
                <div class="pay-row-value">{{ $payment->created_at->translatedFormat('d M Y, H:i') }}</div>
            </div>

            <div class="pay-row">
                <div class="pay-row-icon">🧾</div>
                <div class="pay-row-label">Order ID</div>
                <div class="pay-row-value">#INV-{{ $payment->order->id }}</div>
            </div>

            <div class="pay-row">
                <div class="pay-row-icon">💳</div>
                <div class="pay-row-label">Metode Pembayaran</div>
                <div class="pay-row-value">{{ strtoupper($payment->order->payment_method) }}</div>
            </div>

            <div class="pay-row">
                <div class="pay-row-icon">💰</div>
                <div class="pay-row-label">Total Pembayaran</div>
                <div class="pay-row-value highlight">Rp{{ number_format($payment->order->total_price, 0, ',', '.') }}</div>
            </div>
        </div>

        @if ($payment->status === 'menunggu')
            <div class="pay-actions">
                <button type="button" class="pay-btn pay-btn-reject" onclick="sendConfirm(false)">✕ Tolak</button>
                <button type="button" class="pay-btn pay-btn-approve" onclick="sendConfirm(true)">✓ Setujui</button>
            </div>

            <div class="pay-note">
                🛡️ <span>Pastikan detail transaksi sesuai sebelum menyetujui. Keputusan tidak dapat diubah.</span>
            </div>
        @else
            <div class="pay-card" style="text-align:center; color:#64748b;">
                Pembayaran ini berstatus: <strong>{{ $payment->status }}</strong>
            </div>
        @endif
    </div>

    <script>
        function playTing() {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();

            osc.type = 'sine';
            osc.frequency.setValueAtTime(1200, ctx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(1800, ctx.currentTime + 0.1);

            gain.gain.setValueAtTime(0.3, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.5);

            osc.connect(gain);
            gain.connect(ctx.destination);

            osc.start(ctx.currentTime);
            osc.stop(ctx.currentTime + 0.5);
        }

        async function sendConfirm(approved) {
            if (!confirm(approved ? 'Yakin setujui pembayaran ini?' : 'Yakin tolak pembayaran ini?')) {
                return;
            }

            if (approved) {
                playTing();
            }

            const url = "{{ route('payments.doConfirm', $payment->qr_token) }}";

            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                },
                body: JSON.stringify({ approved: approved }),
            });

            const data = await res.json();
            alert(data.message);
            location.reload();
        }
    </script>
</body>
</html>
