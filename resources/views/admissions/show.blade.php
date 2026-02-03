@extends('layouts.app')

@section('title', 'Admission Details')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('ipd.dashboard') }}">IPD</a></li>
<li class="breadcrumb-item"><a href="{{ route('admissions.index') }}">Admissions</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ $admission->admission_number }}</li>
@endsection

@push('styles')
<style>
  .admission-container { max-width: 1400px; margin: 0 auto; }
  .patient-header { background: #ffffff; border-radius: 12px; padding: 32px; margin-bottom: 24px; border: 1px solid #e5e7eb; }
  .patient-photo { width: 100px; height: 100px; border-radius: 12px; object-fit: cover; border: 2px solid #e5e7eb; }
  .patient-photo-placeholder { width: 100px; height: 100px; border-radius: 12px; background: #111827; display: flex; align-items: center; justify-content: center; color: white; font-size: 40px; font-weight: 600; }
  .patient-name { font-size: 28px; font-weight: 600; color: #111827; margin: 0 0 8px 0; }
  .patient-meta { display: flex; gap: 24px; flex-wrap: wrap; margin-bottom: 16px; }
  .meta-item { display: flex; align-items: center; gap: 6px; font-size: 14px; color: #6b7280; }
  .meta-item i { font-size: 16px; }
  .meta-item strong { color: #111827; }
  .status-badge { padding: 8px 16px; border-radius: 8px; font-weight: 600; font-size: 14px; display: inline-flex; align-items: center; gap: 6px; }
  .status-admitted { background: #d1fae5; color: #065f46; }
  .status-discharged { background: #f3f4f6; color: #374151; }
  .days-badge { background: #f9fafb; border: 1px solid #e5e7eb; padding: 12px 16px; border-radius: 8px; text-align: center; }
  .days-label { font-size: 11px; color: #6b7280; text-transform: uppercase; margin-bottom: 4px; }
  .days-value { font-size: 24px; font-weight: 700; color: #111827; }
  .days-text { font-size: 11px; color: #6b7280; }
  .action-bar { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 24px; }
  .btn-primary-custom { background: #111827; color: white; border: 1px solid #111827; padding: 10px 20px; border-radius: 8px; font-weight: 500; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s; text-decoration: none; }
  .btn-primary-custom:hover { background: #374151; border-color: #374151; color: white; transform: translateY(-1px); }
  .btn-danger-custom { background: #dc2626; color: white; border: 1px solid #dc2626; }
  .btn-danger-custom:hover { background: #991b1b; border-color: #991b1b; }
  .btn-outline-custom { background: white; color: #111827; border: 1px solid #e5e7eb; }
  .btn-outline-custom:hover { background: #f9fafb; border-color: #d1d5db; color: #111827; }
  .info-card { background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; margin-bottom: 20px; }
  .card-title { font-size: 16px; font-weight: 600; color: #111827; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 2px solid #e5e7eb; display: flex; align-items: center; gap: 8px; }
  .card-title i { color: #6b7280; font-size: 18px; }
  .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
  .info-item { margin-bottom: 0; }
  .info-label { font-size: 12px; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
  .info-value { font-size: 14px; color: #111827; font-weight: 500; }
  .vitals-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 16px; }
  .vital-card { background: #f9fafb; padding: 16px; border-radius: 8px; text-align: center; border: 1px solid #e5e7eb; }
  .vital-icon { font-size: 20px; color: #6b7280; margin-bottom: 8px; }
  .vital-label { font-size: 11px; color: #6b7280; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; }
  .vital-value { font-size: 20px; font-weight: 700; color: #111827; margin-bottom: 2px; }
  .vital-unit { font-size: 11px; color: #9ca3af; }
  .alert-card { padding: 16px; border-radius: 8px; margin-bottom: 16px; border-left: 4px solid; }
  .alert-danger { background: #fef2f2; border-color: #dc2626; color: #991b1b; }
  .alert-warning { background: #fffbeb; border-color: #f59e0b; color: #92400e; }
  .alert-info { background: #eff6ff; border-color: #3b82f6; color: #1e40af; }
  .medical-note { background: #f9fafb; border-left: 3px solid #111827; padding: 16px; border-radius: 6px; margin-bottom: 12px; }
  .note-label { font-size: 11px; font-weight: 600; text-transform: uppercase; color: #6b7280; margin-bottom: 6px; }
  .note-content { color: #111827; font-size: 14px; line-height: 1.6; }
  .timeline { position: relative; padding-left: 30px; }
  .timeline::before { content: ''; position: absolute; left: 8px; top: 0; bottom: 0; width: 2px; background: #e5e7eb; }
  .timeline-item { position: relative; padding-bottom: 24px; }
  .timeline-item:last-child { padding-bottom: 0; }
  .timeline-dot { position: absolute; left: -26px; width: 18px; height: 18px; border-radius: 50%; background: white; border: 3px solid #111827; }
  .timeline-time { font-size: 12px; color: #6b7280; margin-bottom: 4px; }
  .timeline-content { font-size: 14px; color: #111827; }
  .timeline-user { font-size: 12px; color: #6b7280; margin-top: 4px; }
</style>
@endpush

@section('content')
<div class="admission-container">
  <!-- Patient Header -->
  <div class="patient-header">
    <div class="d-flex gap-3 align-items-start">
      <div style="flex-shrink: 0;">
        @if($admission->patient->photo)
          <img src="{{ asset('storage/' . $admission->patient->photo) }}" alt="{{ $admission->patient->full_name }}" class="patient-photo">
        @else
          <div class="patient-photo-placeholder">
            {{ substr($admission->patient->first_name, 0, 1) }}{{ substr($admission->patient->last_name, 0, 1) }}
          </div>
        @endif
      </div>

      <div style="flex: 1;">
        <h1 class="patient-name">{{ $admission->patient->full_name }}</h1>
        
        <div class="patient-meta">
          <div class="meta-item">
            <i class="bi bi-hospital"></i>
            <strong>{{ $admission->admission_number }}</strong>
          </div>
          <div class="meta-item">
            <i class="bi bi-person-badge"></i>
            {{ $admission->patient->patient_id }}
          </div>
          <div class="meta-item">
            <i class="bi bi-calendar3"></i>
            {{ $admission->patient->age }} years
          </div>
          <div class="meta-item">
            <i class="bi bi-gender-{{ strtolower($admission->patient->gender) }}"></i>
            {{ ucfirst($admission->patient->gender) }}
          </div>
          <div class="meta-item">
            <i class="bi bi-telephone"></i>
            {{ $admission->patient->phone }}
          </div>
          @if($admission->patient->blood_group)
          <div class="meta-item">
            <i class="bi bi-droplet-fill"></i>
            <strong>{{ $admission->patient->blood_group }}</strong>
          </div>
          @endif
        </div>

        @if($admission->patient->allergies || $admission->patient->chronic_conditions)
        <div class="d-flex gap-2 flex-wrap">
          @if($admission->patient->allergies)
          <div class="alert-card alert-danger" style="display: inline-flex; align-items: center; gap: 8px; margin: 0; padding: 8px 12px;">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span style="font-size: 13px;"><strong>Allergies:</strong> {{ $admission->patient->allergies }}</span>
          </div>
          @endif
          @if($admission->patient->chronic_conditions)
          <div class="alert-card alert-warning" style="display: inline-flex; align-items: center; gap: 8px; margin: 0; padding: 8px 12px;">
            <i class="bi bi-heart-pulse-fill"></i>
            <span style="font-size: 13px;"><strong>Conditions:</strong> {{ $admission->patient->chronic_conditions }}</span>
          </div>
          @endif
        </div>
        @endif
      </div>

      <div style="text-align: center;">
        @if($admission->status === 'admitted')
          <div class="status-badge status-admitted">
            <i class="bi bi-hospital-fill"></i>
            ADMITTED
          </div>
        @else
          <div class="status-badge status-discharged">
            <i class="bi bi-box-arrow-right"></i>
            DISCHARGED
          </div>
        @endif

        @php
          $daysAdmitted = (int) \Carbon\Carbon::parse($admission->admission_date)->diffInDays($admission->status === 'discharged' && $admission->discharge_date ? $admission->discharge_date : now());
        @endphp
        <div class="days-badge" style="margin-top: 16px;">
          <div class="days-label">{{ $admission->status === 'admitted' ? 'Days Admitted' : 'Total Stay' }}</div>
          <div class="days-value">{{ $daysAdmitted }}</div>
          <div class="days-text">{{ $daysAdmitted === 1 ? 'day' : 'days' }}</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Action Buttons -->
  <div class="action-bar">
    @if($admission->status === 'admitted')
      <a href="{{ route('admissions.edit', $admission) }}" class="btn-primary-custom">
        <i class="bi bi-pencil"></i> Edit Admission
      </a>
      <a href="{{ route('admissions.discharge-form', $admission) }}" class="btn-primary-custom btn-danger-custom">
        <i class="bi bi-box-arrow-right"></i> Discharge Patient
      </a>
      <button class="btn-primary-custom btn-outline-custom" data-bs-toggle="modal" data-bs-target="#transferModal">
        <i class="bi bi-arrow-left-right"></i> Transfer Ward/Bed
      </button>
    @endif
    <a href="{{ route('patients.show', $admission->patient) }}" class="btn-primary-custom btn-outline-custom">
      <i class="bi bi-person"></i> View Patient Profile
    </a>
    <a href="{{ route('admissions.index') }}" class="btn-primary-custom btn-outline-custom">
      <i class="bi bi-arrow-left"></i> Back to Admissions
    </a>
  </div>

  <div class="row">
    <div class="col-md-8">
      <!-- Admission Information -->
      <div class="info-card">
        <h5 class="card-title">
          <i class="bi bi-calendar-check"></i>
          Admission Information
        </h5>
        <div class="info-grid">
          <div class="info-item">
            <div class="info-label">Admission Date</div>
            <div class="info-value">{{ \Carbon\Carbon::parse($admission->admission_date)->format('M d, Y h:i A') }}</div>
          </div>
          <div class="info-item">
            <div class="info-label">Admission Type</div>
            <div class="info-value">
              <span style="padding: 4px 10px; background: {{ $admission->admission_type === 'emergency' ? '#fef2f2' : '#eff6ff' }}; color: {{ $admission->admission_type === 'emergency' ? '#991b1b' : '#1e40af' }}; border-radius: 6px; font-size: 12px; font-weight: 600;">
                {{ ucfirst($admission->admission_type) }}
              </span>
            </div>
          </div>
          <div class="info-item">
            <div class="info-label">Category</div>
            <div class="info-value">{{ ucfirst($admission->admission_category) }}</div>
          </div>
          <div class="info-item">
            <div class="info-label">Attending Doctor</div>
            <div class="info-value">
              Dr. {{ $admission->doctor->name }}
              @if($admission->doctor->specialization)
                <br><small style="color: #6b7280;">{{ $admission->doctor->specialization }}</small>
              @endif
            </div>
          </div>
          <div class="info-item">
            <div class="info-label">Ward</div>
            <div class="info-value">
              <a href="{{ route('wards.show', $admission->ward) }}" style="color: #111827; text-decoration: underline;">
                {{ $admission->ward->ward_name }}
              </a>
              <br><small style="color: #6b7280;">{{ ucfirst($admission->ward->ward_type) }} Ward</small>
            </div>
          </div>
          <div class="info-item">
            <div class="info-label">Bed Assignment</div>
            <div class="info-value">
              {{ $admission->bed ? $admission->bed->bed_number : 'Not Assigned' }}
              @if($admission->bed)
                <br><small style="color: #6b7280;">{{ ucfirst($admission->bed->bed_type) }}</small>
              @endif
            </div>
          </div>
          <div class="info-item">
            <div class="info-label">Admitted By</div>
            <div class="info-value">{{ $admission->admittedBy->name }}</div>
          </div>
          @if($admission->estimated_stay_days)
          <div class="info-item">
            <div class="info-label">Estimated Stay</div>
            <div class="info-value">{{ $admission->estimated_stay_days }} days</div>
          </div>
          @endif
        </div>
      </div>

      <!-- Clinical Information -->
      <div class="info-card">
        <h5 class="card-title">
          <i class="bi bi-clipboard-pulse"></i>
          Clinical Information
        </h5>
        
        @if($admission->provisional_diagnosis)
        <div class="medical-note">
          <div class="note-label">Provisional Diagnosis</div>
          <div class="note-content">{{ $admission->provisional_diagnosis }}</div>
        </div>
        @endif

        @if($admission->reason_for_admission)
        <div class="medical-note" style="border-color: #6b7280;">
          <div class="note-label">Reason for Admission</div>
          <div class="note-content">{{ $admission->reason_for_admission }}</div>
        </div>
        @endif

        @if($admission->complaints)
        <div class="medical-note" style="border-color: #6b7280;">
          <div class="note-label">Chief Complaints</div>
          <div class="note-content">{{ $admission->complaints }}</div>
        </div>
        @endif

        @if($admission->medical_history)
        <div class="medical-note" style="border-color: #9ca3af;">
          <div class="note-label">Medical History</div>
          <div class="note-content">{{ $admission->medical_history }}</div>
        </div>
        @endif

        @if($admission->admission_notes)
        <div class="medical-note" style="border-color: #9ca3af;">
          <div class="note-label">Admission Notes</div>
          <div class="note-content">{{ $admission->admission_notes }}</div>
        </div>
        @endif

        @if($admission->special_instructions)
        <div class="medical-note" style="border-color: #dc2626;">
          <div class="note-label"><i class="bi bi-exclamation-triangle"></i> Special Instructions</div>
          <div class="note-content">{{ $admission->special_instructions }}</div>
        </div>
        @endif
      </div>

      <!-- Vitals at Admission -->
      @if($admission->blood_pressure || $admission->temperature || $admission->pulse_rate)
      <div class="info-card">
        <h5 class="card-title">
          <i class="bi bi-heart-pulse"></i>
          Vitals at Admission
        </h5>
        <div class="vitals-grid">
          @if($admission->blood_pressure)
          <div class="vital-card">
            <div class="vital-icon"><i class="bi bi-activity"></i></div>
            <div class="vital-label">Blood Pressure</div>
            <div class="vital-value">{{ $admission->blood_pressure }}</div>
            <div class="vital-unit">mmHg</div>
          </div>
          @endif

          @if($admission->temperature)
          <div class="vital-card">
            <div class="vital-icon"><i class="bi bi-thermometer"></i></div>
            <div class="vital-label">Temperature</div>
            <div class="vital-value">{{ $admission->temperature }}</div>
            <div class="vital-unit">°C</div>
          </div>
          @endif

          @if($admission->pulse_rate)
          <div class="vital-card">
            <div class="vital-icon"><i class="bi bi-heart"></i></div>
            <div class="vital-label">Pulse Rate</div>
            <div class="vital-value">{{ $admission->pulse_rate }}</div>
            <div class="vital-unit">bpm</div>
          </div>
          @endif

          @if($admission->respiratory_rate)
          <div class="vital-card">
            <div class="vital-icon"><i class="bi bi-lungs"></i></div>
            <div class="vital-label">Respiratory Rate</div>
            <div class="vital-value">{{ $admission->respiratory_rate }}</div>
            <div class="vital-unit">breaths/min</div>
          </div>
          @endif

          @if($admission->oxygen_saturation)
          <div class="vital-card">
            <div class="vital-icon"><i class="bi bi-droplet"></i></div>
            <div class="vital-label">SpO2</div>
            <div class="vital-value">{{ $admission->oxygen_saturation }}</div>
            <div class="vital-unit">%</div>
          </div>
          @endif
        </div>
      </div>
      @endif

      <!-- Discharge Information -->
      @if($admission->status === 'discharged' && $admission->discharge_date)
      <div class="info-card">
        <h5 class="card-title">
          <i class="bi bi-box-arrow-right"></i>
          Discharge Information
        </h5>
        <div class="info-grid" style="margin-bottom: 20px;">
          <div class="info-item">
            <div class="info-label">Discharge Date</div>
            <div class="info-value">{{ \Carbon\Carbon::parse($admission->discharge_date)->format('M d, Y h:i A') }}</div>
          </div>
          <div class="info-item">
            <div class="info-label">Discharged By</div>
            <div class="info-value">{{ $admission->dischargedBy ? $admission->dischargedBy->name : 'N/A' }}</div>
          </div>
          <div class="info-item">
            <div class="info-label">Total Stay</div>
            <div class="info-value">{{ $daysAdmitted }} days</div>
          </div>
        </div>

        @if($admission->discharge_summary)
        <div class="medical-note">
          <div class="note-label">Discharge Summary</div>
          <div class="note-content">{{ $admission->discharge_summary }}</div>
        </div>
        @endif

        @if($admission->discharge_instructions)
        <div class="medical-note" style="border-color: #6b7280;">
          <div class="note-label">Discharge Instructions</div>
          <div class="note-content">{{ $admission->discharge_instructions }}</div>
        </div>
        @endif

        @if($admission->follow_up_instructions)
        <div class="medical-note" style="border-color: #9ca3af;">
          <div class="note-label">Follow-up Instructions</div>
          <div class="note-content">{{ $admission->follow_up_instructions }}</div>
        </div>
        @endif
      </div>
      @endif
    </div>

    <!-- Right Column - Continued in next part -->
    <div class="col-md-4">
      <!-- Payment & Billing -->
      <div class="info-card">
        <h5 class="card-title">
          <i class="bi bi-credit-card"></i>
          Payment & Billing
        </h5>
        <div class="info-item" style="margin-bottom: 16px;">
          <div class="info-label">Payment Mode</div>
          <div class="info-value">
            <span style="padding: 4px 10px; background: {{ $admission->payment_mode === 'cash' ? '#d1fae5' : '#dbeafe' }}; color: {{ $admission->payment_mode === 'cash' ? '#065f46' : '#1e40af' }}; border-radius: 6px; font-size: 12px; font-weight: 600;">
              {{ ucfirst($admission->payment_mode) }}
            </span>
          </div>
        </div>

        @if($admission->payment_mode === 'insurance')
          @if($admission->insurance_company)
          <div class="info-item" style="margin-bottom: 12px;">
            <div class="info-label">Insurance Company</div>
            <div class="info-value">{{ $admission->insurance_company }}</div>
          </div>
          @endif
          @if($admission->insurance_policy_number)
          <div class="info-item" style="margin-bottom: 12px;">
            <div class="info-label">Policy Number</div>
            <div class="info-value">{{ $admission->insurance_policy_number }}</div>
          </div>
          @endif
        @endif

        <div style="background: #f9fafb; padding: 16px; border-radius: 8px; margin-top: 16px;">
          @if($admission->advance_payment > 0)
          <div style="display: flex; justify-content: space-between; padding: 8px 0;">
            <span style="color: #6b7280; font-size: 13px;">Advance Payment</span>
            <strong style="color: #111827; font-size: 14px;">TSh {{ number_format($admission->advance_payment, 2) }}</strong>
          </div>
          @endif

          @php
            // Get ward charge from settings
            $wardTypeMapping = [
              'general' => 'ipd_general_ward_charge',
              'private' => 'ipd_private_ward_charge',
              'icu' => 'ipd_icu_charge',
              'nicu' => 'ipd_nicu_charge',
              'maternity' => 'ipd_maternity_charge',
              'pediatric' => 'ipd_pediatric_charge',
            ];
            $settingKey = $wardTypeMapping[$admission->ward->ward_type] ?? 'ipd_general_ward_charge';
            $wardCharge = \App\Models\Setting::where('key', $settingKey)->value('value') ?? $admission->ward->base_charge_per_day;
            $estimatedTotal = $wardCharge * max($daysAdmitted, 1);
          @endphp

          <div style="display: flex; justify-content: space-between; padding: 8px 0; border-top: 1px solid #e5e7eb;">
            <span style="color: #6b7280; font-size: 13px;">Ward Charge/Day</span>
            <strong style="color: #111827; font-size: 14px;">TSh {{ number_format($wardCharge, 2) }}</strong>
          </div>

          <div style="display: flex; justify-content: space-between; padding: 8px 0; border-top: 1px solid #e5e7eb;">
            <span style="color: #6b7280; font-size: 13px;">Days × Rate</span>
            <span style="color: #111827; font-size: 13px;">{{ $daysAdmitted }} × TSh {{ number_format($wardCharge, 2) }}</span>
          </div>

          <div style="display: flex; justify-content: space-between; padding: 12px 0; border-top: 2px solid #e5e7eb; margin-top: 8px;">
            <span style="color: #111827; font-size: 14px; font-weight: 600;">Estimated Total</span>
            <strong style="color: #111827; font-size: 16px;">TSh {{ number_format($estimatedTotal, 2) }}</strong>
          </div>
        </div>

        @if($admission->status === 'admitted')
        <div class="alert-card alert-info" style="margin-top: 16px; margin-bottom: 0;">
          <i class="bi bi-info-circle"></i>
          <span style="font-size: 12px;">Final charges calculated at discharge</span>
        </div>
        @endif
      </div>

      <!-- Emergency Contact -->
      @php
        $emergencyName = $admission->emergency_contact_name ?? $admission->patient->emergency_contact_name;
        $emergencyRelation = $admission->emergency_contact_relation ?? $admission->patient->emergency_contact_relation;
        $emergencyPhone = $admission->emergency_contact_phone ?? $admission->patient->emergency_contact_phone;
      @endphp
      @if($emergencyName || $emergencyPhone)
      <div class="info-card">
        <h5 class="card-title">
          <i class="bi bi-telephone-forward"></i>
          Emergency Contact
        </h5>
        @if($emergencyName)
        <div class="info-item" style="margin-bottom: 12px;">
          <div class="info-label">Name</div>
          <div class="info-value">{{ $emergencyName }}</div>
        </div>
        @endif
        @if($emergencyRelation)
        <div class="info-item" style="margin-bottom: 12px;">
          <div class="info-label">Relation</div>
          <div class="info-value">{{ ucfirst($emergencyRelation) }}</div>
        </div>
        @endif
        @if($emergencyPhone)
        <div class="info-item">
          <div class="info-label">Phone</div>
          <div class="info-value">
            <a href="tel:{{ $emergencyPhone }}" style="color: #111827;">{{ $emergencyPhone }}</a>
          </div>
        </div>
        @endif
      </div>
      @endif

      <!-- Timeline -->
      <div class="info-card">
        <h5 class="card-title">
          <i class="bi bi-clock-history"></i>
          Timeline
        </h5>
        <div class="timeline">
          <div class="timeline-item">
            <div class="timeline-dot"></div>
            <div class="timeline-time">{{ \Carbon\Carbon::parse($admission->admission_date)->format('M d, Y h:i A') }}</div>
            <div class="timeline-content"><strong>Patient Admitted</strong></div>
            <div class="timeline-user">by {{ $admission->admittedBy->name }}</div>
          </div>

          @if($admission->status === 'discharged' && $admission->discharge_date)
          <div class="timeline-item">
            <div class="timeline-dot" style="border-color: #6b7280;"></div>
            <div class="timeline-time">{{ \Carbon\Carbon::parse($admission->discharge_date)->format('M d, Y h:i A') }}</div>
            <div class="timeline-content"><strong>Patient Discharged</strong></div>
            <div class="timeline-user">by {{ $admission->dischargedBy ? $admission->dischargedBy->name : 'N/A' }}</div>
          </div>
          @endif
        </div>
      </div>

      <!-- Quick Actions -->
      @if($admission->status === 'admitted')
      <div class="info-card" style="background: #f9fafb;">
        <h5 class="card-title">
          <i class="bi bi-lightning"></i>
          Quick Actions
        </h5>
        <div style="display: grid; gap: 10px;">
          <a href="{{ route('patients.show', $admission->patient) }}" class="btn-primary-custom btn-outline-custom" style="width: 100%; justify-content: center;">
            <i class="bi bi-person-lines-fill"></i>
            View Patient History
          </a>
          <a href="{{ route('wards.show', $admission->ward) }}" class="btn-primary-custom btn-outline-custom" style="width: 100%; justify-content: center;">
            <i class="bi bi-hospital"></i>
            View Ward Details
          </a>
        </div>
      </div>
      @endif
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
          <div class="alert-card alert-info">
            <i class="bi bi-info-circle"></i> Transfer <strong>{{ $admission->patient->full_name }}</strong> to a different ward or bed
          </div>
          
          <div class="mb-3">
            <label class="form-label">New Ward</label>
            <select name="ward_id" class="form-select" required onchange="loadTransferBeds(this.value)">
              <option value="">Select ward...</option>
              @foreach(\App\Models\Ward::where('is_active', true)->get() as $ward)
                <option value="{{ $ward->id }}" {{ $ward->id == $admission->ward_id ? 'selected' : '' }}>
                  {{ $ward->ward_name }} ({{ $ward->beds()->where('status', 'available')->count() }} available)
                </option>
              @endforeach
            </select>
          </div>
          
          <div class="mb-3">
            <label class="form-label">New Bed</label>
            <select name="bed_id" id="transferBedSelect" class="form-select">
              <option value="">Select ward first...</option>
            </select>
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
