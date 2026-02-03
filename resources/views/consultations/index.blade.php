@extends('layouts.app')

@section('title', 'OPD / Consultations')

@section('breadcrumbs')
<li class="breadcrumb-item active" aria-current="page">OPD / Consultations</li>
@endsection

@push('styles')
<style>
  .opd-container { max-width: 1600px; margin: 0 auto; }
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
  
  /* Stats */
  .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; }
  .stat-card { background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; }
  .stat-value { font-size: 32px; font-weight: 700; color: #111827; margin-bottom: 4px; }
  .stat-label { font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; }
  .stat-card.today { border-left: 4px solid #065f46; }
  .stat-card.today .stat-value { color: #065f46; }
  .stat-card.pending { border-left: 4px solid #d97706; }
  .stat-card.pending .stat-value { color: #d97706; }
  
  /* Filters */
  .filters-card { background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; margin-bottom: 24px; }
  .filter-group { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; align-items: end; }
  .form-label { font-size: 13px; font-weight: 500; color: #374151; margin-bottom: 6px; }
  .form-control, .form-select { border: 1px solid #e5e7eb; border-radius: 8px; padding: 10px 14px; font-size: 14px; transition: all 0.2s; }
  .form-control:focus, .form-select:focus { border-color: #111827; box-shadow: 0 0 0 3px rgba(17, 24, 39, 0.1); }
  .btn-primary-custom { background: #111827; color: white; border: 1px solid #111827; padding: 10px 20px; border-radius: 8px; font-weight: 500; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s; text-decoration: none; }
  .btn-primary-custom:hover { background: #374151; border-color: #374151; color: white; transform: translateY(-1px); }
  .btn-outline-custom { background: white; color: #111827; border: 1px solid #e5e7eb; }
  .btn-outline-custom:hover { background: #f9fafb; border-color: #d1d5db; color: #111827; }
  
  /* Table */
  .table-card { background: white; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; }
  .table-header { padding: 20px 24px; border-bottom: 2px solid #e5e7eb; }
  .table-title { font-size: 16px; font-weight: 600; color: #111827; margin: 0; }
  .consultations-table { width: 100%; }
  .consultations-table thead th { background: #f9fafb; color: #6b7280; font-size: 12px; font-weight: 600; text-transform: uppercase; padding: 14px 20px; border-bottom: 2px solid #e5e7eb; text-align: left; }
  .consultations-table tbody td { padding: 16px 20px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #111827; }
  .consultations-table tbody tr:hover { background: #f9fafb; }
  
  .status-badge { padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; display: inline-block; }
  .status-in-progress { background: #fef3c7; color: #92400e; }
  .status-completed { background: #d1fae5; color: #065f46; }
  
  .empty-state { text-align: center; padding: 64px 24px; color: #9ca3af; }
  .empty-icon { font-size: 64px; margin-bottom: 16px; opacity: 0.5; }
  .empty-text { font-size: 16px; font-weight: 500; margin-bottom: 8px; }
  .empty-subtext { font-size: 14px; color: #9ca3af; }
</style>
@endpush

@section('content')
<div class="opd-container">
  <!-- Page Header -->
  <div class="page-header">
    <div class="d-flex justify-content-between align-items-start">
      <div>
        <h1 class="page-title">OPD / Outpatient Department</h1>
        <p class="page-subtitle">Manage consultations, appointments, and outpatient services</p>
      </div>
      <a href="{{ route('consultations.create') }}" class="btn-primary-custom">
        <i class="bi bi-plus-circle"></i> New Consultation
      </a>
    </div>
  </div>

  <!-- Quick Actions -->
  <div class="quick-actions">
    <a href="{{ route('consultations.create') }}" class="action-card">
      <div class="action-icon"><i class="bi bi-plus-circle"></i></div>
      <div class="action-title">New Consultation</div>
      <div class="action-subtitle">Start a new patient consultation</div>
    </a>
    <a href="{{ route('appointments.index') }}" class="action-card">
      <div class="action-icon"><i class="bi bi-calendar-check"></i></div>
      <div class="action-title">Appointments</div>
      <div class="action-subtitle">View & manage appointments</div>
    </a>
    <a href="{{ route('patients.index') }}" class="action-card">
      <div class="action-icon"><i class="bi bi-people"></i></div>
      <div class="action-title">Patients</div>
      <div class="action-subtitle">Browse patient records</div>
    </a>
    <a href="{{ route('nursing.dashboard') }}" class="action-card">
      <div class="action-icon"><i class="bi bi-heart-pulse"></i></div>
      <div class="action-title">Nursing Station</div>
      <div class="action-subtitle">Patient vitals & care</div>
    </a>
  </div>

  <!-- Statistics -->
  <div class="stats-grid">
    <div class="stat-card today">
      <div class="stat-value">{{ $consultations->where('created_at', '>=', today())->count() }}</div>
      <div class="stat-label">Today's Consultations</div>
    </div>
    <div class="stat-card pending">
      <div class="stat-value">{{ $consultations->where('status', 'in-progress')->count() }}</div>
      <div class="stat-label">In Progress</div>
    </div>
    <div class="stat-card">
      <div class="stat-value">{{ $consultations->where('status', 'completed')->count() }}</div>
      <div class="stat-label">Completed (This Page)</div>
    </div>
    <div class="stat-card">
      <div class="stat-value">{{ $consultations->total() }}</div>
      <div class="stat-label">Total Consultations</div>
    </div>
  </div>

  <!-- Filters -->
  <div class="filters-card">
    <form action="{{ route('consultations.index') }}" method="GET">
      <div class="filter-group">
        <div>
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            <option value="">All Consultations</option>
            <option value="in-progress" {{ request('status') === 'in-progress' ? 'selected' : '' }}>In Progress</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
          </select>
        </div>
        <div>
          <label class="form-label">Doctor</label>
          <select name="doctor_id" class="form-select">
            <option value="">All Doctors</option>
            @foreach(\App\Models\User::role('doctor')->orderBy('name')->get() as $doctor)
              <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                Dr. {{ $doctor->name }}
              </option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="form-label">Date</label>
          <input type="date" name="date" class="form-control" value="{{ request('date') }}">
        </div>
        <div>
          <label class="form-label">Search Patient</label>
          <input type="text" name="search" class="form-control" placeholder="Name or ID..." value="{{ request('search') }}">
        </div>
        <div style="display: flex; gap: 8px;">
          <button type="submit" class="btn-primary-custom">
            <i class="bi bi-search"></i> Filter
          </button>
          <a href="{{ route('consultations.index') }}" class="btn-primary-custom btn-outline-custom">
            <i class="bi bi-x-circle"></i> Clear
          </a>
        </div>
      </div>
    </form>
  </div>

  <!-- Consultations Table -->
  <div class="table-card">
    <div class="table-header">
      <h5 class="table-title">
        <i class="bi bi-file-medical me-2"></i>
        Consultations List ({{ $consultations->total() }})
      </h5>
    </div>

    @if($consultations->count() > 0)
    <div class="table-responsive">
      <table class="consultations-table">
        <thead>
          <tr>
            <th>Consultation #</th>
            <th>Date</th>
            <th>Patient</th>
            <th>Doctor</th>
            <th>Chief Complaint</th>
            <th>Diagnosis</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($consultations as $consultation)
          <tr>
            <td><strong>{{ $consultation->consultation_number }}</strong></td>
            <td>
              {{ $consultation->created_at->format('M d, Y') }}
              <br>
              <small style="color: #6b7280;">{{ $consultation->created_at->format('h:i A') }}</small>
            </td>
            <td>
              <a href="{{ route('patients.show', $consultation->patient) }}" style="font-weight: 600; color: #111827; text-decoration: none;">
                {{ $consultation->patient->full_name }}
              </a>
              <br>
              <small style="color: #6b7280;">{{ $consultation->patient->patient_id }}</small>
            </td>
            <td>Dr. {{ $consultation->doctor->name }}</td>
            <td>{{ Str::limit($consultation->chief_complaint ?? 'N/A', 40) }}</td>
            <td>{{ Str::limit($consultation->final_diagnosis ?? $consultation->provisional_diagnosis ?? 'N/A', 40) }}</td>
            <td>
              <span class="status-badge status-{{ $consultation->status }}">
                {{ ucfirst(str_replace('-', ' ', $consultation->status)) }}
              </span>
            </td>
            <td>
              <div class="d-flex gap-2">
                <a href="{{ route('consultations.show', $consultation) }}" class="btn btn-sm btn-outline-secondary" title="View Details">
                  <i class="bi bi-eye"></i>
                </a>
                @if($consultation->status === 'in-progress')
                <a href="{{ route('consultations.edit', $consultation) }}" class="btn btn-sm btn-outline-secondary" title="Continue Editing">
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
      {{ $consultations->links() }}
    </div>
    @else
    <div class="empty-state">
      <div class="empty-icon"><i class="bi bi-inbox"></i></div>
      <div class="empty-text">No consultations found</div>
      <div class="empty-subtext">
        @if(request()->hasAny(['status', 'doctor_id', 'date', 'search']))
          Try adjusting your filters
        @else
          Start a new consultation to begin
        @endif
      </div>
    </div>
    @endif
  </div>
</div>
@endsection
