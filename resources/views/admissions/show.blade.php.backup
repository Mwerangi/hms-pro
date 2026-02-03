@extends('layouts.app')

@section('title', 'Admission Details')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('ipd.dashboard') }}">IPD</a></li>
<li class="breadcrumb-item"><a href="{{ route('admissions.index') }}">Admissions</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ $admission->admission_number }}</li>
@endsection

@push('styles')
<style>
  .admission-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px;
    padding: 32px;
    margin-bottom: 24px;
  }

  .info-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
  }

  .info-label {
    font-size: 12px;
    font-weight: 500;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
  }

  .info-value {
    font-size: 14px;
    color: #111827;
    font-weight: 500;
  }

  .section-title {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 16px;
    padding-bottom: 8px;
    border-bottom: 2px solid #e5e7eb;
  }

  .vital-item {
    background: #f9fafb;
    padding: 12px;
    border-radius: 8px;
    text-align: center;
  }

  .vital-label {
    font-size: 11px;
    color: #6b7280;
    margin-bottom: 4px;
  }

  .vital-value {
    font-size: 18px;
    font-weight: 600;
    color: #111827;
  }

  .vital-unit {
    font-size: 11px;
    color: #9ca3af;
  }
</style>
@endpush

@section('content')
<!-- Admission Header -->
<div class="admission-header">
  <div class="d-flex justify-content-between align-items-start">
    <div>
      <h4 class="mb-2">{{ $admission->admission_number }}</h4>
      <div class="d-flex align-items-center gap-3">
        <div>
          <i class="bi bi-person-circle"></i>
          <strong>{{ $admission->patient->full_name }}</strong>
        </div>
        <div>
          <i class="bi bi-calendar3"></i>
          {{ $admission->patient->age }} years, {{ ucfirst($admission->patient->gender) }}
        </div>
        <div>
          <i class="bi bi-telephone"></i>
          {{ $admission->patient->phone }}
        </div>
      </div>
    </div>
    <div class="text-end">
      @if($admission->status === 'Admitted')
        <span class="badge bg-success px-3 py-2" style="font-size: 14px;">
          <i class="bi bi-check-circle"></i> Currently Admitted
        </span>
      @elseif($admission->status === 'Discharged')
        <span class="badge bg-secondary px-3 py-2" style="font-size: 14px;">
          <i class="bi bi-box-arrow-right"></i> Discharged
        </span>
      @endif
      <div class="mt-2">
        <small>Duration: {{ $admission->duration }}</small>
      </div>
    </div>
  </div>
</div>

<!-- Action Buttons -->
<div class="d-flex gap-2 mb-4">
  @if($admission->status === 'Admitted')
    <a href="{{ route('admissions.edit', $admission) }}" class="btn btn-primary">
      <i class="bi bi-pencil"></i> Edit Admission
    </a>
    <a href="{{ route('admissions.discharge-form', $admission) }}" class="btn btn-danger">
      <i class="bi bi-box-arrow-right"></i> Discharge Patient
    </a>
    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#transferModal">
      <i class="bi bi-arrow-left-right"></i> Transfer Ward/Bed
    </button>
  @endif
  <a href="{{ route('admissions.index') }}" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-left"></i> Back to Admissions
  </a>
</div>

