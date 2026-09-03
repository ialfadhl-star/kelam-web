<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'category', 'description', 'material', 'price',
        'gradient_from', 'gradient_to', 'is_featured', 'drop_label',
        'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'integer',
        'sort_order' => 'integer',
    ];

    public static array $categories = [
        'Hoodie & Sweater',
        'Outerwear',
        'T-Shirt',
        'Aksesoris',
        'Bottoms',
    ];

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function totalStock(): int
    {
        return (int) $this->variants->sum('stock');
    }

    public function inStock(): bool
    {
        return $this->totalStock() > 0;
    }

    /** Warna unik untuk varian produk ini. */
    public function colors()
    {
        return $this->variants->unique('color')->values();
    }

    /** Ukuran unik untuk varian produk ini. */
    public function sizes()
    {
        return $this->variants->pluck('size')->unique()->values();
    }

    public function formattedPrice(): string
    {
        return 'Rp' . number_format($this->price, 0, ',', '.');
    }

    public static function generateSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;
        while (static::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}
