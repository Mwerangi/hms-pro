@extends('layouts.app')

@section('title', 'Edit Patient - ' . $patient->full_name)

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('patients.index') }}">Patients</a></li>
<li class="breadcrumb-item"><a href="{{ route('patients.show', $patient) }}">{{ $patient->full_name }}</a></li>
<li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection

@push('styles')
<style>
  /* Modern Minimalistic Design */
  .page-header {
    margin-bottom: 32px;
  }

  .page-title {
    font-size: 24px;
    font-weight: 600;
    color: #111827;
    margin: 0 0 8px 0;
  }

  .patient-badge {
    background: #f3f4f6;
    color: #667eea;
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    display: inline-block;
  }

  /* Form Cards */
  .form-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 20px;
  }

  .section-title {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f3f4f6;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .section-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f3f4f6;
    color: #667eea;
    font-size: 16px;
  }

  .form-label {
    font-weight: 500;
    color: #374151;
    margin-bottom: 6px;
    font-size: 13px;
  }

  .required::after {
    content: '*';
    color: #ef4444;
    margin-left: 4px;
  }

  .form-control, .form-select {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 10px 14px;
    font-size: 14px;
  }

  .form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
  }

  .btn-submit {
    background: #667eea;
    border: none;
    color: white;
    padding: 12px 32px;
    font-weight: 500;
    border-radius: 8px;
    font-size: 14px;
  }

  .btn-submit:hover {
    background: #5568d3;
  }

  .photo-preview {
    width: 120px;
    height: 120px;
    border-radius: 10px;
    border: 2px dashed #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    margin-top: 10px;
    background: #f9fafb;
  }

  .photo-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .photo-preview i {
    font-size: 36px;
    color: #d1d5db;
  }

  .current-photo {
    margin-top: 6px;
    font-size: 12px;
    color: #6b7280;
  }
</style>
@endpush

@section('content')
<div class="page-header d-flex justify-content-between align-items-start">
  <div>
    <h1 class="page-title">Edit Patient</h1>
    <span class="patient-badge">{{ $patient->patient_id }}</span>
  </div>
  <a href="{{ route('patients.show', $patient) }}" class="btn btn-outline-primary">
    <i class="bi bi-eye me-2"></i>View Profile
  </a>
</div>

