@extends('layouts.app')

@section('title', 'Bill Details - ' . $bill->bill_number)

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('services.index') }}">Accounting</a></li>
<li class="breadcrumb-item"><a href="{{ route('bills.index') }}">Bills</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ $bill->bill_number }}</li>
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

  .bill-header {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
  }

  .bill-number {
    font-size: 20px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 4px;
  }

  .bill-date {
    color: #6b7280;
    font-size: 14px;
  }

  .info-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 20px;
  }

  .card-title {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 16px;
  }

  .info-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    font-size: 14px;
  }

  .info-label {
    color: #6b7280;
    font-weight: 500;
  }

  .info-value {
    color: #111827;
    font-weight: 500;
  }

  table {
    width: 100%;
    border-collapse: collapse;
  }

  thead {
    background: #f9fafb;
  }

  th {
    padding: 12px 16px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  td {
    padding: 16px;
    font-size: 14px;
    color: #374151;
    border-top: 1px solid #f3f4f6;
  }

  tbody tr:hover {
    background: #f9fafb;
  }

  tfoot td {
    border-top: 2px solid #e5e7eb;
    font-weight: 500;
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
  .badge-secondary { background: #f3f4f6; color: #374151; }

  .summary-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 20px;
  }

  .summary-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    font-size: 14px;
    color: #6b7280;
  }

  .summary-row strong {
    color: #111827;
  }

  .summary-divider {
    border-top: 1px solid #e5e7eb;
    margin: 12px 0;
  }

  .summary-total {
    border-top: 2px solid #e5e7eb;
    padding-top: 12px;
    margin-top: 12px;
  }

  .summary-total .summary-row {
    font-size: 16px;
    font-weight: 600;
  }

  .payment-form {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
  }

  .form-label {
    font-size: 13px;
    font-weight: 500;
    color: #374151;
    margin-bottom: 6px;
  }

  .form-control, .form-select {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 10px 14px;
    font-size: 14px;
    color: #111827;
  }

  .form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    outline: none;
  }

  .btn-primary-custom {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    width: 100%;
    cursor: pointer;
    transition: all 0.2s;
  }

  .btn-primary-custom:hover {
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    transform: translateY(-1px);
    color: white;
  }

  .btn-outline-custom {
    background: white;
    color: #6b7280;
    border: 1px solid #e5e7eb;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    width: 100%;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    display: block;
    text-align: center;
  }

  .btn-outline-custom:hover {
    background: #f9fafb;
    border-color: #d1d5db;
    color: #374151;
  }
</style>
@endpush

@section('content')
<div class="page-header">
  <div>
    <h1 class="page-title">Bill Details</h1>
    <p class="page-subtitle">View and manage bill information</p>
  </div>
</div>

