@extends('layouts.app')

@section('title', 'Reschedule Appointment - ' . $appointment->appointment_number)

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('appointments.index') }}">Appointments</a></li>
<li class="breadcrumb-item"><a href="{{ route('appointments.show', $appointment) }}">{{ $appointment->appointment_number }}</a></li>
<li class="breadcrumb-item active" aria-current="page">Reschedule</li>
@endsection

@push('styles')
<style>
  .page-header {
    background: linear-gradient(135deg, var(--hms-purple), var(--hms-primary));
    border-radius: 16px;
    padding: 30px;
    color: white;
    margin-bottom: 30px;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
  }

  .form-card {
    background: white;
    border-radius: 16px;
    padding: 30px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
  }

  [data-theme="dark"] .form-card {
    background: #1a202c;
    border-color: #2d3748;
  }

  .form-section {
    margin-bottom: 30px;
    padding-bottom: 30px;
    border-bottom: 2px solid #f7fafc;
  }

  [data-theme="dark"] .form-section {
    border-bottom-color: #2d3748;
  }

  .form-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
  }

  .section-title {
    font-size: 18px;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  [data-theme="dark"] .section-title {
    color: #e2e8f0;
  }

  .section-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--hms-purple), var(--hms-primary));
    color: white;
    font-size: 18px;
  }

  .form-label {
    font-weight: 600;
    color: #4a5568;
    margin-bottom: 8px;
  }

  [data-theme="dark"] .form-label {
    color: #cbd5e0;
  }

  .form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    padding: 10px 14px;
    transition: all 0.3s ease;
  }

  [data-theme="dark"] .form-control,
  [data-theme="dark"] .form-select {
    background: #2d3748;
    border-color: #4a5568;
    color: #e2e8f0;
  }

  .form-control:focus, .form-select:focus {
    border-color: var(--hms-primary);
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
  }

  .btn-action {
    padding: 12px 30px;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s ease;
  }

  .btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  }

  .alert-info {
    background: #e0f2fe;
    border-color: #0ea5e9;
    color: #075985;
    border-radius: 12px;
    padding: 16px;
  }

  [data-theme="dark"] .alert-info {
    background: #164e63;
    border-color: #0891b2;
    color: #cffafe;
  }

  .current-info {
    background: #f8fafc;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
  }

  [data-theme="dark"] .current-info {
    background: #2d3748;
  }

  .info-item {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #e2e8f0;
  }

  [data-theme="dark"] .info-item {
    border-bottom-color: #4a5568;
  }

  .info-item:last-child {
    border-bottom: none;
  }

  .info-label {
    font-weight: 600;
    color: #718096;
  }

  [data-theme="dark"] .info-label {
    color: #a0aec0;
  }

  .info-value {
    color: #2d3748;
    font-weight: 500;
  }

  [data-theme="dark"] .info-value {
    color: #e2e8f0;
  }
</style>
@endpush

@section('content')
<!-- Page Header -->
<div class="page-header">
  <h1 style="font-size: 28px; font-weight: 700; margin-bottom: 8px;">
    <i class="bi bi-calendar-event me-2"></i>Reschedule Appointment
  </h1>
  <p style="font-size: 16px; opacity: 0.9; margin: 0;">
    Update the appointment details for {{ $appointment->patient->full_name }}
  </p>
</div>

