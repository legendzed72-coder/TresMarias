<?php
// database/migrations/2024_01_01_000005_create_orders_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained(); // null for walk-in POS orders
            $table->string('order_number')->unique();
            $table->enum('type', ['pos', 'preorder', 'order'])->default('preorder');
            $table->enum('status', [
                'pending', 'confirmed', 'preparing', 
                'ready', 'out_for_delivery', 'completed', 
                'cancelled'
            ])->default('pending');
            
            // For POS orders
            $table->enum('payment_status', ['pending', 'paid', 'refunded'])->default('pending');
            $table->enum('payment_method', ['cod', 'online', 'cash', 'card', 'gcash', 'maya'])->nullable();
            
            // For preorders
            $table->enum('fulfillment_type', ['pickup', 'delivery'])->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->text('special_instructions')->nullable();
            
            // Financial
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            
            // POS specific
            $table->foreignId('cashier_id')->nullable()->constrained('users');
            $table->string('pos_device_id')->nullable();
            $table->boolean('is_offline_synced')->default(false);
            
            $table->timestamps();
            
            $table->index(['status', 'scheduled_at']);
            $table->index(['type', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};