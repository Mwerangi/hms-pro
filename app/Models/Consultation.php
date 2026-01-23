<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Consultation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'consultation_number',
        'appointment_id',
        'patient_id',
        'doctor_id',
        'temperature',
        'blood_pressure',
        'pulse_rate',
        'weight',
        'height',
        'bmi',
        'spo2',
        'respiratory_rate',
        'chief_complaint',
        'history_of_present_illness',
        'past_medical_history',
        'family_history',
        'social_history',
        'allergies',
        'current_medications',
        'general_examination',
        'systemic_examination',
        'clinical_findings',
        'provisional_diagnosis',
        'final_diagnosis',
        'icd_codes',
        'treatment_plan',
        'doctor_notes',
        'advice_instructions',
        'next_visit_date',
        'follow_up_instructions',
        'status',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
        'bmi' => 'decimal:2',
        'next_visit_date' => 'date',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Auto-generate consultation number on creation
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($consultation) {
            if (empty($consultation->consultation_number)) {
                $consultation->consultation_number = self::generateConsultationNumber();
            }
            if (empty($consultation->started_at)) {
                $consultation->started_at = now();
            }
            // Auto-calculate BMI if weight and height are provided
            if ($consultation->weight && $consultation->height) {
                $heightInMeters = $consultation->height / 100;
                $consultation->bmi = round($consultation->weight / ($heightInMeters * $heightInMeters), 2);
            }
        });

        static::updating(function ($consultation) {
            // Recalculate BMI if weight or height changed
            if ($consultation->isDirty(['weight', 'height']) && $consultation->weight && $consultation->height) {
                $heightInMeters = $consultation->height / 100;
                $consultation->bmi = round($consultation->weight / ($heightInMeters * $heightInMeters), 2);
            }
        });
    }

    // Generate unique consultation number: CON{YEAR}{5-digit}
    public static function generateConsultationNumber()
    {
        $year = date('Y');
        $lastConsultation = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = $lastConsultation 
            ? intval(substr($lastConsultation->consultation_number, -5)) + 1 
            : 1;

        return 'CON' . $year . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    // Relationships
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function labOrders()
    {
        return $this->hasMany(LabOrder::class);
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in-progress');
    }

    public function scopeForDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    public function scopeForPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    // Accessors
    public function getFormattedStartedAtAttribute()
    {
        return $this->started_at ? $this->started_at->format('M d, Y h:i A') : null;
    }

    public function getFormattedCompletedAtAttribute()
    {
        return $this->completed_at ? $this->completed_at->format('M d, Y h:i A') : null;
    }

    public function getDurationAttribute()
    {
        if ($this->started_at && $this->completed_at) {
            return $this->started_at->diffForHumans($this->completed_at, true);
        }
        return null;
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'in-progress' => '<span class="badge bg-warning">In Progress</span>',
            'completed' => '<span class="badge bg-success">Completed</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }

    public function getBmiCategoryAttribute()
    {
        if (!$this->bmi) return null;

        if ($this->bmi < 18.5) return 'Underweight';
        if ($this->bmi < 25) return 'Normal';
        if ($this->bmi < 30) return 'Overweight';
        return 'Obese';
    }

    // Helper Methods
    public function complete()
    {
        $this->status = 'completed';
        $this->completed_at = now();
        $this->save();

        // Update appointment status
        if ($this->appointment) {
            $this->appointment->completeConsultation();
        }

        return $this;
    }

    public function hasPrescription()
    {
        return $this->prescriptions()->exists();
    }

    public function hasLabOrders()
    {
        return $this->labOrders()->exists();
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function canBeEdited()
    {
        return $this->status === 'in-progress';
    }
}
