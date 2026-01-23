@extends('layouts.app')

@section('title', 'Consultation Details - ' . $consultation->consultation_number)

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('appointments.index') }}">Appointments</a></li>
<li class="breadcrumb-item active" aria-current="page">Consultation {{ $consultation->consultation_number }}</li>
@endsection

@push('styles')
<style>
  .consultation-header {
    background: linear-gradient(135deg, var(--hms-purple), var(--hms-primary));
    border-radius: 16px;
    padding: 30px;
    color: white;
    margin-bottom: 25px;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
  }

  .info-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    border: 1px solid #e2e8f0;
    margin-bottom: 20px;
  }

  [data-theme="dark"] .info-card {
    background: #1a202c;
    border-color: #2d3748;
  }

  .card-title {
    font-size: 18px;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 2px solid #f7fafc;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  [data-theme="dark"] .card-title {
    color: #e2e8f0;
    border-bottom-color: #2d3748;
  }

  .card-icon {
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

  .vitals-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 16px;
  }

  .vital-box {
    background: #f8fafc;
    padding: 16px;
    border-radius: 8px;
    text-align: center;
  }

  [data-theme="dark"] .vital-box {
    background: #2d3748;
  }

  .vital-label {
    font-size: 12px;
    color: #718096;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 6px;
  }

  .vital-value {
    font-size: 24px;
    font-weight: 700;
    color: #2d3748;
  }

  [data-theme="dark"] .vital-value {
    color: #e2e8f0;
  }

  .prescription-table {
    width: 100%;
    margin-top: 16px;
  }

  .prescription-table th {
    background: #f8fafc;
    padding: 12px;
    font-weight: 600;
    font-size: 13px;
    color: #4a5568;
    border-bottom: 2px solid #e2e8f0;
  }

  [data-theme="dark"] .prescription-table th {
    background: #2d3748;
    color: #cbd5e0;
    border-bottom-color: #4a5568;
  }

  .prescription-table td {
    padding: 12px;
    border-bottom: 1px solid #f7fafc;
  }

  [data-theme="dark"] .prescription-table td {
    border-bottom-color: #2d3748;
  }

  .text-section {
    background: #f8fafc;
    padding: 16px;
    border-radius: 8px;
    margin-bottom: 16px;
  }

  [data-theme="dark"] .text-section {
    background: #2d3748;
  }

  .section-label {
    font-weight: 600;
    color: #4a5568;
    font-size: 13px;
    margin-bottom: 8px;
  }

  [data-theme="dark"] .section-label {
    color: #cbd5e0;
  }

  .section-content {
    color: #2d3748;
    font-size: 14px;
    line-height: 1.6;
  }

  [data-theme="dark"] .section-content {
    color: #e2e8f0;
  }
</style>
@endpush

@section('content')
<!-- Consultation Header -->
<div class="consultation-header">
  <div class="d-flex justify-content-between align-items-start">
    <div>
      <h1 style="font-size: 28px; font-weight: 700; margin-bottom: 8px;">{{ $consultation->consultation_number }}</h1>
      <div style="font-size: 16px; opacity: 0.9;">
        {{ $consultation->patient->full_name }} ({{ $consultation->patient->patient_id }}) | {{ $consultation->created_at->format('F d, Y h:i A') }}
      </div>
      <div class="mt-2 d-flex gap-2 flex-wrap">
        <span class="badge bg-{{ $consultation->status === 'completed' ? 'success' : 'primary' }}">
          {{ ucfirst($consultation->status) }}
        </span>
        @if(isset($pendingLabResults) && $pendingLabResults > 0)
          <a href="#lab-results-tab" class="badge bg-warning text-decoration-none" onclick="document.getElementById('lab-results-tab').click()">
            <i class="bi bi-hourglass-split"></i> {{ $pendingLabResults }} Lab Test(s) Pending - Click to View
          </a>
        @endif
        @if(isset($reportedLabResults) && $reportedLabResults > 0)
          <a href="#lab-results-tab" class="badge bg-success text-decoration-none" onclick="document.getElementById('lab-results-tab').click()">
            <i class="bi bi-check-circle"></i> {{ $reportedLabResults }} Lab Result(s) Ready - Click to View
          </a>
        @endif
        @if(isset($hasPrescription) && $hasPrescription)
          <a href="#prescriptions-tab" class="badge bg-info text-decoration-none" onclick="document.getElementById('prescriptions-tab').click()">
            <i class="bi bi-prescription2"></i> Prescription Available - Click to View
          </a>
        @endif
      </div>
    </div>
    <div class="d-flex gap-2 flex-wrap">
      @if($consultation->status === 'in-progress')
        <a href="{{ route('consultations.edit', $consultation) }}" class="btn btn-light">
          <i class="bi bi-pencil me-2"></i>Continue Editing
        </a>
        
        @if($pendingLabResults > 0)
          <button type="submit" class="btn btn-warning" onclick="return confirm('There are {{ $pendingLabResults }} pending lab tests. Are you sure you want to complete this consultation?')" form="complete-form">
            <i class="bi bi-exclamation-triangle me-2"></i>Complete (with Pending Tests)
          </button>
        @else
          <button type="submit" class="btn btn-success" form="complete-form">
            <i class="bi bi-check-circle me-2"></i>Complete Consultation
          </button>
        @endif
        
        <form id="complete-form" action="{{ route('consultations.complete', $consultation) }}" method="POST" class="d-inline">
          @csrf
        </form>

        <!-- Admit Patient Button -->
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#admitPatientModal">
          <i class="bi bi-hospital me-2"></i>Admit Patient
        </button>
      @elseif($consultation->status === 'completed')
        @if($reportedLabResults > 0 || $pendingLabResults > 0)
          <form action="{{ route('consultations.resume', $consultation) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-warning">
              <i class="bi bi-arrow-clockwise me-2"></i>Resume & Review Results
            </button>
          </form>
        @endif
      @endif
      
      <a href="{{ route('doctor.appointments') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back
      </a>
    </div>
  </div>
