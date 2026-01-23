@extends('layouts.app')

@section('title', 'Bills')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active" aria-current="page">Bills</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 style="font-size: 24px; font-weight: 600; margin: 0;">Bills & Invoices</h1>
  <a href="{{ route('bills.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-circle me-1"></i>Create New Bill
  </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
  {{ session('success') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card mb-3">
  <div class="card-body">
    <form action="{{ route('bills.index') }}" method="GET" class="row g-3">
      <div class="col-md-4">
        <input type="text" name="search" class="form-control" placeholder="Search by bill number or patient..." value="{{ request('search') }}">
      </div>
      <div class="col-md-3">
        <select name="bill_type" class="form-control">
          <option value="">All Types</option>
          <option value="opd" {{ request('bill_type') === 'opd' ? 'selected' : '' }}>OPD</option>
          <option value="ipd" {{ request('bill_type') === 'ipd' ? 'selected' : '' }}>IPD</option>
          <option value="emergency" {{ request('bill_type') === 'emergency' ? 'selected' : '' }}>Emergency</option>
          <option value="pharmacy" {{ request('bill_type') === 'pharmacy' ? 'selected' : '' }}>Pharmacy</option>
          <option value="laboratory" {{ request('bill_type') === 'laboratory' ? 'selected' : '' }}>Laboratory</option>
        </select>
      </div>
      <div class="col-md-3">
        <select name="payment_status" class="form-control">
          <option value="">All Status</option>
          <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
          <option value="partial" {{ request('payment_status') === 'partial' ? 'selected' : '' }}>Partial</option>
          <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
        </select>
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Filter</button>
      </div>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>Bill #</th>
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
          @forelse($bills as $bill)
          <tr>
            <td><strong>{{ $bill->bill_number }}</strong></td>
            <td>{{ $bill->bill_date->format('M d, Y') }}</td>
            <td>{{ $bill->patient->first_name }} {{ $bill->patient->last_name }}</td>
            <td><span class="badge bg-info">{{ strtoupper($bill->bill_type) }}</span></td>
            <td>${{ number_format($bill->total_amount, 2) }}</td>
            <td>${{ number_format($bill->paid_amount, 2) }}</td>
            <td><strong>${{ number_format($bill->balance_amount, 2) }}</strong></td>
            <td>
              @if($bill->payment_status === 'paid')
                <span class="badge bg-success">Paid</span>
              @elseif($bill->payment_status === 'partial')
                <span class="badge bg-warning">Partial</span>
              @else
                <span class="badge bg-danger">Unpaid</span>
              @endif
            </td>
            <td>
              <div class="btn-group btn-group-sm">
                <a href="{{ route('bills.show', $bill) }}" class="btn btn-outline-primary">
                  <i class="bi bi-eye"></i>
                </a>
                <a href="{{ route('bills.receipt', $bill) }}" class="btn btn-outline-secondary" target="_blank">
                  <i class="bi bi-printer"></i>
                </a>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="9" class="text-center text-muted py-4">No bills found</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    
    <div class="mt-3">
      {{ $bills->links() }}
    </div>
  </div>
</div>
@endsection
