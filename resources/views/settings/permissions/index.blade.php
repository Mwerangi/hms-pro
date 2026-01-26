@extends('layouts.app')

@section('title', 'System Permissions')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('settings.category', ['category' => 'roles']) }}">Roles & Permissions</a></li>
<li class="breadcrumb-item active" aria-current="page">All Permissions</li>
@endsection

@push('styles')
<style>
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

  .stats-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 32px;
    display: flex;
    align-items: center;
    gap: 20px;
  }

  .stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    background: #f3f4f6;
    color: #667eea;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
  }

  .stats-content h2 {
    font-size: 32px;
    font-weight: 600;
    color: #111827;
    margin: 0 0 4px 0;
  }

  .stats-content p {
    font-size: 14px;
    color: #6b7280;
    margin: 0;
  }

  .permissions-grid {
    display: grid;
    gap: 24px;
  }

  .permission-module {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
  }

  .module-header {
    background: #f9fafb;
    color: #111827;
    border-bottom: 1px solid #e5e7eb;
    padding: 20px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .module-title {
    font-size: 18px;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .module-count {
    background: #e5e7eb;
    color: #374151;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
  }

  .permissions-list {
    padding: 24px;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 12px;
  }

  .permission-item {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    padding: 12px 16px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: all 0.2s;
  }

  .permission-item:hover {
    background: #f3f4f6;
    border-color: #d1d5db;
  }

  .permission-icon {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    background: white;
    border: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #667eea;
    flex-shrink: 0;
  }

  .permission-details {
    flex: 1;
  }

  .permission-name {
    font-size: 13px;
    font-weight: 500;
    color: #111827;
    margin: 0 0 2px 0;
  }

  .permission-key {
    font-size: 11px;
    color: #6b7280;
    font-family: 'Courier New', monospace;
    margin: 0;
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

  .search-box {
    margin-bottom: 24px;
  }

  .search-input {
    width: 100%;
    padding: 12px 16px 12px 48px;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    font-size: 14px;
    transition: all 0.2s;
  }

  .search-input:focus {
    outline: none;
    border-color: #374151;
    box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.05);
  }

  .search-wrapper {
    position: relative;
  }

  .search-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: 18px;
  }
</style>
@endpush

@section('content')
<div class="page-header">
  <div>
    <h1 class="page-title">System Permissions</h1>
    <p class="page-subtitle">Complete list of all available permissions</p>
  </div>
  <a href="{{ route('settings.category', ['category' => 'roles']) }}" class="btn-secondary">
    <i class="bi bi-arrow-left"></i> Back to Roles
  </a>
</div>

<!-- Total Count -->
<div class="stats-card">
  <div class="stats-icon">
    <i class="bi bi-shield-lock"></i>
  </div>
  <div class="stats-content">
    <h2>{{ $permissions->flatten()->count() }}</h2>
    <p>Total system permissions across {{ $permissions->count() }} modules</p>
  </div>
</div>

<!-- Search -->
<div class="search-box">
  <div class="search-wrapper">
    <i class="bi bi-search search-icon"></i>
    <input type="text" class="search-input" id="permissionSearch" placeholder="Search permissions...">
  </div>
</div>

<!-- Permissions by Module -->
<div class="permissions-grid">
  @foreach($permissions as $moduleName => $modulePermissions)
  <div class="permission-module" data-module="{{ strtolower($moduleName) }}">
    <div class="module-header">
      <h2 class="module-title">
        <i class="bi bi-folder2-open"></i>
        {{ $moduleName }}
      </h2>
      <span class="module-count">{{ $modulePermissions->count() }} permissions</span>
    </div>

    <div class="permissions-list">
      @foreach($modulePermissions as $permission)
      <div class="permission-item" data-permission="{{ $permission->name }}">
        <div class="permission-icon">
          <i class="bi bi-check-circle"></i>
        </div>
        <div class="permission-details">
          <p class="permission-name">
            {{ ucwords(str_replace(['.', '-', '_'], ' ', explode('.', $permission->name)[1] ?? $permission->name)) }}
          </p>
          <p class="permission-key">{{ $permission->name }}</p>
        </div>
      </div>
      @endforeach
    </div>
  </div>
  @endforeach
</div>

@push('scripts')
<script>
  // Search functionality
  document.getElementById('permissionSearch').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const modules = document.querySelectorAll('.permission-module');
    
    modules.forEach(module => {
      const permissions = module.querySelectorAll('.permission-item');
      let hasVisiblePermission = false;
      
      permissions.forEach(permission => {
        const permissionName = permission.dataset.permission.toLowerCase();
        const permissionText = permission.textContent.toLowerCase();
        
        if (permissionName.includes(searchTerm) || permissionText.includes(searchTerm)) {
          permission.style.display = 'flex';
          hasVisiblePermission = true;
        } else {
          permission.style.display = 'none';
        }
      });
      
      // Hide module if no permissions match
      if (searchTerm && !hasVisiblePermission) {
        module.style.display = 'none';
      } else {
        module.style.display = 'block';
      }
    });
  });
</script>
@endpush
@endsection
