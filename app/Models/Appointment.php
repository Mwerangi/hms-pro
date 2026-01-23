<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Appointment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'appointment_number',
        'patient_id',
        'doctor_id',
        'appointment_date',
        'appointment_time',
        'token_number',
        'queue_position',
        'appointment_type',
        'status',
        'reason_for_visit',
        'notes',
        'cancellation_reason',
        'checked_in_at',
        'consultation_started_at',
        'consultation_ended_at',
        'cancelled_at',
        'blood_pressure',
        'pulse_rate',
        'temperature',
        'respiratory_rate',
        'spo2',
        'weight',
        'height',
        'vitals_recorded_at',
        'vitals_recorded_by',
        'patient_type',
        'priority_order',
        'assigned_by',
        'doctor_assigned_at',
        'chief_complaint_initial',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'datetime',
        'checked_in_at' => 'datetime',
        'consultation_started_at' => 'datetime',
        'consultation_ended_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'vitals_recorded_at' => 'datetime',
        'doctor_assigned_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($appointment) {
            if (!$appointment->appointment_number) {
                $appointment->appointment_number = self::generateAppointmentNumber();
            }
            
            if (!$appointment->token_number) {
                $appointment->token_number = self::generateTokenNumber($appointment);
            }
        });
    }

    /**
     * Generate unique appointment number
     */
    public static function generateAppointmentNumber()
    {
        $year = date('Y');
        $lastAppointment = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $increment = $lastAppointment ? ((int) substr($lastAppointment->appointment_number, -5)) + 1 : 1;
        
        return 'APT' . $year . str_pad($increment, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Generate token number for the day
     */
    public static function generateTokenNumber($appointment)
    {
        $date = $appointment->appointment_date instanceof Carbon 
            ? $appointment->appointment_date 
            : Carbon::parse($appointment->appointment_date);

        $count = self::where('doctor_id', $appointment->doctor_id)
            ->whereDate('appointment_date', $date)
            ->count();
        
        // Prefix based on patient type
        $prefix = match($appointment->patient_type) {
            'emergency' => 'E',
            'walk-in' => 'W',
            default => 'A',
        };

        return $prefix . str_pad($count + 1, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Create walk-in appointment (same day, auto-assigned)
     */
    public static function createWalkIn($patientId, $chiefComplaint = null, $isEmergency = false, $preferredDoctorId = null)
    {
        $patientType = $isEmergency ? 'emergency' : 'walk-in';
        $priorityOrder = $isEmergency ? 1 : 3; // Emergency=1, Scheduled=2, Walk-in=3

        // If no preferred doctor, assign to doctor with smallest queue
        $doctorId = $preferredDoctorId ?? self::findLeastBusyDoctor();

        $appointment = self::create([
            'patient_id' => $patientId,
            'doctor_id' => $doctorId,
            'appointment_date' => today(),
            'appointment_time' => now(),
            'appointment_type' => $isEmergency ? 'emergency' : 'new',
            'patient_type' => $patientType,
            'priority_order' => $priorityOrder,
            'status' => 'waiting', // Walk-ins go straight to waiting
            'chief_complaint_initial' => $chiefComplaint,
            'checked_in_at' => now(),
        ]);

        return $appointment;
    }

    /**
     * Find doctor with least patients in queue today
     */
    public static function findLeastBusyDoctor()
    {
        $doctors = \App\Models\User::role('Doctor')->get();
        
        if ($doctors->isEmpty()) {
            throw new \Exception('No doctors available');
        }

        $leastBusy = null;
        $minQueue = PHP_INT_MAX;

        foreach ($doctors as $doctor) {
            $queueCount = self::where('doctor_id', $doctor->id)
                ->whereDate('appointment_date', today())
                ->whereIn('status', ['waiting', 'in-consultation'])
                ->count();

            if ($queueCount < $minQueue) {
                $minQueue = $queueCount;
                $leastBusy = $doctor;
            }
        }

        return $leastBusy->id;
    }

    /**
     * Relationships
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function consultation()
    {
        return $this->hasOne(Consultation::class);
    }

    public function vitalsRecordedBy()
    {
        return $this->belongsTo(User::class, 'vitals_recorded_by');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Scopes
     */
    public function scopeToday($query)
    {
        return $query->whereDate('appointment_date', today());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('appointment_date', '>=', today())
            ->whereIn('status', ['scheduled', 'waiting']);
    }

    public function scopeForDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Accessors
     */
    public function getFormattedDateAttribute()
    {
        return $this->appointment_date?->format('F d, Y');
    }

    public function getFormattedTimeAttribute()
    {
        return $this->appointment_time?->format('h:i A');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'scheduled' => 'badge bg-primary',
            'waiting' => 'badge bg-warning',
            'in-consultation' => 'badge bg-info',
            'completed' => 'badge bg-success',
            'cancelled' => 'badge bg-danger',
            'no-show' => 'badge bg-secondary',
        ];

        return $badges[$this->status] ?? 'badge bg-secondary';
    }

    /**
     * Helper methods
     */
    public function canBeCancelled()
    {
        return in_array($this->status, ['scheduled', 'waiting']);
    }

    public function canBeRescheduled()
    {
        return in_array($this->status, ['scheduled', 'cancelled']);
    }

    public function markAsWaiting()
    {
        $this->update([
            'status' => 'waiting',
            'checked_in_at' => now(),
        ]);
    }

    public function startConsultation()
    {
        $this->update([
            'status' => 'in-consultation',
            'consultation_started_at' => now(),
        ]);
    }

    public function completeConsultation()
    {
        $this->update([
            'status' => 'completed',
            'consultation_ended_at' => now(),
        ]);
    }

    public function cancel($reason = null)
    {
        $this->update([
            'status' => 'cancelled',
            'cancellation_reason' => $reason,
            'cancelled_at' => now(),
        ]);
    }
}
