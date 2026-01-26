<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'name',
        'code',
        'address',
        'phone',
        'email',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get departments in this branch
     */
    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    /**
     * Get users in this branch
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Scope to get only active branches
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
