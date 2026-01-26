@extends('layouts.app')

@section('title', 'Create Role')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('settings.index') }}">Settings</a></li>
<li class="breadcrumb-item"><a href="{{ route('settings.category', ['category' => 'roles']) }}">Roles & Permissions</a></li>
<li class="breadcrumb-item active" aria-current="page">Create Role</li>
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
    padding: 32px;
    margin-bottom: 24px;
  }

  .form-section-title {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin: 0 0 20px 0;
    padding-bottom: 12px;
    border-bottom: 1px solid #e5e7eb;
  }

  .form-label {
    display: block;
    font-size: 13px;
    font-weight: 500;
    color: #374151;
    margin-bottom: 6px;
  }

  .form-control, .form-select {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    color: #111827;
    transition: all 0.2s;
  }

  .form-control:focus, .form-select:focus {
    outline: none;
    border-color: #374151;
    box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.05);
  }

  textarea.form-control {
    resize: vertical;
    min-height: 80px;
  }

  .permissions-grid {
    display: grid;
    gap: 20px;
  }

  .permission-module {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 20px;
  }

  .module-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
  }

  .module-title {
    font-size: 15px;
    font-weight: 600;
    color: #111827;
    margin: 0;
  }

  .select-all-btn {
    font-size: 12px;
    padding: 4px 10px;
    background: white;
    color: #374151;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
  }

  .select-all-btn:hover {
    background: #f3f4f6;
    border-color: #9ca3af;
  }

  .permissions-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 12px;
  }

  .permission-item {
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .permission-checkbox {
    width: 18px;
    height: 18px;
    border-radius: 4px;
    border: 2px solid #d1d5db;
    cursor: pointer;
    transition: all 0.2s;
  }

  .permission-checkbox:checked {
    background: #374151;
    border-color: #374151;
  }

  .permission-label {
    font-size: 13px;
    color: #374151;
    cursor: pointer;
    user-select: none;
  }

  .btn-group {
    display: flex;
    gap: 12px;
    margin-top: 32px;
    padding-top: 24px;
    border-top: 1px solid #e5e7eb;
  }

  .btn-primary-custom {
    background: #111827;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .btn-primary-custom:hover {
    background: #1f2937;
  }

  .btn-secondary {
    background: white;
    color: #374151;
    border: 1px solid #e5e7eb;
    padding: 12px 24px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.2s;
  }

  .btn-secondary:hover {
    background: #f9fafb;
    color: #111827;
  }

  .help-text {
    font-size: 12px;
    color: #6b7280;
    margin-top: 4px;
  }
</style>
@endpush

@section('content')
<div class="page-header">
  <h1 class="page-title">Create New Role</h1>
  <p class="page-subtitle">Define a new role and assign permissions</p>
</div>

<form action="{{ route('settings.roles.store') }}" method="POST">
  @csrf

  <div class="form-card">
    <h2 class="form-section-title">Role Information</h2>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" 
               id="name" name="name" value="{{ old('name') }}" 
               placeholder="e.g., Ward Manager" required>
        <div class="help-text">Use a descriptive name for the role (e.g., Ward Manager, Senior Doctor)</div>
        @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-6 mb-3">
        <label for="display_name" class="form-label">Display Name</label>
        <input type="text" class="form-control @error('display_name') is-invalid @enderror" 
               id="display_name" name="display_name" value="{{ old('display_name') }}"
               placeholder="Optional display name">
        <div class="help-text">Optional: A user-friendly display name</div>
        @error('display_name')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
    </div>

    <div class="mb-3">
      <label for="description" class="form-label">Description</label>
      <textarea class="form-control @error('description') is-invalid @enderror" 
                id="description" name="description" rows="3" 
                placeholder="Describe the purpose and responsibilities of this role">{{ old('description') }}</textarea>
      <div class="help-text">Provide a brief description of this role's purpose</div>
      @error('description')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>
  </div>

  <div class="form-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
      <h2 class="form-section-title" style="margin: 0; padding: 0; border: 0;">Assign Permissions</h2>
      <button type="button" class="select-all-btn" onclick="toggleAllPermissions()">
        <i class="bi bi-check-all"></i> Select All
      </button>
    </div>

    <div class="permissions-grid">
      @foreach($permissions as $moduleName => $modulePermissions)
      <div class="permission-module">
        <div class="module-header">
          <h3 class="module-title">
            <i class="bi bi-folder2-open me-2"></i>{{ $moduleName }}
          </h3>
          <button type="button" class="select-all-btn" onclick="toggleModulePermissions('module-{{ $loop->index }}')">
            Select All
          </button>
        </div>

        <div class="permissions-list" data-module="module-{{ $loop->index }}">
          @foreach($modulePermissions as $permission)
          <div class="permission-item">
            <input type="checkbox" 
                   class="permission-checkbox" 
                   name="permissions[]" 
                   value="{{ $permission->name }}" 
                   id="permission-{{ $permission->id }}"
                   {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}>
            <label for="permission-{{ $permission->id }}" class="permission-label">
              {{ ucwords(str_replace(['.', '-', '_'], ' ', explode('.', $permission->name)[1] ?? $permission->name)) }}
            </label>
          </div>
          @endforeach
        </div>
      </div>
      @endforeach
    </div>
  </div>

  <div class="btn-group">
    <button type="submit" class="btn-primary-custom">
      <i class="bi bi-check-circle me-2"></i>Create Role
    </button>
    <a href="{{ route('settings.category', ['category' => 'roles']) }}" class="btn-secondary">
      <i class="bi bi-x-circle me-2"></i>Cancel
    </a>
  </div>
</form>

@push('scripts')
<script>
  function toggleAllPermissions() {
    const checkboxes = document.querySelectorAll('.permission-checkbox');
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    
    checkboxes.forEach(checkbox => {
      checkbox.checked = !allChecked;
    });
  }

  function toggleModulePermissions(moduleId) {
    const module = document.querySelector(`[data-module="${moduleId}"]`);
    const checkboxes = module.querySelectorAll('.permission-checkbox');
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    
    checkboxes.forEach(checkbox => {
      checkbox.checked = !allChecked;
    });
  }
</script>
@endpush
@endsection
