{{-- Nurse Dashboard Widgets --}}

<div class="stats-grid">
  <!-- Patients to Check-in -->
  <div class="stat-card">
    <div class="stat-icon" style="color: #06b6d4;">
      <i class="bi bi-clipboard-check"></i>
    </div>
    <div class="stat-label">Patients to Check-in</div>
    <div class="stat-value">{{ $stats['scheduled_appointments'] ?? 0 }}</div>
    <div class="stat-meta">
      <i class="bi bi-clock"></i>
      <span>Scheduled today</span>
    </div>
  </div>

  <!-- Awaiting Vitals -->
  <div class="stat-card">
    <div class="stat-icon" style="color: #f59e0b;">
      <i class="bi bi-heart-pulse"></i>
    </div>
    <div class="stat-label">Awaiting Vitals</div>
    <div class="stat-value">{{ $stats['waiting_appointments'] ?? 0 }}</div>
    <div class="stat-meta">
      <i class="bi bi-activity"></i>
      <span>Need vitals recording</span>
    </div>
  </div>

  <!-- Admitted Patients (IPD) -->
  <div class="stat-card">
    <div class="stat-icon" style="color: #8b5cf6;">
      <i class="bi bi-hospital"></i>
    </div>
    <div class="stat-label">Admitted Patients</div>
    <div class="stat-value">{{ $stats['admitted_patients'] ?? 0 }}</div>
    <div class="stat-meta">
      <i class="bi bi-building"></i>
      <span>Currently in wards</span>
    </div>
  </div>

  <!-- Active Patients -->
  <div class="stat-card">
    <div class="stat-icon" style="color: #10b981;">
      <i class="bi bi-people"></i>
    </div>
    <div class="stat-label">Active Patients</div>
    <div class="stat-value">{{ $stats['active_patients'] ?? 0 }}</div>
    <div class="stat-meta">
      <i class="bi bi-graph-up"></i>
      <span>Total active</span>
    </div>
  </div>
</div>

<!-- Quick Actions for Nurse -->
<div class="section-card" style="margin-bottom: 24px;">
  <h3 class="section-title">
    <i class="bi bi-lightning-charge"></i>
    Quick Actions
  </h3>
  <div class="quick-actions-grid">
    @can('appointments.view-all')
    <a href="{{ route('appointments.index') }}" class="quick-action-btn">
      <i class="bi bi-calendar-check"></i>
      <span>Check-in Patients</span>
    </a>
    @endcan
    
    @can('nursing.record-vitals')
    <a href="{{ route('appointments.index') }}?status=waiting" class="quick-action-btn">
      <i class="bi bi-heart-pulse"></i>
      <span>Record Vitals</span>
    </a>
    @endcan
    
    @can('ipd.view-dashboard')
    <a href="{{ route('ipd.dashboard') }}" class="quick-action-btn">
      <i class="bi bi-hospital"></i>
      <span>IPD Dashboard</span>
    </a>
    @endcan
    
    @can('patients.view')
    <a href="{{ route('patients.index') }}" class="quick-action-btn">
      <i class="bi bi-people"></i>
      <span>View Patients</span>
    </a>
    @endcan
  </div>
</div>

<!-- Nursing Tasks -->
<div class="section-card" style="margin-bottom: 24px;">
  <h3 class="section-title">
    <i class="bi bi-list-check"></i>
    Today's Tasks
  </h3>
  <div class="task-list">
    <div class="task-item">
      <i class="bi bi-check-circle text-success"></i>
      <span>Morning ward rounds completed</span>
    </div>
    <div class="task-item pending">
      <i class="bi bi-circle"></i>
      <span>Record vitals for waiting patients ({{ $stats['waiting_appointments'] ?? 0 }})</span>
    </div>
    <div class="task-item pending">
      <i class="bi bi-circle"></i>
      <span>Medication administration - {{ now()->format('g:i A') }}</span>
    </div>
    <div class="task-item pending">
      <i class="bi bi-circle"></i>
      <span>Evening ward rounds - 6:00 PM</span>
    </div>
  </div>
</div>