</div>

<!-- Tabbed Interface -->
<div class="card border-0 shadow-sm">
  <div class="card-header bg-white border-0 pt-3">
    <ul class="nav nav-tabs card-header-tabs" id="consultationTabs" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
          <i class="bi bi-file-medical"></i> Consultation Details
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="lab-results-tab" data-bs-toggle="tab" data-bs-target="#lab-results" type="button" role="tab">
          <i class="bi bi-clipboard-pulse"></i> Lab Tests & Results
          @if($reportedLabResults > 0)
            <span class="badge bg-success ms-1">{{ $reportedLabResults }}</span>
          @endif
          @if($pendingLabResults > 0)
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
  
  <div class="card-body">
    <div class="tab-content" id="consultationTabContent">
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
          
          <div class="mb-3">
            <label for="admission_reason" class="form-label">Reason for Admission <span class="text-danger">*</span></label>
            <textarea name="admission_reason" id="admission_reason" class="form-control" rows="3" required placeholder="e.g., Severe dehydration, requires IV fluids and observation"></textarea>
          </div>
          
          <div class="mb-3">
            <label for="ward_type" class="form-label">Ward/Room Type <span class="text-danger">*</span></label>
            <select name="ward_type" id="ward_type" class="form-select" required>
              <option value="">Select ward type...</option>
              <option value="General Ward">General Ward</option>
              <option value="Private Room">Private Room</option>
              <option value="ICU">ICU</option>
              <option value="NICU">NICU</option>
              <option value="Emergency">Emergency</option>
            </select>
          </div>
          
          <div class="mb-3">
            <label for="admission_notes" class="form-label">Additional Notes</label>
            <textarea name="admission_notes" id="admission_notes" class="form-control" rows="2" placeholder="Any special instructions or requirements"></textarea>
          </div>
          
          <div class="alert alert-warning">
            <small><i class="bi bi-exclamation-triangle"></i> This will complete the consultation and initiate IPD admission process.</small>
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