<div class="row">
  <!-- Left Column -->
  <div class="col-md-8">
    <!-- Admission Information -->
    <div class="info-card">
      <h5 class="section-title">Admission Information</h5>
      <div class="row">
        <div class="col-md-4 mb-3">
          <div class="info-label">Admission Date</div>
          <div class="info-value">{{ \Carbon\Carbon::parse($admission->admission_date)->format('M d, Y h:i A') }}</div>
        </div>
        <div class="col-md-4 mb-3">
          <div class="info-label">Admission Type</div>
          <div class="info-value">
            <span class="badge bg-{{ $admission->admission_type === 'Emergency' ? 'danger' : ($admission->admission_type === 'Planned' ? 'info' : 'warning') }}">
              {{ $admission->admission_type }}
            </span>
          </div>
        </div>
        <div class="col-md-4 mb-3">
          <div class="info-label">Category</div>
          <div class="info-value">{{ $admission->admission_category }}</div>
        </div>
        <div class="col-md-4 mb-3">
          <div class="info-label">Attending Doctor</div>
          <div class="info-value">Dr. {{ $admission->doctor->name }}</div>
        </div>
        <div class="col-md-4 mb-3">
          <div class="info-label">Ward</div>
          <div class="info-value">
            <a href="{{ route('wards.show', $admission->ward) }}">{{ $admission->ward->ward_name }}</a>
          </div>
        </div>
        <div class="col-md-4 mb-3">
          <div class="info-label">Bed</div>
          <div class="info-value">{{ $admission->bed ? $admission->bed->bed_number : 'Not Assigned' }}</div>
        </div>
      </div>
    </div>

    <!-- Clinical Information -->
    <div class="info-card">
      <h5 class="section-title">Clinical Information</h5>
      <div class="mb-3">
        <div class="info-label">Provisional Diagnosis</div>
        <div class="info-value">{{ $admission->diagnosis }}</div>
      </div>
      <div class="mb-3">
        <div class="info-label">Chief Complaints</div>
        <div class="info-value">{{ $admission->chief_complaints }}</div>
      </div>
      <div class="mb-3">
        <div class="info-label">Reason for Admission</div>
        <div class="info-value">{{ $admission->reason_for_admission }}</div>
      </div>
      @if($admission->special_instructions)
        <div>
          <div class="info-label">Special Instructions</div>
          <div class="info-value">{{ $admission->special_instructions }}</div>
        </div>
      @endif
    </div>

    <!-- Vitals at Admission -->
    @if($admission->blood_pressure_systolic || $admission->temperature || $admission->pulse_rate)
      <div class="info-card">
        <h5 class="section-title">Vitals at Admission</h5>
        <div class="row g-3">
          @if($admission->blood_pressure_systolic && $admission->blood_pressure_diastolic)
            <div class="col-md-3">
              <div class="vital-item">
                <div class="vital-label">Blood Pressure</div>
                <div class="vital-value">{{ $admission->blood_pressure_systolic }}/{{ $admission->blood_pressure_diastolic }}</div>
                <div class="vital-unit">mmHg</div>
              </div>
            </div>
          @endif
          @if($admission->temperature)
            <div class="col-md-3">
              <div class="vital-item">
                <div class="vital-label">Temperature</div>
                <div class="vital-value">{{ $admission->temperature }}</div>
                <div class="vital-unit">°F</div>
              </div>
            </div>
          @endif
          @if($admission->pulse_rate)
            <div class="col-md-3">
              <div class="vital-item">
                <div class="vital-label">Pulse Rate</div>
                <div class="vital-value">{{ $admission->pulse_rate }}</div>
                <div class="vital-unit">bpm</div>
              </div>
            </div>
          @endif
          @if($admission->respiratory_rate)
            <div class="col-md-3">
              <div class="vital-item">
                <div class="vital-label">Respiratory Rate</div>
                <div class="vital-value">{{ $admission->respiratory_rate }}</div>
                <div class="vital-unit">breaths/min</div>
              </div>
            </div>
          @endif
          @if($admission->oxygen_saturation)
            <div class="col-md-3">
              <div class="vital-item">
                <div class="vital-label">SpO2</div>
                <div class="vital-value">{{ $admission->oxygen_saturation }}</div>
                <div class="vital-unit">%</div>
              </div>
            </div>
          @endif
          @if($admission->weight)
            <div class="col-md-3">
              <div class="vital-item">
                <div class="vital-label">Weight</div>
                <div class="vital-value">{{ $admission->weight }}</div>
                <div class="vital-unit">kg</div>
              </div>
            </div>
          @endif
          @if($admission->height)
            <div class="col-md-3">
              <div class="vital-item">
                <div class="vital-label">Height</div>
                <div class="vital-value">{{ $admission->height }}</div>
                <div class="vital-unit">cm</div>
              </div>
            </div>
          @endif
        </div>
      </div>
    @endif

    <!-- Discharge Information (if discharged) -->
    @if($admission->status === 'Discharged')
      <div class="info-card">
        <h5 class="section-title">Discharge Information</h5>
        <div class="row">
          <div class="col-md-6 mb-3">
            <div class="info-label">Discharge Date</div>
            <div class="info-value">{{ \Carbon\Carbon::parse($admission->discharge_date)->format('M d, Y h:i A') }}</div>
          </div>
          <div class="col-md-6 mb-3">
            <div class="info-label">Total Days</div>
            <div class="info-value">{{ $admission->total_days }} days</div>
          </div>
          @if($admission->discharge_summary)
            <div class="col-12 mb-3">
              <div class="info-label">Discharge Summary</div>
              <div class="info-value">{{ $admission->discharge_summary }}</div>
            </div>
          @endif
          @if($admission->discharge_instructions)
            <div class="col-12">
              <div class="info-label">Discharge Instructions</div>
              <div class="info-value">{{ $admission->discharge_instructions }}</div>
            </div>
          @endif
        </div>
      </div>
    @endif
  </div>

  <!-- Right Column -->
  <div class="col-md-4">
    <!-- Payment Information -->
    <div class="info-card">
      <h5 class="section-title">Payment Information</h5>
      <div class="mb-3">
        <div class="info-label">Payment Mode</div>
        <div class="info-value">
          <span class="badge bg-{{ $admission->payment_mode === 'Cash' ? 'success' : ($admission->payment_mode === 'Insurance' ? 'info' : 'primary') }}">
            {{ $admission->payment_mode }}
          </span>
        </div>
      </div>
      @if($admission->payment_mode === 'Insurance')
        <div class="mb-3">
          <div class="info-label">Insurance Company</div>
          <div class="info-value">{{ $admission->insurance_company }}</div>
        </div>
        <div class="mb-3">
          <div class="info-label">Policy Number</div>
          <div class="info-value">{{ $admission->insurance_policy_number }}</div>
        </div>
      @endif
      <div class="mb-3">
        <div class="info-label">Advance Payment</div>
        <div class="info-value">Ksh {{ number_format($admission->advance_payment ?? 0, 2) }}</div>
      </div>
      @if($admission->total_charges)
        <div class="mb-3">
          <div class="info-label">Total Charges</div>
          <div class="info-value">Ksh {{ number_format($admission->total_charges, 2) }}</div>
        </div>
      @endif
      @if($admission->payment_status)
        <div>
          <div class="info-label">Payment Status</div>
          <div class="info-value">
            <span class="badge bg-{{ $admission->payment_status === 'Paid' ? 'success' : ($admission->payment_status === 'Partial' ? 'warning' : 'danger') }}">
              {{ $admission->payment_status }}
            </span>
          </div>
        </div>
      @endif
    </div>

    <!-- Stay Information -->
    <div class="info-card">
      <h5 class="section-title">Stay Information</h5>
      <div class="mb-3">
        <div class="info-label">Estimated Stay</div>
        <div class="info-value">{{ $admission->estimated_stay_days ?? 'Not specified' }} {{ $admission->estimated_stay_days ? 'days' : '' }}</div>
      </div>
      @if($admission->status === 'Admitted')
        <div class="mb-3">
          <div class="info-label">Days in Hospital</div>
          <div class="info-value">{{ $admission->total_days }} days</div>
        </div>
      @endif
      @if($admission->bed)
        <div class="mb-3">
          <div class="info-label">Bed Type</div>
          <div class="info-value">{{ $admission->bed->bed_type }}</div>
        </div>
        <div>
          <div class="info-label">Bed Charge/Day</div>
          <div class="info-value">Ksh {{ number_format($admission->bed->charge_per_day, 2) }}</div>
        </div>
      @endif
    </div>

    <!-- Patient Quick Info -->
    <div class="info-card">
      <h5 class="section-title">Patient Information</h5>
      <div class="mb-3">
        <div class="info-label">Patient ID</div>
        <div class="info-value">{{ $admission->patient->patient_id }}</div>
      </div>
      <div class="mb-3">
        <div class="info-label">Date of Birth</div>
        <div class="info-value">{{ \Carbon\Carbon::parse($admission->patient->date_of_birth)->format('M d, Y') }}</div>
      </div>
      <div class="mb-3">
        <div class="info-label">Blood Group</div>
        <div class="info-value">{{ $admission->patient->blood_group ?? 'Not specified' }}</div>
      </div>
      <div class="mb-3">
        <div class="info-label">Emergency Contact</div>
        <div class="info-value">
          {{ $admission->patient->emergency_contact_name ?? 'Not provided' }}<br>
          <small class="text-muted">{{ $admission->patient->emergency_contact_phone ?? '' }}</small>
        </div>
      </div>
      <a href="{{ route('patients.show', $admission->patient) }}" class="btn btn-sm btn-outline-primary w-100">
        <i class="bi bi-person"></i> View Full Patient Record
      </a>
    </div>
  </div>
