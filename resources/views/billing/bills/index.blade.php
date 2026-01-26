@extends('layouts.app')

@section('title', 'Bills')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('services.index') }}">Accounting</a></li>
<li class="breadcrumb-item active" aria-current="page">Bills</li>
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

  /* Filter Card */
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
    align-items: center;
  }

  .filter-row input[type="text"],
  .filter-row select {
    flex: 1;
  }

  .filter-row button,
  .filter-row a.btn-clear {
    flex-shrink: 0;
  }

  .filter-input {
    padding: 10px 14px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    color: #374151;
  }

  .filter-input:focus {
    outline: none;
    border-color: #667eea;
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

  .btn-clear {
    background: white;
    color: #6b7280;
    border: 1px solid #e5e7eb;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-block;
  }

  .btn-clear:hover {
    background: #f9fafb;
    border-color: #d1d5db;
    color: #374151;
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
    <h1 class="page-title">Bills & Invoices</h1>
    <p class="page-subtitle">Manage patient billing and payments</p>
  </div>
  <div>
    <a href="{{ route('bills.create') }}" class="btn-primary-custom">
      <i class="bi bi-plus-circle me-2"></i>Create New Bill
    </a>
  </div>
</div>

<!-- Statistics -->
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon" style="background: #ede9fe; color: #7c3aed;">
      <i class="bi bi-receipt"></i>
    </div>
    <div class="stat-value">{{ $bills->total() }}</div>
    <div class="stat-label">Total Bills</div>
  </div>

  <div class="stat-card">
    <div class="stat-icon" style="background: #d1fae5; color: #059669;">
      <i class="bi bi-check-circle"></i>
    </div>
    <div class="stat-value">{{ $bills->filter(fn($b) => $b->payment_status === 'paid')->count() }}</div>
    <div class="stat-label">Paid Bills</div>
  </div>

  <div class="stat-card">
    <div class="stat-icon" style="background: #fee2e2; color: #dc2626;">
      <i class="bi bi-exclamation-circle"></i>
    </div>
    <div class="stat-value">{{ $bills->filter(fn($b) => $b->payment_status === 'unpaid')->count() }}</div>
    <div class="stat-label">Unpaid Bills</div>
  </div>

  <div class="stat-card">
    <div class="stat-icon" style="background: #fef3c7; color: #d97706;">
      <i class="bi bi-hourglass-split"></i>
    </div>
    <div class="stat-value">{{ $bills->filter(fn($b) => $b->payment_status === 'partial')->count() }}</div>
    <div class="stat-label">Partial Payments</div>
  </div>
</div>

<!-- Filters -->
<div class="filter-card">
  <form action="{{ route('bills.index') }}" method="GET">
    <div class="filter-row">
      <input type="text" name="search" class="filter-input" placeholder="Search by bill number or patient..." value="{{ request('search') }}">
      <select name="bill_type" class="filter-input">
        <option value="">All Types</option>
        <option value="opd" {{ request('bill_type') === 'opd' ? 'selected' : '' }}>OPD</option>
        <option value="ipd" {{ request('bill_type') === 'ipd' ? 'selected' : '' }}>IPD</option>
        <option value="emergency" {{ request('bill_type') === 'emergency' ? 'selected' : '' }}>Emergency</option>
        <option value="pharmacy" {{ request('bill_type') === 'pharmacy' ? 'selected' : '' }}>Pharmacy</option>
        <option value="laboratory" {{ request('bill_type') === 'laboratory' ? 'selected' : '' }}>Laboratory</option>
      </select>
      <select name="payment_status" class="filter-input">
        <option value="">All Status</option>
        <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
        <option value="partial" {{ request('payment_status') === 'partial' ? 'selected' : '' }}>Partial</option>
        <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
      </select>
      <button type="submit" class="btn-primary-custom">
        <i class="bi bi-funnel me-1"></i>Filter
      </button>
      @if(request()->hasAny(['search', 'bill_type', 'payment_status']))
      <a href="{{ route('bills.index') }}" class="btn-clear">
        <i class="bi bi-x-circle me-1"></i>Clear
      </a>
      @endif
    </div>
  </form>
</div>

<!-- Bills Table -->
<div class="modern-table">
  <div class="table-header">
    <h3 class="table-title">All Bills</h3>
  </div>

  @if($bills->count() > 0)
  <table>
    <thead>
      <tr>
        <th>Bill Number</th>
        <th>Date</th>
        <th>Patient</th>
        <th>Type</th>
        <th>Amount</th>
        <th>Paid</th>
        <th>Balance</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($bills as $bill)
      <tr>
        <td><strong>{{ $bill->bill_number }}</strong></td>
        <td>{{ $bill->bill_date->format('M d, Y') }}</td>
        <td>{{ $bill->patient->first_name }} {{ $bill->patient->last_name }}</td>
        <td>
          <span class="badge badge-info">{{ strtoupper($bill->bill_type) }}</span>
        </td>
        <td>TSh {{ number_format($bill->total_amount, 2) }}</td>
        <td>TSh {{ number_format($bill->paid_amount, 2) }}</td>
        <td><strong>TSh {{ number_format($bill->balance_amount, 2) }}</strong></td>
        <td>
          @if($bill->payment_status === 'paid')
            <span class="badge badge-success">Paid</span>
          @elseif($bill->payment_status === 'partial')
            <span class="badge badge-warning">Partial</span>
          @else
            <span class="badge badge-danger">Unpaid</span>
          @endif
        </td>
        <td>
          <a href="{{ route('bills.show', $bill) }}" class="action-btn" title="View Details">
            <i class="bi bi-eye"></i> View
          </a>
          <a href="{{ route('bills.receipt', $bill) }}" class="action-btn" target="_blank" title="Print Receipt">
            <i class="bi bi-printer"></i> Print
          </a>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  @else
  <div class="empty-state">
    <i class="bi bi-inbox"></i>
    <p>No bills found. Create your first bill to get started.</p>
  </div>
  @endif
</div>

<!-- Pagination -->
@if($bills->hasPages())
<div class="mt-4">
  {{ $bills->links('vendor.pagination.custom') }}
</div>
@endif
@endsection
