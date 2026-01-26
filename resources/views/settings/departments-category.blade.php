@extends('layouts.app')

@section('title', 'Departments - Settings')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('settings.index') }}">Settings</a></li>
<li class="breadcrumb-item active" aria-current="page">Departments</li>
@endsection

@push('styles')
<style>
  .settings-container {
    display: flex;
    gap: 0;
    min-height: calc(100vh - 200px);
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  }

  .settings-sidebar {
    width: 280px;
    background: #f9fafb;
    border-right: 1px solid #e5e7eb;
    padding: 24px 0;
    flex-shrink: 0;
  }

  .sidebar-title {
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    color: #6b7280;
    padding: 0 24px;
    margin-bottom: 12px;
    letter-spacing: 0.5px;
  }

  .sidebar-nav {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .sidebar-nav-item {
    margin: 0;
  }

  .sidebar-nav-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 24px;
    color: #374151;
    text-decoration: none;
    transition: all 0.2s;
    border-left: 3px solid transparent;
  }

  .sidebar-nav-link:hover {
    background: #f3f4f6;
    color: #111827;
  }

  .sidebar-nav-link.active {
    background: #f3f4f6;
    color: #111827;
    border-left-color: #374151;
    font-weight: 600;
  }

  .sidebar-icon {
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
  }

  .sidebar-text {
    flex: 1;
    font-size: 14px;
  }

  .settings-content {
    flex: 1;
    padding: 32px 40px;
    overflow-y: auto;
  }

  .content-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 32px;
  }

  .content-title {
    font-size: 24px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 8px;
  }

  .content-description {
    font-size: 14px;
    color: #6b7280;
    margin: 0;
  }

  .btn-primary-custom {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: #667eea;
    color: white;
    border-radius: 8px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.2s;
  }

  .btn-primary-custom:hover {
    background: #5568d3;
    color: white;
  }

  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 16px;
    margin-bottom: 32px;
  }

  .stat-card {
    background: white;
    padding: 16px;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
  }

  .stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    margin-bottom: 10px;
  }

  .stat-value {
    font-size: 24px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 4px;
  }

  .stat-label {
    font-size: 12px;
    color: #6b7280;
  }

  .departments-table {
    background: white;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    overflow: hidden;
  }

  .departments-table table {
    width: 100%;
    border-collapse: collapse;
  }

  .departments-table thead {
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
  }

  .departments-table th {
    padding: 12px 16px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .departments-table td {
    padding: 16px;
    border-bottom: 1px solid #f3f4f6;
    font-size: 14px;
  }

  .departments-table tr:last-child td {
    border-bottom: none;
  }

  .department-name {
    font-weight: 600;
    color: #111827;
    margin-bottom: 4px;
  }

  .department-code {
    display: inline-block;
    padding: 2px 8px;
    background: #f3f4f6;
    color: #6b7280;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 500;
  }

  .department-info {
    font-size: 13px;
    color: #6b7280;
  }

  .badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
  }

  .badge-active {
    background: #d1fae5;
    color: #065f46;
  }

  .badge-inactive {
    background: #f3f4f6;
    color: #6b7280;
  }

  .btn-actions {
    display: flex;
    gap: 8px;
  }

  .btn-icon {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    color: #6b7280;
  }

  .btn-icon:hover {
    background: #f9fafb;
    color: #111827;
    border-color: #d1d5db;
  }

  .empty-state {
    text-align: center;
    padding: 60px 20px;
  }

  .empty-icon {
    font-size: 48px;
    color: #e5e7eb;
    margin-bottom: 16px;
  }

  .empty-text {
    font-size: 14px;
    color: #6b7280;
  }

  .form-group {
    margin-bottom: 20px;
  }

  .form-label {
    display: block;
    font-size: 14px;
    font-weight: 500;
    color: #374151;
    margin-bottom: 8px;
  }

  .form-control {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.2s;
  }

  .form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
  }
</style>
@endpush

