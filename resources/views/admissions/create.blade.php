@extends('layouts.app')

@section('title', 'New Admission')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('ipd.dashboard') }}">IPD</a></li>
<li class="breadcrumb-item"><a href="{{ route('admissions.index') }}">Admissions</a></li>
<li class="breadcrumb-item active" aria-current="page">New Admission</li>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
  .form-section {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
  }

  /* Select2 Custom Styling */
  .select2-container--default .select2-selection--single {
    border: 1px solid #d1d5db;
    border-radius: 8px;
    height: 42px;
    padding: 6px 12px;
  }

  .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 28px;
    color: #111827;
  }

  .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 40px;
  }

  .select2-container--default.select2-container--focus .select2-selection--single {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
  }

  .select2-dropdown {
    border: 1px solid #d1d5db;
    border-radius: 8px;
  }

  .select2-search--dropdown .select2-search__field {
    border: 1px solid #d1d5db;
    border-radius: 6px;
    padding: 8px 12px;
  }

  .select2-results__option--highlighted {
    background-color: #667eea !important;
  }

  .section-title {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 2px solid #e5e7eb;
  }

  .form-label {
    font-size: 13px;
    font-weight: 500;
    color: #374151;
    margin-bottom: 6px;
  }

  .form-control, .form-select {
    border: 1px solid #d1d5db;
    border-radius: 8px;
    padding: 10px 12px;
    font-size: 14px;
  }

  .form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
  }

  .patient-card {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 16px;
  }

  .patient-info {
    font-size: 13px;
    color: #6b7280;
  }

  .patient-name {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 8px;
  }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0">New Patient Admission</h4>
  <a href="{{ route('admissions.index') }}" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-left"></i> Back to Admissions
  </a>
</div>

