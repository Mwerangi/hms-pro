@extends('layouts.app')

@section('title', 'Add New Service')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('services.index') }}">Services</a></li>
<li class="breadcrumb-item active" aria-current="page">Create</li>
@endsection

@section('content')
<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Add New Service</h5>
      </div>
      <div class="card-body">
        <form action="{{ route('services.store') }}" method="POST">
          @csrf
          
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="service_code" class="form-label">Service Code <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('service_code') is-invalid @enderror" 
                     id="service_code" name="service_code" value="{{ old('service_code') }}" required>
              @error('service_code')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <small class="text-muted">e.g., CONS-001, LAB-CBC</small>
            </div>

            <div class="col-md-6">
              <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
              <select class="form-control @error('category') is-invalid @enderror" id="category" name="category" required>
                <option value="">Select Category</option>
                <option value="consultation" {{ old('category') === 'consultation' ? 'selected' : '' }}>Consultation</option>
                <option value="laboratory" {{ old('category') === 'laboratory' ? 'selected' : '' }}>Laboratory</option>
                <option value="radiology" {{ old('category') === 'radiology' ? 'selected' : '' }}>Radiology</option>
                <option value="procedure" {{ old('category') === 'procedure' ? 'selected' : '' }}>Procedure</option>
                <option value="pharmacy" {{ old('category') === 'pharmacy' ? 'selected' : '' }}>Pharmacy</option>
                <option value="room_charge" {{ old('category') === 'room_charge' ? 'selected' : '' }}>Room Charge</option>
                <option value="nursing_care" {{ old('category') === 'nursing_care' ? 'selected' : '' }}>Nursing Care</option>
                <option value="emergency" {{ old('category') === 'emergency' ? 'selected' : '' }}>Emergency</option>
                <option value="surgery" {{ old('category') === 'surgery' ? 'selected' : '' }}>Surgery</option>
                <option value="other" {{ old('category') === 'other' ? 'selected' : '' }}>Other</option>
              </select>
              @error('category')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="mb-3">
            <label for="service_name" class="form-label">Service Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('service_name') is-invalid @enderror" 
                   id="service_name" name="service_name" value="{{ old('service_name') }}" required>
            @error('service_name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label for="department" class="form-label">Department</label>
              <input type="text" class="form-control @error('department') is-invalid @enderror" 
                     id="department" name="department" value="{{ old('department') }}">
              @error('department')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6">
              <label for="standard_charge" class="form-label">Standard Charge ($) <span class="text-danger">*</span></label>
              <input type="number" step="0.01" class="form-control @error('standard_charge') is-invalid @enderror" 
                     id="standard_charge" name="standard_charge" value="{{ old('standard_charge') }}" required>
              @error('standard_charge')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="taxable" name="taxable" value="1" {{ old('taxable') ? 'checked' : '' }}>
                <label class="form-check-label" for="taxable">Taxable Service</label>
              </div>
            </div>

            <div class="col-md-6">
              <label for="tax_percentage" class="form-label">Tax Percentage (%)</label>
              <input type="number" step="0.01" class="form-control @error('tax_percentage') is-invalid @enderror" 
                     id="tax_percentage" name="tax_percentage" value="{{ old('tax_percentage', 0) }}">
              @error('tax_percentage')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control @error('description') is-invalid @enderror" 
                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
            @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-check-circle me-1"></i>Create Service
            </button>
            <a href="{{ route('services.index') }}" class="btn btn-secondary">
              <i class="bi bi-x-circle me-1"></i>Cancel
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
