<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $hero = Product::where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->with('variants')
            ->first();

        $featured = Product::where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->with('variants')
            ->take(4)
            ->get();

        // Kalau produk unggulan tidak cukup, lengkapi dengan produk lain
        if ($featured->count() < 4) {
            $extra = Product::where('is_active', true)
                ->whereNotIn('id', $featured->pluck('id'))
                ->orderBy('sort_order')
                ->with('variants')
                ->take(4 - $featured->count())
                ->get();
            $featured = $featured->concat($extra);
        }

        $categories = Product::$categories;

        // Testimoni PLACEHOLDER — wajib diganti dengan yang asli sebelum go-live.
        $testimonials = [
            [
                'name' => 'Raka W.',
                'city' => 'Jakarta',
                'rating' => 5,
                'text' => 'Bahan hoodie-nya tebal beneran, jatuhnya rapi. Jarang nemu yang oversized tapi nggak kelihatan kebesaran.',
            ],
            [
                'name' => 'Dimas A.',
                'city' => 'Bandung',
                'rating' => 5,
                'text' => 'Cargo pants-nya juara. Kantongnya fungsional, jahitan kuat. Worth the price.',
            ],
            [
                'name' => 'Sena P.',
                'city' => 'Surabaya',
                'rating' => 4,
                'text' => 'Detail kecil kayak label di beanie bikin beda. Packaging juga rapi pas sampai.',
            ],
        ];

        return view('home', compact('hero', 'featured', 'categories', 'testimonials'));
    }
}
