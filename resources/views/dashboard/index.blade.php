@extends('layouts.app')

@section('title', 'Dashboard')

@section('breadcrumbs')
<li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@push('styles')
<style>
  .dashboard-container { max-width: 1600px; margin: 0 auto; }
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
  .stat-card.patients { border-left: 4px solid #065f46; }
  .stat-card.patients .stat-value { color: #065f46; }
  .stat-card.appointments { border-left: 4px solid #1e40af; }
  .stat-card.appointments .stat-value { color: #1e40af; }
  .stat-card.admitted { border-left: 4px solid #7c2d12; }
  .stat-card.admitted .stat-value { color: #7c2d12; }
  .stat-card.revenue { border-left: 4px solid #374151; }
  .stat-card.revenue .stat-value { color: #374151; }
  
  /* Content Grid */
  .content-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-bottom: 24px; }
  @media (max-width: 1200px) { .content-grid { grid-template-columns: 1fr; } }
  
  /* Table */
  .table-card { background: white; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; margin-bottom: 24px; }
  .table-header { padding: 20px 24px; border-bottom: 2px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; }
  .table-title { font-size: 16px; font-weight: 600; color: #111827; margin: 0; }
  .view-all { font-size: 13px; color: #111827; text-decoration: none; font-weight: 500; }
  .view-all:hover { text-decoration: underline; }
  .dashboard-table { width: 100%; }
  .dashboard-table thead th { background: #f9fafb; color: #6b7280; font-size: 12px; font-weight: 600; text-transform: uppercase; padding: 14px 20px; border-bottom: 2px solid #e5e7eb; text-align: left; }
  .dashboard-table tbody td { padding: 16px 20px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #111827; }
  .dashboard-table tbody tr:hover { background: #f9fafb; }
  
  .status-badge { padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; display: inline-block; }
  .status-scheduled { background: #dbeafe; color: #1e40af; }
  .status-confirmed { background: #d1fae5; color: #065f46; }
  .status-pending { background: #fef3c7; color: #92400e; }
  .status-completed { background: #d1fae5; color: #065f46; }
  .status-admitted { background: #fef3c7; color: #92400e; }
  
  .empty-state { text-align: center; padding: 64px 24px; color: #9ca3af; }
  .empty-icon { font-size: 64px; margin-bottom: 16px; opacity: 0.5; }
  .empty-text { font-size: 16px; font-weight: 500; margin-bottom: 8px; }
  
  /* Activity Feed */
  .activity-item { padding: 16px 20px; border-bottom: 1px solid #f3f4f6; display: flex; gap: 12px; }
  .activity-item:last-child { border-bottom: none; }
  .activity-icon { width: 40px; height: 40px; border-radius: 10px; background: #f9fafb; display: flex; align-items: center; justify-content: center; color: #111827; flex-shrink: 0; }
  .activity-content { flex: 1; }
  .activity-text { font-size: 14px; color: #111827; margin-bottom: 4px; }
  .activity-time { font-size: 12px; color: #6b7280; }
</style>
@endpush

@section('content')
<div class="dashboard-container">
  <!-- Page Header -->
  <div class="page-header">
    <div>
      <h1 class="page-title">Dashboard</h1>
      <p class="page-subtitle">Welcome back, {{ auth()->user()->name }} - Here's what's happening today</p>
    </div>
  </div>

  <!-- Quick Actions -->
  <div class="quick-actions">
    @can('patients.create')
    <a href="{{ route('patients.create') }}" class="action-card">
      <div class="action-icon"><i class="bi bi-person-plus"></i></div>
      <div class="action-title">New Patient</div>
      <div class="action-subtitle">Register new patient</div>
    </a>
    @endcan
    @canany(['appointments.create', 'appointments.create-own'])
    <a href="{{ route('appointments.create') }}" class="action-card">
      <div class="action-icon"><i class="bi bi-calendar-plus"></i></div>
      <div class="action-title">New Appointment</div>
      <div class="action-subtitle">Schedule appointment</div>
    </a>
    @endcanany
    @canany(['consultations.create', 'consultations.create-own'])
    <a href="{{ route('consultations.create') }}" class="action-card">
      <div class="action-icon"><i class="bi bi-file-medical"></i></div>
      <div class="action-title">New Consultation</div>
      <div class="action-subtitle">Start consultation</div>
    </a>
    @endcanany
    @can('ipd.create-admission')
    <a href="{{ route('admissions.create') }}" class="action-card">
      <div class="action-icon"><i class="bi bi-hospital"></i></div>
      <div class="action-title">New Admission</div>
      <div class="action-subtitle">Admit patient to IPD</div>
    </a>
    @endcan
  </div>

  <!-- Statistics -->
  <div class="stats-grid">
    <div class="stat-card patients">
      <div class="stat-value">{{ \App\Models\Patient::count() }}</div>
      <div class="stat-label">Total Patients</div>
    </div>
    <div class="stat-card appointments">
      <div class="stat-value">{{ \App\Models\Appointment::whereDate('appointment_date', today())->count() }}</div>
      <div class="stat-label">Today's Appointments</div>
    </div>
    <div class="stat-card admitted">
      <div class="stat-value">{{ \App\Models\Admission::where('status', 'admitted')->count() }}</div>
      <div class="stat-label">Currently Admitted</div>
    </div>
    <div class="stat-card revenue">
      <div class="stat-value">TSh {{ number_format(\App\Models\Payment::whereDate('created_at', today())->sum('amount')) }}</div>
      <div class="stat-label">Today's Revenue</div>
    </div>
  </div>

  <!-- Content Grid -->
  <div class="content-grid">
    <!-- Recent Appointments -->
    <div class="table-card">
      <div class="table-header">
        <h5 class="table-title">
          <i class="bi bi-calendar-check me-2"></i>
          Today's Appointments
        </h5>
        @canany(['appointments.view-all', 'appointments.view-own'])
        <a href="{{ route('appointments.index') }}" class="view-all">View All →</a>
        @endcanany
      </div>

      @php
        $todayAppointments = \App\Models\Appointment::with(['patient', 'doctor'])
          ->whereDate('appointment_date', today())
          ->orderBy('appointment_time')
          ->limit(5)
          ->get();
      @endphp

      @if($todayAppointments->count() > 0)
      <div class="table-responsive">
        <table class="dashboard-table">
          <thead>
            <tr>
              <th>Time</th>
              <th>Patient</th>
              <th>Doctor</th>
              <th>Purpose</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach($todayAppointments as $appointment)
            <tr>
              <td><strong>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</strong></td>
              <td>
                <a href="{{ route('patients.show', $appointment->patient) }}" style="font-weight: 600; color: #111827; text-decoration: none;">
                  {{ $appointment->patient->full_name }}
                </a>
                <br>
                <small style="color: #6b7280;">{{ $appointment->patient->patient_id }}</small>
              </td>
              <td>Dr. {{ $appointment->doctor->name }}</td>
              <td>{{ Str::limit($appointment->purpose ?? 'General Checkup', 30) }}</td>
              <td>
                <span class="status-badge status-{{ $appointment->status }}">
                  {{ ucfirst($appointment->status) }}
                </span>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      @else
      <div class="empty-state">
        <div class="empty-icon"><i class="bi bi-calendar-x"></i></div>
        <div class="empty-text">No appointments today</div>
      </div>
      @endif
    </div>

    <!-- Recent Activity -->
    <div class="table-card">
      <div class="table-header">
        <h5 class="table-title">
          <i class="bi bi-clock-history me-2"></i>
          Recent Activity
        </h5>
      </div>

      @php
        $recentAdmissions = \App\Models\Admission::with(['patient'])->latest()->limit(3)->get();
        $recentConsultations = \App\Models\Consultation::with(['patient'])->latest()->limit(2)->get();
      @endphp

      <div>
        @foreach($recentAdmissions as $admission)
        <div class="activity-item">
          <div class="activity-icon"><i class="bi bi-hospital"></i></div>
          <div class="activity-content">
            <div class="activity-text">
              <strong>{{ $admission->patient->full_name }}</strong> admitted to {{ $admission->ward->ward_name }}
            </div>
            <div class="activity-time">{{ $admission->created_at->diffForHumans() }}</div>
          </div>
        </div>
        @endforeach

        @foreach($recentConsultations as $consultation)
        <div class="activity-item">
          <div class="activity-icon"><i class="bi bi-file-medical"></i></div>
          <div class="activity-content">
            <div class="activity-text">
              <strong>{{ $consultation->patient->full_name }}</strong> consultation completed
            </div>
            <div class="activity-time">{{ $consultation->created_at->diffForHumans() }}</div>
          </div>
        </div>
        @endforeach

        @if($recentAdmissions->count() === 0 && $recentConsultations->count() === 0)
        <div class="empty-state">
          <div class="empty-icon"><i class="bi bi-inbox"></i></div>
          <div class="empty-text">No recent activity</div>
        </div>
        @endif
      </div>
    </div>
  </div>

  <!-- Recent Patients -->
  <div class="table-card">
    <div class="table-header">
      <h5 class="table-title">
        <i class="bi bi-people me-2"></i>
        Recent Patients
      </h5>
      @can('patients.view')
      <a href="{{ route('patients.index') }}" class="view-all">View All →</a>
      @endcan
    </div>

    @php
      $recentPatients = \App\Models\Patient::latest()->limit(5)->get();
    @endphp

    @if($recentPatients->count() > 0)
    <div class="table-responsive">
      <table class="dashboard-table">
        <thead>
          <tr>
            <th>Patient ID</th>
            <th>Name</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Contact</th>
            <th>Registered</th>
          </tr>
        </thead>
        <tbody>
          @foreach($recentPatients as $patient)
          <tr>
            <td><strong>{{ $patient->patient_id }}</strong></td>
            <td>
              <a href="{{ route('patients.show', $patient) }}" style="font-weight: 600; color: #111827; text-decoration: none;">
                {{ $patient->full_name }}
              </a>
            </td>
            <td>{{ $patient->age }} years</td>
            <td>{{ ucfirst($patient->gender) }}</td>
            <td>{{ $patient->phone }}</td>
            <td>{{ $patient->created_at->diffForHumans() }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @else
    <div class="empty-state">
      <div class="empty-icon"><i class="bi bi-inbox"></i></div>
      <div class="empty-text">No patients registered yet</div>
    </div>
    @endif
  </div>
</div>
@endsection
