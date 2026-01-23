@extends('layouts.app')

@section('title', 'My Appointments')

@section('breadcrumbs')
<li class="breadcrumb-item active" aria-current="page">My Appointments</li>
@endsection

@push('styles')
<style>
  .doctor-container {
    max-width: 1400px;
    margin: 0 auto;
  }

  .page-header {
    background: white;
    border-radius: 12px;
    padding: 20px 24px;
    margin-bottom: 24px;
    border: 1px solid #e5e7eb;
  }

  .header-title {
    font-size: 22px;
    font-weight: 600;
    color: #111827;
    margin: 0;
  }

  .header-subtitle {
    font-size: 14px;
    color: #6b7280;
    margin: 4px 0 0 0;
  }

  .date-filter {
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .date-filter input[type="date"] {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 14px;
  }

  .date-filter button {
    padding: 8px 16px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    background: white;
    transition: all 0.15s ease;
  }

  .date-filter button:hover {
    background: #f9fafb;
    border-color: #9ca3af;
  }

  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
  }

  .stat-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px;
    transition: all 0.15s ease;
  }

  .stat-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  }

  .stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    margin-bottom: 12px;
  }

  .stat-value {
    font-size: 28px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 4px;
  }

  .stat-label {
    font-size: 13px;
    color: #6b7280;
    font-weight: 500;
  }

  .content-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
  }

  .full-width {
    grid-column: 1 / -1;
  }

  @media (max-width: 1024px) {
    .content-grid {
      grid-template-columns: 1fr;
    }
  }

  .section-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 24px;
  }

  .section-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f3f4f6;
  }

  .section-header i {
    font-size: 20px;
    color: #667eea;
  }

  .section-header h3 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: #111827;
  }

  .section-header .count {
    margin-left: auto;
    background: #f3f4f6;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    color: #374151;
  }

  .appointment-card {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 16px;
    margin-bottom: 12px;
    transition: all 0.15s ease;
  }

  .appointment-card:hover {
    background: #f3f4f6;
    transform: translateX(2px);
  }

  .appointment-card.current {
    background: #eff6ff;
    border-color: #3b82f6;
    border-left-width: 3px;
  }

  .appointment-card.new-result {
    background: #f0fdf4;
    border-color: #10b981;
    border-left-width: 3px;
  }

  .token {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 50px;
    height: 50px;
    padding: 0 12px;
    background: #667eea;
    color: white;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 700;
    flex-shrink: 0;
  }

  .token.current {
    background: #3b82f6;
    animation: pulse 2s infinite;
  }

  @keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.03); }
  }

  .patient-name {
    font-size: 15px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 4px;
  }

  .patient-meta {
    font-size: 13px;
    color: #6b7280;
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 8px;
  }

  .patient-meta i {
    color: #9ca3af;
    margin-right: 4px;
  }

  .priority-tag {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 8px;
    border-radius: 5px;
    font-size: 11px;
    font-weight: 600;
  }

  .priority-emergency {
    background: #fef2f2;
    color: #991b1b;
  }

  .priority-walk-in {
    background: #fef3c7;
    color: #92400e;
  }

  .priority-scheduled {
    background: #eff6ff;
    color: #1e40af;
  }

  .current-indicator {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #3b82f6;
    color: white;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
  }

  .btn-action {
    padding: 6px 14px;
    font-size: 13px;
    font-weight: 500;
    border-radius: 6px;
    transition: all 0.15s ease;
  }

  .btn-action:hover {
    transform: translateY(-1px);
  }

  .empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #9ca3af;
  }

  .empty-state i {
    font-size: 48px;
    margin-bottom: 12px;
    opacity: 0.4;
  }

  .empty-state p {
    margin: 0;
    font-size: 14px;
  }

  .status-indicator {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 500;
  }

  [data-theme="dark"] .page-header,
  [data-theme="dark"] .stat-card,
  [data-theme="dark"] .section-card {
    background: #1e293b;
    border-color: #334155;
  }

  [data-theme="dark"] .header-title,
  [data-theme="dark"] .section-header h3,
  [data-theme="dark"] .stat-value,
  [data-theme="dark"] .patient-name {
    color: #f1f5f9;
  }

  [data-theme="dark"] .header-subtitle,
  [data-theme="dark"] .stat-label,
  [data-theme="dark"] .patient-meta {
    color: #cbd5e1;
  }

  [data-theme="dark"] .appointment-card {
    background: #0f172a;
    border-color: #334155;
  }

  [data-theme="dark"] .appointment-card:hover {
    background: #1e293b;
  }

  [data-theme="dark"] .section-header {
    border-color: #334155;
  }
