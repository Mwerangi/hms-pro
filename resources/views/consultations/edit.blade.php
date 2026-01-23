@extends('layouts.app')

@section('title', 'Consultation - ' . $consultation->patient->full_name)

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('appointments.index') }}">Appointments</a></li>
<li class="breadcrumb-item active" aria-current="page">Consultation</li>
@endsection

@push('styles')
<style>
  .consultation-wrapper {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 20px;
    margin-bottom: 30px;
  }

  .patient-sidebar {
    background: white;
    border-radius: 16px;
    padding: 24px;
    border: 1px solid #e2e8f0;
    height: fit-content;
    position: sticky;
    top: 80px;
  }

  [data-theme="dark"] .patient-sidebar {
    background: #1a202c;
    border-color: #2d3748;
  }

  .patient-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--hms-purple), var(--hms-primary));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 32px;
    font-weight: 700;
    margin: 0 auto 16px;
  }

  .patient-name {
    text-align: center;
    font-size: 18px;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 4px;
  }

  [data-theme="dark"] .patient-name {
    color: #e2e8f0;
  }

  .patient-id {
    text-align: center;
    font-size: 14px;
    color: #718096;
    margin-bottom: 20px;
  }

  .patient-info-item {
    padding: 10px 0;
    border-bottom: 1px solid #f7fafc;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  [data-theme="dark"] .patient-info-item {
    border-bottom-color: #2d3748;
  }

  .patient-info-item:last-child {
    border-bottom: none;
  }

  .info-label {
    font-size: 13px;
    color: #718096;
    font-weight: 600;
  }

  .info-value {
    font-size: 13px;
    color: #2d3748;
    font-weight: 500;
  }

  [data-theme="dark"] .info-value {
    color: #e2e8f0;
  }

  .consultation-form {
    background: white;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
  }

  [data-theme="dark"] .consultation-form {
    background: #1a202c;
    border-color: #2d3748;
  }

  .form-section {
    padding: 24px;
    border-bottom: 2px solid #f7fafc;
  }

  [data-theme="dark"] .form-section {
    border-bottom-color: #2d3748;
  }

  .form-section:last-child {
    border-bottom: none;
  }

  .section-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 2px solid #f7fafc;
  }

  [data-theme="dark"] .section-header {
    border-bottom-color: #2d3748;
  }

  .section-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--hms-purple), var(--hms-primary));
    color: white;
    font-size: 20px;
  }

  .section-title {
    font-size: 18px;
    font-weight: 700;
    color: #2d3748;
    margin: 0;
  }

  [data-theme="dark"] .section-title {
    color: #e2e8f0;
  }

  .vitals-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
  }

  .vital-input {
    position: relative;
  }

  .vital-input .form-label {
    font-size: 13px;
    font-weight: 600;
    color: #4a5568;
    margin-bottom: 6px;
  }

  [data-theme="dark"] .vital-input .form-label {
    color: #cbd5e0;
  }

  .vital-input .form-control {
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    padding: 10px 14px;
  }

  [data-theme="dark"] .vital-input .form-control {
    background: #2d3748;
    border-color: #4a5568;
    color: #e2e8f0;
  }

  .prescription-builder,
  .lab-order-builder {
    background: #f8fafc;
    border-radius: 12px;
    padding: 20px;
    margin-top: 16px;
  }

  [data-theme="dark"] .prescription-builder,
  [data-theme="dark"] .lab-order-builder {
    background: #2d3748;
  }

  .medicine-item {
    background: white;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 12px;
    border: 1px solid #e2e8f0;
    position: relative;
  }

  [data-theme="dark"] .medicine-item {
    background: #1a202c;
    border-color: #4a5568;
  }

  .remove-item {
    position: absolute;
    top: 12px;
    right: 12px;
    background: #fee2e2;
    color: #991b1b;
    border: none;
    border-radius: 6px;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .remove-item:hover {
    background: #fecaca;
  }

  .btn-add-medicine,
  .btn-add-test {
    background: linear-gradient(135deg, var(--hms-purple), var(--hms-primary));
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
  }

  .btn-add-medicine:hover,
  .btn-add-test:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    color: white;
  }

  .action-buttons {
    position: sticky;
    bottom: 0;
    background: white;
    padding: 20px 24px;
    border-top: 2px solid #f7fafc;
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    border-radius: 0 0 16px 16px;
  }

  [data-theme="dark"] .action-buttons {
    background: #1a202c;
    border-top-color: #2d3748;
  }

  .btn-action {
    padding: 12px 30px;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s ease;
  }

  .btn-action:hover {
    transform: translateY(-2px);
  }
