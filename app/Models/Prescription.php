<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Prescription extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'prescription_number',
        'consultation_id',
        'patient_id',
        'doctor_id',
        'prescription_date',
        'valid_until',
        'special_instructions',
        'pharmacy_notes',
        'status',
        'dispensed_by',
        'dispensed_at',
        'payment_verified',
        'bill_id',
        'is_emergency',
        'emergency_approved_by',
        'emergency_reason',
    ];

    protected $casts = [
        'prescription_date' => 'date',
        'valid_until' => 'date',
        'dispensed_at' => 'datetime',
        'payment_verified' => 'boolean',
        'is_emergency' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($prescription) {
            if (empty($prescription->prescription_number)) {
                $prescription->prescription_number = self::generatePrescriptionNumber();
            }
            if (empty($prescription->prescription_date)) {
                $prescription->prescription_date = now();
            }
            if (empty($prescription->valid_until)) {
                $prescription->valid_until = now()->addDays(30);
            }
        });
    }

    public static function generatePrescriptionNumber()
    {
        $year = date('Y');
        $lastPrescription = self::whereYear('created_at', $year)->orderBy('id', 'desc')->first();
        $nextNumber = $lastPrescription ? intval(substr($lastPrescription->prescription_number, -5)) + 1 : 1;
        return 'RX' . $year . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function dispensedBy()
    {
        return $this->belongsTo(User::class, 'dispensed_by');
    }

    public function dispensedByUser()
    {
        return $this->belongsTo(User::class, 'dispensed_by');
    }

    public function items()
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function dispense($userId = null)
    {
        $this->status = 'dispensed';
        $this->dispensed_by = $userId ?? auth()->id();
        $this->dispensed_at = now();
        $this->save();
        $this->items()->update(['status' => 'dispensed']);
        return $this;
    }

    public function canBeDispensed()
    {
        return in_array($this->status, ['pending', 'partially-dispensed']);
    }

    /**
     * Check if payment is verified (50% minimum threshold)
     */
    public function hasPaymentVerification()
    {
        if ($this->is_emergency) {
            return true; // Emergency prescriptions bypass payment check
        }

        if (!$this->bill_id) {
            return false; // No bill created yet
        }

        $bill = $this->bill;
        if (!$bill) {
            return false;
        }

        // Check if at least 50% of bill is paid
        $paymentPercentage = ($bill->paid_amount / $bill->total_amount) * 100;
        return $paymentPercentage >= 50;
    }

    /**
     * Verify payment before dispensing
     */
    public function verifyPayment(Bill $bill)
    {
        $this->update([
            'payment_verified' => true,
            'bill_id' => $bill->id,
        ]);
    }

    /**
     * Mark as emergency to bypass payment
     */
    public function markAsEmergency($userId, $reason)
    {
        $this->update([
            'is_emergency' => true,
            'emergency_approved_by' => $userId,
            'emergency_reason' => $reason,
        ]);
    }

    /**
     * Relationships
     */
    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    public function emergencyApprovedBy()
    {
        return $this->belongsTo(User::class, 'emergency_approved_by');
    }
}

