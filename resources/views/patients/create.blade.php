@extends('layouts.app')

@section('title', 'Register New Patient')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('patients.index') }}">Patients</a></li>
<li class="breadcrumb-item active" aria-current="page">Register New Patient</li>
@endsection

@push('styles')
<style>
  .form-card {
    background: white;
    border-radius: 16px;
    padding: 28px;
    border: 1px solid #e2e8f0;
    margin-bottom: 20px;
    transition: all 0.3s ease;
  }

  [data-theme="dark"] .form-card {
    background: #1a202c;
    border-color: #2d3748;
  }

  .form-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  }

  .section-title {
    font-size: 18px;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 2px solid #f7fafc;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  [data-theme="dark"] .section-title {
    color: #e2e8f0;
    border-bottom-color: #2d3748;
  }

  .section-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--hms-purple), var(--hms-primary));
    color: white;
    font-size: 18px;
  }

  .page-header {
    background: transparent;
    margin-bottom: 25px;
  }

  .page-title {
    font-size: 28px;
    font-weight: 700;
    color: #2d3748;
    margin: 0;
  }

  [data-theme="dark"] .page-title {
    color: #e2e8f0;
  }

  .form-label {
    font-weight: 500;
    color: #4a5568;
    margin-bottom: 8px;
  }

  [data-theme="dark"] .form-label {
    color: #cbd5e0;
  }

  .required::after {
    content: '*';
    color: #dc3545;
    margin-left: 4px;
  }

  .form-control:focus, .form-select:focus {
    border-color: var(--hms-purple);
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
  }

  .btn-submit {
    background: linear-gradient(135deg, var(--hms-purple), var(--hms-primary));
    border: none;
    padding: 12px 32px;
    font-weight: 600;
    transition: all 0.3s ease;
  }

  .btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
  }

  .photo-preview {
    width: 120px;
    height: 120px;
    border-radius: 12px;
    border: 2px dashed #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    margin-top: 10px;
    background: #f7fafc;
  }

  [data-theme="dark"] .photo-preview {
    background: #2d3748;
    border-color: #4a5568;
  }

  .photo-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .photo-preview i {
    font-size: 48px;
    color: #cbd5e0;
  }
</style>
@endpush

@section('content')
<div class="page-header">
  <h1 class="page-title">
    <i class="bi bi-person-plus-fill text-primary me-2"></i>
    Register New Patient
  </h1>
</div>

