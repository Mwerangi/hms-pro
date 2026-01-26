@extends('layouts.app')

@section('title', 'Roles & Permissions - Settings')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('settings.index') }}">Settings</a></li>
<li class="breadcrumb-item active" aria-current="page">Roles & Permissions</li>
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
    margin: 0 0 8px 0;
  }

  .content-description {
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
    padding: 20px;    transition: all 0.2s ease;
  }

  .stat-card:hover {
    border-color: #667eea;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);  }

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
  }

  .role-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 24px;
    transition: all 0.2s;
  }

  .role-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
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
  }

  .btn-view {
    background: #111827;
    color: white;
  }

  .btn-view:hover {
    background: #1f2937;
    color: white;
  }

  .btn-edit {
    background: white;
    color: #374151;
    border: 1px solid #e5e7eb;
  }

  .btn-edit:hover {
    background: #f9fafb;
    color: #111827;
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
    transition: all 0.2s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
  }

  .btn-primary-custom:hover {
    background: #1f2937;
    color: white;
  }

  @media (max-width: 768px) {
    .settings-container {
      flex-direction: column;
    }

    .settings-sidebar {
      width: 100%;
      border-right: none;
      border-bottom: 1px solid #e5e7eb;
    }

    .settings-content {
      padding: 24px 20px;
    }

    .roles-grid {
      grid-template-columns: 1fr;
    }
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
    <div class="content-header">
      <div>
        <h1 class="content-title">Roles & Permissions</h1>
        <p class="content-description">Manage system roles and assign permissions to control user access</p>
      </div>
      <a href="{{ route('settings.roles.create') }}" class="btn-primary-custom">
        <i class="bi bi-plus-circle"></i> Create New Role
      </a>
    </div>

    <!-- Statistics -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon" style="background: #f3f4f6; color: #667eea;">
          <i class="bi bi-shield-check"></i>
        </div>
        <div class="stat-value">{{ $stats['total_roles'] }}</div>
        <div class="stat-label">Total Roles</div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background: #f3f4f6; color: #667eea;">
          <i class="bi bi-key"></i>
        </div>
        <div class="stat-value">{{ $stats['total_permissions'] }}</div>
        <div class="stat-label">Total Permissions</div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background: #f3f4f6; color: #667eea;">
          <i class="bi bi-people"></i>
        </div>
        <div class="stat-value">{{ $stats['active_users_with_roles'] }}</div>
        <div class="stat-label">Active Users with Roles</div>
      </div>
    </div>

    <!-- Roles Grid -->
    <div class="roles-grid">
      @foreach($roles as $role)
      <div class="role-card">
        <div class="role-header">
          <div>
            <h3 class="role-name">{{ $role->name }}</h3>
            @if(in_array($role->name, ['Super Admin', 'Admin', 'Doctor', 'Nurse', 'Receptionist']))
              <span class="role-badge badge-protected">Protected</span>
            @endif
          </div>
        </div>

        <div class="role-stats">
          <div class="role-stat">
            <i class="bi bi-shield-lock"></i>
            <span>{{ $role->permissions->count() }} permissions</span>
          </div>
          <div class="role-stat">
            <i class="bi bi-people"></i>
            <span>{{ $role->users_count }} users</span>
          </div>
        </div>

        <div class="role-actions">
          <a href="{{ route('settings.roles.show', $role) }}" class="btn-action btn-view">
            <i class="bi bi-eye"></i> View Details
          </a>
          @if($role->name !== 'Super Admin')
          <a href="{{ route('settings.roles.edit', $role) }}" class="btn-action btn-edit">
            <i class="bi bi-pencil"></i> Edit
          </a>
          @endif
        </div>
      </div>
      @endforeach
    </div>
  </div>
</div>
@endsection
