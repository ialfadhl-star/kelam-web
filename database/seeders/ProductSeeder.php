<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Palet warna KELAM -> hex untuk swatch
        $hex = [
            'Onyx Black' => '#0A0A0A',
            'Charcoal'   => '#2B2B2B',
            'Off White'  => '#EDEAE5',
            'Rust'       => '#B4552D',
            'Olive'      => '#6B7042',
        ];

        // Gradient placeholder foto (gelap/dramatis) per produk
        $products = [
            [
                'name' => 'KELAM Oversized Hoodie',
                'category' => 'Hoodie & Sweater',
                'price' => 385000,
                'material' => 'Fleece cotton 380 gsm, potongan oversized dengan drop shoulder.',
                'description' => 'Hoodie berat dengan siluet oversized. Dibuat dari fleece cotton tebal yang jatuh sempurna, bukan sekadar longgar. Untuk yang jalan sendiri.',
                'colors' => ['Onyx Black', 'Charcoal'],
                'sizes' => ['S', 'M', 'L', 'XL'],
                'featured' => true,
                'gradient' => ['#2B2B2B', '#0A0A0A'],
                'stock' => 12,
            ],
            [
                'name' => 'KELAM Varsity Jacket',
                'category' => 'Outerwear',
                'price' => 650000,
                'material' => 'Body wool-blend melton, lengan kulit sintetis, lining satin.',
                'description' => 'Varsity jacket dengan bahan melton tebal dan detail rib knit. Statement piece yang tidak butuh penjelasan.',
                'colors' => ['Charcoal'],
                'sizes' => ['S', 'M', 'L', 'XL'],
                'featured' => true,
                'gradient' => ['#3A3A3A', '#111111'],
                'stock' => 6,
            ],
            [
                'name' => 'KELAM Essential Tee',
                'category' => 'T-Shirt',
                'price' => 195000,
                'material' => 'Cotton combed 24s, jahitan double-stitch, garis bahu drop.',
                'description' => 'Kaus dasar yang bukan basi. Bahan tebal dengan potongan boxy modern. Grafik minimal, logo kecil.',
                'colors' => ['Off White', 'Onyx Black'],
                'sizes' => ['S', 'M', 'L', 'XL'],
                'featured' => true,
                'gradient' => ['#262626', '#0A0A0A'],
                'stock' => 20,
            ],
            [
                'name' => 'KELAM Logo Cap',
                'category' => 'Aksesoris',
                'price' => 165000,
                'material' => 'Cotton twill, strap metal buckle, bordir logo timbul.',
                'description' => 'Topi 6-panel dengan bordir logo minimal. Aksen rust untuk yang mau sedikit warna tanpa berisik.',
                'colors' => ['Rust', 'Onyx Black'],
                'sizes' => ['All Size'],
                'featured' => false,
                'gradient' => ['#4A2A1A', '#141414'],
                'stock' => 15,
            ],
            [
                'name' => 'KELAM Cargo Pants',
                'category' => 'Bottoms',
                'price' => 425000,
                'material' => 'Ripstop cotton, enam kantong fungsional, adjustable hem.',
                'description' => 'Cargo pants dengan potongan relaxed dan kantong yang benar-benar dipakai. Bukan dekorasi.',
                'colors' => ['Olive', 'Onyx Black'],
                'sizes' => ['S', 'M', 'L', 'XL'],
                'featured' => true,
                'gradient' => ['#33351F', '#0F0F0F'],
                'stock' => 10,
            ],
            [
                'name' => 'KELAM Bomber Jacket',
                'category' => 'Outerwear',
                'price' => 580000,
                'material' => 'Nylon MA-1, isian ringan, rib knit collar & cuff.',
                'description' => 'Bomber klasik yang dipangkas dari hal-hal berlebihan. Hitam pekat, siluet bersih.',
                'colors' => ['Onyx Black'],
                'sizes' => ['S', 'M', 'L', 'XL'],
                'featured' => false,
                'gradient' => ['#242424', '#050505'],
                'stock' => 7,
            ],
            [
                'name' => 'KELAM Heavyweight Long Sleeve',
                'category' => 'T-Shirt',
                'price' => 245000,
                'material' => 'Cotton 260 gsm, potongan reguler, cuff rib.',
                'description' => 'Long sleeve tebal untuk layering atau dipakai sendiri. Berat yang terasa premium di tangan.',
                'colors' => ['Charcoal'],
                'sizes' => ['S', 'M', 'L', 'XL'],
                'featured' => false,
                'gradient' => ['#2E2E2E', '#0C0C0C'],
                'stock' => 14,
            ],
            [
                'name' => 'KELAM Ribbed Beanie',
                'category' => 'Aksesoris',
                'price' => 125000,
                'material' => 'Acrylic-wool rib knit, double layer cuff.',
                'description' => 'Beanie rib rajut rapat, hangat, dan tahan bentuk. Detail label kecil di lipatan.',
                'colors' => ['Onyx Black', 'Olive'],
                'sizes' => ['All Size'],
                'featured' => false,
                'gradient' => ['#1E2213', '#0A0A0A'],
                'stock' => 18,
            ],
        ];

        foreach ($products as $i => $data) {
            $product = Product::create([
                'name' => $data['name'],
                'slug' => Product::generateSlug($data['name']),
                'category' => $data['category'],
                'description' => $data['description'],
                'material' => $data['material'],
                'price' => $data['price'],
                'gradient_from' => $data['gradient'][0],
                'gradient_to' => $data['gradient'][1],
                'is_featured' => $data['featured'],
                'is_active' => true,
                'sort_order' => $i,
            ]);

            foreach ($data['colors'] as $color) {
                foreach ($data['sizes'] as $size) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'color' => $color,
                        'color_hex' => $hex[$color] ?? '#0A0A0A',
                        'size' => $size,
                        'stock' => $data['stock'],
                    ]);
                }
            }
        }
    }
}
