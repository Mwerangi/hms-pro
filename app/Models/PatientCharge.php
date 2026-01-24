<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PatientCharge extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id',
        'service_id',
        'source_type',
        'source_id',
        'quantity',
        'unit_price',
        'discount_percentage',
        'discount_amount',
        'taxable',
        'tax_percentage',
        'tax_amount',
        'total_amount',
        'status',
        'bill_id',
        'added_by',
        'notes',
        'service_date',
        'billed_at',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'taxable' => 'boolean',
        'tax_percentage' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'service_date' => 'datetime',
        'billed_at' => 'datetime',
    ];

    // Relationships
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function source()
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeBilled($query)
    {
        return $query->where('status', 'billed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeForPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('service_date', $date);
    }

    // Helper Methods
    public function calculateTotals()
    {
        $subtotal = $this->quantity * $this->unit_price;
        
        // Apply discount
        if ($this->discount_percentage > 0) {
            $this->discount_amount = $subtotal * ($this->discount_percentage / 100);
        }
        
        $afterDiscount = $subtotal - $this->discount_amount;
        
        // Apply tax
        if ($this->taxable) {
            $this->tax_amount = $afterDiscount * ($this->tax_percentage / 100);
        } else {
            $this->tax_amount = 0;
        }
        
        $this->total_amount = $afterDiscount + $this->tax_amount;
        
        return $this;
    }

    public static function createFromService(Service $service, Patient $patient, $sourceType = null, $sourceId = null, $quantity = 1)
    {
        $charge = new self([
            'patient_id' => $patient->id,
            'service_id' => $service->id,
            'source_type' => $sourceType,
            'source_id' => $sourceId,
            'quantity' => $quantity,
            'unit_price' => $service->standard_charge,
            'taxable' => $service->taxable,
            'tax_percentage' => $service->tax_percentage,
            'added_by' => auth()->id(),
            'service_date' => now(),
        ]);

        $charge->calculateTotals();
        $charge->save();

        return $charge;
    }

    public function markAsBilled(Bill $bill)
    {
        $this->update([
            'status' => 'billed',
            'bill_id' => $bill->id,
            'billed_at' => now(),
        ]);
    }

    public function cancel($reason = null)
    {
        $this->update([
            'status' => 'cancelled',
            'notes' => $reason ? "Cancelled: {$reason}" : $this->notes,
        ]);
    }
}

