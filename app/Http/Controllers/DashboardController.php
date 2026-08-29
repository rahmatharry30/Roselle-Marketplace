<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->isSeller()) {
            return $this->sellerDashboard($request);
        }

        return $this->buyerDashboard($request);
    }

    private function sellerDashboard(Request $request)
    {
        $userId = $request->user()->id;

        $products = Product::where('user_id', $userId)->get();

        $totalProduk = $products->count();
        $totalStok = $products->sum('stock');

        $soldItems = OrderItem::whereHas('product', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->whereHas('order', function ($query) {
                $query->whereIn('status', ['diproses', 'dikemas', 'dalam_perjalanan', 'selesai']);
            })
            ->get();

        $totalTerjual = $soldItems->sum('quantity');
        $totalPendapatan = $soldItems->sum(fn ($item) => $item->price * $item->quantity);

        $pendapatanBulanIni = OrderItem::whereHas('product', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->whereHas('order', function ($query) {
                $query->whereIn('status', ['diproses', 'dikemas', 'dalam_perjalanan', 'selesai'])
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
            })
            ->get()
            ->sum(fn ($item) => $item->price * $item->quantity);

        $orderMasuk = Order::whereHas('items.product', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->where('status', 'belum_dibayar')
            ->count();

        $produkTerlaris = OrderItem::whereHas('product', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->selectRaw('product_id, SUM(quantity) as total_qty')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->with('product')
            ->take(5)
            ->get();

        return view('dashboard-seller', compact(
            'totalProduk',
            'totalStok',
            'totalTerjual',
            'totalPendapatan',
            'pendapatanBulanIni',
            'orderMasuk',
            'produkTerlaris'
        ));
    }

    private function buyerDashboard(Request $request)
    {
        $userId = $request->user()->id;

        $baseQuery = Order::where('user_id', $userId);

        $totalDiproses = (clone $baseQuery)->where('status', 'diproses')->count();
        $totalDikemas = (clone $baseQuery)->where('status', 'dikemas')->count();
        $totalDalamPerjalanan = (clone $baseQuery)->where('status', 'dalam_perjalanan')->count();
        $totalSelesai = (clone $baseQuery)->where('status', 'selesai')->count();
        $totalBelumDibayar = (clone $baseQuery)->where('status', 'belum_dibayar')->count();
        $totalDibatalkan = (clone $baseQuery)->where('status', 'dibatalkan')->count();

        $totalSudahDirating = Review::where('user_id', $userId)->count();

        return view('dashboard-buyer', compact(
            'totalBelumDibayar',
            'totalDiproses',
            'totalDikemas',
            'totalDalamPerjalanan',
            'totalSelesai',
            'totalDibatalkan',
            'totalSudahDirating'
        ));
    }
}