</div>

<!-- Transfer Modal -->
<div class="modal fade" id="transferModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('admissions.transfer', $admission) }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Transfer Patient</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Transfer <strong>{{ $admission->patient->full_name }}</strong> to a different ward or bed
          </div>
          
          <div class="mb-3">
            <label class="form-label">New Ward</label>
            <select name="new_ward_id" class="form-select" required onchange="loadTransferBeds(this.value)">
              <option value="">Select ward...</option>
              @foreach(\App\Models\Ward::where('is_active', true)->get() as $ward)
                <option value="{{ $ward->id }}" {{ $ward->id == $admission->ward_id ? 'selected' : '' }}>
                  {{ $ward->ward_name }} ({{ $ward->available_beds }} available)
                </option>
              @endforeach
            </select>
          </div>
          
          <div class="mb-3">
            <label class="form-label">New Bed</label>
            <select name="new_bed_id" id="transferBedSelect" class="form-select">
              <option value="">Select ward first...</option>
            </select>
          </div>
          
          <div class="mb-3">
            <label class="form-label">Reason for Transfer</label>
            <textarea name="transfer_reason" class="form-control" rows="2" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Transfer Patient</button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
  function loadTransferBeds(wardId) {
    const bedSelect = document.getElementById('transferBedSelect');
    
    if (!wardId) {
      bedSelect.innerHTML = '<option value="">Select ward first...</option>';
      return;
    }
    
    bedSelect.innerHTML = '<option value="">Loading...</option>';
    
    fetch(`/api/wards/${wardId}/available-beds`)
      .then(response => response.json())
      .then(data => {
        if (data.beds && data.beds.length > 0) {
          bedSelect.innerHTML = '<option value="">Select bed...</option>';
          data.beds.forEach(bed => {
            const option = document.createElement('option');
            option.value = bed.id;
            option.textContent = `${bed.bed_number} - ${bed.bed_type}`;
            bedSelect.appendChild(option);
          });
        } else {
          bedSelect.innerHTML = '<option value="">No beds available</option>';
        }
      })
      .catch(error => {
        console.error('Error:', error);
        bedSelect.innerHTML = '<option value="">Error loading beds</option>';
      });
  }
</script>
@endpush
@endsection
