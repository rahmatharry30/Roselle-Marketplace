<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function form(Request $request)
    {
        $cart = Cart::with('items.product')->where('user_id', $request->user()->id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong');
        }

        $total = $cart->items->sum(fn ($item) => $item->product->price * $item->quantity);

        return view('checkout.form', compact('cart', 'total'));
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'payment_method' => 'required|in:cod,transfer',
        ]);

        $cart = Cart::with('items.product')->where('user_id', $request->user()->id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong');
        }

        // Cek stok dulu sebelum diproses
        foreach ($cart->items as $item) {
            if ($item->quantity > $item->product->stock) {
                return back()->with('error', "Stok {$item->product->name} tidak mencukupi");
            }
        }

        $order = DB::transaction(function () use ($cart, $validated, $request) {
            $total = $cart->items->sum(fn ($item) => $item->product->price * $item->quantity);

            $order = $request->user()->orders()->create([
                'total_price' => $total,
                'payment_method' => $validated['payment_method'],
                'status' => 'belum_dibayar',
            ]);

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);

                $item->product->decrement('stock', $item->quantity);
            }

            $cart->items()->delete();

            return $order;
        });

        if ($validated['payment_method'] === 'transfer') {
            return redirect()->route('payments.generate', $order);
        }

        return redirect()->route('orders.show', $order)->with('success', 'Pesanan berhasil dibuat, silakan tunggu konfirmasi COD');
    }
}
