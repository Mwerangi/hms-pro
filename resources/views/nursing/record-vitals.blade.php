@extends('layouts.app')

@section('title', 'Record Vital Signs')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('nursing.dashboard') }}">Nursing Station</a></li>
<li class="breadcrumb-item active" aria-current="page">Record Vitals</li>
@endsection

@push('styles')
<style>
  .vitals-container {
    max-width: 1100px;
    margin: 0 auto;
  }

  .page-header {
    background: white;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
    border: 1px solid #e5e7eb;
  }

  .patient-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-top: 16px;
  }

  .info-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #6b7280;
  }

  .info-item i {
    color: #9ca3af;
    font-size: 16px;
  }

  .info-item strong {
    color: #111827;
    font-weight: 500;
  }

  .form-card {
    background: white;
    border-radius: 12px;
    padding: 32px;
    border: 1px solid #e5e7eb;
  }

  .form-section {
    margin-bottom: 32px;
  }

  .form-section:last-child {
    margin-bottom: 0;
  }

  .section-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f3f4f6;
  }

  .section-header i {
    font-size: 20px;
    color: #667eea;
  }

  .section-header h3 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: #111827;
  }

  .vitals-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
  }

  .form-group {
    margin-bottom: 0;
  }

  .form-label {
    font-size: 13px;
    font-weight: 500;
    color: #374151;
    margin-bottom: 6px;
    display: block;
  }

  .form-label.required::after {
    content: '*';
    color: #ef4444;
    margin-left: 3px;
  }

  .form-control {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 10px 12px;
    font-size: 14px;
    transition: all 0.15s ease;
  }

  .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
  }

  .form-control::placeholder {
    color: #d1d5db;
  }

  .form-text {
    font-size: 12px;
    color: #9ca3af;
    margin-top: 4px;
    display: block;
  }

  .alert-note {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-left: 3px solid #667eea;
    border-radius: 8px;
    padding: 12px 16px;
    margin-bottom: 20px;
    font-size: 14px;
    color: #374151;
    display: flex;
    align-items: start;
    gap: 10px;
  }

  .alert-note i {
    color: #667eea;
    margin-top: 2px;
  }

  .priority-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
  }

  .priority-emergency {
    background: #fef2f2;
    color: #991b1b;
    border: 1px solid #fecaca;
  }

  .priority-walk-in {
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #fde68a;
  }

  .priority-scheduled {
    background: #eff6ff;
    color: #1e40af;
    border: 1px solid #dbeafe;
  }

  .action-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 24px;
    margin-top: 24px;
    border-top: 1px solid #f3f4f6;
  }

  .btn {
    padding: 10px 20px;
    font-size: 14px;
    font-weight: 500;
    border-radius: 8px;
    transition: all 0.15s ease;
  }

  .btn-outline-secondary {
    color: #6b7280;
    border-color: #d1d5db;
  }

  .btn-outline-secondary:hover {
    background: #f9fafb;
    border-color: #9ca3af;
    color: #374151;
  }

  .btn-primary {
    background: #667eea;
    border-color: #667eea;
  }

  .btn-primary:hover {
    background: #5568d3;
    border-color: #5568d3;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.25);
  }

  [data-theme="dark"] .page-header,
  [data-theme="dark"] .form-card {
    background: #1e293b;
    border-color: #334155;
  }

  [data-theme="dark"] .section-header {
    border-color: #334155;
  }

  [data-theme="dark"] .section-header h3,
  [data-theme="dark"] .form-label,
  [data-theme="dark"] .info-item strong {
    color: #f1f5f9;
  }

  [data-theme="dark"] .info-item {
    color: #cbd5e1;
  }

  [data-theme="dark"] .form-control {
    background: #0f172a;
    border-color: #334155;
    color: #f1f5f9;
  }

  [data-theme="dark"] .form-control:focus {
    border-color: #667eea;
    background: #1e293b;
  }

  [data-theme="dark"] .alert-note {
    background: #0f172a;
    border-color: #334155;
    color: #cbd5e1;
  }
</style>
@endpush

