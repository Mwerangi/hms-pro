@extends('layouts.app')

@section('title', 'User Management')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
<li class="breadcrumb-item active" aria-current="page">All Users</li>
@endsection

@push('styles')
<style>
  /* Stats Grid */
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
  }

  @media (max-width: 992px) {
    .stats-grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }

  @media (max-width: 576px) {
    .stats-grid {
      grid-template-columns: 1fr;
    }
  }

  .stat-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    transition: all 0.2s ease;
  }

  .stat-card:hover {
    border-color: #667eea;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.1);
  }

  .stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: #667eea;
    flex-shrink: 0;
  }

  .stat-content {
    flex: 1;
    min-width: 0;
  }

  .stat-value {
    font-size: 24px;
    font-weight: 700;
    color: #111827;
    line-height: 1.2;
    margin-bottom: 2px;
  }

  .stat-label {
    font-size: 12px;
    color: #6b7280;
    font-weight: 500;
    line-height: 1.2;
  }

  /* Page Header */
  .page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
  }

  .page-title {
    font-size: 22px;
    font-weight: 600;
    color: #111827;
    margin: 0;
  }

  /* Filter Section */
  .filter-section {
    background: white;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    border: 1px solid #e5e7eb;
  }

  .filter-row {
    display: grid;
    grid-template-columns: 1fr 200px 150px auto auto;
    gap: 12px;
    align-items: end;
  }

  @media (max-width: 992px) {
    .filter-row {
      grid-template-columns: 1fr;
      gap: 12px;
    }
  }

  .form-label {
    font-size: 12px;
    font-weight: 500;
    color: #6b7280;
    margin-bottom: 6px;
    display: block;
  }

  .form-control, .form-select {
    font-size: 13px;
    padding: 8px 12px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    transition: all 0.2s ease;
  }

  .form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
  }

  /* Table Container */
  .users-table-wrapper {
    background: white;
    border-radius: 10px;
    border: 1px solid #e5e7eb;
    overflow: hidden;
  }

  .table {
    margin-bottom: 0;
    width: 100%;
  }

  .table thead {
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
  }

  .table thead th {
    padding: 12px 16px;
    font-weight: 600;
    font-size: 11px;
    color: #6b7280;
    border: none;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
  }

  .table tbody td {
    padding: 16px;
    vertical-align: middle;
    color: #374151;
    border-bottom: 1px solid #f3f4f6;
    font-size: 13px;
  }

  .table tbody tr:last-child td {
    border-bottom: none;
  }

  .table tbody tr {
    transition: background 0.15s ease;
  }

  .table tbody tr:hover {
    background: #f9fafb;
  }

  /* User Info Cell */
  .user-info {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 220px;
  }

  .user-avatar-lg {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 15px;
    flex-shrink: 0;
  }

  .user-details {
    flex: 1;
    min-width: 0;
  }

  .user-details h6 {
    font-size: 14px;
    font-weight: 600;
    color: #111827;
    margin: 0 0 3px 0;
    line-height: 1.2;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .user-details p {
    font-size: 12px;
    color: #9ca3af;
    margin: 0 0 2px 0;
    line-height: 1.2;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .user-details small {
    font-size: 11px;
    color: #9ca3af;
    display: block;
    line-height: 1.2;
  }

  /* Role & Department Cells */
  .role-cell {
    min-width: 140px;
  }

  .role-name {
    font-size: 13px;
    font-weight: 600;
    color: #111827;
    display: block;
    margin-bottom: 3px;
    line-height: 1.2;
  }

  .department-cell {
    min-width: 120px;
  }

  .department-name {
    font-size: 13px;
    font-weight: 500;
    color: #374151;
    display: block;
    line-height: 1.2;
  }

  .phone-cell {
    min-width: 110px;
    font-size: 13px;
    color: #6b7280;
  }

  .login-cell {
    min-width: 130px;
  }

  .login-date {
    font-size: 13px;
    color: #374151;
    font-weight: 500;
    display: block;
    margin-bottom: 2px;
    line-height: 1.2;
  }

  .login-time {
    font-size: 11px;
    color: #9ca3af;
    line-height: 1.2;
  }

  /* Badges */
  .badge {
    padding: 5px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
    border: none;
    display: inline-block;
  }

  .role-badge {
    font-size: 11px;
    padding: 5px 10px;
    border-radius: 6px;
    font-weight: 600;
    letter-spacing: 0.3px;
    display: inline-block;
  }

  .role-badge.role-super-admin { background: #667eea; color: white; }
  .role-badge.role-admin { background: #667eea; color: white; }
  .role-badge.role-doctor { background: #00a19e; color: white; }
  .role-badge.role-nurse { background: #48bb78; color: white; }
  .role-badge.role-receptionist { background: #4299e1; color: white; }
  .role-badge.role-pharmacist { background: #ed8936; color: white; }
  .role-badge.role-lab-technician { background: #9f7aea; color: white; }
  .role-badge.role-radiologist { background: #f687b3; color: white; }
  .role-badge.role-accountant { background: #f6ad55; color: white; }

  .badge.bg-success {
    background: #10b981 !important;
    color: white;
  }

  .badge.bg-secondary {
    background: #e5e7eb !important;
    color: #6b7280;
  }

  .specialization-text {
    font-size: 11px;
    color: #9ca3af;
    margin-top: 4px;
    display: block;
    line-height: 1.2;
  }

  /* Action Buttons */
  .action-buttons {
    display: flex;
    gap: 6px;
    justify-content: flex-end;
  }

  .btn-sm { 
    padding: 6px 10px;
    font-size: 13px;
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.2s ease;
    border-width: 1px;
  }

  .btn-sm i {
    font-size: 14px;
  }

  .btn-outline-primary {
    border-color: #d1d5db;
    color: #667eea;
    background: white;
  }

  .btn-outline-primary:hover {
    background: #667eea;
    border-color: #667eea;
    color: white;
  }

  .btn-outline-warning {
    border-color: #d1d5db;
    color: #f59e0b;
    background: white;
  }

  .btn-outline-warning:hover {
    background: #f59e0b;
    border-color: #f59e0b;
    color: white;
  }

  .btn-outline-danger {
    border-color: #d1d5db;
    color: #ef4444;
    background: white;
  }

  .btn-outline-danger:hover {
    background: #ef4444;
    border-color: #ef4444;
    color: white;
  }

  .btn-primary {
    background: #667eea;
    border-color: #667eea;
    color: white;
  }

  .btn-primary:hover {
    background: #5568d3;
    border-color: #5568d3;
  }

  .btn-outline-secondary {
    border-color: #d1d5db;
    color: #6b7280;
    background: white;
  }

  .btn-outline-secondary:hover {
    background: #f3f4f6;
    border-color: #9ca3af;
    color: #374151;
  }

  /* Empty State */
  .empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #9ca3af;
  }

  .empty-state i {
    font-size: 56px;
    color: #d1d5db;
    margin-bottom: 16px;
  }

  .empty-state p {
    font-size: 14px;
    margin-bottom: 16px;
  }

  /* Pagination */
  .pagination-wrapper {
    padding: 16px 20px;
    border-top: 1px solid #e5e7eb;
    background: #f9fafb;
  }
</style>
@endpush

@section('content')
<!-- Stats Grid -->
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon">
      <i class="bi bi-people"></i>
    </div>
    <div class="stat-content">
      <div class="stat-value">{{ $stats['total'] }}</div>
      <div class="stat-label">Total Users</div>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon">
      <i class="bi bi-check-circle"></i>
    </div>
    <div class="stat-content">
      <div class="stat-value">{{ $stats['active'] }}</div>
      <div class="stat-label">Active Users</div>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon">
      <i class="bi bi-x-circle"></i>
    </div>
    <div class="stat-content">
      <div class="stat-value">{{ $stats['inactive'] }}</div>
      <div class="stat-label">Inactive</div>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon">
      <i class="bi bi-person-plus"></i>
    </div>
    <div class="stat-content">
      <div class="stat-value">{{ $stats['new_this_month'] }}</div>
      <div class="stat-label">New This Month</div>
    </div>
  </div>
</div>

<!-- Page Header -->
<div class="page-header">
  <h1 class="page-title">User Management</h1>
  <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
    <i class="bi bi-plus-circle me-1"></i>Add New User
  </a>
</div>

<!-- Filters -->
<div class="filter-section">
  <form action="{{ route('users.index') }}" method="GET">
    <div class="filter-row">
      <div>
        <label class="form-label">Search</label>
        <input type="text" name="search" class="form-control" placeholder="Search by name, email or ID..." value="{{ request('search') }}">
      </div>
      
      <div>
        <label class="form-label">Role</label>
        <select name="role" class="form-select">
          <option value="">All Roles</option>
          @foreach($roles as $role)
          <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
            {{ ucwords(str_replace('-', ' ', $role->name)) }}
          </option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
          <option value="">All</option>
          <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
          <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary btn-sm" style="height: 37px;">
        <i class="bi bi-search me-1"></i>Filter
      </button>

      @if(request()->hasAny(['search', 'role', 'status']))
      <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm" style="height: 37px;">
        <i class="bi bi-x-circle me-1"></i>Clear
      </a>
      @endif
    </div>
  </form>
</div>

<!-- Users Table -->
<div class="users-table-wrapper">
  @if($users->count() > 0)
  <table class="table">
    <thead>
      <tr>
        <th>User</th>
        <th>Role</th>
        <th>Department</th>
        <th>Phone</th>
        <th>Last Login</th>
        <th>Status</th>
        <th style="text-align: right;">Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($users as $user)
      <tr>
        <td>
          <div class="user-info">
            <div class="user-avatar-lg">
              {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="user-details">
              <h6>{{ $user->name }}</h6>
              <p>{{ $user->email }}</p>
              @if($user->employee_id)
                <small>ID: {{ $user->employee_id }}</small>
              @endif
            </div>
          </div>
        </td>
        <td class="role-cell">
          @php
            $role = $user->roles->first();
            $roleName = $role ? $role->name : 'user';
          @endphp
          <span class="role-badge role-{{ $roleName }}">
            {{ ucwords(str_replace('-', ' ', $roleName)) }}
          </span>
          @if($user->specialization)
            <span class="specialization-text">{{ $user->specialization }}</span>
          @endif
        </td>
        <td class="department-cell">
          <span class="department-name">{{ $user->department?->name ?? '-' }}</span>
        </td>
        <td class="phone-cell">
          {{ $user->phone ?? '-' }}
        </td>
        <td class="login-cell">
          @if($user->last_login_at)
            <span class="login-date">{{ $user->last_login_at->format('M d, Y') }}</span>
            <span class="login-time">{{ $user->last_login_at->diffForHumans() }}</span>
          @else
            <span class="text-muted">Never</span>
          @endif
        </td>
        <td>
          @if($user->is_active)
          <span class="badge bg-success">Active</span>
          @else
          <span class="badge bg-secondary">Inactive</span>
          @endif
        </td>
        <td>
          <div class="action-buttons">
            <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-outline-primary" title="View Details">
              <i class="bi bi-eye"></i>
            </a>
            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-warning" title="Edit User">
              <i class="bi bi-pencil"></i>
            </a>
            @if($user->id !== auth()->id())
            <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" 
                  onsubmit="return confirm('Are you sure you want to delete this user?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete User">
                <i class="bi bi-trash"></i>
              </button>
            </form>
            @endif
          </div>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div class="pagination-wrapper">
    {{ $users->links() }}
  </div>
  @else
  <div class="empty-state">
    <i class="bi bi-people"></i>
    <p>No users found</p>
    @if(request()->hasAny(['search', 'role', 'status']))
    <a href="{{ route('users.index') }}" class="btn btn-sm btn-primary">Clear Filters</a>
    @endif
  </div>
  @endif
</div>
@endsection
