<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bed extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ward_id',
        'bed_number',
        'bed_label',
        'bed_type',
        'status',
        'features',
        'has_oxygen',
        'has_ventilator',
        'has_monitor',
        'additional_charge_per_day',
        'notes',
        'last_cleaned_at',
        'occupied_since',
        'is_active',
    ];

    protected $casts = [
        'additional_charge_per_day' => 'decimal:2',
        'has_oxygen' => 'boolean',
        'has_ventilator' => 'boolean',
        'has_monitor' => 'boolean',
        'is_active' => 'boolean',
        'last_cleaned_at' => 'datetime',
        'occupied_since' => 'datetime',
    ];

    // Relationships
    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }

    public function admissions()
    {
        return $this->hasMany(Admission::class);
    }

    public function currentAdmission()
    {
        return $this->hasOne(Admission::class)->where('status', 'admitted')->latest();
    }

    // Helper methods
    public function markAsOccupied()
    {
        $this->status = 'occupied';
        $this->occupied_since = now();
        $this->save();
        $this->ward->updateBedCounts();
    }

    public function markAsAvailable()
    {
        $this->status = 'available';
        $this->occupied_since = null;
        $this->last_cleaned_at = now();
        $this->save();
        $this->ward->updateBedCounts();
    }

    public function markForCleaning()
    {
        $this->status = 'under_cleaning';
        $this->save();
        $this->ward->updateBedCounts();
    }

    public function getTotalChargePerDayAttribute()
    {
        return $this->ward->base_charge_per_day + $this->additional_charge_per_day;
    }

    public function getOccupancyDurationAttribute()
    {
        if (!$this->occupied_since) return null;
        return now()->diff($this->occupied_since);
    }
}
