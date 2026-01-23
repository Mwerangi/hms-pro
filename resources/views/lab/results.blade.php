@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-file-earmark-text"></i> Enter Lab Results</h5>
                </div>
                <div class="card-body">
                    <!-- Order Info -->
                    <div class="alert alert-info">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Order #:</strong> {{ $labOrder->order_number }}<br>
                                <strong>Patient:</strong> {{ $labOrder->patient->full_name }} ({{ $labOrder->patient->patient_id }})<br>
                                <strong>Test Type:</strong> {{ ucfirst($labOrder->test_type) }}
                            </div>
                            <div class="col-md-6">
                                <strong>Tests:</strong> {{ $labOrder->tests_list }}<br>
                                <strong>Collected:</strong> {{ $labOrder->sample_collected_at ? $labOrder->sample_collected_at->format('M d, Y h:i A') : 'N/A' }}
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('lab.store-results', $labOrder) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Structured Result Values (Optional) -->
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Test Parameters (Optional)</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Hemoglobin (g/dL)</label>
                                        <input type="text" name="result_values[hemoglobin]" class="form-control" placeholder="e.g., 13.5">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">WBC Count (/μL)</label>
                                        <input type="text" name="result_values[wbc]" class="form-control" placeholder="e.g., 7500">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Platelet Count (/μL)</label>
                                        <input type="text" name="result_values[platelets]" class="form-control" placeholder="e.g., 250000">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">RBC Count (million/μL)</label>
                                        <input type="text" name="result_values[rbc]" class="form-control" placeholder="e.g., 5.2">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Blood Sugar (mg/dL)</label>
                                        <input type="text" name="result_values[blood_sugar]" class="form-control" placeholder="e.g., 95">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Creatinine (mg/dL)</label>
                                        <input type="text" name="result_values[creatinine]" class="form-control" placeholder="e.g., 1.0">
                                    </div>
                                </div>
                                <small class="text-muted">Add any relevant test parameters. These are optional and depend on the type of test.</small>
                            </div>
                        </div>

                        <!-- Results Summary (Required) -->
                        <div class="mb-3">
                            <label for="results" class="form-label">Results Summary / Interpretation <span class="text-danger">*</span></label>
                            <textarea name="results" id="results" class="form-control @error('results') is-invalid @enderror" rows="6" required placeholder="Enter complete test results and interpretation..."></textarea>
                            @error('results')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Provide a comprehensive summary of the test results including any abnormal findings.</small>
                        </div>

                        <!-- Upload Report File -->
                        <div class="mb-3">
                            <label for="result_file" class="form-label">Upload Report (PDF/Image)</label>
                            <input type="file" name="result_file" id="result_file" class="form-control @error('result_file') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
                            @error('result_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Optional: Upload a scanned or generated lab report (max 10MB).</small>
                        </div>

                        <!-- Lab Technician Notes -->
                        <div class="mb-3">
                            <label for="lab_technician_notes" class="form-label">Lab Technician Notes</label>
                            <textarea name="lab_technician_notes" id="lab_technician_notes" class="form-control @error('lab_technician_notes') is-invalid @enderror" rows="3" placeholder="Any additional observations, quality notes, or remarks..."></textarea>
                            @error('lab_technician_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('lab.show', $labOrder) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Save Results & Complete
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
