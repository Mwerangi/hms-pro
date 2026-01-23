@extends('layouts.app')

@section('title', 'Prescription Details - ' . $prescription->prescription_number)

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('pharmacy.dashboard') }}">Pharmacy</a></li>
<li class="breadcrumb-item active" aria-current="page">Prescription Details</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('pharmacy.dashboard') }}">Pharmacy</a></li>
                    <li class="breadcrumb-item active">{{ $prescription->prescription_number }}</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0">Prescription Details</h1>
        </div>
        <div>
            <a href="{{ route('pharmacy.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Prescription Details -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-gradient text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-file-medical me-2"></i>
                            Prescription #{{ $prescription->prescription_number }}
                        </h5>
                        @if($prescription->status === 'pending')
                            <span class="badge bg-warning">Pending</span>
                        @elseif($prescription->status === 'partially-dispensed')
                            <span class="badge bg-info">Partially Dispensed</span>
                        @elseif($prescription->status === 'dispensed')
                            <span class="badge bg-success">Dispensed</span>
                        @elseif($prescription->status === 'cancelled')
                            <span class="badge bg-danger">Cancelled</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <!-- Prescription Info -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Prescription Date:</strong><br>
                                {{ $prescription->prescription_date->format('F d, Y') }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Valid Until:</strong><br>
                                {{ $prescription->valid_until ? $prescription->valid_until->format('F d, Y') : 'N/A' }}
                            </p>
                        </div>
                    </div>

                    @if($prescription->special_instructions)
                    <div class="alert alert-info">
                        <strong><i class="bi bi-info-circle me-2"></i>Special Instructions:</strong><br>
                        {{ $prescription->special_instructions }}
                    </div>
                    @endif

                    <!-- Medicines List -->
                    <h6 class="mb-3"><i class="bi bi-capsule me-2"></i>Prescribed Medicines</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Medicine</th>
                                    <th>Dosage</th>
                                    <th>Frequency</th>
                                    <th>Duration</th>
                                    <th>Instructions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($prescription->items as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $item->medicine_name }}</strong>
                                        @if($item->medicine_type)
                                        <br><small class="text-muted">{{ $item->medicine_type }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $item->dosage }}</td>
                                    <td>{{ $item->frequency }}</td>
                                    <td>{{ $item->duration }}</td>
                                    <td>
                                        @if($item->instructions)
                                            <small>{{ $item->instructions }}</small>
                                        @else
                                            <small class="text-muted">-</small>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No medicines prescribed</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($prescription->pharmacy_notes)
                    <div class="mt-3">
                        <strong>Pharmacy Notes:</strong>
                        <p class="text-muted mb-0">{{ $prescription->pharmacy_notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Dispensing Form -->
            @if($prescription->status === 'pending' || $prescription->status === 'partially-dispensed')
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="bi bi-check-circle me-2"></i>Dispense Prescription</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('pharmacy.dispense', $prescription->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="pharmacy_notes" class="form-label">Pharmacy Notes (Optional)</label>
                            <textarea class="form-control" 
                                      id="pharmacy_notes" 
                                      name="pharmacy_notes" 
                                      rows="3"
                                      placeholder="Add any notes about the dispensing (e.g., patient counseling provided, substitutions made, etc.)">{{ old('pharmacy_notes') }}</textarea>
                            @error('pharmacy_notes')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Please verify all medicines before dispensing. This action cannot be undone.
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle me-2"></i>Dispense Prescription
                            </button>
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                                <i class="bi bi-x-circle me-2"></i>Cancel Prescription
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @elseif($prescription->status === 'dispensed')
            <div class="alert alert-success">
                <i class="bi bi-check-circle me-2"></i>
                This prescription was dispensed on {{ $prescription->dispensed_at->format('F d, Y \a\t h:i A') }}
                @if($prescription->dispensedByUser)
                    by {{ $prescription->dispensedByUser->name }}
                @endif
            </div>
            @elseif($prescription->status === 'cancelled')
            <div class="alert alert-danger">
                <i class="bi bi-x-circle me-2"></i>
                This prescription has been cancelled.
            </div>
            @endif
        </div>

        <!-- Patient Information Sidebar -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="bi bi-person me-2"></i>Patient Information</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($prescription->patient->photo)
                            <img src="{{ asset('storage/' . $prescription->patient->photo) }}" 
                                 alt="{{ $prescription->patient->full_name }}" 
                                 class="rounded-circle mb-2"
                                 style="width: 100px; height: 100px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-secondary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2"
                                 style="width: 100px; height: 100px;">
                                <i class="bi bi-person fs-1 text-secondary"></i>
                            </div>
                        @endif
                        <h5 class="mb-0">{{ $prescription->patient->full_name }}</h5>
                        <p class="text-muted mb-0">{{ $prescription->patient->patient_id }}</p>
                    </div>

                    <hr>

                    <div class="mb-2">
                        <small class="text-muted">Age</small>
                        <p class="mb-0"><strong>{{ $prescription->patient->age }} years</strong></p>
                    </div>

                    <div class="mb-2">
                        <small class="text-muted">Gender</small>
                        <p class="mb-0"><strong>{{ ucfirst($prescription->patient->gender) }}</strong></p>
                    </div>

                    <div class="mb-2">
                        <small class="text-muted">Blood Group</small>
                        <p class="mb-0"><strong>{{ $prescription->patient->blood_group ?? 'N/A' }}</strong></p>
                    </div>

                    <div class="mb-2">
                        <small class="text-muted">Phone</small>
                        <p class="mb-0"><strong>{{ $prescription->patient->phone }}</strong></p>
                    </div>

                    @if($prescription->patient->allergies)
                    <div class="alert alert-danger mt-3 mb-0">
                        <strong><i class="bi bi-exclamation-triangle me-2"></i>Allergies:</strong><br>
                        {{ $prescription->patient->allergies }}
                    </div>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="bi bi-person-badge me-2"></i>Prescribed By</h5>
                </div>
                <div class="card-body">
                    <h6 class="mb-1">Dr. {{ $prescription->doctor->name }}</h6>
                    @if($prescription->doctor->specialization)
                        <p class="text-muted mb-0">{{ $prescription->doctor->specialization }}</p>
                    @endif
                    @if($prescription->doctor->email)
                        <p class="mb-0 mt-2">
                            <small><i class="bi bi-envelope me-2"></i>{{ $prescription->doctor->email }}</small>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('pharmacy.cancel', $prescription->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelModalLabel">Cancel Prescription</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        This action cannot be undone. Please provide a reason for cancellation.
                    </div>
                    <div class="mb-3">
                        <label for="cancellation_reason" class="form-label">Cancellation Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" 
                                  id="cancellation_reason" 
                                  name="cancellation_reason" 
                                  rows="3"
                                  required
                                  placeholder="Enter reason for cancellation"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Confirm Cancellation</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
@endsection
