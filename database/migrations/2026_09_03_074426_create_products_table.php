<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category');            // Hoodie & Sweater, Outerwear, T-Shirt, Aksesoris, Bottoms
            $table->text('description')->nullable();
            $table->string('material')->nullable(); // deskripsi bahan
            $table->integer('price');               // rupiah, integer (tanpa desimal)
            $table->string('gradient_from')->default('#1C1C1C'); // placeholder foto: gradient gelap
            $table->string('gradient_to')->default('#0A0A0A');
            $table->boolean('is_featured')->default(false);
            $table->string('drop_label')->nullable(); // opsional label "drop" (tidak dipakai di baseline)
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
