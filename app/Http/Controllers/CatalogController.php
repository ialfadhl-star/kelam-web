<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('is_active', true)->with('variants');

        // Filter kategori
        $category = $request->query('kategori');
        if ($category && in_array($category, Product::$categories, true)) {
            $query->where('category', $category);
        }

        // Filter warna & ukuran (UPGRADE dari baseline Paket Starter)
        $color = $request->query('warna');
        if ($color) {
            $query->whereHas('variants', fn ($q) => $q->where('color', $color));
        }

        $size = $request->query('ukuran');
        if ($size) {
            $query->whereHas('variants', fn ($q) => $q->where('size', $size));
        }

        // Urutkan
        $sort = $request->query('urut', 'default');
        match ($sort) {
            'termurah'  => $query->orderBy('price', 'asc'),
            'termahal'  => $query->orderBy('price', 'desc'),
            'terbaru'   => $query->orderByDesc('created_at'),
            default     => $query->orderBy('sort_order'),
        };

        $products = $query->get();

        $categories = Product::$categories;
        $colors = ProductVariant::query()->distinct()->orderBy('color')->pluck('color');
        $sizes = ProductVariant::query()->distinct()->pluck('size')
            ->sortBy(fn ($s) => array_search($s, ['S', 'M', 'L', 'XL', 'All Size']))
            ->values();

        return view('catalog.index', compact(
            'products', 'categories', 'colors', 'sizes',
            'category', 'color', 'size', 'sort'
        ));
    }

    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);
        $product->load('variants');

        $related = Product::where('is_active', true)
            ->where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->with('variants')
            ->take(3)
            ->get();

        return view('catalog.show', compact('product', 'related'));
    }
}
