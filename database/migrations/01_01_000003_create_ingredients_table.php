<?php
// database/migrations/2024_01_01_000003_create_ingredients_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('unit_type'); // kg, g, l, ml, piece
            $table->decimal('stock_quantity', 10, 3)->default(0);
            $table->decimal('min_stock_level', 10, 3)->default(0);
            $table->decimal('cost_per_unit', 10, 2)->nullable();
            $table->string('supplier')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};