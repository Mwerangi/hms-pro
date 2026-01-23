@extends('layouts.app')

@section('title', 'Lab Order Details - ' . $labOrder->order_number)

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('lab.dashboard') }}">Laboratory</a></li>
<li class="breadcrumb-item active" aria-current="page">Order Details</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Lab Order Details</h1>
            <p class="text-muted mb-0">{{ $labOrder->order_number }}</p>
        </div>
        <div>
            <a href="{{ $labOrder->test_type === 'imaging' ? route('radiology.dashboard') : route('lab.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Patient & Order Info -->
        <div class="col-md-4">
            <!-- Patient Info Card -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="bi bi-person"></i> Patient Information</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="bi bi-person text-primary" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h5 class="text-center mb-3">{{ $labOrder->patient->full_name }}</h5>
                    
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="text-muted">Patient ID:</td>
                            <td class="text-end"><strong>{{ $labOrder->patient->patient_id }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Age/Gender:</td>
                            <td class="text-end">{{ $labOrder->patient->age }} years / {{ ucfirst($labOrder->patient->gender) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Phone:</td>
                            <td class="text-end">{{ $labOrder->patient->phone_number }}</td>
                        </tr>
                    </table>
                    
                    <a href="{{ route('patients.show', $labOrder->patient) }}" class="btn btn-outline-primary btn-sm w-100">
                        <i class="bi bi-eye"></i> View Full Record
                    </a>
                </div>
            </div>

            <!-- Order Info Card -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="bi bi-clipboard2-pulse"></i> Order Information</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted">Ordered By:</td>
                            <td class="text-end"><strong>{{ $labOrder->doctor->name }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Order Date:</td>
                            <td class="text-end">{{ $labOrder->order_date->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Urgency:</td>
                            <td class="text-end">{!! $labOrder->urgency_badge !!}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Priority:</td>
                            <td class="text-end">{!! $labOrder->priority_badge !!}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status:</td>
                            <td class="text-end">{!! $labOrder->status_badge !!}</td>
                        </tr>
                        @if($labOrder->scheduled_at)
                        <tr>
                            <td class="text-muted">Scheduled:</td>
                            <td class="text-end">{{ $labOrder->scheduled_at->format('M d, Y h:i A') }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            @if($labOrder->consultation)
            <!-- Consultation Link -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="mb-2"><i class="bi bi-stethoscope"></i> Related Consultation</h6>
                    <p class="mb-2"><strong>{{ $labOrder->consultation->consultation_number }}</strong></p>
                    <a href="{{ route('consultations.show', $labOrder->consultation) }}" class="btn btn-outline-primary btn-sm w-100">
                        View Consultation
                    </a>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column - Test Details & Actions -->
        <div class="col-md-8">
            <!-- Test Details Card -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Test Details</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Test Type</label>
                            <p><span class="badge bg-secondary">{{ ucfirst($labOrder->test_type) }}</span></p>
                        </div>
                        @if($labOrder->test_category)
                        <div class="col-md-6">
                            <label class="form-label text-muted">Test Category</label>
                            <p><strong>{{ $labOrder->test_category }}</strong></p>
                        </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Tests Ordered</label>
                        <p class="border p-3 rounded bg-light">{{ $labOrder->tests_list }}</p>
                    </div>

                    @if($labOrder->clinical_notes)
                    <div class="mb-3">
                        <label class="form-label text-muted">Clinical Notes</label>
                        <p class="border p-3 rounded bg-light">{{ $labOrder->clinical_notes }}</p>
                    </div>
                    @endif

                    @if($labOrder->special_instructions)
                    <div class="mb-3">
                        <label class="form-label text-muted">Special Instructions</label>
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> {{ $labOrder->special_instructions }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Results Card -->
            @if($labOrder->status !== 'pending')
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-file-earmark-text"></i> Test Results</h5>
                </div>
                <div class="card-body">
                    @if($labOrder->sample_collected_at)
                    <div class="mb-3">
                        <label class="text-muted">Sample Collected</label>
                        <p>{{ $labOrder->sample_collected_at->format('M d, Y h:i A') }}
                            @if($labOrder->collectedBy)
                                by <strong>{{ $labOrder->collectedBy->name }}</strong>
                            @endif
                        </p>
                    </div>
                    @endif

                    @if($labOrder->completed_at)
                    <div class="mb-3">
                        <label class="text-muted">Completed</label>
                        <p>{{ $labOrder->completed_at->format('M d, Y h:i A') }}
                            @if($labOrder->processedBy)
                                by <strong>{{ $labOrder->processedBy->name }}</strong>
                            @endif
                        </p>
                    </div>
                    @endif

                    @if($labOrder->results)
                    <div class="mb-3">
                        <label class="text-muted">Results / Findings</label>
                        <div class="border p-3 rounded bg-light">
                            {{ $labOrder->results }}
                        </div>
                    </div>
                    @endif

                    @if($labOrder->radiologist_findings)
                    <div class="mb-3">
                        <label class="text-muted">Radiologist Findings</label>
                        <div class="border p-3 rounded bg-light">
                            {{ $labOrder->radiologist_findings }}
                        </div>
                    </div>
                    @endif

                    @if($labOrder->result_file_path)
                    <div class="mb-3">
                        <a href="{{ route('lab.download-result', $labOrder) }}" class="btn btn-primary">
                            <i class="bi bi-download"></i> Download Report
                        </a>
                    </div>
                    @endif

                    @if($labOrder->imaging_file_path)
                    <div class="mb-3">
                        <a href="{{ route('lab.download-imaging', $labOrder) }}" class="btn btn-info">
                            <i class="bi bi-download"></i> Download Images
                        </a>
                    </div>
                    @endif

                    @if($labOrder->lab_technician_notes)
                    <div class="mb-3">
                        <label class="text-muted">Lab Technician Notes</label>
                        <p class="border p-3 rounded bg-light">{{ $labOrder->lab_technician_notes }}</p>
                    </div>
                    @endif

                    @if($labOrder->reported_at)
                    <div class="alert alert-success mb-0">
                        <i class="bi bi-check-circle"></i> Reported on {{ $labOrder->reported_at->format('M d, Y h:i A') }}
                        @if($labOrder->reportedBy)
                            by <strong>{{ $labOrder->reportedBy->name }}</strong>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="mb-3">Actions</h6>
                    <div class="d-flex gap-2 flex-wrap">
                        @if($labOrder->canCollectSample())
                            <a href="{{ route('lab.collect-sample', $labOrder) }}" class="btn btn-success">
                                <i class="bi bi-droplet"></i> Collect Sample
                            </a>
                        @endif

                        @if($labOrder->canProcess())
                            @if($labOrder->test_type === 'imaging')
                                <a href="{{ route('lab.radiology-process', $labOrder) }}" class="btn btn-primary">
                                    <i class="bi bi-camera"></i> Process Imaging
                                </a>
                            @else
                                <a href="{{ route('lab.process', $labOrder) }}" class="btn btn-info">
                                    <i class="bi bi-play-circle"></i> Start Processing
                                </a>
                            @endif
                        @endif

                        @if($labOrder->canComplete() && $labOrder->test_type !== 'imaging')
                            <a href="{{ route('lab.results', $labOrder) }}" class="btn btn-primary">
                                <i class="bi bi-file-earmark-text"></i> Enter Results
                            </a>
                        @endif

                        @if($labOrder->canReport() && $labOrder->status !== 'reported')
                            <form action="{{ route('lab.mark-reported', $labOrder) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle"></i> Mark as Reported
                                </button>
                            </form>
                        @endif

                        @if($labOrder->canBeCancelled())
                            <form action="{{ route('lab.cancel', $labOrder) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this lab order?')">
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-x-circle"></i> Cancel Order
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
