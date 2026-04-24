<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminNotification extends Model
{
    protected $fillable = [
        'admin_id',
        'user_id',
        'title',
        'message',
        'type',
        'icon',
        'action_url',
        'is_read',
        'read_at',
        'send_to_all',
        'scheduled_at'
    ];

    protected $casts = [
        'action_url' => 'json',
        'is_read' => 'boolean',
        'send_to_all' => 'boolean',
        'read_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the admin who sent the notification
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Get the user who received the notification
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    /**
     * Scope to get unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope to get notifications for specific user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('user_id', $userId)
              ->orWhere('send_to_all', true);
        });
    }

    /**
     * Get icon based on type
     */
    public function getIconAttribute()
    {
        return match($this->type) {
            'success' => 'fas fa-check-circle',
            'warning' => 'fas fa-exclamation-circle',
            'danger' => 'fas fa-times-circle',
            'info' => 'fas fa-info-circle',
            default => 'fas fa-bell'
        };
    }

    /**
     * Get color based on type
     */
    public function getColorAttribute()
    {
        return match($this->type) {
            'success' => 'text-green-600',
            'warning' => 'text-yellow-600',
            'danger' => 'text-red-600',
            'info' => 'text-blue-600',
            default => 'text-gray-600'
        };
    }
}
