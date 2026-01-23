@extends('layouts.app')

@section('title', 'Create Bill')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('bills.index') }}">Bills</a></li>
<li class="breadcrumb-item active" aria-current="page">Create</li>
@endsection

@push('styles')
<style>
  .service-row {
    background: #f9fafb;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 10px;
    border: 1px solid #e5e7eb;
  }
  .total-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    border-radius: 12px;
    margin-top: 20px;
  }
</style>
@endpush

@section('content')
<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Create New Bill</h5>
      </div>
      <div class="card-body">
        <form action="{{ route('bills.store') }}" method="POST" id="billForm">
          @csrf
          
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="patient_id" class="form-label">Patient <span class="text-danger">*</span></label>
              <select class="form-control @error('patient_id') is-invalid @enderror" 
                      id="patient_id" name="patient_id" required>
                <option value="">Select Patient</option>
                @foreach($patients as $patient)
                  <option value="{{ $patient->id }}" {{ (old('patient_id') ?? $appointment?->patient_id) == $patient->id ? 'selected' : '' }}>
                    {{ $patient->first_name }} {{ $patient->last_name }} - {{ $patient->patient_id }}
                  </option>
                @endforeach
              </select>
              @error('patient_id')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-3">
              <label for="bill_type" class="form-label">Bill Type <span class="text-danger">*</span></label>
              <select class="form-control @error('bill_type') is-invalid @enderror" 
                      id="bill_type" name="bill_type" required>
                <option value="opd" {{ old('bill_type') === 'opd' ? 'selected' : '' }}>OPD</option>
                <option value="emergency" {{ old('bill_type') === 'emergency' ? 'selected' : '' }}>Emergency</option>
                <option value="laboratory" {{ old('bill_type') === 'laboratory' ? 'selected' : '' }}>Laboratory</option>
                <option value="pharmacy" {{ old('bill_type') === 'pharmacy' ? 'selected' : '' }}>Pharmacy</option>
              </select>
              @error('bill_type')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-3">
              <label for="visit_type" class="form-label">Visit Type</label>
              <select class="form-control" id="visit_type" name="visit_type">
                <option value="">Select Type</option>
                <option value="opd">OPD</option>
                <option value="emergency">Emergency</option>
              </select>
            </div>
          </div>

          <hr>
          <h6 class="mb-3">Services/Items</h6>
          
          <div id="servicesContainer">
            <div class="service-row" data-index="0">
              <div class="row align-items-end">
                <div class="col-md-5">
                  <label class="form-label">Service <span class="text-danger">*</span></label>
                  <select class="form-control service-select" name="services[0][service_id]" required>
                    <option value="">Select Service</option>
                    @foreach($services as $service)
                      <option value="{{ $service->id }}" 
                              data-price="{{ $service->standard_charge }}"
                              data-taxable="{{ $service->taxable }}"
                              data-tax="{{ $service->tax_percentage }}">
                        {{ $service->service_name }} - ${{ number_format($service->standard_charge, 2) }}
                      </option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-2">
                  <label class="form-label">Qty <span class="text-danger">*</span></label>
                  <input type="number" class="form-control quantity-input" name="services[0][quantity]" value="1" min="1" required>
                </div>
                <div class="col-md-2">
                  <label class="form-label">Discount %</label>
                  <input type="number" class="form-control discount-input" name="services[0][discount_percentage]" value="0" min="0" max="100" step="0.01">
                </div>
                <div class="col-md-2">
                  <label class="form-label">Amount</label>
                  <input type="text" class="form-control item-total" readonly value="0.00">
                </div>
                <div class="col-md-1">
                  <button type="button" class="btn btn-danger btn-sm remove-service" disabled>
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <button type="button" class="btn btn-sm btn-outline-primary" id="addService">
            <i class="bi bi-plus-circle me-1"></i>Add Another Service
          </button>

          <hr>

          <div class="row">
            <div class="col-md-6">
              <label for="discount_percentage" class="form-label">Overall Discount %</label>
              <input type="number" class="form-control" id="discount_percentage" name="discount_percentage" value="0" min="0" max="100" step="0.01">
            </div>
            <div class="col-md-6">
              <label for="discount_reason" class="form-label">Discount Reason</label>
              <input type="text" class="form-control" id="discount_reason" name="discount_reason">
            </div>
          </div>

          <div class="mb-3 mt-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-check-circle me-1"></i>Generate Bill
            </button>
            <a href="{{ route('bills.index') }}" class="btn btn-secondary">
              <i class="bi bi-x-circle me-1"></i>Cancel
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="total-section">
      <h5 class="mb-3">Bill Summary</h5>
      <div class="d-flex justify-content-between mb-2">
        <span>Subtotal:</span>
        <strong id="subtotal">$0.00</strong>
      </div>
      <div class="d-flex justify-content-between mb-2">
        <span>Discount:</span>
        <strong id="discount">$0.00</strong>
      </div>
      <div class="d-flex justify-content-between mb-2">
        <span>Tax:</span>
        <strong id="tax">$0.00</strong>
      </div>
      <hr style="border-color: rgba(255,255,255,0.3);">
      <div class="d-flex justify-content-between">
        <h5>Total Amount:</h5>
        <h4 id="totalAmount">$0.00</h4>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
