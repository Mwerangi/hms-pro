@extends('layouts.app')

@section('title', 'All Admissions')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('ipd.dashboard') }}">IPD</a></li>
<li class="breadcrumb-item active" aria-current="page">Admissions</li>
@endsection

@push('styles')
<style>
  .admissions-container { max-width: 1600px; margin: 0 auto; }
  .page-header { background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px 32px; margin-bottom: 24px; }
  .page-title { font-size: 24px; font-weight: 600; color: #111827; margin: 0 0 8px 0; }
  .page-subtitle { font-size: 14px; color: #6b7280; margin: 0; }
  
  /* Quick Actions */
  .quick-actions { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; }
  .action-card { background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; text-align: center; transition: all 0.2s; cursor: pointer; text-decoration: none; display: block; }
  .action-card:hover { border-color: #111827; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
  .action-icon { width: 48px; height: 48px; border-radius: 12px; background: #f9fafb; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; font-size: 24px; color: #111827; }
  .action-card:hover .action-icon { background: #111827; color: white; }
  .action-title { font-size: 14px; font-weight: 600; color: #111827; margin-bottom: 4px; }
  .action-subtitle { font-size: 12px; color: #6b7280; }
  
  .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; }
  .stat-card { background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; }
  .stat-value { font-size: 32px; font-weight: 700; color: #111827; margin-bottom: 4px; }
  .stat-label { font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; }
  .stat-card.admitted { border-left: 4px solid #065f46; }
  .stat-card.admitted .stat-value { color: #065f46; }
  .stat-card.discharged { border-left: 4px solid #374151; }
  .stat-card.discharged .stat-value { color: #374151; }
  .filters-card { background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; margin-bottom: 24px; }
  .filter-group { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; align-items: end; }
  .form-label { font-size: 13px; font-weight: 500; color: #374151; margin-bottom: 6px; }
  .form-control, .form-select { border: 1px solid #e5e7eb; border-radius: 8px; padding: 10px 14px; font-size: 14px; transition: all 0.2s; }
  .form-control:focus, .form-select:focus { border-color: #111827; box-shadow: 0 0 0 3px rgba(17, 24, 39, 0.1); }
  .btn-primary-custom { background: #111827; color: white; border: 1px solid #111827; padding: 10px 20px; border-radius: 8px; font-weight: 500; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s; text-decoration: none; }
  .btn-primary-custom:hover { background: #374151; border-color: #374151; color: white; transform: translateY(-1px); }
  .btn-outline-custom { background: white; color: #111827; border: 1px solid #e5e7eb; }
  .btn-outline-custom:hover { background: #f9fafb; border-color: #d1d5db; color: #111827; }
  .table-card { background: white; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; }
  .table-header { padding: 20px 24px; border-bottom: 2px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; }
  .table-title { font-size: 16px; font-weight: 600; color: #111827; margin: 0; }
  .admissions-table { width: 100%; }
  .admissions-table thead th { background: #f9fafb; color: #6b7280; font-size: 12px; font-weight: 600; text-transform: uppercase; padding: 14px 20px; border-bottom: 2px solid #e5e7eb; text-align: left; }
  .admissions-table tbody td { padding: 16px 20px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #111827; }
  .admissions-table tbody tr:hover { background: #f9fafb; }
  .patient-info { display: flex; align-items: center; gap: 12px; }
  .patient-avatar { width: 40px; height: 40px; border-radius: 50%; background: #111827; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 14px; flex-shrink: 0; }
  .patient-details { }
  .patient-name { font-weight: 600; color: #111827; display: block; margin-bottom: 2px; }
  .patient-id { font-size: 12px; color: #6b7280; }
  .badge-status { padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; display: inline-block; }
  .badge-admitted { background: #d1fae5; color: #065f46; }
  .badge-discharged { background: #f3f4f6; color: #374151; }
  .badge-emergency { background: #fee2e2; color: #991b1b; }
  .badge-scheduled { background: #dbeafe; color: #1e40af; }
  .empty-state { text-align: center; padding: 64px 24px; color: #9ca3af; }
  .empty-icon { font-size: 64px; margin-bottom: 16px; opacity: 0.5; }
  .empty-text { font-size: 16px; font-weight: 500; margin-bottom: 8px; }
  .empty-subtext { font-size: 14px; color: #9ca3af; }
  .pagination { margin-top: 24px; }
  .days-badge { background: #f9fafb; border: 1px solid #e5e7eb; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; color: #111827; }
</style>
@endpush

@section('content')
<div class="admissions-container">
  <!-- Page Header -->
  <div class="page-header">
    <div class="d-flex justify-content-between align-items-start">
      <div>
        <h1 class="page-title">IPD / Inpatient Department</h1>
        <p class="page-subtitle">Manage admissions, wards, beds, and inpatient services</p>
      </div>
      <a href="{{ route('admissions.create') }}" class="btn-primary-custom">
        <i class="bi bi-plus-circle"></i> New Admission
      </a>
    </div>
  </div>

  <!-- Quick Actions -->
  <div class="quick-actions">
    <a href="{{ route('admissions.create') }}" class="action-card">
      <div class="action-icon"><i class="bi bi-plus-circle"></i></div>
      <div class="action-title">New Admission</div>
      <div class="action-subtitle">Admit a new patient</div>
    </a>
    <a href="{{ route('ipd.dashboard') }}" class="action-card">
      <div class="action-icon"><i class="bi bi-grid-3x3"></i></div>
      <div class="action-title">Wards & Beds</div>
      <div class="action-subtitle">View ward bed grid</div>
    </a>
    <a href="{{ route('bills.index') }}" class="action-card">
      <div class="action-icon"><i class="bi bi-receipt"></i></div>
      <div class="action-title">IPD Billing</div>
      <div class="action-subtitle">Patient bills & charges</div>
    </a>
    <a href="{{ route('nursing.dashboard') }}" class="action-card">
      <div class="action-icon"><i class="bi bi-heart-pulse"></i></div>
      <div class="action-title">Nursing Station</div>
      <div class="action-subtitle">Patient vitals & care</div>
    </a>
  </div>

  <!-- Statistics -->
  <div class="stats-grid">
    <div class="stat-card admitted">
      <div class="stat-value">{{ $admissions->where('status', 'admitted')->count() }}</div>
      <div class="stat-label">Currently Admitted</div>
    </div>
    <div class="stat-card discharged">
      <div class="stat-value">{{ $admissions->where('status', 'discharged')->count() }}</div>
      <div class="stat-label">Discharged (This Page)</div>
    </div>
    <div class="stat-card">
      <div class="stat-value">{{ $admissions->total() }}</div>
      <div class="stat-label">Total Admissions</div>
    </div>
    <div class="stat-card">
      <div class="stat-value">
        @php
          $avgStay = $admissions->where('status', 'discharged')->avg(function($admission) {
            return \Carbon\Carbon::parse($admission->admission_date)->diffInDays($admission->discharge_date);
          });
        @endphp
        {{ $avgStay ? number_format($avgStay, 1) : 'N/A' }}
      </div>
      <div class="stat-label">Avg Stay (Days)</div>
    </div>
  </div>

  <!-- Filters -->
  <div class="filters-card">
    <form action="{{ route('admissions.index') }}" method="GET">
      <div class="filter-group">
        <div>
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            <option value="">All Admissions</option>
            <option value="admitted" {{ request('status') === 'admitted' ? 'selected' : '' }}>Currently Admitted</option>
            <option value="discharged" {{ request('status') === 'discharged' ? 'selected' : '' }}>Discharged</option>
          </select>
        </div>
        <div>
          <label class="form-label">Ward</label>
          <select name="ward_id" class="form-select">
            <option value="">All Wards</option>
            @foreach(\App\Models\Ward::orderBy('ward_name')->get() as $ward)
              <option value="{{ $ward->id }}" {{ request('ward_id') == $ward->id ? 'selected' : '' }}>
                {{ $ward->ward_name }}
              </option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="form-label">Admission Type</label>
          <select name="admission_type" class="form-select">
            <option value="">All Types</option>
            <option value="emergency" {{ request('admission_type') === 'emergency' ? 'selected' : '' }}>Emergency</option>
            <option value="scheduled" {{ request('admission_type') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
          </select>
        </div>
        <div>
          <label class="form-label">Search Patient</label>
          <input type="text" name="search" class="form-control" placeholder="Name or ID..." value="{{ request('search') }}">
        </div>
        <div style="display: flex; gap: 8px;">
          <button type="submit" class="btn-primary-custom">
            <i class="bi bi-search"></i> Filter
          </button>
          <a href="{{ route('admissions.index') }}" class="btn-primary-custom btn-outline-custom">
            <i class="bi bi-x-circle"></i> Clear
          </a>
        </div>
      </div>
    </form>
  </div>

  <!-- Admissions Table -->
  <div class="table-card">
    <div class="table-header">
      <h5 class="table-title">
        <i class="bi bi-list-ul me-2"></i>
        Admissions List ({{ $admissions->total() }})
      </h5>
    </div>

    @if($admissions->count() > 0)
    <div class="table-responsive">
      <table class="admissions-table">
        <thead>
          <tr>
            <th>Patient</th>
            <th>Admission #</th>
            <th>Admission Date</th>
            <th>Days</th>
            <th>Ward / Bed</th>
            <th>Doctor</th>
            <th>Diagnosis</th>
            <th>Type</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($admissions as $admission)
          <tr>
            <td>
              <div class="patient-info">
                <div class="patient-avatar">
                  {{ substr($admission->patient->first_name, 0, 1) }}{{ substr($admission->patient->last_name, 0, 1) }}
                </div>
                <div class="patient-details">
                  <a href="{{ route('patients.show', $admission->patient) }}" class="patient-name">
                    {{ $admission->patient->full_name }}
                  </a>
                  <span class="patient-id">{{ $admission->patient->patient_id }}</span>
                </div>
              </div>
            </td>
            <td><strong>{{ $admission->admission_number }}</strong></td>
            <td>
              {{ \Carbon\Carbon::parse($admission->admission_date)->format('M d, Y') }}
              <br>
              <small style="color: #6b7280;">{{ \Carbon\Carbon::parse($admission->admission_date)->format('h:i A') }}</small>
            </td>
            <td>
              @php
                $days = (int) \Carbon\Carbon::parse($admission->admission_date)->diffInDays($admission->discharge_date ?? now());
              @endphp
              <span class="days-badge">{{ $days }} {{ $days === 1 ? 'day' : 'days' }}</span>
            </td>
            <td>
              <strong>{{ $admission->ward->ward_name }}</strong>
              <br>
              <small style="color: #6b7280;">
                {{ $admission->bed ? 'Bed: ' . $admission->bed->bed_number : 'No bed assigned' }}
              </small>
            </td>
            <td>Dr. {{ $admission->doctor->name }}</td>
            <td>{{ Str::limit($admission->provisional_diagnosis, 35) }}</td>
            <td>
              <span class="badge-status badge-{{ $admission->admission_type === 'emergency' ? 'emergency' : 'scheduled' }}">
                {{ ucfirst($admission->admission_type) }}
              </span>
            </td>
            <td>
              <span class="badge-status badge-{{ $admission->status === 'admitted' ? 'admitted' : 'discharged' }}">
                @if($admission->status === 'admitted')
                  <i class="bi bi-hospital-fill me-1"></i>
                @else
                  <i class="bi bi-box-arrow-right me-1"></i>
                @endif
                {{ ucfirst($admission->status) }}
              </span>
            </td>
            <td>
              <div class="d-flex gap-2">
                <a href="{{ route('admissions.show', $admission) }}" class="btn btn-sm btn-outline-secondary" title="View Details">
                  <i class="bi bi-eye"></i>
                </a>
                @if($admission->status === 'admitted')
                <a href="{{ route('admissions.edit', $admission) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                  <i class="bi bi-pencil"></i>
                </a>
                @endif
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="px-4 py-3">
      {{ $admissions->links() }}
    </div>
    @else
    <div class="empty-state">
      <div class="empty-icon"><i class="bi bi-inbox"></i></div>
      <div class="empty-text">No admissions found</div>
      <div class="empty-subtext">
        @if(request()->hasAny(['status', 'ward_id', 'admission_type', 'search']))
          Try adjusting your filters
        @else
          Start by admitting a new patient
        @endif
      </div>
    </div>
    @endif
  </div>
</div>
@endsection
