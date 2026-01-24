<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'medicine_code',
        'medicine_name',
        'generic_name',
        'brand_name',
        'medicine_type',
        'strength',
        'manufacturer',
        'description',
        'unit_price',
        'unit_of_measure',
        'current_stock',
        'reorder_level',
        'expiry_date',
        'is_active',
        'requires_prescription',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'current_stock' => 'integer',
        'reorder_level' => 'integer',
        'expiry_date' => 'date',
        'is_active' => 'boolean',
        'requires_prescription' => 'boolean',
    ];

    /**
     * Scope for active medicines
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for in-stock medicines
     */
    public function scopeInStock($query)
    {
        return $query->where('current_stock', '>', 0);
    }

    /**
     * Scope for low stock medicines
     */
    public function scopeLowStock($query)
    {
        return $query->whereRaw('current_stock <= reorder_level');
    }

    /**
     * Get full medicine display name
     */
    public function getFullNameAttribute()
    {
        $name = $this->medicine_name;
        if ($this->brand_name) {
            $name .= " ({$this->brand_name})";
        }
        return "{$name} - {$this->strength}";
    }

    /**
     * Check if medicine is in stock
     */
    public function isInStock()
    {
        return $this->current_stock > 0;
    }

    /**
     * Check if stock is low
     */
    public function isLowStock()
    {
        return $this->current_stock <= $this->reorder_level;
    }

    /**
     * Reduce stock when dispensed
     */
    public function reduceStock($quantity)
    {
        $this->current_stock = max(0, $this->current_stock - $quantity);
        $this->save();
        return $this;
    }

    /**
     * Add stock when received
     */
    public function addStock($quantity)
    {
        $this->current_stock += $quantity;
        $this->save();
        return $this;
    }
}

