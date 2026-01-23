@extends('layouts.app')

@section('title', 'Service Catalog')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active" aria-current="page">Services</li>
@endsection

@push('styles')
<style>
  .service-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 16px;
    transition: all 0.2s;
  }

  .service-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  }

  .category-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
  }

  .badge-consultation { background: #dbeafe; color: #1e40af; }
  .badge-laboratory { background: #dcfce7; color: #15803d; }
  .badge-radiology { background: #fce7f3; color: #be123c; }
  .badge-procedure { background: #fef3c7; color: #92400e; }
  .badge-pharmacy { background: #ede9fe; color: #6b21a8; }
  .badge-room_charge { background: #e0e7ff; color: #4338ca; }
  .badge-nursing_care { background: #fce7f3; color: #9f1239; }
  .badge-emergency { background: #fee2e2; color: #991b1b; }
  .badge-surgery { background: #cffafe; color: #155e75; }
  .badge-other { background: #f3f4f6; color: #374151; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 style="font-size: 24px; font-weight: 600; margin: 0;">Service Catalog</h1>
  <a href="{{ route('services.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-circle me-1"></i>Add New Service
  </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
  {{ session('success') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>Code</th>
            <th>Service Name</th>
            <th>Category</th>
            <th>Department</th>
            <th>Standard Charge</th>
            <th>Tax</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($services as $service)
          <tr>
            <td><code>{{ $service->service_code }}</code></td>
            <td>{{ $service->service_name }}</td>
            <td>
              <span class="category-badge badge-{{ $service->category }}">
                {{ ucfirst(str_replace('_', ' ', $service->category)) }}
              </span>
            </td>
            <td>{{ $service->department ?? 'N/A' }}</td>
            <td><strong>${{ number_format($service->standard_charge, 2) }}</strong></td>
            <td>
              @if($service->taxable)
                <span class="badge bg-info">{{ $service->tax_percentage }}%</span>
              @else
                <span class="text-muted">-</span>
              @endif
            </td>
            <td>
              @if($service->is_active)
                <span class="badge bg-success">Active</span>
              @else
                <span class="badge bg-secondary">Inactive</span>
              @endif
            </td>
            <td>
              <div class="btn-group btn-group-sm">
                <a href="{{ route('services.edit', $service) }}" class="btn btn-outline-primary">
                  <i class="bi bi-pencil"></i>
                </a>
                <form action="{{ route('services.toggle-status', $service) }}" method="POST" class="d-inline">
                  @csrf
                  <button type="submit" class="btn btn-outline-{{ $service->is_active ? 'warning' : 'success' }}">
                    <i class="bi bi-{{ $service->is_active ? 'pause' : 'play' }}-circle"></i>
                  </button>
                </form>
                <form action="{{ route('services.destroy', $service) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-outline-danger">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" class="text-center text-muted py-4">No services found. Add your first service!</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
