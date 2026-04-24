<?php
// app/Models/Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'order_number', 'type', 'status', 'payment_status',
        'payment_method', 'fulfillment_type', 'scheduled_at', 'special_instructions',
        'subtotal', 'tax', 'delivery_fee', 'discount', 'total',
        'cashier_id', 'pos_device_id', 'is_offline_synced'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'is_offline_synced' => 'boolean',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . strtoupper(uniqid());
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function delivery(): HasOne
    {
        return $this->hasOne(Delivery::class);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopePos($query)
    {
        return $query->where('type', 'pos');
    }

    public function scopePreorder($query)
    {
        return $query->where('type', 'preorder');
    }

    public function processInventoryDeduction(int $userId): void
    {
        foreach ($this->items as $item) {
            $item->product->deductStock(
                $item->quantity, 
                "Order #{$this->order_number}", 
                $userId
            );
        }
    }

    public function calculateTotals(): void
    {
        $this->subtotal = $this->items->sum('subtotal');
        $this->tax = $this->subtotal * 0.12; // 12% tax example
        $this->total = $this->subtotal + $this->tax + $this->delivery_fee - $this->discount;
        $this->save();
    }
}