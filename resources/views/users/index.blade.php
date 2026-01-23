@extends('layouts.app')

@section('title', 'User Management')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
<li class="breadcrumb-item active" aria-current="page">All Users</li>
@endsection

@push('styles')
<style>
  /* Modern Minimalistic Design */
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
  }

  .stat-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px;
  }

  .stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: #667eea;
    margin-bottom: 14px;
  }

  .stat-value {
    font-size: 28px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 4px;
  }

  .stat-label {
    font-size: 13px;
    color: #6b7280;
    font-weight: 500;
  }

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

  .filter-section {
    background: white;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
    border: 1px solid #e5e7eb;
  }

  .filter-row {
    display: flex;
    gap: 15px;
    align-items: end;
  }

  .users-table-wrapper {
    background: transparent;
    border-radius: 0;
    border: none;
    overflow: visible;
  }

  .table {
    margin-bottom: 0;
    border-collapse: separate;
    border-spacing: 0 8px;
  }

  .table thead {
    position: sticky;
    top: 0;
    z-index: 10;
  }

  .table thead th {
    background: transparent;
    padding: 8px 16px;
    font-weight: 600;
    font-size: 11px;
    color: #9ca3af;
    border: none;
    text-transform: uppercase;
    letter-spacing: 0.8px;
  }

  .table tbody td {
    background: white;
    padding: 10px 16px;
    vertical-align: middle;
    color: #374151;
    border: none;
    font-size: 13px;
  }

  .table tbody tr {
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    border-radius: 10px;
    transition: all 0.2s ease;
  }

  .table tbody tr:hover {
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
    transform: translateY(-1px);
  }

  .table tbody tr td:first-child {
    border-radius: 10px 0 0 10px;
  }

  .table tbody tr td:last-child {
    border-radius: 0 10px 10px 0;
  }

  .user-info {
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 12px;
  }

  .user-avatar-lg {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 13px;
    flex-shrink: 0;
    box-shadow: 0 2px 6px rgba(102, 126, 234, 0.25);
  }

  .user-details h6 {
    font-size: 14px;
    font-weight: 600;
    color: #111827;
    margin: 0 0 2px 0;
    line-height: 1.3;
  }

  .user-details p {
    font-size: 12px;
    color: #9ca3af;
    margin: 0;
    line-height: 1.3;
  }

  .badge {
    padding: 5px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
    border: none;
  }

  .badge.role-super-admin { 
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 2px 6px rgba(102, 126, 234, 0.3);
  }
  .badge.role-admin { 
    background: #667eea;
    color: white;
    box-shadow: 0 2px 6px rgba(102, 126, 234, 0.25);
  }
  .badge.role-doctor { 
    background: #00a19e;
    color: white;
    box-shadow: 0 2px 6px rgba(0, 161, 158, 0.25);
  }
  .badge.role-nurse { 
    background: #48bb78;
    color: white;
    box-shadow: 0 2px 6px rgba(72, 187, 120, 0.25);
  }
  .badge.role-receptionist { 
    background: #4299e1;
    color: white;
    box-shadow: 0 2px 6px rgba(66, 153, 225, 0.25);
  }
  .badge.role-pharmacist { 
    background: #ed8936;
    color: white;
    box-shadow: 0 2px 6px rgba(237, 137, 54, 0.25);
  }
  .badge.role-lab-technician { 
    background: #9f7aea;
    color: white;
    box-shadow: 0 2px 6px rgba(159, 122, 234, 0.25);
  }
  .badge.role-radiologist { 
    background: #f687b3;
    color: white;
    box-shadow: 0 2px 6px rgba(246, 135, 179, 0.25);
  }
  .badge.role-accountant { 
    background: #f6ad55;
    color: white;
    box-shadow: 0 2px 6px rgba(246, 173, 85, 0.25);
  }

  .badge.bg-success {
    background: #10b981 !important;
    color: white;
    box-shadow: 0 2px 6px rgba(16, 185, 129, 0.25);
  }

  .badge.bg-secondary {
    background: #d1d5db !important;
    color: #6b7280;
    box-shadow: 0 2px 6px rgba(209, 213, 219, 0.3);
  }

  .action-buttons {
    display: flex;
    gap: 6px;
  }

  .btn-sm { 
    padding: 7px 14px;
    font-size: 13px;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
  }
  
  .action-buttons .btn-sm {
    padding: 7px 12px;
  }

  .action-buttons .btn-sm i {
    font-size: 14px;
  }

  .action-buttons .btn-sm:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
  }

  .btn-outline-primary {
    border-color: #667eea;
    color: #667eea;
  }

  .btn-outline-primary:hover {
    background: #667eea;
    border-color: #667eea;
    color: white;
  }

  .btn-outline-warning {
    border-color: #f59e0b;
    color: #f59e0b;
  }

  .btn-outline-warning:hover {
    background: #f59e0b;
    border-color: #f59e0b;
    color: white;
  }

  .btn-outline-danger {
    border-color: #ef4444;
    color: #ef4444;
  }

  .btn-outline-danger:hover {
    background: #ef4444;
    border-color: #ef4444;
    color: white;
  }

  .empty-state {
    text-align: center;
    padding: 48px 20px;
    color: #9ca3af;
  }

  .empty-state i {
    font-size: 48px;
    color: #d1d5db;
    margin-bottom: 12px;
  }
