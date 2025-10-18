<?php
// app/Models/ComplaintStatusHistory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_id',
        'status',
        'remarks',
        'updated_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relationships
     */
    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Accessors
     */
    public function getStatusTextAttribute()
    {
        return Complaint::STATUSES[$this->status] ?? 'Unknown';
    }
}