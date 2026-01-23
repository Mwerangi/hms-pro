@extends('layouts.app')

@section('title', 'Create Ward')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('ipd.dashboard') }}">IPD</a></li>
<li class="breadcrumb-item"><a href="{{ route('wards.index') }}">Wards</a></li>
<li class="breadcrumb-item active" aria-current="page">Create</li>
@endsection

@push('styles')
<style>
  .page-header {
    margin-bottom: 32px;
  }

  .page-title {
    font-size: 24px;
    font-weight: 600;
    color: #111827;
    margin: 0 0 8px 0;
  }

  .form-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
  }

  .form-section-title {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 2px solid #f3f4f6;
  }

  .form-label {
    font-size: 13px;
    font-weight: 500;
    color: #374151;
    margin-bottom: 6px;
  }

  .form-control {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 10px 14px;
    font-size: 14px;
  }

  .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
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
  }

  .btn-outline {
    border: 1px solid #e5e7eb;
    background: white;
    color: #374151;
    padding: 12px 24px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    display: inline-block;
  }

  .form-text {
    font-size: 12px;
    color: #6b7280;
    margin-top: 4px;
  }
</style>
@endpush

@section('content')
<div class="page-header">
  <h1 class="page-title">Create Ward</h1>
  <p style="color: #6b7280; font-size: 14px; margin: 0;">Add a new ward to the hospital</p>
</div>

<form action="{{ route('wards.store') }}" method="POST">
  @csrf

  <div class="form-card">
    <h3 class="form-section-title">Basic Information</h3>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label for="ward_number" class="form-label">Ward Number <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('ward_number') is-invalid @enderror" 
               id="ward_number" name="ward_number" value="{{ old('ward_number') }}" required>
        @error('ward_number')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="form-text">Unique identifier for the ward (e.g., W-001, ICU-01)</small>
      </div>

      <div class="col-md-6 mb-3">
        <label for="ward_name" class="form-label">Ward Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('ward_name') is-invalid @enderror" 
               id="ward_name" name="ward_name" value="{{ old('ward_name') }}" required>
        @error('ward_name')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label for="ward_type" class="form-label">Ward Type <span class="text-danger">*</span></label>
        <select class="form-control @error('ward_type') is-invalid @enderror" 
                id="ward_type" name="ward_type" required>
          <option value="">Select ward type</option>
          <option value="general" {{ old('ward_type') === 'general' ? 'selected' : '' }}>General</option>
          <option value="semi-private" {{ old('ward_type') === 'semi-private' ? 'selected' : '' }}>Semi-Private</option>
          <option value="private" {{ old('ward_type') === 'private' ? 'selected' : '' }}>Private</option>
          <option value="icu" {{ old('ward_type') === 'icu' ? 'selected' : '' }}>ICU</option>
          <option value="nicu" {{ old('ward_type') === 'nicu' ? 'selected' : '' }}>NICU</option>
          <option value="picu" {{ old('ward_type') === 'picu' ? 'selected' : '' }}>PICU</option>
          <option value="emergency" {{ old('ward_type') === 'emergency' ? 'selected' : '' }}>Emergency</option>
        </select>
        @error('ward_type')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-6 mb-3">
        <label for="base_charge_per_day" class="form-label">Base Charge per Day ($) <span class="text-danger">*</span></label>
        <input type="number" class="form-control @error('base_charge_per_day') is-invalid @enderror" 
               id="base_charge_per_day" name="base_charge_per_day" 
               value="{{ old('base_charge_per_day') }}" 
               step="0.01" min="0" required>
        @error('base_charge_per_day')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
    </div>

    <div class="row">
      <div class="col-md-4 mb-3">
        <label for="floor" class="form-label">Floor</label>
        <input type="text" class="form-control @error('floor') is-invalid @enderror" 
               id="floor" name="floor" value="{{ old('floor') }}">
        @error('floor')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-8 mb-3">
        <label for="building" class="form-label">Building</label>
        <input type="text" class="form-control @error('building') is-invalid @enderror" 
               id="building" name="building" value="{{ old('building') }}">
        @error('building')
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
  </div>

  <div class="form-card">
    <h3 class="form-section-title">Staff & Contact Information</h3>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label for="nurse_id" class="form-label">Nurse in Charge</label>
        <select class="form-control @error('nurse_id') is-invalid @enderror" 
                id="nurse_id" name="nurse_id">
          <option value="">-- Select Nurse (Optional) --</option>
          @foreach($nurses as $nurse)
            <option value="{{ $nurse->id }}" {{ old('nurse_id') == $nurse->id ? 'selected' : '' }}>
              {{ $nurse->name }} - {{ $nurse->email }}
            </option>
          @endforeach
        </select>
        @error('nurse_id')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="form-text">Assign a nurse to manage this ward (can be changed later)</small>
      </div>

      <div class="col-md-6 mb-3">
        <label for="contact_number" class="form-label">Contact Number</label>
        <input type="text" class="form-control @error('contact_number') is-invalid @enderror" 
               id="contact_number" name="contact_number" value="{{ old('contact_number') }}">
        @error('contact_number')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
    </div>
  </div>

  <div style="display: flex; gap: 12px;">
    <button type="submit" class="btn-primary-custom">
      <i class="bi bi-check-circle me-1"></i>Create Ward
    </button>
    <a href="{{ route('wards.index') }}" class="btn-outline">
      <i class="bi bi-x-circle me-1"></i>Cancel
    </a>
  </div>
</form>
@endsection
