<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LabOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'consultation_id',
        'patient_id',
        'doctor_id',
        'test_type',
        'test_category',
        'tests_ordered',
        'clinical_notes',
        'special_instructions',
        'urgency',
        'priority',
        'status',
        'order_date',
        'scheduled_at',
        'sample_collected_at',
        'collected_by',
        'completed_at',
        'processed_by',
        'reported_at',
        'reported_by',
        'results',
        'result_values',
        'result_file_path',
        'imaging_file_path',
        'radiologist_findings',
        'lab_technician_notes',
        'viewed_at',
        'viewed_by',
    ];

    protected $casts = [
        'order_date' => 'date',
        'scheduled_at' => 'datetime',
        'sample_collected_at' => 'datetime',
        'completed_at' => 'datetime',
        'reported_at' => 'datetime',
        'viewed_at' => 'datetime',
        'result_values' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($labOrder) {
            if (empty($labOrder->order_number)) {
                $labOrder->order_number = self::generateOrderNumber();
            }
            if (empty($labOrder->order_date)) {
                $labOrder->order_date = now();
            }
        });
    }

    public static function generateOrderNumber()
    {
        $year = date('Y');
        $lastOrder = self::whereYear('created_at', $year)->orderBy('id', 'desc')->first();
        $nextNumber = $lastOrder ? intval(substr($lastOrder->order_number, -5)) + 1 : 1;
        return 'LAB' . $year . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
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

    public function collectedBy()
    {
        return $this->belongsTo(User::class, 'collected_by');
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function reportedBy()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function viewedBy()
    {
        return $this->belongsTo(User::class, 'viewed_by');
    }

    // Workflow methods
    public function collectSample($userId = null)
    {
        $this->status = 'sample-collected';
        $this->sample_collected_at = now();
        $this->collected_by = $userId ?? auth()->id();
        $this->save();
        return $this;
    }

    public function startProcessing($userId = null)
    {
        $this->status = 'in-progress';
        $this->processed_by = $userId ?? auth()->id();
        $this->save();
        return $this;
    }

    public function complete($userId = null)
    {
        $this->status = 'completed';
        $this->completed_at = now();
        if (!$this->processed_by) {
            $this->processed_by = $userId ?? auth()->id();
        }
        $this->save();
        return $this;
    }

    public function report($results, $userId = null)
    {
        $this->status = 'reported';
        $this->results = $results;
        $this->reported_at = now();
        $this->reported_by = $userId ?? auth()->id();
        $this->save();
        return $this;
    }

    public function cancel()
    {
        $this->status = 'cancelled';
        $this->save();
        return $this;
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSampleCollected($query)
    {
        return $query->where('status', 'sample-collected');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in-progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeReported($query)
    {
        return $query->where('status', 'reported');
    }

    public function scopeUrgent($query)
    {
        return $query->whereIn('urgency', ['urgent', 'stat']);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('test_type', $type);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="badge bg-warning">Pending</span>',
            'sample-collected' => '<span class="badge bg-info">Sample Collected</span>',
            'in-progress' => '<span class="badge bg-primary">In Progress</span>',
            'completed' => '<span class="badge bg-success">Completed</span>',
            'reported' => '<span class="badge bg-success">Reported</span>',
            'cancelled' => '<span class="badge bg-danger">Cancelled</span>',
        ];
        return $badges[$this->status] ?? '<span class="badge bg-secondary">' . ucfirst($this->status) . '</span>';
    }

    public function getUrgencyBadgeAttribute()
    {
        $badges = [
            'routine' => '<span class="badge bg-secondary">Routine</span>',
            'urgent' => '<span class="badge bg-warning">Urgent</span>',
            'stat' => '<span class="badge bg-danger">STAT</span>',
        ];
        return $badges[$this->urgency] ?? '<span class="badge bg-secondary">' . ucfirst($this->urgency) . '</span>';
    }

    public function getPriorityBadgeAttribute()
    {
        $badges = [
            'normal' => '<span class="badge bg-secondary">Normal</span>',
            'high' => '<span class="badge bg-warning">High</span>',
            'critical' => '<span class="badge bg-danger">Critical</span>',
        ];
        return $badges[$this->priority] ?? '<span class="badge bg-secondary">Normal</span>';
    }

    public function getTestsListAttribute()
    {
        if (is_array($this->tests_ordered)) {
            return implode(', ', $this->tests_ordered);
        }
        return $this->tests_ordered;
    }

    // Helper methods
    public function isImaging()
    {
        return $this->test_type === 'imaging';
    }

    public function canCollectSample()
    {
        return $this->status === 'pending';
    }

    public function canProcess()
    {
        return in_array($this->status, ['pending', 'sample-collected']);
    }

    public function canComplete()
    {
        return in_array($this->status, ['sample-collected', 'in-progress']);
    }

    public function canReport()
    {
        return in_array($this->status, ['completed', 'in-progress']);
    }

    public function canBeCancelled()
    {
        return !in_array($this->status, ['completed', 'reported', 'cancelled']);
    }

    public function isUrgent()
    {
        return in_array($this->urgency, ['urgent', 'stat']);
    }

    public function isCritical()
    {
        return $this->priority === 'critical';
    }
}