</style>
@endpush

@section('content')
<div class="consultation-wrapper">
  <!-- Patient Sidebar -->
  <div class="patient-sidebar">
    <div class="patient-avatar">
      {{ strtoupper(substr($consultation->patient->first_name, 0, 1)) }}{{ strtoupper(substr($consultation->patient->last_name, 0, 1)) }}
    </div>
    <div class="patient-name">{{ $consultation->patient->full_name }}</div>
    <div class="patient-id">{{ $consultation->patient->patient_id }}</div>

    <div class="patient-info-item">
      <span class="info-label">Age</span>
      <span class="info-value">{{ $consultation->patient->age }} years</span>
    </div>
    <div class="patient-info-item">
      <span class="info-label">Gender</span>
      <span class="info-value">{{ ucfirst($consultation->patient->gender) }}</span>
    </div>
    <div class="patient-info-item">
      <span class="info-label">Blood Group</span>
      <span class="info-value">{{ $consultation->patient->blood_group ?? 'N/A' }}</span>
    </div>
    <div class="patient-info-item">
      <span class="info-label">Phone</span>
      <span class="info-value">{{ $consultation->patient->phone }}</span>
    </div>
    
    @if($consultation->patient->allergies)
    <div style="margin-top: 20px; padding: 12px; background: #fee2e2; border-radius: 8px;">
      <div style="font-weight: 700; color: #991b1b; font-size: 13px; margin-bottom: 4px;">
        <i class="bi bi-exclamation-triangle me-1"></i>Allergies
      </div>
      <div style="font-size: 12px; color: #7f1d1d;">{{ $consultation->patient->allergies }}</div>
    </div>
    @endif

    <div style="margin-top: 20px;">
      <a href="{{ route('patients.show', $consultation->patient) }}" class="btn btn-sm btn-outline-primary w-100">
        <i class="bi bi-file-medical me-2"></i>View Full Record
      </a>
    </div>
  </div>

  <!-- Consultation Form -->
  <div class="consultation-form">
    <form action="{{ route('consultations.update', $consultation) }}" method="POST" id="consultationForm">
      @csrf
      @method('PUT')

      <!-- Vitals Section -->
      <div class="form-section">
        <div class="section-header">
          <div class="section-icon">
            <i class="bi bi-heart-pulse"></i>
          </div>
          <h3 class="section-title">Vital Signs</h3>
          @if($consultation->appointment && $consultation->appointment->vitals_recorded_at)
            <span class="badge bg-success ms-2">
              <i class="bi bi-check-circle"></i> Pre-recorded by Nursing
            </span>
          @endif
        </div>
        
        @if($consultation->appointment && $consultation->appointment->vitals_recorded_at)
          <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Vitals were recorded by <strong>{{ $consultation->appointment->vitalsRecordedBy->name }}</strong> at nursing station
            on {{ $consultation->appointment->vitals_recorded_at->format('M d, Y h:i A') }}
          </div>
        @endif
        
        <div class="vitals-grid">
          <div class="vital-input">
            <label class="form-label">Temperature (°F)</label>
            <input type="text" name="temperature" class="form-control" 
                   value="{{ old('temperature', $consultation->temperature ?? ($consultation->appointment->temperature ?? '')) }}" placeholder="98.6">
          </div>
          <div class="vital-input">
            <label class="form-label">Blood Pressure</label>
            <input type="text" name="blood_pressure" class="form-control" 
                   value="{{ old('blood_pressure', $consultation->blood_pressure ?? ($consultation->appointment->blood_pressure ?? '')) }}" placeholder="120/80">
          </div>
          <div class="vital-input">
            <label class="form-label">Pulse Rate (bpm)</label>
            <input type="text" name="pulse_rate" class="form-control" 
                   value="{{ old('pulse_rate', $consultation->pulse_rate ?? ($consultation->appointment->pulse_rate ?? '')) }}" placeholder="72">
          </div>
          <div class="vital-input">
            <label class="form-label">Weight (kg)</label>
            <input type="number" step="0.01" name="weight" class="form-control" 
                   value="{{ old('weight', $consultation->weight ?? ($consultation->appointment->weight ?? '')) }}" placeholder="70.5">
          </div>
          <div class="vital-input">
            <label class="form-label">Height (cm)</label>
            <input type="number" step="0.01" name="height" class="form-control" 
                   value="{{ old('height', $consultation->height ?? ($consultation->appointment->height ?? '')) }}" placeholder="175">
          </div>
          <div class="vital-input">
            <label class="form-label">SpO2 (%)</label>
            <input type="text" name="spo2" class="form-control" 
                   value="{{ old('spo2', $consultation->spo2 ?? ($consultation->appointment->spo2 ?? '')) }}" placeholder="98">
          </div>
          <div class="vital-input">
            <label class="form-label">Respiratory Rate</label>
            <input type="text" name="respiratory_rate" class="form-control" 
                   value="{{ old('respiratory_rate', $consultation->respiratory_rate ?? ($consultation->appointment->respiratory_rate ?? '')) }}" placeholder="16">
          </div>
          @if($consultation->bmi)
          <div class="vital-input">
            <label class="form-label">BMI</label>
            <input type="text" class="form-control" value="{{ number_format($consultation->bmi, 2) }} ({{ $consultation->bmi_category }})" readonly>
          </div>
          @endif
        </div>
      </div>

      <!-- Chief Complaint & History -->
      <div class="form-section">
        <div class="section-header">
          <div class="section-icon">
            <i class="bi bi-chat-left-text"></i>
          </div>
          <h3 class="section-title">Chief Complaint & History</h3>
        </div>
        
        <div class="mb-3">
          <label class="form-label">Chief Complaint</label>
          <textarea name="chief_complaint" class="form-control" rows="2" 
                    placeholder="Main reason for visit...">{{ old('chief_complaint', $consultation->chief_complaint) }}</textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">History of Present Illness</label>
          <textarea name="history_of_present_illness" class="form-control" rows="3" 
                    placeholder="Details of current illness...">{{ old('history_of_present_illness', $consultation->history_of_present_illness) }}</textarea>
        </div>

        <div class="mb-0">
          <label class="form-label">Known Allergies</label>
          <textarea name="allergies" class="form-control" rows="2" 
                    placeholder="Drug allergies, food allergies...">{{ old('allergies', $consultation->allergies) }}</textarea>
        </div>
      </div>

      <!-- Examination -->
      <div class="form-section">
        <div class="section-header">
          <div class="section-icon">
            <i class="bi bi-clipboard-pulse"></i>
          </div>
          <h3 class="section-title">Physical Examination</h3>
        </div>
        
        <div class="mb-3">
          <label class="form-label">General Examination</label>
          <textarea name="general_examination" class="form-control" rows="3" 
                    placeholder="General appearance, consciousness, built, nourishment...">{{ old('general_examination', $consultation->general_examination) }}</textarea>
        </div>
      </div>

      <!-- Diagnosis & Treatment -->
      <div class="form-section">
        <div class="section-header">
          <div class="section-icon">
            <i class="bi bi-clipboard-check"></i>
          </div>
          <h3 class="section-title">Diagnosis & Treatment Plan</h3>
        </div>
        
        <div class="mb-3">
          <label class="form-label">Provisional Diagnosis</label>
          <textarea name="provisional_diagnosis" class="form-control" rows="2" 
                    placeholder="Initial diagnosis...">{{ old('provisional_diagnosis', $consultation->provisional_diagnosis) }}</textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Final Diagnosis</label>
          <textarea name="final_diagnosis" class="form-control" rows="2" 
                    placeholder="Confirmed diagnosis...">{{ old('final_diagnosis', $consultation->final_diagnosis) }}</textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Treatment Plan</label>
          <textarea name="treatment_plan" class="form-control" rows="3" 
                    placeholder="Recommended treatment, procedures...">{{ old('treatment_plan', $consultation->treatment_plan) }}</textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Advice & Instructions</label>
          <textarea name="advice_instructions" class="form-control" rows="3" 
                    placeholder="Lifestyle changes, precautions, diet...">{{ old('advice_instructions', $consultation->advice_instructions) }}</textarea>
        </div>

        <div class="mb-0">
          <label class="form-label">Doctor's Notes (Private)</label>
          <textarea name="doctor_notes" class="form-control" rows="2" 
                    placeholder="Internal notes...">{{ old('doctor_notes', $consultation->doctor_notes) }}</textarea>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="action-buttons">
        <button type="submit" class="btn btn-primary btn-action">
          <i class="bi bi-save me-2"></i>Save Progress
        </button>
        <button type="button" class="btn btn-success btn-action" onclick="document.getElementById('prescriptionModal').classList.add('show'); document.getElementById('prescriptionModal').style.display='block';">
          <i class="bi bi-capsule me-2"></i>Add Prescription
        </button>
        <button type="button" class="btn btn-info btn-action" onclick="document.getElementById('labOrderModal').classList.add('show'); document.getElementById('labOrderModal').style.display='block';">
          <i class="bi bi-clipboard2-pulse me-2"></i>Order Lab Tests
        </button>
        <a href="{{ route('consultations.show', $consultation) }}" class="btn btn-outline-secondary btn-action">
          <i class="bi bi-eye me-2"></i>Preview
        </a>
      </div>
    </form>
  </div>
