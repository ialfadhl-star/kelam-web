<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('variants')->orderBy('sort_order')->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $product = new Product([
            'gradient_from' => '#2B2B2B',
            'gradient_to' => '#0A0A0A',
            'is_active' => true,
        ]);
        return view('admin.products.form', [
            'product' => $product,
            'categories' => Product::$categories,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        DB::transaction(function () use ($data, $request) {
            $product = Product::create([
                'name' => $data['name'],
                'slug' => Product::generateSlug($data['name']),
                'category' => $data['category'],
                'description' => $data['description'] ?? null,
                'material' => $data['material'] ?? null,
                'price' => $data['price'],
                'gradient_from' => $data['gradient_from'],
                'gradient_to' => $data['gradient_to'],
                'is_featured' => $request->boolean('is_featured'),
                'is_active' => $request->boolean('is_active'),
                'sort_order' => $data['sort_order'] ?? 0,
            ]);

            $this->syncVariants($product, $request);
        });

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $product->load('variants');
        return view('admin.products.form', [
            'product' => $product,
            'categories' => Product::$categories,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateData($request);

        DB::transaction(function () use ($data, $request, $product) {
            $product->update([
                'name' => $data['name'],
                'category' => $data['category'],
                'description' => $data['description'] ?? null,
                'material' => $data['material'] ?? null,
                'price' => $data['price'],
                'gradient_from' => $data['gradient_from'],
                'gradient_to' => $data['gradient_to'],
                'is_featured' => $request->boolean('is_featured'),
                'is_active' => $request->boolean('is_active'),
                'sort_order' => $data['sort_order'] ?? 0,
            ]);

            $this->syncVariants($product, $request);
        });

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')
            ->with('success', 'Produk dihapus.');
    }

    protected function validateData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'category' => ['required', 'in:' . implode(',', Product::$categories)],
            'description' => ['nullable', 'string', 'max:2000'],
            'material' => ['nullable', 'string', 'max:500'],
            'price' => ['required', 'integer', 'min:0'],
            'gradient_from' => ['required', 'string', 'max:9'],
            'gradient_to' => ['required', 'string', 'max:9'],
            'sort_order' => ['nullable', 'integer'],
            'variants' => ['array'],
            'variants.*.color' => ['nullable', 'string', 'max:60'],
            'variants.*.color_hex' => ['nullable', 'string', 'max:9'],
            'variants.*.size' => ['nullable', 'string', 'max:20'],
            'variants.*.stock' => ['nullable', 'integer', 'min:0'],
        ]);
    }

    /**
     * Replace-all sync varian: hapus yang lama, tulis yang baru dari form.
     * Baris varian yang kosong (tanpa color/size) diabaikan.
     */
    protected function syncVariants(Product $product, Request $request): void
    {
        $rows = collect($request->input('variants', []))
            ->filter(fn ($v) => filled($v['color'] ?? null) && filled($v['size'] ?? null));

        $product->variants()->delete();

        $seen = [];
        foreach ($rows as $v) {
            $key = $v['color'] . '|' . $v['size'];
            if (isset($seen[$key])) {
                continue; // hindari duplikat color+size
            }
            $seen[$key] = true;

            ProductVariant::create([
                'product_id' => $product->id,
                'color' => $v['color'],
                'color_hex' => $v['color_hex'] ?? '#0A0A0A',
                'size' => $v['size'],
                'stock' => (int) ($v['stock'] ?? 0),
            ]);
        }
    }
}
