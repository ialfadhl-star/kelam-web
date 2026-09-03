<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'products' => Product::count(),
            'orders' => Order::count(),
            'pending' => Order::where('status', 'menunggu_pembayaran')->count(),
            'revenue' => Order::whereIn('status', ['diproses', 'dikirim', 'selesai'])->sum('total'),
        ];

        $recentOrders = Order::orderByDesc('created_at')->take(8)->get();

        $lowStock = Product::with('variants')->get()
            ->filter(fn ($p) => $p->totalStock() <= 5)
            ->values();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'lowStock'));
    }
}
