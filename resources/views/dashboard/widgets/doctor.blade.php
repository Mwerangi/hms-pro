{{-- Doctor Dashboard Widgets --}}

<div class="stats-grid">
  <!-- Today's Appointments -->
  <div class="stat-card">
    <div class="stat-icon">
      <i class="bi bi-calendar-check"></i>
    </div>
    <div class="stat-label">Today's Appointments</div>
    <div class="stat-value">{{ $stats['appointments_today'] ?? 0 }}</div>
    <div class="stat-meta">
      <i class="bi bi-clock"></i>
      <span>{{ now()->format('l') }}</span>
    </div>
  </div>

  <!-- Pending Consultations -->
  <div class="stat-card">
    <div class="stat-icon" style="color: #f59e0b;">
      <i class="bi bi-hourglass-split"></i>
    </div>
    <div class="stat-label">Pending Consultations</div>
    <div class="stat-value">{{ $stats['pending_appointments'] ?? 0 }}</div>
    <div class="stat-meta">
      <i class="bi bi-person-lines-fill"></i>
      <span>Awaiting consultation</span>
    </div>
  </div>

  <!-- Completed Today -->
  <div class="stat-card">
    <div class="stat-icon" style="color: #10b981;">
      <i class="bi bi-check-circle"></i>
    </div>
    <div class="stat-label">Completed Today</div>
    <div class="stat-value">{{ $stats['completed_today'] ?? 0 }}</div>
    <div class="stat-meta">
      <i class="bi bi-graph-up"></i>
      <span>Successfully completed</span>
    </div>
  </div>

  <!-- Total Patients (Doctor's) -->
  <div class="stat-card">
    <div class="stat-icon" style="color: #8b5cf6;">
      <i class="bi bi-people"></i>
    </div>
    <div class="stat-label">My Patients</div>
    <div class="stat-value">{{ $stats['doctor_patients'] ?? 0 }}</div>
    <div class="stat-meta">
      <i class="bi bi-heart-pulse"></i>
      <span>Under your care</span>
    </div>
  </div>
</div>

<!-- Quick Actions for Doctor -->
<div class="section-card" style="margin-bottom: 24px;">
  <h3 class="section-title">
    <i class="bi bi-lightning-charge"></i>
    Quick Actions
  </h3>
  <div class="quick-actions-grid">
    @can('appointments.view-own')
    <a href="{{ route('appointments.index') }}" class="quick-action-btn">
      <i class="bi bi-calendar-event"></i>
      <span>My Appointments</span>
    </a>
    @endcan
    
    @can('consultations.view-own')
    <a href="{{ route('consultations.index') }}" class="quick-action-btn">
      <i class="bi bi-clipboard2-pulse"></i>
      <span>Consultations</span>
    </a>
    @endcan
    
    @can('patients.view')
    <a href="{{ route('patients.index') }}" class="quick-action-btn">
      <i class="bi bi-people"></i>
      <span>Patients</span>
    </a>
    @endcan
    
    @can('lab.view-orders')
    <a href="{{ route('lab.dashboard') }}" class="quick-action-btn">
      <i class="bi bi-clipboard-pulse"></i>
      <span>Lab Results</span>
    </a>
    @endcan
  </div>
</div>
