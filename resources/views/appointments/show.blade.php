@extends('layouts.app')

@section('title', 'Appointment Details - ' . $appointment->appointment_number)

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('appointments.index') }}">Appointments</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ $appointment->appointment_number }}</li>
@endsection

@push('styles')
<style>
  /* Modern Minimalistic Design */
  .appointment-header {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 32px;
    margin-bottom: 24px;
  }

  .header-main {
    display: flex;
    gap: 24px;
    align-items: start;
  }

  .token-display {
    background: #f3f4f6;
    padding: 24px;
    border-radius: 12px;
    text-align: center;
    border: 2px solid #e5e7eb;
    min-width: 140px;
  }

  .token-number {
    font-size: 42px;
    font-weight: 700;
    color: #667eea;
    margin-bottom: 4px;
  }

  .token-label {
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
    grid-template-columns: 150px 1fr;
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

  .status-badge {
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }

  .status-scheduled { background: #dbeafe; color: #1e40af; }
  .status-waiting { background: #fef3c7; color: #92400e; }
  .status-in-consultation { background: #ddd6fe; color: #5b21b6; }
  .status-completed { background: #d1fae5; color: #065f46; }
  .status-cancelled { background: #fee2e2; color: #991b1b; }
  .status-no-show { background: #e5e7eb; color: #374151; }

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

  .btn-action:hover {
    transform: translateY(-2px);
  }

  .timeline {
    position: relative;
    padding-left: 30px;
  }

  .timeline-item {
    position: relative;
    padding-bottom: 20px;
  }

  .timeline-item::before {
    content: '';
    position: absolute;
    left: -22px;
    top: 8px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: var(--hms-purple);
  }

  .timeline-item::after {
    content: '';
    position: absolute;
    left: -17px;
    top: 20px;
    width: 2px;
    height: calc(100% - 10px);
    background: #e2e8f0;
  }

  .timeline-item:last-child::after {
    display: none;
  }

  .timeline-time {
    font-size: 13px;
    color: #718096;
    font-weight: 600;
  }

  .timeline-label {
    font-size: 14px;
    color: #4a5568;
    margin-top: 2px;
  }

  [data-theme="dark"] .timeline-label {
    color: #cbd5e0;
  }
</style>
@endpush

@section('content')
<!-- Appointment Header -->
<div class="appointment-header">
  <div class="d-flex justify-content-between align-items-start">
    <div class="flex-grow-1">
      <h1 style="font-size: 28px; font-weight: 700; margin-bottom: 8px;">{{ $appointment->appointment_number }}</h1>
      <div style="font-size: 16px; opacity: 0.9;">
        {{ $appointment->appointment_date->format('l, F d, Y') }} at {{ $appointment->appointment_time->format('h:i A') }}
      </div>
    </div>
    <div class="token-display">
      <div class="token-number">{{ $appointment->token_number }}</div>
      <div class="token-label">Token Number</div>
    </div>
    <div class="action-buttons ms-3">
      <a href="{{ route('appointments.index') }}" class="btn btn-secondary btn-action">
        <i class="bi bi-arrow-left me-2"></i>Back
      </a>
      
      @if($appointment->status === 'scheduled')
        <form action="{{ route('appointments.check-in', $appointment) }}" method="POST" class="d-inline">
          @csrf
          <button type="submit" class="btn btn-success btn-action">
            <i class="bi bi-check-circle me-2"></i>Check In
          </button>
        </form>
      @endif

      <!-- Consultation Actions -->
      @if(in_array($appointment->status, ['waiting', 'in-consultation']))
        @if($appointment->consultation)
          <a href="{{ route('consultations.edit', $appointment->consultation) }}" class="btn btn-primary btn-action">
            <i class="bi bi-clipboard-pulse me-2"></i>Continue Consultation
          </a>
          @if($appointment->consultation->status === 'in-progress')
            <form action="{{ route('consultations.complete', $appointment->consultation) }}" method="POST" class="d-inline">
              @csrf
              <button type="submit" class="btn btn-success btn-action">
                <i class="bi bi-check-square me-2"></i>Complete Consultation
              </button>
            </form>
          @endif
        @else
          <a href="{{ route('consultations.start-from-appointment', $appointment) }}" class="btn btn-primary btn-action" style="background: linear-gradient(135deg, var(--hms-purple), var(--hms-primary)); border: none;">
            <i class="bi bi-clipboard-plus me-2"></i>Start Consultation
          </a>
        @endif
      @endif

      @if($appointment->status === 'completed' && $appointment->consultation)
        <a href="{{ route('consultations.show', $appointment->consultation) }}" class="btn btn-info btn-action">
          <i class="bi bi-file-medical me-2"></i>View Consultation
        </a>
      @endif

      @if($appointment->canBeRescheduled())
        <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-warning btn-action">
          <i class="bi bi-calendar-event me-2"></i>Reschedule
        </a>
      @endif

      @if($appointment->canBeCancelled())
        <button type="button" class="btn btn-danger btn-action" data-bs-toggle="modal" data-bs-target="#cancelModal">
          <i class="bi bi-x-circle me-2"></i>Cancel
        </button>
      @endif
    </div>
  </div>
</div>

<!-- Main Content -->
<div class="row">
  <!-- Left Column -->
  <div class="col-md-6">
    <!-- Patient Information -->
    <div class="info-card">
      <h3 class="info-card-title">
        <div class="card-icon">
          <i class="bi bi-person"></i>
        </div>
        Patient Information
      </h3>
      <div class="info-row">
        <div class="info-label">Patient Name</div>
        <div class="info-value">
          <a href="{{ route('patients.show', $appointment->patient) }}">{{ $appointment->patient->full_name }}</a>
        </div>
      </div>
      <div class="info-row">
        <div class="info-label">Patient ID</div>
        <div class="info-value">{{ $appointment->patient->patient_id }}</div>
      </div>
      <div class="info-row">
        <div class="info-label">Phone</div>
        <div class="info-value">{{ $appointment->patient->phone }}</div>
      </div>
      <div class="info-row">
        <div class="info-label">Age</div>
        <div class="info-value">{{ $appointment->patient->age }} years</div>
      </div>
      <div class="info-row">
        <div class="info-label">Gender</div>
        <div class="info-value">{{ ucfirst($appointment->patient->gender) }}</div>
      </div>
      <div class="info-row">
        <div class="info-label">Blood Group</div>
        <div class="info-value">{{ $appointment->patient->blood_group ?? 'Not specified' }}</div>
      </div>
    </div>

    <!-- Appointment Details -->
    <div class="info-card">
      <h3 class="info-card-title">
        <div class="card-icon">
          <i class="bi bi-calendar-check"></i>
        </div>
        Appointment Details
      </h3>
      <div class="info-row">
        <div class="info-label">Appointment #</div>
        <div class="info-value">{{ $appointment->appointment_number }}</div>
      </div>
      <div class="info-row">
        <div class="info-label">Token Number</div>
        <div class="info-value"><strong>{{ $appointment->token_number }}</strong></div>
      </div>
      <div class="info-row">
        <div class="info-label">Type</div>
        <div class="info-value">
          <span class="badge bg-secondary">{{ ucfirst($appointment->appointment_type) }}</span>
        </div>
      </div>
      <div class="info-row">
        <div class="info-label">Status</div>
        <div class="info-value">
          <span class="status-badge status-{{ $appointment->status }}">
            {{ ucfirst(str_replace('-', ' ', $appointment->status)) }}
          </span>
        </div>
      </div>
      <div class="info-row">
        <div class="info-label">Doctor</div>
        <div class="info-value">Dr. {{ $appointment->doctor->name }}</div>
      </div>
      <div class="info-row">
        <div class="info-label">Date</div>
        <div class="info-value">{{ $appointment->appointment_date->format('F d, Y') }}</div>
      </div>
      <div class="info-row">
        <div class="info-label">Time</div>
        <div class="info-value">{{ $appointment->appointment_time->format('h:i A') }}</div>
      </div>
    </div>
  </div>

  <!-- Right Column -->
  <div class="col-md-6">
    <!-- Consultation Summary (if exists) -->
    @if($appointment->consultation)
    <div class="info-card" style="border: 2px solid var(--hms-primary);">
      <h3 class="info-card-title">
        <div class="card-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
          <i class="bi bi-clipboard-pulse"></i>
        </div>
        Consultation Record
      </h3>
      <div class="info-row">
        <div class="info-label">Consultation #</div>
        <div class="info-value"><strong>{{ $appointment->consultation->consultation_number }}</strong></div>
      </div>
      <div class="info-row">
        <div class="info-label">Status</div>
        <div class="info-value">
          <span class="status-badge status-{{ $appointment->consultation->status }}">
            {{ ucfirst(str_replace('-', ' ', $appointment->consultation->status)) }}
          </span>
        </div>
      </div>
      @if($appointment->consultation->chief_complaint)
      <div class="info-row">
        <div class="info-label">Chief Complaint</div>
        <div class="info-value">{{ $appointment->consultation->chief_complaint }}</div>
      </div>
      @endif
      @if($appointment->consultation->final_diagnosis)
      <div class="info-row">
        <div class="info-label">Diagnosis</div>
        <div class="info-value">{{ $appointment->consultation->final_diagnosis }}</div>
      </div>
      @endif
      <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #e2e8f0;">
        <a href="{{ route('consultations.show', $appointment->consultation) }}" class="btn btn-primary w-100">
          <i class="bi bi-eye me-2"></i>View Full Consultation Details
        </a>
      </div>
    </div>
    @endif

    <!-- Visit Information -->
    <div class="info-card">
      <h3 class="info-card-title">
        <div class="card-icon">
          <i class="bi bi-file-text"></i>
        </div>
        Visit Information
      </h3>
      <div class="info-row">
        <div class="info-label">Reason for Visit</div>
        <div class="info-value">{{ $appointment->reason_for_visit ?? 'Not specified' }}</div>
      </div>
      <div class="info-row">
        <div class="info-label">Notes</div>
        <div class="info-value">{{ $appointment->notes ?? 'No additional notes' }}</div>
      </div>
      @if($appointment->status === 'cancelled')
      <div class="info-row">
        <div class="info-label">Cancellation Reason</div>
        <div class="info-value text-danger">{{ $appointment->cancellation_reason }}</div>
      </div>
      @endif
    </div>

    <!-- Timeline -->
    <div class="info-card">
      <h3 class="info-card-title">
        <div class="card-icon">
          <i class="bi bi-clock-history"></i>
        </div>
        Timeline
      </h3>
      <div class="timeline">
        <div class="timeline-item">
          <div class="timeline-time">{{ $appointment->created_at->format('M d, Y h:i A') }}</div>
          <div class="timeline-label">Appointment Booked</div>
        </div>
        
        @if($appointment->checked_in_at)
        <div class="timeline-item">
          <div class="timeline-time">{{ $appointment->checked_in_at->format('M d, Y h:i A') }}</div>
          <div class="timeline-label">Patient Checked In</div>
        </div>
        @endif

        @if($appointment->consultation_started_at)
        <div class="timeline-item">
          <div class="timeline-time">{{ $appointment->consultation_started_at->format('M d, Y h:i A') }}</div>
          <div class="timeline-label">Consultation Started</div>
        </div>
        @endif

        @if($appointment->consultation_ended_at)
        <div class="timeline-item">
          <div class="timeline-time">{{ $appointment->consultation_ended_at->format('M d, Y h:i A') }}</div>
          <div class="timeline-label">Consultation Completed</div>
        </div>
        @endif

        @if($appointment->cancelled_at)
        <div class="timeline-item">
          <div class="timeline-time">{{ $appointment->cancelled_at->format('M d, Y h:i A') }}</div>
          <div class="timeline-label text-danger">Appointment Cancelled</div>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Cancel Appointment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('appointments.cancel', $appointment) }}" method="POST">
        @csrf
        <div class="modal-body">
          <p>Are you sure you want to cancel this appointment for <strong>{{ $appointment->patient->full_name }}</strong>?</p>
          <div class="mb-3">
            <label class="form-label">Cancellation Reason <span class="text-danger">*</span></label>
            <textarea name="cancellation_reason" class="form-control" rows="3" required 
                      placeholder="Please provide a reason for cancellation..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-danger">Cancel Appointment</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
