<?php
// database/migrations/2024_01_01_000007_create_deliveries_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('recipient_name');
            $table->string('phone');
            $table->text('address');
            $table->string('city');
            $table->string('postal_code')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->enum('status', ['pending', 'assigned', 'picked_up', 'in_transit', 'delivered', 'failed'])->default('pending');
            $table->foreignId('driver_id')->nullable()->constrained('users');
            $table->timestamp('delivered_at')->nullable();
            $table->text('delivery_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};