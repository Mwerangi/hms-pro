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
    <div class="info-value"><strong>TSh {{ number_format($wardCharge, 2) }}</strong></div>
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
    <div class="bed-card {{ $bed->status }}" 
         @if($bed->status === 'occupied' && $bed->currentAdmission) 
         style="cursor: pointer;"
         data-bs-toggle="modal" 
         data-bs-target="#bedDetailsModal{{ $bed->id }}"
         @endif>
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
      <div style="margin-top: 8px; font-size: 11px; color: #6b7280; border-top: 1px solid rgba(0,0,0,0.1); padding-top: 8px;">
        <div style="font-weight: 600; color: #111827; margin-bottom: 4px;">
          <i class="bi bi-person-fill"></i> {{ $bed->currentAdmission->patient->full_name }}
        </div>
        <div style="font-size: 10px;">
          ID: {{ $bed->currentAdmission->patient->patient_id }}
        </div>
        @if($bed->currentAdmission->doctor)
        <div style="font-size: 10px; margin-top: 2px;">
          <i class="bi bi-person-badge"></i> Dr. {{ $bed->currentAdmission->doctor->name }}
        </div>
        @endif
        <div style="font-size: 10px; margin-top: 2px; color: #f59e0b;">
          <i class="bi bi-calendar"></i> {{ $bed->currentAdmission->admission_date->diffForHumans() }}
        </div>
        <div style="margin-top: 6px; font-size: 10px; color: #667eea; font-weight: 500;">
          <i class="bi bi-info-circle"></i> Click for details
        </div>
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

