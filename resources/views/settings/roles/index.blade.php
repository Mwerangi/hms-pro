@extends('layouts.app')

@section('title', 'Role & Permission Management')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item">Settings</li>
<li class="breadcrumb-item active" aria-current="page">Roles & Permissions</li>
@endsection

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
    margin: 0 0 4px 0;
  }

  .page-subtitle {
    color: #6b7280;
    font-size: 14px;
    margin: 0;
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
    transition: all 0.2s ease;
  }

  .stat-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transform: translateY(-2px);
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

  .roles-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
  }

  .role-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 24px;
    transition: all 0.2s ease;
    position: relative;
  }

  .role-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  }

  .role-header {
    display: flex;
    align-items: start;
    justify-content: space-between;
    margin-bottom: 16px;
  }

  .role-name {
    font-size: 18px;
    font-weight: 600;
    color: #111827;
    margin: 0 0 4px 0;
  }

  .role-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
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

  .role-stats {
    display: flex;
    gap: 20px;
    margin-bottom: 16px;
  }

  .role-stat {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: #6b7280;
  }

  .role-stat i {
    color: #9ca3af;
  }

  .role-actions {
    display: flex;
    gap: 8px;
    padding-top: 16px;
    border-top: 1px solid #f3f4f6;
  }

  .btn-action {
    flex: 1;
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    text-align: center;
    transition: all 0.2s;
    border: none;
    cursor: pointer;
  }

  .btn-view {
    background: #f3f4f6;
    color: #374151;
  }

  .btn-view:hover {
    background: #e5e7eb;
    color: #111827;
  }

  .btn-edit {
    background: #f9fafb;
    color: #374151;
    border: 1px solid #e5e7eb;
  }

  .btn-edit:hover {
    background: #f3f4f6;
    color: #111827;
  }

  .btn-delete {
    background: #fee2e2;
    color: #991b1b;
  }

  .btn-delete:hover {
    background: #fecaca;
    color: #7f1d1d;
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
    background: #1f2937;
    color: white;
  }

  .protected-label {
    position: absolute;
    top: 12px;
    right: 12px;
  }
</style>
@endpush

@section('content')
<div class="page-header">
  <div>
    <h1 class="page-title">Roles & Permissions Management</h1>
    <p class="page-subtitle">Manage system roles and assign permissions</p>
  </div>
  <div style="display: flex; gap: 12px;">
    <a href="{{ route('settings.permissions.index') }}" class="btn-primary-custom" style="background: white; color: #374151; border: 1px solid #e5e7eb;">
      <i class="bi bi-shield-check"></i> View All Permissions
    </a>
    <a href="{{ route('settings.roles.create') }}" class="btn-primary-custom">
      <i class="bi bi-plus-circle"></i> Create New Role
    </a>
  </div>
</div>

<!-- Statistics -->
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon" style="background: #f3f4f6; color: #374151;">
      <i class="bi bi-person-badge"></i>
    </div>
    <div class="stat-value">{{ $stats['total_roles'] }}</div>
    <div class="stat-label">Total Roles</div>
  </div>

  <div class="stat-card">
    <div class="stat-icon" style="background: #f3f4f6; color: #374151;">
      <i class="bi bi-shield-lock"></i>
    </div>
    <div class="stat-value">{{ $stats['total_permissions'] }}</div>
    <div class="stat-label">Total Permissions</div>
  </div>

  <div class="stat-card">
    <div class="stat-icon" style="background: #f3f4f6; color: #374151;">
      <i class="bi bi-people"></i>
    </div>
    <div class="stat-value">{{ $stats['active_users_with_roles'] }}</div>
    <div class="stat-label">Active Users</div>
  </div>
</div>

<!-- Roles Grid -->
<div class="roles-grid">
  @foreach($roles as $role)
  <div class="role-card">
    @if(in_array($role->name, ['Super Admin', 'Admin', 'Doctor', 'Nurse', 'Receptionist']))
    <div class="protected-label">
      <span class="role-badge badge-protected">
        <i class="bi bi-shield-fill-check"></i> Protected
      </span>
    </div>
    @else
    <div class="protected-label">
      <span class="role-badge badge-custom">Custom</span>
    </div>
    @endif

    <div class="role-header">
      <div>
        <h3 class="role-name">{{ $role->name }}</h3>
      </div>
    </div>

    <div class="role-stats">
      <div class="role-stat">
        <i class="bi bi-shield-check"></i>
        <span>{{ $role->permissions->count() }} Permissions</span>
      </div>
      <div class="role-stat">
        <i class="bi bi-people"></i>
        <span>{{ $role->users_count }} Users</span>
      </div>
    </div>

    <div class="role-actions">
      <a href="{{ route('settings.roles.show', $role) }}" class="btn-action btn-view">
        <i class="bi bi-eye"></i> View
      </a>
      @if($role->name !== 'Super Admin')
      <a href="{{ route('settings.roles.edit', $role) }}" class="btn-action btn-edit">
        <i class="bi bi-pencil"></i> Edit
      </a>
      @endif
      @if(!in_array($role->name, ['Super Admin', 'Admin', 'Doctor', 'Nurse', 'Receptionist']))
      <form action="{{ route('settings.roles.destroy', $role) }}" method="POST" style="flex: 1;" onsubmit="return confirm('Are you sure you want to delete this role?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-action btn-delete" style="width: 100%;">
          <i class="bi bi-trash"></i> Delete
        </button>
      </form>
      @endif
    </div>
  </div>
  @endforeach
</div>

@if($roles->isEmpty())
<div style="text-align: center; padding: 60px 20px; color: #9ca3af;">
  <i class="bi bi-inbox" style="font-size: 48px; opacity: 0.5; display: block; margin-bottom: 16px;"></i>
  <p>No roles found. Create your first role to get started.</p>
</div>
@endif
@endsection