<form action="{{ route('admissions.store') }}" method="POST">
  @csrf
  
  <!-- Patient Selection -->
  <div class="form-section">
    <h5 class="section-title"><i class="bi bi-person-circle"></i> Patient Information</h5>
    
    <div class="row">
      <div class="col-md-6">
        <div class="mb-3">
          <label for="patient_id" class="form-label">Select Patient <span class="text-danger">*</span></label>
          <select name="patient_id" id="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required onchange="loadPatientInfo(this.value)">
            <option value="">-- Select Patient --</option>
            @foreach($patients as $patient)
              <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                {{ $patient->patient_id }} - {{ $patient->full_name }} ({{ $patient->phone }})
              </option>
            @endforeach
          </select>
          @error('patient_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
      <div class="col-md-6">
        <div id="patientInfoCard" style="display: none;">
          <div class="patient-card">
            <div class="patient-name" id="patientName"></div>
            <div class="patient-info">
              <div><strong>Age:</strong> <span id="patientAge"></span> | <strong>Gender:</strong> <span id="patientGender"></span></div>
              <div><strong>Phone:</strong> <span id="patientPhone"></span></div>
              <div><strong>Blood Group:</strong> <span id="patientBlood"></span></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Ward & Bed Assignment -->
  <div class="form-section">
    <h5 class="section-title"><i class="bi bi-hospital"></i> Ward & Bed Assignment</h5>
    
    <div class="row">
      <div class="col-md-4">
        <div class="mb-3">
          <label for="ward_id" class="form-label">Ward <span class="text-danger">*</span></label>
          <select name="ward_id" id="ward_id" class="form-select @error('ward_id') is-invalid @enderror" required onchange="loadAvailableBeds(this.value)">
            <option value="">-- Select Ward --</option>
            @foreach($wards as $ward)
              <option value="{{ $ward->id }}" {{ old('ward_id') == $ward->id ? 'selected' : '' }}>
                {{ $ward->ward_name }} ({{ $ward->available_beds }} beds available)
              </option>
            @endforeach
          </select>
          @error('ward_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3">
          <label for="bed_id" class="form-label">Bed</label>
          <select name="bed_id" id="bed_id" class="form-select @error('bed_id') is-invalid @enderror">
            <option value="">Select ward first...</option>
          </select>
          <small class="text-muted">Optional - can be assigned later</small>
          @error('bed_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3">
          <label for="doctor_id" class="form-label">Attending Doctor <span class="text-danger">*</span></label>
          <select name="doctor_id" id="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror" required>
            <option value="">-- Select Doctor --</option>
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
  </div>

  <!-- Admission Details -->
  <div class="form-section">
    <h5 class="section-title"><i class="bi bi-file-medical"></i> Admission Details</h5>
    
    <div class="row">
      <div class="col-md-4">
        <div class="mb-3">
          <label for="admission_date" class="form-label">Admission Date & Time <span class="text-danger">*</span></label>
          <input type="datetime-local" name="admission_date" id="admission_date" class="form-control @error('admission_date') is-invalid @enderror" value="{{ old('admission_date', now()->format('Y-m-d\TH:i')) }}" required>
          @error('admission_date')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3">
          <label for="admission_type" class="form-label">Admission Type <span class="text-danger">*</span></label>
          <select name="admission_type" id="admission_type" class="form-select @error('admission_type') is-invalid @enderror" required>
            <option value="">-- Select Type --</option>
            <option value="emergency" {{ old('admission_type') == 'emergency' ? 'selected' : '' }}>Emergency</option>
            <option value="elective" {{ old('admission_type') == 'elective' ? 'selected' : '' }}>Elective/Planned</option>
            <option value="transfer" {{ old('admission_type') == 'transfer' ? 'selected' : '' }}>Transfer</option>
            <option value="delivery" {{ old('admission_type') == 'delivery' ? 'selected' : '' }}>Delivery</option>
          </select>
          @error('admission_type')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3">
          <label for="admission_category" class="form-label">Category <span class="text-danger">*</span></label>
          <select name="admission_category" id="admission_category" class="form-select @error('admission_category') is-invalid @enderror" required>
            <option value="">-- Select Category --</option>
            <option value="medical" {{ old('admission_category') == 'medical' ? 'selected' : '' }}>Medical</option>
            <option value="surgical" {{ old('admission_category') == 'surgical' ? 'selected' : '' }}>Surgical</option>
            <option value="maternity" {{ old('admission_category') == 'maternity' ? 'selected' : '' }}>Maternity</option>
            <option value="pediatric" {{ old('admission_category') == 'pediatric' ? 'selected' : '' }}>Pediatric</option>
            <option value="icu" {{ old('admission_category') == 'icu' ? 'selected' : '' }}>ICU</option>
          </select>
          @error('admission_category')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
    </div>

    <div class="mb-3">
      <label for="reason_for_admission" class="form-label">Reason for Admission <span class="text-danger">*</span></label>
      <textarea name="reason_for_admission" id="reason_for_admission" rows="2" class="form-control @error('reason_for_admission') is-invalid @enderror" required placeholder="Brief reason why patient is being admitted">{{ old('reason_for_admission') }}</textarea>
      @error('reason_for_admission')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <div class="row">
      <div class="col-md-6">
        <div class="mb-3">
          <label for="provisional_diagnosis" class="form-label">Provisional Diagnosis</label>
          <textarea name="provisional_diagnosis" id="provisional_diagnosis" rows="2" class="form-control @error('provisional_diagnosis') is-invalid @enderror" placeholder="Initial diagnosis">{{ old('provisional_diagnosis') }}</textarea>
          @error('provisional_diagnosis')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
      <div class="col-md-6">
        <div class="mb-3">
          <label for="complaints" class="form-label">Chief Complaints</label>
          <textarea name="complaints" id="complaints" rows="2" class="form-control @error('complaints') is-invalid @enderror" placeholder="Patient's main complaints">{{ old('complaints') }}</textarea>
          @error('complaints')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
    </div>

    <div class="mb-3">
      <label for="medical_history" class="form-label">Relevant Medical History</label>
      <textarea name="medical_history" id="medical_history" rows="2" class="form-control @error('medical_history') is-invalid @enderror" placeholder="Past medical conditions, surgeries, etc.">{{ old('medical_history') }}</textarea>
      @error('medical_history')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>
  </div>

  <!-- Vital Signs -->
  <div class="form-section">
    <h5 class="section-title"><i class="bi bi-heart-pulse"></i> Vital Signs at Admission</h5>
    
    <div class="row">
      <div class="col-md-3">
        <div class="mb-3">
          <label for="blood_pressure" class="form-label">Blood Pressure</label>
          <input type="text" name="blood_pressure" id="blood_pressure" class="form-control @error('blood_pressure') is-invalid @enderror" value="{{ old('blood_pressure') }}" placeholder="120/80">
          <small class="text-muted">mmHg</small>
          @error('blood_pressure')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
      <div class="col-md-3">
        <div class="mb-3">
          <label for="temperature" class="form-label">Temperature</label>
          <input type="number" step="0.1" name="temperature" id="temperature" class="form-control @error('temperature') is-invalid @enderror" value="{{ old('temperature') }}" placeholder="98.6">
          <small class="text-muted">°F</small>
          @error('temperature')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
      <div class="col-md-3">
        <div class="mb-3">
          <label for="pulse_rate" class="form-label">Pulse Rate</label>
          <input type="number" name="pulse_rate" id="pulse_rate" class="form-control @error('pulse_rate') is-invalid @enderror" value="{{ old('pulse_rate') }}" placeholder="72">
          <small class="text-muted">bpm</small>
          @error('pulse_rate')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
      <div class="col-md-3">
        <div class="mb-3">
          <label for="respiratory_rate" class="form-label">Respiratory Rate</label>
          <input type="number" name="respiratory_rate" id="respiratory_rate" class="form-control @error('respiratory_rate') is-invalid @enderror" value="{{ old('respiratory_rate') }}" placeholder="16">
          <small class="text-muted">breaths/min</small>
          @error('respiratory_rate')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
      <div class="col-md-3">
        <div class="mb-3">
          <label for="oxygen_saturation" class="form-label">Oxygen Saturation</label>
          <input type="number" name="oxygen_saturation" id="oxygen_saturation" class="form-control @error('oxygen_saturation') is-invalid @enderror" value="{{ old('oxygen_saturation') }}" placeholder="98">
          <small class="text-muted">%</small>
          @error('oxygen_saturation')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
    </div>
  </div>

  <!-- Payment Information -->
  <div class="form-section">
    <h5 class="section-title"><i class="bi bi-credit-card"></i> Payment Information</h5>
    
    <div class="row">
      <div class="col-md-4">
        <div class="mb-3">
          <label for="payment_mode" class="form-label">Payment Mode <span class="text-danger">*</span></label>
          <select name="payment_mode" id="payment_mode" class="form-select @error('payment_mode') is-invalid @enderror" required onchange="toggleInsuranceFields(this.value)">
            <option value="">-- Select Mode --</option>
            <option value="cash" {{ old('payment_mode') == 'cash' ? 'selected' : '' }}>Cash</option>
            <option value="insurance" {{ old('payment_mode') == 'insurance' ? 'selected' : '' }}>Insurance</option>
            <option value="company" {{ old('payment_mode') == 'company' ? 'selected' : '' }}>Company</option>
            <option value="government" {{ old('payment_mode') == 'government' ? 'selected' : '' }}>Government</option>
          </select>
          @error('payment_mode')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3">
          <label for="estimated_stay_days" class="form-label">Estimated Stay</label>
          <input type="number" name="estimated_stay_days" id="estimated_stay_days" class="form-control @error('estimated_stay_days') is-invalid @enderror" value="{{ old('estimated_stay_days') }}" placeholder="3">
          <small class="text-muted">days</small>
          @error('estimated_stay_days')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3">
          <label for="advance_payment" class="form-label">Advance Payment</label>
          <input type="number" step="0.01" name="advance_payment" id="advance_payment" class="form-control @error('advance_payment') is-invalid @enderror" value="{{ old('advance_payment', 0) }}" placeholder="0.00">
          <small class="text-muted">Ksh</small>
          @error('advance_payment')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
    </div>

    <div id="insuranceFields" style="display: {{ old('payment_mode') == 'insurance' ? 'block' : 'none' }};">
      <div class="row">
        <div class="col-md-6">
          <div class="mb-3">
            <label for="insurance_company" class="form-label">Insurance Company</label>
            <input type="text" name="insurance_company" id="insurance_company" class="form-control @error('insurance_company') is-invalid @enderror" value="{{ old('insurance_company') }}" placeholder="Company name">
            @error('insurance_company')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="col-md-6">
          <div class="mb-3">
            <label for="insurance_policy_number" class="form-label">Policy Number</label>
            <input type="text" name="insurance_policy_number" id="insurance_policy_number" class="form-control @error('insurance_policy_number') is-invalid @enderror" value="{{ old('insurance_policy_number') }}" placeholder="Policy/Member number">
            @error('insurance_policy_number')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Form Actions -->
  <div class="d-flex gap-2 justify-content-end">
    <a href="{{ route('admissions.index') }}" class="btn btn-secondary">
      <i class="bi bi-x-circle"></i> Cancel
    </a>
    <button type="submit" class="btn btn-primary">
      <i class="bi bi-check-circle"></i> Admit Patient
    </button>
  </div>
</form>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  // Initialize Select2 on patient and doctor dropdowns
  $(document).ready(function() {
    $('#patient_id').select2({
      placeholder: '-- Search for a patient --',
      allowClear: true,
      width: '100%'
    });

    $('#doctor_id').select2({
      placeholder: '-- Select Doctor --',
      allowClear: true,
      width: '100%'
    });

    $('#ward_id').select2({
      placeholder: '-- Select Ward --',
      allowClear: true,
      width: '100%'
    });
  });

  // Load available beds when ward is selected
  function loadAvailableBeds(wardId) {
    const bedSelect = document.getElementById('bed_id');
    
    if (!wardId) {
      bedSelect.innerHTML = '<option value="">Select ward first...</option>';
      return;
    }
    
    bedSelect.innerHTML = '<option value="">Loading...</option>';
    
    fetch(`/api/wards/${wardId}/available-beds`)
      .then(response => response.json())
      .then(data => {
        if (data.beds && data.beds.length > 0) {
          bedSelect.innerHTML = '<option value="">None (assign later)</option>';
          data.beds.forEach(bed => {
            const option = document.createElement('option');
            option.value = bed.id;
            option.textContent = `${bed.bed_number} - ${bed.bed_type}`;
            bedSelect.appendChild(option);
          });
        } else {
          bedSelect.innerHTML = '<option value="">No beds available in this ward</option>';
        }
      })
      .catch(error => {
        console.error('Error loading beds:', error);
        bedSelect.innerHTML = '<option value="">Error loading beds</option>';
      });
  }

  // Toggle insurance fields based on payment mode
  function toggleInsuranceFields(paymentMode) {
    const insuranceFields = document.getElementById('insuranceFields');
    if (paymentMode === 'insurance') {
      insuranceFields.style.display = 'block';
    } else {
      insuranceFields.style.display = 'none';
    }
  }

  // Load patient info when selected
  function loadPatientInfo(patientId) {
    if (!patientId) {
      document.getElementById('patientInfoCard').style.display = 'none';
      return;
    }

    // Find patient in the select options
    const select = document.getElementById('patient_id');
    const option = select.options[select.selectedIndex];
    const text = option.text;
    
    // Show the card (we'll populate with basic info from the option text)
    document.getElementById('patientInfoCard').style.display = 'block';
    
    // You could also make an AJAX call here to get full patient details
    // For now, we'll just show basic info
  }
</script>
@endpush
@endsection
