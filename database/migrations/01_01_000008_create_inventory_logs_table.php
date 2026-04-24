<?php
// database/migrations/2024_01_01_000008_create_inventory_logs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained();
            $table->foreignId('ingredient_id')->nullable()->constrained();
            $table->enum('type', ['in', 'out', 'adjustment', 'waste', 'return']);
            $table->decimal('quantity', 10, 3);
            $table->decimal('running_stock', 10, 3)->default(0);
            $table->string('reference_type')->default('adjustment'); // order, adjustment, purchase
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('reason');
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
            
            $table->index(['product_id', 'created_at']);
            $table->index(['ingredient_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_logs');
    }
};