</style>
@endpush

@section('content')
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon">
      <i class="bi bi-people"></i>
    </div>
    <div class="stat-value">{{ $stats['total'] }}</div>
    <div class="stat-label">Total Users</div>
  </div>

  <div class="stat-card">
    <div class="stat-icon">
      <i class="bi bi-check-circle"></i>
    </div>
    <div class="stat-value">{{ $stats['active'] }}</div>
    <div class="stat-label">Active</div>
  </div>

  <div class="stat-card">
    <div class="stat-icon">
      <i class="bi bi-x-circle"></i>
    </div>
    <div class="stat-value">{{ $stats['inactive'] }}</div>
    <div class="stat-label">Inactive</div>
  </div>

  <div class="stat-card">
    <div class="stat-icon">
      <i class="bi bi-person-plus"></i>
    </div>
    <div class="stat-value">{{ $stats['new_this_month'] }}</div>
    <div class="stat-label">New This Month</div>
  </div>
</div>

<div class="page-header">
  <h1 class="page-title">User Management</h1>
  <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
    <i class="bi bi-plus-circle me-2"></i>Add New User
  </a>
</div>

<!-- Filters -->
<div class="filter-section">
  <form action="{{ route('users.index') }}" method="GET">
    <div class="filter-row">
      <div class="flex-grow-1">
        <label class="form-label">Search</label>
        <input type="text" name="search" class="form-control" placeholder="Search by name or email..." value="{{ request('search') }}">
      </div>
      
      <div style="width: 200px;">
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

      <div style="width: 150px;">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
          <option value="">All Status</option>
          <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
          <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary">
        <i class="bi bi-search"></i> Filter
      </button>

      @if(request()->hasAny(['search', 'role', 'status']))
      <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-x-circle"></i> Clear
      </a>
      @endif
    </div>
  </form>
</div>

<div class="users-table-wrapper">
  @if($users->count() > 0)
  <table class="table">
    <thead>
      <tr>
        <th>User</th>
        <th>Role</th>
        <th>Phone</th>
        <th>Status</th>
        <th>Created</th>
        <th>Actions</th>
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
            </div>
          </div>
        </td>
        <td>
          @php
            $role = $user->roles->first();
            $roleName = $role ? $role->name : 'user';
          @endphp
          <span class="badge role-{{ $roleName }}">
            {{ ucwords(str_replace('-', ' ', $roleName)) }}
          </span>
        </td>
        <td>
          {{ $user->phone ?? '-' }}
        </td>
        <td>
          @if($user->is_active)
          <span class="badge bg-success">Active</span>
          @else
          <span class="badge bg-secondary">Inactive</span>
          @endif
        </td>
        <td>
          <small class="text-muted">{{ $user->created_at->format('M d, Y') }}</small>
        </td>
        <td>
          <div class="action-buttons">
            <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-outline-primary">
              <i class="bi bi-eye"></i>
            </a>
            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-warning">
              <i class="bi bi-pencil"></i>
            </a>
            @if($user->id !== auth()->id())
            <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" 
                  onsubmit="return confirm('Are you sure you want to delete this user?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-outline-danger">
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

  <div class="p-4" style="background: transparent;">
    {{ $users->links() }}
  </div>
  @else
  <div class="empty-state">
    <i class="bi bi-people"></i>
    <p>No users found</p>
    @if(request()->hasAny(['search', 'role', 'status']))
    <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-primary">Clear Filters</a>
    @endif
  </div>
  @endif
</div>

@push('scripts')
<script>
// Nothing needed for now
</script>
@endpush
@endsection
