<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403, 'Ini bukan pesanan Anda.');
        abort_if($order->status !== 'selesai', 403, 'Pesanan harus selesai dulu sebelum bisa direview.');

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $alreadyReviewed = Review::where('product_id', $validated['product_id'])
            ->where('user_id', auth()->id())
            ->where('order_id', $order->id)
            ->exists();

        if ($alreadyReviewed) {
            return back()->with('error', 'Anda sudah mereview produk ini untuk pesanan ini.');
        }

        Review::create([
            'product_id' => $validated['product_id'],
            'user_id' => auth()->id(),
            'order_id' => $order->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return back()->with('success', 'Review berhasil dikirim, terima kasih!');
    }
}
