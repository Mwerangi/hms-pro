@extends('layouts.app')

@section('title', 'Patient Profile - ' . $patient->full_name)

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('patients.index') }}">Patients</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ $patient->full_name }}</li>
@endsection

@push('styles')
<style>
  /* Modern Minimalistic Design */
  .profile-header {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 32px;
    margin-bottom: 24px;
  }

  .profile-main {
    display: flex;
    gap: 24px;
    align-items: start;
  }

  .profile-photo {
    width: 100px;
    height: 100px;
    border-radius: 12px;
    border: 2px solid #e5e7eb;
    overflow: hidden;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .profile-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .profile-photo i {
    font-size: 48px;
    color: #9ca3af;
  }

  .profile-info h1 {
    font-size: 24px;
    font-weight: 600;
    color: #111827;
    margin: 0 0 8px 0;
  }

  .patient-id-badge {
    display: inline-block;
    background: #f3f4f6;
    color: #667eea;
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 12px;
  }

  .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
  }

  .status-active {
    background: #d1fae5;
    color: #065f46;
  }

  .status-inactive {
    background: #fee2e2;
    color: #991b1b;
  }

  .status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
  }

  .action-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 16px;
  }

  .btn-action {
    padding: 8px 16px;
    font-size: 13px;
    font-weight: 500;
    border-radius: 8px;
  }

  /* Alert Banner */
  .alert-banner {
    background: #fef3c7;
    border: 1px solid #fbbf24;
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 24px;
    display: flex;
    align-items: start;
    gap: 12px;
  }

  .alert-icon {
    font-size: 20px;
    color: #f59e0b;
    margin-top: 2px;
  }

  .alert-content {
    flex: 1;
    font-size: 14px;
    color: #92400e;
  }

  /* Quick Stats */
  .quick-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
  }

  .quick-stat {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
  }

  .quick-stat-value {
    font-size: 24px;
    font-weight: 700;
    color: #667eea;
    margin-bottom: 4px;
  }

  .quick-stat-label {
    font-size: 12px;
    color: #6b7280;
    font-weight: 500;
  }

  /* Info Cards */
  .info-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 20px;
  }

  .info-card-title {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f3f4f6;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .card-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f3f4f6;
    color: #667eea;
    font-size: 16px;
  }

  .info-row {
    display: grid;
    grid-template-columns: 140px 1fr;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid #f9fafb;
  }

  .info-row:last-child {
    border-bottom: none;
  }

  .info-label {
    font-weight: 600;
    color: #6b7280;
    font-size: 13px;
  }

  .info-value {
    color: #111827;
    font-size: 13px;
  }

  .empty-state {
    text-align: center;
    color: #9ca3af;
    font-size: 14px;
    padding: 32px;
  }

  .medical-alert {
    background: #fee2e2;
    border: 1px solid #ef4444;
    border-radius: 8px;
    padding: 10px 14px;
    margin-bottom: 8px;
    color: #991b1b;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 8px;
  }
</style>
@endpush

@section('content')
<!-- Profile Header -->
<div class="profile-header">
  <div class="profile-main">
    <div class="profile-photo">
      @if($patient->photo)
        <img src="{{ Storage::url($patient->photo) }}" alt="{{ $patient->full_name }}">
      @else
        <i class="bi bi-person-circle"></i>
      @endif
    </div>
    <div class="flex-grow-1">
      <div class="profile-info">
        <h1>{{ $patient->full_name }}</h1>
        <div class="patient-id-badge">{{ $patient->patient_id }}</div>
        <div>
          <span class="status-badge {{ $patient->is_active ? 'status-active' : 'status-inactive' }}">
            <span class="status-dot"></span>
            {{ $patient->is_active ? 'Active' : 'Inactive' }}
          </span>
        </div>
      </div>
    </div>
    <div class="action-buttons" style="margin-top: 0;">
      <a href="{{ route('patients.index') }}" class="btn btn-secondary btn-action">
        <i class="bi bi-arrow-left me-1"></i>Back
      </a>
      @if($patient->latestAppointment)
        <a href="{{ route('appointments.show', $patient->latestAppointment) }}" class="btn btn-primary btn-action">
          <i class="bi bi-calendar-check me-1"></i>View Appointment
        </a>
      @else
        <a href="{{ route('appointments.create', ['patient_id' => $patient->id]) }}" class="btn btn-success btn-action">
          <i class="bi bi-calendar-plus me-1"></i>Create Appointment
        </a>
      @endif
      <a href="{{ route('patients.edit', $patient) }}" class="btn btn-outline-secondary btn-action">
        <i class="bi bi-pencil-square me-1"></i>Edit
      </a>
      <button class="btn btn-outline-secondary btn-action" onclick="window.print()">
        <i class="bi bi-printer me-1"></i>Print
      </button>
    </div>
  </div>
