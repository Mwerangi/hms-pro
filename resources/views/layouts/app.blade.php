<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>@yield('title', 'Dashboard') - HMS Pro</title>
  <meta name="description" content="Hospital Management System">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Favicons -->
  <link href="{{ asset('theme/assets/img/favicon.png') }}" rel="icon">
  <link href="{{ asset('theme/assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&family=Ubuntu:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('theme/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('theme/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  
  <!-- NProgress Loading Bar -->
  <link rel="stylesheet" href="https://unpkg.com/nprogress@0.2.0/nprogress.css">

  <!-- Main CSS File -->
  <link href="{{ asset('theme/assets/css/main.css') }}" rel="stylesheet">
  <link href="{{ asset('theme/assets/css/dashboard.css') }}" rel="stylesheet">

  @stack('styles')

  <style>
    /* Custom HMS Styles */
    :root {
      --hms-primary: #111827;
      --hms-primary-dark: #000000;
      --hms-secondary: #374151;
      --hms-accent: #6b7280;
      --hms-success: #065f46;
      --hms-warning: #92400e;
      --hms-danger: #991b1b;
      --hms-info: #1e40af;
      --bg-primary: #ffffff;
      --bg-secondary: #f9fafb;
      --bg-tertiary: #f3f4f6;
      --text-primary: #111827;
      --text-secondary: #6b7280;
      --text-tertiary: #9ca3af;
      --border-color: #e5e7eb;
      --border-color-light: #f3f4f6;
    }

    /* Dark Mode Variables */
    [data-theme="dark"] {
      --bg-primary: #1a202c;
      --bg-secondary: #2d3748;
      --text-primary: #f7fafc;
      --text-secondary: #cbd5e0;
      --border-color: #4a5568;
    }

    body {
      background: var(--bg-secondary);
      font-family: 'Roboto', sans-serif;
      color: var(--text-primary);
      transition: background-color 0.3s ease, color 0.3s ease;
    }

    /* Breadcrumb */
    .breadcrumb-section {
      padding: 16px 24px 0;
      margin-top: 60px;
      margin-left: 260px;
      transition: margin-left 0.3s ease;
      background: transparent;
    }

    .sidebar-collapsed .breadcrumb-section {
      margin-left: 70px;
    }

    .breadcrumb {
      margin: 0;
      padding: 10px 16px;
      background: white;
      font-size: 13px;
      border-radius: 8px;
      box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
      border: 1px solid var(--border-color);
    }

    .breadcrumb-item {
      display: flex;
      align-items: center;
    }

    .breadcrumb-item a {
      color: var(--text-secondary);
      text-decoration: none;
      transition: all 0.2s ease;
      display: flex;
      align-items: center;
      font-weight: 500;
    }

    .breadcrumb-item a:hover {
      color: var(--text-primary);
    }

    .breadcrumb-item a i {
      font-size: 14px;
    }

    .breadcrumb-item.active {
      color: var(--text-primary);
      font-weight: 600;
    }

    .breadcrumb-item + .breadcrumb-item::before {
      content: '/';
      color: var(--border-color);
      padding: 0 8px;
      font-weight: 400;
    }

    /* Dark Mode Toggle */
    .dark-mode-toggle {
      width: 40px;
      height: 40px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s ease;
      background: transparent;
      border: none;
      color: #4a5568;
    }

    .dark-mode-toggle:hover {
      background: var(--bg-tertiary);
      color: var(--text-primary);
      transform: none;
    }

    /* ===== Loading Styles ===== */
    /* NProgress Customization */
    #nprogress .bar {
      background: var(--hms-primary) !important;
      height: 3px !important;
      z-index: 10000 !important;
    }

    #nprogress .peg {
      box-shadow: 0 0 10px var(--hms-primary), 0 0 5px var(--hms-primary) !important;
    }

    #nprogress .spinner-icon {
      border-top-color: var(--hms-primary) !important;
      border-left-color: var(--hms-primary) !important;
    }

    /* Button Loading States */
    .btn-loading {
      position: relative;
      pointer-events: none;
      opacity: 0.65;
    }

    .btn-loading .btn-text {
      visibility: hidden;
      opacity: 0;
    }

    .btn-loading::after {
      content: "";
      position: absolute;
      width: 16px;
      height: 16px;
      top: 50%;
      left: 50%;
      margin-left: -8px;
      margin-top: -8px;
      border: 2px solid transparent;
      border-top-color: currentColor;
      border-radius: 50%;
      animation: spinner-border 0.75s linear infinite;
    }

    @keyframes spinner-border {
      to {
        transform: rotate(360deg);
      }
    }

    /* Full Page Loader (Optional) */
    .page-loader {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(4px);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 9999;
      transition: opacity 0.3s ease;
    }

    [data-theme="dark"] .page-loader {
      background: rgba(26, 32, 44, 0.9);
    }

    .page-loader.active {
      display: flex;
    }

    .loader-spinner {
      width: 50px;
      height: 50px;
      border: 4px solid var(--border-color);
      border-top-color: var(--hms-primary);
      border-radius: 50%;
      animation: spinner-border 1s linear infinite;
    }

    [data-theme="dark"] .loader-spinner {
      border-color: #4a5568;
      border-top-color: var(--hms-primary);
    }

    /* Top Header */
    .hms-header {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      height: 60px;
      background: white;
      border-bottom: 1px solid var(--border-color);
      z-index: 1000;
      display: flex;
      align-items: center;
      padding: 0 24px;
      box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
      transition: all 0.3s ease;
    }

    [data-theme="dark"] .hms-header {
      background: rgba(26, 32, 44, 0.95);
    }

    .sidebar-toggle {
      width: 40px;
      height: 40px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.2s ease;
      background: transparent;
      border: none;
      color: var(--text-secondary);
      margin-right: 20px;
    }

    .sidebar-toggle:hover {
      background: var(--bg-tertiary);
      color: var(--text-primary);
    }

    .hms-logo {
      display: flex;
      align-items: center;
      gap: 12px;
      font-size: 19px;
      font-weight: 700;
      color: var(--text-primary);
      text-decoration: none;
      margin-right: 30px;
      letter-spacing: -0.5px;
    }

    .hms-logo i {
      font-size: 24px;
      color: var(--hms-primary);
    }

    .header-actions {
      display: flex;
      align-items: center;
      gap: 15px;
      margin-left: auto;
    }

    .header-icon {
      position: relative;
      width: 40px;
      height: 40px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--text-secondary);
      cursor: pointer;
      transition: all 0.2s ease;
      background: transparent;
      border: none;
    }

    .header-icon:hover {
      background: var(--bg-tertiary);
      color: var(--text-primary);
    }

    .header-icon .badge {
      position: absolute;
      top: -2px;
      right: -2px;
      background: var(--hms-danger);
      color: white;
      font-size: 10px;
      font-weight: 600;
      padding: 2px 5px;
      border-radius: 10px;
      border: 2px solid white;
    }

    .user-profile {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 6px 12px;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.2s ease;
      border: 1px solid transparent;
    }

    .user-profile:hover {
      background: var(--bg-tertiary);
      border-color: var(--border-color);
    }

    .user-avatar {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: var(--hms-primary);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: 600;
      font-size: 14px;
    }

    .user-info {
      display: flex;
      flex-direction: column;
    }

    .user-name {
      font-weight: 600;
      font-size: 14px;
      color: var(--text-primary);
    }

    .user-role {
      font-size: 12px;
      color: var(--text-secondary);
      text-transform: capitalize;
    }

    /* Sidebar */
    .hms-sidebar {
      position: fixed;
      top: 60px;
      left: 0;
      bottom: 0;
      width: 260px;
      background: white;
      border-right: 1px solid var(--border-color);
      overflow-y: auto;
      overflow-x: hidden;
      transition: all 0.3s ease;
      z-index: 999;
    }

    [data-theme="dark"] .hms-sidebar {
      background: var(--bg-primary);
    }

    .hms-sidebar.collapsed {
      width: 70px;
    }

    .sidebar-nav {
      padding: 16px 0;
    }

    .nav-section-title {
      padding: 12px 20px 8px;
      font-size: 11px;
      font-weight: 600;
      color: var(--text-tertiary);
      text-transform: uppercase;
      letter-spacing: 0.8px;
      transition: all 0.3s ease;
    }

    .hms-sidebar.collapsed .nav-section-title {
      opacity: 0;
      height: 0;
      padding: 0;
      margin: 0;
      overflow: hidden;
    }

    .nav-item {
      margin: 0px 12px 4px;
    }

    .nav-link {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 11px 14px;
      color: var(--text-secondary);
      text-decoration: none;
      border-radius: 8px;
      transition: all 0.2s ease;
      font-size: 14px;
      font-weight: 500;
      position: relative;
    }

    .nav-link i {
      font-size: 19px;
      min-width: 19px;
      color: var(--text-secondary);
      transition: color 0.2s ease;
    }

    .nav-link:hover {
      background: var(--bg-tertiary);
      color: var(--text-primary);
    }

    .nav-link:hover i {
      color: var(--text-primary);
    }

    .nav-link.active {
      background: #111827;
      color: white;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .nav-link.active i {
      color: white;
    }

    .hms-sidebar.collapsed .nav-link {
      justify-content: center;
      padding: 11px;
    }

    .hms-sidebar.collapsed .nav-link span {
      display: none;
    }

    .hms-sidebar.collapsed .nav-link {
      gap: 0;
    }

    /* Main Content */
    .hms-main {
      margin-left: 260px;
      margin-top: 60px;
      padding: 10px 24px 0;
      transition: all 0.3s ease;
      min-height: calc(100vh - 60px);
    }

    .hms-main.expanded {
      margin-left: 70px;
    }

    /* Footer */
    .hms-footer {
      margin-top: 60px;
      padding: 24px 0;
      border-top: 1px solid var(--border-color);
      background: white;
    }

    .footer-content {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      align-items: center;
      font-size: 13px;
    }

    .footer-left {
      text-align: left;
    }

    .footer-center {
      text-align: center;
    }

    .footer-right {
      text-align: right;
    }

    .footer-text {
      color: var(--text-secondary);
    }

    .footer-link {
      color: var(--text-secondary);
      text-decoration: none;
      font-weight: 500;
      transition: color 0.2s;
    }

    .footer-link:hover {
      color: var(--text-primary);
    }

    /* Logout Form */
    .logout-form {
      display: inline;
    }
  </style>
</head>

<body>
  <!-- Optional Full Page Loader -->
  <div id="pageLoader" class="page-loader">
    <div class="loader-spinner"></div>
  </div>

  <!-- Top Header -->
  <header class="hms-header">
    <button class="sidebar-toggle" onclick="toggleSidebar()">
      <i class="bi bi-list" style="font-size: 24px;"></i>
    </button>

    <a href="{{ route('dashboard') }}" class="hms-logo">
      <i class="bi bi-hospital"></i>
      <span>HMS Pro</span>
    </a>

    <div class="header-actions">
      <button class="dark-mode-toggle" onclick="toggleDarkMode()" title="Toggle Dark Mode">
        <i class="bi bi-moon-stars-fill" id="darkModeIcon" style="font-size: 20px;"></i>
      </button>
      
      <button class="header-icon">
        <i class="bi bi-bell" style="font-size: 20px;"></i>
        <span class="badge">3</span>
      </button>

      <div class="user-profile" onclick="toggleProfileMenu()">
        <div class="user-avatar">
          {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
        </div>
        <div class="user-info">
          <span class="user-name">{{ auth()->user()->name ?? 'User' }}</span>
          <span class="user-role">
            @php
              $userRole = auth()->user()->roles->first();
              $roleName = $userRole ? $userRole->name : 'user';
            @endphp
            {{ ucwords(str_replace('-', ' ', $roleName)) }}
          </span>
        </div>
      </div>
    </div>
  </header>

  <!-- Breadcrumb Navigation -->
  <div class="breadcrumb-section">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-3">
        <li class="breadcrumb-item">
          <a href="{{ route('dashboard') }}">
            <i class="bi bi-house-door me-1"></i>Home
          </a>
        </li>
        @yield('breadcrumbs')
      </ol>
    </nav>
  </div>

  <!-- Sidebar -->
  <aside class="hms-sidebar" id="sidebar">
    <nav class="sidebar-nav">
      <div class="nav-section-title">Main</div>
      <ul class="list-unstyled">
        <li class="nav-item">
          <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
          </a>
        </li>
      </ul>

      @if(auth()->user()->can('patients.view') || auth()->user()->canAny(['appointments.view-all', 'appointments.view-own']))
      <div class="nav-section-title">Patient Management</div>
      <ul class="list-unstyled">
        @can('patients.view')
        <li class="nav-item">
          <a href="{{ route('patients.index') }}" class="nav-link {{ request()->routeIs('patients.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i>
            <span>Patients</span>
          </a>
        </li>
        @endcan
        @canany(['appointments.view-all', 'appointments.view-own'])
        <li class="nav-item">
          <a href="{{ route('appointments.index') }}" class="nav-link {{ request()->routeIs('appointments.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-check"></i>
            <span>Appointments</span>
          </a>
        </li>
        @endcanany
      </ul>
      @endif

      @if(auth()->user()->canAny(['nursing.view-dashboard', 'consultations.view-all', 'consultations.view-own', 'ipd.view-dashboard', 'lab.view-dashboard', 'radiology.view-dashboard', 'pharmacy.view-dashboard']))
      <div class="nav-section-title">Clinical</div>
      <ul class="list-unstyled">
        @canany(['consultations.view-all', 'consultations.view-own'])
        <li class="nav-item">
          <a href="{{ route('consultations.index') }}" class="nav-link {{ request()->routeIs('consultations.*') ? 'active' : '' }}">
            <i class="bi bi-file-medical"></i>
            <span>OPD / Consultations</span>
          </a>
        </li>
        @endcanany
        @can('ipd.view-dashboard')
        <li class="nav-item">
          <a href="{{ route('admissions.index') }}" class="nav-link {{ request()->routeIs('admissions.*') || request()->routeIs('ipd.*') || request()->routeIs('wards.*') || request()->routeIs('beds.*') ? 'active' : '' }}">
            <i class="bi bi-hospital"></i>
            <span>IPD / Admissions</span>
          </a>
        </li>
        @endcan
        @can('lab.view-dashboard')
        <li class="nav-item">
          <a href="{{ route('lab.dashboard') }}" class="nav-link {{ request()->routeIs('lab.*') && !request()->routeIs('radiology.*') ? 'active' : '' }}">
            <i class="bi bi-clipboard-pulse"></i>
            <span>Laboratory</span>
          </a>
        </li>
        @endcan
        @can('radiology.view-dashboard')
        <li class="nav-item">
          <a href="{{ route('radiology.dashboard') }}" class="nav-link {{ request()->routeIs('radiology.*') ? 'active' : '' }}">
            <i class="bi bi-camera"></i>
            <span>Radiology</span>
          </a>
        </li>
        @endcan
      </ul>
      @endif

      @if(auth()->user()->canAny(['billing.view-dashboard', 'billing.view-services', 'billing.view-bills']))
      <div class="nav-section-title">Accounting</div>
      <ul class="list-unstyled">
        @can('billing.view-dashboard')
        <li class="nav-item">
          <a href="{{ route('accounting.dashboard') }}" class="nav-link {{ request()->routeIs('accounting.*') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            <span>Accounting Dashboard</span>
          </a>
        </li>
        @endcan
        @can('billing.view-services')
        <li class="nav-item">
          <a href="{{ route('services.index') }}" class="nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}">
            <i class="bi bi-list-check"></i>
            <span>Service Catalog</span>
          </a>
        </li>
        @endcan
        @can('billing.view-bills')
        <li class="nav-item">
          <a href="{{ route('bills.index') }}" class="nav-link {{ request()->routeIs('bills.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text"></i>
            <span>Bills</span>
          </a>
        </li>
        @endcan
      </ul>
      @endif

      @if(auth()->user()->canAny(['users.view', 'settings.view']))
      <div class="nav-section-title">System</div>
      <ul class="list-unstyled">
        @can('users.view')
        <li class="nav-item">
          <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i>
            <span>User Management</span>
          </a>
        </li>
        @endcan
        @can('settings.view')
        <li class="nav-item">
          <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
            <i class="bi bi-gear"></i>
            <span>Settings</span>
          </a>
        </li>
        @endcan
      </ul>
      @endif

      <ul class="list-unstyled">
        <li class="nav-item">
          <form action="{{ route('logout') }}" method="POST" class="logout-form">
            @csrf
            <a href="#" class="nav-link" onclick="event.preventDefault(); this.closest('form').submit();">
              <i class="bi bi-box-arrow-right"></i>
              <span>Logout</span>
            </a>
          </form>
        </li>
      </ul>
    </nav>
  </aside>

  <!-- Main Content -->
  <main class="hms-main" id="mainContent">
    @yield('content')
    
    <!-- Footer -->
    <footer class="hms-footer">
      <div class="footer-content">
        <div class="footer-left">
          <span class="footer-text">HMS Pro</span>
        </div>
        <div class="footer-center">
          <span class="footer-text">Made with <i class="bi bi-heart-fill" style="color: #ef4444;"></i> by <a href="https://tynex.co.tz" target="_blank" rel="noopener" class="footer-link">Tynex</a></span>
        </div>
        <div class="footer-right">
          <span class="footer-text">Version 1.0.0</span>
        </div>
      </div>
    </footer>
  </main>

  <!-- Toast Notification Container -->
  <div id="toastContainer" style="position: fixed; top: 80px; right: 20px; z-index: 9999; min-width: 320px;"></div>

  <!-- Vendor JS Files -->
  <script src="{{ asset('theme/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  
  <!-- NProgress Loading Bar -->
  <script src="https://unpkg.com/nprogress@0.2.0/nprogress.js"></script>

  <script>
    // Toast Notification System
    function showToast(message, type = 'success', duration = 4000) {
      const container = document.getElementById('toastContainer');
      const toastId = 'toast-' + Date.now();
      const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
      
      const icons = {
        success: 'bi-check-circle-fill',
        error: 'bi-x-circle-fill',
        warning: 'bi-exclamation-triangle-fill',
        info: 'bi-info-circle-fill'
      };
      
      const colors = {
        success: '#48bb78',
        error: '#f56565',
        warning: '#ed8936',
        info: '#4299e1'
      };
      
      const toast = document.createElement('div');
      toast.id = toastId;
      toast.style.cssText = `
        background: ${isDark ? '#2d3748' : 'white'};
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 12px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, ${isDark ? '0.4' : '0.15'});
        display: flex;
        align-items: center;
        gap: 12px;
        animation: slideInRight 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-left: 4px solid ${colors[type]};
        position: relative;
        overflow: hidden;
      `;
      
      toast.innerHTML = `
        <div style="
          width: 40px;
          height: 40px;
          background: ${colors[type]}15;
          border-radius: 10px;
          display: flex;
          align-items: center;
          justify-content: center;
          font-size: 20px;
          color: ${colors[type]};
          flex-shrink: 0;
        ">
          <i class="bi ${icons[type]}"></i>
        </div>
        <div style="flex: 1; color: ${isDark ? '#f7fafc' : '#2d3748'}; font-size: 14px; font-weight: 500;">
          ${message}
        </div>
        <button onclick="removeToast('${toastId}')" 
                style="
                  background: transparent;
                  border: none;
                  color: ${isDark ? '#cbd5e0' : '#718096'};
                  cursor: pointer;
                  font-size: 18px;
                  padding: 4px 8px;
                  display: flex;
                  align-items: center;
                  justify-content: center;
                  transition: color 0.2s ease;
                "
                onmouseover="this.style.color='${isDark ? '#f7fafc' : '#2d3748'}'"
                onmouseout="this.style.color='${isDark ? '#cbd5e0' : '#718096'}'">
          <i class="bi bi-x"></i>
        </button>
        <div style="
          position: absolute;
          bottom: 0;
          left: 0;
          height: 3px;
          background: ${colors[type]};
          width: 100%;
          animation: shrink ${duration}ms linear;
        "></div>
      `;
      
      container.appendChild(toast);
      
      // Auto remove
      setTimeout(() => {
        removeToast(toastId);
      }, duration);
    }
    
    function removeToast(toastId) {
      const toast = document.getElementById(toastId);
      if (toast) {
        toast.style.animation = 'slideOutRight 0.3s cubic-bezier(0.4, 0, 1, 1)';
        setTimeout(() => toast.remove(), 300);
      }
    }
    
    // Add animations to document
    if (!document.querySelector('#toastAnimations')) {
      const style = document.createElement('style');
      style.id = 'toastAnimations';
      style.textContent = `
        @keyframes slideInRight {
          from {
            opacity: 0;
            transform: translateX(100%);
          }
          to {
            opacity: 1;
            transform: translateX(0);
          }
        }
        
        @keyframes slideOutRight {
          from {
            opacity: 1;
            transform: translateX(0);
          }
          to {
            opacity: 0;
            transform: translateX(100%);
          }
        }
        
        @keyframes shrink {
          from {
            width: 100%;
          }
          to {
            width: 0%;
          }
        }
      `;
      document.head.appendChild(style);
    }
    
    // Show Laravel flash messages as toasts
    document.addEventListener('DOMContentLoaded', function() {
      @if(session('success'))
        showToast("{{ session('success') }}", 'success');
      @endif
      
      @if(session('error'))
        showToast("{{ session('error') }}", 'error');
      @endif
      
      @if(session('warning'))
        showToast("{{ session('warning') }}", 'warning');
      @endif
      
      @if(session('info'))
        showToast("{{ session('info') }}", 'info');
      @endif
      
      @if($errors->any())
        @foreach($errors->all() as $error)
          showToast("{{ addslashes($error) }}", 'error', 6000);
        @endforeach
      @endif
    });

    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const mainContent = document.getElementById('mainContent');
      
      sidebar.classList.toggle('collapsed');
      mainContent.classList.toggle('expanded');
      
      // Save state to localStorage
      const isCollapsed = sidebar.classList.contains('collapsed');
      localStorage.setItem('sidebarCollapsed', isCollapsed);
    }

    function toggleProfileMenu() {
      // TODO: Implement profile dropdown menu
      console.log('Profile menu clicked');
    }

    // Dark Mode Toggle
    function toggleDarkMode() {
      const html = document.documentElement;
      const currentTheme = html.getAttribute('data-theme');
      const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
      const icon = document.getElementById('darkModeIcon');
      
      html.setAttribute('data-theme', newTheme);
      localStorage.setItem('theme', newTheme);
      
      // Update icon
      if (newTheme === 'dark') {
        icon.className = 'bi bi-sun-fill';
      } else {
        icon.className = 'bi bi-moon-stars-fill';
      }
      
      // Show toast notification
      if (typeof showToast === 'function') {
        showToast(`${newTheme === 'dark' ? 'Dark' : 'Light'} mode activated`, 'success');
      }
    }

    // Restore sidebar state and theme on page load
    document.addEventListener('DOMContentLoaded', function() {
      // Sidebar state
      const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
      if (sidebarCollapsed) {
        document.getElementById('sidebar').classList.add('collapsed');
        document.getElementById('mainContent').classList.add('expanded');
      }
      
      // Theme state
      const savedTheme = localStorage.getItem('theme') || 'light';
      document.documentElement.setAttribute('data-theme', savedTheme);
      const icon = document.getElementById('darkModeIcon');
      if (savedTheme === 'dark') {
        icon.className = 'bi bi-sun-fill';
      } else {
        icon.className = 'bi bi-moon-stars-fill';
      }
    });
  </script>

  <script>
    // ===== Loading & Progress System =====
    
    // Configure NProgress
    NProgress.configure({ 
      showSpinner: false,
      trickleSpeed: 200,
      minimum: 0.08,
      easing: 'ease',
      speed: 500
    });

    // Show loading bar on page navigation
    document.addEventListener('DOMContentLoaded', function() {
      // Start NProgress on any link click
      document.addEventListener('click', function(e) {
        const link = e.target.closest('a');
        if (link && link.href && !link.hasAttribute('data-bs-toggle') && 
            !link.hasAttribute('download') && !link.getAttribute('href').startsWith('#') &&
            link.target !== '_blank') {
          NProgress.start();
        }
      });

      // Complete NProgress when page loads
      window.addEventListener('load', function() {
        NProgress.done();
      });

      // Handle browser back/forward buttons
      window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
          NProgress.done();
        }
      });
    });

    // Form submission loading handler
    function handleFormSubmit(form, submitButton) {
      if (!submitButton) {
        submitButton = form.querySelector('button[type="submit"]');
      }
      
      if (submitButton && !submitButton.classList.contains('btn-loading')) {
        // Wrap button text if not already wrapped
        if (!submitButton.querySelector('.btn-text')) {
          const text = submitButton.innerHTML;
          submitButton.innerHTML = `<span class="btn-text">${text}</span>`;
        }
        
        // Add loading class
        submitButton.classList.add('btn-loading');
        submitButton.disabled = true;
        
        // Show NProgress
        NProgress.start();
      }
    }

    // Auto-attach to all forms with class 'form-loading'
    document.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('form.form-loading').forEach(form => {
        form.addEventListener('submit', function(e) {
          const submitButton = this.querySelector('button[type="submit"]');
          handleFormSubmit(this, submitButton);
        });
      });
    });

    // Manual button loading control
    function showButtonLoading(button) {
      if (typeof button === 'string') {
        button = document.querySelector(button);
      }
      
      if (button && !button.classList.contains('btn-loading')) {
        if (!button.querySelector('.btn-text')) {
          const text = button.innerHTML;
          button.innerHTML = `<span class="btn-text">${text}</span>`;
        }
        button.classList.add('btn-loading');
        button.disabled = true;
      }
    }

    function hideButtonLoading(button) {
      if (typeof button === 'string') {
        button = document.querySelector(button);
      }
      
      if (button) {
        button.classList.remove('btn-loading');
        button.disabled = false;
      }
    }

    // Full page loader control
    function showPageLoader() {
      const loader = document.getElementById('pageLoader');
      if (loader) {
        loader.classList.add('active');
      }
      NProgress.start();
    }

    function hidePageLoader() {
      const loader = document.getElementById('pageLoader');
      if (loader) {
        loader.classList.remove('active');
      }
      NProgress.done();
    }

    // Expose functions globally
    window.showButtonLoading = showButtonLoading;
    window.hideButtonLoading = hideButtonLoading;
    window.showPageLoader = showPageLoader;
    window.hidePageLoader = hidePageLoader;
    window.handleFormSubmit = handleFormSubmit;
  </script>

  @stack('scripts')

</body>
</html>
