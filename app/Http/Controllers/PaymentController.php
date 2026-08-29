<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PaymentController extends Controller
{
    public function generate(Order $order)
    {
        $payment = Payment::create([
            'order_id' => $order->id,
            'qr_token' => Str::random(40),
            'status' => 'menunggu',
            'expires_at' => now()->addMinutes(10),
        ]);

        $payment->load('order');

        $confirmUrl = route('payments.confirm', $payment->qr_token);

        $qrImage = QrCode::size(212)->generate($confirmUrl);

        return view('payments.show', [
            'payment' => $payment,
            'qrImage' => $qrImage,
        ]);
    }

    public function confirmPage(string $token)
    {
        $payment = Payment::with('order.user')->where('qr_token', $token)->firstOrFail();

        if ($payment->expires_at && $payment->expires_at->isPast()) {
            $payment->update(['status' => 'kadaluarsa']);
        }

        return view('payments.confirm', ['payment' => $payment]);
    }

    public function confirm(Request $request, string $token)
    {
        $validated = $request->validate([
            'approved' => 'required|boolean',
        ]);

        $payment = Payment::where('qr_token', $token)->firstOrFail();

        if ($payment->expires_at && $payment->expires_at->isPast()) {
            return response()->json(['message' => 'QR sudah kadaluarsa'], 410);
        }

        $payment->update([
            'status' => $validated['approved'] ? 'disetujui' : 'ditolak',
        ]);

        if ($validated['approved']) {
            $payment->order->update(['status' => 'diproses']);
        }

        return response()->json(['message' => 'Konfirmasi berhasil', 'payment' => $payment]);
    }

    public function checkStatus(Payment $payment)
    {
        return response()->json(['status' => $payment->status]);
    }
}
