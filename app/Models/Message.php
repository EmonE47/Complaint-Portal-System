<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_id',
        'sender_id',
        'receiver_id',
        'message',
        'status',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    // Status options
    const STATUSES = [
        'sent' => 'Sent',
        'delivered' => 'Delivered',
        'read' => 'Read',
    ];

    /**
     * Relationships
     */
    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Accessors
     */
    public function getStatusTextAttribute()
    {
        return self::STATUSES[$this->status] ?? 'Unknown';
    }

    /**
     * Scopes
     */
    public function scopeUnread($query)
    {
        return $query->where('status', '!=', 'read');
    }

    public function scopeBetweenUsers($query, $userId1, $userId2)
    {
        return $query->where(function ($q) use ($userId1, $userId2) {
            $q->where(function ($subQ) use ($userId1, $userId2) {
                $subQ->where('sender_id', $userId1)->where('receiver_id', $userId2);
            })->orWhere(function ($subQ) use ($userId1, $userId2) {
                $subQ->where('sender_id', $userId2)->where('receiver_id', $userId1);
            });
        });
    }

    /**
     * Mark message as read
     */
    public function markAsRead()
    {
        if ($this->status !== 'read') {
            $this->update([
                'status' => 'read',
                'read_at' => now(),
            ]);
        }
    }

    /**
     * Mark message as delivered
     */
    public function markAsDelivered()
    {
        if ($this->status === 'sent') {
            $this->update(['status' => 'delivered']);
        }
    }
}
