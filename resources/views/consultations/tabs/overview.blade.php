<!-- Vitals Section -->
<div class="section-card">
  <div class="section-title">
    <i class="bi bi-heart-pulse"></i>
    Vital Signs
  </div>
  <div class="vitals-grid">
    <div class="vital-box">
      <div class="vital-label">Blood Pressure</div>
      <div class="vital-value">{{ $consultation->blood_pressure ?? 'N/A' }}</div>
    </div>
    <div class="vital-box">
      <div class="vital-label">Pulse Rate</div>
      <div class="vital-value">{{ $consultation->pulse_rate ?? 'N/A' }}</div>
    </div>
    <div class="vital-box">
      <div class="vital-label">Temperature</div>
      <div class="vital-value">{{ $consultation->temperature ?? 'N/A' }}</div>
    </div>
    <div class="vital-box">
      <div class="vital-label">Respiratory Rate</div>
      <div class="vital-value">{{ $consultation->respiratory_rate ?? 'N/A' }}</div>
    </div>
    <div class="vital-box">
      <div class="vital-label">SpO2</div>
      <div class="vital-value">{{ $consultation->spo2 ?? 'N/A' }}</div>
    </div>
    <div class="vital-box">
      <div class="vital-label">Weight</div>
      <div class="vital-value">{{ $consultation->weight ?? 'N/A' }}</div>
    </div>
    <div class="vital-box">
      <div class="vital-label">Height</div>
      <div class="vital-value">{{ $consultation->height ?? 'N/A' }}</div>
    </div>
  </div>
</div>

<!-- Chief Complaint & History -->
<div class="section-card">
  <div class="section-title">
    <i class="bi bi-chat-left-text"></i>
    Chief Complaint & History
  </div>
  
  <div class="mb-3">
    <label class="data-label d-block mb-2">Chief Complaint</label>
    <div class="text-content">
      {{ $consultation->chief_complaint ?? 'Not recorded' }}
    </div>
  </div>
  
  <div class="mb-3">
    <label class="data-label d-block mb-2">History of Present Illness</label>
    <div class="text-content">
      {{ $consultation->history_present_illness ?? 'Not recorded' }}
    </div>
  </div>
  
  @if($consultation->patient->allergies)
    <div class="alert alert-warning">
      <i class="bi bi-exclamation-triangle"></i> <strong>Allergies:</strong> {{ $consultation->patient->allergies }}
    </div>
  @endif
</div>

<!-- Physical Examination -->
@if($consultation->physical_examination)
<div class="section-card">
  <div class="section-title">
    <i class="bi bi-stethoscope"></i>
    Physical Examination
  </div>
  <div class="text-content">
    {{ $consultation->physical_examination }}
  </div>
</div>
@endif

<!-- Diagnosis & Treatment -->
<div class="section-card">
  <div class="section-title">
    <i class="bi bi-clipboard-check"></i>
    Diagnosis & Treatment Plan
  </div>
  
  @if($consultation->provisional_diagnosis)
    <div class="mb-3">
      <label class="data-label d-block mb-2">Provisional Diagnosis</label>
      <div class="text-content">
        {{ $consultation->provisional_diagnosis }}
      </div>
    </div>
  @endif
  
  @if($consultation->final_diagnosis)
    <div class="mb-3">
      <label class="data-label d-block mb-2">Final Diagnosis</label>
      <div class="text-content">
        {{ $consultation->final_diagnosis }}
      </div>
    </div>
  @endif
  
  @if($consultation->treatment_plan)
    <div class="mb-3">
      <label class="data-label d-block mb-2">Treatment Plan</label>
      <div class="text-content">
        {{ $consultation->treatment_plan }}
      </div>
    </div>
  @endif
  
  @if($consultation->advice)
    <div class="mb-3">
      <label class="data-label d-block mb-2">Advice</label>
      <div class="text-content">
        {{ $consultation->advice }}
      </div>
    </div>
  @endif
</div>
