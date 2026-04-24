<?php
// app/Models/Ingredient.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'unit_type', 'stock_quantity', 'min_stock_level',
        'cost_per_unit', 'supplier'
    ];

    protected $casts = [
        'stock_quantity' => 'decimal:3',
        'min_stock_level' => 'decimal:3',
        'cost_per_unit' => 'decimal:2',
    ];

    protected $appends = ['is_low_stock'];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_ingredients')
            ->withPivot('quantity_needed')
            ->withTimestamps();
    }

    public function inventoryLogs(): HasMany
    {
        return $this->hasMany(InventoryLog::class);
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->stock_quantity <= $this->min_stock_level;
    }

    public function deductStock(float $quantity, string $reason, int $userId): void
    {
        $this->decrement('stock_quantity', $quantity);
        
        InventoryLog::create([
            'ingredient_id' => $this->id,
            'type' => 'out',
            'quantity' => $quantity,
            'running_stock' => $this->fresh()->stock_quantity,
            'reference_type' => 'production',
            'reason' => $reason,
            'user_id' => $userId,
        ]);
    }

    public function addStock(float $quantity, string $reason, int $userId): void
    {
        $this->increment('stock_quantity', $quantity);
        
        InventoryLog::create([
            'ingredient_id' => $this->id,
            'type' => 'in',
            'quantity' => $quantity,
            'running_stock' => $this->fresh()->stock_quantity,
            'reference_type' => 'purchase',
            'reason' => $reason,
            'user_id' => $userId,
        ]);
    }
}