</style>
@endpush

@section('content')
<div class="doctor-container">
  <!-- Page Header -->
  <div class="page-header">
    <div class="d-flex justify-content-between align-items-start">
      <div>
        <h1 class="header-title">My Appointments</h1>
        <p class="header-subtitle">
          Dr. {{ auth()->user()->name }} • {{ $date->format('l, F d, Y') }}
        </p>
      </div>
      <form action="{{ route('doctor.appointments') }}" method="GET" class="date-filter">
        <input type="date" 
               name="date" 
               value="{{ $date->format('Y-m-d') }}"
               onchange="this.form.submit()">
        <button type="submit">
          <i class="bi bi-search"></i>
        </button>
      </form>
    </div>
  </div>

  <!-- Stats Grid -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon" style="background: #eff6ff; color: #3b82f6;">
        <i class="bi bi-calendar-check"></i>
      </div>
      <div class="stat-value">{{ $stats['total'] }}</div>
      <div class="stat-label">Total Today</div>
    </div>

    <div class="stat-card">
      <div class="stat-icon" style="background: #fef3c7; color: #f59e0b;">
        <i class="bi bi-clock-history"></i>
      </div>
      <div class="stat-value">{{ $stats['scheduled'] }}</div>
      <div class="stat-label">Scheduled</div>
    </div>

    <div class="stat-card">
      <div class="stat-icon" style="background: #fee2e2; color: #ef4444;">
        <i class="bi bi-hourglass-split"></i>
      </div>
      <div class="stat-value">{{ $stats['waiting'] }}</div>
      <div class="stat-label">Waiting</div>
    </div>

    <div class="stat-card">
      <div class="stat-icon" style="background: #d1fae5; color: #10b981;">
        <i class="bi bi-check-circle"></i>
      </div>
      <div class="stat-value">{{ $stats['completed'] }}</div>
      <div class="stat-label">Completed</div>
    </div>
  </div>

  <!-- Content Grid -->
  <div class="content-grid">
    <!-- Patient Queue - Full Width -->
    <div class="section-card full-width">
      <div class="section-header">
        <i class="bi bi-person-lines-fill"></i>
        <h3>Patient Queue</h3>
        @php
          $currentAppointment = $appointments->firstWhere('status', 'in-consultation');
          $waitingQueue = $appointments->where('status', 'waiting')->sortBy('queue_position');
        @endphp
        <span class="count">{{ $waitingQueue->count() + ($currentAppointment ? 1 : 0) }}</span>
      </div>

      @if($currentAppointment || $waitingQueue->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(450px, 1fr)); gap: 16px;">
          @if($currentAppointment)
            <div class="appointment-card current">
              <div class="d-flex gap-3 align-items-start">
                <div class="token current">{{ $currentAppointment->token_number }}</div>
                <div class="flex-grow-1">
                  <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                    <div class="patient-name">{{ $currentAppointment->patient->full_name }}</div>
                    <div class="current-indicator">
                      <span class="pulse-dot" style="width: 6px; height: 6px; background: white; border-radius: 50%; animation: pulse 2s infinite;"></span>
                      In Consultation
                    </div>
                  </div>
                  <div class="patient-meta">
                    <span><i class="bi bi-person-badge"></i>{{ $currentAppointment->patient->patient_id }}</span>
                    <span><i class="bi bi-calendar3"></i>{{ $currentAppointment->patient->age }}Y, {{ $currentAppointment->patient->gender }}</span>
                  </div>
                  <div class="d-flex align-items-center gap-2 flex-wrap mt-2">
                    @if($currentAppointment->patient_type === 'emergency')
                      <span class="priority-tag priority-emergency">
                        <i class="bi bi-exclamation-triangle-fill"></i> Emergency
                      </span>
                    @elseif($currentAppointment->patient_type === 'walk-in')
                      <span class="priority-tag priority-walk-in">
                        <i class="bi bi-person-walking"></i> Walk-in
                      </span>
                    @else
                      <span class="priority-tag priority-scheduled">
                        <i class="bi bi-calendar-check"></i> {{ $currentAppointment->appointment_time->format('h:i A') }}
                      </span>
                    @endif
                    @if($currentAppointment->consultation)
                      <a href="{{ route('consultations.edit', $currentAppointment->consultation) }}" class="btn btn-sm btn-primary btn-action">
                        <i class="bi bi-clipboard-pulse me-1"></i>Continue
                      </a>
                    @else
                      <a href="{{ route('consultations.start-from-appointment', $currentAppointment) }}" class="btn btn-sm btn-primary btn-action">
                        <i class="bi bi-clipboard-plus me-1"></i>Start
                      </a>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          @endif

          @foreach($waitingQueue as $appointment)
            <div class="appointment-card">
              <div class="d-flex gap-3 align-items-start">
                <div class="token">{{ $appointment->token_number }}</div>
                <div class="flex-grow-1">
                  <div class="patient-name">{{ $appointment->patient->full_name }}</div>
                  <div class="patient-meta">
                    <span><i class="bi bi-person-badge"></i>{{ $appointment->patient->patient_id }}</span>
                    <span><i class="bi bi-calendar3"></i>{{ $appointment->patient->age }}Y</span>
                  </div>
                  <div class="d-flex align-items-center gap-2 flex-wrap mt-2">
                    @if($appointment->patient_type === 'emergency')
                      <span class="priority-tag priority-emergency">
                        <i class="bi bi-exclamation-triangle-fill"></i> Emergency
                      </span>
                    @elseif($appointment->patient_type === 'walk-in')
                      <span class="priority-tag priority-walk-in">
                        <i class="bi bi-person-walking"></i> Walk-in
                      </span>
                    @else
                      <span class="priority-tag priority-scheduled">
                        <i class="bi bi-calendar-check"></i> {{ $appointment->appointment_time->format('h:i A') }}
                      </span>
                    @endif
                    @if($appointment->consultation)
                      <a href="{{ route('consultations.edit', $appointment->consultation) }}" class="btn btn-sm btn-primary btn-action">
                        <i class="bi bi-clipboard-pulse me-1"></i>View
                      </a>
                    @else
                      <a href="{{ route('consultations.start-from-appointment', $appointment) }}" class="btn btn-sm btn-success btn-action">
                        <i class="bi bi-play-fill me-1"></i>Start
                      </a>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div class="empty-state">
          <i class="bi bi-inbox"></i>
          <p>No patients in queue</p>
        </div>
      @endif
    </div>

    <!-- Today's Schedule -->
    <div class="section-card">
      <div class="section-header">
        <i class="bi bi-calendar-day"></i>
        <h3>Today's Schedule</h3>
        @php
          $scheduledAppointments = $appointments->where('status', 'scheduled')->sortBy('appointment_time');
        @endphp
        <span class="count">{{ $scheduledAppointments->count() }}</span>
      </div>

      @if($scheduledAppointments->count() > 0)
        @foreach($scheduledAppointments as $appointment)
          <div class="appointment-card">
            <div class="d-flex gap-3 align-items-start">
              <div class="token">{{ $appointment->token_number }}</div>
              <div class="flex-grow-1">
                <div class="patient-name">{{ $appointment->patient->full_name }}</div>
                <div class="patient-meta">
                  <span><i class="bi bi-clock"></i>{{ $appointment->appointment_time->format('h:i A') }}</span>
                  <span><i class="bi bi-person-badge"></i>{{ $appointment->patient->patient_id }}</span>
                </div>
              </div>
              <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-sm btn-outline-primary btn-action">
                <i class="bi bi-eye"></i>
              </a>
            </div>
          </div>
        @endforeach
      @else
        <div class="empty-state">
          <i class="bi bi-calendar-x"></i>
          <p>No scheduled appointments</p>
        </div>
      @endif
    </div>

    <!-- Completed Today -->
    @php
      $completedToday = $appointments->where('status', 'completed');
    @endphp

    @if($completedToday->count() > 0)
      <div class="section-card">
        <div class="section-header">
          <i class="bi bi-check-circle"></i>
          <h3>Completed Today</h3>
          <span class="count">{{ $completedToday->count() }}</span>
        </div>
        
        @foreach($completedToday as $appointment)
          <div class="appointment-card" style="background: #f0fdf4; border-color: #d1fae5;">
            <div class="d-flex justify-content-between align-items-center">
              <div class="d-flex align-items-center gap-3">
                <div class="token" style="background: #10b981;">{{ $appointment->token_number }}</div>
                <div>
                  <div class="patient-name">{{ $appointment->patient->full_name }}</div>
                  <div class="status-indicator" style="color: #059669;">
                    <i class="bi bi-clock-fill"></i>
                    {{ $appointment->consultation_ended_at ? $appointment->consultation_ended_at->format('h:i A') : 'Completed' }}
                  </div>
                </div>
              </div>
              <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-sm btn-outline-success btn-action">
                <i class="bi bi-eye"></i>
              </a>
            </div>
          </div>
        @endforeach
      </div>
    @endif

      <!-- Lab Results Pending Review -->
      @if(isset($consultationsWithPendingResults) && $consultationsWithPendingResults->count() > 0)
        <div class="section-card" style="margin-top: 20px;">
          <div class="section-header">
            <i class="bi bi-clipboard-pulse"></i>
            <h3>Lab Results Pending Review</h3>
            <span class="count">{{ $consultationsWithPendingResults->count() }}</span>
          </div>
          
          @foreach($consultationsWithPendingResults as $consultation)
            @php
              $newResults = $consultation->labOrders->where('status', 'reported')->whereNull('viewed_at')->count();
              $pendingResults = $consultation->labOrders->whereNotIn('status', ['reported', 'cancelled'])->count();
              $hasNewResults = $newResults > 0;
            @endphp
            <div class="appointment-card {{ $hasNewResults ? 'new-result' : '' }}">
              <div class="d-flex justify-content-between align-items-start gap-3">
                <div class="flex-grow-1">
                  <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                    <div class="patient-name">{{ $consultation->patient->full_name }}</div>
                    @if($hasNewResults)
                      <span style="background: #10b981; color: white; padding: 3px 8px; border-radius: 5px; font-size: 11px; font-weight: 600;">
                        <i class="bi bi-star-fill"></i> {{ $newResults }} New
                      </span>
                    @endif
                  </div>
                  <div class="patient-meta">
                    <span><i class="bi bi-hash"></i>{{ $consultation->consultation_number }}</span>
                    <span><i class="bi bi-calendar"></i>{{ $consultation->created_at->format('M d, h:i A') }}</span>
                  </div>
                  <div style="font-size: 12px; margin-top: 8px;">
                    @foreach($consultation->labOrders as $order)
                      @php
                        $isNewResult = $order->status === 'reported' && is_null($order->viewed_at);
                      @endphp
                      <span class="priority-tag {{ $isNewResult ? 'priority-emergency' : 'priority-scheduled' }}" style="margin-right: 4px; margin-bottom: 4px;">
                        {{ $order->tests_list }} - {{ $isNewResult ? 'NEW!' : ucfirst(str_replace('_', ' ', $order->status)) }}
                      </span>
                    @endforeach
                  </div>
                </div>
                <div class="d-flex flex-column gap-2">
                  @if($hasNewResults)
                    <a href="{{ route('consultations.show', $consultation) }}" class="btn btn-sm btn-success btn-action">
                      <i class="bi bi-file-earmark-medical"></i> View
                    </a>
                  @endif
                  @if($consultation->status === 'in-progress')
                    <a href="{{ route('consultations.edit', $consultation) }}" class="btn btn-sm btn-primary btn-action">
                      <i class="bi bi-pencil"></i>
                    </a>
                  @else
                    <form action="{{ route('consultations.resume', $consultation) }}" method="POST" class="d-inline">
                      @csrf
                      <button type="submit" class="btn btn-sm btn-warning btn-action">
                        <i class="bi bi-arrow-clockwise"></i> Resume
                      </button>
                    </form>
                  @endif
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
