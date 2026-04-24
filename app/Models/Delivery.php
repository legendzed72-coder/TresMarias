<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'recipient_name', 'phone', 'address', 'city', 'postal_code',
        'latitude', 'longitude', 'status', 'driver_id', 'delivered_at', 'delivery_notes',
    ];

    protected $casts = [
        'latitude'     => 'decimal:8',
        'longitude'    => 'decimal:8',
        'delivered_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
}
