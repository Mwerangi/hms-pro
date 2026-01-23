<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'admission_number',
        'patient_id',
        'bed_id',
        'ward_id',
        'doctor_id',
        'admitted_by',
        'admission_date',
        'admission_type',
        'admission_category',
        'reason_for_admission',
        'provisional_diagnosis',
        'complaints',
        'medical_history',
        'blood_pressure',
        'temperature',
        'pulse_rate',
        'respiratory_rate',
        'oxygen_saturation',
        'payment_mode',
        'insurance_company',
        'insurance_policy_number',
        'estimated_stay_days',
        'advance_payment',
        'status',
        'discharge_date',
        'discharged_by',
        'discharge_summary',
        'discharge_instructions',
        'follow_up_instructions',
        'emergency_contact_name',
        'emergency_contact_relation',
        'emergency_contact_phone',
        'admission_notes',
        'special_instructions',
    ];

    protected $casts = [
        'admission_date' => 'datetime',
        'discharge_date' => 'datetime',
        'temperature' => 'decimal:1',
        'estimated_stay_days' => 'decimal:1',
        'advance_payment' => 'decimal:2',
        'pulse_rate' => 'integer',
        'respiratory_rate' => 'integer',
        'oxygen_saturation' => 'integer',
    ];

    // Relationships
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function bed()
    {
        return $this->belongsTo(Bed::class);
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function admittedBy()
    {
        return $this->belongsTo(User::class, 'admitted_by');
    }

    public function dischargedBy()
    {
        return $this->belongsTo(User::class, 'discharged_by');
    }

    // Scopes
    public function scopeAdmitted($query)
    {
        return $query->where('status', 'admitted');
    }

    public function scopeDischarged($query)
    {
        return $query->where('status', 'discharged');
    }

    // Helper methods
    public static function generateAdmissionNumber()
    {
        $year = date('Y');
        $lastAdmission = static::whereYear('created_at', $year)->orderBy('id', 'desc')->first();
        $nextNumber = $lastAdmission ? (int)substr($lastAdmission->admission_number, -5) + 1 : 1;
        return 'ADM' . $year . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    public function getDurationAttribute()
    {
        $end = $this->discharge_date ?? now();
        return $this->admission_date->diff($end);
    }

    public function getTotalDaysAttribute()
    {
        $end = $this->discharge_date ?? now();
        return $this->admission_date->diffInDays($end) + 1; // Include admission day
    }
}
