<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriptionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'prescription_id',
        'medicine_name',
        'medicine_type',
        'dosage',
        'frequency',
        'duration',
        'quantity',
        'route',
        'instructions',
        'quantity_dispensed',
        'status',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'quantity_dispensed' => 'integer',
    ];

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

    public function dispense($quantity = null)
    {
        $this->quantity_dispensed = $quantity ?? $this->quantity;
        $this->status = 'dispensed';
        $this->save();
        return $this;
    }

    public function getRemainingQuantityAttribute()
    {
        return max(0, $this->quantity - $this->quantity_dispensed);
    }
}