<!-- Bed Details Modals -->
@foreach($ward->beds as $bed)
  @if($bed->status === 'occupied' && $bed->currentAdmission)
  <div class="modal fade" id="bedDetailsModal{{ $bed->id }}" tabindex="-1" aria-labelledby="bedDetailsModalLabel{{ $bed->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
          <h5 class="modal-title" id="bedDetailsModalLabel{{ $bed->id }}">
            <i class="bi bi-bed-fill me-2"></i>Bed {{ $bed->bed_label ?? $bed->bed_number }} - Patient Details
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" style="padding: 0;">
          @php
            $admission = $bed->currentAdmission;
            $patient = $admission->patient;
          @endphp
          
          <!-- Patient Header -->
          <div style="background: #ffffff; padding: 24px; border-bottom: 1px solid #e5e7eb;">
            <div style="display: flex; gap: 24px; align-items: start;">
              <!-- Patient Photo -->
              <div style="flex-shrink: 0;">
                @if($patient->photo)
                  <img src="{{ asset('storage/' . $patient->photo) }}" 
                       alt="{{ $patient->full_name }}" 
                       style="width: 100px; height: 100px; border-radius: 12px; object-fit: cover; border: 2px solid #e5e7eb;">
                @else
                  <div style="width: 100px; height: 100px; border-radius: 12px; background: #111827; display: flex; align-items: center; justify-content: center; color: white; font-size: 40px; font-weight: 600;">
                    {{ substr($patient->first_name, 0, 1) }}{{ substr($patient->last_name, 0, 1) }}
                  </div>
                @endif
              </div>
              
              <!-- Patient Info -->
              <div style="flex: 1;">
                <h3 style="font-size: 24px; font-weight: 600; color: #111827; margin: 0 0 8px 0;">
                  {{ $patient->full_name }}
                  @if($patient->gender === 'male')
                    <i class="bi bi-gender-male" style="color: #6b7280;"></i>
                  @elseif($patient->gender === 'female')
                    <i class="bi bi-gender-female" style="color: #6b7280;"></i>
                  @endif
                </h3>
                <div style="display: flex; gap: 24px; flex-wrap: wrap; margin-bottom: 12px;">
                  <div>
                    <span style="color: #6b7280; font-size: 12px;">Patient ID</span>
                    <div style="font-weight: 600; color: #111827;">{{ $patient->patient_id }}</div>
                  </div>
                  <div>
                    <span style="color: #6b7280; font-size: 12px;">Age</span>
                    <div style="font-weight: 600; color: #111827;">{{ $patient->age }} years</div>
                  </div>
                  <div>
                    <span style="color: #6b7280; font-size: 12px;">Blood Group</span>
                    <div style="font-weight: 600; color: #111827;">{{ $patient->blood_group ?? 'N/A' }}</div>
                  </div>
                  <div>
                    <span style="color: #6b7280; font-size: 12px;">Contact</span>
                    <div style="font-weight: 600; color: #111827;">{{ $patient->phone }}</div>
                  </div>
                </div>
                
                <!-- Medical Alerts -->
                @if($patient->allergies || $patient->chronic_conditions)
                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                  @if($patient->allergies)
                  <div style="padding: 6px 12px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 6px; font-size: 13px; color: #991b1b;">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    <strong>Allergies:</strong> {{ $patient->allergies }}
                  </div>
                  @endif
                  @if($patient->chronic_conditions)
                  <div style="padding: 6px 12px; background: #fffbeb; border: 1px solid #fde68a; border-radius: 6px; font-size: 13px; color: #92400e;">
                    <i class="bi bi-heart-pulse-fill me-1"></i>
                    <strong>Conditions:</strong> {{ $patient->chronic_conditions }}
                  </div>
                  @endif
                </div>
                @endif
              </div>
              
              <!-- Admission Badge -->
              <div style="text-align: center;">
                <div style="background: #111827; color: white; padding: 8px 16px; border-radius: 8px; font-weight: 600; font-size: 14px; margin-bottom: 8px;">
                  <i class="bi bi-hospital-fill me-1"></i>ADMITTED
                </div>
                <div style="font-size: 12px; color: #6b7280; margin-bottom: 8px;">
                  #{{ $admission->admission_number }}
                </div>
                @php
                  $daysAdmitted = (int) \Carbon\Carbon::parse($admission->admission_date)->diffInDays(now());
                @endphp
                <div style="background: #f9fafb; border: 1px solid #e5e7eb; padding: 8px 12px; border-radius: 6px; margin-top: 8px;">
                  <div style="font-size: 11px; color: #6b7280; text-transform: uppercase; margin-bottom: 2px;">Days Admitted</div>
                  <div style="font-size: 20px; font-weight: 700; color: #111827;">{{ $daysAdmitted }}</div>
                  <div style="font-size: 10px; color: #6b7280;">
                    {{ $daysAdmitted === 1 ? 'day' : 'days' }}
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Details Grid: 2 Columns -->
          <div style="display: grid; grid-template-columns: 1fr 1fr; min-height: 400px;">
            <!-- Left Column -->
            <div style="border-right: 1px solid #e5e7eb;">
              <!-- Admission Information -->
              <div style="padding: 20px; border-bottom: 1px solid #e5e7eb;">
                <h6 style="font-size: 13px; font-weight: 600; color: #111827; margin-bottom: 16px; text-transform: uppercase; letter-spacing: 0.5px;">
                  <i class="bi bi-calendar-check me-2"></i>Admission Information
                </h6>
                @php
                  $actualDaysAdmitted = (int) \Carbon\Carbon::parse($admission->admission_date)->diffInDays(now());
                  $remainingDays = $admission->estimated_stay_days ? (int) max(0, $admission->estimated_stay_days - $actualDaysAdmitted) : null;
                @endphp
                <div style="display: grid; gap: 10px;">
                  <div style="display: flex; justify-content: space-between; padding: 8px 0;">
                    <span style="color: #6b7280; font-size: 13px;">Admission Date</span>
                    <strong style="color: #111827; font-size: 13px;">{{ $admission->admission_date->format('M d, Y h:i A') }}</strong>
                  </div>
                  <div style="display: flex; justify-content: space-between; padding: 8px 0; border-top: 1px solid #f3f4f6;">
                    <span style="color: #6b7280; font-size: 13px;">Days Admitted</span>
                    <strong style="color: #111827; font-size: 13px; font-weight: 700;">{{ $actualDaysAdmitted }} {{ $actualDaysAdmitted === 1 ? 'day' : 'days' }}</strong>
                  </div>
                  @if($admission->estimated_stay_days)
                  <div style="display: flex; justify-content: space-between; padding: 8px 0; border-top: 1px solid #f3f4f6;">
                    <span style="color: #6b7280; font-size: 13px;">Estimated Stay</span>
                    <strong style="color: #111827; font-size: 13px;">{{ $admission->estimated_stay_days }} days</strong>
                  </div>
                  @if($remainingDays !== null)
                  <div style="display: flex; justify-content: space-between; padding: 8px 0; border-top: 1px solid #f3f4f6;">
                    <span style="color: #6b7280; font-size: 13px;">
                      @if($actualDaysAdmitted > $admission->estimated_stay_days)
                        Overdue By
                      @else
                        Remaining Days
                      @endif
                    </span>
                    <strong style="color: {{ $actualDaysAdmitted > $admission->estimated_stay_days ? '#991b1b' : '#111827' }}; font-size: 13px;">
                      @if($actualDaysAdmitted > $admission->estimated_stay_days)
                        {{ $actualDaysAdmitted - $admission->estimated_stay_days }} {{ ($actualDaysAdmitted - $admission->estimated_stay_days) === 1 ? 'day' : 'days' }}
                      @else
                        {{ $remainingDays }} {{ $remainingDays === 1 ? 'day' : 'days' }}
                      @endif
                    </strong>
                  </div>
                  @endif
                  @endif
                  <div style="display: flex; justify-content: space-between; padding: 8px 0; border-top: 1px solid #f3f4f6;">
                    <span style="color: #6b7280; font-size: 13px;">Admission Type</span>
                    <strong style="color: #111827; font-size: 13px;">{{ ucfirst($admission->admission_type) }}</strong>
                  </div>
                  <div style="display: flex; justify-content: space-between; padding: 8px 0; border-top: 1px solid #f3f4f6;">
                    <span style="color: #6b7280; font-size: 13px;">Category</span>
                    <strong style="color: #111827; font-size: 13px;">{{ ucfirst($admission->admission_category) }}</strong>
                  </div>
                </div>
              </div>
              
              <!-- Medical Information -->
              <div style="padding: 20px; border-bottom: 1px solid #e5e7eb;">
                <h6 style="font-size: 13px; font-weight: 600; color: #111827; margin-bottom: 16px; text-transform: uppercase; letter-spacing: 0.5px;">
                  <i class="bi bi-heart-pulse me-2"></i>Medical Information
                </h6>
                <div style="display: grid; gap: 12px;">
                  @if($admission->provisional_diagnosis)
                  <div style="padding: 12px; background: #f9fafb; border-left: 3px solid #111827; border-radius: 6px;">
                    <div style="color: #6b7280; font-size: 11px; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Diagnosis</div>
                    <div style="color: #111827; font-size: 13px;">{{ $admission->provisional_diagnosis }}</div>
                  </div>
                  @endif
                  
                  @if($admission->reason_for_admission)
                  <div style="padding: 12px; background: #f9fafb; border-left: 3px solid #6b7280; border-radius: 6px;">
                    <div style="color: #6b7280; font-size: 11px; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Reason for Admission</div>
                    <div style="color: #111827; font-size: 13px;">{{ $admission->reason_for_admission }}</div>
                  </div>
                  @endif
                  
                  @if($admission->complaints)
                  <div style="padding: 12px; background: #f9fafb; border-left: 3px solid #6b7280; border-radius: 6px;">
                    <div style="color: #6b7280; font-size: 11px; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Complaints</div>
                    <div style="color: #111827; font-size: 13px;">{{ $admission->complaints }}</div>
                  </div>
                  @endif
                  
                  @if($admission->medical_history)
                  <div style="padding: 12px; background: #f9fafb; border-radius: 6px; border: 1px solid #e5e7eb;">
                    <div style="color: #6b7280; font-size: 11px; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Medical History</div>
                    <div style="color: #111827; font-size: 13px;">{{ $admission->medical_history }}</div>
                  </div>
                  @endif
                </div>
              </div>
              
              <!-- Payment Information -->
              <div style="padding: 20px;">
                <h6 style="font-size: 13px; font-weight: 600; color: #111827; margin-bottom: 16px; text-transform: uppercase; letter-spacing: 0.5px;">
                  <i class="bi bi-credit-card me-2"></i>Payment Information
                </h6>
                <div style="display: grid; gap: 10px;">
                  <div style="display: flex; justify-content: space-between; padding: 8px 0;">
                    <span style="color: #6b7280; font-size: 13px;">Payment Mode</span>
                    <strong style="color: #111827; font-size: 13px;">{{ ucfirst($admission->payment_mode ?? 'N/A') }}</strong>
                  </div>
                  @if($admission->advance_payment)
                  <div style="display: flex; justify-content: space-between; padding: 8px 0; border-top: 1px solid #f3f4f6;">
                    <span style="color: #6b7280; font-size: 13px;">Advance Payment</span>
                    <strong style="color: #111827; font-size: 13px;">TSh {{ number_format($admission->advance_payment, 2) }}</strong>
                  </div>
                  @endif
                  @if($admission->insurance_company)
                  <div style="padding: 12px; background: #f9fafb; border-radius: 6px; border: 1px solid #e5e7eb; margin-top: 4px;">
                    <div style="color: #6b7280; font-size: 11px; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Insurance</div>
                    <div style="color: #111827; font-size: 13px; margin-bottom: 4px;">{{ $admission->insurance_company }}</div>
                    @if($admission->insurance_policy_number)
                    <div style="color: #6b7280; font-size: 12px;">Policy: {{ $admission->insurance_policy_number }}</div>
                    @endif
                  </div>
                  @endif
                </div>
              </div>
            </div>
            
            <!-- Right Column -->
            <div>
              <!-- Doctor & Staff Information -->
              <div style="padding: 20px; border-bottom: 1px solid #e5e7eb;">
                <h6 style="font-size: 13px; font-weight: 600; color: #111827; margin-bottom: 16px; text-transform: uppercase; letter-spacing: 0.5px;">
                  <i class="bi bi-people me-2"></i>Medical Team
                </h6>
                <div style="display: grid; gap: 12px;">
                  @if($admission->doctor)
                  <div style="padding: 12px; background: #f9fafb; border-radius: 8px; border: 1px solid #e5e7eb;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                      <div style="width: 48px; height: 48px; border-radius: 50%; background: #111827; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 14px;">
                        Dr
                      </div>
                      <div style="flex: 1;">
                        <div style="font-size: 11px; color: #6b7280; text-transform: uppercase;">Attending Doctor</div>
                        <div style="font-weight: 600; color: #111827; font-size: 14px;">Dr. {{ $admission->doctor->name }}</div>
                        <div style="font-size: 12px; color: #6b7280;">{{ $admission->doctor->email }}</div>
                      </div>
                    </div>
                  </div>
                  @endif
                  
                  @if($admission->admittedBy)
                  <div style="padding: 12px; background: #f9fafb; border-radius: 8px; border: 1px solid #e5e7eb;">
                    <div style="font-size: 11px; color: #6b7280; text-transform: uppercase; margin-bottom: 4px;">Admitted By</div>
                    <div style="font-weight: 600; color: #111827; font-size: 13px;">{{ $admission->admittedBy->name }}</div>
                  </div>
                  @endif
                </div>
              </div>
              
              <!-- Vital Signs at Admission -->
              <div style="padding: 20px; border-bottom: 1px solid #e5e7eb;">
                <h6 style="font-size: 13px; font-weight: 600; color: #111827; margin-bottom: 16px; text-transform: uppercase; letter-spacing: 0.5px;">
                  <i class="bi bi-activity me-2"></i>Vital Signs at Admission
                </h6>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                  @if($admission->blood_pressure)
                  <div style="text-align: center; padding: 12px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px;">
                    <i class="bi bi-heart-pulse-fill" style="font-size: 20px; color: #6b7280; margin-bottom: 4px;"></i>
                    <div style="font-size: 16px; font-weight: 600; color: #111827;">{{ $admission->blood_pressure }}</div>
                    <div style="font-size: 11px; color: #6b7280;">BP (mmHg)</div>
                  </div>
                  @endif
                  
                  @if($admission->temperature)
                  <div style="text-align: center; padding: 12px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px;">
                    <i class="bi bi-thermometer-half" style="font-size: 20px; color: #6b7280; margin-bottom: 4px;"></i>
                    <div style="font-size: 16px; font-weight: 600; color: #111827;">{{ $admission->temperature }}°F</div>
                    <div style="font-size: 11px; color: #6b7280;">Temperature</div>
                  </div>
                  @endif
                  
                  @if($admission->pulse_rate)
                  <div style="text-align: center; padding: 12px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px;">
                    <i class="bi bi-heart-fill" style="font-size: 20px; color: #6b7280; margin-bottom: 4px;"></i>
                    <div style="font-size: 16px; font-weight: 600; color: #111827;">{{ $admission->pulse_rate }}</div>
                    <div style="font-size: 11px; color: #6b7280;">Pulse (bpm)</div>
                  </div>
                  @endif
                  
                  @if($admission->respiratory_rate)
                  <div style="text-align: center; padding: 12px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px;">
                    <i class="bi bi-lungs-fill" style="font-size: 20px; color: #6b7280; margin-bottom: 4px;"></i>
                    <div style="font-size: 16px; font-weight: 600; color: #111827;">{{ $admission->respiratory_rate }}</div>
                    <div style="font-size: 11px; color: #6b7280;">Resp. Rate</div>
                  </div>
                  @endif
                  
                  @if($admission->oxygen_saturation)
                  <div style="text-align: center; padding: 12px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px;">
                    <i class="bi bi-droplet-fill" style="font-size: 20px; color: #6b7280; margin-bottom: 4px;"></i>
                    <div style="font-size: 16px; font-weight: 600; color: #111827;">{{ $admission->oxygen_saturation }}%</div>
                    <div style="font-size: 11px; color: #6b7280;">SpO₂</div>
                  </div>
                  @endif
                </div>
              </div>
              
              <!-- Emergency Contact -->
              <div style="padding: 20px; border-bottom: 1px solid #e5e7eb;">
                <h6 style="font-size: 13px; font-weight: 600; color: #111827; margin-bottom: 16px; text-transform: uppercase; letter-spacing: 0.5px;">
                  <i class="bi bi-telephone me-2"></i>Emergency Contact
                </h6>
                @php
                  $emergencyName = $admission->emergency_contact_name ?? $patient->emergency_contact_name;
                  $emergencyPhone = $admission->emergency_contact_phone ?? $patient->emergency_contact_phone;
                  $emergencyRelation = $admission->emergency_contact_relation ?? $patient->emergency_contact_relationship;
                @endphp
                @if($emergencyName || $emergencyPhone)
                <div style="padding: 12px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px;">
                  @if($emergencyName)
                  <div style="font-weight: 600; color: #111827; font-size: 14px; margin-bottom: 4px;">
                    {{ $emergencyName }}
                    @if($emergencyRelation)
                      <span style="color: #6b7280; font-size: 12px;">({{ $emergencyRelation }})</span>
                    @endif
                  </div>
                  @endif
                  @if($emergencyPhone)
                  <div style="color: #111827; font-size: 13px;">
                    <i class="bi bi-telephone-fill me-1"></i>{{ $emergencyPhone }}
                  </div>
                  @endif
                </div>
                @else
                <div style="padding: 12px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; text-align: center; color: #9ca3af;">
                  No emergency contact on file
                </div>
                @endif
              </div>
              
              <!-- Bed & Ward Details -->
              <div style="padding: 20px;">
                <h6 style="font-size: 13px; font-weight: 600; color: #111827; margin-bottom: 16px; text-transform: uppercase; letter-spacing: 0.5px;">
                  <i class="bi bi-building me-2"></i>Bed & Ward Details
                </h6>
                <div style="display: grid; gap: 12px;">
                  <div style="padding: 12px; background: #111827; border-radius: 8px; color: white;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                      <span style="font-size: 12px; opacity: 0.8;">Ward</span>
                      <span style="font-weight: 600; font-size: 14px;">{{ $ward->ward_name }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                      <span style="font-size: 12px; opacity: 0.8;">Bed Number</span>
                      <span style="font-weight: 600; font-size: 16px;">{{ $bed->bed_label ?? $bed->bed_number }}</span>
                    </div>
                  </div>
                  
                  @if($bed->bed_type)
                  <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f3f4f6;">
                    <span style="color: #6b7280; font-size: 13px;">Bed Type</span>
                    <strong style="color: #111827; font-size: 13px;">{{ ucfirst($bed->bed_type) }}</strong>
                  </div>
                  @endif
                  
                  @php
                    // Calculate charges from settings
                    $baseCharge = $wardCharge;
                    $additionalCharge = 0;
                    
                    // Add VIP bed charge if applicable
                    if($bed->bed_type === 'vip') {
                      $vipCharge = \App\Models\Setting::where('key', 'ipd_vip_bed_charge')->value('value') ?? 0;
                      $additionalCharge += $vipCharge;
                    }
                    
                    // Add oxygen charge if available
                    if($bed->has_oxygen) {
                      $oxygenCharge = \App\Models\Setting::where('key', 'ipd_oxygen_charge')->value('value') ?? 0;
                      $additionalCharge += $oxygenCharge;
                    }
                    
                    // Add monitoring charge if available
                    if($bed->has_monitor) {
                      $monitorCharge = \App\Models\Setting::where('key', 'ipd_monitoring_charge')->value('value') ?? 0;
                      $additionalCharge += $monitorCharge;
                    }
                    
                    $totalCharge = $baseCharge + $additionalCharge;
                  @endphp
                  
                  <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f3f4f6;">
                    <span style="color: #6b7280; font-size: 13px;">Base Charge/Day</span>
                    <strong style="color: #111827; font-size: 13px;">TSh {{ number_format($baseCharge, 2) }}</strong>
                  </div>
                  
                  @if($additionalCharge > 0)
                  <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f3f4f6;">
                    <span style="color: #6b7280; font-size: 13px;">Additional Services/Day</span>
                    <strong style="color: #111827; font-size: 13px;">TSh {{ number_format($additionalCharge, 2) }}</strong>
                  </div>
                  
                  @if($bed->bed_type === 'vip')
                  <div style="display: flex; justify-content: space-between; padding: 6px 12px; font-size: 11px; background: #f9fafb;">
                    <span style="color: #6b7280;">• VIP Bed</span>
                    <span style="color: #111827;">TSh {{ number_format(\App\Models\Setting::where('key', 'ipd_vip_bed_charge')->value('value') ?? 0, 2) }}</span>
                  </div>
                  @endif
                  
                  @if($bed->has_oxygen)
                  <div style="display: flex; justify-content: space-between; padding: 6px 12px; font-size: 11px; background: #f9fafb;">
                    <span style="color: #6b7280;">• Oxygen Support</span>
                    <span style="color: #111827;">TSh {{ number_format(\App\Models\Setting::where('key', 'ipd_oxygen_charge')->value('value') ?? 0, 2) }}</span>
                  </div>
                  @endif
                  
                  @if($bed->has_monitor)
                  <div style="display: flex; justify-content: space-between; padding: 6px 12px; font-size: 11px; background: #f9fafb;">
                    <span style="color: #6b7280;">• Patient Monitoring</span>
                    <span style="color: #111827;">TSh {{ number_format(\App\Models\Setting::where('key', 'ipd_monitoring_charge')->value('value') ?? 0, 2) }}</span>
                  </div>
                  @endif
                  @endif
                  
                  <div style="display: flex; justify-content: space-between; padding: 10px 0; border-top: 2px solid #e5e7eb;">
                    <span style="color: #111827; font-size: 13px; font-weight: 600;">Total Charge/Day</span>
                    <strong style="color: #111827; font-size: 15px;">TSh {{ number_format($totalCharge, 2) }}</strong>
                  </div>
                  
                  @if($bed->has_oxygen || $bed->has_ventilator || $bed->has_monitor)
                  <div style="padding: 10px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; margin-top: 4px;">
                    <div style="color: #6b7280; font-size: 11px; font-weight: 600; text-transform: uppercase; margin-bottom: 6px;">
                      <i class="bi bi-gear-fill me-1"></i>Available Equipment
                    </div>
                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                      @if($bed->has_oxygen)
                      <span style="background: white; padding: 4px 10px; border-radius: 4px; font-size: 11px; color: #111827; border: 1px solid #e5e7eb;">
                        <i class="bi bi-droplet-fill"></i> Oxygen
                      </span>
                      @endif
                      @if($bed->has_ventilator)
                      <span style="background: white; padding: 4px 10px; border-radius: 4px; font-size: 11px; color: #111827; border: 1px solid #e5e7eb;">
                        <i class="bi bi-fan"></i> Ventilator
                      </span>
                      @endif
                      @if($bed->has_monitor)
                      <span style="background: white; padding: 4px 10px; border-radius: 4px; font-size: 11px; color: #111827; border: 1px solid #e5e7eb;">
                        <i class="bi bi-activity"></i> Monitor
                      </span>
                      @endif
                    </div>
                  </div>
                  @endif
                </div>
              </div>
            </div>
            
            <!-- Right Column -->
            <div>
            </div>
          </div>
          
          <!-- Notes Section (Full Width) -->
          @if($admission->admission_notes || $admission->special_instructions)
          <div style="padding: 20px; background: #f9fafb; border-top: 1px solid #e5e7eb;">
            <div style="display: grid; gap: 16px;">
              @if($admission->admission_notes)
              <div>
                <h6 style="font-size: 13px; font-weight: 600; color: #111827; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">
                  <i class="bi bi-journal-text me-2"></i>Admission Notes
                </h6>
                <div style="padding: 12px; background: white; border-radius: 6px; border: 1px solid #e5e7eb; font-size: 13px; color: #111827;">
                  {{ $admission->admission_notes }}
                </div>
              </div>
              @endif
              
              @if($admission->special_instructions)
              <div>
                <h6 style="font-size: 13px; font-weight: 600; color: #111827; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">
                  <i class="bi bi-exclamation-triangle-fill me-2"></i>Special Instructions
                </h6>
                <div style="padding: 12px; background: white; border-radius: 6px; border: 1px solid #e5e7eb; font-size: 13px; color: #111827; font-weight: 500;">
                  {{ $admission->special_instructions }}
                </div>
              </div>
              @endif
            </div>
          </div>
          @endif
        </div>
        <div class="modal-footer" style="background: #f9fafb; border-top: 2px solid #e5e7eb;">
          <a href="{{ route('admissions.show', $admission) }}" class="btn btn-primary" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
            <i class="bi bi-file-earmark-text me-1"></i>View Full Admission Record
          </a>
          <a href="{{ route('patients.show', $patient) }}" class="btn btn-outline-primary">
            <i class="bi bi-person me-1"></i>View Patient Profile
          </a>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  @endif
@endforeach
@endsection
