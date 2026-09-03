<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::withCount('items')->orderByDesc('created_at');

        $status = $request->query('status');
        if ($status && array_key_exists($status, Order::$statuses)) {
            $query->where('status', $status);
        }

        $orders = $query->get();

        return view('admin.orders.index', [
            'orders' => $orders,
            'statuses' => Order::$statuses,
            'currentStatus' => $status,
        ]);
    }

    public function show(Order $order)
    {
        $order->load('items');
        return view('admin.orders.show', [
            'order' => $order,
            'statuses' => Order::$statuses,
        ]);
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', 'in:' . implode(',', array_keys(Order::$statuses))],
            'courier' => ['nullable', 'string', 'max:60'],
            'tracking_number' => ['nullable', 'string', 'max:60'],
        ]);

        $order->update($data);

        return back()->with('success', 'Pesanan diperbarui.');
    }
}
