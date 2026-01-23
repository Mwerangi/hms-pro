@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-play-circle"></i> Start Lab Processing</h5>
                </div>
                <div class="card-body">
                    <!-- Order Info -->
                    <div class="alert alert-info">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Order #:</strong> {{ $labOrder->order_number }}<br>
                                <strong>Patient:</strong> {{ $labOrder->patient->full_name }}<br>
                                <strong>Test Type:</strong> {{ ucfirst($labOrder->test_type) }}
                            </div>
                            <div class="col-md-6">
                                <strong>Tests:</strong> {{ $labOrder->tests_list }}<br>
                                <strong>Urgency:</strong> {!! $labOrder->urgency_badge !!}<br>
                                <strong>Priority:</strong> {!! $labOrder->priority_badge !!}
                            </div>
                        </div>
                    </div>

                    @if($labOrder->sample_collected_at)
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle"></i> Sample collected on {{ $labOrder->sample_collected_at->format('M d, Y h:i A') }}
                    </div>
                    @endif

                    <form action="{{ route('lab.store-process', $labOrder) }}" method="POST">
                        @csrf

                        <div class="alert alert-light border">
                            <h6>Before Starting:</h6>
                            <ul class="mb-0">
                                <li>Verify sample integrity</li>
                                <li>Check equipment calibration</li>
                                <li>Review test protocols</li>
                                <li>Document start time</li>
                            </ul>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('lab.show', $labOrder) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-info">
                                <i class="bi bi-play-circle"></i> Start Processing
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
