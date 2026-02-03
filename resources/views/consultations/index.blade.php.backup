@extends('layouts.app')

@section('title', 'Consultations')

@section('breadcrumbs')
<li class="breadcrumb-item active" aria-current="page">Consultations</li>
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

  .consultations-table {
    background: white;
    border-radius: 12px;
    padding: 24px;
    border: 1px solid #e5e7eb;
  }

  .section-title {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin: 0 0 20px 0;
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
    white-space: nowrap;
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

  .status-badge {
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
    display: inline-block;
  }

  .status-in-progress {
    background: #fef3c7;
    color: #92400e;
  }

  .status-completed {
    background: #d1fae5;
    color: #065f46;
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
    color: #9ca3af;
    margin: 0;
  }

  .btn-sm {
    padding: 6px 12px;
    font-size: 13px;
  }
</style>
@endpush

@section('content')
<div class="container-fluid">
  <!-- Page Header -->
  <div class="page-header">
    <h1 class="page-title">Consultations</h1>
    <p class="page-subtitle">View and manage patient consultations</p>
  </div>

  <!-- Consultations Table -->
  <div class="consultations-table">
    <h5 class="section-title">All Consultations ({{ $consultations->total() }})</h5>

    <div class="table-responsive">
      <table>
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
        @forelse($consultations as $consultation)
        <tr>
          <td>
            <strong>{{ $consultation->consultation_number }}</strong>
          </td>
          <td>{{ $consultation->created_at->format('M d, Y') }}</td>
          <td>
            <div style="font-weight: 600;">{{ $consultation->patient->full_name }}</div>
            <div style="font-size: 12px; color: #718096;">{{ $consultation->patient->patient_id }}</div>
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
              <a href="{{ route('consultations.show', $consultation) }}" 
                 class="btn btn-sm btn-primary" 
                 title="View Details">
                <i class="bi bi-eye"></i>
              </a>
              @if($consultation->status === 'in-progress')
              <a href="{{ route('consultations.edit', $consultation) }}" 
                 class="btn btn-sm btn-warning" 
                 title="Continue Editing">
                <i class="bi bi-pencil"></i>
              </a>
              @endif
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8">
            <div class="empty-state">
              <i class="bi bi-inbox"></i>
              <p>No consultations found</p>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

    <!-- Pagination -->
    @if($consultations->hasPages())
    <div class="d-flex justify-content-center mt-4">
      {{ $consultations->links() }}
    </div>
    @endif
  </div>
</div>
@endsection
