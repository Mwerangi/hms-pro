@extends('layouts.app')

@section('title', 'IPD Dashboard')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('ipd.dashboard') }}">IPD</a></li>
<li class="breadcrumb-item active" aria-current="page">Dashboard</li>
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

  .stat-change {
    font-size: 12px;
    margin-top: 8px;
  }

  .stat-change.positive {
    color: #10b981;
  }

  .stat-change.negative {
    color: #ef4444;
  }

  /* Ward Cards */
  .ward-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
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
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 4px;
  }

  .ward-type {
    font-size: 12px;
    padding: 3px 8px;
    border-radius: 4px;
    font-weight: 500;
  }

  .ward-info {
    display: flex;
    gap: 16px;
    margin-bottom: 12px;
    font-size: 13px;
    color: #6b7280;
  }

  .occupancy-bar {
    height: 8px;
    background: #f3f4f6;
    border-radius: 4px;
    overflow: hidden;
    margin-bottom: 8px;
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
  }

  .table-title {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin: 0;
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
  }

  .btn-primary-custom:hover {
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    transform: translateY(-1px);
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
    <h1 class="page-title">Inpatient Department (IPD)</h1>
    <p class="page-subtitle">Manage beds, wards, and patient admissions</p>
  </div>
  <div>
    <a href="{{ route('admissions.create') }}" class="btn-primary-custom me-2">
      <i class="bi bi-person-plus me-2"></i>New Admission
    </a>
    <a href="{{ route('wards.index') }}" class="action-btn">
      <i class="bi bi-hospital me-1"></i>Manage Wards
    </a>
  </div>
</div>

<!-- Statistics -->
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon" style="background: #ede9fe; color: #7c3aed;">
      <i class="bi bi-hospital"></i>
    </div>
    <div class="stat-value">{{ $stats['total_wards'] }}</div>
    <div class="stat-label">Active Wards</div>
  </div>

  <div class="stat-card">
    <div class="stat-icon" style="background: #dbeafe; color: #2563eb;">
      <i class="bi bi-grid-3x2"></i>
    </div>
    <div class="stat-value">{{ $stats['total_beds'] }}</div>
    <div class="stat-label">Total Beds</div>
  </div>

  <div class="stat-card">
    <div class="stat-icon" style="background: #d1fae5; color: #059669;">
      <i class="bi bi-check-circle"></i>
    </div>
    <div class="stat-value">{{ $stats['available_beds'] }}</div>
    <div class="stat-label">Available Beds</div>
  </div>

  <div class="stat-card">
    <div class="stat-icon" style="background: #fed7aa; color: #c2410c;">
      <i class="bi bi-person-fill"></i>
    </div>
    <div class="stat-value">{{ $stats['occupied_beds'] }}</div>
    <div class="stat-label">Occupied Beds</div>
  </div>

  <div class="stat-card">
    <div class="stat-icon" style="background: #fce7f3; color: #be123c;">
      <i class="bi bi-activity"></i>
    </div>
    <div class="stat-value">{{ $stats['occupancy_rate'] }}%</div>
    <div class="stat-label">Occupancy Rate</div>
  </div>

  <div class="stat-card">
    <div class="stat-icon" style="background: #cffafe; color: #0e7490;">
      <i class="bi bi-people"></i>
    </div>
    <div class="stat-value">{{ $stats['current_patients'] }}</div>
    <div class="stat-label">Current Patients</div>
  </div>

  <div class="stat-card">
    <div class="stat-icon" style="background: #e0e7ff; color: #4338ca;">
      <i class="bi bi-arrow-down-circle"></i>
    </div>
    <div class="stat-value">{{ $stats['admissions_today'] }}</div>
    <div class="stat-label">Admissions Today</div>
  </div>

  <div class="stat-card">
    <div class="stat-icon" style="background: #fef3c7; color: #a16207;">
      <i class="bi bi-arrow-up-circle"></i>
    </div>
    <div class="stat-value">{{ $stats['discharges_today'] }}</div>
    <div class="stat-label">Discharges Today</div>
  </div>
</div>

<!-- Ward Status -->
<h3 class="mb-3" style="font-size: 18px; font-weight: 600; color: #111827;">Ward Status</h3>
<div class="ward-grid">
  @forelse($wards as $ward)
  <div class="ward-card">
    <div class="ward-header">
      <div>
        <div class="ward-name">{{ $ward->ward_name }}</div>
        <span class="ward-type badge-{{ $ward->ward_type === 'icu' ? 'danger' : ($ward->ward_type === 'private' ? 'purple' : 'info') }}">
          {{ ucfirst(str_replace('-', ' ', $ward->ward_type)) }}
        </span>
      </div>
      <a href="{{ route('wards.show', $ward) }}" class="action-btn" style="padding: 6px 10px;">
        <i class="bi bi-arrow-right"></i>
      </a>
    </div>

    <div class="ward-info">
      <span><i class="bi bi-geo-alt me-1"></i>{{ $ward->floor ? 'Floor ' . $ward->floor : 'N/A' }}</span>
      <span><i class="bi bi-building me-1"></i>{{ $ward->building ?? 'Main' }}</span>
    </div>

    <div class="occupancy-bar">
      <div class="occupancy-fill" style="width: {{ $ward->occupancy_rate }}%"></div>
    </div>

    <div class="occupancy-label">
      {{ $ward->occupied_beds }}/{{ $ward->total_beds }} Beds ({{ $ward->occupancy_rate }}%)
    </div>
  </div>
  @empty
  <div class="empty-state" style="grid-column: 1 / -1;">
    <i class="bi bi-hospital"></i>
    <p>No wards configured yet. Create wards to start managing beds.</p>
  </div>
  @endforelse
</div>

<!-- Recent Admissions -->
<div class="modern-table">
  <div class="table-header">
    <h3 class="table-title">Current Admitted Patients</h3>
  </div>
  <table>
    <thead>
      <tr>
        <th>Admission #</th>
        <th>Patient</th>
        <th>Ward / Bed</th>
        <th>Doctor</th>
        <th>Admitted</th>
        <th>Type</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($recentAdmissions as $admission)
      <tr>
        <td><strong>{{ $admission->admission_number }}</strong></td>
        <td>
          <div style="font-weight: 500;">{{ $admission->patient->full_name }}</div>
          <div style="font-size: 12px; color: #6b7280;">{{ $admission->patient->patient_id }}</div>
        </td>
        <td>
          <div>{{ $admission->ward->ward_name }}</div>
          @if($admission->bed)
          <div style="font-size: 12px; color: #6b7280;">Bed: {{ $admission->bed->bed_number }}</div>
          @endif
        </td>
        <td>{{ $admission->doctor->name }}</td>
        <td>{{ $admission->admission_date->format('M d, Y') }}</td>
        <td>
          <span class="badge badge-{{ $admission->admission_type === 'emergency' ? 'danger' : 'info' }}">
            {{ ucfirst($admission->admission_type) }}
          </span>
        </td>
        <td>
          <span class="badge badge-success">Admitted</span>
        </td>
        <td>
          <a href="{{ route('admissions.show', $admission) }}" class="action-btn">View</a>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="8">
          <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <p>No patients currently admitted</p>
          </div>
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