@section('content')
<div class="settings-container">
  <!-- Sidebar Navigation -->
  <div class="settings-sidebar">
    <div class="sidebar-title">Configuration</div>
    <ul class="sidebar-nav">
      <li class="sidebar-nav-item">
        <a href="{{ route('settings.index', ['category' => 'general']) }}" class="sidebar-nav-link">
          <span class="sidebar-icon"><i class="bi bi-gear"></i></span>
          <span class="sidebar-text">General Settings</span>
        </a>
      </li>
      <li class="sidebar-nav-item">
        <a href="{{ route('settings.index', ['category' => 'patient']) }}" class="sidebar-nav-link">
          <span class="sidebar-icon"><i class="bi bi-person-badge"></i></span>
          <span class="sidebar-text">Patient Management</span>
        </a>
      </li>
      <li class="sidebar-nav-item">
        <a href="{{ route('settings.index', ['category' => 'appointment']) }}" class="sidebar-nav-link">
          <span class="sidebar-icon"><i class="bi bi-calendar-check"></i></span>
          <span class="sidebar-text">Appointments</span>
        </a>
      </li>
      <li class="sidebar-nav-item">
        <a href="{{ route('settings.index', ['category' => 'billing']) }}" class="sidebar-nav-link">
          <span class="sidebar-icon"><i class="bi bi-receipt"></i></span>
          <span class="sidebar-text">Billing & Payment</span>
        </a>
      </li>
      <li class="sidebar-nav-item">
        <a href="{{ route('settings.index', ['category' => 'pharmacy']) }}" class="sidebar-nav-link">
          <span class="sidebar-icon"><i class="bi bi-capsule"></i></span>
          <span class="sidebar-text">Pharmacy</span>
        </a>
      </li>
      <li class="sidebar-nav-item">
        <a href="{{ route('settings.index', ['category' => 'laboratory']) }}" class="sidebar-nav-link">
          <span class="sidebar-icon"><i class="bi bi-clipboard2-pulse"></i></span>
          <span class="sidebar-text">Laboratory</span>
        </a>
      </li>
      <li class="sidebar-nav-item">
        <a href="{{ route('settings.index', ['category' => 'notifications']) }}" class="sidebar-nav-link">
          <span class="sidebar-icon"><i class="bi bi-bell"></i></span>
          <span class="sidebar-text">Notifications</span>
        </a>
      </li>
      <li class="sidebar-nav-item">
        <a href="{{ route('settings.index', ['category' => 'security']) }}" class="sidebar-nav-link">
          <span class="sidebar-icon"><i class="bi bi-shield-lock"></i></span>
          <span class="sidebar-text">Security</span>
        </a>
      </li>
    </ul>

    <div class="sidebar-title" style="margin-top: 32px;">Access Control</div>
    <ul class="sidebar-nav">
      <li class="sidebar-nav-item">
        <a href="{{ route('settings.category', ['category' => 'roles']) }}" class="sidebar-nav-link">
          <span class="sidebar-icon"><i class="bi bi-shield-check"></i></span>
          <span class="sidebar-text">Roles & Permissions</span>
        </a>
      </li>
    </ul>

    <div class="sidebar-title" style="margin-top: 32px;">Hospital Structure</div>
    <ul class="sidebar-nav">
      <li class="sidebar-nav-item">
        <a href="{{ route('settings.category', ['category' => 'branches']) }}" class="sidebar-nav-link">
          <span class="sidebar-icon"><i class="bi bi-building"></i></span>
          <span class="sidebar-text">Branches</span>
        </a>
      </li>
      <li class="sidebar-nav-item">
        <a href="{{ route('settings.category', ['category' => 'departments']) }}" class="sidebar-nav-link active">
          <span class="sidebar-icon"><i class="bi bi-diagram-3"></i></span>
          <span class="sidebar-text">Departments</span>
        </a>
      </li>
    </ul>
  </div>

  <!-- Main Content -->
  <div class="settings-content">
    <div class="content-header">
      <div>
        <h1 class="content-title">Hospital Departments</h1>
        <p class="content-description">Manage hospital departments and units</p>
      </div>
      <button type="button" class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#createDepartmentModal">
        <i class="bi bi-plus-circle"></i> Add Department
      </button>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Statistics -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon" style="background: #f3f4f6; color: #667eea;">
          <i class="bi bi-building"></i>
        </div>
        <div class="stat-value">{{ $stats['total_departments'] }}</div>
        <div class="stat-label">Total Departments</div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background: #f3f4f6; color: #667eea;">
          <i class="bi bi-check-circle"></i>
        </div>
        <div class="stat-value">{{ $stats['active_departments'] }}</div>
        <div class="stat-label">Active Departments</div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background: #f3f4f6; color: #667eea;">
          <i class="bi bi-building"></i>
        </div>
        <div class="stat-value">{{ \App\Models\Branch::count() }}</div>
        <div class="stat-label">Total Branches</div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background: #f3f4f6; color: #667eea;">
          <i class="bi bi-people"></i>
        </div>
        <div class="stat-value">{{ $stats['total_staff'] }}</div>
        <div class="stat-label">Staff Assigned</div>
      </div>
    </div>

    <!-- Departmentes Table -->
    <div class="departments-table">
      @if($departments->count() > 0)
      <table>
        <thead>
          <tr>
            <th>DEPARTMENT</th>
            <th>BRANCH</th>
            <th>DESCRIPTION</th>
            <th>STAFF</th>
            <th>STATUS</th>
            <th style="text-align: right;">ACTIONS</th>
          </tr>
        </thead>
        <tbody>
          @foreach($departments as $department)
          <tr>
            <td>
              <div class="department-name">{{ $department->name }}</div>
              <span class="department-code">{{ $department->code }}</span>
            </td>
            <td>
              @if($department->branch)
                <span class="text-dark">{{ $department->branch->name }}</span>
              @else
                <span class="text-muted">—</span>
              @endif
            </td>
            <td>
              @if($department->description)
                <div class="department-info">{{ Str::limit($department->description, 50) }}</div>
              @else
                <span class="text-muted">—</span>
              @endif
            </td>
            <td>{{ $department->users_count }}</td>
            <td>
              @if($department->is_active)
                <span class="badge badge-active">Active</span>
              @else
                <span class="badge badge-inactive">Inactive</span>
              @endif
            </td>
            <td style="text-align: right;">
              <div class="btn-actions">
                <button type="button" class="btn-icon" data-bs-toggle="modal" data-bs-target="#editDepartmentModal{{ $department->id }}" title="Edit">
                  <i class="bi bi-pencil"></i>
                </button>
                <button type="button" class="btn-icon" data-bs-toggle="modal" data-bs-target="#deleteDepartmentModal{{ $department->id }}" title="Delete">
                  <i class="bi bi-trash"></i>
                </button>
              </div>
            </td>
          </tr>

          <!-- Edit Department Modal -->
          <div class="modal fade" id="editDepartmentModal{{ $department->id }}" tabindex="-1">
            <div class="modal-dialog">
              <div class="modal-content">
                <form action="{{ route('settings.departments.update', $department) }}" method="POST" class="form-loading">
                  @csrf
                  @method('PUT')
                  <div class="modal-header">
                    <h5 class="modal-title">Edit Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-md-8">
                        <div class="form-group">
                          <label class="form-label">Department Name <span class="text-danger">*</span></label>
                          <input type="text" name="name" class="form-control" value="{{ $department->name }}" required>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="form-label">Code <span class="text-danger">*</span></label>
                          <input type="text" name="code" class="form-control" value="{{ $department->code }}" required>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="form-label">Branch</label>
                      <select name="branch_id" class="form-select">
                        <option value="">Select Branch (Optional)</option>
                        @foreach($branches as $branch)
                          <option value="{{ $branch->id }}" {{ $department->branch_id == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group">
                      <label class="form-label">Description</label>
                      <textarea name="description" class="form-control" rows="2">{{ $department->description }}</textarea>
                    </div>
                    <div class="form-group mb-0">
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $department->is_active ? 'checked' : '' }}>
                        <label class="form-check-label">Active</label>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Department</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          @endforeach
        </tbody>
      </table>
      @else
      <div class="empty-state">
        <div class="empty-icon">
          <i class="bi bi-building"></i>
        </div>
        <div class="empty-text">No departments found. Click "Add Department" to create your first department.</div>
      </div>
      @endif
    </div>
  </div>