let serviceIndex = 1;

document.getElementById('addService').addEventListener('click', function() {
  const container = document.getElementById('servicesContainer');
  const newRow = document.querySelector('.service-row').cloneNode(true);
  
  newRow.setAttribute('data-index', serviceIndex);
  newRow.querySelectorAll('input, select').forEach(input => {
    const name = input.getAttribute('name');
    if (name) {
      input.setAttribute('name', name.replace(/\[\d+\]/, `[${serviceIndex}]`));
    }
    if (input.type !== 'button') {
      input.value = input.classList.contains('quantity-input') ? '1' : '0';
    }
  });
  
  newRow.querySelector('.remove-service').disabled = false;
  container.appendChild(newRow);
  serviceIndex++;
  attachServiceEventListeners();
});

document.addEventListener('click', function(e) {
  if (e.target.closest('.remove-service')) {
    if (document.querySelectorAll('.service-row').length > 1) {
      e.target.closest('.service-row').remove();
      calculateTotals();
    }
  }
});

function attachServiceEventListeners() {
  document.querySelectorAll('.service-select, .quantity-input, .discount-input').forEach(el => {
    el.removeEventListener('change', calculateItemTotal);
    el.addEventListener('change', calculateItemTotal);
  });
}

function calculateItemTotal(e) {
  const row = e.target.closest('.service-row');
  const select = row.querySelector('.service-select');
  const qty = parseFloat(row.querySelector('.quantity-input').value) || 0;
  const discount = parseFloat(row.querySelector('.discount-input').value) || 0;
  
  if (select.value) {
    const option = select.options[select.selectedIndex];
    const price = parseFloat(option.dataset.price) || 0;
    const taxable = option.dataset.taxable === '1';
    const taxRate = parseFloat(option.dataset.tax) || 0;
    
    let amount = price * qty;
    amount -= amount * (discount / 100);
    
    if (taxable) {
      amount += amount * (taxRate / 100);
    }
    
    row.querySelector('.item-total').value = amount.toFixed(2);
  }
  
  calculateTotals();
}

function calculateTotals() {
  let subtotal = 0;
  
  document.querySelectorAll('.item-total').forEach(input => {
    subtotal += parseFloat(input.value) || 0;
  });
  
  const overallDiscount = parseFloat(document.getElementById('discount_percentage').value) || 0;
  const discountAmount = subtotal * (overallDiscount / 100);
  const total = subtotal - discountAmount;
  
  document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
  document.getElementById('discount').textContent = '$' + discountAmount.toFixed(2);
  document.getElementById('totalAmount').textContent = '$' + total.toFixed(2);
}

document.getElementById('discount_percentage').addEventListener('input', calculateTotals);

attachServiceEventListeners();
</script>
@endpush
@endsection
