<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Inspector extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'number',
        'nid_number',
        'rank',
        'police_station_id',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * Relationships
     */
    public function policeStation()
    {
        return $this->belongsTo(PoliceStation::class);
    }
}
