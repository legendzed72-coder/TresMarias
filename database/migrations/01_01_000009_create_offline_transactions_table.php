<?php
// database/migrations/2024_01_01_000009_create_offline_transactions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offline_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('local_id'); // ID generated on client side
            $table->foreignId('order_id')->nullable()->constrained();
            $table->json('payload'); // Complete order data
            $table->enum('status', ['pending_sync', 'synced', 'failed'])->default('pending_sync');
            $table->text('sync_error')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();
            
            $table->unique('local_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offline_transactions');
    }
};