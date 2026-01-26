@extends('layouts.app')

@section('title', 'Book New Appointment')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('appointments.index') }}">Appointments</a></li>
<li class="breadcrumb-item active" aria-current="page">Book Appointment</li>
@endsection

@push('styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<style>
  .form-card {
    background: white;
    border-radius: 16px;
    padding: 28px;
    border: 1px solid #e2e8f0;
    margin-bottom: 20px;
    transition: all 0.3s ease;
  }

  [data-theme="dark"] .form-card {
    background: #1a202c;
    border-color: #2d3748;
  }

  .form-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  }

  .section-title {
    font-size: 18px;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 2px solid #f7fafc;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  [data-theme="dark"] .section-title {
    color: #e2e8f0;
    border-bottom-color: #2d3748;
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

  .page-header {
    background: transparent;
    margin-bottom: 25px;
  }

  .page-title {
    font-size: 28px;
    font-weight: 700;
    color: #2d3748;
    margin: 0;
  }

  [data-theme="dark"] .page-title {
    color: #e2e8f0;
  }

  .form-label {
    font-weight: 500;
    color: #4a5568;
    margin-bottom: 8px;
  }

  [data-theme="dark"] .form-label {
    color: #cbd5e0;
  }

  .required::after {
    content: '*';
    color: #dc3545;
    margin-left: 4px;
  }

  .form-control:focus, .form-select:focus {
    border-color: var(--hms-purple);
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
  }

  .btn-submit {
    background: linear-gradient(135deg, var(--hms-purple), var(--hms-primary));
    border: none;
    padding: 12px 32px;
    font-weight: 600;
    transition: all 0.3s ease;
  }

  .btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
  }

  .time-slot-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    gap: 10px;
    margin-top: 10px;
  }

  .time-slot {
    padding: 10px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s ease;
    background: white;
  }

  [data-theme="dark"] .time-slot {
    background: #2d3748;
    border-color: #4a5568;
    color: #e2e8f0;
  }

  .time-slot:hover {
    border-color: var(--hms-purple);
    background: #f7fafc;
  }

  [data-theme="dark"] .time-slot:hover {
    background: #4a5568;
  }

  .time-slot.selected {
    border-color: var(--hms-purple);
    background: var(--hms-purple);
    color: white;
  }

  .time-slot.disabled {
    opacity: 0.4;
    cursor: not-allowed;
  }

  .info-alert {
    background: #e0e7ff;
    border: 1px solid #818cf8;
    border-radius: 8px;
    padding: 12px 16px;
    color: #3730a3;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  [data-theme="dark"] .info-alert {
    background: rgba(129, 140, 248, 0.1);
    color: #a5b4fc;
  }
</style>
@endpush

@section('content')
<div class="page-header">
  <h1 class="page-title">
    <i class="bi bi-calendar-plus text-primary me-2"></i>
    Book New Appointment
  </h1>
</div>

