@extends('layouts.app')

@section('title', 'Consultation Details')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('consultations.index') }}">Consultations</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ $consultation->consultation_number }}</li>
@endsection

@push('styles')
<style>
  /* Modern Minimalistic Design */
  .consultation-header {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
  }

  .header-title {
    font-size: 20px;
    font-weight: 600;
    color: #111827;
    margin: 0 0 4px 0;
  }

  .header-subtitle {
    font-size: 14px;
    color: #6b7280;
    margin: 0;
  }

  .nav-tabs {
    border-bottom: 1px solid #e5e7eb;
  }

  .nav-tabs .nav-link {
    color: #6b7280;
    font-weight: 500;
    border: none;
    border-bottom: 2px solid transparent;
    padding: 12px 20px;
    font-size: 14px;
  }

  .nav-tabs .nav-link:hover {
    border-bottom-color: #667eea;
    color: #667eea;
  }

  .nav-tabs .nav-link.active {
    color: #667eea;
    border-bottom-color: #667eea;
    background: transparent;
  }

  .section-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    border: 1px solid #e5e7eb;
  }

  .section-title {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .section-title i {
    color: #667eea;
    font-size: 18px;
  }

  .data-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #f3f4f6;
  }

  .data-row:last-child {
    border-bottom: none;
  }

  .data-label {
    color: #6b7280;
    font-weight: 500;
    font-size: 14px;
  }

  .data-value {
    color: #111827;
    font-weight: 600;
    font-size: 14px;
  }

  .vitals-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 16px;
    margin-top: 16px;
  }

  .vital-box {
    background: #f9fafb;
    padding: 16px;
    border-radius: 10px;
    text-align: center;
    border: 1px solid #e5e7eb;
  }

  .vital-label {
    font-size: 11px;
    color: #6b7280;
    text-transform: uppercase;
    font-weight: 600;
    margin-bottom: 8px;
    letter-spacing: 0.5px;
  }

  .vital-value {
    font-size: 24px;
    font-weight: 700;
    color: #111827;
  }

  .text-content {
    background: #f9fafb;
    padding: 16px;
    border-radius: 8px;
    margin-top: 12px;
    line-height: 1.6;
    color: #374151;
    font-size: 14px;
  }

  .prescription-table {
    width: 100%;
    margin-top: 12px;
    border-collapse: collapse;
  }

  .prescription-table th {
    background: #f9fafb;
    padding: 12px;
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .prescription-table td {
    padding: 12px;
    font-size: 14px;
    color: #374151;
    border-bottom: 1px solid #f3f4f6;
  }

  .prescription-table tr:last-child td {
    border-bottom: none;
  }

  .lab-order-card {
    background: #f9fafb;
    border-radius: 10px;
    padding: 16px;
    margin-bottom: 12px;
    border: 1px solid #e5e7eb;
  }

  .lab-order-card.reported {
    background: #f0fdf4;
    border-color: #d1fae5;
  }

  .result-box {
    background: white;
    border-radius: 8px;
    padding: 12px;
    margin-top: 8px;
    border: 1px solid #e5e7eb;
  }

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

  .empty-state p {
    font-size: 14px;
    margin: 0;
  }

  .badge {
    font-size: 11px;
    padding: 4px 8px;
    font-weight: 600;
  }

  .bg-success { background: #d1fae5 !important; color: #065f46 !important; }
  .bg-warning { background: #fef3c7 !important; color: #92400e !important; }
  .bg-info { background: #dbeafe !important; color: #1e40af !important; }
  .bg-primary { background: #e0e7ff !important; color: #3730a3 !important; }
</style>
@endpush

@section('content')
<div class="container-fluid">
  <!-- Consultation Header -->
  <div class="consultation-header">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
      <div>
        <h1 class="header-title">{{ $consultation->consultation_number }}</h1>
        <p class="header-subtitle">
          {{ $consultation->patient->full_name }} ({{ $consultation->patient->patient_id }}) | {{ $consultation->created_at->format('F d, Y h:i A') }}
        </p>
        <div class="d-flex gap-2 flex-wrap mt-2">
          <span class="badge bg-{{ $consultation->status === 'completed' ? 'success' : 'warning' }}">
            {{ ucfirst($consultation->status) }}
          </span>
          @if(isset($pendingLabResults) && $pendingLabResults > 0)
            <span class="badge bg-warning" style="cursor: pointer;" onclick="document.getElementById('lab-results-tab').click()">
              <i class="bi bi-hourglass-split"></i> {{ $pendingLabResults }} Lab Test(s) Pending
            </span>
        @endif
        @if(isset($reportedLabResults) && $reportedLabResults > 0)
          <span class="badge bg-success" style="font-size: 14px; padding: 6px 12px; cursor: pointer;" onclick="document.getElementById('lab-results-tab').click()">
            <i class="bi bi-check-circle"></i> {{ $reportedLabResults }} Result(s) Ready - Click to View
          </span>
          @endif
          @if(isset($hasPrescription) && $hasPrescription)
            <span class="badge bg-info" style="cursor: pointer;" onclick="document.getElementById('prescriptions-tab').click()">
              <i class="bi bi-prescription2"></i> Prescription Available
            </span>
          @endif
        </div>
      </div>
      <div class="d-flex gap-2 flex-wrap">
        @if($consultation->status === 'in-progress')
          <a href="{{ route('consultations.edit', $consultation) }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-pencil me-2"></i>Continue Editing
          </a>
          
          @if(isset($pendingLabResults) && $pendingLabResults > 0)
            <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('There are {{ $pendingLabResults }} pending lab tests. Are you sure you want to complete this consultation?')" form="complete-form">
              <i class="bi bi-exclamation-triangle me-2"></i>Complete (Pending Tests)
            </button>
          @else
            <button type="submit" class="btn btn-success btn-sm" form="complete-form">
              <i class="bi bi-check-circle me-2"></i>Complete Consultation
            </button>
          @endif
          
          <form id="complete-form" action="{{ route('consultations.complete', $consultation) }}" method="POST" class="d-inline">
            @csrf
          </form>

          <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#admitPatientModal">
            <i class="bi bi-hospital me-2"></i>Admit Patient
          </button>
        @elseif($consultation->status === 'completed')
          @if((isset($reportedLabResults) && $reportedLabResults > 0) || (isset($pendingLabResults) && $pendingLabResults > 0))
            <form action="{{ route('consultations.resume', $consultation) }}" method="POST" class="d-inline">
              @csrf
              <button type="submit" class="btn btn-warning btn-sm">
                <i class="bi bi-arrow-clockwise me-2"></i>Resume & Review Results
              </button>
            </form>
          @endif
        @endif
        
        <a href="{{ route('doctor.appointments') }}" class="btn btn-outline-secondary btn-sm">
          <i class="bi bi-arrow-left me-2"></i>Back
        </a>
      </div>
    </div>
  </div>

  <!-- Tabbed Interface -->
  <div class="card border-0" style="background: white; border: 1px solid #e5e7eb; border-radius: 12px;">
    <div class="card-header bg-white border-0 pt-3">
      <ul class="nav nav-tabs" id="consultationTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
            <i class="bi bi-file-medical"></i> Consultation Details
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="lab-results-tab" data-bs-toggle="tab" data-bs-target="#lab-results" type="button" role="tab">
            <i class="bi bi-clipboard-pulse"></i> Lab Tests & Results
            @if(isset($reportedLabResults) && $reportedLabResults > 0)
              <span class="badge bg-success ms-1">{{ $reportedLabResults }}</span>
            @endif
            @if(isset($pendingLabResults) && $pendingLabResults > 0)
              <span class="badge bg-warning ms-1">{{ $pendingLabResults }}</span>
            @endif
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="prescriptions-tab" data-bs-toggle="tab" data-bs-target="#prescriptions" type="button" role="tab">
            <i class="bi bi-prescription2"></i> Prescriptions
            @if($consultation->prescriptions->count() > 0)
              <span class="badge bg-info ms-1">{{ $consultation->prescriptions->count() }}</span>
          @endif
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="patient-info-tab" data-bs-toggle="tab" data-bs-target="#patient-info" type="button" role="tab">
          <i class="bi bi-person"></i> Patient Information
        </button>
      </li>
    </ul>
  </div>
  
  <div class="card-body p-4">
    <div class="tab-content" id="consultationTabContent">
      
      <!-- TAB 1: Consultation Overview -->
      <div class="tab-pane fade show active" id="overview" role="tabpanel">
        @include('consultations.tabs.overview')
      </div>
      
      <!-- TAB 2: Lab Results -->
      <div class="tab-pane fade" id="lab-results" role="tabpanel">
        @include('consultations.tabs.lab-results')
      </div>
      
      <!-- TAB 3: Prescriptions -->
      <div class="tab-pane fade" id="prescriptions" role="tabpanel">
        @include('consultations.tabs.prescriptions')
      </div>
      
      <!-- TAB 4: Patient Info -->
      <div class="tab-pane fade" id="patient-info" role="tabpanel">
        @include('consultations.tabs.patient-info')
      </div>
      
    </div>
  </div>
</div>

<!-- Admit Patient Modal -->
<div class="modal fade" id="admitPatientModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="{{ route('consultations.admit', $consultation) }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title"><i class="bi bi-hospital"></i> Admit Patient to IPD</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Admitting: <strong>{{ $consultation->patient->full_name }}</strong>
          </div>
          
          <!-- Ward & Bed Selection -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="ward_id" class="form-label">Ward <span class="text-danger">*</span></label>
              <select name="ward_id" id="ward_id" class="form-select" required onchange="loadAvailableBeds(this.value)">
                <option value="">Select ward...</option>
                @foreach(\App\Models\Ward::where('is_active', true)->get() as $ward)
                  <option value="{{ $ward->id }}">{{ $ward->ward_name }} ({{ $ward->available_beds }} available)</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label for="bed_id" class="form-label">Bed</label>
              <select name="bed_id" id="bed_id" class="form-select">
                <option value="">Select ward first...</option>
              </select>
              <small class="text-muted">Optional - can be assigned later</small>
            </div>
          </div>
          
          <!-- Admission Details -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="admission_type" class="form-label">Admission Type <span class="text-danger">*</span></label>
              <select name="admission_type" id="admission_type" class="form-select" required>
                <option value="">Select type...</option>
                <option value="emergency">Emergency</option>
                <option value="elective">Elective/Planned</option>
                <option value="transfer">Transfer</option>
                <option value="delivery">Delivery</option>
              </select>
            </div>
            <div class="col-md-6">
              <label for="admission_category" class="form-label">Category <span class="text-danger">*</span></label>
              <select name="admission_category" id="admission_category" class="form-select" required>
                <option value="">Select category...</option>
                <option value="medical">Medical</option>
                <option value="surgical">Surgical</option>
                <option value="maternity">Maternity</option>
                <option value="pediatric">Pediatric</option>
                <option value="icu">ICU</option>
              </select>
            </div>
          </div>
          
          <!-- Clinical Information -->
          <div class="mb-3">
            <label for="diagnosis" class="form-label">Provisional Diagnosis <span class="text-danger">*</span></label>
            <textarea name="diagnosis" id="diagnosis" class="form-control" rows="2" required>{{ $consultation->diagnosis ?? '' }}</textarea>
          </div>
          
          <div class="mb-3">
            <label for="chief_complaints" class="form-label">Chief Complaints <span class="text-danger">*</span></label>
            <textarea name="chief_complaints" id="chief_complaints" class="form-control" rows="2" required>{{ $consultation->appointment->chief_complaint ?? '' }}</textarea>
          </div>
          
          <div class="mb-3">
            <label for="reason_for_admission" class="form-label">Reason for Admission <span class="text-danger">*</span></label>
            <textarea name="reason_for_admission" id="reason_for_admission" class="form-control" rows="2" required placeholder="Detailed reason why patient needs admission"></textarea>
          </div>
          
          <!-- Payment Details -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="payment_mode" class="form-label">Payment Mode <span class="text-danger">*</span></label>
              <select name="payment_mode" id="payment_mode" class="form-select" required onchange="toggleInsuranceFields(this.value)">
                <option value="">Select payment mode...</option>
                <option value="cash">Cash</option>
                <option value="insurance">Insurance</option>
                <option value="company">Company</option>
                <option value="government">Government</option>
              </select>
            </div>
            <div class="col-md-6">
              <label for="advance_payment" class="form-label">Advance Payment</label>
              <input type="number" name="advance_payment" id="advance_payment" class="form-control" step="0.01" min="0" placeholder="0.00">
            </div>
          </div>
          
          <!-- Insurance Fields (shown when payment_mode is Insurance) -->
          <div id="insuranceFields" style="display: none;">
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="insurance_company" class="form-label">Insurance Company</label>
                <input type="text" name="insurance_company" id="insurance_company" class="form-control" placeholder="Company name">
              </div>
              <div class="col-md-6">
                <label for="insurance_policy_number" class="form-label">Policy Number</label>
                <input type="text" name="insurance_policy_number" id="insurance_policy_number" class="form-control" placeholder="Policy number">
              </div>
            </div>
          </div>
          
          <div class="mb-3">
            <label for="estimated_stay_days" class="form-label">Estimated Stay (days)</label>
            <input type="number" name="estimated_stay_days" id="estimated_stay_days" class="form-control" min="1" placeholder="3">
          </div>
          
          <div class="alert alert-warning">
            <small><i class="bi bi-exclamation-triangle"></i> This will complete the consultation and create an IPD admission record.</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">
            <i class="bi bi-hospital"></i> Admit Patient
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
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
      document.getElementById('insurance_company').required = true;
      document.getElementById('insurance_policy_number').required = true;
    } else {
      insuranceFields.style.display = 'none';
      document.getElementById('insurance_company').required = false;
      document.getElementById('insurance_policy_number').required = false;
    }
  }
</script>
@endpush

@endsection
