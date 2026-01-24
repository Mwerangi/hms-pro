@extends('layouts.app')

@section('title', 'Accountant Dashboard')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active" aria-current="page">Accounting</li>
@endsection

@push('styles')
<style>
  .page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
  }

  .page-title {
    font-size: 24px;
    font-weight: 600;
    color: #111827;
    margin: 0;
  }

  .page-subtitle {
    font-size: 14px;
    color: #6b7280;
    margin-top: 4px;
  }

  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
    margin-bottom: 28px;
  }

  .stat-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px;
    transition: all 0.2s;
  }

  .stat-card:hover {
    border-color: #d1d5db;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  }

  .stat-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
  }

  .stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
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
  }

  .filter-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 24px;
  }

  .filter-row {
    display: flex;
    gap: 12px;
    align-items: end;
  }

  .form-group {
    flex: 1;
  }

  .form-label {
    display: block;
    font-size: 13px;
    font-weight: 500;
    color: #374151;
    margin-bottom: 6px;
  }

  .form-control {
    width: 100%;
    padding: 9px 12px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    color: #111827;
    transition: all 0.2s;
  }

  .form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
  }

  .btn-primary-custom {
    padding: 9px 16px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 8px;
    color: white;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
  }

  .btn-primary-custom:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    color: white;
  }

  .patients-table-card {
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
    font-size: 13px;
    font-weight: 600;
    color: #6b7280;
    border-bottom: 1px solid #e5e7eb;
  }

  tbody tr {
    border-bottom: 1px solid #f3f4f6;
    transition: background 0.15s;
  }

  tbody tr:hover {
    background: #f9fafb;
  }

  td {
    padding: 16px 20px;
    font-size: 14px;
    color: #374151;
  }

  .patient-info strong {
    color: #111827;
    font-weight: 600;
  }

  .patient-id {
    color: #6b7280;
    font-size: 13px;
  }

  .badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
  }

  .badge-warning {
    background: #fef3c7;
    color: #92400e;
  }

  .amount-pending {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
  }

  .action-btns {
    display: flex;
    gap: 8px;
  }

  .btn-action {
    padding: 6px 12px;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    background: white;
    color: #374151;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }

  .btn-action:hover {
    background: #f9fafb;
    border-color: #d1d5db;
    color: #111827;
  }

  .btn-action.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
  }

  .btn-action.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
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
    <h1 class="page-title">Accountant Dashboard</h1>
    <p class="page-subtitle">Manage patient charges and generate bills</p>
  </div>
</div>

<!-- Statistics -->
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-header">
      <div>
        <div class="stat-value">{{ $stats['patients_with_pending'] }}</div>
        <div class="stat-label">Patients with Pending Charges</div>
      </div>
      <div class="stat-icon" style="background: #fef3c7; color: #f59e0b;">
        <i class="bi bi-people"></i>
      </div>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-header">
      <div>
        <div class="stat-value">TSh {{ number_format($stats['total_pending_amount'], 2) }}</div>
        <div class="stat-label">Total Pending Amount</div>
      </div>
      <div class="stat-icon" style="background: #fee2e2; color: #ef4444;">
        <i class="bi bi-cash-stack"></i>
      </div>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-header">
      <div>
        <div class="stat-value">{{ $stats['pending_charges_today'] }}</div>
        <div class="stat-label">Charges Added Today</div>
      </div>
      <div class="stat-icon" style="background: #e0e7ff; color: #6366f1;">
        <i class="bi bi-plus-circle"></i>
      </div>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-header">
      <div>
        <div class="stat-value">{{ $stats['bills_created_today'] }}</div>
        <div class="stat-label">Bills Created Today</div>
      </div>
      <div class="stat-icon" style="background: #d1fae5; color: #10b981;">
        <i class="bi bi-file-earmark-text"></i>
      </div>
    </div>
  </div>
</div>

<!-- Filters -->
<div class="filter-card">
  <form method="GET" action="{{ route('accounting.dashboard') }}">
    <div class="filter-row">
      <div class="form-group">
        <label class="form-label">Filter by Service Date</label>
        <input type="date" name="date" class="form-control" value="{{ $filterDate }}">
      </div>
      <button type="submit" class="btn-primary-custom">
        <i class="bi bi-funnel"></i>Apply Filter
      </button>
      <a href="{{ route('accounting.dashboard') }}" class="btn-action">
        <i class="bi bi-x-circle"></i>Clear
      </a>
    </div>
  </form>
</div>

<!-- Patients with Pending Charges -->
<div class="patients-table-card">
  <div class="table-header">
    <h5 class="table-title">Patients with Pending Charges</h5>
  </div>

  @if($patientsWithCharges->count() > 0)
  <table>
    <thead>
      <tr>
        <th>Patient</th>
        <th>Pending Charges</th>
        <th>Total Amount</th>
        <th>Last Service Date</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($patientsWithCharges as $patient)
      <tr>
        <td>
          <div class="patient-info">
            <strong>{{ $patient->name }}</strong><br>
            <span class="patient-id">{{ $patient->patient_id }}</span>
          </div>
        </td>
        <td>
          <span class="badge badge-warning">
            {{ $patient->pending_charges_count }} charge(s)
          </span>
        </td>
        <td>
          <span class="amount-pending">TSh {{ number_format($patient->total_pending_amount, 2) }}</span>
        </td>
        <td>
          {{ $patient->charges->max('service_date')?->format('M d, Y h:i A') ?? 'N/A' }}
        </td>
        <td>
          <div class="action-btns">
            <a href="{{ route('accounting.patient-charges', $patient) }}" class="btn-action">
              <i class="bi bi-eye"></i>View Charges
            </a>
            <a href="{{ route('bills.create', ['patient_id' => $patient->id]) }}" class="btn-action btn-primary">
              <i class="bi bi-receipt"></i>Generate Bill
            </a>
          </div>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  @else
  <div class="empty-state">
    <i class="bi bi-inbox"></i>
    <p>No patients with pending charges{{ request('date') ? ' for the selected date' : '' }}.</p>
  </div>
  @endif
</div>
@endsection
