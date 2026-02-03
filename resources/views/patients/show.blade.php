@extends('layouts.app')

@section('title', 'Patient Profile')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('patients.index') }}">Patients</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ $patient->full_name }}</li>
@endsection

@push('styles')
<style>
  .patient-container { max-width: 1600px; margin: 0 auto; }
  .patient-header { background: #ffffff; border-radius: 12px; padding: 32px; margin-bottom: 24px; border: 1px solid #e5e7eb; }
  .patient-photo { width: 100px; height: 100px; border-radius: 12px; object-fit: cover; border: 2px solid #e5e7eb; }
  .patient-photo-placeholder { width: 100px; height: 100px; border-radius: 12px; background: #111827; display: flex; align-items: center; justify-content: center; color: white; font-size: 40px; font-weight: 600; }
  .patient-name { font-size: 28px; font-weight: 600; color: #111827; margin: 0 0 8px 0; }
  .patient-meta { display: flex; gap: 24px; flex-wrap: wrap; margin-bottom: 16px; }
  .meta-item { display: flex; align-items: center; gap: 6px; font-size: 14px; color: #6b7280; }
  .meta-item i { font-size: 16px; }
  .meta-item strong { color: #111827; }
  .status-badge { padding: 8px 16px; border-radius: 8px; font-weight: 600; font-size: 14px; display: inline-flex; align-items: center; gap: 6px; }
  .status-active { background: #d1fae5; color: #065f46; }
  .status-inactive { background: #f3f4f6; color: #6b7280; }
  .action-bar { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 24px; }
  .btn-primary-custom { background: #111827; color: white; border: 1px solid #111827; padding: 10px 20px; border-radius: 8px; font-weight: 500; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s; text-decoration: none; }
  .btn-primary-custom:hover { background: #374151; border-color: #374151; color: white; transform: translateY(-1px); }
  .btn-outline-custom { background: white; color: #111827; border: 1px solid #e5e7eb; }
  .btn-outline-custom:hover { background: #f9fafb; border-color: #d1d5db; color: #111827; }
  .info-card { background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; margin-bottom: 20px; }
  .card-title { font-size: 16px; font-weight: 600; color: #111827; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 2px solid #e5e7eb; display: flex; align-items: center; gap: 8px; }
  .card-title i { color: #6b7280; font-size: 18px; }
  .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
  .info-item { margin-bottom: 0; }
  .info-label { font-size: 12px; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
  .info-value { font-size: 14px; color: #111827; font-weight: 500; }
  .alert-card { padding: 16px; border-radius: 8px; margin-bottom: 16px; border-left: 4px solid; }
  .alert-danger { background: #fef2f2; border-color: #dc2626; color: #991b1b; }
  .alert-warning { background: #fffbeb; border-color: #f59e0b; color: #92400e; }
  .medical-note { background: #f9fafb; border-left: 3px solid #111827; padding: 16px; border-radius: 6px; margin-bottom: 12px; }
  .note-label { font-size: 11px; font-weight: 600; text-transform: uppercase; color: #6b7280; margin-bottom: 6px; }
  .note-content { color: #111827; font-size: 14px; line-height: 1.6; }
  .history-table { width: 100%; }
  .history-table thead th { background: #f9fafb; color: #6b7280; font-size: 12px; font-weight: 600; text-transform: uppercase; padding: 12px; border-bottom: 2px solid #e5e7eb; }
  .history-table tbody td { padding: 12px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #111827; }
  .history-table tbody tr:hover { background: #f9fafb; }
  .badge-status { padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; display: inline-block; }
  .badge-admitted { background: #d1fae5; color: #065f46; }
  .badge-discharged { background: #f3f4f6; color: #374151; }
  .badge-completed { background: #dbeafe; color: #1e40af; }
  .badge-pending { background: #fef3c7; color: #92400e; }
  .badge-paid { background: #d1fae5; color: #065f46; }
  .badge-unpaid { background: #fee2e2; color: #991b1b; }
  .empty-state { text-align: center; padding: 48px 24px; color: #9ca3af; }
  .empty-icon { font-size: 48px; margin-bottom: 12px; opacity: 0.5; }
  .empty-text { font-size: 14px; }
  .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 16px; margin-bottom: 24px; }
  .stat-card { background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; text-align: center; }
  .stat-value { font-size: 32px; font-weight: 700; color: #111827; margin-bottom: 4px; }
  .stat-label { font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; }
  .nav-tabs-custom { border-bottom: 2px solid #e5e7eb; margin-bottom: 24px; }
  .nav-tabs-custom .nav-link { border: none; background: none; color: #6b7280; font-weight: 500; padding: 12px 24px; border-bottom: 3px solid transparent; }
  .nav-tabs-custom .nav-link:hover { color: #111827; }
  .nav-tabs-custom .nav-link.active { color: #111827; border-bottom-color: #111827; background: none; }
</style>
@endpush

@section('content')
<div class="patient-container">
  <!-- Patient Header -->
  <div class="patient-header">
    <div class="d-flex gap-3 align-items-start">
      <div style="flex-shrink: 0;">
        @if($patient->photo)
          <img src="{{ asset('storage/' . $patient->photo) }}" alt="{{ $patient->full_name }}" class="patient-photo">
        @else
          <div class="patient-photo-placeholder">
            {{ substr($patient->first_name, 0, 1) }}{{ substr($patient->last_name, 0, 1) }}
          </div>
        @endif
      </div>

      <div style="flex: 1;">
        <h1 class="patient-name">{{ $patient->full_name }}</h1>
        
        <div class="patient-meta">
          <div class="meta-item">
            <i class="bi bi-person-badge"></i>
            <strong>{{ $patient->patient_id }}</strong>
          </div>
          <div class="meta-item">
            <i class="bi bi-calendar3"></i>
            {{ $patient->age }} years old
          </div>
          <div class="meta-item">
            <i class="bi bi-gender-{{ strtolower($patient->gender) }}"></i>
            {{ ucfirst($patient->gender) }}
          </div>
          <div class="meta-item">
            <i class="bi bi-telephone"></i>
            {{ $patient->phone }}
          </div>
          @if($patient->blood_group)
          <div class="meta-item">
            <i class="bi bi-droplet-fill"></i>
            <strong>{{ $patient->blood_group }}</strong>
          </div>
          @endif
          <div class="meta-item">
            <i class="bi bi-calendar-check"></i>
            Registered {{ $patient->created_at->format('M d, Y') }}
          </div>
        </div>

        @if($patient->allergies || $patient->chronic_conditions)
        <div class="d-flex gap-2 flex-wrap">
          @if($patient->allergies)
          <div class="alert-card alert-danger" style="display: inline-flex; align-items: center; gap: 8px; margin: 0; padding: 8px 12px;">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span style="font-size: 13px;"><strong>Allergies:</strong> {{ $patient->allergies }}</span>
          </div>
          @endif
          @if($patient->chronic_conditions)
          <div class="alert-card alert-warning" style="display: inline-flex; align-items: center; gap: 8px; margin: 0; padding: 8px 12px;">
            <i class="bi bi-heart-pulse-fill"></i>
            <span style="font-size: 13px;"><strong>Conditions:</strong> {{ $patient->chronic_conditions }}</span>
          </div>
          @endif
        </div>
        @endif
      </div>

      <div style="text-align: right;">
        <div class="status-badge {{ $patient->is_active ? 'status-active' : 'status-inactive' }}">
          <i class="bi bi-{{ $patient->is_active ? 'check-circle-fill' : 'x-circle-fill' }}"></i>
          {{ $patient->is_active ? 'ACTIVE' : 'INACTIVE' }}
        </div>
      </div>
    </div>
  </div>

  <!-- Action Buttons -->
  <div class="action-bar">
    <a href="{{ route('appointments.create', ['patient_id' => $patient->id]) }}" class="btn-primary-custom">
      <i class="bi bi-calendar-plus"></i> New Appointment
    </a>
    <a href="{{ route('patients.edit', $patient) }}" class="btn-primary-custom btn-outline-custom">
      <i class="bi bi-pencil"></i> Edit Patient
    </a>
    <button class="btn-primary-custom btn-outline-custom" onclick="window.print()">
      <i class="bi bi-printer"></i> Print Profile
    </button>
    <a href="{{ route('patients.index') }}" class="btn-primary-custom btn-outline-custom">
      <i class="bi bi-arrow-left"></i> Back to Patients
    </a>
  </div>

  <!-- Statistics -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-value">{{ $admissions->count() }}</div>
      <div class="stat-label">Total Admissions</div>
    </div>
    <div class="stat-card">
      <div class="stat-value">{{ $consultations->count() }}</div>
      <div class="stat-label">Consultations</div>
    </div>
    <div class="stat-card">
      <div class="stat-value">{{ $consultations->sum(function($c) { return $c->prescriptions->count(); }) + $standalonePrescriptions->count() }}</div>
      <div class="stat-label">Prescriptions</div>
    </div>
    <div class="stat-card">
      <div class="stat-value">{{ $labOrders->count() }}</div>
      <div class="stat-label">Lab Orders</div>
    </div>
    <div class="stat-card">
      <div class="stat-value">{{ $patient->appointments->count() }}</div>
      <div class="stat-label">Appointments</div>
    </div>
    <div class="stat-card">
      <div class="stat-value">TSh {{ number_format($patient->bills->sum('total_amount'), 2) }}</div>
      <div class="stat-label">Total Bills</div>
    </div>
  </div>

  <!-- Tabs Navigation -->
  <ul class="nav nav-tabs-custom" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" data-bs-toggle="tab" href="#overview">
        <i class="bi bi-info-circle me-1"></i> Overview
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="tab" href="#admissions">
        <i class="bi bi-hospital me-1"></i> Admissions ({{ $admissions->count() }})
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="tab" href="#consultations">
        <i class="bi bi-clipboard-pulse me-1"></i> Consultations & Prescriptions ({{ $consultations->count() }})
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="tab" href="#laborders">
        <i class="bi bi-prescription2 me-1"></i> Lab Orders ({{ $labOrders->count() }})
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="tab" href="#billing">
        <i class="bi bi-credit-card me-1"></i> Billing ({{ $patient->bills->count() }})
      </a>
    </li>
  </ul>

  <!-- Tab Content -->
  <div class="tab-content">
    <!-- Overview Tab -->
    <div class="tab-pane fade show active" id="overview">
      <div class="row">
        <div class="col-md-6">
          <!-- Personal Information -->
          <div class="info-card">
            <h5 class="card-title">
              <i class="bi bi-person"></i>
              Personal Information
            </h5>
            <div class="info-grid">
              <div class="info-item">
                <div class="info-label">Full Name</div>
                <div class="info-value">{{ $patient->full_name }}</div>
              </div>
              <div class="info-item">
                <div class="info-label">Date of Birth</div>
                <div class="info-value">{{ $patient->date_of_birth?->format('M d, Y') }}</div>
              </div>
              <div class="info-item">
                <div class="info-label">Age</div>
                <div class="info-value">{{ $patient->age }} years</div>
              </div>
              <div class="info-item">
                <div class="info-label">Gender</div>
                <div class="info-value">{{ ucfirst($patient->gender) }}</div>
              </div>
              <div class="info-item">
                <div class="info-label">Blood Group</div>
                <div class="info-value">{{ $patient->blood_group ?? 'Not specified' }}</div>
              </div>
              <div class="info-item">
                <div class="info-label">Marital Status</div>
                <div class="info-value">{{ $patient->marital_status ? ucfirst($patient->marital_status) : 'Not specified' }}</div>
              </div>
              <div class="info-item">
                <div class="info-label">Occupation</div>
                <div class="info-value">{{ $patient->occupation ?? 'Not specified' }}</div>
              </div>
            </div>
          </div>

          <!-- Contact Information -->
          <div class="info-card">
            <h5 class="card-title">
              <i class="bi bi-telephone"></i>
              Contact Information
            </h5>
            <div class="info-grid">
              <div class="info-item">
                <div class="info-label">Phone</div>
                <div class="info-value">{{ $patient->phone }}</div>
              </div>
              <div class="info-item">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $patient->email ?? 'Not provided' }}</div>
              </div>
              <div class="info-item">
                <div class="info-label">Address</div>
                <div class="info-value">{{ $patient->address }}</div>
              </div>
              <div class="info-item">
                <div class="info-label">City</div>
                <div class="info-value">{{ $patient->city }}</div>
              </div>
              <div class="info-item">
                <div class="info-label">State/County</div>
                <div class="info-value">{{ $patient->state }}</div>
              </div>
              <div class="info-item">
                <div class="info-label">Country</div>
                <div class="info-value">{{ $patient->country }}</div>
              </div>
            </div>
          </div>

          <!-- Emergency Contact -->
          <div class="info-card">
            <h5 class="card-title">
              <i class="bi bi-telephone-forward"></i>
              Emergency Contact
            </h5>
            <div class="info-grid">
              <div class="info-item">
                <div class="info-label">Name</div>
                <div class="info-value">{{ $patient->emergency_contact_name }}</div>
              </div>
              <div class="info-item">
                <div class="info-label">Relationship</div>
                <div class="info-value">{{ $patient->emergency_contact_relationship }}</div>
              </div>
              <div class="info-item">
                <div class="info-label">Phone</div>
                <div class="info-value">{{ $patient->emergency_contact_phone }}</div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <!-- Medical Information -->
          <div class="info-card">
            <h5 class="card-title">
              <i class="bi bi-clipboard-pulse"></i>
              Medical Information
            </h5>
            
            @if($patient->allergies)
            <div class="medical-note" style="border-color: #dc2626;">
              <div class="note-label"><i class="bi bi-exclamation-triangle"></i> Allergies</div>
              <div class="note-content">{{ $patient->allergies }}</div>
            </div>
            @else
            <div class="medical-note" style="border-color: #9ca3af;">
              <div class="note-label">Allergies</div>
              <div class="note-content" style="color: #6b7280;">No known allergies</div>
            </div>
            @endif

            @if($patient->chronic_conditions)
            <div class="medical-note" style="border-color: #f59e0b;">
              <div class="note-label"><i class="bi bi-heart-pulse"></i> Chronic Conditions</div>
              <div class="note-content">{{ $patient->chronic_conditions }}</div>
            </div>
            @else
            <div class="medical-note" style="border-color: #9ca3af;">
              <div class="note-label">Chronic Conditions</div>
              <div class="note-content" style="color: #6b7280;">No chronic conditions reported</div>
            </div>
            @endif

            @if($patient->current_medications)
            <div class="medical-note" style="border-color: #3b82f6;">
              <div class="note-label"><i class="bi bi-capsule"></i> Current Medications</div>
              <div class="note-content">{{ $patient->current_medications }}</div>
            </div>
            @else
            <div class="medical-note" style="border-color: #9ca3af;">
              <div class="note-label">Current Medications</div>
              <div class="note-content" style="color: #6b7280;">Not currently on medication</div>
            </div>
            @endif

            @if($patient->medical_history)
            <div class="medical-note">
              <div class="note-label"><i class="bi bi-clipboard-data"></i> Medical History</div>
              <div class="note-content">{{ $patient->medical_history }}</div>
            </div>
            @endif
          </div>

          <!-- Insurance Information -->
          <div class="info-card">
            <h5 class="card-title">
              <i class="bi bi-shield-check"></i>
              Insurance Information
            </h5>
            @if($patient->insurance_provider || $patient->insurance_number)
            <div class="info-grid">
              <div class="info-item">
                <div class="info-label">Provider</div>
                <div class="info-value">{{ $patient->insurance_provider ?? 'Not specified' }}</div>
              </div>
              <div class="info-item">
                <div class="info-label">Policy Number</div>
                <div class="info-value">{{ $patient->insurance_number ?? 'Not specified' }}</div>
              </div>
            </div>
            @else
            <div class="empty-state" style="padding: 24px;">
              <div class="empty-icon"><i class="bi bi-shield-slash"></i></div>
              <div class="empty-text">No insurance information on file</div>
            </div>
            @endif
          </div>

          <!-- Latest Admission -->
          @if($admissions->where('status', 'admitted')->first())
          @php $currentAdmission = $admissions->where('status', 'admitted')->first(); @endphp
          <div class="info-card" style="border-color: #dc2626; border-width: 2px;">
            <h5 class="card-title" style="color: #dc2626;">
              <i class="bi bi-hospital-fill"></i>
              Currently Admitted
            </h5>
            <div class="info-grid">
              <div class="info-item">
                <div class="info-label">Admission Number</div>
                <div class="info-value">
                  <a href="{{ route('admissions.show', $currentAdmission) }}" style="color: #111827; text-decoration: underline;">
                    {{ $currentAdmission->admission_number }}
                  </a>
                </div>
              </div>
              <div class="info-item">
                <div class="info-label">Ward</div>
                <div class="info-value">{{ $currentAdmission->ward->ward_name }}</div>
              </div>
              <div class="info-item">
                <div class="info-label">Bed</div>
                <div class="info-value">{{ $currentAdmission->bed ? $currentAdmission->bed->bed_number : 'Not Assigned' }}</div>
              </div>
              <div class="info-item">
                <div class="info-label">Days Admitted</div>
                <div class="info-value">
                  {{ (int) \Carbon\Carbon::parse($currentAdmission->admission_date)->diffInDays(now()) }} days
                </div>
              </div>
            </div>
          </div>
          @endif
        </div>
      </div>
    </div>

    <!-- Admissions Tab -->
    <div class="tab-pane fade" id="admissions">
      <div class="info-card">
        <h5 class="card-title">
          <i class="bi bi-hospital"></i>
          Admission History
        </h5>
        
        @if($admissions->count() > 0)
        <div class="table-responsive">
          <table class="history-table">
            <thead>
              <tr>
                <th>Admission #</th>
                <th>Admission Date</th>
                <th>Discharge Date</th>
                <th>Days</th>
                <th>Ward</th>
                <th>Bed</th>
                <th>Doctor</th>
                <th>Diagnosis</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($admissions as $admission)
              <tr>
                <td><strong>{{ $admission->admission_number }}</strong></td>
                <td>{{ \Carbon\Carbon::parse($admission->admission_date)->format('M d, Y') }}</td>
                <td>
                  @if($admission->discharge_date)
                    {{ \Carbon\Carbon::parse($admission->discharge_date)->format('M d, Y') }}
                  @else
                    <span style="color: #6b7280;">-</span>
                  @endif
                </td>
                <td>
                  @php
                    $days = (int) \Carbon\Carbon::parse($admission->admission_date)->diffInDays($admission->discharge_date ?? now());
                  @endphp
                  {{ $days }} {{ $days === 1 ? 'day' : 'days' }}
                </td>
                <td>{{ $admission->ward->ward_name }}</td>
                <td>{{ $admission->bed ? $admission->bed->bed_number : 'N/A' }}</td>
                <td>Dr. {{ $admission->doctor->name }}</td>
                <td>{{ Str::limit($admission->provisional_diagnosis, 30) }}</td>
                <td>
                  <span class="badge-status badge-{{ $admission->status === 'admitted' ? 'admitted' : 'discharged' }}">
                    {{ ucfirst($admission->status) }}
                  </span>
                </td>
                <td>
                  <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#admissionModal{{ $admission->id }}">
                    <i class="bi bi-eye"></i> View
                  </button>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @else
        <div class="empty-state">
          <div class="empty-icon"><i class="bi bi-hospital"></i></div>
          <div class="empty-text">No admission history found</div>
        </div>
        @endif
      </div>
    </div>

    <!-- Consultations Tab -->
    <div class="tab-pane fade" id="consultations">
      <div class="info-card">
        <h5 class="card-title">
          <i class="bi bi-clipboard-pulse"></i>
          Consultation & Prescription History
        </h5>
        
        @if($consultations->count() > 0)
        <div class="table-responsive">
          <table class="history-table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Doctor</th>
                <th>Chief Complaint</th>
                <th>Diagnosis</th>
                <th>Treatment Plan</th>
                <th>Prescriptions</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($consultations as $consultation)
              <tr>
                <td>{{ $consultation->created_at->format('M d, Y h:i A') }}</td>
                <td>Dr. {{ $consultation->doctor->name }}</td>
                <td>{{ Str::limit($consultation->chief_complaint, 40) }}</td>
                <td>{{ Str::limit($consultation->diagnosis ?? $consultation->provisional_diagnosis, 40) }}</td>
                <td>{{ Str::limit($consultation->treatment_plan, 40) }}</td>
                <td>
                  @if($consultation->prescriptions && $consultation->prescriptions->count() > 0)
                    <span class="badge-status" style="background: #dbeafe; color: #1e40af;">
                      {{ $consultation->prescriptions->count() }} Rx
                    </span>
                  @else
                    <span style="color: #9ca3af; font-size: 12px;">None</span>
                  @endif
                </td>
                <td>
                  <span class="badge-status badge-completed">Completed</span>
                </td>
                <td>
                  <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#consultationModal{{ $consultation->id }}">
                    <i class="bi bi-eye"></i> View
                  </button>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <!-- Standalone Prescriptions (not linked to consultations) -->
        @if($standalonePrescriptions->count() > 0)
        <div style="margin-top: 32px; padding-top: 24px; border-top: 2px solid #e5e7eb;">
          <h6 style="font-weight: 600; margin-bottom: 16px; color: #111827;">
            <i class="bi bi-capsule me-2"></i>Other Prescriptions
          </h6>
          @foreach($standalonePrescriptions as $prescription)
          <div class="medical-note" style="margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 12px;">
              <div>
                <div class="note-label">Prescription Date</div>
                <div style="color: #111827; font-weight: 600; font-size: 14px;">
                  {{ $prescription->created_at->format('M d, Y') }}
                </div>
              </div>
              <div>
                <div class="note-label">Prescribed By</div>
                <div style="color: #111827; font-weight: 600; font-size: 14px;">
                  Dr. {{ $prescription->doctor->name }}
                </div>
              </div>
              <div>
                <span class="badge-status badge-completed">{{ ucfirst($prescription->status ?? 'completed') }}</span>
              </div>
            </div>

            @if($prescription->items && $prescription->items->count() > 0)
            <div style="background: white; padding: 12px; border-radius: 6px; margin-top: 12px;">
              <div class="note-label" style="margin-bottom: 8px;">Medications</div>
              <ul style="margin: 0; padding-left: 20px;">
                @foreach($prescription->items as $item)
                <li style="margin-bottom: 6px; color: #111827; font-size: 13px;">
                  <strong>{{ $item->medicine ? $item->medicine->name : $item->medicine_name ?? 'N/A' }}</strong> - 
                  {{ $item->dosage }} {{ $item->frequency }} 
                  for {{ $item->duration }} days
                  @if($item->instructions)
                    <br><em style="color: #6b7280; font-size: 12px;">{{ $item->instructions }}</em>
                  @endif
                </li>
                @endforeach
              </ul>
            </div>
            @endif
          </div>
          @endforeach
        </div>
        @endif
        @else
        <div class="empty-state">
          <div class="empty-icon"><i class="bi bi-clipboard-pulse"></i></div>
          <div class="empty-text">No consultation history found</div>
        </div>
        @endif
      </div>
    </div>

    <!-- Lab Orders Tab -->
    <div class="tab-pane fade" id="laborders">
      <div class="info-card">
        <h5 class="card-title">
          <i class="bi bi-prescription2"></i>
          Lab Order History
        </h5>
        
        @if($labOrders->count() > 0)
        <div class="table-responsive">
          <table class="history-table">
            <thead>
              <tr>
                <th>Order #</th>
                <th>Order Date</th>
                <th>Test Name</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Result Date</th>
              </tr>
            </thead>
            <tbody>
              @foreach($labOrders as $labOrder)
              <tr>
                <td><strong>{{ $labOrder->order_number ?? 'N/A' }}</strong></td>
                <td>{{ $labOrder->created_at->format('M d, Y') }}</td>
                <td>{{ $labOrder->test_name ?? 'N/A' }}</td>
                <td>
                  @if($labOrder->priority === 'urgent')
                    <span class="badge-status" style="background: #fee2e2; color: #991b1b;">Urgent</span>
                  @else
                    <span class="badge-status" style="background: #dbeafe; color: #1e40af;">Normal</span>
                  @endif
                </td>
                <td>
                  @if($labOrder->status === 'completed')
                    <span class="badge-status badge-completed">Completed</span>
                  @elseif($labOrder->status === 'pending')
                    <span class="badge-status badge-pending">Pending</span>
                  @else
                    <span class="badge-status" style="background: #fef3c7; color: #92400e;">{{ ucfirst($labOrder->status) }}</span>
                  @endif
                </td>
                <td>
                  @if($labOrder->result_date)
                    {{ \Carbon\Carbon::parse($labOrder->result_date)->format('M d, Y') }}
                  @else
                    <span style="color: #6b7280;">-</span>
                  @endif
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @else
        <div class="empty-state">
          <div class="empty-icon"><i class="bi bi-prescription2"></i></div>
          <div class="empty-text">No lab order history found</div>
        </div>
        @endif
      </div>
    </div>

    <!-- Billing Tab -->
    <div class="tab-pane fade" id="billing">
      <div class="info-card">
        <h5 class="card-title">
          <i class="bi bi-credit-card"></i>
          Billing History
        </h5>
        
        @if($patient->bills->count() > 0)
        <div class="table-responsive">
          <table class="history-table">
            <thead>
              <tr>
                <th>Bill #</th>
                <th>Date</th>
                <th>Description</th>
                <th>Total Amount</th>
                <th>Paid Amount</th>
                <th>Balance</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($patient->bills as $bill)
              <tr>
                <td><strong>{{ $bill->bill_number }}</strong></td>
                <td>{{ $bill->created_at->format('M d, Y') }}</td>
                <td>{{ $bill->description ?? 'Medical Services' }}</td>
                <td>TSh {{ number_format($bill->total_amount, 2) }}</td>
                <td>TSh {{ number_format($bill->paid_amount ?? 0, 2) }}</td>
                <td>TSh {{ number_format($bill->total_amount - ($bill->paid_amount ?? 0), 2) }}</td>
                <td>
                  @if($bill->status === 'paid')
                    <span class="badge-status badge-paid">Paid</span>
                  @elseif($bill->status === 'partial')
                    <span class="badge-status badge-pending">Partial</span>
                  @else
                    <span class="badge-status badge-unpaid">Unpaid</span>
                  @endif
                </td>
                <td>
                  <a href="{{ route('bills.show', $bill) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-eye"></i>
                  </a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div style="background: #f9fafb; padding: 24px; border-radius: 8px; margin-top: 20px;">
          <div class="row text-center">
            <div class="col-md-4">
              <div class="stat-label">Total Billed</div>
              <div class="stat-value" style="font-size: 24px; margin-top: 8px;">
                TSh {{ number_format($patient->bills->sum('total_amount'), 2) }}
              </div>
            </div>
            <div class="col-md-4">
              <div class="stat-label">Total Paid</div>
              <div class="stat-value" style="font-size: 24px; margin-top: 8px; color: #065f46;">
                TSh {{ number_format($patient->bills->sum('paid_amount'), 2) }}
              </div>
            </div>
            <div class="col-md-4">
              <div class="stat-label">Outstanding Balance</div>
              <div class="stat-value" style="font-size: 24px; margin-top: 8px; color: #dc2626;">
                TSh {{ number_format($patient->bills->sum('total_amount') - $patient->bills->sum('paid_amount'), 2) }}
              </div>
            </div>
          </div>
        </div>
        @else
        <div class="empty-state">
          <div class="empty-icon"><i class="bi bi-credit-card"></i></div>
          <div class="empty-text">No billing history found</div>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Admission Modals -->
@foreach($admissions as $admission)
<div class="modal fade" id="admissionModal{{ $admission->id }}" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="bi bi-hospital me-2"></i>
          Admission Details - {{ $admission->admission_number }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <!-- Status Badge -->
        <div class="mb-3">
          <span class="badge-status badge-{{ $admission->status === 'admitted' ? 'admitted' : 'discharged' }}" style="font-size: 13px; padding: 8px 14px;">
            <i class="bi bi-{{ $admission->status === 'admitted' ? 'hospital-fill' : 'box-arrow-right' }}"></i>
            {{ ucfirst($admission->status) }}
          </span>
        </div>

        <!-- Admission Information -->
        <div class="info-card" style="margin-bottom: 20px;">
          <h6 style="font-weight: 600; margin-bottom: 16px; color: #111827;">
            <i class="bi bi-calendar-check me-2"></i>Admission Information
          </h6>
          <div class="info-grid">
            <div class="info-item">
              <div class="info-label">Admission Date</div>
              <div class="info-value">{{ \Carbon\Carbon::parse($admission->admission_date)->format('M d, Y h:i A') }}</div>
            </div>
            @if($admission->discharge_date)
            <div class="info-item">
              <div class="info-label">Discharge Date</div>
              <div class="info-value">{{ \Carbon\Carbon::parse($admission->discharge_date)->format('M d, Y h:i A') }}</div>
            </div>
            @endif
            <div class="info-item">
              <div class="info-label">Days</div>
              <div class="info-value">
                @php
                  $days = (int) \Carbon\Carbon::parse($admission->admission_date)->diffInDays($admission->discharge_date ?? now());
                @endphp
                {{ $days }} {{ $days === 1 ? 'day' : 'days' }}
              </div>
            </div>
            <div class="info-item">
              <div class="info-label">Ward</div>
              <div class="info-value">{{ $admission->ward->ward_name }} ({{ ucfirst($admission->ward->ward_type) }})</div>
            </div>
            <div class="info-item">
              <div class="info-label">Bed</div>
              <div class="info-value">{{ $admission->bed ? $admission->bed->bed_number : 'Not Assigned' }}</div>
            </div>
            <div class="info-item">
              <div class="info-label">Attending Doctor</div>
              <div class="info-value">Dr. {{ $admission->doctor->name }}</div>
            </div>
            <div class="info-item">
              <div class="info-label">Admission Type</div>
              <div class="info-value">{{ ucfirst($admission->admission_type) }}</div>
            </div>
            <div class="info-item">
              <div class="info-label">Category</div>
              <div class="info-value">{{ ucfirst($admission->admission_category) }}</div>
            </div>
          </div>
        </div>

        <!-- Clinical Information -->
        @if($admission->provisional_diagnosis || $admission->reason_for_admission || $admission->complaints)
        <div class="info-card" style="margin-bottom: 20px;">
          <h6 style="font-weight: 600; margin-bottom: 16px; color: #111827;">
            <i class="bi bi-clipboard-pulse me-2"></i>Clinical Information
          </h6>
          
          @if($admission->provisional_diagnosis)
          <div class="medical-note" style="margin-bottom: 12px;">
            <div class="note-label">Provisional Diagnosis</div>
            <div class="note-content">{{ $admission->provisional_diagnosis }}</div>
          </div>
          @endif

          @if($admission->reason_for_admission)
          <div class="medical-note" style="margin-bottom: 12px; border-color: #6b7280;">
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
        </div>
        @endif

        <!-- Vitals -->
        @if($admission->blood_pressure || $admission->temperature || $admission->pulse_rate)
        <div class="info-card">
          <h6 style="font-weight: 600; margin-bottom: 16px; color: #111827;">
            <i class="bi bi-heart-pulse me-2"></i>Vitals at Admission
          </h6>
          <div class="info-grid">
            @if($admission->blood_pressure)
            <div class="info-item">
              <div class="info-label">Blood Pressure</div>
              <div class="info-value">{{ $admission->blood_pressure }} mmHg</div>
            </div>
            @endif
            @if($admission->temperature)
            <div class="info-item">
              <div class="info-label">Temperature</div>
              <div class="info-value">{{ $admission->temperature }} °C</div>
            </div>
            @endif
            @if($admission->pulse_rate)
            <div class="info-item">
              <div class="info-label">Pulse Rate</div>
              <div class="info-value">{{ $admission->pulse_rate }} bpm</div>
            </div>
            @endif
            @if($admission->respiratory_rate)
            <div class="info-item">
              <div class="info-label">Respiratory Rate</div>
              <div class="info-value">{{ $admission->respiratory_rate }} /min</div>
            </div>
            @endif
            @if($admission->oxygen_saturation)
            <div class="info-item">
              <div class="info-label">SpO2</div>
              <div class="info-value">{{ $admission->oxygen_saturation }}%</div>
            </div>
            @endif
          </div>
        </div>
        @endif
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <a href="{{ route('admissions.show', $admission) }}" class="btn btn-primary">
          <i class="bi bi-box-arrow-up-right me-1"></i>Full Details
        </a>
      </div>
    </div>
  </div>
</div>
@endforeach

<!-- Consultation Modals -->
@foreach($consultations as $consultation)
<div class="modal fade" id="consultationModal{{ $consultation->id }}" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="bi bi-clipboard-pulse me-2"></i>
          Consultation Details
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <!-- Consultation Info -->
        <div class="info-card" style="margin-bottom: 20px;">
          <h6 style="font-weight: 600; margin-bottom: 16px; color: #111827;">
            <i class="bi bi-info-circle me-2"></i>Consultation Information
          </h6>
          <div class="info-grid">
            <div class="info-item">
              <div class="info-label">Date & Time</div>
              <div class="info-value">{{ $consultation->created_at->format('M d, Y h:i A') }}</div>
            </div>
            <div class="info-item">
              <div class="info-label">Doctor</div>
              <div class="info-value">Dr. {{ $consultation->doctor->name }}</div>
            </div>
            @if($consultation->appointment)
            <div class="info-item">
              <div class="info-label">Appointment</div>
              <div class="info-value">{{ $consultation->appointment->appointment_number }}</div>
            </div>
            @endif
          </div>
        </div>

        <!-- Clinical Details -->
        <div class="info-card" style="margin-bottom: 20px;">
          <h6 style="font-weight: 600; margin-bottom: 16px; color: #111827;">
            <i class="bi bi-clipboard-pulse me-2"></i>Clinical Details
          </h6>
          
          @if($consultation->chief_complaint)
          <div class="medical-note" style="margin-bottom: 12px;">
            <div class="note-label">Chief Complaint</div>
            <div class="note-content">{{ $consultation->chief_complaint }}</div>
          </div>
          @endif

          @if($consultation->history_of_present_illness)
          <div class="medical-note" style="margin-bottom: 12px; border-color: #6b7280;">
            <div class="note-label">History of Present Illness</div>
            <div class="note-content">{{ $consultation->history_of_present_illness }}</div>
          </div>
          @endif

          @if($consultation->physical_examination)
          <div class="medical-note" style="margin-bottom: 12px; border-color: #6b7280;">
            <div class="note-label">Physical Examination</div>
            <div class="note-content">{{ $consultation->physical_examination }}</div>
          </div>
          @endif

          @if($consultation->diagnosis)
          <div class="medical-note" style="margin-bottom: 12px; border-color: #111827;">
            <div class="note-label">Diagnosis</div>
            <div class="note-content">{{ $consultation->diagnosis }}</div>
          </div>
          @endif

          @if($consultation->treatment_plan)
          <div class="medical-note" style="border-color: #3b82f6;">
            <div class="note-label">Treatment Plan</div>
            <div class="note-content">{{ $consultation->treatment_plan }}</div>
          </div>
          @endif
        </div>

        <!-- Vitals -->
        @if($consultation->blood_pressure || $consultation->temperature || $consultation->pulse_rate)
        <div class="info-card">
          <h6 style="font-weight: 600; margin-bottom: 16px; color: #111827;">
            <i class="bi bi-heart-pulse me-2"></i>Vitals
          </h6>
          <div class="info-grid">
            @if($consultation->blood_pressure)
            <div class="info-item">
              <div class="info-label">Blood Pressure</div>
              <div class="info-value">{{ $consultation->blood_pressure }} mmHg</div>
            </div>
            @endif
            @if($consultation->temperature)
            <div class="info-item">
              <div class="info-label">Temperature</div>
              <div class="info-value">{{ $consultation->temperature }} °C</div>
            </div>
            @endif
            @if($consultation->pulse_rate)
            <div class="info-item">
              <div class="info-label">Pulse Rate</div>
              <div class="info-value">{{ $consultation->pulse_rate }} bpm</div>
            </div>
            @endif
            @if($consultation->respiratory_rate)
            <div class="info-item">
              <div class="info-label">Respiratory Rate</div>
              <div class="info-value">{{ $consultation->respiratory_rate }} /min</div>
            </div>
            @endif
            @if($consultation->weight)
            <div class="info-item">
              <div class="info-label">Weight</div>
              <div class="info-value">{{ $consultation->weight }} kg</div>
            </div>
            @endif
            @if($consultation->height)
            <div class="info-item">
              <div class="info-label">Height</div>
              <div class="info-value">{{ $consultation->height }} cm</div>
            </div>
            @endif
          </div>
        </div>
        @endif

        <!-- Prescriptions -->
        @if($consultation->prescriptions && $consultation->prescriptions->count() > 0)
        <div class="info-card">
          <h6 style="font-weight: 600; margin-bottom: 16px; color: #111827;">
            <i class="bi bi-capsule me-2"></i>Prescriptions ({{ $consultation->prescriptions->count() }})
          </h6>
          
          @foreach($consultation->prescriptions as $prescription)
          <div class="medical-note" style="margin-bottom: 16px; border-color: #3b82f6;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 12px;">
              <div>
                <div class="note-label">Prescription Date</div>
                <div style="color: #111827; font-weight: 600; font-size: 13px;">
                  {{ $prescription->created_at->format('M d, Y') }}
                </div>
              </div>
              <div>
                <span class="badge-status badge-completed">{{ ucfirst($prescription->status ?? 'completed') }}</span>
              </div>
            </div>

            @if($prescription->items && $prescription->items->count() > 0)
            <div style="background: white; padding: 12px; border-radius: 6px;">
              <div class="note-label" style="margin-bottom: 8px;">Medications</div>
              <ul style="margin: 0; padding-left: 20px;">
                @foreach($prescription->items as $item)
                <li style="margin-bottom: 6px; color: #111827; font-size: 13px;">
                  <strong>{{ $item->medicine ? $item->medicine->name : $item->medicine_name ?? 'N/A' }}</strong> - 
                  {{ $item->dosage }} {{ $item->frequency }} 
                  for {{ $item->duration }} days
                  @if($item->instructions)
                    <br><em style="color: #6b7280; font-size: 12px;">{{ $item->instructions }}</em>
                  @endif
                </li>
                @endforeach
              </ul>
            </div>
            @endif

            @if($prescription->special_instructions)
            <div style="margin-top: 12px; padding: 12px; background: #fffbeb; border-radius: 6px;">
              <div class="note-label">Special Instructions</div>
              <div style="color: #111827; font-size: 13px;">{{ $prescription->special_instructions }}</div>
            </div>
            @endif
          </div>
          @endforeach
        </div>
        @endif
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endforeach

@endsection
