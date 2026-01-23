<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bill extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'bill_number',
        'bill_type',
        'patient_id',
        'visit_type',
        'reference_id',
        'bill_date',
        'sub_total',
        'discount_amount',
        'discount_percentage',
        'discount_reason',
        'tax_amount',
        'total_amount',
        'paid_amount',
        'balance_amount',
        'payment_status',
        'insurance_claim_amount',
        'patient_payable',
        'billed_by_user_id',
        'status',
        'notes',
    ];

    protected $casts = [
        'bill_date' => 'datetime',
        'sub_total' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance_amount' => 'decimal:2',
        'insurance_claim_amount' => 'decimal:2',
        'patient_payable' => 'decimal:2',
    ];

    // Relationships
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function billedBy()
    {
        return $this->belongsTo(User::class, 'billed_by_user_id');
    }

    public function billItems()
    {
        return $this->hasMany(BillItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Helper methods
    public function calculateTotals()
    {
        $this->sub_total = $this->billItems()->sum('total_amount');
        
        // Calculate discount
        if ($this->discount_percentage > 0) {
            $this->discount_amount = $this->sub_total * ($this->discount_percentage / 100);
        }
        
        // Calculate tax
        $this->tax_amount = $this->billItems()->sum('tax_amount');
        
        // Calculate total
        $this->total_amount = $this->sub_total - $this->discount_amount + $this->tax_amount;
        
        // Calculate balance
        $this->balance_amount = $this->total_amount - $this->paid_amount;
        
        // Update payment status
        if ($this->balance_amount <= 0) {
            $this->payment_status = 'paid';
        } elseif ($this->paid_amount > 0) {
            $this->payment_status = 'partial';
        } else {
            $this->payment_status = 'unpaid';
        }
        
        $this->save();
    }

    public function addPayment($amount, $method, $reference = null, $userId = null)
    {
        $payment = Payment::create([
            'payment_number' => $this->generatePaymentNumber(),
            'bill_id' => $this->id,
            'patient_id' => $this->patient_id,
            'payment_date' => now(),
            'amount' => $amount,
            'payment_method' => $method,
            'payment_reference' => $reference,
            'received_by_user_id' => $userId ?? auth()->id(),
        ]);

        $this->paid_amount += $amount;
        $this->calculateTotals();

        return $payment;
    }

    protected function generatePaymentNumber()
    {
        $lastPayment = Payment::orderBy('id', 'desc')->first();
        $nextNumber = $lastPayment ? intval(substr($lastPayment->payment_number, 4)) + 1 : 1;
        return 'PAY-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }

    // Scopes
    public function scopeFinalized($query)
    {
        return $query->where('status', 'finalized');
    }

    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', 'unpaid');
    }

    public function scopePartiallyPaid($query)
    {
        return $query->where('payment_status', 'partial');
    }
}