</div>

<!-- Medical Alerts -->
@if($patient->allergies || $patient->chronic_conditions)
<div class="alert-banner">
  <i class="bi bi-exclamation-triangle-fill alert-icon"></i>
  <div class="alert-content">
    <strong>Medical Alerts:</strong> This patient has documented allergies or chronic conditions. Please review medical information before treatment.
  </div>
</div>
@endif

<!-- Quick Stats -->
<div class="quick-stats">
  <div class="quick-stat">
    <div class="quick-stat-value">{{ $patient->age }}</div>
    <div class="quick-stat-label">Years Old</div>
  </div>
  <div class="quick-stat">
    <div class="quick-stat-value">{{ $patient->blood_group ?? 'N/A' }}</div>
    <div class="quick-stat-label">Blood Group</div>
  </div>
  <div class="quick-stat">
    <div class="quick-stat-value">{{ ucfirst($patient->gender) }}</div>
    <div class="quick-stat-label">Gender</div>
  </div>
  <div class="quick-stat">
    <div class="quick-stat-value">{{ $patient->created_at->format('M Y') }}</div>
    <div class="quick-stat-label">Registered</div>
  </div>
</div>

<!-- Main Content Grid -->
<div class="row">
  <!-- Left Column -->
  <div class="col-md-6">
    <!-- Personal Information -->
    <div class="info-card">
      <h3 class="info-card-title">
        <div class="card-icon">
          <i class="bi bi-person"></i>
        </div>
        Personal Information
      </h3>
      <div class="info-row">
        <div class="info-label">Full Name</div>
        <div class="info-value">{{ $patient->full_name }}</div>
      </div>
      <div class="info-row">
        <div class="info-label">Date of Birth</div>
        <div class="info-value">{{ $patient->date_of_birth?->format('F d, Y') }}</div>
      </div>
      <div class="info-row">
        <div class="info-label">Age</div>
        <div class="info-value">{{ $patient->age }} years</div>
      </div>
      <div class="info-row">
        <div class="info-label">Gender</div>
        <div class="info-value">{{ ucfirst($patient->gender) }}</div>
      </div>
      <div class="info-row">
        <div class="info-label">Blood Group</div>
        <div class="info-value">{{ $patient->blood_group ?? 'Not specified' }}</div>
      </div>
      <div class="info-row">
        <div class="info-label">Marital Status</div>
        <div class="info-value">{{ $patient->marital_status ? ucfirst($patient->marital_status) : 'Not specified' }}</div>
      </div>
      <div class="info-row">
        <div class="info-label">Occupation</div>
        <div class="info-value">{{ $patient->occupation ?? 'Not specified' }}</div>
      </div>
    </div>

    <!-- Contact Information -->
    <div class="info-card">
      <h3 class="info-card-title">
        <div class="card-icon">
          <i class="bi bi-telephone"></i>
        </div>
        Contact Information
      </h3>
      <div class="info-row">
        <div class="info-label">Phone</div>
        <div class="info-value">
          <a href="tel:{{ $patient->phone }}">{{ $patient->phone }}</a>
        </div>
      </div>
      <div class="info-row">
        <div class="info-label">Email</div>
        <div class="info-value">
          @if($patient->email)
            <a href="mailto:{{ $patient->email }}">{{ $patient->email }}</a>
          @else
            Not provided
          @endif
        </div>
      </div>
      <div class="info-row">
        <div class="info-label">Address</div>
        <div class="info-value">{{ $patient->address }}</div>
      </div>
      <div class="info-row">
        <div class="info-label">City</div>
        <div class="info-value">{{ $patient->city }}</div>
      </div>
      <div class="info-row">
        <div class="info-label">State/County</div>
        <div class="info-value">{{ $patient->state }}</div>
      </div>
      <div class="info-row">
        <div class="info-label">Postal Code</div>
        <div class="info-value">{{ $patient->postal_code ?? 'Not specified' }}</div>
      </div>
      <div class="info-row">
        <div class="info-label">Country</div>
        <div class="info-value">{{ $patient->country }}</div>
      </div>
    </div>

    <!-- Emergency Contact -->
    <div class="info-card">
      <h3 class="info-card-title">
        <div class="card-icon">
          <i class="bi bi-exclamation-triangle"></i>
        </div>
        Emergency Contact
      </h3>
      <div class="info-row">
        <div class="info-label">Name</div>
        <div class="info-value">{{ $patient->emergency_contact_name }}</div>
      </div>
      <div class="info-row">
        <div class="info-label">Phone</div>
        <div class="info-value">
          <a href="tel:{{ $patient->emergency_contact_phone }}">{{ $patient->emergency_contact_phone }}</a>
        </div>
      </div>
      <div class="info-row">
        <div class="info-label">Relationship</div>
        <div class="info-value">{{ $patient->emergency_contact_relationship }}</div>
      </div>
    </div>
  </div>

  <!-- Right Column -->
  <div class="col-md-6">
    <!-- Medical Information -->
    <div class="info-card">
      <h3 class="info-card-title">
        <div class="card-icon">
          <i class="bi bi-heart-pulse"></i>
        </div>
        Medical Information
      </h3>
      
      <div class="mb-3">
        <div class="info-label mb-2">Allergies</div>
        @if($patient->allergies)
          <div class="medical-alert">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ $patient->allergies }}
          </div>
        @else
          <div class="info-value">No known allergies</div>
        @endif
      </div>

      <div class="mb-3">
        <div class="info-label mb-2">Chronic Conditions</div>
        @if($patient->chronic_conditions)
          <div class="medical-alert">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ $patient->chronic_conditions }}
          </div>
        @else
          <div class="info-value">No chronic conditions reported</div>
        @endif
      </div>

      <div class="mb-3">
        <div class="info-label mb-2">Current Medications</div>
        @if($patient->current_medications)
          <div class="info-value">{{ $patient->current_medications }}</div>
        @else
          <div class="info-value">Not currently on medication</div>
        @endif
      </div>

      <div>
        <div class="info-label mb-2">Medical History</div>
        @if($patient->medical_history)
          <div class="info-value">{{ $patient->medical_history }}</div>
        @else
          <div class="info-value">No medical history recorded</div>
        @endif
      </div>
    </div>

    <!-- Insurance Information -->
    <div class="info-card">
      <h3 class="info-card-title">
        <div class="card-icon">
          <i class="bi bi-shield-check"></i>
        </div>
        Insurance Information
      </h3>
      @if($patient->insurance_provider || $patient->insurance_number)
        <div class="info-row">
          <div class="info-label">Provider</div>
          <div class="info-value">{{ $patient->insurance_provider ?? 'Not specified' }}</div>
        </div>
        <div class="info-row">
          <div class="info-label">Policy Number</div>
          <div class="info-value">{{ $patient->insurance_number ?? 'Not specified' }}</div>
        </div>
      @else
        <div class="empty-state">
          <i class="bi bi-shield-slash" style="font-size: 48px; margin-bottom: 10px;"></i>
          <p>No insurance information on file</p>
        </div>
      @endif
    </div>

    <!-- System Information -->
    <div class="info-card">
      <h3 class="info-card-title">
        <div class="card-icon">
          <i class="bi bi-info-circle"></i>
        </div>
        System Information
      </h3>
      <div class="info-row">
        <div class="info-label">Patient ID</div>
        <div class="info-value">{{ $patient->patient_id }}</div>
      </div>
      <div class="info-row">
        <div class="info-label">Registered On</div>
        <div class="info-value">{{ $patient->created_at->format('F d, Y \a\t h:i A') }}</div>
      </div>
      <div class="info-row">
        <div class="info-label">Last Updated</div>
        <div class="info-value">{{ $patient->updated_at->format('F d, Y \a\t h:i A') }}</div>
      </div>
      <div class="info-row">
        <div class="info-label">Status</div>
        <div class="info-value">
          <span class="status-badge {{ $patient->is_active ? 'status-active' : 'status-inactive' }}">
            {{ $patient->is_active ? 'Active' : 'Inactive' }}
          </span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Toggle Status Modal -->
<div class="modal fade" id="toggleStatusModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ $patient->is_active ? 'Deactivate' : 'Activate' }} Patient</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to {{ $patient->is_active ? 'deactivate' : 'activate' }} <strong>{{ $patient->full_name }}</strong>?</p>
        @if($patient->is_active)
        <div class="alert alert-warning">
          <i class="bi bi-exclamation-triangle me-2"></i>
          <strong>Note:</strong> Deactivating this patient will prevent new appointments and consultations.
        </div>
        @endif
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="document.getElementById('toggleStatusForm').submit()">Confirm</button>
      </div>
    </div>
  </div>
</div>

<!-- Hidden Form for Status Toggle -->
<form id="toggleStatusForm" action="{{ route('patients.toggle-status', $patient) }}" method="POST" style="display: none;">
  @csrf
</form>
@endsection
