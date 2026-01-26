{{-- Admin Dashboard Widgets --}}

<div class="stats-grid">
  <!-- Total Users -->
  <div class="stat-card">
    <div class="stat-icon">
      <i class="bi bi-people"></i>
    </div>
    <div class="stat-label">Total Users</div>
    <div class="stat-value">{{ $stats['total_users'] ?? 0 }}</div>
    <div class="stat-meta">
      <span class="badge bg-success">{{ $stats['active_users'] ?? 0 }} active</span>
    </div>
  </div>

  <!-- Total Patients -->
  <div class="stat-card">
    <div class="stat-icon" style="color: #8b5cf6;">
      <i class="bi bi-person-hearts"></i>
    </div>
    <div class="stat-label">Total Patients</div>
    <div class="stat-value">{{ $stats['total_patients'] ?? 0 }}</div>
    <div class="stat-meta">
      <span class="badge bg-success">{{ $stats['active_patients'] ?? 0 }} active</span>
    </div>
  </div>

  <!-- Today's Appointments -->
  <div class="stat-card">
    <div class="stat-icon" style="color: #667eea;">
      <i class="bi bi-calendar-event"></i>
    </div>
    <div class="stat-label">Today's Appointments</div>
    <div class="stat-value">{{ $stats['appointments_today'] ?? 0 }}</div>
    <div class="stat-meta">
      <i class="bi bi-clock"></i>
      <span>{{ now()->format('l') }}</span>
    </div>
  </div>

  <!-- System Health -->
  <div class="stat-card">
    <div class="stat-icon" style="color: #10b981;">
      <i class="bi bi-shield-check"></i>
    </div>
    <div class="stat-label">System Status</div>
    <div class="stat-value" style="font-size: 20px;">Healthy</div>
    <div class="stat-meta">
      <i class="bi bi-check-circle text-success"></i>
      <span>All systems operational</span>
    </div>
  </div>
</div>

<!-- Quick Actions for Admin -->
<div class="section-card" style="margin-bottom: 24px;">
  <h3 class="section-title">
    <i class="bi bi-lightning-charge"></i>
    Quick Actions
  </h3>
  <div class="quick-actions-grid">
    @can('users.view')
    <a href="{{ route('users.index') }}" class="quick-action-btn">
      <i class="bi bi-people"></i>
      <span>Manage Users</span>
    </a>
    @endcan
    
    @can('patients.view')
    <a href="{{ route('patients.index') }}" class="quick-action-btn">
      <i class="bi bi-person-hearts"></i>
      <span>Manage Patients</span>
    </a>
    @endcan
    
    @can('settings.view')
    <a href="{{ route('settings.category', ['category' => 'roles']) }}" class="quick-action-btn">
      <i class="bi bi-gear"></i>
      <span>Settings</span>
    </a>
    @endcan
    
    @can('billing.view-dashboard')
    <a href="{{ route('accounting.dashboard') }}" class="quick-action-btn">
      <i class="bi bi-bar-chart"></i>
      <span>View Reports</span>
    </a>
    @endcan
  </div>
</div>

<!-- Department Overview -->
<div class="section-card" style="margin-bottom: 24px;">
  <h3 class="section-title">
    <i class="bi bi-diagram-3"></i>
    Department Overview
  </h3>
  <div class="department-grid">
    <div class="department-item">
      <i class="bi bi-hospital"></i>
      <div class="department-info">
        <div class="department-name">OPD</div>
        <div class="department-stat">{{ $stats['appointments_today'] ?? 0 }} today</div>
      </div>
    </div>
    <div class="department-item">
      <i class="bi bi-building"></i>
      <div class="department-info">
        <div class="department-name">IPD</div>
        <div class="department-stat">{{ $stats['admitted_patients'] ?? 0 }} admitted</div>
      </div>
    </div>
    <div class="department-item">
      <i class="bi bi-clipboard-pulse"></i>
      <div class="department-info">
        <div class="department-name">Laboratory</div>
        <div class="department-stat">{{ $stats['lab_orders'] ?? 0 }} tests</div>
      </div>
    </div>
    <div class="department-item">
      <i class="bi bi-capsule"></i>
      <div class="department-info">
        <div class="department-name">Pharmacy</div>
        <div class="department-stat">{{ $stats['pending_prescriptions'] ?? 0 }} pending</div>
      </div>
    </div>
  </div>
</div>

<!-- Recent Activity -->
<div class="section-card">
  <h3 class="section-title">
    <i class="bi bi-clock-history"></i>
    Recent Activity
  </h3>
  <div class="activity-list">
    <div class="activity-item">
      <div class="activity-icon bg-success">
        <i class="bi bi-person-plus"></i>
      </div>
      <div class="activity-content">
        <div class="activity-text">{{ $stats['new_users_today'] ?? 0 }} new user(s) registered today</div>
        <div class="activity-time">Today</div>
      </div>
    </div>
    <div class="activity-item">
      <div class="activity-icon bg-primary">
        <i class="bi bi-calendar-check"></i>
      </div>
      <div class="activity-content">
        <div class="activity-text">{{ $stats['completed_today'] ?? 0 }} appointments completed</div>
        <div class="activity-time">Today</div>
      </div>
    </div>
    <div class="activity-item">
      <div class="activity-icon bg-warning">
        <i class="bi bi-person-hearts"></i>
      </div>
      <div class="activity-content">
        <div class="activity-text">{{ $stats['new_patients_today'] ?? 0 }} new patient(s) registered</div>
        <div class="activity-time">Today</div>
      </div>
    </div>
  </div>
</div>
