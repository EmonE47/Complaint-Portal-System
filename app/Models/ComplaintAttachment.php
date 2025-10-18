<?php
// app/Models/ComplaintAttachment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'original_name'
    ];

    /**
     * Relationships
     */
    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }
}