<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\Setting;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct(protected CartService $cart) {}

    public function index()
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang kamu masih kosong.');
        }

        $subtotal = $this->cart->subtotal();
        $shippingCost = $this->shippingCost($subtotal);

        return view('checkout.index', [
            'items' => $this->cart->all(),
            'subtotal' => $subtotal,
            'shippingCost' => $shippingCost,
            'total' => $subtotal + $shippingCost,
            'paymentMethods' => Order::$paymentMethods,
        ]);
    }

    public function store(Request $request)
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang kamu masih kosong.');
        }

        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'customer_email' => ['nullable', 'email', 'max:120'],
            'shipping_address' => ['required', 'string', 'max:500'],
            'shipping_city' => ['required', 'string', 'max:80'],
            'shipping_postal_code' => ['nullable', 'string', 'max:10'],
            'notes' => ['nullable', 'string', 'max:500'],
            'payment_method' => ['required', 'in:' . implode(',', array_keys(Order::$paymentMethods))],
        ]);

        $cartItems = $this->cart->all();
        $subtotal = $this->cart->subtotal();
        $shippingCost = $this->shippingCost($subtotal);

        try {
            $order = DB::transaction(function () use ($data, $cartItems, $subtotal, $shippingCost) {
                $order = Order::create([
                    'order_number' => Order::generateNumber(),
                    'customer_name' => $data['customer_name'],
                    'customer_phone' => $data['customer_phone'],
                    'customer_email' => $data['customer_email'] ?? null,
                    'shipping_address' => $data['shipping_address'],
                    'shipping_city' => $data['shipping_city'],
                    'shipping_postal_code' => $data['shipping_postal_code'] ?? null,
                    'notes' => $data['notes'] ?? null,
                    'payment_method' => $data['payment_method'],
                    'subtotal' => $subtotal,
                    'shipping_cost' => $shippingCost,
                    'total' => $subtotal + $shippingCost,
                    'status' => 'menunggu_pembayaran',
                ]);

                foreach ($cartItems as $item) {
                    // Kunci baris varian untuk cegah oversell saat konkuren
                    $variant = ProductVariant::lockForUpdate()->find($item['variant_id']);
                    $qty = $item['quantity'];

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'],
                        'product_name' => $item['product_name'],
                        'color' => $item['color'],
                        'size' => $item['size'],
                        'price' => $item['price'],
                        'quantity' => $qty,
                        'line_total' => $item['price'] * $qty,
                    ]);

                    if ($variant) {
                        $variant->decrement('stock', min($qty, $variant->stock));
                    }
                }

                return $order;
            });
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Terjadi kesalahan saat memproses pesanan. Coba lagi.')
                ->withInput();
        }

        $this->cart->clear();

        return redirect()->route('order.confirmation', $order->order_number);
    }

    public function confirmation(Order $order)
    {
        $order->load('items');

        $bank = [
            'name' => Setting::get('bank_name', 'Bank BCA'),
            'account' => Setting::get('bank_account', '1234567890'),
            'holder' => Setting::get('bank_holder', 'PT Kelam Indonesia'),
        ];

        return view('checkout.confirmation', compact('order', 'bank'));
    }

    protected function shippingCost(int $subtotal): int
    {
        $freeMin = (int) Setting::get('free_shipping_min', '500000');
        $cost = (int) Setting::get('shipping_cost', '25000');

        if ($freeMin > 0 && $subtotal >= $freeMin) {
            return 0;
        }
        return $cost;
    }
}
