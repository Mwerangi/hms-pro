@extends('layouts.app')

@section('title', 'Ward Details - ' . $ward->ward_name)

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('ipd.dashboard') }}">IPD</a></li>
<li class="breadcrumb-item"><a href="{{ route('wards.index') }}">Wards</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ $ward->ward_name }}</li>
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

  .stat-value {
    font-size: 28px;
    font-weight: 600;
    color: #111827;
  }

  .stat-label {
    font-size: 13px;
    color: #6b7280;
    margin-top: 4px;
  }

  .info-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
  }

  .info-row {
    display: flex;
    padding: 12px 0;
    border-bottom: 1px solid #f3f4f6;
  }

  .info-row:last-child {
    border-bottom: none;
  }

  .info-label {
    font-size: 13px;
    color: #6b7280;
    font-weight: 500;
    min-width: 180px;
  }

  .info-value {
    font-size: 14px;
    color: #111827;
  }

  .bed-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 16px;
    margin-top: 24px;
  }

  .bed-card {
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    padding: 16px;
    text-align: center;
    transition: all 0.2s ease;
  }

  .bed-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  }

  .bed-card.available {
    border-color: #10b981;
    background: #f0fdf4;
  }

  .bed-card.occupied {
    border-color: #f59e0b;
    background: #fffbeb;
  }

  .bed-card.cleaning {
    border-color: #3b82f6;
    background: #eff6ff;
  }

  .bed-card.maintenance {
    border-color: #ef4444;
    background: #fef2f2;
  }

  .bed-number {
    font-size: 18px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 8px;
  }

  .bed-status {
    font-size: 12px;
    padding: 4px 10px;
    border-radius: 6px;
    display: inline-block;
    font-weight: 500;
  }

  .bed-features {
    margin-top: 12px;
    font-size: 11px;
    color: #6b7280;
  }

  .badge {
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
  }

  .badge-success { background: #d1fae5; color: #065f46; }
  .badge-warning { background: #fef3c7; color: #92400e; }
  .badge-danger { background: #fee2e2; color: #991b1b; }
  .badge-info { background: #dbeafe; color: #1e40af; }

  .btn-primary-custom {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    display: inline-block;
  }

  .btn-outline {
    border: 1px solid #e5e7eb;
    background: white;
    color: #374151;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    display: inline-block;
  }
</style>
@endpush

@section('content')
<div class="page-header">
  <div>
    <h1 class="page-title">{{ $ward->ward_name }}</h1>
    <span class="badge badge-{{ $ward->ward_type === 'icu' ? 'danger' : ($ward->ward_type === 'private' ? 'info' : 'success') }}">
      {{ ucfirst(str_replace('-', ' ', $ward->ward_type)) }}
    </span>
  </div>
  <div>
    <a href="{{ route('beds.create') }}?ward_id={{ $ward->id }}" class="btn-primary-custom me-2">
      <i class="bi bi-plus-circle me-1"></i>Add Bed
    </a>
    <a href="{{ route('wards.edit', $ward) }}" class="btn-outline me-2">
      <i class="bi bi-pencil me-1"></i>Edit Ward
    </a>
    <a href="{{ route('wards.index') }}" class="btn-outline">
      <i class="bi bi-arrow-left me-1"></i>Back
    </a>
  </div>
</div>

<!-- Statistics -->
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-value">{{ $stats['total_beds'] }}</div>
    <div class="stat-label">Total Beds</div>
  </div>
  <div class="stat-card">
    <div class="stat-value" style="color: #10b981;">{{ $stats['available'] }}</div>
    <div class="stat-label">Available</div>
  </div>
  <div class="stat-card">
    <div class="stat-value" style="color: #f59e0b;">{{ $stats['occupied'] }}</div>
    <div class="stat-label">Occupied</div>
  </div>
  <div class="stat-card">
    <div class="stat-value" style="color: #3b82f6;">{{ $stats['cleaning'] }}</div>
    <div class="stat-label">Cleaning</div>
  </div>
  <div class="stat-card">
    <div class="stat-value" style="color: #ef4444;">{{ $stats['maintenance'] }}</div>
    <div class="stat-label">Maintenance</div>
  </div>
  <div class="stat-card">
    <div class="stat-value">{{ $ward->occupancy_rate }}%</div>
    <div class="stat-label">Occupancy Rate</div>
  </div>
</div>

<!-- Ward Information -->
<div class="info-card">
  <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 16px;">Ward Information</h3>
  <div class="info-row">
    <div class="info-label">Ward Number</div>
    <div class="info-value"><strong>{{ $ward->ward_number }}</strong></div>
  </div>
  <div class="info-row">
    <div class="info-label">Ward Type</div>
    <div class="info-value">{{ ucfirst(str_replace('-', ' ', $ward->ward_type)) }}</div>
  </div>
  <div class="info-row">
    <div class="info-label">Location</div>
    <div class="info-value">{{ $ward->building ?? 'N/A' }} - Floor {{ $ward->floor ?? 'N/A' }}</div>
  </div>
  <div class="info-row">
    <div class="info-label">Nurse in Charge</div>
    <div class="info-value">
      @if($ward->nurse)
        {{ $ward->nurse->name }}
        <span style="color: #6b7280; font-size: 13px;">({{ $ward->nurse->email }})</span>
      @else
        <span style="color: #9ca3af;">Not assigned</span>
      @endif
    </div>
  </div>
  <div class="info-row" style="background: #f9fafb; padding: 16px; border-radius: 8px; margin-top: 8px;">
    <div class="info-label" style="align-self: center;">Quick Reassign Nurse</div>
    <div style="flex: 1;">
      <form action="{{ route('wards.assign-nurse', $ward) }}" method="POST" style="display: flex; gap: 8px; align-items: center;">
        @csrf
        <select name="nurse_id" class="form-control" style="max-width: 300px; font-size: 13px;">
          <option value="">-- No Nurse --</option>
          @foreach($nurses as $nurse)
            <option value="{{ $nurse->id }}" {{ $ward->nurse_id == $nurse->id ? 'selected' : '' }}>
              {{ $nurse->name }}
            </option>
          @endforeach
        </select>
        <button type="submit" class="btn btn-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 8px 16px; border-radius: 6px; font-size: 13px; white-space: nowrap;">
          <i class="bi bi-person-check me-1"></i>Assign
        </button>
      </form>
      <small style="color: #6b7280; font-size: 12px; display: block; margin-top: 4px;">
        <i class="bi bi-info-circle me-1"></i>Quickly change the nurse assigned to this ward
      </small>
    </div>
  </div>
  <div class="info-row">
    <div class="info-label">Contact Number</div>
    <div class="info-value">{{ $ward->contact_number ?? 'N/A' }}</div>
  </div>
  <div class="info-row">
    <div class="info-label">Base Charge/Day</div>
    <div class="info-value"><strong>${{ number_format($ward->base_charge_per_day, 2) }}</strong></div>
  </div>
  @if($ward->description)
  <div class="info-row">
    <div class="info-label">Description</div>
    <div class="info-value">{{ $ward->description }}</div>
  </div>
  @endif
</div>

<!-- Beds -->
<div class="info-card">
  <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 4px;">Beds ({{ $ward->beds->count() }})</h3>
  
  @if($ward->beds->count() > 0)
  <div class="bed-grid">
    @foreach($ward->beds as $bed)
    <div class="bed-card {{ $bed->status }}">
      <div class="bed-number">{{ $bed->bed_label ?? $bed->bed_number }}</div>
      <span class="bed-status badge-{{ $bed->status === 'available' ? 'success' : ($bed->status === 'occupied' ? 'warning' : 'info') }}">
        {{ ucfirst(str_replace('_', ' ', $bed->status)) }}
      </span>
      
      @if($bed->has_oxygen || $bed->has_ventilator || $bed->has_monitor)
      <div class="bed-features">
        @if($bed->has_oxygen)<i class="bi bi-droplet-fill"></i> O₂ @endif
        @if($bed->has_ventilator)<i class="bi bi-fan"></i> Vent @endif
        @if($bed->has_monitor)<i class="bi bi-activity"></i> Monitor @endif
      </div>
      @endif

      @if($bed->status === 'occupied' && $bed->currentAdmission)
      <div style="margin-top: 8px; font-size: 11px; color: #6b7280;">
        <i class="bi bi-person-fill"></i> {{ $bed->currentAdmission->patient->full_name }}
      </div>
      @endif
    </div>
    @endforeach
  </div>
  @else
  <div style="text-align: center; padding: 40px; color: #9ca3af;">
    <i class="bi bi-grid-3x2" style="font-size: 48px; opacity: 0.5; margin-bottom: 16px; display: block;"></i>
    <p style="margin: 0;">No beds configured for this ward yet.</p>
    <a href="{{ route('beds.create') }}?ward_id={{ $ward->id }}" class="btn-primary-custom mt-3">
      Add First Bed
    </a>
  </div>
  @endif
</div>
@endsection
