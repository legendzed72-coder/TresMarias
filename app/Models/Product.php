<?php
// app/Models/Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'description', 'price', 'cost_price',
        'stock_quantity', 'min_stock_level', 'unit_type', 'is_active',
        'available_for_preorder', 'preorder_hours_needed', 'image_url', 'allergens', 'is_archived'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_archived' => 'boolean',
        'available_for_preorder' => 'boolean',
        'allergens' => 'array',
    ];

    protected $appends = ['is_low_stock', 'profit_margin'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'product_ingredients')
            ->withPivot('quantity_needed')
            ->withTimestamps();
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function inventoryLogs(): HasMany
    {
        return $this->hasMany(InventoryLog::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->stock_quantity <= $this->min_stock_level;
    }

    public function getProfitMarginAttribute(): float
    {
        if (!$this->cost_price || $this->cost_price == 0) return 0;
        return (($this->price - $this->cost_price) / $this->price) * 100;
    }

    public function getAverageRatingAttribute(): float
    {
        $average = $this->ratings()->avg('rating');
        return $average ? round($average, 1) : 0;
    }

    public function getRatingCountAttribute(): int
    {
        return $this->ratings()->count();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_active', true)
                     ->where('stock_quantity', '>', 0);
    }

    public function archive()
    {
        return $this->update(['is_archived' => true]);
    }

    public function unarchive()
    {
        return $this->update(['is_archived' => false]);
    }

    public function deductStock(int $quantity, string $reason, int $userId): void
    {
        $this->decrement('stock_quantity', $quantity);
        
        InventoryLog::create([
            'product_id' => $this->id,
            'type' => 'out',
            'quantity' => $quantity,
            'running_stock' => $this->fresh()->stock_quantity,
            'reference_type' => 'order',
            'reason' => $reason,
            'user_id' => $userId,
        ]);

        // Deduct ingredients if configured
        foreach ($this->ingredients as $ingredient) {
            $neededQty = $ingredient->pivot->quantity_needed * $quantity;
            $ingredient->deductStock($neededQty, "Used in product: {$this->name}", $userId);
        }
    }

    public function addStock(int $quantity, string $reason, int $userId): void
    {
        $this->increment('stock_quantity', $quantity);
        
        InventoryLog::create([
            'product_id' => $this->id,
            'type' => 'in',
            'quantity' => $quantity,
            'running_stock' => $this->fresh()->stock_quantity,
            'reference_type' => 'adjustment',
            'reason' => $reason,
            'user_id' => $userId,
        ]);
    }
}