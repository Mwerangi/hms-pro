<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PatientDeposit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'admission_id',
        'patient_id',
        'deposit_amount',
        'utilized_amount',
        'balance_amount',
        'deposit_date',
        'payment_method',
        'payment_reference',
        'received_by_user_id',
        'refund_amount',
        'refund_date',
        'refunded_by_user_id',
        'notes',
    ];

    protected $casts = [
        'deposit_amount' => 'decimal:2',
        'utilized_amount' => 'decimal:2',
        'balance_amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'deposit_date' => 'datetime',
        'refund_date' => 'datetime',
    ];

    // Relationships
    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by_user_id');
    }

    public function refundedBy()
    {
        return $this->belongsTo(User::class, 'refunded_by_user_id');
    }

    // Helper methods
    public function utilize($amount)
    {
        $this->utilized_amount += $amount;
        $this->balance_amount = $this->deposit_amount - $this->utilized_amount - $this->refund_amount;
        $this->save();
    }

    public function processRefund($amount, $userId = null)
    {
        $this->refund_amount += $amount;
        $this->balance_amount = $this->deposit_amount - $this->utilized_amount - $this->refund_amount;
        $this->refund_date = now();
        $this->refunded_by_user_id = $userId ?? auth()->id();
        $this->save();
    }
}
