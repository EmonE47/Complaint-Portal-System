<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoliceStation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'phone',
        'email',
        'address',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function inspectors()
    {
        return $this->hasMany(Inspector::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'police_station_id');
    }
}
