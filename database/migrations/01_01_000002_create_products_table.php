<?php
// database/migrations/2024_01_01_000002_create_products_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->integer('min_stock_level')->default(10);
            $table->string('unit_type')->default('piece'); // piece, kg, dozen
            $table->boolean('is_active')->default(true);
            $table->boolean('available_for_preorder')->default(true);
            $table->integer('preorder_hours_needed')->default(24);
            $table->string('image_url')->nullable();
            $table->json('allergens')->nullable();
            $table->timestamps();
            
            $table->index(['is_active', 'available_for_preorder']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};