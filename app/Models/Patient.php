<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'patient_id',
        'first_name',
        'middle_name',
        'last_name',
        'date_of_birth',
        'gender',
        'blood_group',
        'marital_status',
        'phone',
        'email',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'allergies',
        'chronic_conditions',
        'current_medications',
        'medical_history',
        'occupation',
        'insurance_provider',
        'insurance_number',
        'photo',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_of_birth' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the patient's full name.
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
    }

    /**
     * Get the patient's age.
     */
    public function getAgeAttribute(): int
    {
        return $this->date_of_birth->age;
    }

    /**
     * Boot function to generate patient ID
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($patient) {
            if (empty($patient->patient_id)) {
                $patient->patient_id = self::generatePatientId();
            }
        });
    }

    /**
     * Generate unique patient ID
     */
    private static function generatePatientId(): string
    {
        $year = date('Y');
        $lastPatient = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();
        
        $nextNumber = $lastPatient ? (int)substr($lastPatient->patient_id, -5) + 1 : 1;
        
        return 'PAT' . $year . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Get all appointments for the patient
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the latest upcoming or active appointment
     */
    public function latestAppointment()
    {
        return $this->hasOne(Appointment::class)
            ->whereIn('status', ['scheduled', 'waiting', 'in_consultation'])
            ->latest('appointment_time');
    }
}
