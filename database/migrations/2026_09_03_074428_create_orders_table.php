<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->text('shipping_address');
            $table->string('shipping_city');
            $table->string('shipping_postal_code')->nullable();
            $table->text('notes')->nullable();
            $table->string('payment_method');   // transfer_bank, qris, cod
            $table->integer('subtotal');
            $table->integer('shipping_cost')->default(0);
            $table->integer('total');
            $table->string('status')->default('menunggu_pembayaran'); // menunggu_pembayaran, diproses, dikirim, selesai, dibatalkan
            $table->string('courier')->nullable();
            $table->string('tracking_number')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
