@extends('layouts.app')

@section('title', 'Radiology & Imaging Dashboard')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('radiology.dashboard') }}">Radiology</a></li>
<li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@push('styles')
<style>
  /* Modern Minimalistic Design */
  .page-header {
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

  .section-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 24px;
  }

  .section-title {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 20px;
  }

  table {
    width: 100%;
  }

  thead th {
    background: #f9fafb;
    padding: 12px;
    font-weight: 600;
    font-size: 12px;
    color: #6b7280;
    border-bottom: 1px solid #e5e7eb;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  tbody td {
    padding: 16px 12px;
    border-bottom: 1px solid #f3f4f6;
    font-size: 14px;
    vertical-align: middle;
    color: #374151;
  }

  tbody tr:hover {
    background: #f9fafb;
  }

  .table-danger {
    background: #fef2f2 !important;
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

  .empty-state p {
    font-size: 14px;
    margin: 0;
  }

  .badge {
    font-size: 11px;
    padding: 4px 8px;
    font-weight: 600;
  }

  .btn-sm { padding: 6px 12px; font-size: 13px; }
</style>
@endpush

@section('content')
<div class="container-fluid">
  <!-- Page Header -->
  <div class="page-header d-flex justify-content-between align-items-center">
    <div>
      <h1 class="page-title">Radiology & Imaging Dashboard</h1>
      <p class="page-subtitle">Manage imaging requests and radiology reports</p>
    </div>
    <div>
      <a href="{{ route('lab.index') }}?type=imaging" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-list-ul"></i> All Imaging Orders
      </a>
    </div>
  </div>

  <!-- Statistics -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon">
        <i class="bi bi-camera"></i>
      </div>
      <div class="stat-value">{{ $stats['pending'] }}</div>
      <div class="stat-label">Pending Imaging</div>
    </div>
    
    <div class="stat-card">
      <div class="stat-icon">
        <i class="bi bi-hourglass-split"></i>
      </div>
      <div class="stat-value">{{ $stats['in_progress'] }}</div>
      <div class="stat-label">In Progress</div>
    </div>
    
    <div class="stat-card">
      <div class="stat-icon">
        <i class="bi bi-exclamation-triangle"></i>
      </div>
      <div class="stat-value">{{ $stats['urgent'] }}</div>
      <div class="stat-label">Urgent</div>
    </div>
  </div>

  <!-- Imaging Queue -->
  <div class="section-card">
    <h5 class="section-title"><i class="bi bi-camera-fill me-2"></i>Imaging Queue</h5>
    @if($pendingOrders->count() > 0)
      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>Order #</th>
              <th>Patient</th>
              <th>Imaging Type</th>
              <th>Category</th>
              <th>Urgency</th>
              <th>Priority</th>
              <th>Status</th>
              <th>Scheduled</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
                            @foreach($pendingOrders as $order)
                            <tr class="{{ $order->isUrgent() || $order->isCritical() ? 'table-danger' : '' }}">
                                <td>
                                    <strong>{{ $order->order_number }}</strong>
                                    @if($order->isCritical())
                                        <span class="badge bg-danger ms-1">CRITICAL</span>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ $order->patient->full_name }}</div>
                                    <small class="text-muted">{{ $order->patient->patient_id }}</small>
                                </td>
                                <td>
                                    <small>{{ $order->tests_list }}</small>
                                </td>
                                <td>
                                    @if($order->test_category)
                                        <span class="badge bg-secondary">{{ $order->test_category }}</span>
                                    @endif
                                </td>
                                <td>{!! $order->urgency_badge !!}</td>
                                <td>{!! $order->priority_badge !!}</td>
                                <td>{!! $order->status_badge !!}</td>
                                <td>
                                    @if($order->scheduled_at)
                                        <small>{{ $order->scheduled_at->format('M d, Y h:i A') }}</small>
                                    @else
                                        <small class="text-muted">Not scheduled</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('lab.show', $order) }}" class="btn btn-outline-primary" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        
                                        @if($order->canProcess())
                                            <a href="{{ route('lab.radiology-process', $order) }}" class="btn btn-outline-success" title="Process & Report">
                                                <i class="bi bi-camera"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <div class="empty-state">
        <i class="bi bi-camera"></i>
        <p>No pending imaging orders</p>
      </div>
    @endif
  </div>
</div>
@endsection
