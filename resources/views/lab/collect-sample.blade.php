@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-droplet"></i> Collect Sample</h5>
                </div>
                <div class="card-body">
                    <!-- Order Info -->
                    <div class="alert alert-info">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Order #:</strong> {{ $labOrder->order_number }}<br>
                                <strong>Patient:</strong> {{ $labOrder->patient->full_name }}<br>
                                <strong>Patient ID:</strong> {{ $labOrder->patient->patient_id }}
                            </div>
                            <div class="col-md-6">
                                <strong>Test Type:</strong> {{ ucfirst($labOrder->test_type) }}<br>
                                <strong>Tests:</strong> {{ $labOrder->tests_list }}<br>
                                <strong>Urgency:</strong> {!! $labOrder->urgency_badge !!}
                            </div>
                        </div>
                    </div>

                    @if($labOrder->special_instructions)
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i> <strong>Special Instructions:</strong><br>
                        {{ $labOrder->special_instructions }}
                    </div>
                    @endif

                    <form action="{{ route('lab.store-sample-collection', $labOrder) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="collection_notes" class="form-label">Collection Notes (Optional)</label>
                            <textarea name="collection_notes" id="collection_notes" class="form-control @error('collection_notes') is-invalid @enderror" rows="3" placeholder="E.g., Sample quality, collection location, any observations..."></textarea>
                            @error('collection_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Document any relevant information about the sample collection.</small>
                        </div>

                        <div class="alert alert-light border">
                            <h6>Sample Collection Checklist:</h6>
                            <ul class="mb-0">
                                <li>Verify patient identity</li>
                                <li>Label sample containers properly</li>
                                <li>Note collection time</li>
                                <li>Store sample according to protocol</li>
                            </ul>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('lab.show', $labOrder) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Confirm Sample Collection
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