<form action="{{ route('patients.store') }}" method="POST" enctype="multipart/form-data" class="form-loading">
  @csrf
  
  <!-- Personal Information -->
  <div class="form-card">
    <h3 class="section-title">
      <div class="section-icon">
        <i class="bi bi-person"></i>
      </div>
      Personal Information
    </h3>
    
    <div class="row">
      <div class="col-md-4 mb-3">
        <label class="form-label required">First Name</label>
        <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" 
               value="{{ old('first_name') }}" required>
        @error('first_name')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-4 mb-3">
        <label class="form-label">Middle Name</label>
        <input type="text" name="middle_name" class="form-control @error('middle_name') is-invalid @enderror" 
               value="{{ old('middle_name') }}">
        @error('middle_name')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-4 mb-3">
        <label class="form-label required">Last Name</label>
        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" 
               value="{{ old('last_name') }}" required>
        @error('last_name')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-3 mb-3">
        <label class="form-label required">Date of Birth</label>
        <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" 
               value="{{ old('date_of_birth') }}" required>
        @error('date_of_birth')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-3 mb-3">
        <label class="form-label required">Gender</label>
        <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
          <option value="">Select Gender</option>
          <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
          <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
          <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
        </select>
        @error('gender')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-3 mb-3">
        <label class="form-label">Blood Group</label>
        <select name="blood_group" class="form-select @error('blood_group') is-invalid @enderror">
          <option value="">Select Blood Group</option>
          <option value="A+" {{ old('blood_group') == 'A+' ? 'selected' : '' }}>A+</option>
          <option value="A-" {{ old('blood_group') == 'A-' ? 'selected' : '' }}>A-</option>
          <option value="B+" {{ old('blood_group') == 'B+' ? 'selected' : '' }}>B+</option>
          <option value="B-" {{ old('blood_group') == 'B-' ? 'selected' : '' }}>B-</option>
          <option value="O+" {{ old('blood_group') == 'O+' ? 'selected' : '' }}>O+</option>
          <option value="O-" {{ old('blood_group') == 'O-' ? 'selected' : '' }}>O-</option>
          <option value="AB+" {{ old('blood_group') == 'AB+' ? 'selected' : '' }}>AB+</option>
          <option value="AB-" {{ old('blood_group') == 'AB-' ? 'selected' : '' }}>AB-</option>
        </select>
        @error('blood_group')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-3 mb-3">
        <label class="form-label">Marital Status</label>
        <select name="marital_status" class="form-select @error('marital_status') is-invalid @enderror">
          <option value="">Select Status</option>
          <option value="single" {{ old('marital_status') == 'single' ? 'selected' : '' }}>Single</option>
          <option value="married" {{ old('marital_status') == 'married' ? 'selected' : '' }}>Married</option>
          <option value="divorced" {{ old('marital_status') == 'divorced' ? 'selected' : '' }}>Divorced</option>
          <option value="widowed" {{ old('marital_status') == 'widowed' ? 'selected' : '' }}>Widowed</option>
        </select>
        @error('marital_status')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-6 mb-3">
        <label class="form-label">Occupation</label>
        <input type="text" name="occupation" class="form-control @error('occupation') is-invalid @enderror" 
               value="{{ old('occupation') }}" placeholder="e.g., Teacher, Engineer">
        @error('occupation')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-6 mb-3">
        <label class="form-label">Photo</label>
        <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" 
               accept="image/*" onchange="previewPhoto(event)">
        @error('photo')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="photo-preview" id="photoPreview">
          <i class="bi bi-camera"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Contact Information -->
  <div class="form-card">
    <h3 class="section-title">
      <div class="section-icon">
        <i class="bi bi-telephone"></i>
      </div>
      Contact Information
    </h3>
    
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label required">Phone Number</label>
        <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
               value="{{ old('phone') }}" required placeholder="+254 700 000000">
        @error('phone')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-6 mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
               value="{{ old('email') }}" placeholder="patient@example.com">
        @error('email')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-12 mb-3">
        <label class="form-label required">Address</label>
        <textarea name="address" rows="2" class="form-control @error('address') is-invalid @enderror" 
                  required>{{ old('address') }}</textarea>
        @error('address')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-4 mb-3">
        <label class="form-label required">City</label>
        <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" 
               value="{{ old('city') }}" required>
        @error('city')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-4 mb-3">
        <label class="form-label required">State/County</label>
        <input type="text" name="state" class="form-control @error('state') is-invalid @enderror" 
               value="{{ old('state') }}" required>
        @error('state')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-2 mb-3">
        <label class="form-label">Postal Code</label>
        <input type="text" name="postal_code" class="form-control @error('postal_code') is-invalid @enderror" 
               value="{{ old('postal_code') }}">
        @error('postal_code')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-2 mb-3">
        <label class="form-label required">Country</label>
        <input type="text" name="country" class="form-control @error('country') is-invalid @enderror" 
               value="{{ old('country', 'Kenya') }}" required>
        @error('country')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
    </div>
  </div>

  <!-- Emergency Contact -->
  <div class="form-card">
    <h3 class="section-title">
      <div class="section-icon">
        <i class="bi bi-exclamation-triangle"></i>
      </div>
      Emergency Contact
    </h3>
    
    <div class="row">
      <div class="col-md-4 mb-3">
        <label class="form-label required">Contact Name</label>
        <input type="text" name="emergency_contact_name" class="form-control @error('emergency_contact_name') is-invalid @enderror" 
               value="{{ old('emergency_contact_name') }}" required>
        @error('emergency_contact_name')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-4 mb-3">
        <label class="form-label required">Contact Phone</label>
        <input type="tel" name="emergency_contact_phone" class="form-control @error('emergency_contact_phone') is-invalid @enderror" 
               value="{{ old('emergency_contact_phone') }}" required>
        @error('emergency_contact_phone')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-4 mb-3">
        <label class="form-label required">Relationship</label>
        <input type="text" name="emergency_contact_relationship" class="form-control @error('emergency_contact_relationship') is-invalid @enderror" 
               value="{{ old('emergency_contact_relationship') }}" required placeholder="e.g., Spouse, Parent">
        @error('emergency_contact_relationship')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
    </div>
  </div>

  <!-- Medical Information -->
  <div class="form-card">
    <h3 class="section-title">
      <div class="section-icon">
        <i class="bi bi-heart-pulse"></i>
      </div>
      Medical Information
    </h3>
    
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Allergies</label>
        <textarea name="allergies" rows="3" class="form-control @error('allergies') is-invalid @enderror" 
                  placeholder="List any known allergies...">{{ old('allergies') }}</textarea>
        @error('allergies')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-6 mb-3">
        <label class="form-label">Chronic Conditions</label>
        <textarea name="chronic_conditions" rows="3" class="form-control @error('chronic_conditions') is-invalid @enderror" 
                  placeholder="e.g., Diabetes, Hypertension...">{{ old('chronic_conditions') }}</textarea>
        @error('chronic_conditions')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-6 mb-3">
        <label class="form-label">Current Medications</label>
        <textarea name="current_medications" rows="3" class="form-control @error('current_medications') is-invalid @enderror" 
                  placeholder="List current medications...">{{ old('current_medications') }}</textarea>
        @error('current_medications')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-6 mb-3">
        <label class="form-label">Medical History</label>
        <textarea name="medical_history" rows="3" class="form-control @error('medical_history') is-invalid @enderror" 
                  placeholder="Previous surgeries, major illnesses...">{{ old('medical_history') }}</textarea>
        @error('medical_history')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
    </div>
  </div>

  <!-- Insurance Information -->
  <div class="form-card">
    <h3 class="section-title">
      <div class="section-icon">
        <i class="bi bi-shield-check"></i>
      </div>
      Insurance Information (Optional)
    </h3>
    
    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Insurance Provider</label>
        <input type="text" name="insurance_provider" class="form-control @error('insurance_provider') is-invalid @enderror" 
               value="{{ old('insurance_provider') }}" placeholder="e.g., NHIF, AAR, Jubilee">
        @error('insurance_provider')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-6 mb-3">
        <label class="form-label">Insurance Number</label>
        <input type="text" name="insurance_number" class="form-control @error('insurance_number') is-invalid @enderror" 
               value="{{ old('insurance_number') }}" placeholder="Policy/Member Number">
        @error('insurance_number')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
    </div>
  </div>

  <!-- Form Actions -->
  <div class="d-flex gap-2 justify-content-end">
    <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary">
      <i class="bi bi-x-circle me-2"></i>Cancel
    </a>
    <button type="submit" class="btn btn-primary btn-submit">
      <i class="bi bi-check-circle me-2"></i>Register Patient
    </button>
  </div>
</form>

@push('scripts')
<script>
function previewPhoto(event) {
  const preview = document.getElementById('photoPreview');
  const file = event.target.files[0];
  
  if (file) {
    const reader = new FileReader();
    reader.onload = function(e) {
      preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
    }
    reader.readAsDataURL(file);
  }
}
</script>
@endpush
@endsection