<form action="{{ route('appointments.store') }}" method="POST" class="form-loading">
  @csrf
  
  <!-- Patient & Doctor Selection -->
  <div class="form-card">
    <h3 class="section-title">
      <div class="section-icon">
        <i class="bi bi-people"></i>
      </div>
      Patient & Doctor Information
    </h3>

    @if($patients->isEmpty())
      <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i>
        <strong>No patients available.</strong> All active patients currently have ongoing appointments. Please complete or cancel existing appointments to book new ones.
      </div>
    @else
      <div class="info-alert mb-3">
        <i class="bi bi-info-circle"></i>
        <span>Only patients without active appointments are shown. Patients with scheduled, waiting, or in-consultation appointments are excluded.</span>
      </div>
    @endif
    
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label required">Select Patient</label>
        <select name="patient_id" id="patientSelect" class="form-select @error('patient_id') is-invalid @enderror" required data-placeholder="Search patient by name, ID, or phone...">
          <option value="">Search patient...</option>
          @foreach($patients as $patient)
            <option value="{{ $patient->id }}" 
                    {{ old('patient_id') == $patient->id ? 'selected' : '' }}
                    {{ request('patient_id') == $patient->id ? 'selected' : '' }}>
              {{ $patient->full_name }} - {{ $patient->patient_id }} ({{ $patient->phone }})
            </option>
          @endforeach
        </select>
        @error('patient_id')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-6 mb-3">
        <label class="form-label required">Select Doctor</label>
        <select name="doctor_id" id="doctorSelect" class="form-select @error('doctor_id') is-invalid @enderror" required>
          <option value="">Choose a doctor...</option>
          @foreach($doctors as $doctor)
            <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
              Dr. {{ $doctor->name }}
            </option>
          @endforeach
        </select>
        @error('doctor_id')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
    </div>
  </div>

  <!-- Appointment Details -->
  <div class="form-card">
    <h3 class="section-title">
      <div class="section-icon">
        <i class="bi bi-calendar-event"></i>
      </div>
      Appointment Details
    </h3>
    
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label required">Appointment Date</label>
        <input type="date" name="appointment_date" id="appointmentDate" 
               class="form-control @error('appointment_date') is-invalid @enderror" 
               value="{{ old('appointment_date', date('Y-m-d')) }}" 
               min="{{ date('Y-m-d') }}" required>
        @error('appointment_date')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-6 mb-3">
        <label class="form-label required">Appointment Time</label>
        <input type="time" name="appointment_time" id="appointmentTime"
               class="form-control @error('appointment_time') is-invalid @enderror" 
               value="{{ old('appointment_time', '09:00') }}" required>
        @error('appointment_time')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="info-alert mt-2">
          <i class="bi bi-info-circle"></i>
          <span>Working hours: 9:00 AM - 5:00 PM</span>
        </div>
      </div>

      <div class="col-md-6 mb-3">
        <label class="form-label required">Appointment Type</label>
        <select name="appointment_type" class="form-select @error('appointment_type') is-invalid @enderror" required>
          <option value="">Select type...</option>
          <option value="new" {{ old('appointment_type') == 'new' ? 'selected' : '' }}>New Consultation</option>
          <option value="followup" {{ old('appointment_type') == 'followup' ? 'selected' : '' }}>Follow-up</option>
          <option value="emergency" {{ old('appointment_type') == 'emergency' ? 'selected' : '' }}>Emergency</option>
        </select>
        @error('appointment_type')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-12 mb-3">
        <label class="form-label">Reason for Visit</label>
        <textarea name="reason_for_visit" rows="3" 
                  class="form-control @error('reason_for_visit') is-invalid @enderror" 
                  placeholder="Brief description of symptoms or reason for consultation...">{{ old('reason_for_visit') }}</textarea>
        @error('reason_for_visit')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-12 mb-3">
        <label class="form-label">Additional Notes</label>
        <textarea name="notes" rows="2" 
                  class="form-control @error('notes') is-invalid @enderror" 
                  placeholder="Any special instructions or notes...">{{ old('notes') }}</textarea>
        @error('notes')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
    </div>
  </div>

  <!-- Form Actions -->
  <div class="d-flex gap-2 justify-content-end">
    <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">
      <i class="bi bi-x-circle me-2"></i>Cancel
    </a>
    <button type="submit" class="btn btn-primary btn-submit">
      <i class="bi bi-check-circle me-2"></i>Book Appointment
    </button>
  </div>
</form>

@push('scripts')
<!-- jQuery (required for Select2) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
// Initialize Select2 for patient dropdown
$(document).ready(function() {
  $('#patientSelect').select2({
    theme: 'bootstrap-5',
    width: '100%',
    placeholder: 'Search patient by name, ID, or phone...',
    allowClear: true,
    matcher: function(params, data) {
      // If there are no search terms, return all data
      if ($.trim(params.term) === '') {
        return data;
      }

      // Don't search on the first empty option
      if (!data.text) {
        return null;
      }

      // Search in text (case-insensitive)
      if (data.text.toUpperCase().indexOf(params.term.toUpperCase()) > -1) {
        return data;
      }

      // Return `null` if the term should not be displayed
      return null;
    }
  });

  // Initialize Select2 for doctor dropdown
  $('#doctorSelect').select2({
    theme: 'bootstrap-5',
    width: '100%',
    placeholder: 'Select a doctor...',
    allowClear: true
  });
});

// Quick time slot selection (optional enhancement)
document.getElementById('appointmentDate')?.addEventListener('change', function() {
  // Could add logic here to fetch available time slots for the selected doctor/date
  console.log('Date changed:', this.value);
});

document.getElementById('doctorSelect')?.addEventListener('change', function() {
  // Could add logic to show doctor's available slots
  console.log('Doctor changed:', this.value);
});
</script>
@endpush
@endsection
