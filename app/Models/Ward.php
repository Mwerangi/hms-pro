<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ward extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ward_number',
        'ward_name',
        'ward_type',
        'floor',
        'building',
        'total_beds',
        'available_beds',
        'occupied_beds',
        'description',
        'nurse_id',
        'contact_number',
        'base_charge_per_day',
        'is_active',
    ];

    protected $casts = [
        'base_charge_per_day' => 'decimal:2',
        'is_active' => 'boolean',
        'total_beds' => 'integer',
        'available_beds' => 'integer',
        'occupied_beds' => 'integer',
    ];

    // Relationships
    public function nurse()
    {
        return $this->belongsTo(User::class, 'nurse_id');
    }

    public function beds()
    {
        return $this->hasMany(Bed::class);
    }

    public function admissions()
    {
        return $this->hasMany(Admission::class);
    }

    public function activeBeds()
    {
        return $this->hasMany(Bed::class)->where('is_active', true);
    }

    public function availableBeds()
    {
        return $this->hasMany(Bed::class)->where('status', 'available')->where('is_active', true);
    }

    public function occupiedBeds()
    {
        return $this->hasMany(Bed::class)->where('status', 'occupied');
    }

    // Helper methods
    public function updateBedCounts()
    {
        $this->available_beds = $this->beds()->where('status', 'available')->where('is_active', true)->count();
        $this->occupied_beds = $this->beds()->where('status', 'occupied')->count();
        $this->total_beds = $this->beds()->where('is_active', true)->count();
        $this->save();
    }

    public function getOccupancyRateAttribute()
    {
        if ($this->total_beds == 0) return 0;
        return round(($this->occupied_beds / $this->total_beds) * 100, 1);
    }
}
