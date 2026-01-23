<!-- Compact Patient Information Grid -->
<div class="row g-3">
  <!-- Patient Details -->
  <div class="col-md-6">
    <div class="section-card" style="padding: 16px; height: 100%;">
      <div class="section-title" style="margin-bottom: 12px; font-size: 14px;">
        <i class="bi bi-person"></i>
        Patient Information
      </div>
      
      <div class="data-row" style="padding: 6px 0;">
        <span class="data-label" style="font-size: 12px;">Patient Name</span>
        <span class="data-value" style="font-size: 13px;">{{ $consultation->patient->full_name }}</span>
      </div>
      
      <div class="data-row" style="padding: 6px 0;">
        <span class="data-label" style="font-size: 12px;">Patient ID</span>
        <span class="data-value" style="font-size: 13px;">{{ $consultation->patient->patient_id }}</span>
      </div>
      
      <div class="data-row" style="padding: 6px 0;">
        <span class="data-label" style="font-size: 12px;">Age</span>
        <span class="data-value" style="font-size: 13px;">{{ $consultation->patient->age }} years</span>
      </div>
      
      <div class="data-row" style="padding: 6px 0;">
        <span class="data-label" style="font-size: 12px;">Gender</span>
        <span class="data-value" style="font-size: 13px;">{{ ucfirst($consultation->patient->gender) }}</span>
      </div>
      
      @if($consultation->patient->blood_group)
        <div class="data-row" style="padding: 6px 0;">
          <span class="data-label" style="font-size: 12px;">Blood Group</span>
          <span class="data-value" style="font-size: 13px;">{{ $consultation->patient->blood_group }}</span>
        </div>
      @endif
      
      <div class="data-row" style="padding: 6px 0;">
        <span class="data-label" style="font-size: 12px;">Phone</span>
        <span class="data-value" style="font-size: 13px;">{{ $consultation->patient->phone }}</span>
      </div>
      
      @if($consultation->patient->email)
        <div class="data-row" style="padding: 6px 0;">
          <span class="data-label" style="font-size: 12px;">Email</span>
          <span class="data-value" style="font-size: 13px;">{{ $consultation->patient->email }}</span>
        </div>
      @endif
      
      @if($consultation->patient->address)
        <div class="data-row" style="padding: 6px 0; border-bottom: none;">
          <span class="data-label" style="font-size: 12px;">Address</span>
          <span class="data-value" style="font-size: 13px;">{{ $consultation->patient->address }}</span>
        </div>
      @endif
      
      @if($consultation->patient->allergies)
        <div class="mt-2">
          <div class="alert alert-danger" style="padding: 8px 12px; margin: 0; font-size: 12px;">
            <i class="bi bi-exclamation-triangle"></i> <strong>Allergies:</strong> {{ $consultation->patient->allergies }}
          </div>
        </div>
      @endif
    </div>
  </div>

  <!-- Appointment & Doctor Details -->
  <div class="col-md-6">
    <!-- Appointment Details -->
    @if($consultation->appointment)
    <div class="section-card" style="padding: 16px; margin-bottom: 16px;">
      <div class="section-title" style="margin-bottom: 12px; font-size: 14px;">
        <i class="bi bi-calendar-check"></i>
        Appointment Details
      </div>
      
      <div class="data-row" style="padding: 6px 0;">
        <span class="data-label" style="font-size: 12px;">Appointment Number</span>
        <span class="data-value" style="font-size: 13px;">{{ $consultation->appointment->appointment_number }}</span>
      </div>
      
      <div class="data-row" style="padding: 6px 0;">
        <span class="data-label" style="font-size: 12px;">Scheduled Date</span>
        <span class="data-value" style="font-size: 13px;">{{ $consultation->appointment->appointment_date->format('M d, Y') }}</span>
      </div>
      
      <div class="data-row" style="padding: 6px 0;">
        <span class="data-label" style="font-size: 12px;">Time Slot</span>
        <span class="data-value" style="font-size: 13px;">{{ $consultation->appointment->appointment_time }}</span>
      </div>
      
      <div class="data-row" style="padding: 6px 0;">
        <span class="data-label" style="font-size: 12px;">Type</span>
        <span class="data-value" style="font-size: 13px;">{{ ucfirst($consultation->appointment->appointment_type) }}</span>
      </div>
      
      <div class="data-row" style="padding: 6px 0; border-bottom: none;">
        <span class="data-label" style="font-size: 12px;">Token Number</span>
        <span class="data-value" style="font-size: 13px;">{{ $consultation->appointment->token_number ?? 'N/A' }}</span>
      </div>
    </div>
    @endif

    <!-- Consulting Doctor -->
    <div class="section-card" style="padding: 16px;">
      <div class="section-title" style="margin-bottom: 12px; font-size: 14px;">
        <i class="bi bi-person-badge"></i>
        Consulting Doctor
      </div>
      
      <div class="data-row" style="padding: 6px 0;">
        <span class="data-label" style="font-size: 12px;">Doctor Name</span>
        <span class="data-value" style="font-size: 13px;">Dr. {{ $consultation->doctor->name }}</span>
      </div>
      
      @if($consultation->doctor->specialization)
        <div class="data-row" style="padding: 6px 0;">
          <span class="data-label" style="font-size: 12px;">Specialization</span>
          <span class="data-value" style="font-size: 13px;">{{ $consultation->doctor->specialization }}</span>
        </div>
      @endif
      
      @if($consultation->doctor->qualifications)
        <div class="data-row" style="padding: 6px 0;">
          <span class="data-label" style="font-size: 12px;">Qualifications</span>
          <span class="data-value" style="font-size: 13px;">{{ $consultation->doctor->qualifications }}</span>
        </div>
      @endif
      
      <div class="data-row" style="padding: 6px 0; border-bottom: none;">
        <span class="data-label" style="font-size: 12px;">Department</span>
        <span class="data-value" style="font-size: 13px;">{{ $consultation->doctor->department ?? 'General' }}</span>
      </div>
    </div>
  </div>
</div>
