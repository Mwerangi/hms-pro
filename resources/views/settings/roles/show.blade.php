@extends('layouts.app')

@section('title', 'Role Details - ' . $role->name)

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('settings.index') }}">Settings</a></li>
<li class="breadcrumb-item"><a href="{{ route('settings.category', ['category' => 'roles']) }}">Roles & Permissions</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ $role->name }}</li>
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

  .page-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
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

  .role-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    margin-top: 8px;
  }

  .badge-protected {
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
  }

  .badge-custom {
    background: #f9fafb;
    color: #6b7280;
    border: 1px solid #e5e7eb;
  }

  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
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
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 12px;
    font-size: 20px;
  }

  .stat-value {
    font-size: 28px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 4px;
  }

  .stat-label {
    font-size: 13px;
    color: #6b7280;
    font-weight: 500;
  }

  .content-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
  }

  .card-title {
    font-size: 18px;
    font-weight: 600;
    color: #111827;
    margin: 0 0 20px 0;
    padding-bottom: 12px;
    border-bottom: 1px solid #e5e7eb;
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

  .module-title {
    font-size: 15px;
    font-weight: 600;
    color: #111827;
    margin: 0 0 16px 0;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .permissions-list {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
  }

  @media (max-width: 1200px) {
    .permissions-list {
      grid-template-columns: repeat(3, 1fr);
    }
  }

  @media (max-width: 768px) {
    .permissions-list {
      grid-template-columns: repeat(2, 1fr);
    }
  }

  @media (max-width: 480px) {
    .permissions-list {
      grid-template-columns: 1fr;
    }
  }

  .permission-badge {
    background: white;
    border: 1px solid #e5e7eb;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 13px;
    color: #374151;
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .permission-badge i {
    color: #10b981;
  }

  .users-list {
    display: grid;
    gap: 12px;
  }

  .user-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px;
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
  }

  .user-info {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #f3f4f6;
    color: #667eea;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 16px;
  }

  .user-details {
    display: flex;
    flex-direction: column;
  }

  .user-name {
    font-weight: 500;
    color: #111827;
    font-size: 14px;
  }

  .user-email {
    font-size: 12px;
    color: #6b7280;
  }

  .status-badge {
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
  }

  .status-active {
    background: #d1fae5;
    color: #065f46;
  }

  .status-inactive {
    background: #fee2e2;
    color: #991b1b;
  }

  .btn-group {
    display: flex;
    gap: 12px;
  }

  .btn-primary-custom {
    background: #111827;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
  }

  .btn-primary-custom:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    color: white;
  }

  .btn-secondary {
    background: white;
    color: #374151;
    border: 1px solid #e5e7eb;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
  }

  .btn-secondary:hover {
    background: #f9fafb;
    color: #111827;
  }

  .btn-danger {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
  }

  .btn-danger:hover {
    background: #fecaca;
    color: #7f1d1d;
  }

  .empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #9ca3af;
  }

  .empty-state i {
    font-size: 48px;
    opacity: 0.5;
    margin-bottom: 12px;
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
        <a href="{{ route('settings.category', ['category' => 'roles']) }}" class="sidebar-nav-link active">
          <span class="sidebar-icon"><i class="bi bi-shield-check"></i></span>
          <span class="sidebar-text">Roles & Permissions</span>
        </a>
      </li>
    </ul>
  </div>

  <!-- Main Content -->
  <div class="settings-content">
<div class="page-header">
  <div>
    <h1 class="page-title">{{ $role->name }}</h1>
    <p class="page-subtitle">Role details and permissions</p>
    @if(in_array($role->name, ['Super Admin', 'Admin', 'Doctor', 'Nurse', 'Receptionist']))
    <span class="role-badge badge-protected">
      <i class="bi bi-shield-fill-check"></i> Protected System Role
    </span>
    @else
    <span class="role-badge badge-custom">Custom Role</span>
    @endif
  </div>
  <div class="btn-group">
    @if($role->name !== 'Super Admin')
    <a href="{{ route('settings.roles.edit', $role) }}" class="btn-primary-custom">
      <i class="bi bi-pencil"></i> Edit Role
    </a>
    @endif
    <a href="{{ route('settings.category', ['category' => 'roles']) }}" class="btn-secondary">
      <i class="bi bi-arrow-left"></i> Back to Roles
    </a>
  </div>
</div>

<!-- Statistics -->
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon" style="background: #f3f4f6; color: #667eea;">
      <i class="bi bi-shield-check"></i>
    </div>
    <div class="stat-value">{{ $role->permissions->count() }}</div>
    <div class="stat-label">Permissions Assigned</div>
  </div>

  <div class="stat-card">
    <div class="stat-icon" style="background: #f3f4f6; color: #667eea;">
      <i class="bi bi-people"></i>
    </div>
    <div class="stat-value">{{ $role->users->count() }}</div>
    <div class="stat-label">Users with Role</div>
  </div>

  <div class="stat-card">
    <div class="stat-icon" style="background: #f3f4f6; color: #667eea;">
      <i class="bi bi-clock-history"></i>
    </div>
    <div class="stat-value">{{ $role->created_at->format('M Y') }}</div>
    <div class="stat-label">Created</div>
  </div>
</div>

<!-- Permissions -->
<div class="content-card">
  <h2 class="card-title">
    <i class="bi bi-shield-lock me-2"></i>Assigned Permissions ({{ $role->permissions->count() }})
  </h2>

  @if($role->permissions->count() > 0)
  <div class="permissions-grid">
    @php
      $groupedPermissions = $role->permissions->groupBy(function($permission) {
        $parts = explode('.', $permission->name);
        return ucwords(str_replace('-', ' ', $parts[0]));
      });
    @endphp

    @foreach($groupedPermissions as $moduleName => $modulePermissions)
    <div class="permission-module">
      <h3 class="module-title">
        <i class="bi bi-folder2-open"></i>
        {{ $moduleName }} ({{ $modulePermissions->count() }})
      </h3>

      <div class="permissions-list">
        @foreach($modulePermissions as $permission)
        <div class="permission-badge">
          <i class="bi bi-check-circle-fill"></i>
          {{ ucwords(str_replace(['.', '-', '_'], ' ', explode('.', $permission->name)[1] ?? $permission->name)) }}
        </div>
        @endforeach
      </div>
    </div>
    @endforeach
  </div>
  @else
  <div class="empty-state">
    <i class="bi bi-shield-x"></i>
    <p>No permissions assigned to this role yet.</p>
  </div>
  @endif
</div>

<!-- Users with this Role -->
<div class="content-card">
  <h2 class="card-title">
    <i class="bi bi-people me-2"></i>Users with this Role ({{ $role->users->count() }})
  </h2>

  @if($role->users->count() > 0)
  <div class="users-list">
    @foreach($role->users as $user)
    <div class="user-item">
      <div class="user-info">
        <div class="user-avatar">
          {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div class="user-details">
          <span class="user-name">{{ $user->name }}</span>
          <span class="user-email">{{ $user->email }}</span>
        </div>
      </div>
      <span class="status-badge {{ $user->is_active ? 'status-active' : 'status-inactive' }}">
        {{ $user->is_active ? 'Active' : 'Inactive' }}
      </span>
    </div>
    @endforeach
  </div>
  @else
  <div class="empty-state">
    <i class="bi bi-people"></i>
    <p>No users assigned to this role yet.</p>
  </div>
  @endif
</div>

<!-- Delete Role (for custom roles only) -->
@if(!in_array($role->name, ['Super Admin', 'Admin', 'Doctor', 'Nurse', 'Receptionist', 'Pharmacist', 'Lab Technician', 'Radiologist', 'Accountant']))
<div class="content-card" style="border-color: #fecaca;">
  <h2 class="card-title" style="color: #991b1b;">
    <i class="bi bi-exclamation-triangle me-2"></i>Danger Zone
  </h2>
  <p style="color: #6b7280; margin-bottom: 16px;">
    Deleting this role is permanent and cannot be undone. All users with this role will lose their permissions.
  </p>
  <form action="{{ route('settings.roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this role? This action cannot be undone.')">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn-danger" {{ $role->users->count() > 0 ? 'disabled' : '' }}>
      <i class="bi bi-trash"></i> 
      Delete Role
      @if($role->users->count() > 0)
        (Cannot delete - {{ $role->users->count() }} user(s) assigned)
      @endif
    </button>
  </form>
</div>
@endif
  </div>
</div>
@endsection
