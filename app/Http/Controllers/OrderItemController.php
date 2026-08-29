<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderItemController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with('items.product')
            ->where('user_id', $request->user()->id)
            ->get();

        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'payment_method' => 'required|in:cod,transfer',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $order = DB::transaction(function () use ($validated, $request) {
            $total = 0;
            $itemsData = [];

            foreach ($validated['items'] as $item) {
                $product = \App\Models\Product::findOrFail($item['product_id']);
                $subtotal = $product->price * $item['quantity'];
                $total += $subtotal;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ];

                $product->decrement('stock', $item['quantity']);
            }

            $order = Order::create([
                'user_id' => $request->user()->id,
                'total_price' => $total,
                'payment_method' => $validated['payment_method'],
                'status' => 'belum_dibayar',
            ]);

            foreach ($itemsData as $data) {
                $order->items()->create($data);
            }

            return $order;
        });

        return response()->json($order->load('items.product'), 201);
    }

    public function show(Order $order)
    {
        return response()->json($order->load('items.product'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:belum_dibayar,diproses,dikemas,dalam_perjalanan,selesai',
        ]);

        $order->update($validated);

        return response()->json($order);
    }
}
