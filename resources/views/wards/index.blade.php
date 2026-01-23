@extends('layouts.app')

@section('title', 'Ward Management')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('ipd.dashboard') }}">IPD</a></li>
<li class="breadcrumb-item active" aria-current="page">Wards</li>
@endsection

@push('styles')
<style>
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
    margin: 0;
  }

  .ward-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 20px;
  }

  .ward-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px;
    transition: all 0.2s ease;
  }

  .ward-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transform: translateY(-2px);
  }

  .ward-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 16px;
  }

  .ward-name {
    font-size: 18px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 4px;
  }

  .ward-number {
    font-size: 12px;
    color: #6b7280;
    font-family: monospace;
  }

  .ward-type {
    font-size: 12px;
    padding: 4px 10px;
    border-radius: 6px;
    font-weight: 500;
  }

  .ward-info {
    margin-bottom: 16px;
  }

  .info-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    font-size: 13px;
    border-bottom: 1px solid #f3f4f6;
  }

  .info-item:last-child {
    border-bottom: none;
  }

  .info-label {
    color: #6b7280;
  }

  .info-value {
    font-weight: 500;
    color: #111827;
  }

  .occupancy-bar {
    height: 8px;
    background: #f3f4f6;
    border-radius: 4px;
    overflow: hidden;
    margin: 12px 0 8px;
  }

  .occupancy-fill {
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    transition: width 0.3s ease;
  }

  .occupancy-label {
    font-size: 12px;
    color: #6b7280;
    text-align: right;
  }

  .ward-actions {
    display: flex;
    gap: 8px;
    margin-top: 16px;
  }

  .btn-action {
    flex: 1;
    padding: 8px 12px;
    font-size: 13px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    background: white;
    color: #374151;
    text-align: center;
    text-decoration: none;
    transition: all 0.2s ease;
  }

  .btn-action:hover {
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
    text-decoration: none;
    display: inline-block;
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
  .badge-secondary { background: #f3f4f6; color: #6b7280; }

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

  .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 11px;
    padding: 3px 8px;
    border-radius: 4px;
  }

  .status-badge.active {
    background: #d1fae5;
    color: #065f46;
  }

  .status-badge.inactive {
    background: #f3f4f6;
    color: #6b7280;
  }
</style>
@endpush

@section('content')
<div class="page-header">
  <div>
    <h1 class="page-title">Ward Management</h1>
    <p style="color: #6b7280; font-size: 14px; margin: 4px 0 0 0;">Manage hospital wards and bed allocation</p>
  </div>
  <div>
    <a href="{{ route('wards.create') }}" class="btn-primary-custom">
      <i class="bi bi-plus-circle me-2"></i>Create Ward
    </a>
  </div>
</div>

@if($wards->count() > 0)
<div class="ward-grid">
  @foreach($wards as $ward)
  <div class="ward-card">
    <div class="ward-header">
      <div>
        <div class="ward-name">{{ $ward->ward_name }}</div>
        <div class="ward-number">{{ $ward->ward_number }}</div>
      </div>
      <div>
        <span class="badge badge-{{ $ward->ward_type === 'icu' ? 'danger' : ($ward->ward_type === 'private' ? 'purple' : ($ward->ward_type === 'nicu' ? 'warning' : 'info')) }}">
          {{ ucfirst(str_replace('-', ' ', $ward->ward_type)) }}
        </span>
      </div>
    </div>

    <div class="ward-info">
      <div class="info-item">
        <span class="info-label"><i class="bi bi-geo-alt me-1"></i>Location</span>
        <span class="info-value">{{ $ward->building ?? 'Main' }} - Floor {{ $ward->floor ?? 'N/A' }}</span>
      </div>
      <div class="info-item">
        <span class="info-label"><i class="bi bi-person-badge me-1"></i>Nurse</span>
        <span class="info-value">{{ $ward->nurse ? $ward->nurse->name : 'Not assigned' }}</span>
      </div>
      <div class="info-item">
        <span class="info-label"><i class="bi bi-currency-dollar me-1"></i>Base Charge</span>
        <span class="info-value">${{ number_format($ward->base_charge_per_day, 2) }}/day</span>
      </div>
      <div class="info-item">
        <span class="info-label"><i class="bi bi-grid-3x2 me-1"></i>Total Beds</span>
        <span class="info-value">{{ $ward->total_beds }}</span>
      </div>
    </div>

    <div class="occupancy-bar">
      <div class="occupancy-fill" style="width: {{ $ward->occupancy_rate }}%"></div>
    </div>
    <div class="occupancy-label">
      <strong>{{ $ward->occupied_beds }}</strong> occupied / 
      <strong>{{ $ward->available_beds }}</strong> available 
      ({{ $ward->occupancy_rate }}%)
    </div>

    <div style="margin-top: 12px;">
      <span class="status-badge {{ $ward->is_active ? 'active' : 'inactive' }}">
        <i class="bi bi-circle-fill" style="font-size: 6px;"></i>
        {{ $ward->is_active ? 'Active' : 'Inactive' }}
      </span>
    </div>

    <div class="ward-actions">
      <a href="{{ route('wards.show', $ward) }}" class="btn-action">
        <i class="bi bi-eye me-1"></i>View
      </a>
      <a href="{{ route('wards.edit', $ward) }}" class="btn-action">
        <i class="bi bi-pencil me-1"></i>Edit
      </a>
    </div>
  </div>
  @endforeach
</div>
@else
<div class="empty-state">
  <i class="bi bi-hospital"></i>
  <p style="font-size: 16px; margin-bottom: 8px;">No wards created yet</p>
  <p style="font-size: 14px; margin-bottom: 20px;">Start by creating your first ward to manage hospital beds</p>
  <a href="{{ route('wards.create') }}" class="btn-primary-custom">
    <i class="bi bi-plus-circle me-2"></i>Create First Ward
  </a>
</div>
@endif
@endsection
