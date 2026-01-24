@extends('layouts.app')

@section('title', 'Patient Charges - ' . $patient->name)

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('accounting.dashboard') }}">Accounting</a></li>
<li class="breadcrumb-item active" aria-current="page">Patient Charges</li>
@endsection

@push('styles')
<style>
  .patient-header {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
  }

  .patient-header-content {
    display: flex;
    justify-content: space-between;
    align-items: start;
  }

  .patient-name {
    font-size: 22px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 8px;
  }

  .patient-id {
    font-size: 14px;
    color: #6b7280;
  }

  .total-amount {
    text-align: right;
  }

  .total-label {
    font-size: 13px;
    color: #6b7280;
    margin-bottom: 4px;
  }

  .total-value {
    font-size: 32px;
    font-weight: 700;
    color: #111827;
  }

  .charges-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 24px;
  }

  .card-header {
    padding: 20px;
    border-bottom: 1px solid #e5e7eb;
    background: #f9fafb;
  }

  .card-title {
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

  td {
    padding: 16px 20px;
    font-size: 14px;
    color: #374151;
    border-bottom: 1px solid #f3f4f6;
  }

  .service-name {
    font-weight: 600;
    color: #111827;
  }

  .service-source {
    font-size: 12px;
    color: #6b7280;
    margin-top: 4px;
  }

  .badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
  }

  .badge-info {
    background: #e0e7ff;
    color: #3730a3;
  }

  .action-buttons {
    display: flex;
    gap: 12px;
    padding: 20px;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
  }

  .btn {
    padding: 12px 24px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
    border: none;
  }

  .btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
  }

  .btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
  }

  .btn-secondary {
    background: white;
    border: 1px solid #e5e7eb;
    color: #374151;
  }

  .btn-secondary:hover {
    background: #f9fafb;
    border-color: #d1d5db;
  }
</style>
@endpush

@section('content')
<div class="patient-header">
  <div class="patient-header-content">
    <div>
      <div class="patient-name">{{ $patient->name }}</div>
      <div class="patient-id">Patient ID: {{ $patient->patient_id }}</div>
    </div>
    <div class="total-amount">
      <div class="total-label">Total Pending Amount</div>
      <div class="total-value">TSh {{ number_format($totalAmount, 2) }}</div>
    </div>
  </div>
</div>

<div class="charges-card">
  <div class="card-header">
    <h5 class="card-title">Pending Charges ({{ $charges->count() }})</h5>
  </div>

  <table>
    <thead>
      <tr>
        <th>Service</th>
        <th>Source</th>
        <th>Qty</th>
        <th>Unit Price</th>
        <th>Discount</th>
        <th>Tax</th>
        <th>Total</th>
        <th>Date</th>
      </tr>
    </thead>
    <tbody>
      @foreach($charges as $charge)
      <tr>
        <td>
          <div class="service-name">
            {{ $charge->service ? $charge->service->service_name : 'Medication' }}
          </div>
          @if($charge->notes)
          <div class="service-source">{{ $charge->notes }}</div>
          @endif
        </td>
        <td>
          <span class="badge badge-info">
            {{ class_basename($charge->source_type ?? 'Manual') }}
          </span>
        </td>
        <td>{{ $charge->quantity }}</td>
        <td>TSh {{ number_format($charge->unit_price, 2) }}</td>
        <td>
          @if($charge->discount_amount > 0)
            TSh {{ number_format($charge->discount_amount, 2) }}
            @if($charge->discount_percentage > 0)
              ({{ $charge->discount_percentage }}%)
            @endif
          @else
            -
          @endif
        </td>
        <td>
          @if($charge->taxable)
            TSh {{ number_format($charge->tax_amount, 2) }} ({{ $charge->tax_percentage }}%)
          @else
            -
          @endif
        </td>
        <td><strong>TSh {{ number_format($charge->total_amount, 2) }}</strong></td>
        <td>{{ $charge->service_date->format('M d, Y h:i A') }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

<div class="action-buttons">
  <form action="{{ route('bills.generate-from-charges', $patient) }}" method="POST" style="display: inline;">
    @csrf
    <input type="hidden" name="bill_type" value="opd">
    <button type="submit" class="btn btn-primary">
      <i class="bi bi-receipt"></i>Generate Bill from These Charges
    </button>
  </form>
  <a href="{{ route('bills.create', ['patient_id' => $patient->id]) }}" class="btn btn-secondary">
    <i class="bi bi-plus-circle"></i>Create Custom Bill
  </a>
  <a href="{{ route('accounting.dashboard') }}" class="btn btn-secondary">
    <i class="bi bi-arrow-left"></i>Back to Dashboard
  </a>
</div>
@endsection
