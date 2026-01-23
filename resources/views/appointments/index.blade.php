@extends('layouts.app')

@section('title', 'Appointments')

@section('breadcrumbs')
<li class="breadcrumb-item active" aria-current="page">Appointments</li>
@endsection

@push('styles')
<style>
  /* Modern Minimalistic Design */
  .page-header {
    margin-bottom: 32px;
  }

  .page-title {
    font-size: 24px;
    font-weight: 600;
    color: #111827;
    margin: 0;
  }

  /* Stats */
  .stat-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px;
    transition: all 0.2s ease;
  }

  .stat-card:hover {
    border-color: #667eea;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
  }

  .stat-value {
    font-size: 28px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 4px;
  }

  .stat-label {
    font-size: 12px;
    color: #6b7280;
    font-weight: 500;
  }

  /* Badges */
  .status-badge {
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
  }

  .status-scheduled { background: #dbeafe; color: #1e40af; }
  .status-waiting { background: #fef3c7; color: #92400e; }
  .status-in-consultation { background: #ddd6fe; color: #5b21b6; }
  .status-completed { background: #d1fae5; color: #065f46; }
  .status-cancelled { background: #fee2e2; color: #991b1b; }
  .status-no-show { background: #e5e7eb; color: #374151; }

  .token-badge {
    background: #f3f4f6;
    color: #667eea;
    padding: 4px 10px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 12px;
  }

  /* Table */
  .card {
    border: 1px solid #e5e7eb;
    border-radius: 12px;
  }

  .table thead th {
    background: #f9fafb;
    color: #6b7280;
    font-weight: 600;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 14px 16px;
    border-bottom: 1px solid #e5e7eb;
  }

  .table tbody td {
    padding: 14px 16px;
    border-bottom: 1px solid #f3f4f6;
  }

  .table tbody tr:hover {
    background: #f9fafb;
  }

  .action-group {
    display: flex;
    gap: 6px;
  }

  .btn-icon {
    width: 32px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    font-size: 14px;
    border: 1px solid #e5e7eb;
  }

  .btn-icon:hover {
    border-color: #667eea;
  }

  /* Empty State */
  .empty-state {
    text-align: center;
    padding: 48px 20px;
  }

  .empty-state i {
    font-size: 48px;
    color: #d1d5db;
    margin-bottom: 12px;
  }

  .empty-state p {
    color: #9ca3af;
  }
</style>
@endpush

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
  <h1 class="page-title">Appointments Management</h1>
  <a href="{{ route('appointments.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-circle me-2"></i>Book Appointment
  </a>
</div>

<!-- Statistics Cards -->
<div class="row g-3 mb-4">
  <div class="col-md-2">
    <div class="stat-card">
      <div class="stat-value">{{ $stats['today_total'] }}</div>
      <div class="stat-label">Today's Total</div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="stat-card" style="animation-delay: 0.1s">
      <div class="stat-value">{{ $stats['scheduled'] }}</div>
      <div class="stat-label">Scheduled</div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="stat-card" style="animation-delay: 0.2s">
      <div class="stat-value">{{ $stats['waiting'] }}</div>
      <div class="stat-label">Waiting</div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="stat-card" style="animation-delay: 0.3s">
      <div class="stat-value">{{ $stats['in_consultation'] }}</div>
      <div class="stat-label">In Consultation</div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="stat-card" style="animation-delay: 0.4s">
      <div class="stat-value">{{ $stats['completed'] }}</div>
      <div class="stat-label">Completed</div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="stat-card" style="animation-delay: 0.5s">
      <div class="stat-value">{{ $stats['cancelled'] }}</div>
      <div class="stat-label">Cancelled</div>
    </div>
  </div>
</div>

<!-- Filters -->
<div class="card mb-4">
  <div class="card-body">
    <form method="GET" action="{{ route('appointments.index') }}" class="row g-3">
      <div class="col-md-3">
        <input type="text" name="search" class="form-control" 
               placeholder="Search by patient, token, or apt #" 
               value="{{ request('search') }}">
      </div>
      <div class="col-md-2">
        <input type="date" name="date" class="form-control" 
               value="{{ request('date', date('Y-m-d')) }}">
      </div>
      <div class="col-md-2">
        <select name="doctor_id" class="form-select">
          <option value="">All Doctors</option>
          @foreach($doctors as $doctor)
            <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
              Dr. {{ $doctor->name }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <select name="status" class="form-select">
          <option value="">All Status</option>
          <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
          <option value="waiting" {{ request('status') == 'waiting' ? 'selected' : '' }}>Waiting</option>
          <option value="in-consultation" {{ request('status') == 'in-consultation' ? 'selected' : '' }}>In Consultation</option>
          <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
          <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
      </div>
      <div class="col-md-2">
        <select name="type" class="form-select">
          <option value="">All Types</option>
          <option value="new" {{ request('type') == 'new' ? 'selected' : '' }}>New</option>
          <option value="followup" {{ request('type') == 'followup' ? 'selected' : '' }}>Follow-up</option>
          <option value="emergency" {{ request('type') == 'emergency' ? 'selected' : '' }}>Emergency</option>
        </select>
      </div>
      <div class="col-md-1">
        <button type="submit" class="btn btn-primary w-100">
          <i class="bi bi-search"></i>
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Appointments Table -->
<div class="card">
  @if($appointments->count() > 0)
  <div class="table-responsive">
    <table class="table table-hover mb-0">
      <thead>
        <tr>
          <th>Token</th>
          <th>Appointment #</th>
          <th>Patient</th>
          <th>Doctor</th>
          <th>Date & Time</th>
          <th>Type</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($appointments as $appointment)
        <tr>
          <td>
            <span class="token-badge">{{ $appointment->token_number }}</span>
          </td>
          <td>
            <a href="{{ route('appointments.show', $appointment) }}" class="text-decoration-none">
              {{ $appointment->appointment_number }}
            </a>
          </td>
          <td>
            <a href="{{ route('patients.show', $appointment->patient) }}" class="text-decoration-none">
              {{ $appointment->patient->full_name }}
            </a>
            <small class="d-block text-muted">{{ $appointment->patient->patient_id }}</small>
          </td>
          <td>Dr. {{ $appointment->doctor->name }}</td>
          <td>
            <div>{{ $appointment->appointment_date->format('M d, Y') }}</div>
            <small class="text-muted">{{ $appointment->appointment_time->format('h:i A') }}</small>
          </td>
          <td>
            <span class="badge bg-secondary">{{ ucfirst($appointment->appointment_type) }}</span>
          </td>
          <td>
            <span class="status-badge status-{{ $appointment->status }}">
              {{ ucfirst(str_replace('-', ' ', $appointment->status)) }}
            </span>
          </td>
          <td>
            <div class="action-group">
              @if($appointment->status === 'scheduled')
                <form action="{{ route('appointments.check-in', $appointment) }}" method="POST" class="d-inline">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-success btn-icon" title="Check In">
                    <i class="bi bi-check-circle"></i>
                  </button>
                </form>
              @endif

              @if(in_array($appointment->status, ['scheduled', 'waiting']))
                <form action="{{ route('appointments.start-consultation', $appointment) }}" method="POST" class="d-inline">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-info btn-icon" title="Start Consultation">
                    <i class="bi bi-play-circle"></i>
                  </button>
                </form>
              @endif

              @if($appointment->status === 'in-consultation')
                <form action="{{ route('appointments.complete-consultation', $appointment) }}" method="POST" class="d-inline">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-primary btn-icon" title="Complete">
                    <i class="bi bi-check-square"></i>
                  </button>
                </form>
              @endif

              <a href="{{ route('appointments.show', $appointment) }}" 
                 class="btn btn-sm btn-outline-primary btn-icon" title="View Details">
                <i class="bi bi-eye"></i>
              </a>

              @if($appointment->canBeRescheduled())
                <a href="{{ route('appointments.edit', $appointment) }}" 
                   class="btn btn-sm btn-outline-secondary btn-icon" title="Reschedule">
                  <i class="bi bi-calendar-event"></i>
                </a>
              @endif

              @if($appointment->canBeCancelled())
                <button type="button" class="btn btn-sm btn-outline-danger btn-icon" 
                        title="Cancel" onclick="showCancelModal({{ $appointment->id }}, '{{ $appointment->patient->full_name }}')">
                  <i class="bi bi-x-circle"></i>
                </button>
              @endif
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="p-3">
    {{ $appointments->links() }}
  </div>
  @else
  <div class="empty-state">
    <i class="bi bi-calendar-x"></i>
    <p>No appointments found</p>
    @if(request()->hasAny(['search', 'date', 'doctor_id', 'status', 'type']))
    <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-outline-primary" style="margin-top: 12px;">Clear Filters</a>
    @endif
  </div>
  @endif
</div>

<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Cancel Appointment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="cancelForm" method="POST">
        @csrf
        <div class="modal-body">
          <p id="cancelMessage"></p>
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

<!-- Toast Container -->
<div id="toastContainer" style="position: fixed; top: 80px; right: 20px; z-index: 9999;"></div>

@push('scripts')
<script>
function showCancelModal(appointmentId, patientName) {
  document.getElementById('cancelMessage').innerHTML = `Are you sure you want to cancel the appointment for <strong>${patientName}</strong>?`;
  document.getElementById('cancelForm').action = `/appointments/${appointmentId}/cancel`;
  
  const modal = new bootstrap.Modal(document.getElementById('cancelModal'));
  modal.show();
}

// Toast Notification
function showToast(message, type = 'success') {
  const container = document.getElementById('toastContainer');
  const toast = document.createElement('div');
  toast.className = `toast-notification ${type}`;
  
  const icon = type === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill';
  toast.innerHTML = `
    <i class="bi bi-${icon} me-2"></i>
    <span>${message}</span>
  `;
  
  container.appendChild(toast);
  
  setTimeout(() => toast.classList.add('show'), 100);
  setTimeout(() => {
    toast.classList.remove('show');
    setTimeout(() => toast.remove(), 300);
  }, 5000);
}

@if(session('success'))
  showToast("{{ session('success') }}", 'success');
@endif

@if(session('error'))
  showToast("{{ session('error') }}", 'error');
@endif
</script>
@endpush
@endsection
