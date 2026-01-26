@extends('layouts.app')

@section('title', 'System Settings')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active" aria-current="page">Settings</li>
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

  .settings-form {
    max-width: 800px;
  }

  .form-section {
    margin-bottom: 32px;
    padding-bottom: 32px;
    border-bottom: 1px solid #e5e7eb;
  }

  .form-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
  }

  .form-group {
    margin-bottom: 24px;
  }

  .form-label {
    display: block;
    font-size: 14px;
    font-weight: 500;
    color: #374151;
    margin-bottom: 8px;
  }

  .form-label-description {
    font-size: 12px;
    font-weight: 400;
    color: #6b7280;
    margin-top: 2px;
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
    border-color: #374151;
    box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.05);
  }

  .form-control-textarea {
    min-height: 80px;
    resize: vertical;
  }

  .form-switch {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px;
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
  }

  .form-switch:hover {
    background: #f3f4f6;
  }

  .switch-info {
    flex: 1;
  }

  .switch-label {
    font-size: 14px;
    font-weight: 500;
    color: #111827;
    margin-bottom: 4px;
  }

  .switch-description {
    font-size: 12px;
    color: #6b7280;
  }

  .switch-toggle {
    position: relative;
    width: 48px;
    height: 26px;
    flex-shrink: 0;
  }

  .switch-toggle input {
    opacity: 0;
    width: 0;
    height: 0;
  }

  .switch-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #d1d5db;
    transition: .3s;
    border-radius: 26px;
  }

  .switch-slider:before {
    position: absolute;
    content: "";
    height: 20px;
    width: 20px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .3s;
    border-radius: 50%;
  }

  input:checked + .switch-slider {
    background: #374151;
  }

  input:checked + .switch-slider:before {
    transform: translateX(22px);
  }

  .form-actions {
    display: flex;
    gap: 12px;
    padding-top: 32px;
    border-top: 1px solid #e5e7eb;
    position: sticky;
    bottom: 0;
    background: white;
    margin-top: 32px;
  }

  .btn-save {
    background: #111827;
    color: white;
    border: none;
    padding: 12px 32px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
  }

  .btn-save:hover {
    background: #1f2937;
  }

  .btn-reset {
    background: white;
    color: #374151;
    border: 1px solid #d1d5db;
    padding: 12px 24px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
  }

  .btn-reset:hover {
    background: #f9fafb;
  }

  .alert {
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 14px;
  }

  .alert-success {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
  }

  .alert-error {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
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
        <a href="{{ route('settings.index', ['category' => 'general']) }}" 
           class="sidebar-nav-link {{ $activeCategory === 'general' ? 'active' : '' }}">
          <span class="sidebar-icon"><i class="bi bi-gear"></i></span>
          <span class="sidebar-text">General Settings</span>
        </a>
      </li>
      <li class="sidebar-nav-item">
        <a href="{{ route('settings.index', ['category' => 'patient']) }}" 
           class="sidebar-nav-link {{ $activeCategory === 'patient' ? 'active' : '' }}">
          <span class="sidebar-icon"><i class="bi bi-person-badge"></i></span>
          <span class="sidebar-text">Patient Management</span>
        </a>
      </li>
      <li class="sidebar-nav-item">
        <a href="{{ route('settings.index', ['category' => 'appointment']) }}" 
           class="sidebar-nav-link {{ $activeCategory === 'appointment' ? 'active' : '' }}">
          <span class="sidebar-icon"><i class="bi bi-calendar-check"></i></span>
          <span class="sidebar-text">Appointments</span>
        </a>
      </li>
      <li class="sidebar-nav-item">
        <a href="{{ route('settings.index', ['category' => 'billing']) }}" 
           class="sidebar-nav-link {{ $activeCategory === 'billing' ? 'active' : '' }}">
          <span class="sidebar-icon"><i class="bi bi-receipt"></i></span>
          <span class="sidebar-text">Billing & Payment</span>
        </a>
      </li>
      <li class="sidebar-nav-item">
        <a href="{{ route('settings.index', ['category' => 'pharmacy']) }}" 
           class="sidebar-nav-link {{ $activeCategory === 'pharmacy' ? 'active' : '' }}">
          <span class="sidebar-icon"><i class="bi bi-capsule"></i></span>
          <span class="sidebar-text">Pharmacy</span>
        </a>
      </li>
      <li class="sidebar-nav-item">
        <a href="{{ route('settings.index', ['category' => 'laboratory']) }}" 
           class="sidebar-nav-link {{ $activeCategory === 'laboratory' ? 'active' : '' }}">
          <span class="sidebar-icon"><i class="bi bi-clipboard2-pulse"></i></span>
          <span class="sidebar-text">Laboratory</span>
        </a>
      </li>
      <li class="sidebar-nav-item">
        <a href="{{ route('settings.index', ['category' => 'notifications']) }}" 
           class="sidebar-nav-link {{ $activeCategory === 'notifications' ? 'active' : '' }}">
          <span class="sidebar-icon"><i class="bi bi-bell"></i></span>
          <span class="sidebar-text">Notifications</span>
        </a>
      </li>
      <li class="sidebar-nav-item">
        <a href="{{ route('settings.index', ['category' => 'security']) }}" 
           class="sidebar-nav-link {{ $activeCategory === 'security' ? 'active' : '' }}">
          <span class="sidebar-icon"><i class="bi bi-shield-lock"></i></span>
          <span class="sidebar-text">Security</span>
        </a>
      </li>
    </ul>

    <div class="sidebar-title" style="margin-top: 32px;">Access Control</div>
    <ul class="sidebar-nav">
      <li class="sidebar-nav-item">
        <a href="{{ route('settings.category', ['category' => 'roles']) }}" 
           class="sidebar-nav-link {{ $activeCategory === 'roles' ? 'active' : '' }}">
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
        <a href="{{ route('settings.category', ['category' => 'departments']) }}" class="sidebar-nav-link">
          <span class="sidebar-icon"><i class="bi bi-diagram-3"></i></span>
          <span class="sidebar-text">Departments</span>
        </a>
      </li>
    </ul>
  </div>

  <!-- Main Content -->
  <div class="settings-content">
    <div class="content-header">
      <h1 class="content-title">{{ ucfirst($activeCategory) }} Settings</h1>
      <p class="content-description">Configure {{ strtolower($activeCategory) }} related parameters for your hospital system</p>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
      <i class="bi bi-check-circle-fill"></i>
      {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-error">
      <i class="bi bi-exclamation-triangle-fill"></i>
      {{ session('error') }}
    </div>
    @endif

    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="settings-form">
      @csrf
      @method('PUT')

      <div class="form-section">
        @foreach($settings as $setting)
          @if($setting->type === 'boolean')
            <div class="form-group">
              <label class="form-switch">
                <div class="switch-info">
                  <div class="switch-label">{{ ucwords(str_replace('_', ' ', $setting->key)) }}</div>
                  <div class="switch-description">{{ $setting->description }}</div>
                </div>
                <div class="switch-toggle">
                  <input type="checkbox" name="{{ $setting->key }}" value="1" {{ $setting->value == '1' ? 'checked' : '' }}>
                  <span class="switch-slider"></span>
                </div>
              </label>
            </div>
          @elseif($setting->type === 'text')
            <div class="form-group">
              <label class="form-label">
                {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                @if($setting->description)
                  <div class="form-label-description">{{ $setting->description }}</div>
                @endif
              </label>
              <textarea name="{{ $setting->key }}" class="form-control form-control-textarea">{{ $setting->value }}</textarea>
            </div>
          @else
            <div class="form-group">
              <label class="form-label">
                {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                @if($setting->description)
                  <div class="form-label-description">{{ $setting->description }}</div>
                @endif
              </label>
              <input 
                type="{{ $setting->type === 'password' ? 'password' : 'text' }}" 
                name="{{ $setting->key }}" 
                value="{{ $setting->value }}" 
                class="form-control"
                {{ $setting->type === 'integer' || $setting->type === 'decimal' ? 'step="any"' : '' }}
              >
            </div>
          @endif
        @endforeach
      </div>

      <div class="form-actions">
        <button type="submit" class="btn-save">
          <i class="bi bi-check-circle me-2"></i>Save Changes
        </button>
        <button type="reset" class="btn-reset">
          <i class="bi bi-arrow-counterclockwise me-2"></i>Reset
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
