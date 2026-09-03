<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number', 'customer_name', 'customer_phone', 'customer_email',
        'shipping_address', 'shipping_city', 'shipping_postal_code', 'notes',
        'payment_method', 'subtotal', 'shipping_cost', 'total', 'status',
        'courier', 'tracking_number',
    ];

    protected $casts = [
        'subtotal' => 'integer',
        'shipping_cost' => 'integer',
        'total' => 'integer',
    ];

    public static array $statuses = [
        'menunggu_pembayaran' => 'Menunggu Pembayaran',
        'diproses'            => 'Diproses',
        'dikirim'             => 'Dikirim',
        'selesai'             => 'Selesai',
        'dibatalkan'          => 'Dibatalkan',
    ];

    public static array $paymentMethods = [
        'transfer_bank' => 'Transfer Bank',
        'qris'          => 'QRIS',
        'cod'           => 'COD (Bayar di Tempat)',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getRouteKeyName(): string
    {
        return 'order_number';
    }

    public function statusLabel(): string
    {
        return static::$statuses[$this->status] ?? $this->status;
    }

    public function paymentMethodLabel(): string
    {
        return static::$paymentMethods[$this->payment_method] ?? $this->payment_method;
    }

    public function formattedTotal(): string
    {
        return 'Rp' . number_format($this->total, 0, ',', '.');
    }

    public static function generateNumber(): string
    {
        return 'KLM-' . now()->format('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
    }
}
