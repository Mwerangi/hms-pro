@extends('layouts.app')

@section('title', 'Discharge Patient')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('ipd.dashboard') }}">IPD</a></li>
<li class="breadcrumb-item"><a href="{{ route('admissions.index') }}">Admissions</a></li>
<li class="breadcrumb-item"><a href="{{ route('admissions.show', $admission) }}">{{ $admission->admission_number }}</a></li>
<li class="breadcrumb-item active" aria-current="page">Discharge</li>
@endsection

@push('styles')
<style>
  .discharge-container { max-width: 900px; margin: 0 auto; }
  .patient-summary { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; margin-bottom: 24px; }
  .summary-title { font-size: 18px; font-weight: 600; color: #111827; margin-bottom: 16px; }
  .summary-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; }
  .summary-item { }
  .summary-label { font-size: 12px; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
  .summary-value { font-size: 14px; color: #111827; font-weight: 500; }
  .form-card { background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 32px; }
  .form-section-title { font-size: 16px; font-weight: 600; color: #111827; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 2px solid #e5e7eb; display: flex; align-items: center; gap: 8px; }
  .form-section-title i { color: #6b7280; font-size: 18px; }
  .alert-warning-custom { background: #fffbeb; border-left: 4px solid #f59e0b; padding: 16px; border-radius: 8px; margin-bottom: 24px; color: #92400e; }
  .btn-primary-custom { background: #111827; color: white; border: 1px solid #111827; padding: 12px 24px; border-radius: 8px; font-weight: 500; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s; }
  .btn-primary-custom:hover { background: #374151; border-color: #374151; color: white; transform: translateY(-1px); }
  .btn-danger-custom { background: #dc2626; border-color: #dc2626; }
  .btn-danger-custom:hover { background: #991b1b; border-color: #991b1b; }
  .btn-outline-custom { background: white; color: #111827; border: 1px solid #e5e7eb; }
  .btn-outline-custom:hover { background: #f9fafb; border-color: #d1d5db; color: #111827; }
</style>
@endpush

@section('content')
<div class="discharge-container">
  <!-- Patient Summary -->
  <div class="patient-summary">
    <div class="summary-title">
      <i class="bi bi-person-fill"></i> {{ $admission->patient->full_name }}
    </div>
    <div class="summary-grid">
      <div class="summary-item">
        <div class="summary-label">Admission Number</div>
        <div class="summary-value">{{ $admission->admission_number }}</div>
      </div>
      <div class="summary-item">
        <div class="summary-label">Admission Date</div>
        <div class="summary-value">{{ \Carbon\Carbon::parse($admission->admission_date)->format('M d, Y') }}</div>
      </div>
      <div class="summary-item">
        <div class="summary-label">Days Admitted</div>
        <div class="summary-value">
          {{ (int) \Carbon\Carbon::parse($admission->admission_date)->diffInDays(now()) }} days
        </div>
      </div>
      <div class="summary-item">
        <div class="summary-label">Ward</div>
        <div class="summary-value">{{ $admission->ward->ward_name }}</div>
      </div>
      <div class="summary-item">
        <div class="summary-label">Bed</div>
        <div class="summary-value">{{ $admission->bed ? $admission->bed->bed_number : 'Not Assigned' }}</div>
      </div>
      <div class="summary-item">
        <div class="summary-label">Attending Doctor</div>
        <div class="summary-value">Dr. {{ $admission->doctor->name }}</div>
      </div>
    </div>
  </div>

  <!-- Discharge Form -->
  <div class="form-card">
    <div class="alert-warning-custom">
      <i class="bi bi-exclamation-triangle-fill"></i>
      <strong>Warning:</strong> This action will discharge the patient and mark the bed as available for cleaning. Please ensure all required information is complete.
    </div>

    <form action="{{ route('admissions.discharge', $admission) }}" method="POST">
      @csrf
      
      <div class="form-section-title">
        <i class="bi bi-calendar-check"></i>
        Discharge Details
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label for="discharge_date" class="form-label">
            Discharge Date & Time <span class="text-danger">*</span>
          </label>
          <input 
            type="datetime-local" 
            class="form-control @error('discharge_date') is-invalid @enderror" 
            id="discharge_date" 
            name="discharge_date" 
            value="{{ old('discharge_date', now()->format('Y-m-d\TH:i')) }}"
            required
          >
          @error('discharge_date')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <div class="mb-3">
        <label for="discharge_summary" class="form-label">
          Discharge Summary <span class="text-danger">*</span>
        </label>
        <textarea 
          class="form-control @error('discharge_summary') is-invalid @enderror" 
          id="discharge_summary" 
          name="discharge_summary" 
          rows="6"
          placeholder="Provide a comprehensive summary of the patient's hospital stay, treatments administered, and final diagnosis..."
          required
        >{{ old('discharge_summary') }}</textarea>
        @error('discharge_summary')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="text-muted">
          <i class="bi bi-info-circle"></i> 
          Include final diagnosis, treatments administered, patient condition at discharge, and response to treatment.
        </small>
      </div>

      <div class="mb-3">
        <label for="discharge_instructions" class="form-label">
          Discharge Instructions
        </label>
        <textarea 
          class="form-control @error('discharge_instructions') is-invalid @enderror" 
          id="discharge_instructions" 
          name="discharge_instructions" 
          rows="4"
          placeholder="Home care instructions, medication to continue, activity restrictions, warning signs to watch for..."
        >{{ old('discharge_instructions') }}</textarea>
        @error('discharge_instructions')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="text-muted">
          <i class="bi bi-info-circle"></i> 
          Instructions for patient care after leaving the hospital.
        </small>
      </div>

      <div class="mb-4">
        <label for="follow_up_instructions" class="form-label">
          Follow-up Instructions
        </label>
        <textarea 
          class="form-control @error('follow_up_instructions') is-invalid @enderror" 
          id="follow_up_instructions" 
          name="follow_up_instructions" 
          rows="3"
          placeholder="Schedule for follow-up appointments, tests to be done, specialist referrals..."
        >{{ old('follow_up_instructions') }}</textarea>
        @error('follow_up_instructions')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="text-muted">
          <i class="bi bi-info-circle"></i> 
          When and why the patient should return for follow-up care.
        </small>
      </div>

      <!-- Action Buttons -->
      <div class="d-flex gap-3 justify-content-end" style="border-top: 2px solid #e5e7eb; padding-top: 24px; margin-top: 24px;">
        <a href="{{ route('admissions.show', $admission) }}" class="btn-primary-custom btn-outline-custom">
          <i class="bi bi-x-circle"></i> Cancel
        </a>
        <button type="submit" class="btn-primary-custom btn-danger-custom">
          <i class="bi bi-box-arrow-right"></i> Discharge Patient
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