<form action="{{ route('patients.update', $patient) }}" method="POST" enctype="multipart/form-data" class="form-loading">
  @csrf
  @method('PUT')
  
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
               value="{{ old('first_name', $patient->first_name) }}" required>
        @error('first_name')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-4 mb-3">
        <label class="form-label">Middle Name</label>
        <input type="text" name="middle_name" class="form-control @error('middle_name') is-invalid @enderror" 
               value="{{ old('middle_name', $patient->middle_name) }}">
        @error('middle_name')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-4 mb-3">
        <label class="form-label required">Last Name</label>
        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" 
               value="{{ old('last_name', $patient->last_name) }}" required>
        @error('last_name')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-3 mb-3">
        <label class="form-label required">Date of Birth</label>
        <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" 
               value="{{ old('date_of_birth', $patient->date_of_birth?->format('Y-m-d')) }}" required>
        @error('date_of_birth')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-3 mb-3">
        <label class="form-label required">Gender</label>
        <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
          <option value="">Select Gender</option>
          <option value="male" {{ old('gender', $patient->gender) == 'male' ? 'selected' : '' }}>Male</option>
          <option value="female" {{ old('gender', $patient->gender) == 'female' ? 'selected' : '' }}>Female</option>
          <option value="other" {{ old('gender', $patient->gender) == 'other' ? 'selected' : '' }}>Other</option>
        </select>
        @error('gender')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-3 mb-3">
        <label class="form-label">Blood Group</label>
        <select name="blood_group" class="form-select @error('blood_group') is-invalid @enderror">
          <option value="">Select Blood Group</option>
          <option value="A+" {{ old('blood_group', $patient->blood_group) == 'A+' ? 'selected' : '' }}>A+</option>
          <option value="A-" {{ old('blood_group', $patient->blood_group) == 'A-' ? 'selected' : '' }}>A-</option>
          <option value="B+" {{ old('blood_group', $patient->blood_group) == 'B+' ? 'selected' : '' }}>B+</option>
          <option value="B-" {{ old('blood_group', $patient->blood_group) == 'B-' ? 'selected' : '' }}>B-</option>
          <option value="O+" {{ old('blood_group', $patient->blood_group) == 'O+' ? 'selected' : '' }}>O+</option>
          <option value="O-" {{ old('blood_group', $patient->blood_group) == 'O-' ? 'selected' : '' }}>O-</option>
          <option value="AB+" {{ old('blood_group', $patient->blood_group) == 'AB+' ? 'selected' : '' }}>AB+</option>
          <option value="AB-" {{ old('blood_group', $patient->blood_group) == 'AB-' ? 'selected' : '' }}>AB-</option>
        </select>
        @error('blood_group')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-3 mb-3">
        <label class="form-label">Marital Status</label>
        <select name="marital_status" class="form-select @error('marital_status') is-invalid @enderror">
          <option value="">Select Status</option>
          <option value="single" {{ old('marital_status', $patient->marital_status) == 'single' ? 'selected' : '' }}>Single</option>
          <option value="married" {{ old('marital_status', $patient->marital_status) == 'married' ? 'selected' : '' }}>Married</option>
          <option value="divorced" {{ old('marital_status', $patient->marital_status) == 'divorced' ? 'selected' : '' }}>Divorced</option>
          <option value="widowed" {{ old('marital_status', $patient->marital_status) == 'widowed' ? 'selected' : '' }}>Widowed</option>
        </select>
        @error('marital_status')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-6 mb-3">
        <label class="form-label">Occupation</label>
        <input type="text" name="occupation" class="form-control @error('occupation') is-invalid @enderror" 
               value="{{ old('occupation', $patient->occupation) }}" placeholder="e.g., Teacher, Engineer">
        @error('occupation')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-6 mb-3">
        <label class="form-label">Photo</label>
        @if($patient->photo)
          <div class="current-photo">
            <i class="bi bi-image me-1"></i>Current photo on file
          </div>
        @endif
        <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" 
               accept="image/*" onchange="previewPhoto(event)">
        @error('photo')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="photo-preview" id="photoPreview">
          @if($patient->photo)
            <img src="{{ Storage::url($patient->photo) }}" alt="{{ $patient->full_name }}">
          @else
            <i class="bi bi-camera"></i>
          @endif
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
               value="{{ old('phone', $patient->phone) }}" required placeholder="+254 700 000000">
        @error('phone')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-6 mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
               value="{{ old('email', $patient->email) }}" placeholder="patient@example.com">
        @error('email')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-12 mb-3">
        <label class="form-label required">Address</label>
        <textarea name="address" rows="2" class="form-control @error('address') is-invalid @enderror" 
                  required>{{ old('address', $patient->address) }}</textarea>
        @error('address')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-4 mb-3">
        <label class="form-label required">City</label>
        <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" 
               value="{{ old('city', $patient->city) }}" required>
        @error('city')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-4 mb-3">
        <label class="form-label required">State/County</label>
        <input type="text" name="state" class="form-control @error('state') is-invalid @enderror" 
               value="{{ old('state', $patient->state) }}" required>
        @error('state')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-2 mb-3">
        <label class="form-label">Postal Code</label>
        <input type="text" name="postal_code" class="form-control @error('postal_code') is-invalid @enderror" 
               value="{{ old('postal_code', $patient->postal_code) }}">
        @error('postal_code')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-2 mb-3">
        <label class="form-label required">Country</label>
        <input type="text" name="country" class="form-control @error('country') is-invalid @enderror" 
               value="{{ old('country', $patient->country) }}" required>
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
               value="{{ old('emergency_contact_name', $patient->emergency_contact_name) }}" required>
        @error('emergency_contact_name')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-4 mb-3">
        <label class="form-label required">Contact Phone</label>
        <input type="tel" name="emergency_contact_phone" class="form-control @error('emergency_contact_phone') is-invalid @enderror" 
               value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone) }}" required>
        @error('emergency_contact_phone')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-4 mb-3">
        <label class="form-label required">Relationship</label>
        <input type="text" name="emergency_contact_relationship" class="form-control @error('emergency_contact_relationship') is-invalid @enderror" 
               value="{{ old('emergency_contact_relationship', $patient->emergency_contact_relationship) }}" required placeholder="e.g., Spouse, Parent">
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
                  placeholder="List any known allergies...">{{ old('allergies', $patient->allergies) }}</textarea>
        @error('allergies')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-6 mb-3">
        <label class="form-label">Chronic Conditions</label>
        <textarea name="chronic_conditions" rows="3" class="form-control @error('chronic_conditions') is-invalid @enderror" 
                  placeholder="e.g., Diabetes, Hypertension...">{{ old('chronic_conditions', $patient->chronic_conditions) }}</textarea>
        @error('chronic_conditions')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-6 mb-3">
        <label class="form-label">Current Medications</label>
        <textarea name="current_medications" rows="3" class="form-control @error('current_medications') is-invalid @enderror" 
                  placeholder="List current medications...">{{ old('current_medications', $patient->current_medications) }}</textarea>
        @error('current_medications')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-6 mb-3">
        <label class="form-label">Medical History</label>
        <textarea name="medical_history" rows="3" class="form-control @error('medical_history') is-invalid @enderror" 
                  placeholder="Previous surgeries, major illnesses...">{{ old('medical_history', $patient->medical_history) }}</textarea>
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
               value="{{ old('insurance_provider', $patient->insurance_provider) }}" placeholder="e.g., NHIF, AAR, Jubilee">
        @error('insurance_provider')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-6 mb-3">
        <label class="form-label">Insurance Number</label>
        <input type="text" name="insurance_number" class="form-control @error('insurance_number') is-invalid @enderror" 
               value="{{ old('insurance_number', $patient->insurance_number) }}" placeholder="Policy/Member Number">
        @error('insurance_number')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
    </div>
  </div>

  <!-- Form Actions -->
  <div class="d-flex gap-2 justify-content-between">
    <button type="button" class="btn btn-outline-danger" onclick="confirmDelete()">
      <i class="bi bi-trash me-2"></i>Archive Patient
    </button>
    <div class="d-flex gap-2">
      <a href="{{ route('patients.show', $patient) }}" class="btn btn-outline-secondary">
        <i class="bi bi-x-circle me-2"></i>Cancel
      </a>
      <button type="submit" class="btn btn-primary btn-submit">
        <i class="bi bi-check-circle me-2"></i>Update Patient
      </button>
    </div>
  </div>
</form>

<!-- Delete Form (Hidden) -->
<form id="deleteForm" action="{{ route('patients.destroy', $patient) }}" method="POST" style="display: none;">
  @csrf
  @method('DELETE')
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

function confirmDelete() {
  if (confirm('Are you sure you want to archive this patient? This action can be reversed later.')) {
    document.getElementById('deleteForm').submit();
  }
}
</script>
@endpush
@endsection
