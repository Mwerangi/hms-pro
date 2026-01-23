@extends('layouts.app')

@section('title', 'User Details')

@push('styles')
<style>
  .page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
  }

  .page-title {
    font-size: 20px;
    font-weight: 600;
    color: #111827;
    margin: 0;
  }

  /* Hero Card - User Header */
  .user-hero-card {
    background: white;
    border-radius: 12px;
    padding: 32px;
    margin-bottom: 20px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
  }

  .user-hero-content {
    display: flex;
    align-items: center;
    gap: 24px;
  }

  .user-avatar-hero {
    width: 80px;
    height: 80px;
    border-radius: 12px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 32px;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.25);
  }

  .user-hero-info h2 {
    font-size: 24px;
    font-weight: 700;
    margin: 0 0 6px 0;
    color: #111827;
  }

  .user-hero-info p {
    font-size: 14px;
    margin: 0 0 12px 0;
    color: #6b7280;
  }

  .user-hero-badges {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
  }

  /* Info Grid */
  .info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 16px;
    margin-bottom: 20px;
  }

  .info-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    transition: all 0.2s ease;
  }

  .info-card:hover {
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
    transform: translateY(-2px);
  }

  .info-card-title {
    font-size: 12px;
    font-weight: 600;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .info-card-title i {
    font-size: 14px;
    color: #667eea;
  }

  .info-item {
    margin-bottom: 12px;
  }

  .info-item:last-child {
    margin-bottom: 0;
  }

  .info-label {
    font-size: 11px;
    color: #9ca3af;
    margin-bottom: 4px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
  }

  .info-value {
    font-size: 14px;
    color: #111827;
    font-weight: 500;
  }

  /* Wide Card */
  .wide-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
  }

  .wide-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
  }

  .wide-card-title {
    font-size: 12px;
    font-weight: 600;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .wide-card-title i {
    font-size: 14px;
    color: #667eea;
  }

  .role-description-box {
    background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
    border-radius: 8px;
    padding: 16px;
    border-left: 4px solid #667eea;
  }

  .role-description-box p {
    font-size: 13px;
    line-height: 1.6;
    color: #6b7280;
    margin: 0;
  }

  /* Badges */
  .badge {
    padding: 6px 12px;
    font-size: 11px;
    font-weight: 600;
    border-radius: 6px;
    border: none;
  }

  .badge-light {
    background: #f3f4f6;
    border: 1px solid #e5e7eb;
    color: #6b7280;
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

  .btn-sm { 
    padding: 7px 14px;
    font-size: 13px;
    border-radius: 8px;
  }

  .stat-box {
    text-align: center;
    padding: 16px;
    background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
    border-radius: 8px;
    border: 1px solid #e5e7eb;
  }

  .stat-value {
    font-size: 20px;
    font-weight: 700;
    color: #667eea;
    margin-bottom: 4px;
  }

  .stat-label {
    font-size: 11px;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
</style>
@endpush

@section('content')
<div class="page-header">
  <h1 class="page-title">User Profile</h1>
  <div class="d-flex gap-2">
    <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm">
      <i class="bi bi-pencil me-2"></i>Edit
    </a>
    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">
      <i class="bi bi-arrow-left me-2"></i>Back
    </a>
  </div>
</div>

@php
  $role = $user->roles->first();
  $roleName = $role ? $role->name : 'user';
@endphp

<!-- Hero Card -->
<div class="user-hero-card">
  <div class="user-hero-content">
    <div class="user-avatar-hero">
      {{ strtoupper(substr($user->name, 0, 1)) }}
    </div>
    <div class="user-hero-info flex-grow-1">
      <h2>{{ $user->name }}</h2>
      <p><i class="bi bi-envelope me-2"></i>{{ $user->email }}</p>
      <div class="user-hero-badges">
        <span class="badge badge-light">
          <i class="bi bi-person-badge me-1"></i>{{ ucwords(str_replace('-', ' ', $roleName)) }}
        </span>
        @if($user->is_active)
        <span class="badge badge-light">
          <i class="bi bi-check-circle me-1"></i>Active
        </span>
        @else
        <span class="badge badge-light">
          <i class="bi bi-x-circle me-1"></i>Inactive
        </span>
        @endif
        <span class="badge badge-light">
          <i class="bi bi-calendar me-1"></i>Joined {{ $user->created_at->format('M Y') }}
        </span>
      </div>
    </div>
  </div>
</div>

<!-- Info Grid -->
<div class="info-grid">
  <!-- Contact Information -->
  <div class="info-card">
    <div class="info-card-title">
      <i class="bi bi-telephone"></i>
      Contact Information
    </div>
    <div class="info-item">
      <div class="info-label">Phone Number</div>
      <div class="info-value">{{ $user->phone ?? 'Not provided' }}</div>
    </div>
    <div class="info-item">
      <div class="info-label">Email Address</div>
      <div class="info-value">{{ $user->email }}</div>
    </div>
    <div class="info-item">
      <div class="info-label">Address</div>
      <div class="info-value">{{ $user->address ?? 'Not provided' }}</div>
    </div>
  </div>

  <!-- Account Details -->
  <div class="info-card">
    <div class="info-card-title">
      <i class="bi bi-person-badge"></i>
      Account Details
    </div>
    <div class="info-item">
      <div class="info-label">User ID</div>
      <div class="info-value">#{{ $user->id }}</div>
    </div>
    <div class="info-item">
      <div class="info-label">Status</div>
      <div class="info-value">
        @if($user->is_active)
        <span class="badge bg-success">Active</span>
        @else
        <span class="badge bg-secondary">Inactive</span>
        @endif
      </div>
    </div>
    <div class="info-item">
      <div class="info-label">Assigned Role</div>
      <div class="info-value">
        <span class="badge role-{{ $roleName }}">
          {{ ucwords(str_replace('-', ' ', $roleName)) }}
        </span>
      </div>
    </div>
  </div>

  <!-- Activity Timeline -->
  <div class="info-card">
    <div class="info-card-title">
      <i class="bi bi-clock-history"></i>
      Activity Timeline
    </div>
    <div class="info-item">
      <div class="info-label">Account Created</div>
      <div class="info-value">{{ $user->created_at->format('M d, Y') }}</div>
      <small class="text-muted" style="font-size: 11px;">{{ $user->created_at->diffForHumans() }}</small>
    </div>
    <div class="info-item">
      <div class="info-label">Last Updated</div>
      <div class="info-value">{{ $user->updated_at->format('M d, Y') }}</div>
      <small class="text-muted" style="font-size: 11px;">{{ $user->updated_at->diffForHumans() }}</small>
    </div>
  </div>
</div>

<!-- Role & Permissions -->
<div class="wide-card">
  <div class="wide-card-title">
    <i class="bi bi-shield-check"></i>
    Role & Permissions
  </div>
  <div class="role-description-box">
    <p>
      @switch($roleName)
        @case('super-admin')
          <strong>Super Administrator:</strong> Full system access with all administrative privileges and controls.
          @break
        @case('admin')
          <strong>Administrator:</strong> Administrative privileges for managing hospital operations and staff.
          @break
        @case('doctor')
          <strong>Doctor:</strong> Medical staff with access to patient care, prescriptions, and medical records.
          @break
        @case('nurse')
          <strong>Nurse:</strong> Nursing staff with patient monitoring and care coordination access.
          @break
        @case('receptionist')
          <strong>Receptionist:</strong> Front desk operations including patient registration and appointment scheduling.
          @break
        @case('pharmacist')
          <strong>Pharmacist:</strong> Pharmacy management including medication dispensing and inventory.
          @break
        @case('lab-technician')
          <strong>Lab Technician:</strong> Laboratory operations including test processing and result reporting.
          @break
        @case('radiologist')
          <strong>Radiologist:</strong> Radiology and medical imaging services management.
          @break
        @case('accountant')
          <strong>Accountant:</strong> Financial management including billing, invoicing, and financial reporting.
          @break
        @default
          <strong>User:</strong> Standard user access.
      @endswitch
    </p>
  </div>
</div>

@if($user->id !== auth()->id())
<div class="wide-card mt-3">
  <div class="wide-card-title">
    <i class="bi bi-shield-exclamation"></i>
    Danger Zone
  </div>
  <div class="d-flex justify-content-between align-items-center">
    <div>
      <strong style="color: #111827; font-size: 14px;">Delete User Account</strong>
      <p class="text-muted mb-0" style="font-size: 12px;">Permanently remove this user and all associated data. This action cannot be undone.</p>
    </div>
    <form action="{{ route('users.destroy', $user) }}" method="POST" 
          onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
      @csrf
      @method('DELETE')
      <button type="submit" class="btn btn-danger btn-sm">
        <i class="bi bi-trash me-2"></i>Delete User
      </button>
    </form>
  </div>
</div>
@endif
@endsection
