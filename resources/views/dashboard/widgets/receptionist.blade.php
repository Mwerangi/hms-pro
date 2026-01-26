{{-- Receptionist Dashboard Widgets --}}

<div class="stats-grid">
  <!-- Today's Appointments -->
  <div class="stat-card">
    <div class="stat-icon">
      <i class="bi bi-calendar-event"></i>
    </div>
    <div class="stat-label">Today's Appointments</div>
    <div class="stat-value">{{ $stats['appointments_today'] ?? 0 }}</div>
    <div class="stat-meta">
      <i class="bi bi-clock"></i>
      <span>{{ now()->format('l') }}</span>
    </div>
  </div>

  <!-- Checked-in Patients -->
  <div class="stat-card">
    <div class="stat-icon" style="color: #10b981;">
      <i class="bi bi-check-circle"></i>
    </div>
    <div class="stat-label">Checked-in Today</div>
    <div class="stat-value">{{ $stats['checkedin_today'] ?? 0 }}</div>
    <div class="stat-meta">
      <i class="bi bi-person-check"></i>
      <span>Successfully checked in</span>
    </div>
  </div>

  <!-- Total Patients -->
  <div class="stat-card">
    <div class="stat-icon" style="color: #8b5cf6;">
      <i class="bi bi-people"></i>
    </div>
    <div class="stat-label">Total Patients</div>
    <div class="stat-value">{{ $stats['total_patients'] ?? 0 }}</div>
    <div class="stat-meta">
      <i class="bi bi-database"></i>
      <span>Registered</span>
    </div>
  </div>

  <!-- Pending Bills -->
  <div class="stat-card">
    <div class="stat-icon" style="color: #f59e0b;">
      <i class="bi bi-receipt"></i>
    </div>
    <div class="stat-label">Pending Bills</div>
    <div class="stat-value">{{ $stats['pending_bills'] ?? 0 }}</div>
    <div class="stat-meta">
      <i class="bi bi-currency-dollar"></i>
      <span>Awaiting payment</span>
    </div>
  </div>
</div>

<!-- Quick Actions for Receptionist -->
<div class="section-card" style="margin-bottom: 24px;">
  <h3 class="section-title">
    <i class="bi bi-lightning-charge"></i>
    Quick Actions
  </h3>
  <div class="quick-actions-grid">
    @can('patients.create')
    <a href="{{ route('patients.create') }}" class="quick-action-btn">
      <i class="bi bi-person-plus"></i>
      <span>Register Patient</span>
    </a>
    @endcan
    
    @can('appointments.create')
    <a href="{{ route('appointments.create') }}" class="quick-action-btn">
      <i class="bi bi-calendar-plus"></i>
      <span>Book Appointment</span>
    </a>
    @endcan
    
    @can('appointments.checkin')
    <a href="{{ route('appointments.index') }}?status=scheduled" class="quick-action-btn">
      <i class="bi bi-check-circle"></i>
      <span>Check-in Patient</span>
    </a>
    @endcan
    
    @can('billing.create-bill')
    <a href="{{ route('billing.dashboard') }}" class="quick-action-btn">
      <i class="bi bi-receipt"></i>
      <span>Billing</span>
    </a>
    @endcan
  </div>
</div>

<!-- Today's Schedule -->
<div class="section-card" style="margin-bottom: 24px;">
  <h3 class="section-title">
    <i class="bi bi-calendar2-week"></i>
    Today's Schedule Overview
  </h3>
  <div class="schedule-stats">
    <div class="schedule-stat">
      <span class="schedule-label">Scheduled</span>
      <span class="schedule-value">{{ $stats['scheduled_appointments'] ?? 0 }}</span>
      <div class="schedule-bar">
        <div class="schedule-fill" style="width: {{ ($stats['scheduled_appointments'] ?? 0) > 0 ? (($stats['scheduled_appointments'] ?? 0) / max(($stats['appointments_today'] ?? 1), 1) * 100) : 0 }}%; background: #667eea;"></div>
      </div>
    </div>
    <div class="schedule-stat">
      <span class="schedule-label">Checked-in</span>
      <span class="schedule-value">{{ $stats['checkedin_today'] ?? 0 }}</span>
      <div class="schedule-bar">
        <div class="schedule-fill" style="width: {{ ($stats['checkedin_today'] ?? 0) > 0 ? (($stats['checkedin_today'] ?? 0) / max(($stats['appointments_today'] ?? 1), 1) * 100) : 0 }}%; background: #10b981;"></div>
      </div>
    </div>
    <div class="schedule-stat">
      <span class="schedule-label">In Consultation</span>
      <span class="schedule-value">{{ $stats['in_consultation'] ?? 0 }}</span>
      <div class="schedule-bar">
        <div class="schedule-fill" style="width: {{ ($stats['in_consultation'] ?? 0) > 0 ? (($stats['in_consultation'] ?? 0) / max(($stats['appointments_today'] ?? 1), 1) * 100) : 0 }}%; background: #f59e0b;"></div>
      </div>
    </div>
    <div class="schedule-stat">
      <span class="schedule-label">Completed</span>
      <span class="schedule-value">{{ $stats['completed_today'] ?? 0 }}</span>
      <div class="schedule-bar">
        <div class="schedule-fill" style="width: {{ ($stats['completed_today'] ?? 0) > 0 ? (($stats['completed_today'] ?? 0) / max(($stats['appointments_today'] ?? 1), 1) * 100) : 0 }}%; background: #06b6d4;"></div>
      </div>
    </div>
  </div>
</div>
