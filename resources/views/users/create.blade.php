@extends('layouts.app')

@section('title', 'Add New User')

@push('styles')
<style>
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
    margin: 0;
  }

  .form-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    border: 1px solid #e5e7eb;
  }

  .section-title {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 1px solid #e5e7eb;
  }

  .btn-sm { padding: 6px 12px; font-size: 13px; }
</style>
@endpush

@section('content')
<div class="page-header">
  <h1 class="page-title">Add New User</h1>
  <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">
    <i class="bi bi-arrow-left me-2"></i>Back
  </a>
</div>

<div class="row">
  <div class="col-lg-8">
    <div class="form-card">
      <h5 class="section-title">User Information</h5>

      <form action="{{ route('users.store') }}" method="POST">
        @csrf

        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="name" class="form-label">
              Full Name <span class="text-danger">*</span>
            </label>
            <input type="text" 
                   class="form-control @error('name') is-invalid @enderror" 
                   id="name" 
                   name="name" 
                   value="{{ old('name') }}" 
                   required>
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-md-6 mb-3">
            <label for="email" class="form-label">
              Email Address <span class="required-indicator">*</span>
            </label>
            <input type="email" 
                   class="form-control @error('email') is-invalid @enderror" 
                   id="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required>
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="password" class="form-label">
              Password <span class="required-indicator">*</span>
            </label>
            <input type="password" 
                   class="form-control @error('password') is-invalid @enderror" 
                   id="password" 
                   name="password" 
                   required>
            @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">Minimum 6 characters</small>
          </div>

          <div class="col-md-6 mb-3">
            <label for="password_confirmation" class="form-label">
              Confirm Password <span class="required-indicator">*</span>
            </label>
            <input type="password" 
                   class="form-control" 
                   id="password_confirmation" 
                   name="password_confirmation" 
                   required>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="role" class="form-label">
              Role <span class="required-indicator">*</span>
            </label>
            <select class="form-select @error('role') is-invalid @enderror" 
                    id="role" 
                    name="role" 
                    required>
              <option value="">Select Role</option>
              @foreach($roles as $role)
              <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                {{ ucwords(str_replace('-', ' ', $role->name)) }}
              </option>
              @endforeach
            </select>
            @error('role')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-md-6 mb-3">
            <label for="phone" class="form-label">Phone Number</label>
            <input type="text" 
                   class="form-control @error('phone') is-invalid @enderror" 
                   id="phone" 
                   name="phone" 
                   value="{{ old('phone') }}" 
                   placeholder="+1 (555) 123-4567">
            @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <h5 class="section-title mt-4">Additional Information</h5>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="employee_id" class="form-label">Employee ID</label>
            <input type="text" 
                   class="form-control @error('employee_id') is-invalid @enderror" 
                   id="employee_id" 
                   name="employee_id" 
                   value="{{ old('employee_id') }}" 
                   placeholder="EMP-001">
            @error('employee_id')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="branch_id" class="form-label">Branch</label>
            <select class="form-select @error('branch_id') is-invalid @enderror" 
                    id="branch_id" 
                    name="branch_id">
              <option value="">Select Branch (Optional)</option>
              @foreach($branches as $branch)
                <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                  {{ $branch->name }}
                </option>
              @endforeach
            </select>
            @error('branch_id')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-md-6 mb-3">
            <label for="department_id" class="form-label">Department</label>
            <select class="form-select @error('department_id') is-invalid @enderror" 
                    id="department_id" 
                    name="department_id">
              <option value="">Select Department (Optional)</option>
              @foreach($departments as $department)
                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                  {{ $department->name }}
                </option>
              @endforeach
            </select>
            @error('department_id')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="specialization" class="form-label">Specialization</label>
            <input type="text" 
                   class="form-control @error('specialization') is-invalid @enderror" 
                   id="specialization" 
                   name="specialization" 
                   value="{{ old('specialization') }}" 
                   placeholder="e.g., General Physician, Surgeon">
            @error('specialization')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-md-6 mb-3">
            <label for="license_number" class="form-label">License Number</label>
            <input type="text" 
                   class="form-control @error('license_number') is-invalid @enderror" 
                   id="license_number" 
                   name="license_number" 
                   value="{{ old('license_number') }}" 
                   placeholder="Medical License #">
            @error('license_number')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="gender" class="form-label">Gender</label>
            <select class="form-select @error('gender') is-invalid @enderror" 
                    id="gender" 
                    name="gender">
              <option value="">Select Gender</option>
              <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
              <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
              <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
            </select>
            @error('gender')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-md-6 mb-3">
            <label for="date_of_joining" class="form-label">Date of Joining</label>
            <input type="date" 
                   class="form-control @error('date_of_joining') is-invalid @enderror" 
                   id="date_of_joining" 
                   name="date_of_joining" 
                   value="{{ old('date_of_joining') }}">
            @error('date_of_joining')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="mb-3">
          <label for="address" class="form-label">Address</label>
          <textarea class="form-control @error('address') is-invalid @enderror" 
                    id="address" 
                    name="address" 
                    rows="3" 
                    placeholder="Enter full address">{{ old('address') }}</textarea>
          @error('address')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <div class="form-check">
            <input class="form-check-input" 
                   type="checkbox" 
                   id="is_active" 
                   name="is_active" 
                   value="1" 
                   {{ old('is_active', true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">
              Active (User can login)
            </label>
          </div>
        </div>

        <div class="d-flex gap-2 mt-4 pt-3" style="border-top: 1px solid #e5e7eb;">
          <button type="submit" class="btn btn-primary btn-sm">
            <i class="bi bi-check-circle me-2"></i>Create User
          </button>
          <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">
            Cancel
          </a>
        </div>
      </form>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="form-card">
      <h5 class="section-title">Role Descriptions</h5>
      
      <div style="font-size: 13px;">
        <div class="mb-3">
          <strong class="d-block mb-1" style="color: #111827;">Super Admin</strong>
          <span class="text-muted">Full system access and control</span>
        </div>
        <div class="mb-3">
          <strong class="d-block mb-1" style="color: #111827;">Admin</strong>
          <span class="text-muted">Administrative privileges</span>
        </div>
        <div class="mb-3">
          <strong class="d-block mb-1" style="color: #111827;">Doctor</strong>
          <span class="text-muted">Medical staff with patient care access</span>
        </div>
        <div class="mb-3">
          <strong class="d-block mb-1" style="color: #111827;">Nurse</strong>
          <span class="text-muted">Nursing staff with patient monitoring</span>
        </div>
        <div class="mb-3">
          <strong class="d-block mb-1" style="color: #111827;">Receptionist</strong>
          <span class="text-muted">Front desk and appointment management</span>
        </div>
        <div class="mb-3">
          <strong class="d-block mb-1" style="color: #111827;">Pharmacist</strong>
          <span class="text-muted">Pharmacy and medication management</span>
        </div>
        <div class="mb-3">
          <strong class="d-block mb-1" style="color: #111827;">Lab Technician</strong>
          <span class="text-muted">Laboratory tests and results</span>
        </div>
        <div class="mb-3">
          <strong class="d-block mb-1" style="color: #111827;">Radiologist</strong>
          <span class="text-muted">Radiology and imaging services</span>
        </div>
        <div class="mb-0">
          <strong class="d-block mb-1" style="color: #111827;">Accountant</strong>
          <span class="text-muted">Financial and billing management</span>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
