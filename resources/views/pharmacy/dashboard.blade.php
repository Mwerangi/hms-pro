@extends('layouts.app')

@section('title', 'Pharmacy Dashboard')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('pharmacy.dashboard') }}">Pharmacy</a></li>
<li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@push('styles')
<style>
  /* Modern Minimalistic Design */
  .page-header {
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

  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
  }

  .stat-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px;
  }

  .stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: #667eea;
    margin-bottom: 14px;
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

  .section-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
  }

  .section-title {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .section-title i {
    color: #667eea;
    font-size: 20px;
  }

  .section-title .badge {
    background: #f3f4f6;
    color: #667eea;
    font-size: 12px;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 10px;
  }

  table {
    width: 100%;
  }

  thead th {
    background: #f9fafb;
    padding: 12px;
    font-weight: 600;
    font-size: 12px;
    color: #6b7280;
    border-bottom: 1px solid #e5e7eb;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  tbody td {
    padding: 16px 12px;
    border-bottom: 1px solid #f3f4f6;
    font-size: 14px;
    vertical-align: middle;
    color: #374151;
  }

  tbody tr:hover {
    background: #f9fafb;
  }

  .empty-state {
    text-align: center;
    padding: 48px 20px;
    color: #9ca3af;
  }

  .empty-state i {
    font-size: 48px;
    color: #d1d5db;
    margin-bottom: 12px;
  }

  .empty-state p {
    font-size: 14px;
    margin: 0;
  }

  .badge {
    font-size: 11px;
    padding: 4px 8px;
    font-weight: 600;
  }

  .bg-purple { background: #e0e7ff !important; color: #3730a3 !important; }
  .bg-warning { background: #fef3c7 !important; color: #92400e !important; }
  .bg-success { background: #d1fae5 !important; color: #065f46 !important; }
  .bg-info { background: #dbeafe !important; color: #1e40af !important; }
  .btn-sm { padding: 6px 12px; font-size: 13px; }
</style>
@endpush

@section('content')
<div class="container-fluid">
  <!-- Page Header -->
  <div class="page-header d-flex justify-content-between align-items-center">
    <div>
      <h1 class="page-title">Pharmacy Dashboard</h1>
      <p class="page-subtitle">Manage prescriptions and medicine dispensing</p>
    </div>
    <div>
      <form method="GET" action="{{ route('pharmacy.dashboard') }}" class="d-flex gap-2">
        <input type="date" 
               name="date" 
               class="form-control" 
               value="{{ $filterDate }}"
               onchange="this.form.submit()">
      </form>
    </div>
  </div>

  <!-- Statistics -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon">
        <i class="bi bi-clock-history"></i>
      </div>
      <div class="stat-value">{{ $stats['pending_today'] }}</div>
      <div class="stat-label">Pending Today</div>
    </div>
    <div class="stat-card">
      <div class="stat-icon">
        <i class="bi bi-check-circle"></i>
      </div>
      <div class="stat-value">{{ $stats['dispensed_today'] }}</div>
      <div class="stat-label">Dispensed Today</div>
    </div>
    <div class="stat-card">
      <div class="stat-icon">
        <i class="bi bi-file-medical"></i>
      </div>
      <div class="stat-value">{{ $stats['total_prescriptions'] }}</div>
      <div class="stat-label">Total Prescriptions</div>
    </div>
  </div>

  <!-- Pending Prescriptions -->
  <div class="section-card">
    <h5 class="section-title">
      <i class="bi bi-clock-history"></i>
      Pending Prescriptions
      <span class="badge">{{ $pendingPrescriptions->count() }}</span>
    </h5>
    @if($pendingPrescriptions->isEmpty())
      <div class="empty-state">
        <i class="bi bi-inbox"></i>
        <p>No pending prescriptions for {{ \Carbon\Carbon::parse($filterDate)->format('M d, Y') }}</p>
      </div>
    @else
      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>Rx Number</th>
              <th>Patient</th>
              <th>Doctor</th>
              <th>Date</th>
              <th>Items</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
                            @foreach($pendingPrescriptions as $prescription)
                            <tr>
                                <td>
                                    <span class="badge bg-purple">{{ $prescription->prescription_number }}</span>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $prescription->patient->full_name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $prescription->patient->patient_id }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        Dr. {{ $prescription->doctor->name }}
                                        @if($prescription->doctor->specialization)
                                        <br>
                                        <small class="text-muted">{{ $prescription->doctor->specialization }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <small>{{ $prescription->prescription_date->format('M d, Y') }}</small>
                                    <br>
                                    <small class="text-muted">{{ $prescription->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $prescription->items->count() }} items</span>
                                </td>
                                <td>
                                    @if($prescription->status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($prescription->status === 'partially-dispensed')
                                        <span class="badge bg-info">Partially Dispensed</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('pharmacy.show', $prescription->id) }}" 
                                           class="btn btn-outline-primary">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
  </div>

  <!-- Dispensed Prescriptions -->
  <div class="section-card">
    <h5 class="section-title">
      <i class="bi bi-check-circle"></i>
      Dispensed Prescriptions ({{ \Carbon\Carbon::parse($filterDate)->format('M d, Y') }})
      <span class="badge">{{ $dispensedPrescriptions->count() }}</span>
    </h5>
    @if($dispensedPrescriptions->isEmpty())
      <div class="empty-state">
        <i class="bi bi-inbox"></i>
        <p>No prescriptions dispensed on {{ \Carbon\Carbon::parse($filterDate)->format('M d, Y') }}</p>
      </div>
    @else
      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>Rx Number</th>
              <th>Patient</th>
              <th>Doctor</th>
              <th>Items</th>
              <th>Dispensed At</th>
              <th>Dispensed By</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
                            @foreach($dispensedPrescriptions as $prescription)
                            <tr>
                                <td>
                                    <span class="badge bg-purple">{{ $prescription->prescription_number }}</span>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $prescription->patient->full_name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $prescription->patient->patient_id }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        Dr. {{ $prescription->doctor->name }}
                                        @if($prescription->doctor->specialization)
                                        <br>
                                        <small class="text-muted">{{ $prescription->doctor->specialization }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $prescription->items->count() }} items</span>
                                </td>
                                <td>
                                    <small>{{ $prescription->dispensed_at->format('M d, Y') }}</small>
                                    <br>
                                    <small class="text-muted">{{ $prescription->dispensed_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    @if($prescription->dispensedByUser)
                                        <small>{{ $prescription->dispensedByUser->name }}</small>
                                    @else
                                        <small class="text-muted">-</small>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('pharmacy.show', $prescription->id) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>
</div>
@endsection
