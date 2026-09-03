<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(protected CartService $cart) {}

    public function index()
    {
        return view('cart.index', [
            'items' => $this->cart->all(),
            'subtotal' => $this->cart->subtotal(),
        ]);
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $variant = ProductVariant::with('product')->findOrFail($data['variant_id']);

        if ($variant->stock < 1) {
            return $this->respond($request, false, 'Varian ini sedang habis.');
        }

        $this->cart->add($variant, $data['quantity'] ?? 1);

        return $this->respond($request, true, 'Ditambahkan ke keranjang.');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'variant_id' => ['required', 'integer'],
            'quantity' => ['required', 'integer', 'min:0', 'max:99'],
        ]);

        $this->cart->update($data['variant_id'], $data['quantity']);

        return $this->respond($request, true, 'Keranjang diperbarui.');
    }

    public function remove(Request $request)
    {
        $data = $request->validate([
            'variant_id' => ['required', 'integer'],
        ]);

        $this->cart->remove($data['variant_id']);

        return $this->respond($request, true, 'Item dihapus.');
    }

    protected function respond(Request $request, bool $ok, string $message)
    {
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'ok' => $ok,
                'message' => $message,
                'count' => $this->cart->count(),
                'subtotal' => $this->cart->subtotal(),
                'subtotal_formatted' => 'Rp' . number_format($this->cart->subtotal(), 0, ',', '.'),
            ]);
        }

        return back()->with($ok ? 'success' : 'error', $message);
    }
}
