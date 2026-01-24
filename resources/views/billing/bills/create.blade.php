@extends('layouts.app')

@section('title', 'Create Bill')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('services.index') }}">Accounting</a></li>
<li class="breadcrumb-item"><a href="{{ route('bills.index') }}">Bills</a></li>
<li class="breadcrumb-item active" aria-current="page">Create</li>
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

  .form-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
  }

  .form-section {
    padding: 24px;
    border-bottom: 1px solid #f3f4f6;
  }

  .form-section:last-child {
    border-bottom: none;
  }

  .section-title {
    font-size: 14px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 16px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
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
    transition: all 0.2s;
  }

  .form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    outline: none;
  }

  .service-row {
    background: #f9fafb;
    padding: 16px;
    border-radius: 8px;
    margin-bottom: 12px;
    border: 1px solid #e5e7eb;
    transition: all 0.2s;
  }

  .service-row:hover {
    border-color: #d1d5db;
    background: #f3f4f6;
  }

  .summary-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 24px;
    position: sticky;
    top: 100px;
  }

  .summary-title {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
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

  .summary-total {
    border-top: 2px solid #e5e7eb;
    padding-top: 16px;
    margin-top: 16px;
  }

  .summary-total .summary-row {
    font-size: 18px;
    font-weight: 600;
  }

  .btn-add-service {
    border: 1px dashed #d1d5db;
    background: transparent;
    color: #6b7280;
    padding: 10px 16px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
  }

  .btn-add-service:hover {
    border-color: #667eea;
    color: #667eea;
    background: #f9fafb;
  }

  .btn-remove {
    width: 36px;
    height: 36px;
    padding: 0;
    border: 1px solid #fee2e2;
    background: white;
    color: #dc2626;
    border-radius: 6px;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .btn-remove:hover {
    background: #fee2e2;
    border-color: #dc2626;
  }

  .btn-primary-custom {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
  }

  .btn-primary-custom:hover {
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    transform: translateY(-1px);
    color: white;
  }

  .btn-secondary-custom {
    background: white;
    color: #6b7280;
    border: 1px solid #e5e7eb;
    padding: 12px 24px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    display: inline-block;
  }

  .btn-secondary-custom:hover {
    background: #f9fafb;
    border-color: #d1d5db;
    color: #374151;
  }

  .item-total-display {
    background: #f3f4f6;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 10px 14px;
    font-size: 14px;
    font-weight: 600;
    color: #111827;
    text-align: right;
  }
</style>
@endpush

@section('content')
<div class="page-header">
  <div>
    <h1 class="page-title">Create New Bill</h1>
    <p class="page-subtitle">Generate bill for patient services</p>
  </div>
</div>

<div class="row">
  <div class="col-lg-8">
    <form action="{{ route('bills.store') }}" method="POST" id="billForm">
      @csrf
      <div class="form-card">
        <div class="form-section">
          <div class="section-title">Bill Information</div>
          
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label for="patient_id" class="form-label">Patient <span class="text-danger">*</span></label>
              <select class="form-select @error('patient_id') is-invalid @enderror" 
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
              <select class="form-select @error('bill_type') is-invalid @enderror" 
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
              <select class="form-select" id="visit_type" name="visit_type">
                <option value="">Select Type</option>
                <option value="opd">OPD</option>
                <option value="emergency">Emergency</option>
              </select>
            </div>
          </div>

          <div class="section-title mt-4">Services & Items</div>
        
          <div id="servicesContainer">
            <div class="service-row" data-index="0">
              <div class="row g-3 align-items-end">
                <div class="col-md-5">
                  <label class="form-label">Service <span class="text-danger">*</span></label>
                  <select class="form-select service-select" name="services[0][service_id]" required>
                  <option value="">Select Service</option>
                  @foreach($services as $service)
                    <option value="{{ $service->id }}" 
                            data-price="{{ $service->standard_charge }}"
                            data-taxable="{{ $service->taxable }}"
                            data-tax="{{ $service->tax_percentage }}">
                      {{ $service->service_name }} - TSh {{ number_format($service->standard_charge, 2) }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-2">
                <label class="form-label">Quantity</label>
                <input type="number" class="form-control quantity-input" name="services[0][quantity]" value="1" min="1" required>
              </div>
              <div class="col-md-2">
                <label class="form-label">Discount %</label>
                <input type="number" class="form-control discount-input" name="services[0][discount_percentage]" value="0" min="0" max="100" step="0.01">
              </div>
              <div class="col-md-2">
                <label class="form-label">Amount</label>
                <div class="item-total-display">0.00</div>
                <input type="hidden" class="item-total" value="0.00">
              </div>
              <div class="col-md-1">
                <button type="button" class="btn-remove remove-service" disabled>
                  <i class="bi bi-trash"></i>
                </button>
              </div>
            </div>
          </div>

          <button type="button" class="btn-add-service w-100" id="addService">
            <i class="bi bi-plus-circle me-2"></i>Add Another Service
          </button>

          <div class="section-title mt-4">Additional Information</div>
        
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label for="discount_percentage" class="form-label">Overall Discount %</label>
              <input type="number" class="form-control" id="discount_percentage" name="discount_percentage" value="0" min="0" max="100" step="0.01">
            </div>
            <div class="col-md-6">
              <label for="discount_reason" class="form-label">Discount Reason</label>
              <input type="text" class="form-control" id="discount_reason" name="discount_reason">
            </div>
          </div>

          <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn-primary-custom">
              <i class="bi bi-check-circle me-2"></i>Generate Bill
            </button>
            <a href="{{ route('bills.index') }}" class="btn-secondary-custom">
              <i class="bi bi-x-circle me-2"></i>Cancel
            </a>
          </div>
        </form></a>
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="summary-card">
      <h3 class="summary-title">Bill Summary</h3>
      
      <div class="summary-row">
        <span>Subtotal:</span>
        <strong id="subtotal">TSh 0.00</strong>
      </div>
      <div class="summary-row">
        <span>Discount:</span>
        <strong id="discount">TSh 0.00</strong>
      </div>
      <div class="summary-row">
        <span>Tax:</span>
        <strong id="tax">TSh 0.00</strong>
      </div>
      
      <div class="summary-total">
        <div class="summary-row">
          <span>Total Amount:</span>
          <strong id="totalAmount">TSh 0.00</strong>
        </div>
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
    if (input.type !== 'button' && input.type !== 'hidden') {
      input.value = input.classList.contains('quantity-input') ? '1' : '0';
    }
  });
  
  newRow.querySelector('.item-total-display').textContent = '0.00';
  newRow.querySelector('.item-total').value = '0.00';
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
    
    row.querySelector('.item-total-display').textContent = amount.toFixed(2);
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
  
  document.getElementById('subtotal').textContent = 'TSh ' + subtotal.toFixed(2);
  document.getElementById('discount').textContent = 'TSh ' + discountAmount.toFixed(2);
  document.getElementById('totalAmount').textContent = 'TSh ' + total.toFixed(2);
}

document.getElementById('discount_percentage').addEventListener('input', calculateTotals);

attachServiceEventListeners();
</script>
@endpush
@endsection
