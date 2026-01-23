@extends('layouts.app')

@section('title', 'Nursing Station')

@section('breadcrumbs')
<li class="breadcrumb-item active" aria-current="page">Nursing Station</li>
@endsection

@push('styles')
<style>
  /* Modern Minimalistic Design */
  .page-header {
    margin-bottom: 32px;
  }

  .page-title {
    font-size: 24px;
    font-weight: 600;
    color: #111827;
    margin: 0 0 4px 0;
  }

  .page-subtitle {
    color: #6b7280;
    font-size: 14px;
    margin: 0;
  }

  /* Stats Grid */
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
  }

  .stat-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px;
    transition: all 0.2s ease;
  }

  .stat-card:hover {
    border-color: #667eea;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
  }

  .stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: #667eea;
    margin-bottom: 14px;
  }

  .stat-value {
    font-size: 28px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 4px;
  }

  .stat-label {
    font-size: 13px;
    color: #6b7280;
    font-weight: 500;
  }

  /* Section Cards */
  .section-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
  }

  .section-title {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .section-title i {
    color: #667eea;
    font-size: 20px;
  }

  .section-title .badge {
    background: #f3f4f6;
    color: #667eea;
    font-size: 12px;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 10px;
  }

  /* Patient Cards */
  .patient-card {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 16px;
    transition: all 0.2s ease;
  }

  .patient-card:hover {
    background: white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  }

  .patient-card.completed {
    background: #f0fdf4;
    border-color: #d1fae5;
  }

  .token-badge {
    background: #f3f4f6;
    color: #667eea;
    padding: 10px 16px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 700;
    min-width: 70px;
    text-align: center;
  }

  .token-badge.completed {
    background: #d1fae5;
    color: #065f46;
  }

  .patient-name {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 4px;
  }

  .patient-details {
    font-size: 13px;
    color: #6b7280;
  }

  .vital-badge {
    background: #f3f4f6;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 12px;
    color: #374151;
    margin-right: 6px;
    margin-bottom: 4px;
    display: inline-block;
  }

  /* Empty State */
  .empty-state {
    text-align: center;
    padding: 48px 20px;
    color: #9ca3af;
  }

  .empty-state i {
    font-size: 48px;
    color: #d1d5db;
    margin-bottom: 12px;
  }

  .empty-state h5 {
    font-size: 16px;
    font-weight: 600;
    color: #6b7280;
    margin-bottom: 4px;
  }

  .empty-state p {
    font-size: 14px;
    color: #9ca3af;
    margin: 0;
  }

  .badge {
    font-size: 11px;
    padding: 4px 8px;
    font-weight: 600;
  }

  .bg-danger { background: #fee2e2 !important; color: #991b1b !important; }
  .bg-warning { background: #fef3c7 !important; color: #92400e !important; }
  .bg-primary { background: #dbeafe !important; color: #1e40af !important; }
  .bg-info { background: #e0e7ff !important; color: #3730a3 !important; }
  .bg-secondary { background: #f3f4f6 !important; color: #374151 !important; }
  .bg-success { background: #d1fae5 !important; color: #065f46 !important; }
</style>
@endpush

@section('content')
<div class="container-fluid">
  <!-- Page Header -->
  <div class="page-header">
    <h1 class="page-title">Nursing Station</h1>
    <p class="page-subtitle">Record vitals and manage patient queue</p>
  </div>

  <!-- Statistics -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon">
        <i class="bi bi-calendar-check"></i>
      </div>
      <div class="stat-value">{{ $stats['total_today'] }}</div>
      <div class="stat-label">Total Today</div>
    </div>

    <div class="stat-card">
      <div class="stat-icon">
        <i class="bi bi-hourglass-split"></i>
      </div>
      <div class="stat-value">{{ $stats['pending_vitals'] }}</div>
      <div class="stat-label">Pending</div>
    </div>

    <div class="stat-card">
      <div class="stat-icon">
        <i class="bi bi-check-circle"></i>
      </div>
      <div class="stat-value">{{ $stats['completed_vitals'] }}</div>
      <div class="stat-label">Completed</div>
    </div>

    <div class="stat-card">
      <div class="stat-icon">
        <i class="bi bi-person-badge"></i>
      </div>
      <div class="stat-value">{{ $stats['ready_for_doctor'] }}</div>
      <div class="stat-label">Ready</div>
    </div>
  </div>

<!-- Pending Vitals Section -->
@php
  // Pre-fetch all doctors once to avoid N+1 query issues
  $allDoctors = \App\Models\User::role('Doctor')->get();
  
  // Pre-calculate queue counts for all doctors
  $queueCounts = \App\Models\Appointment::whereDate('appointment_date', today())
    ->whereIn('status', ['waiting', 'in_consultation'])
    ->select('doctor_id', \DB::raw('count(*) as count'))
    ->groupBy('doctor_id')
    ->pluck('count', 'doctor_id');
@endphp

  <!-- Pending Vitals Section -->
  <div class="section-card">
    <div class="section-title">
      <i class="bi bi-clipboard-pulse"></i>
      Pending Vitals
      @if($pendingVitals->count() > 0)
        <span class="badge">{{ $pendingVitals->count() }}</span>
      @endif
    </div>

    @if($pendingVitals->count() > 0)
      @foreach($pendingVitals as $appointment)
        <div class="patient-card">
          <div class="d-flex gap-3 align-items-start">
            <div class="token-badge">{{ $appointment->token_number }}</div>
            <div style="flex: 1;">
              <div class="patient-name">{{ $appointment->patient->full_name }}</div>
              <div class="patient-details">
                <i class="bi bi-person-badge me-1"></i>{{ $appointment->patient->patient_id }} |
                <i class="bi bi-gender-{{ strtolower($appointment->patient->gender) }} me-1"></i>{{ $appointment->patient->age }}Y, {{ ucfirst($appointment->patient->gender) }} |
                <i class="bi bi-telephone me-1"></i>{{ $appointment->patient->phone }}
              </div>
              <div class="mt-2" style="font-size: 14px;">
                @if($appointment->patient_type === 'emergency')
                  <span class="badge bg-danger"><i class="bi bi-exclamation-triangle"></i> EMERGENCY</span>
                @elseif($appointment->patient_type === 'walk-in')
                  <span class="badge bg-warning text-dark"><i class="bi bi-person-walking"></i> Walk-in</span>
                @else
                  <span class="badge bg-primary"><i class="bi bi-calendar-check"></i> Scheduled</span>
                @endif
                <span class="badge bg-info">{{ ucfirst($appointment->appointment_type) }}</span>
                <span class="badge bg-secondary">Dr. {{ $appointment->doctor->name }}</span>
                @if($appointment->checked_in_at)
                  <span class="badge bg-success">Checked in {{ $appointment->checked_in_at->diffForHumans() }}</span>
                @endif
              </div>
              @if($appointment->chief_complaint_initial)
                <div class="mt-2" style="font-size: 13px; color: #64748b;">
                  <strong>Chief Complaint:</strong> {{ $appointment->chief_complaint_initial }}
                </div>
              @elseif($appointment->reason_for_visit)
                <div class="mt-2" style="font-size: 13px; color: #64748b;">
                  <strong>Reason:</strong> {{ $appointment->reason_for_visit }}
                </div>
              @endif
            </div>
            <div>
              <a href="{{ route('nursing.vitals-form', $appointment) }}" class="btn btn-primary">
                <i class="bi bi-heart-pulse me-2"></i>Record Vitals
              </a>
            </div>
          </div>
        </div>
      @endforeach

    @else
      <div class="empty-state">
        <i class="bi bi-check-circle"></i>
        <h5>All Caught Up!</h5>
        <p>No patients waiting for vital signs recording at this moment.</p>
      </div>
    @endif
  </div>

  <!-- Completed Vitals Section -->
  <div class="section-card">
    <div class="section-title">
      <i class="bi bi-clipboard-check"></i>
      Vitals Recorded - Ready for Doctor
      @if($completedVitals->count() > 0)
        <span class="badge">{{ $completedVitals->count() }}</span>
      @endif
    </div>

    @if($completedVitals->count() > 0)
      @foreach($completedVitals as $appointment)
        <div class="patient-card completed">
          <div class="d-flex gap-3 align-items-start">
            <div class="token-badge completed">{{ $appointment->token_number }}</div>
            <div style="flex: 1;">
              <div class="patient-name">{{ $appointment->patient->full_name }}</div>
              <div class="patient-details">
                <i class="bi bi-person-badge me-1"></i>{{ $appointment->patient->patient_id }} |
                <i class="bi bi-gender-{{ strtolower($appointment->patient->gender) }} me-1"></i>{{ $appointment->patient->age }}Y |
                Dr. {{ $appointment->doctor->name }}
              </div>
              <div class="mt-2">
                <span class="vital-badge"><i class="bi bi-heart-pulse"></i> BP: {{ $appointment->blood_pressure }}</span>
                <span class="vital-badge"><i class="bi bi-activity"></i> Pulse: {{ $appointment->pulse_rate }}</span>
                <span class="vital-badge"><i class="bi bi-thermometer"></i> Temp: {{ $appointment->temperature }}</span>
                @if($appointment->spo2)
                  <span class="vital-badge"><i class="bi bi-lungs"></i> SpO2: {{ $appointment->spo2 }}</span>
                @endif
                @if($appointment->weight)
                  <span class="vital-badge"><i class="bi bi-person"></i> Weight: {{ $appointment->weight }}</span>
                @endif
              </div>
              <div class="mt-2" style="font-size: 13px; color: #10b981;">
                <i class="bi bi-check-circle"></i> Recorded {{ $appointment->vitals_recorded_at->diffForHumans() }}
                by {{ $appointment->vitalsRecordedBy->name }}
              </div>
            </div>
          </div>
        </div>
      @endforeach
    @else
      <div class="empty-state">
        <i class="bi bi-calendar-x"></i>
        <h5>No Records Yet</h5>
        <p>No vitals have been recorded today.</p>
      </div>
    @endif
  </div>
</div>
@endsection