<div class="bill-header">
  <div class="d-flex justify-content-between align-items-start">
    <div>
      <div class="bill-number">{{ $bill->bill_number }}</div>
      <div class="bill-date">{{ $bill->bill_date->format('F d, Y h:i A') }}</div>
    </div>
    <div class="text-end">
      <div class="mb-2">
        <span class="badge badge-info">{{ strtoupper($bill->bill_type) }}</span>
      </div>
      <div>
        @if($bill->payment_status === 'paid')
          <span class="badge badge-success">Paid</span>
        @elseif($bill->payment_status === 'partial')
          <span class="badge badge-warning">Partially Paid</span>
        @else
          <span class="badge badge-danger">Unpaid</span>
        @endif
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-8">
    <div class="info-card">
      <h5 class="card-title">Patient Information</h5>
      <div class="row">
        <div class="col-md-6">
          <div class="info-row">
            <span class="info-label">Name:</span>
            <span class="info-value">{{ $bill->patient->first_name }} {{ $bill->patient->last_name }}</span>
          </div>
          <div class="info-row">
            <span class="info-label">Patient ID:</span>
            <span class="info-value">{{ $bill->patient->patient_id }}</span>
          </div>
        </div>
        <div class="col-md-6">
          <div class="info-row">
            <span class="info-label">Phone:</span>
            <span class="info-value">{{ $bill->patient->phone }}</span>
          </div>
          <div class="info-row">
            <span class="info-label">Email:</span>
            <span class="info-value">{{ $bill->patient->email ?? 'N/A' }}</span>
          </div>
        </div>
      </div>
    </div>

    <div class="info-card">
      <h5 class="card-title">Bill Items</h5>
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
              <td>TSh {{ number_format($item->unit_price, 2) }}</td>
              <td>
                @if($item->discount_amount > 0)
                  TSh {{ number_format($item->discount_amount, 2) }}
                  <small class="text-muted">({{ $item->discount_percentage }}%)</small>
                @else
                  -
                @endif
              </td>
              <td>
                @if($item->tax_amount > 0)
                  TSh {{ number_format($item->tax_amount, 2) }}
                @else
                  -
                @endif
              </td>
              <td class="text-end"><strong>TSh {{ number_format($item->total_amount, 2) }}</strong></td>
            </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <td colspan="5" class="text-end"><strong>Subtotal:</strong></td>
              <td class="text-end">TSh {{ number_format($bill->sub_total, 2) }}</td>
            </tr>
            @if($bill->discount_amount > 0)
            <tr>
              <td colspan="5" class="text-end">
                <strong>Discount ({{ $bill->discount_percentage }}%):</strong>
                @if($bill->discount_reason)
                  <br><small class="text-muted">{{ $bill->discount_reason }}</small>
                @endif
              </td>
              <td class="text-end text-danger">-TSh {{ number_format($bill->discount_amount, 2) }}</td>
            </tr>
            @endif
            <tr>
              <td colspan="5" class="text-end"><strong>Tax:</strong></td>
              <td class="text-end">TSh {{ number_format($bill->tax_amount, 2) }}</td>
            </tr>
            <tr class="summary-total">
              <td colspan="5" class="text-end"><strong>Total Amount:</strong></td>
              <td class="text-end"><strong>TSh {{ number_format($bill->total_amount, 2) }}</strong></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>

    @if($bill->payments->count() > 0)
    <div class="info-card">
      <h5 class="card-title">Payment History</h5>
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
              <td class="text-end"><strong>TSh {{ number_format($payment->amount, 2) }}</strong></td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @endif
  </div>

  <div class="col-md-4">
    <div class="summary-card">
      <h5 class="card-title">Bill Summary</h5>
      <div class="summary-row">
        <span>Total Amount:</span>
        <strong>TSh {{ number_format($bill->total_amount, 2) }}</strong>
      </div>
      <div class="summary-row">
        <span>Paid Amount:</span>
        <strong>TSh {{ number_format($bill->paid_amount, 2) }}</strong>
      </div>
      <div class="summary-divider"></div>
      <div class="summary-row">
        <span>Balance Due:</span>
        <strong>TSh {{ number_format($bill->balance_amount, 2) }}</strong>
      </div>
    </div>

    @if($bill->balance_amount > 0)
    <div class="payment-form">
      <h6 class="card-title">Add Payment</h6>
      <form action="{{ route('bills.add-payment', $bill) }}" method="POST">
        @csrf
        <div class="mb-3">
          <label class="form-label">Amount <span class="text-danger">*</span></label>
          <input type="number" step="0.01" class="form-control" name="amount" 
                 max="{{ $bill->balance_amount }}" value="{{ $bill->balance_amount }}" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Payment Method <span class="text-danger">*</span></label>
          <select class="form-select" name="payment_method" required>
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
        <button type="submit" class="btn-primary-custom">
          <i class="bi bi-cash-coin me-2"></i>Add Payment
        </button>
      </form>
    </div>
    @endif

    <div class="d-grid gap-2">
      <a href="{{ route('bills.receipt', $bill) }}" class="btn-outline-custom" target="_blank">
        <i class="bi bi-printer me-2"></i>Print Receipt
      </a>
      <a href="{{ route('bills.index') }}" class="btn-outline-custom">
        <i class="bi bi-arrow-left me-2"></i>Back to Bills
      </a>
    </div>
  </div>
</div>
@endsection
