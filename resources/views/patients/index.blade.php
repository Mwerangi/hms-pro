@extends('layouts.app')

@section('title', 'Patient Management')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('patients.index') }}">Patients</a></li>
<li class="breadcrumb-item active" aria-current="page">All Patients</li>
@endsection

@push('styles')
<style>
  /* Modern Minimalistic Design */
  .page-header {
    margin-bottom: 32px;
  }

  .page-title {
    font-size: 28px;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
  }

  /* Stats Grid */
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
    transition: all 0.2s ease;
  }

  .stat-card:hover {
    border-color: #667eea;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
  }

  .stat-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
  }

  .stat-icon {
    width: 44px;
    height: 44px;
    background: #f3f4f6;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: #667eea;
  }

  .stat-value {
    font-size: 28px;
    font-weight: 700;
    color: #111827;
    line-height: 1;
    margin-bottom: 4px;
  }

  .stat-label {
    font-size: 13px;
    color: #6b7280;
    font-weight: 500;
  }

  /* Filter Section */
  .filter-section {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
  }

  .filter-row {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr auto auto;
    gap: 16px;
    align-items: end;
  }

  /* Table */
  .patients-table-wrapper {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
  }

  .table {
    margin-bottom: 0;
    font-size: 14px;
  }

  .table thead th {
    background: #f9fafb;
    color: #6b7280;
    font-weight: 600;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 14px 16px;
    border-bottom: 1px solid #e5e7eb;
  }

  .table td {
    padding: 16px;
    vertical-align: middle;
    color: #374151;
    border-bottom: 1px solid #f3f4f6;
  }

  .table tbody tr:hover {
    background: #f9fafb;
  }

  .patient-info {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .patient-avatar {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #667eea;
    font-weight: 600;
    font-size: 14px;
  }

  .patient-details h6 {
    font-size: 14px;
    font-weight: 600;
    color: #111827;
    margin: 0 0 2px 0;
  }

  .patient-details p {
    font-size: 12px;
    color: #6b7280;
    margin: 0;
  }

  .badge {
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
  }

  .bg-success { background: #d1fae5; color: #065f46; }
  .bg-secondary { background: #e5e7eb; color: #374151; }
  .bg-danger { background: #fee2e2; color: #991b1b; }

  .action-buttons {
    display: flex;
    gap: 6px;
  }

  .btn-icon {
    width: 36px;
    height: 36px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.2s ease;
    border: 1px solid #e5e7eb;
  }

  .btn-icon:hover {
    border-color: #667eea;
    background: #f9fafb;
  }

  .btn-outline-primary { color: #667eea; border-color: #e5e7eb; }
  .btn-outline-primary:hover { background: #667eea; color: white; border-color: #667eea; }
  
  .btn-outline-warning { color: #f59e0b; border-color: #e5e7eb; }
  .btn-outline-warning:hover { background: #f59e0b; color: white; border-color: #f59e0b; }
  
  .btn-outline-danger { color: #ef4444; border-color: #e5e7eb; }
  .btn-outline-danger:hover { background: #ef4444; color: white; border-color: #ef4444; }

  .btn-success { background: #10b981; border-color: #10b981; }
  .btn-success:hover { background: #059669; border-color: #059669; }

  /* Empty State */
  .empty-state {
    text-align: center;
    padding: 48px 20px;
  }

  .empty-state i {
    font-size: 48px;
    color: #d1d5db;
    margin-bottom: 12px;
  }

  .empty-state p {
    color: #9ca3af;
  }

  /* Toast */
  .toast-container {
    position: fixed;
    top: 80px;
    right: 20px;
    z-index: 9999;
  }

  .toast-notification {
    min-width: 320px;
    background: white;
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 12px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    display: flex;
    align-items: center;
    gap: 12px;
    animation: slideInRight 0.3s ease;
  }

  .toast-notification.success {
    border-left: 4px solid #10b981;
  }

  .toast-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
  }

  .toast-notification.success .toast-icon {
    background: #d1fae5;
    color: #10b981;
  }

  @keyframes slideInRight {
    from { transform: translateX(400px); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
  }
</style>
@endpush

@section('content')
<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center">
  <h1 class="page-title">Patient Management</h1>
  <a href="{{ route('patients.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-circle me-2"></i>Register New Patient
  </a>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-header">
      <div>
        <div class="stat-value">{{ $stats['total'] }}</div>
        <div class="stat-label">Total Patients</div>
      </div>
      <div class="stat-icon">
        <i class="bi bi-people-fill"></i>
      </div>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-header">
      <div>
        <div class="stat-value">{{ $stats['active'] }}</div>
        <div class="stat-label">Active Patients</div>
      </div>
      <div class="stat-icon">
        <i class="bi bi-heart-pulse-fill"></i>
      </div>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-header">
      <div>
        <div class="stat-value">{{ $stats['male'] }}</div>
        <div class="stat-label">Male</div>
      </div>
      <div class="stat-icon">
        <i class="bi bi-gender-male"></i>
      </div>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-header">
      <div>
        <div class="stat-value">{{ $stats['female'] }}</div>
        <div class="stat-label">Female</div>
      </div>
      <div class="stat-icon">
        <i class="bi bi-gender-female"></i>
      </div>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-header">
      <div>
        <div class="stat-value">{{ $stats['new_today'] }}</div>
        <div class="stat-label">New Today</div>
      </div>
      <div class="stat-icon">
        <i class="bi bi-person-plus-fill"></i>
      </div>
    </div>
  </div>
</div>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>

<!-- Filters -->
<div class="filter-section">
  <form action="{{ route('patients.index') }}" method="GET">
    <div class="filter-row">
      <div>
        <label class="form-label">Search</label>
        <input type="text" name="search" class="form-control" 
               placeholder="Search by name, ID, phone..." 
               value="{{ request('search') }}">
      </div>
      
      <div>
        <label class="form-label">Gender</label>
        <select name="gender" class="form-select">
          <option value="">All</option>
          <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
          <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
          <option value="other" {{ request('gender') == 'other' ? 'selected' : '' }}>Other</option>
        </select>
      </div>

      <div>
        <label class="form-label">Blood Group</label>
        <select name="blood_group" class="form-select">
          <option value="">All</option>
          <option value="A+" {{ request('blood_group') == 'A+' ? 'selected' : '' }}>A+</option>
          <option value="A-" {{ request('blood_group') == 'A-' ? 'selected' : '' }}>A-</option>
          <option value="B+" {{ request('blood_group') == 'B+' ? 'selected' : '' }}>B+</option>
          <option value="B-" {{ request('blood_group') == 'B-' ? 'selected' : '' }}>B-</option>
          <option value="O+" {{ request('blood_group') == 'O+' ? 'selected' : '' }}>O+</option>
          <option value="O-" {{ request('blood_group') == 'O-' ? 'selected' : '' }}>O-</option>
          <option value="AB+" {{ request('blood_group') == 'AB+' ? 'selected' : '' }}>AB+</option>
          <option value="AB-" {{ request('blood_group') == 'AB-' ? 'selected' : '' }}>AB-</option>
        </select>
      </div>

      <div>
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
          <option value="">All</option>
          <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
          <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary">
        <i class="bi bi-search"></i> Filter
      </button>

      @if(request()->hasAny(['search', 'gender', 'blood_group', 'status']))
      <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-x-circle"></i> Clear
      </a>
      @endif
    </div>
  </form>
</div>

<!-- Patients Table -->
<div class="patients-table-wrapper">
  @if($patients->count() > 0)
  <table class="table table-hover">
    <thead>
      <tr>
        <th>Patient</th>
        <th>Patient ID</th>
        <th>Age/Gender</th>
        <th>Blood Group</th>
        <th>Contact</th>
        <th>Status</th>
        <th>Registered</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($patients as $patient)
      <tr>
        <td>
          <div class="patient-info">
            <div class="patient-avatar">
              {{ strtoupper(substr($patient->first_name, 0, 1)) }}{{ strtoupper(substr($patient->last_name, 0, 1)) }}
            </div>
            <div class="patient-details">
              <h6>{{ $patient->full_name }}</h6>
              <p>{{ $patient->email ?? 'No email' }}</p>
            </div>
          </div>
        </td>
        <td>
          <strong class="text-primary">{{ $patient->patient_id }}</strong>
        </td>
        <td>
          {{ $patient->age }} yrs / 
          <span class="text-capitalize">{{ $patient->gender }}</span>
        </td>
        <td>
          @if($patient->blood_group)
            <span class="badge bg-danger">{{ $patient->blood_group }}</span>
          @else
            <span class="text-muted">-</span>
          @endif
        </td>
        <td>{{ $patient->phone }}</td>
        <td>
          @if($patient->is_active)
          <span class="badge bg-success">Active</span>
          @else
          <span class="badge bg-secondary">Inactive</span>
          @endif
        </td>
        <td>
          <small class="text-muted">{{ $patient->created_at->format('M d, Y') }}</small>
        </td>
        <td>
          <div class="action-buttons">
            <button type="button"
                    class="btn btn-sm btn-success btn-icon"
                    title="Quick Register (Walk-in)"
                    data-bs-toggle="modal"
                    data-bs-target="#walkInModal{{ $patient->id }}">
              <i class="bi bi-person-plus-fill"></i>
            </button>
            
            <a href="{{ route('patients.show', $patient) }}" 
               class="btn btn-sm btn-outline-primary btn-icon" 
               title="View">
              <i class="bi bi-eye"></i>
            </a>
            <a href="{{ route('patients.edit', $patient) }}" 
               class="btn btn-sm btn-outline-warning btn-icon" 
               title="Edit">
              <i class="bi bi-pencil"></i>
            </a>
            
            <button type="button" 
                    class="btn btn-sm btn-outline-{{ $patient->is_active ? 'secondary' : 'success' }} btn-icon" 
                    title="{{ $patient->is_active ? 'Deactivate' : 'Activate' }}"
                    onclick="showToggleModal({{ $patient->id }}, '{{ $patient->full_name }}', '{{ $patient->is_active ? 'deactivate' : 'activate' }}')">
              <i class="bi bi-{{ $patient->is_active ? 'toggle-off' : 'toggle-on' }}"></i>
            </button>
            <form id="toggleForm{{ $patient->id }}" action="{{ route('patients.toggle-status', $patient) }}" method="POST" style="display: none;">
              @csrf
            </form>

            <button type="button" class="btn btn-sm btn-outline-danger btn-icon" title="Archive"
                    onclick="showArchiveModal({{ $patient->id }}, '{{ $patient->full_name }}')">
              <i class="bi bi-archive"></i>
            </button>
            <form id="archiveForm{{ $patient->id }}" action="{{ route('patients.destroy', $patient) }}" method="POST" style="display: none;">
              @csrf
              @method('DELETE')
            </form>
          </div>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div class="p-3">
    {{ $patients->links() }}
  </div>
  @else
  <div class="empty-state">
    <i class="bi bi-people"></i>
    <p>No patients found</p>
    @if(request()->hasAny(['search', 'gender', 'blood_group', 'status']))
    <a href="{{ route('patients.index') }}" class="btn btn-sm btn-outline-primary" style="margin-top: 12px;">Clear Filters</a>
    @endif
  </div>
  @endif
</div>

<!-- Walk-in Registration Modals -->
@foreach($patients as $patient)
<div class="modal fade" id="walkInModal{{ $patient->id }}" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('appointments.create-walk-in') }}" method="POST">
        @csrf
        <input type="hidden" name="patient_id" value="{{ $patient->id }}">
        
        <div class="modal-header">
          <h5 class="modal-title">
            <i class="bi bi-person-plus-fill"></i> Quick Registration - {{ $patient->full_name }}
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        
        <div class="modal-body">
          <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Patient: <strong>{{ $patient->full_name }}</strong> ({{ $patient->patient_id }})
          </div>
          
          <div class="mb-3">
            <label class="form-label">Priority <span class="text-danger">*</span></label>
            <select name="is_emergency" class="form-select" required>
              <option value="0">Normal Walk-in (FIFO)</option>
              <option value="1" style="color: red; font-weight: bold;">🚨 EMERGENCY (Priority)</option>
            </select>
            <small class="text-muted">Emergency cases get immediate priority</small>
          </div>
          
          <div class="mb-3">
            <label class="form-label">Chief Complaint <span class="text-danger">*</span></label>
            <textarea name="chief_complaint" class="form-control" rows="2" required
                      placeholder="e.g., Fever and headache for 3 days"></textarea>
            <small class="text-muted">Brief description of why patient is here</small>
          </div>
          
          <div class="mb-3">
            <label class="form-label">Doctor Preference</label>
            <select name="doctor_id" class="form-select">
              <option value="">Auto-assign (Least busy doctor)</option>
              @foreach(\App\Models\User::role('Doctor')->get() as $doctor)
                <option value="{{ $doctor->id }}">
                  Dr. {{ $doctor->name }}
                  @if($doctor->specialization)
                    - {{ $doctor->specialization }}
                  @endif
                </option>
              @endforeach
            </select>
            <small class="text-muted">Leave blank for automatic assignment to least busy doctor</small>
          </div>
          
          <div class="alert alert-success">
            <i class="bi bi-check-circle"></i> Patient will be automatically checked-in and sent to <strong>Nursing Station</strong> for vitals recording.
          </div>
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">
            <i class="bi bi-check-circle"></i> Register & Send to Nursing
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach

<!-- Toggle Status Modal -->
<div class="modal fade" id="toggleStatusModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="toggleStatusTitle">Confirm Action</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p id="toggleStatusMessage"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="confirmToggle">Confirm</button>
      </div>
    </div>
  </div>
</div>

<!-- Archive Modal -->
<div class="modal fade" id="archiveModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Archive Patient</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p id="archiveMessage"></p>
        <div class="alert alert-warning">
          <i class="bi bi-exclamation-triangle me-2"></i>
          <strong>Note:</strong> This action can be reversed later.
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmArchive">Archive Patient</button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
let currentToggleFormId = null;
let currentArchiveFormId = null;

function showToggleModal(patientId, patientName, action) {
  currentToggleFormId = `toggleForm${patientId}`;
  const title = action === 'activate' ? 'Activate Patient' : 'Deactivate Patient';
  const message = `Are you sure you want to ${action} <strong>${patientName}</strong>?`;
  
  document.getElementById('toggleStatusTitle').textContent = title;
  document.getElementById('toggleStatusMessage').innerHTML = message;
  
  const modal = new bootstrap.Modal(document.getElementById('toggleStatusModal'));
  modal.show();
}

function showArchiveModal(patientId, patientName) {
  currentArchiveFormId = `archiveForm${patientId}`;
  document.getElementById('archiveMessage').innerHTML = `Are you sure you want to archive <strong>${patientName}</strong>?`;
  
  const modal = new bootstrap.Modal(document.getElementById('archiveModal'));
  modal.show();
}

document.getElementById('confirmToggle').addEventListener('click', function() {
  if (currentToggleFormId) {
    document.getElementById(currentToggleFormId).submit();
  }
});

document.getElementById('confirmArchive').addEventListener('click', function() {
  if (currentArchiveFormId) {
    document.getElementById(currentArchiveFormId).submit();
  }
});

// Toast Notification System
function showToast(message, type = 'success') {
  const container = document.getElementById('toastContainer');
  const toast = document.createElement('div');
  toast.className = `toast-notification ${type}`;
  
  const icon = type === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill';
  
  toast.innerHTML = `
    <div class="toast-icon">
      <i class="bi bi-${icon}"></i>
    </div>
    <div class="flex-grow-1">
      <strong>${type === 'success' ? 'Success!' : 'Error!'}</strong>
      <div class="text-muted" style="font-size: 13px;">${message}</div>
    </div>
    <button class="btn-close btn-sm" onclick="this.parentElement.remove()"></button>
  `;
  
  container.appendChild(toast);
  
  setTimeout(() => {
    toast.style.animation = 'slideOutRight 0.3s ease';
    setTimeout(() => toast.remove(), 300);
  }, 5000);
}

@if(session('success'))
  showToast("{{ session('success') }}", 'success');
@endif

@if(session('error'))
  showToast("{{ session('error') }}", 'error');
@endif
</script>
@endpush
@endsection