<div class="row">
  <!-- Patient Information -->
  <div class="col-md-4">
    <div class="info-card">
      <h3 class="card-title">
        <div class="card-icon">
          <i class="bi bi-person"></i>
        </div>
        Patient Information
      </h3>
      <div style="font-size: 14px; line-height: 2;">
        <div><strong>Name:</strong> {{ $consultation->patient->full_name }}</div>
        <div><strong>ID:</strong> {{ $consultation->patient->patient_id }}</div>
        <div><strong>Age:</strong> {{ $consultation->patient->age }} years</div>
        <div><strong>Gender:</strong> {{ ucfirst($consultation->patient->gender) }}</div>
        <div><strong>Blood Group:</strong> {{ $consultation->patient->blood_group ?? 'N/A' }}</div>
        <div><strong>Phone:</strong> {{ $consultation->patient->phone }}</div>
        <div><strong>Doctor:</strong> Dr. {{ $consultation->doctor->name }}</div>
      </div>
    </div>

    @if($consultation->appointment)
    <div class="info-card">
      <h3 class="card-title">
        <div class="card-icon">
          <i class="bi bi-calendar-check"></i>
        </div>
        Appointment Details
      </h3>
      <div style="font-size: 14px; line-height: 2;">
        <div><strong>Appointment:</strong> {{ $consultation->appointment->appointment_number }}</div>
        <div><strong>Token:</strong> {{ $consultation->appointment->token_number }}</div>
        <div><strong>Date:</strong> {{ $consultation->appointment->appointment_date->format('M d, Y') }}</div>
        <div><strong>Time:</strong> {{ $consultation->appointment->appointment_time->format('h:i A') }}</div>
      </div>
    </div>
    @endif
  </div>

  <!-- Main Content -->
  <div class="col-md-8">
    <!-- Vitals -->
    @if($consultation->temperature || $consultation->blood_pressure || $consultation->pulse_rate)
    <div class="info-card">
      <h3 class="card-title">
        <div class="card-icon">
          <i class="bi bi-heart-pulse"></i>
        </div>
        Vital Signs
      </h3>
      <div class="vitals-grid">
        @if($consultation->temperature)
        <div class="vital-box">
          <div class="vital-label">Temperature</div>
          <div class="vital-value">{{ $consultation->temperature }}°F</div>
        </div>
        @endif
        @if($consultation->blood_pressure)
        <div class="vital-box">
          <div class="vital-label">BP</div>
          <div class="vital-value">{{ $consultation->blood_pressure }}</div>
        </div>
        @endif
        @if($consultation->pulse_rate)
        <div class="vital-box">
          <div class="vital-label">Pulse</div>
          <div class="vital-value">{{ $consultation->pulse_rate }}</div>
        </div>
        @endif
        @if($consultation->weight)
        <div class="vital-box">
          <div class="vital-label">Weight</div>
          <div class="vital-value">{{ $consultation->weight }} kg</div>
        </div>
        @endif
        @if($consultation->height)
        <div class="vital-box">
          <div class="vital-label">Height</div>
          <div class="vital-value">{{ $consultation->height }} cm</div>
        </div>
        @endif
        @if($consultation->bmi)
        <div class="vital-box">
          <div class="vital-label">BMI</div>
          <div class="vital-value">{{ number_format($consultation->bmi, 1) }}</div>
          <div style="font-size: 11px; color: #718096; margin-top: 4px;">{{ $consultation->bmi_category }}</div>
        </div>
        @endif
        @if($consultation->spo2)
        <div class="vital-box">
          <div class="vital-label">SpO2</div>
          <div class="vital-value">{{ $consultation->spo2 }}%</div>
        </div>
        @endif
      </div>
    </div>
    @endif

    <!-- Chief Complaint & History -->
    <div class="info-card">
      <h3 class="card-title">
        <div class="card-icon">
          <i class="bi bi-chat-left-text"></i>
        </div>
        Chief Complaint & History
      </h3>
      
      @if($consultation->chief_complaint)
      <div class="text-section">
        <div class="section-label">Chief Complaint</div>
        <div class="section-content">{{ $consultation->chief_complaint }}</div>
      </div>
      @endif

      @if($consultation->history_of_present_illness)
      <div class="text-section">
        <div class="section-label">History of Present Illness</div>
        <div class="section-content">{{ $consultation->history_of_present_illness }}</div>
      </div>
      @endif

      @if($consultation->allergies)
      <div class="text-section" style="background: #fee2e2;">
        <div class="section-label" style="color: #991b1b;">
          <i class="bi bi-exclamation-triangle me-1"></i>Allergies
        </div>
        <div class="section-content" style="color: #7f1d1d;">{{ $consultation->allergies }}</div>
      </div>
      @endif
    </div>

    <!-- Examination -->
    @if($consultation->general_examination)
    <div class="info-card">
      <h3 class="card-title">
        <div class="card-icon">
          <i class="bi bi-clipboard-pulse"></i>
        </div>
        Physical Examination
      </h3>
      <div class="text-section">
        <div class="section-content">{{ $consultation->general_examination }}</div>
      </div>
    </div>
    @endif

    <!-- Diagnosis & Treatment -->
    <div class="info-card">
      <h3 class="card-title">
        <div class="card-icon">
          <i class="bi bi-clipboard-check"></i>
        </div>
        Diagnosis & Treatment
      </h3>

      @if($consultation->provisional_diagnosis)
      <div class="text-section">
        <div class="section-label">Provisional Diagnosis</div>
        <div class="section-content">{{ $consultation->provisional_diagnosis }}</div>
      </div>
      @endif

      @if($consultation->final_diagnosis)
      <div class="text-section">
        <div class="section-label">Final Diagnosis</div>
        <div class="section-content">{{ $consultation->final_diagnosis }}</div>
      </div>
      @endif

      @if($consultation->treatment_plan)
      <div class="text-section">
        <div class="section-label">Treatment Plan</div>
        <div class="section-content">{{ $consultation->treatment_plan }}</div>
      </div>
      @endif

      @if($consultation->advice_instructions)
      <div class="text-section">
        <div class="section-label">Advice & Instructions</div>
        <div class="section-content">{{ $consultation->advice_instructions }}</div>
      </div>
      @endif
    </div>

    <!-- Prescriptions -->
    @if($consultation->prescriptions->count() > 0)
    <div class="info-card">
      <h3 class="card-title">
        <div class="card-icon">
          <i class="bi bi-capsule"></i>
        </div>
        Prescriptions ({{ $consultation->prescriptions->count() }})
      </h3>
      
      @foreach($consultation->prescriptions as $prescription)
        <div style="margin-bottom: 20px;">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <strong>{{ $prescription->prescription_number }}</strong>
            <span class="badge bg-{{ $prescription->status === 'dispensed' ? 'success' : 'warning' }}">
              {{ ucfirst($prescription->status) }}
            </span>
          </div>
          
          <table class="prescription-table">
            <thead>
              <tr>
                <th>Medicine</th>
                <th>Dosage</th>
                <th>Frequency</th>
                <th>Duration</th>
                <th>Qty</th>
                <th>Instructions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($prescription->items as $item)
              <tr>
                <td><strong>{{ $item->medicine_name }}</strong></td>
                <td>{{ $item->dosage }}</td>
                <td>{{ $item->frequency }}</td>
                <td>{{ $item->duration }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->instructions ?? '-' }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>

          @if($prescription->special_instructions)
          <div class="mt-2" style="padding: 12px; background: #fef3c7; border-radius: 6px; font-size: 13px;">
            <strong>Instructions:</strong> {{ $prescription->special_instructions }}
          </div>
          @endif
        </div>
      @endforeach
    </div>
    @endif

    <!-- Lab Orders -->
    @if($consultation->labOrders->count() > 0)
    <div class="info-card">
      <h3 class="card-title">
        <div class="card-icon">
          <i class="bi bi-clipboard2-pulse"></i>
        </div>
        Lab Orders ({{ $consultation->labOrders->count() }})
      </h3>
      
      @foreach($consultation->labOrders as $order)
        <div style="padding: 16px; background: #f8fafc; border-radius: 8px; margin-bottom: 12px;">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
              <strong>{{ $order->order_number }}</strong>
              <span class="badge bg-info ms-2">{{ ucfirst($order->test_type) }}</span>
              {!! $order->urgency_badge !!}
            </div>
            {!! $order->status_badge !!}
          </div>
          <div style="font-size: 14px; margin-top: 8px;">
            <strong>Tests:</strong> {{ $order->tests_list }}
          </div>
          @if($order->clinical_notes)
          <div style="font-size: 13px; margin-top: 8px; color: #718096;">
            <strong>Clinical Notes:</strong> {{ $order->clinical_notes }}
          </div>
          @endif
          
          <!-- Results Section -->
          @if($order->status === 'reported' || $order->status === 'completed')
            <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid #e2e8f0;">
              @if($order->results)
                <div style="font-size: 13px; margin-bottom: 8px;">
                  <strong style="color: #10b981;">Results:</strong>
                  <div style="margin-top: 4px; padding: 8px; background: white; border-radius: 4px; border-left: 3px solid #10b981;">
                    {{ $order->results }}
                  </div>
                </div>
              @endif
              
              @if($order->radiologist_findings)
                <div style="font-size: 13px; margin-bottom: 8px;">
                  <strong style="color: #3b82f6;">Radiologist Findings:</strong>
                  <div style="margin-top: 4px; padding: 8px; background: white; border-radius: 4px; border-left: 3px solid #3b82f6;">
                    {{ $order->radiologist_findings }}
                  </div>
                </div>
              @endif
              
              <div class="d-flex gap-2 mt-2">
                @if($order->result_file_path)
                  <a href="{{ route('lab.download-result', $order) }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-download"></i> Download Report
                  </a>
                @endif
                @if($order->imaging_file_path)
                  <a href="{{ route('lab.download-imaging', $order) }}" class="btn btn-sm btn-info">
                    <i class="bi bi-download"></i> Download Images
                  </a>
                @endif
                <a href="{{ route('lab.show', $order) }}" class="btn btn-sm btn-outline-primary">
                  <i class="bi bi-eye"></i> Full Details
                </a>
              </div>
            </div>
          @else
            <div style="margin-top: 8px;">
              <a href="{{ route('lab.show', $order) }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-eye"></i> View Order
              </a>
            </div>
          @endif
        </div>
      @endforeach
    </div>
    @endif
  </div>
</div>
@endsection
