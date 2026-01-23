@extends('layouts.app')

@section('title', 'Bill Details - ' . $bill->bill_number)

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('bills.index') }}">Bills</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ $bill->bill_number }}</li>
@endsection

@push('styles')
<style>
  .bill-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 30px;
    border-radius: 12px;
    margin-bottom: 24px;
  }
  .info-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
  }
  .payment-section {
    background: #f0fdf4;
    border: 2px solid #86efac;
    border-radius: 12px;
    padding: 20px;
    margin-top: 20px;
  }
</style>
@endpush

@section('content')
<div class="bill-header">
  <div class="d-flex justify-content-between align-items-start">
    <div>
      <h2 class="mb-2">{{ $bill->bill_number }}</h2>
      <p class="mb-0 opacity-75">{{ $bill->bill_date->format('F d, Y h:i A') }}</p>
    </div>
    <div class="text-end">
      <div class="badge bg-light text-dark mb-2">{{ strtoupper($bill->bill_type) }}</div>
      <div>
        @if($bill->payment_status === 'paid')
          <span class="badge bg-success">Paid</span>
        @elseif($bill->payment_status === 'partial')
          <span class="badge bg-warning">Partially Paid</span>
        @else
          <span class="badge bg-danger">Unpaid</span>
        @endif
      </div>
    </div>
  </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
  {{ session('success') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show">
  {{ session('error') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row">
  <div class="col-md-8">
    <div class="info-card">
      <h5 class="mb-3">Patient Information</h5>
      <div class="row">
        <div class="col-md-6">
          <p><strong>Name:</strong> {{ $bill->patient->first_name }} {{ $bill->patient->last_name }}</p>
          <p><strong>Patient ID:</strong> {{ $bill->patient->patient_id }}</p>
        </div>
        <div class="col-md-6">
          <p><strong>Phone:</strong> {{ $bill->patient->phone }}</p>
          <p><strong>Email:</strong> {{ $bill->patient->email ?? 'N/A' }}</p>
        </div>
      </div>
    </div>

    <div class="info-card">
      <h5 class="mb-3">Bill Items</h5>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>Service</th>
              <th>Qty</th>
              <th>Price</th>
              <th>Discount</th>
              <th>Tax</th>
              <th class="text-end">Total</th>
            </tr>
          </thead>
          <tbody>
            @foreach($bill->billItems as $item)
            <tr>
              <td>
                <strong>{{ $item->service_name }}</strong><br>
                <small class="text-muted">{{ $item->service_code }}</small>
              </td>
              <td>{{ $item->quantity }}</td>
              <td>${{ number_format($item->unit_price, 2) }}</td>
              <td>
                @if($item->discount_amount > 0)
                  ${{ number_format($item->discount_amount, 2) }}
                  <small class="text-muted">({{ $item->discount_percentage }}%)</small>
                @else
                  -
                @endif
              </td>
              <td>
                @if($item->tax_amount > 0)
                  ${{ number_format($item->tax_amount, 2) }}
                @else
                  -
                @endif
              </td>
              <td class="text-end"><strong>${{ number_format($item->total_amount, 2) }}</strong></td>
            </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <td colspan="5" class="text-end"><strong>Subtotal:</strong></td>
              <td class="text-end">${{ number_format($bill->sub_total, 2) }}</td>
            </tr>
            @if($bill->discount_amount > 0)
            <tr>
              <td colspan="5" class="text-end">
                <strong>Discount ({{ $bill->discount_percentage }}%):</strong>
                @if($bill->discount_reason)
                  <br><small class="text-muted">{{ $bill->discount_reason }}</small>
                @endif
              </td>
              <td class="text-end text-danger">-${{ number_format($bill->discount_amount, 2) }}</td>
            </tr>
            @endif
            <tr>
              <td colspan="5" class="text-end"><strong>Tax:</strong></td>
              <td class="text-end">${{ number_format($bill->tax_amount, 2) }}</td>
            </tr>
            <tr class="table-primary">
              <td colspan="5" class="text-end"><h5 class="mb-0">Total Amount:</h5></td>
              <td class="text-end"><h5 class="mb-0">${{ number_format($bill->total_amount, 2) }}</h5></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>

    @if($bill->payments->count() > 0)
    <div class="info-card">
      <h5 class="mb-3">Payment History</h5>
      <div class="table-responsive">
        <table class="table table-sm">
          <thead>
            <tr>
              <th>Date</th>
              <th>Method</th>
              <th>Reference</th>
              <th>Received By</th>
              <th class="text-end">Amount</th>
            </tr>
          </thead>
          <tbody>
            @foreach($bill->payments as $payment)
            <tr>
              <td>{{ $payment->payment_date->format('M d, Y H:i') }}</td>
              <td><span class="badge bg-secondary">{{ strtoupper($payment->payment_method) }}</span></td>
              <td>{{ $payment->payment_reference ?? '-' }}</td>
              <td>{{ $payment->receivedBy->name }}</td>
              <td class="text-end"><strong>${{ number_format($payment->amount, 2) }}</strong></td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @endif
  </div>

  <div class="col-md-4">
    <div class="info-card">
      <h5 class="mb-3">Bill Summary</h5>
      <div class="d-flex justify-content-between mb-2">
        <span>Total Amount:</span>
        <strong>${{ number_format($bill->total_amount, 2) }}</strong>
      </div>
      <div class="d-flex justify-content-between mb-2">
        <span>Paid Amount:</span>
        <strong class="text-success">${{ number_format($bill->paid_amount, 2) }}</strong>
      </div>
      <hr>
      <div class="d-flex justify-content-between">
        <h6>Balance Due:</h6>
        <h5 class="text-danger">${{ number_format($bill->balance_amount, 2) }}</h5>
      </div>
    </div>

    @if($bill->balance_amount > 0)
    <div class="payment-section">
      <h6 class="mb-3">Add Payment</h6>
      <form action="{{ route('bills.add-payment', $bill) }}" method="POST">
        @csrf
        <div class="mb-3">
          <label class="form-label">Amount <span class="text-danger">*</span></label>
          <input type="number" step="0.01" class="form-control" name="amount" 
                 max="{{ $bill->balance_amount }}" value="{{ $bill->balance_amount }}" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Payment Method <span class="text-danger">*</span></label>
          <select class="form-control" name="payment_method" required>
            <option value="cash">Cash</option>
            <option value="card">Card</option>
            <option value="upi">UPI</option>
            <option value="bank_transfer">Bank Transfer</option>
            <option value="cheque">Cheque</option>
            <option value="insurance">Insurance</option>
            <option value="other">Other</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Reference/Transaction ID</label>
          <input type="text" class="form-control" name="payment_reference">
        </div>
        <div class="mb-3">
          <label class="form-label">Notes</label>
          <textarea class="form-control" name="notes" rows="2"></textarea>
        </div>
        <button type="submit" class="btn btn-success w-100">
          <i class="bi bi-cash-coin me-1"></i>Add Payment
        </button>
      </form>
    </div>
    @endif

    <div class="d-grid gap-2 mt-3">
      <a href="{{ route('bills.receipt', $bill) }}" class="btn btn-outline-primary" target="_blank">
        <i class="bi bi-printer me-1"></i>Print Receipt
      </a>
      <a href="{{ route('bills.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back to Bills
      </a>
    </div>
  </div>
</div>
@endsection