</div>

<!-- Prescription Modal -->
<div class="modal fade" id="prescriptionModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Prescription</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('consultations.add-prescription', $consultation) }}" method="POST" id="prescriptionForm">
        @csrf
        <div class="modal-body">
          <div id="medicineItems">
            <div class="medicine-item" data-index="0">
              <div class="row">
                <div class="col-md-3 mb-3">
                  <label class="form-label">Medicine Name <span class="text-danger">*</span></label>
                  <input type="text" name="items[0][medicine_name]" class="form-control" required placeholder="Paracetamol">
                </div>
                <div class="col-md-2 mb-3">
                  <label class="form-label">Dosage <span class="text-danger">*</span></label>
                  <input type="text" name="items[0][dosage]" class="form-control" required placeholder="500mg">
                </div>
                <div class="col-md-2 mb-3">
                  <label class="form-label">Frequency <span class="text-danger">*</span></label>
                  <select name="items[0][frequency]" class="form-select" required>
                    <option value="Once daily">Once daily</option>
                    <option value="Twice daily (BD)">Twice daily (BD)</option>
                    <option value="3 times daily (TDS)">3 times daily (TDS)</option>
                    <option value="4 times daily (QID)">4 times daily (QID)</option>
                    <option value="Every 6 hours">Every 6 hours</option>
                    <option value="As needed (PRN)">As needed (PRN)</option>
                  </select>
                </div>
                <div class="col-md-2 mb-3">
                  <label class="form-label">Duration <span class="text-danger">*</span></label>
                  <input type="text" name="items[0][duration]" class="form-control" required placeholder="7 days">
                </div>
                <div class="col-md-2 mb-3">
                  <label class="form-label">Quantity <span class="text-danger">*</span></label>
                  <input type="number" name="items[0][quantity]" class="form-control" required min="1" placeholder="21">
                </div>
                <div class="col-md-12 mb-3">
                  <label class="form-label">Instructions</label>
                  <input type="text" name="items[0][instructions]" class="form-control" placeholder="After meals">
                </div>
              </div>
            </div>
          </div>
          
          <button type="button" class="btn btn-add-medicine" onclick="addMedicineItem()">
            <i class="bi bi-plus-circle me-2"></i>Add Another Medicine
          </button>

          <div class="mt-3">
            <label class="form-label">Special Instructions</label>
            <textarea name="special_instructions" class="form-control" rows="2" 
                      placeholder="General instructions for all medicines..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle me-2"></i>Save Prescription
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Lab Order Modal -->
<div class="modal fade" id="labOrderModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Order Lab Tests</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('consultations.add-lab-order', $consultation) }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Test Type <span class="text-danger">*</span></label>
              <select name="test_type" class="form-select" required>
                <option value="blood">Blood Test</option>
                <option value="urine">Urine Test</option>
                <option value="stool">Stool Test</option>
                <option value="imaging">Imaging (X-Ray, CT, MRI)</option>
                <option value="pathology">Pathology</option>
                <option value="other">Other</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Urgency <span class="text-danger">*</span></label>
              <select name="urgency" class="form-select" required>
                <option value="routine">Routine</option>
                <option value="urgent">Urgent</option>
                <option value="stat">STAT (Immediate)</option>
              </select>
            </div>
            <div class="col-12 mb-3">
              <label class="form-label">Tests Ordered <span class="text-danger">*</span></label>
              <textarea name="tests_ordered" class="form-control" rows="3" required 
                        placeholder="E.g., CBC, Blood Sugar (Fasting), Lipid Profile, X-Ray Chest PA view"></textarea>
              <small class="text-muted">Enter test names separated by commas</small>
            </div>
            <div class="col-12 mb-3">
              <label class="form-label">Clinical Notes</label>
              <textarea name="clinical_notes" class="form-control" rows="2" 
                        placeholder="Clinical indication for tests..."></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle me-2"></i>Submit Order
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
let medicineIndex = 1;

