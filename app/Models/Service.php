<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'service_code',
        'service_name',
        'category',
        'department',
        'description',
        'standard_charge',
        'taxable',
        'tax_percentage',
        'is_active',
    ];

    protected $casts = [
        'standard_charge' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'taxable' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function billItems()
    {
        return $this->hasMany(BillItem::class);
    }

    public function charges()
    {
        return $this->hasMany(PatientCharge::class);
    }

    // Helper methods
    public function getChargeWithTax()
    {
        if ($this->taxable) {
            return $this->standard_charge + ($this->standard_charge * $this->tax_percentage / 100);
        }
        return $this->standard_charge;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