@section('content')
<div class="vitals-container">
  <!-- Page Header with Patient Info -->
  <div class="page-header">
    <div class="d-flex justify-content-between align-items-start">
      <div class="flex-grow-1">
        <div class="d-flex align-items-center gap-3 mb-2">
          <h1 style="font-size: 24px; font-weight: 600; margin: 0; color: #111827;">{{ $appointment->patient->full_name }}</h1>
          @if($appointment->patient_type === 'emergency')
            <span class="priority-badge priority-emergency">
              <i class="bi bi-exclamation-triangle-fill"></i> Emergency
            </span>
          @elseif($appointment->patient_type === 'walk-in')
            <span class="priority-badge priority-walk-in">
              <i class="bi bi-person-walking"></i> Walk-in
            </span>
          @else
            <span class="priority-badge priority-scheduled">
              <i class="bi bi-calendar-check"></i> Scheduled
            </span>
          @endif
        </div>
        
        <div class="patient-info-grid">
          <div class="info-item">
            <i class="bi bi-hash"></i>
            <span>Token: <strong>{{ $appointment->token_number }}</strong></span>
          </div>
          <div class="info-item">
            <i class="bi bi-person-badge"></i>
            <span>ID: <strong>{{ $appointment->patient->patient_id }}</strong></span>
          </div>
          <div class="info-item">
            <i class="bi bi-calendar3"></i>
            <span><strong>{{ $appointment->patient->age }}Y</strong>, {{ ucfirst($appointment->patient->gender) }}</span>
          </div>
          <div class="info-item">
            <i class="bi bi-telephone"></i>
            <span><strong>{{ $appointment->patient->phone }}</strong></span>
          </div>
          <div class="info-item">
            <i class="bi bi-person-heart"></i>
            <span>Dr. <strong>{{ $appointment->doctor->name }}</strong>
              @if($appointment->doctor->specialization)
                <span style="color: #9ca3af;">({{ $appointment->doctor->specialization }})</span>
              @endif
            </span>
          </div>
        </div>
      </div>
    </div>

    @if($appointment->chief_complaint_initial || $appointment->reason_for_visit)
      <div class="alert-note mt-3">
        <i class="bi bi-info-circle"></i>
        <div>
          <strong>{{ $appointment->chief_complaint_initial ? 'Chief Complaint' : 'Reason for Visit' }}:</strong>
          {{ $appointment->chief_complaint_initial ?? $appointment->reason_for_visit }}
        </div>
      </div>
    @endif
  </div>

  <!-- Vitals Form -->
  <div class="form-card">
    <form action="{{ route('nursing.record-vitals', $appointment) }}" method="POST">
      @csrf
      
      <!-- Essential Vital Signs --></span>
      <!-- Essential Vital Signs -->
      <div class="form-section">
        <div class="section-header">
          <i class="bi bi-heart-pulse-fill"></i>
          <h3>Essential Vital Signs</h3>
        </div>

        <div class="vitals-grid">
          <div class="form-group">
            <label class="form-label required">Blood Pressure</label>
            <input type="text" name="blood_pressure" class="form-control @error('blood_pressure') is-invalid @enderror" 
                   value="{{ old('blood_pressure') }}" required placeholder="120/80">
            <small class="form-text">mmHg (systolic/diastolic)</small>
            @error('blood_pressure')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-group">
            <label class="form-label required">Pulse Rate</label>
            <input type="text" name="pulse_rate" class="form-control @error('pulse_rate') is-invalid @enderror" 
                   value="{{ old('pulse_rate') }}" required placeholder="72">
            <small class="form-text">beats per minute (bpm)</small>
            @error('pulse_rate')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-group">
            <label class="form-label required">Temperature</label>
            <input type="text" name="temperature" class="form-control @error('temperature') is-invalid @enderror" 
                   value="{{ old('temperature') }}" required placeholder="98.6">
            <small class="form-text">°F or °C</small>
            @error('temperature')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-group">
            <label class="form-label">Respiratory Rate</label>
            <input type="text" name="respiratory_rate" class="form-control @error('respiratory_rate') is-invalid @enderror" 
                   value="{{ old('respiratory_rate') }}" placeholder="16">
            <small class="form-text">breaths/min (optional)</small>
            @error('respiratory_rate')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-group">
            <label class="form-label">SpO2</label>
            <input type="text" name="spo2" class="form-control @error('spo2') is-invalid @enderror" 
                   value="{{ old('spo2') }}" placeholder="98">
            <small class="form-text">% (oxygen saturation)</small>
            @error('spo2')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>

      <!-- Physical Measurements -->
      <div class="form-section">
        <div class="section-header">
          <i class="bi bi-rulers"></i>
          <h3>Physical Measurements</h3>
        </div>

        <div class="vitals-grid">
          <div class="form-group">
            <label class="form-label">Weight</label>
            <input type="text" name="weight" class="form-control @error('weight') is-invalid @enderror" 
                   value="{{ old('weight') }}" placeholder="70">
            <small class="form-text">kg or lbs (optional)</small>
            @error('weight')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-group">
            <label class="form-label">Height</label>
            <input type="text" name="height" class="form-control @error('height') is-invalid @enderror" 
                   value="{{ old('height') }}" placeholder="170">
            <small class="form-text">cm or ft/in (optional)</small>
            @error('height')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>

      <!-- Doctor Assignment -->
      <div class="form-section">
        <div class="section-header">
          <i class="bi bi-person-badge"></i>
          <h3>Doctor Assignment</h3>
        </div>

        <div class="row">
          <div class="col-md-8">
            <div class="form-group">
              <label class="form-label">Assigned Doctor</label>
              <select name="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror">
                <option value="{{ $appointment->doctor_id }}" selected>
                  Dr. {{ $appointment->doctor->name }}
                  @if($appointment->doctor->specialization)
                    ({{ $appointment->doctor->specialization }})
                  @endif
                  - Current
                </option>
                @php
                  $allDoctors = \App\Models\User::role('Doctor')->where('id', '!=', $appointment->doctor_id)->get();
                  $queueCounts = \App\Models\Appointment::whereDate('appointment_date', today())
                    ->whereIn('status', ['waiting', 'in_consultation'])
                    ->select('doctor_id', \DB::raw('count(*) as count'))
                    ->groupBy('doctor_id')
                    ->pluck('count', 'doctor_id');
                @endphp
                @foreach($allDoctors as $doctor)
                  <option value="{{ $doctor->id }}">
                    Dr. {{ $doctor->name }}
                    @if($doctor->specialization)
                      ({{ $doctor->specialization }})
                    @endif
                    - {{ $queueCounts[$doctor->id] ?? 0 }} waiting
                  </option>
                @endforeach
              </select>
              <small class="form-text">Reassign if specialty change needed</small>
              @error('doctor_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>
      </div>

      <!-- Action Bar -->
      <div class="action-bar">
        <a href="{{ route('nursing.dashboard') }}" class="btn btn-outline-secondary">
          <i class="bi bi-arrow-left me-2"></i>Back to Queue
        </a>
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-check-circle me-2"></i>Complete & Send to Doctor
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
