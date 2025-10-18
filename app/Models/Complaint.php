<?php
// app/Models/Complaint.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Complaint extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'phone_no',
        'email',
        'voter_id_number',
        'permanent_address',
        'present_address',
        'is_same_address',
        'complaint_type',
        'police_station_id',
        'description',
        'status',
        'complaint_number',
        'attachments',
        'priority',
        'complainant_name',
        'complainant_contact',
        'incident_location',
        'incident_datetime',
        'created_by',
        'evidence'
    ];

    protected $casts = [
        'is_same_address' => 'boolean',
        'attachments' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'incident_datetime' => 'datetime',
    ];

    // Complaint type options
    const COMPLAINT_TYPES = [
        'lost_item' => 'Lost Item',
        'land_dispute' => 'Land Dispute',
        'harassment' => 'Harassment',
        'theft' => 'Theft',
        'fraud' => 'Fraud',
        'domestic_violence' => 'Domestic Violence',
        'public_nuisance' => 'Public Nuisance',
        'cyber_crime' => 'Cyber Crime',
        'other' => 'Other'
    ];

    // Police station options
    const POLICE_STATIONS = [
        'khulna_sadar' => 'Khulna Sadar Police Station',
        'sonadanga' => 'Sonadanga Police Station',
        'khalishpur' => 'Khalishpur Police Station',
        'daulatpur' => 'Daulatpur Police Station',
        'khan_jahan_ali' => 'Khan Jahan Ali Police Station',
        'terokhada' => 'Terokhada Police Station',
        'dumuria' => 'Dumuria Police Station',
        'phultala' => 'Phultala Police Station',
        'rupsha' => 'Rupsha Police Station',
        'botiaghata' => 'Botiaghata Police Station'
    ];

    // Status options
    const STATUSES = [
        'pending' => 'Pending',
        'under_investigation' => 'Under Investigation',
        'resolved' => 'Resolved',
        'rejected' => 'Rejected',
        'reopened' => 'Reopened'
    ];

    /**
     * Boot function to generate complaint number
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($complaint) {
            $complaint->complaint_number = 'CMS-' . date('Y') . '-' . str_pad(static::count() + 1, 4, '0', STR_PAD_LEFT);
        });
    }

    /**
     * Relationships
     */
    public function statusHistories()
    {
        return $this->hasMany(ComplaintStatusHistory::class);
    }

    public function attachments()
    {
        return $this->hasMany(ComplaintAttachment::class);
    }

    /**
     * Accessors
     */
    public function getComplaintTypeTextAttribute()
    {
        return self::COMPLAINT_TYPES[$this->complaint_type] ?? 'Unknown';
    }

    public function getPoliceStationTextAttribute()
    {
        return $this->policeStation ? $this->policeStation->name : 'Unknown';
    }

    public function policeStation()
    {
        return $this->belongsTo(PoliceStation::class, 'police_station_id');
    }

    public function getStatusTextAttribute()
    {
        return self::STATUSES[$this->status] ?? 'Unknown';
    }

    /**
     * Check if address is same
     */
    public function getIsSameAddressAttribute($value)
    {
        return (bool) $value;
    }
    // In Complaint.php model
public function assignments()
{
    return $this->hasMany(CaseAssignment::class);
}

public function caseAssignment()
{
    return $this->hasOne(CaseAssignment::class)->latest();
}

public function currentAssignment()
{
    return $this->hasOne(CaseAssignment::class)
                ->whereIn('status', ['assigned', 'in_progress'])
                ->latest();
}

    public function assignedInspector()
    {
        return $this->hasOneThrough(User::class, CaseAssignment::class, 'complaint_id', 'id', 'id', 'user_id')
                    ->whereIn('case_assignments.status', ['assigned', 'in_progress']);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
