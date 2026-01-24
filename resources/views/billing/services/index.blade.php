@extends('layouts.app')

@section('title', 'Service Catalog')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('services.index') }}">Accounting</a></li>
<li class="breadcrumb-item active" aria-current="page">Service Catalog</li>
@endsection

@push('styles')
<style>
  /* Modern Minimalistic Design */
  .page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
  }

  .page-title {
    font-size: 24px;
    font-weight: 600;
    color: #111827;
    margin: 0 0 4px 0;
  }

  .page-subtitle {
    color: #6b7280;
    font-size: 14px;
    margin: 0;
  }

  /* Stats Grid */
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
  }

  .stat-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px;
    transition: all 0.2s ease;
  }

  .stat-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transform: translateY(-2px);
  }

  .stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 12px;
    font-size: 20px;
  }

  .stat-value {
    font-size: 28px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 4px;
  }

  .stat-label {
    font-size: 13px;
    color: #6b7280;
    font-weight: 500;
  }

  /* Table */
  .modern-table {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
  }

  .table-header {
    padding: 20px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .table-title {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin: 0;
  }

  .filter-group {
    display: flex;
    gap: 12px;
  }

  .filter-select {
    padding: 8px 12px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 13px;
    color: #374151;
    background: white;
  }

  table {
    width: 100%;
    border-collapse: collapse;
  }

  thead {
    background: #f9fafb;
  }

  th {
    padding: 12px 20px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  td {
    padding: 16px 20px;
    font-size: 14px;
    color: #374151;
    border-top: 1px solid #f3f4f6;
  }

  tbody tr:hover {
    background: #f9fafb;
  }

  .badge {
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
  }

  .badge-success { background: #d1fae5; color: #065f46; }
  .badge-warning { background: #fef3c7; color: #92400e; }
  .badge-danger { background: #fee2e2; color: #991b1b; }
  .badge-info { background: #dbeafe; color: #1e40af; }
  .badge-purple { background: #ede9fe; color: #5b21b6; }

  .action-btn {
    padding: 6px 12px;
    font-size: 13px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    background: white;
    color: #374151;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-block;
    margin-right: 4px;
  }

  .action-btn:hover {
    background: #f9fafb;
    border-color: #667eea;
    color: #667eea;
  }

  .btn-primary-custom {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-block;
  }

  .btn-primary-custom:hover {
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    transform: translateY(-1px);
    color: white;
  }

  .empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #9ca3af;
  }

  .empty-state i {
    font-size: 48px;
    margin-bottom: 16px;
    opacity: 0.5;
  }

  .empty-state p {
    font-size: 14px;
    margin: 0;
  }
</style>
@endpush

@section('content')
<div class="page-header">
  <div>
    <h1 class="page-title">Service Catalog</h1>
    <p class="page-subtitle">Manage hospital services and pricing</p>
  </div>
  <div>
    <a href="{{ route('services.create') }}" class="btn-primary-custom">
      <i class="bi bi-plus-circle me-2"></i>Add New Service
    </a>
  </div>
</div>

<!-- Statistics -->
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon" style="background: #ede9fe; color: #7c3aed;">
      <i class="bi bi-list-check"></i>
    </div>
    <div class="stat-value">{{ $services->total() }}</div>
    <div class="stat-label">Total Services</div>
  </div>

  <div class="stat-card">
    <div class="stat-icon" style="background: #d1fae5; color: #059669;">
      <i class="bi bi-check-circle"></i>
    </div>
    <div class="stat-value">{{ $services->filter(fn($s) => $s->is_active)->count() }}</div>
    <div class="stat-label">Active Services</div>
  </div>

  <div class="stat-card">
    <div class="stat-icon" style="background: #dbeafe; color: #2563eb;">
      <i class="bi bi-wallet2"></i>
    </div>
    <div class="stat-value">{{ $services->filter(fn($s) => $s->taxable)->count() }}</div>
    <div class="stat-label">Taxable Services</div>
  </div>

  <div class="stat-card">
    <div class="stat-icon" style="background: #fef3c7; color: #d97706;">
      <i class="bi bi-tags"></i>
    </div>
    <div class="stat-value">{{ $services->pluck('category')->unique()->count() }}</div>
    <div class="stat-label">Categories</div>
  </div>
</div>

<!-- Services Table -->
<div class="modern-table">
  <div class="table-header">
    <h3 class="table-title">All Services</h3>
    <div class="filter-group">
      <select class="filter-select" onchange="filterServices(this.value)">
        <option value="">All Categories</option>
        <option value="consultation">Consultation</option>
        <option value="laboratory">Laboratory</option>
        <option value="radiology">Radiology</option>
        <option value="procedure">Procedure</option>
        <option value="pharmacy">Pharmacy</option>
        <option value="room_charge">Room Charge</option>
        <option value="nursing_care">Nursing Care</option>
        <option value="emergency">Emergency</option>
        <option value="surgery">Surgery</option>
        <option value="other">Other</option>
      </select>
      <select class="filter-select" onchange="filterStatus(this.value)">
        <option value="">All Status</option>
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
      </select>
    </div>
  </div>

  @if($services->count() > 0)
  <table>
    <thead>
      <tr>
        <th>Service Name</th>
        <th>Category</th>
        <th>Charge</th>
        <th>Tax Rate</th>
        <th>Total (incl. Tax)</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($services as $service)
      <tr>
        <td>
          <strong>{{ $service->service_name }}</strong>
          @if($service->description)
          <br><small style="color: #9ca3af;">{{ Str::limit($service->description, 50) }}</small>
          @endif
        </td>
        <td>
          <span class="badge badge-info">
            {{ ucfirst(str_replace('_', ' ', $service->category)) }}
          </span>
        </td>
        <td>TSh {{ number_format($service->standard_charge, 2) }}</td>
        <td>
          @if($service->taxable)
            {{ $service->tax_percentage }}%
          @else
            <span class="text-muted">N/A</span>
          @endif
        </td>
        <td>
          <strong>TSh {{ number_format($service->getChargeWithTax(), 2) }}</strong>
        </td>
        <td>
          @if($service->is_active)
            <span class="badge badge-success">Active</span>
          @else
            <span class="badge badge-danger">Inactive</span>
          @endif
        </td>
        <td>
          <a href="{{ route('services.edit', $service) }}" class="action-btn">
            <i class="bi bi-pencil"></i> Edit
          </a>
          <form action="{{ route('services.toggle-status', $service) }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="action-btn">
              <i class="bi bi-{{ $service->is_active ? 'x-circle' : 'check-circle' }}"></i>
              {{ $service->is_active ? 'Deactivate' : 'Activate' }}
            </button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  @else
  <div class="empty-state">
    <i class="bi bi-inbox"></i>
    <p>No services found. Add your first service to get started.</p>
  </div>
  @endif
</div>

<!-- Pagination -->
@if($services->hasPages())
<div class="mt-4">
  {{ $services->links('vendor.pagination.custom') }}
</div>
@endif
@endsection

@push('scripts')
<script>
  function filterServices(category) {
    const url = new URL(window.location.href);
    if (category) {
      url.searchParams.set('category', category);
    } else {
      url.searchParams.delete('category');
    }
    window.location.href = url.toString();
  }

  function filterStatus(status) {
    const url = new URL(window.location.href);
    if (status) {
      url.searchParams.set('status', status);
    } else {
      url.searchParams.delete('status');
    }
    window.location.href = url.toString();
  }
</script>
@endpush
