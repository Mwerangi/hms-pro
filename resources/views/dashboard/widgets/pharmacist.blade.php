{{-- Pharmacist Dashboard Widgets --}}

<div class="stats-grid">
  <!-- Pending Prescriptions -->
  <div class="stat-card">
    <div class="stat-icon" style="color: #ef4444;">
      <i class="bi bi-prescription2"></i>
    </div>
    <div class="stat-label">Pending Prescriptions</div>
    <div class="stat-value">{{ $stats['pending_prescriptions'] ?? 0 }}</div>
    <div class="stat-meta">
      <i class="bi bi-clock"></i>
      <span>Awaiting dispensing</span>
    </div>
  </div>

  <!-- Dispensed Today -->
  <div class="stat-card">
    <div class="stat-icon" style="color: #10b981;">
      <i class="bi bi-check-circle"></i>
    </div>
    <div class="stat-label">Dispensed Today</div>
    <div class="stat-value">{{ $stats['dispensed_today'] ?? 0 }}</div>
    <div class="stat-meta">
      <i class="bi bi-graph-up"></i>
      <span>Successfully dispensed</span>
    </div>
  </div>

  <!-- Low Stock Items -->
  <div class="stat-card">
    <div class="stat-icon" style="color: #f59e0b;">
      <i class="bi bi-exclamation-triangle"></i>
    </div>
    <div class="stat-label">Low Stock Alerts</div>
    <div class="stat-value">{{ $stats['low_stock_items'] ?? 0 }}</div>
    <div class="stat-meta">
      <i class="bi bi-box-seam"></i>
      <span>Need reordering</span>
    </div>
  </div>

  <!-- Total Medicines -->
  <div class="stat-card">
    <div class="stat-icon" style="color: #8b5cf6;">
      <i class="bi bi-capsule"></i>
    </div>
    <div class="stat-label">Total Medicines</div>
    <div class="stat-value">{{ $stats['total_medicines'] ?? 0 }}</div>
    <div class="stat-meta">
      <i class="bi bi-database"></i>
      <span>In inventory</span>
    </div>
  </div>
</div>

<!-- Quick Actions for Pharmacist -->
<div class="section-card" style="margin-bottom: 24px;">
  <h3 class="section-title">
    <i class="bi bi-lightning-charge"></i>
    Quick Actions
  </h3>
  <div class="quick-actions-grid">
    @can('pharmacy.view-dashboard')
    <a href="{{ route('pharmacy.dashboard') }}" class="quick-action-btn">
      <i class="bi bi-speedometer2"></i>
      <span>Pharmacy Dashboard</span>
    </a>
    @endcan
    
    @can('pharmacy.view-prescriptions')
    <a href="{{ route('pharmacy.prescriptions.index') }}" class="quick-action-btn">
      <i class="bi bi-prescription2"></i>
      <span>View Prescriptions</span>
    </a>
    @endcan
    
    @can('pharmacy.manage-inventory')
    <a href="{{ route('pharmacy.medicines.index') }}" class="quick-action-btn">
      <i class="bi bi-box-seam"></i>
      <span>Manage Inventory</span>
    </a>
    @endcan
    
    @can('pharmacy.dispense')
    <a href="{{ route('pharmacy.prescriptions.index') }}?status=pending" class="quick-action-btn">
      <i class="bi bi-capsule-pill"></i>
      <span>Dispense Medicines</span>
    </a>
    @endcan
  </div>
</div>

<!-- Stock Alerts -->
@if(isset($lowStockMedicines) && $lowStockMedicines->count() > 0)
<div class="section-card alert-card" style="margin-bottom: 24px;">
  <h3 class="section-title">
    <i class="bi bi-exclamation-triangle text-warning"></i>
    Low Stock Alerts
  </h3>
  <div class="alert-list">
    @foreach($lowStockMedicines->take(5) as $medicine)
    <div class="alert-item">
      <div class="alert-icon">
        <i class="bi bi-capsule"></i>
      </div>
      <div class="alert-content">
        <div class="alert-title">{{ $medicine->name }}</div>
        <div class="alert-meta">
          <span class="badge bg-warning">{{ $medicine->quantity_in_stock }} units left</span>
          <span class="text-muted">Reorder level: {{ $medicine->reorder_level }}</span>
        </div>
      </div>
    </div>
    @endforeach
  </div>
  @can('pharmacy.manage-inventory')
  <a href="{{ route('pharmacy.medicines.index') }}?filter=low_stock" class="btn btn-sm btn-warning mt-3">
    <i class="bi bi-box-seam"></i> View All Low Stock Items
  </a>
  @endcan
</div>
@endif
