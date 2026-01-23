@extends('layouts.app')

@push('styles')
<style>
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

  .filter-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 24px;
  }

  .section-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 24px;
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
      <h1 class="page-title">All Lab Orders</h1>
      <p class="page-subtitle">Complete list of laboratory and imaging orders</p>
    </div>
    <div class="d-flex gap-2">
      <a href="{{ route('lab.dashboard') }}" class="btn btn-outline-primary btn-sm me-2">
        <i class="bi bi-speedometer2"></i> Lab Dashboard
      </a>
      <a href="{{ route('radiology.dashboard') }}" class="btn btn-outline-info btn-sm">
        <i class="bi bi-camera"></i> Radiology Dashboard
      </a>
    </div>
  </div>

  <!-- Filters -->
  <div class="filter-card">
    <form action="{{ route('lab.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Test Type</label>
                    <select name="type" class="form-select" onchange="this.form.submit()">
                        <option value="all" {{ request('type') == 'all' ? 'selected' : '' }}>All Types</option>
                        <option value="blood" {{ request('type') == 'blood' ? 'selected' : '' }}>Blood</option>
                        <option value="urine" {{ request('type') == 'urine' ? 'selected' : '' }}>Urine</option>
                        <option value="stool" {{ request('type') == 'stool' ? 'selected' : '' }}>Stool</option>
                        <option value="imaging" {{ request('type') == 'imaging' ? 'selected' : '' }}>Imaging</option>
                        <option value="pathology" {{ request('type') == 'pathology' ? 'selected' : '' }}>Pathology</option>
                        <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="sample-collected" {{ request('status') == 'sample-collected' ? 'selected' : '' }}>Sample Collected</option>
                        <option value="in-progress" {{ request('status') == 'in-progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="reported" {{ request('status') == 'reported' ? 'selected' : '' }}>Reported</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Urgency</label>
                    <select name="urgency" class="form-select" onchange="this.form.submit()">
                        <option value="all" {{ request('urgency') == 'all' ? 'selected' : '' }}>All Urgency</option>
                        <option value="routine" {{ request('urgency') == 'routine' ? 'selected' : '' }}>Routine</option>
                        <option value="urgent" {{ request('urgency') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        <option value="stat" {{ request('urgency') == 'stat' ? 'selected' : '' }}>STAT</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <a href="{{ route('lab.index') }}" class="btn btn-outline-secondary w-100 btn-sm">
                        <i class="bi bi-x-circle"></i> Clear Filters
                    </a>
                </div>
            </form>
  </div>

  <!-- Lab Orders Table -->
  <div class="section-card">
    @if($labOrders->count() > 0)
      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>Order #</th>
              <th>Date</th>
              <th>Patient</th>
              <th>Type</th>
              <th>Tests</th>
              <th>Doctor</th>
              <th>Urgency</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
                            @foreach($labOrders as $order)
                            <tr>
                                <td><strong>{{ $order->order_number }}</strong></td>
                                <td>{{ $order->order_date->format('M d, Y') }}</td>
                                <td>
                                    <div>{{ $order->patient->full_name }}</div>
                                    <small class="text-muted">{{ $order->patient->patient_id }}</small>
                                </td>
                                <td><span class="badge bg-secondary">{{ ucfirst($order->test_type) }}</span></td>
                                <td><small>{{ Str::limit($order->tests_list, 30) }}</small></td>
                                <td><small>{{ $order->doctor->name }}</small></td>
                                <td>{!! $order->urgency_badge !!}</td>
                                <td>{!! $order->status_badge !!}</td>
                                <td>
                                    <a href="{{ route('lab.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="d-flex justify-content-center mt-4">
        {{ $labOrders->links() }}
      </div>
    @else
      <div class="empty-state">
        <i class="bi bi-inbox"></i>
        <p>No lab orders found</p>
      </div>
    @endif
  </div>
</div>
@endsection