<div class="row justify-content-center">
  <div class="col-lg-10">
    <!-- Current Appointment Info -->
    <div class="alert alert-info mb-4">
      <div class="d-flex align-items-center">
        <i class="bi bi-info-circle fs-4 me-3"></i>
        <div>
          <strong>Current Appointment:</strong> {{ $appointment->appointment_number }} | 
          Token: <strong>{{ $appointment->token_number }}</strong> | 
          Status: <strong>{{ ucfirst(str_replace('-', ' ', $appointment->status)) }}</strong>
        </div>
      </div>
    </div>

    <div class="form-card">
      <form action="{{ route('appointments.update', $appointment) }}" method="POST" id="rescheduleForm">
        @csrf
        @method('PUT')

        <!-- Current Details -->
        <div class="form-section">
          <h3 class="section-title">
            <div class="section-icon">
              <i class="bi bi-info-circle"></i>
            </div>
            Current Details
          </h3>
          <div class="current-info">
            <div class="info-item">
              <span class="info-label">Patient:</span>
              <span class="info-value">{{ $appointment->patient->full_name }} ({{ $appointment->patient->patient_id }})</span>
            </div>
            <div class="info-item">
              <span class="info-label">Doctor:</span>
              <span class="info-value">Dr. {{ $appointment->doctor->name }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">Current Date & Time:</span>
              <span class="info-value">{{ $appointment->appointment_date->format('F d, Y') }} at {{ $appointment->appointment_time->format('h:i A') }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">Type:</span>
              <span class="info-value">{{ ucfirst($appointment->appointment_type) }}</span>
            </div>
          </div>
        </div>

        <!-- New Schedule -->
        <div class="form-section">
          <h3 class="section-title">
            <div class="section-icon">
              <i class="bi bi-calendar-check"></i>
            </div>
            New Schedule
          </h3>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="patient_id" class="form-label">Patient <span class="text-danger">*</span></label>
              <select name="patient_id" id="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                <option value="{{ $appointment->patient_id }}" selected>{{ $appointment->patient->full_name }} ({{ $appointment->patient->patient_id }})</option>
                @foreach($patients as $patient)
                  @if($patient->id !== $appointment->patient_id)
                    <option value="{{ $patient->id }}">{{ $patient->full_name }} ({{ $patient->patient_id }})</option>
                  @endif
                @endforeach
              </select>
              @error('patient_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6 mb-3">
              <label for="doctor_id" class="form-label">Doctor <span class="text-danger">*</span></label>
              <select name="doctor_id" id="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror" required>
                <option value="{{ $appointment->doctor_id }}" selected>Dr. {{ $appointment->doctor->name }}</option>
                @foreach($doctors as $doctor)
                  @if($doctor->id !== $appointment->doctor_id)
                    <option value="{{ $doctor->id }}">Dr. {{ $doctor->name }}</option>
                  @endif
                @endforeach
              </select>
              @error('doctor_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6 mb-3">
              <label for="appointment_date" class="form-label">Appointment Date <span class="text-danger">*</span></label>
              <input type="date" 
                     name="appointment_date" 
                     id="appointment_date" 
                     class="form-control @error('appointment_date') is-invalid @enderror" 
                     value="{{ old('appointment_date', $appointment->appointment_date->format('Y-m-d')) }}"
                     min="{{ date('Y-m-d') }}"
                     required>
              @error('appointment_date')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6 mb-3">
              <label for="appointment_time" class="form-label">Appointment Time <span class="text-danger">*</span></label>
              <input type="time" 
                     name="appointment_time" 
                     id="appointment_time" 
                     class="form-control @error('appointment_time') is-invalid @enderror" 
                     value="{{ old('appointment_time', $appointment->appointment_time->format('H:i')) }}"
                     required>
              @error('appointment_time')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6 mb-3">
              <label for="appointment_type" class="form-label">Appointment Type <span class="text-danger">*</span></label>
              <select name="appointment_type" id="appointment_type" class="form-select @error('appointment_type') is-invalid @enderror" required>
                <option value="new" {{ old('appointment_type', $appointment->appointment_type) === 'new' ? 'selected' : '' }}>New Appointment</option>
                <option value="followup" {{ old('appointment_type', $appointment->appointment_type) === 'followup' ? 'selected' : '' }}>Follow-up</option>
                <option value="emergency" {{ old('appointment_type', $appointment->appointment_type) === 'emergency' ? 'selected' : '' }}>Emergency</option>
              </select>
              @error('appointment_type')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>

        <!-- Visit Details -->
        <div class="form-section">
          <h3 class="section-title">
            <div class="section-icon">
              <i class="bi bi-file-text"></i>
            </div>
            Visit Details
          </h3>
          <div class="row">
            <div class="col-12 mb-3">
              <label for="reason_for_visit" class="form-label">Reason for Visit</label>
              <textarea name="reason_for_visit" 
                        id="reason_for_visit" 
                        class="form-control @error('reason_for_visit') is-invalid @enderror" 
                        rows="3"
                        placeholder="Enter the reason for this appointment...">{{ old('reason_for_visit', $appointment->reason_for_visit) }}</textarea>
              @error('reason_for_visit')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-12 mb-3">
              <label for="notes" class="form-label">Additional Notes</label>
              <textarea name="notes" 
                        id="notes" 
                        class="form-control @error('notes') is-invalid @enderror" 
                        rows="3"
                        placeholder="Any additional notes or special instructions...">{{ old('notes', $appointment->notes) }}</textarea>
              @error('notes')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex gap-2 justify-content-end">
          <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-secondary btn-action">
            <i class="bi bi-x-circle me-2"></i>Cancel
          </a>
          <button type="submit" class="btn btn-primary btn-action">
            <i class="bi bi-check-circle me-2"></i>Update Appointment
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('rescheduleForm');
    const dateInput = document.getElementById('appointment_date');
    const timeInput = document.getElementById('appointment_time');
    
    // Validate date is not in the past
    dateInput.addEventListener('change', function() {
      const selectedDate = new Date(this.value);
      const today = new Date();
      today.setHours(0, 0, 0, 0);
      
      if (selectedDate < today) {
        alert('Please select a future date for the appointment.');
        this.value = '';
      }
    });

    // Form submission confirmation
    form.addEventListener('submit', function(e) {
      const confirmed = confirm('Are you sure you want to reschedule this appointment?');
      if (!confirmed) {
        e.preventDefault();
      }
    });
  });
</script>
@endpush
