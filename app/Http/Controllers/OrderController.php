<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with('items.product')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403, 'Ini bukan pesanan Anda.');

        $order->load('items.product');
        return view('orders.show', compact('order'));
    }

    public function cancel(Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403, 'Ini bukan pesanan Anda.');
        abort_if($order->status !== 'belum_dibayar', 403, 'Pesanan ini sudah diproses, tidak bisa dibatalkan.');

        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }

            $order->update(['status' => 'dibatalkan']);
        });

        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibatalkan, stok telah dikembalikan');
    }

    public function sellerIndex(Request $request)
    {
        $orders = Order::with('items.product', 'user')
            ->whereHas('items.product', function ($query) use ($request) {
                $query->where('user_id', $request->user()->id);
            })
            ->latest()
            ->get();

        return view('orders.seller-index', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:belum_dibayar,diproses,dikemas,dalam_perjalanan,selesai',
        ]);

        $order->update($validated);

        return back()->with('success', 'Status pesanan diperbarui');
    }
}