</div>

<!-- Delete Confirmation Modals -->
@foreach($departments as $department)
<div class="modal fade" id="deleteDepartmentModal{{ $department->id }}" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('settings.departments.destroy', $department) }}" method="POST" class="form-loading">
        @csrf
        @method('DELETE')
        <div class="modal-header">
          <h5 class="modal-title">Delete Department</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="alert alert-warning mb-3">
            <i class="bi bi-exclamation-triangle"></i> <strong>Warning:</strong> This action cannot be undone.
          </div>
          <p>Are you sure you want to delete the following department?</p>
          <div class="card bg-light">
            <div class="card-body">
              <h6 class="mb-2">{{ $department->name }}</h6>
              <div class="text-muted small">
                <div><strong>Code:</strong> {{ $department->code }}</div>
                @if($department->branch)
                <div><strong>Branch:</strong> {{ $department->branch->name }}</div>
                @endif
                @if($department->description)
                <div><strong>Description:</strong> {{ Str::limit($department->description, 100) }}</div>
                @endif
                <div class="mt-2">
                  <span class="badge bg-success">{{ $department->users_count }} Staff Member(s)</span>
                </div>
              </div>
            </div>
          </div>
          @if($department->users_count > 0)
          <p class="text-danger mt-3 mb-0">
            <small><i class="bi bi-info-circle"></i> Note: This department has assigned staff members. Deleting it may affect existing records.</small>
          </p>
          @endif
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Delete Department</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach

<!-- Create Department Modal -->
<div class="modal fade" id="createDepartmentModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('settings.departments.store') }}" method="POST" class="form-loading">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add New Department</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-8">
              <div class="form-group">
                <label class="form-label">Department Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Code <span class="text-danger">*</span></label>
                <input type="text" name="code" class="form-control" placeholder="e.g., CARD" required>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Branch</label>
            <select name="branch_id" class="form-select">
              <option value="">Select Branch (Optional)</option>
              @foreach($branches as $branch)
                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="2"></textarea>
          </div>
          <div class="form-group mb-0">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
              <label class="form-check-label">Active</label>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Create Department</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
