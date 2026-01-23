@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
<style>
  /* Modern Minimalistic Dashboard Styles */
  .page-header {
    margin-bottom: 32px;
  }

  .page-header h1 {
    font-size: 28px;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
  }

  .page-subtitle {
    color: #6b7280;
    font-size: 14px;
    margin-top: 4px;
  }

  /* Stats Grid */
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
  }

  .stat-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 24px;
    transition: all 0.2s ease;
  }

  .stat-card:hover {
    border-color: #667eea;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
  }

  .stat-icon {
    width: 48px;
    height: 48px;
    background: #f3f4f6;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: #667eea;
    margin-bottom: 16px;
  }

  .stat-label {
    font-size: 13px;
    color: #6b7280;
    margin-bottom: 8px;
    font-weight: 500;
  }

  .stat-value {
    font-size: 32px;
    font-weight: 700;
    color: #111827;
    line-height: 1;
    margin-bottom: 8px;
  }

  .stat-meta {
    font-size: 13px;
    color: #9ca3af;
    display: flex;
    align-items: center;
    gap: 4px;
  }

  /* Content Grid */
  .content-grid {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 24px;
  }

  @media (max-width: 1200px) {
    .content-grid {
      grid-template-columns: 1fr;
    }
  }

  /* Section Card */
  .section-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 24px;
  }

  .section-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 1px solid #f3f4f6;
  }

  .section-header i {
    font-size: 20px;
    color: #667eea;
  }

  .section-header h3 {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin: 0;
    flex: 1;
  }

  .count {
    background: #f3f4f6;
    color: #6b7280;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 600;
  }

  /* Alert Card */
  .alert-card {
    background: #fef3c7;
    border: 1px solid #fbbf24;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 24px;
    display: flex;
    align-items: start;
    gap: 16px;
  }

  .alert-card i {
    font-size: 24px;
    color: #f59e0b;
    margin-top: 2px;
  }

  .alert-content h5 {
    font-size: 15px;
    font-weight: 600;
    color: #92400e;
    margin: 0 0 4px 0;
  }

  .alert-content p {
    font-size: 13px;
    color: #78350f;
    margin: 0;
  }

  /* Table Styles */
  .minimal-table {
    width: 100%;
    font-size: 14px;
  }

  .minimal-table thead th {
    background: #f9fafb;
    color: #6b7280;
    font-weight: 600;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 12px 16px;
    border-bottom: 1px solid #e5e7eb;
  }

  .minimal-table tbody td {
    padding: 16px;
    border-bottom: 1px solid #f3f4f6;
    color: #374151;
  }

  .minimal-table tbody tr:hover {
    background: #f9fafb;
  }

  .token-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 12px;
  }

  .badge-emergency { background: #fee2e2; color: #991b1b; }
  .badge-warning { background: #fef3c7; color: #92400e; }
  .badge-default { background: #e5e7eb; color: #374151; }

  /* Quick Actions */
  .quick-action {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 16px;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    transition: all 0.2s ease;
    text-decoration: none;
    color: inherit;
    margin-bottom: 12px;
  }

  .quick-action:hover {
    border-color: #667eea;
    background: #f9fafb;
    transform: translateX(4px);
  }

  .quick-action-icon {
    width: 44px;
    height: 44px;
    background: #f3f4f6;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: #667eea;
  }

  .quick-action-title {
    font-weight: 600;
    color: #111827;
    font-size: 14px;
    margin-bottom: 2px;
  }

  .quick-action-desc {
    font-size: 12px;
    color: #6b7280;
  }

  /* List Items */
  .list-item {
    padding: 14px 0;
    border-bottom: 1px solid #f3f4f6;
    text-decoration: none;
    color: inherit;
    display: block;
    transition: background 0.2s ease;
  }

  .list-item:hover {
    background: #f9fafb;
    margin: 0 -16px;
    padding-left: 16px;
    padding-right: 16px;
  }

  .list-item:last-child {
    border-bottom: none;
  }

  .list-item-title {
    font-weight: 600;
    color: #111827;
    font-size: 14px;
    margin-bottom: 4px;
  }

  .list-item-meta {
    font-size: 12px;
    color: #6b7280;
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
    margin: 0;
  }

  /* Button Styles */
  .btn-action {
    padding: 6px 14px;
    font-size: 13px;
    border-radius: 8px;
    font-weight: 500;
  }

  .btn-outline-primary {
    border-color: #e5e7eb;
    color: #667eea;
  }

  .btn-outline-primary:hover {
    background: #667eea;
    border-color: #667eea;
    color: white;
  }

  /* Profile Card */
  .profile-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 0;
    border-bottom: 1px solid #f3f4f6;
    font-size: 13px;
  }

  .profile-item:last-child {
    border-bottom: none;
  }

  .profile-label {
    color: #6b7280;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .profile-value {
    color: #111827;
    font-weight: 600;
  }

  .status-badge {
    background: #d1fae5;
    color: #065f46;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }

  .status-dot {
    width: 6px;
    height: 6px;
    background: #10b981;
    border-radius: 50%;
  }
</style>
@endpush

@section('content')
<!-- Page Header -->
<div class="page-header">
  <h1>Dashboard</h1>
  <div class="page-subtitle">Welcome back, {{ auth()->user()->name }} • {{ now()->format('l, F d, Y') }}</div>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon">
      <i class="bi bi-people-fill"></i>
    </div>
    <div class="stat-label">Total Patients</div>
    <div class="stat-value">{{ $stats['total_patients'] }}</div>
    <div class="stat-meta">
      <i class="bi bi-arrow-up-short"></i>
      {{ $stats['active_patients'] }} active
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon">
      <i class="bi bi-calendar-check-fill"></i>
    </div>
    <div class="stat-label">Appointments Today</div>
    <div class="stat-value">{{ $stats['appointments_today'] }}</div>
    <div class="stat-meta">
      <i class="bi bi-check-circle"></i>
      {{ $stats['completed_today'] }} completed
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon">
      <i class="bi bi-clock-history"></i>
    </div>
    <div class="stat-label">Pending Appointments</div>
    <div class="stat-value">{{ $stats['pending_appointments'] }}</div>
    <div class="stat-meta">
      <i class="bi bi-exclamation-triangle"></i>
      Needs attention
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon">
      <i class="bi bi-person-badge"></i>
    </div>
    <div class="stat-label">System Users</div>
    <div class="stat-value">{{ $stats['total_users'] }}</div>
    <div class="stat-meta">
      <i class="bi bi-shield-check"></i>
      {{ $stats['roles_count'] }} roles
    </div>
  </div>
</div>

<!-- Alerts/Reminders Section -->
@if($stuckAppointments->count() > 0 || $missedAppointments->count() > 0)
<div class="alert-card">
  <i class="bi bi-exclamation-triangle-fill"></i>
  <div class="alert-content">
    <h5>Action Required: Pending Appointments Need Attention</h5>
    <p>
      @if($stuckAppointments->count() > 0)
        <strong>{{ $stuckAppointments->count() }}</strong> appointment(s) waiting without consultation. 
      @endif
      @if($missedAppointments->count() > 0)
        <strong>{{ $missedAppointments->count() }}</strong> missed appointment(s) from previous dates.
      @endif
      Please review and take action below.
    </p>
  </div>
</div>
@endif

<div class="content-grid">
  <div>
    <!-- Stuck Appointments Section -->
    @if($stuckAppointments->count() > 0)
    <div class="section-card" style="margin-bottom: 24px; border-left: 3px solid #ef4444;">
      <div class="section-header">
        <i class="bi bi-exclamation-octagon" style="color: #ef4444;"></i>
        <h3>Stuck Appointments</h3>
        <span class="count" style="background: #fee2e2; color: #991b1b;">{{ $stuckAppointments->count() }}</span>
      </div>
      <div style="overflow-x: auto;">
        <table class="minimal-table">
          <thead>
            <tr>
              <th>Token</th>
              <th>Patient</th>
              <th>Doctor</th>
              <th>Date</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($stuckAppointments as $appointment)
            <tr>
              <td><span class="token-badge badge-emergency">{{ $appointment->token_number }}</span></td>
              <td>
                <div style="font-weight: 600;">{{ $appointment->patient->full_name }}</div>
                <div style="font-size: 12px; color: #6b7280;">{{ $appointment->patient->patient_id }}</div>
              </td>
              <td>
                @if($appointment->doctor)
                  Dr. {{ $appointment->doctor->name }}
                @else
                  <span style="color: #9ca3af;">Not assigned</span>
                @endif
              </td>
              <td>
                <div>{{ $appointment->appointment_date->format('M d, Y') }}</div>
                <div style="font-size: 12px; color: #ef4444;">{{ $appointment->appointment_date->diffForHumans() }}</div>
              </td>
              <td><span class="token-badge badge-warning">{{ ucfirst($appointment->status) }}</span></td>
              <td>
                <div style="display: flex; gap: 8px;">
                  <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-sm btn-outline-primary btn-action">
                    <i class="bi bi-eye"></i>
                  </a>
                  <a href="{{ route('consultations.start-from-appointment', $appointment->id) }}" class="btn btn-sm btn-success btn-action">
                    <i class="bi bi-play-fill"></i>
                  </a>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @endif

    <!-- Missed Appointments Section -->
    @if($missedAppointments->count() > 0)
    <div class="section-card" style="margin-bottom: 24px; border-left: 3px solid #f59e0b;">
      <div class="section-header">
        <i class="bi bi-calendar-x" style="color: #f59e0b;"></i>
        <h3>Missed Appointments</h3>
        <span class="count" style="background: #fef3c7; color: #92400e;">{{ $missedAppointments->count() }}</span>
      </div>
      <div style="overflow-x: auto;">
        <table class="minimal-table">
          <thead>
            <tr>
              <th>Token</th>
              <th>Patient</th>
              <th>Doctor</th>
              <th>Scheduled Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($missedAppointments as $appointment)
            <tr>
              <td><span class="token-badge badge-warning">{{ $appointment->token_number }}</span></td>
              <td>
                <div style="font-weight: 600;">{{ $appointment->patient->full_name }}</div>
                <div style="font-size: 12px; color: #6b7280;">{{ $appointment->patient->phone }}</div>
              </td>
              <td>
                @if($appointment->doctor)
                  Dr. {{ $appointment->doctor->name }}
                @else
                  <span style="color: #9ca3af;">Not assigned</span>
                @endif
              </td>
              <td>
                <div>{{ $appointment->appointment_date->format('M d, Y') }}</div>
                <div style="font-size: 12px; color: #f59e0b;">{{ $appointment->appointment_date->diffForHumans() }}</div>
              </td>
              <td>
                <div style="display: flex; gap: 8px;">
                  <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-sm btn-outline-primary btn-action">
                    <i class="bi bi-eye"></i>
                  </a>
                  <form action="{{ route('appointments.cancel', $appointment->id) }}" method="POST" class="d-inline" 
                        onsubmit="return confirm('Mark this appointment as missed/cancelled?')">
                    @csrf
                    <input type="hidden" name="cancellation_reason" value="Patient did not show up (No-show)">
                    <button type="submit" class="btn btn-sm btn-outline-danger btn-action">
                      <i class="bi bi-x-circle"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @endif

    <!-- All Pending Appointments -->
    <div class="section-card">
      <div class="section-header">
        <i class="bi bi-clock-history"></i>
        <h3>All Pending Appointments</h3>
        <span class="count">{{ $pendingAppointments->count() }}</span>
        <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-outline-primary btn-action" style="margin-left: auto;">View All</a>
      </div>
      @if($pendingAppointments->count() > 0)
      <div style="overflow-x: auto;">
        <table class="minimal-table">
          <thead>
            <tr>
              <th>Token</th>
              <th>Patient</th>
              <th>Doctor</th>
              <th>Date</th>
              <th>Type</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($pendingAppointments->take(10) as $appointment)
            <tr>
              <td>
                <span class="token-badge {{ 
                  $appointment->patient_type === 'emergency' ? 'badge-emergency' : 
                  ($appointment->patient_type === 'walk-in' ? 'badge-warning' : 'badge-default') 
                }}">
                  {{ $appointment->token_number }}
                </span>
              </td>
              <td>
                <div style="font-weight: 600;">{{ $appointment->patient->full_name }}</div>
                <div style="font-size: 12px; color: #6b7280;">{{ $appointment->patient->patient_id }}</div>
              </td>
              <td>
                @if($appointment->doctor)
                  <div style="font-size: 13px;">Dr. {{ $appointment->doctor->name }}</div>
                @else
                  <span style="color: #9ca3af;">-</span>
                @endif
              </td>
              <td>
                <div>{{ $appointment->appointment_date->format('M d, Y') }}</div>
                <div style="font-size: 12px; color: #6b7280;">{{ $appointment->appointment_time->format('h:i A') }}</div>
              </td>
              <td>
                @if($appointment->patient_type === 'emergency')
                  <span class="token-badge badge-emergency">Emergency</span>
                @elseif($appointment->patient_type === 'walk-in')
                  <span class="token-badge badge-warning">Walk-in</span>
                @else
                  <span class="token-badge badge-default">Scheduled</span>
                @endif
              </td>
              <td>
                <span class="token-badge badge-default">{{ ucfirst($appointment->status) }}</span>
              </td>
              <td>
                <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-sm btn-outline-primary btn-action">
                  <i class="bi bi-eye"></i>
                </a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      @if($pendingAppointments->count() > 10)
      <div style="text-align: center; margin-top: 20px;">
        <a href="{{ route('appointments.index') }}" class="btn btn-outline-primary btn-action">
          View All {{ $pendingAppointments->count() }} Pending Appointments
        </a>
      </div>
      @endif
      @else
      <div class="empty-state">
        <i class="bi bi-check-circle"></i>
        <p>No pending appointments</p>
      </div>
      @endif
    </div>
  </div>

  <div>
  <div>
    <div class="section-card">
      <div class="section-header">
        <i class="bi bi-lightning-charge"></i>
        <h3>Quick Actions</h3>
      </div>
      <div>
        <a href="{{ route('patients.create') }}" class="quick-action">
          <div class="quick-action-icon">
            <i class="bi bi-person-plus"></i>
          </div>
          <div>
            <div class="quick-action-title">Register Patient</div>
            <div class="quick-action-desc">Add new patient record</div>
          </div>
        </a>
        <a href="{{ route('appointments.create') }}" class="quick-action">
          <div class="quick-action-icon">
            <i class="bi bi-calendar-plus"></i>
          </div>
          <div>
            <div class="quick-action-title">Book Appointment</div>
            <div class="quick-action-desc">Schedule new appointment</div>
          </div>
        </a>
        <a href="{{ route('nursing.dashboard') }}" class="quick-action">
          <div class="quick-action-icon">
            <i class="bi bi-heart-pulse"></i>
          </div>
          <div>
            <div class="quick-action-title">Nursing Station</div>
            <div class="quick-action-desc">Record vitals</div>
          </div>
        </a>
        <a href="{{ route('doctor.appointments') }}" class="quick-action">
          <div class="quick-action-icon">
            <i class="bi bi-stethoscope"></i>
          </div>
          <div>
            <div class="quick-action-title">Doctor Dashboard</div>
            <div class="quick-action-desc">View patient queue</div>
          </div>
        </a>
      </div>
    </div>

    <!-- Recent Consultations -->
    <div class="section-card" style="margin-top: 20px;">
      <div class="section-header">
        <i class="bi bi-file-medical"></i>
        <h3>Recent Consultations</h3>
      </div>
      @if($recentConsultations->count() > 0)
      <div>
        @foreach($recentConsultations as $consultation)
        <a href="{{ route('consultations.show', $consultation->id) }}" class="list-item">
          <div class="d-flex justify-content-between align-items-start">
            <div class="flex-grow-1">
              <div class="list-item-title">{{ $consultation->patient->full_name }}</div>
              <div class="list-item-meta">
                <i class="bi bi-person-badge me-1"></i>Dr. {{ $consultation->doctor->name }}
              </div>
              <div class="list-item-meta">
                <i class="bi bi-calendar3 me-1"></i>{{ $consultation->created_at->format('M d, Y h:i A') }}
              </div>
            </div>
            <span class="token-badge {{ $consultation->status === 'completed' ? 'badge-default' : 'badge-warning' }}" style="margin-top: 4px;">
              {{ ucfirst($consultation->status) }}
            </span>
          </div>
        </a>
        @endforeach
      </div>
      @else
      <div class="empty-state">
        <i class="bi bi-inbox"></i>
        <p>No consultations yet</p>
      </div>
      @endif
    </div>

    <!-- Profile -->
    <div class="section-card" style="margin-top: 20px;">
      <div class="section-header">
        <i class="bi bi-person-circle"></i>
        <h3>Your Profile</h3>
      </div>
      <div>
        <div class="profile-item">
          <span class="profile-label"><i class="bi bi-shield-check"></i>Role:</span>
          <span class="profile-value text-capitalize">{{ $role }}</span>
        </div>
        <div class="profile-item">
          <span class="profile-label"><i class="bi bi-envelope"></i>Email:</span>
          <span class="profile-value" style="font-size: 12px;">{{ auth()->user()->email }}</span>
        </div>
        <div class="profile-item">
          <span class="profile-label"><i class="bi bi-clock"></i>Member Since:</span>
          <span class="profile-value">{{ auth()->user()->created_at->format('M d, Y') }}</span>
        </div>
        <div class="profile-item">
          <span class="profile-label"><i class="bi bi-activity"></i>Status:</span>
          <span class="status-badge">
            <span class="status-dot"></span> Online
          </span>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
// Show welcome toast on page load
document.addEventListener('DOMContentLoaded', function() {
  // Only show welcome toast once per session
  if (!sessionStorage.getItem('welcomeToastShown')) {
    showWelcomeToast();
    sessionStorage.setItem('welcomeToastShown', 'true');
  }
});

function showWelcomeToast() {
  const container = document.createElement('div');
  container.className = 'toast-container-welcome';
  container.style.cssText = `
    position: fixed;
    top: 80px;
    right: 20px;
    z-index: 9999;
  `;
  
  const toast = document.createElement('div');
  toast.className = 'welcome-toast';
  toast.style.cssText = `
    min-width: 380px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 16px;
    padding: 24px 28px;
    box-shadow: 0 12px 40px rgba(102, 126, 234, 0.4);
    display: flex;
    align-items: center;
    gap: 16px;
    animation: slideInRight 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
  `;
  
  toast.innerHTML = `
    <div style="
      width: 56px;
      height: 56px;
      background: rgba(255, 255, 255, 0.2);
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 28px;
      backdrop-filter: blur(10px);
    ">👋</div>
    <div style="flex: 1; color: white;">
      <div style="font-size: 18px; font-weight: 700; margin-bottom: 4px;">
        Welcome back, {{ auth()->user()->name }}!
      </div>
      <div style="font-size: 14px; opacity: 0.95;">
        {{ now()->format('l, F d, Y') }}
      </div>
    </div>
    <button onclick="this.closest('.toast-container-welcome').remove()" 
            style="
              background: rgba(255, 255, 255, 0.2);
              border: none;
              color: white;
              width: 32px;
              height: 32px;
              border-radius: 8px;
              display: flex;
              align-items: center;
              justify-content: center;
              cursor: pointer;
              transition: all 0.2s ease;
            "
            onmouseover="this.style.background='rgba(255, 255, 255, 0.3)'"
            onmouseout="this.style.background='rgba(255, 255, 255, 0.2)'">
      <i class="bi bi-x" style="font-size: 20px;"></i>
    </button>
  `;
  
  container.appendChild(toast);
  document.body.appendChild(container);
  
  // Auto remove after 6 seconds
  setTimeout(() => {
    toast.style.animation = 'slideOutRight 0.4s cubic-bezier(0.4, 0, 1, 1)';
    setTimeout(() => container.remove(), 400);
  }, 6000);
}
</script>
@endpush
@endsection
