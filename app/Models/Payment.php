<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'payment_number',
        'bill_id',
        'patient_id',
        'payment_date',
        'amount',
        'payment_method',
        'payment_reference',
        'bank_name',
        'received_by_user_id',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'amount' => 'decimal:2',
    ];

    // Relationships
    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by_user_id');
    }
}