function addMedicineItem() {
  const container = document.getElementById('medicineItems');
  const newItem = document.createElement('div');
  newItem.className = 'medicine-item';
  newItem.setAttribute('data-index', medicineIndex);
  newItem.innerHTML = `
    <button type="button" class="remove-item" onclick="removeMedicineItem(this)">
      <i class="bi bi-x"></i>
    </button>
    <div class="row">
      <div class="col-md-3 mb-3">
        <label class="form-label">Medicine Name <span class="text-danger">*</span></label>
        <input type="text" name="items[${medicineIndex}][medicine_name]" class="form-control" required>
      </div>
      <div class="col-md-2 mb-3">
        <label class="form-label">Dosage <span class="text-danger">*</span></label>
        <input type="text" name="items[${medicineIndex}][dosage]" class="form-control" required>
      </div>
      <div class="col-md-2 mb-3">
        <label class="form-label">Frequency <span class="text-danger">*</span></label>
        <select name="items[${medicineIndex}][frequency]" class="form-select" required>
          <option value="Once daily">Once daily</option>
          <option value="Twice daily (BD)">Twice daily (BD)</option>
          <option value="3 times daily (TDS)">3 times daily (TDS)</option>
          <option value="4 times daily (QID)">4 times daily (QID)</option>
          <option value="Every 6 hours">Every 6 hours</option>
          <option value="As needed (PRN)">As needed (PRN)</option>
        </select>
      </div>
      <div class="col-md-2 mb-3">
        <label class="form-label">Duration <span class="text-danger">*</span></label>
        <input type="text" name="items[${medicineIndex}][duration]" class="form-control" required>
      </div>
      <div class="col-md-2 mb-3">
        <label class="form-label">Quantity <span class="text-danger">*</span></label>
        <input type="number" name="items[${medicineIndex}][quantity]" class="form-control" required min="1">
      </div>
      <div class="col-md-12 mb-3">
        <label class="form-label">Instructions</label>
        <input type="text" name="items[${medicineIndex}][instructions]" class="form-control">
      </div>
    </div>
  `;
  container.appendChild(newItem);
  medicineIndex++;
}

function removeMedicineItem(button) {
  button.closest('.medicine-item').remove();
}
</script>
@endpush
