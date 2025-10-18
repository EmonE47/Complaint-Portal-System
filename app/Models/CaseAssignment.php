<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class CaseAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_id',
        'user_id',
        'assigned_by',
        'assignment_notes',
        'status',
        'assigned_at',
        'completed_at',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Status options
    const STATUSES = [
        'assigned' => 'Assigned',
        'in_progress' => 'In Progress',
        'completed' => 'Completed',
        'transferred' => 'Transferred',
    ];

    /**
     * Relationships
     */
    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
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
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['assigned', 'in_progress']);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
