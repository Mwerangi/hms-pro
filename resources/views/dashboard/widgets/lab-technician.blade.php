{{-- Lab Technician Dashboard Widgets --}}

<div class="stats-grid">
  <!-- Pending Tests -->
  <div class="stat-card">
    <div class="stat-icon" style="color: #ef4444;">
      <i class="bi bi-clipboard2-pulse"></i>
    </div>
    <div class="stat-label">Pending Tests</div>
    <div class="stat-value">{{ $stats['pending_lab_tests'] ?? 0 }}</div>
    <div class="stat-meta">
      <i class="bi bi-hourglass-split"></i>
      <span>Awaiting processing</span>
    </div>
  </div>

  <!-- In Progress -->
  <div class="stat-card">
    <div class="stat-icon" style="color: #f59e0b;">
      <i class="bi bi-gear"></i>
    </div>
    <div class="stat-label">In Progress</div>
    <div class="stat-value">{{ $stats['processing_tests'] ?? 0 }}</div>
    <div class="stat-meta">
      <i class="bi bi-clock"></i>
      <span>Currently processing</span>
    </div>
  </div>

  <!-- Completed Today -->
  <div class="stat-card">
    <div class="stat-icon" style="color: #10b981;">
      <i class="bi bi-check-circle"></i>
    </div>
    <div class="stat-label">Completed Today</div>
    <div class="stat-value">{{ $stats['completed_tests_today'] ?? 0 }}</div>
    <div class="stat-meta">
      <i class="bi bi-graph-up"></i>
      <span>Successfully completed</span>
    </div>
  </div>

  <!-- Total Tests -->
  <div class="stat-card">
    <div class="stat-icon" style="color: #8b5cf6;">
      <i class="bi bi-bar-chart"></i>
    </div>
    <div class="stat-label">Total Tests</div>
    <div class="stat-value">{{ $stats['total_lab_tests'] ?? 0 }}</div>
    <div class="stat-meta">
      <i class="bi bi-database"></i>
      <span>All time</span>
    </div>
  </div>
</div>

<!-- Quick Actions for Lab Tech -->
<div class="section-card" style="margin-bottom: 24px;">
  <h3 class="section-title">
    <i class="bi bi-lightning-charge"></i>
    Quick Actions
  </h3>
  <div class="quick-actions-grid">
    @can('lab.view-dashboard')
    <a href="{{ route('lab.dashboard') }}" class="quick-action-btn">
      <i class="bi bi-speedometer2"></i>
      <span>Lab Dashboard</span>
    </a>
    @endcan
    
    @can('lab.view-orders')
    <a href="{{ route('lab.orders.index') }}" class="quick-action-btn">
      <i class="bi bi-clipboard2-pulse"></i>
      <span>View Orders</span>
    </a>
    @endcan
    
    @can('lab.collect-sample')
    <a href="{{ route('lab.orders.index') }}?status=pending" class="quick-action-btn">
      <i class="bi bi-droplet"></i>
      <span>Collect Sample</span>
    </a>
    @endcan
    
    @can('lab.enter-results')
    <a href="{{ route('lab.orders.index') }}?status=processing" class="quick-action-btn">
      <i class="bi bi-file-earmark-medical"></i>
      <span>Enter Results</span>
    </a>
    @endcan
  </div>
</div>

<!-- Priority Tests -->
<div class="section-card" style="margin-bottom: 24px;">
  <h3 class="section-title">
    <i class="bi bi-exclamation-triangle text-warning"></i>
    Priority Tests
  </h3>
  <div class="priority-list">
    <div class="priority-item urgent">
      <span class="priority-badge">URGENT</span>
      <span class="priority-text">{{ $stats['urgent_tests'] ?? 0 }} urgent test(s) pending</span>
    </div>
    <div class="priority-item high">
      <span class="priority-badge">HIGH</span>
      <span class="priority-text">{{ $stats['high_priority_tests'] ?? 0 }} high priority test(s)</span>
    </div>
    <div class="priority-item normal">
      <span class="priority-badge">NORMAL</span>
      <span class="priority-text">{{ $stats['normal_priority_tests'] ?? 0 }} normal priority test(s)</span>
    </div>
  </div>
</div>
