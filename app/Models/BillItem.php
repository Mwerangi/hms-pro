<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BillItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_id',
        'service_id',
        'service_name',
        'service_code',
        'quantity',
        'unit_price',
        'discount_percentage',
        'discount_amount',
        'tax_percentage',
        'tax_amount',
        'total_amount',
        'service_date',
        'performed_by_user_id',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'service_date' => 'datetime',
    ];

    // Relationships
    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function performedBy()
    {
        return $this->belongsTo(User::class, 'performed_by_user_id');
    }

    // Helpers
    public function calculateTotals()
    {
        // Calculate base amount
        $baseAmount = $this->unit_price * $this->quantity;
        
        // Calculate discount
        if ($this->discount_percentage > 0) {
            $this->discount_amount = $baseAmount * ($this->discount_percentage / 100);
        }
        
        $amountAfterDiscount = $baseAmount - $this->discount_amount;
        
        // Calculate tax
        if ($this->tax_percentage > 0) {
            $this->tax_amount = $amountAfterDiscount * ($this->tax_percentage / 100);
        }
        
        // Calculate total
        $this->total_amount = $amountAfterDiscount + $this->tax_amount;
        
        $this->save();
    }
}
