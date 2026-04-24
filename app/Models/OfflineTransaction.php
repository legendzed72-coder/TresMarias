<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfflineTransaction extends Model
{
    use HasFactory;

    protected $fillable = ['local_id', 'data', 'synced_at'];

    protected $casts = [
        'data' => 'array',
        'synced_at' => 'datetime',
    ];
}