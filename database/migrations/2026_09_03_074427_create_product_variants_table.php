<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('color');      // Onyx Black, Charcoal, dll
            $table->string('color_hex')->default('#0A0A0A');
            $table->string('size');       // S, M, L, XL, All Size
            $table->integer('stock')->default(0);
            $table->timestamps();

            $table->unique(['product_id', 'color', 'size']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
