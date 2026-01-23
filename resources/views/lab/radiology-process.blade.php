@extends('layouts.app')

@push('styles')
<style>
  .process-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 24px;
  }

  .card-header-custom {
    background: white;
    border-bottom: 1px solid #e5e7eb;
    padding: 16px 24px;
    border-radius: 12px 12px 0 0;
  }

  .card-title {
    font-size: 18px;
    font-weight: 600;
    color: #111827;
    margin: 0;
  }

  .info-box {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 16px;
    margin-bottom: 20px;
  }

  .alert-custom {
    background: #fef3c7;
    border: 1px solid #fbbf24;
    border-radius: 10px;
    padding: 12px 16px;
    margin-bottom: 20px;
  }

  .checklist-box {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 16px;
  }

  .checklist-box h6 {
    font-size: 14px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 12px;
  }

  .checklist-box ul {
    margin-bottom: 0;
    font-size: 13px;
    color: #374151;
  }
</style>
@endpush

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="process-card">
        <div class="card-header-custom">
          <h5 class="card-title"><i class="bi bi-camera me-2"></i>Radiology - Process & Report</h5>
        </div>
        <div style="padding-top: 24px;">
          <!-- Order Info -->
          <div class="info-box">
            <div class="row">
              <div class="col-md-6">
                <strong>Order #:</strong> {{ $labOrder->order_number }}<br>
                <strong>Patient:</strong> {{ $labOrder->patient->full_name }} ({{ $labOrder->patient->patient_id }})<br>
                <strong>Age/Gender:</strong> {{ $labOrder->patient->age }} / {{ ucfirst($labOrder->patient->gender) }}
              </div>
              <div class="col-md-6">
                <strong>Imaging Type:</strong> {{ $labOrder->tests_list }}<br>
                <strong>Category:</strong> {{ $labOrder->test_category ?? 'N/A' }}<br>
                <strong>Urgency:</strong> {!! $labOrder->urgency_badge !!}
              </div>
            </div>
          </div>

          @if($labOrder->clinical_notes)
          <div class="alert-custom">
            <strong><i class="bi bi-info-circle"></i> Clinical Notes:</strong><br>
            {{ $labOrder->clinical_notes }}
          </div>
          @endif

                    <form action="{{ route('lab.store-radiology-results', $labOrder) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Radiologist Findings (Required) -->
                        <div class="mb-3">
                            <label for="radiologist_findings" class="form-label">Radiologist Findings & Interpretation <span class="text-danger">*</span></label>
                            <textarea name="radiologist_findings" id="radiologist_findings" class="form-control @error('radiologist_findings') is-invalid @enderror" rows="8" required placeholder="Enter detailed radiological findings, measurements, impressions, and recommendations...

Example:
FINDINGS:
- The lungs are clear bilaterally
- No pleural effusion or pneumothorax
- Cardiac silhouette is normal in size
- No acute bony abnormality

IMPRESSION:
- Normal chest radiograph
"></textarea>
                            @error('radiologist_findings')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Upload Imaging Files -->
                        <div class="mb-3">
                            <label for="imaging_file" class="form-label">Upload Imaging Files (X-Ray, CT, MRI, etc.)</label>
                            <input type="file" name="imaging_file" id="imaging_file" class="form-control @error('imaging_file') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png,.dcm">
                            @error('imaging_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Upload imaging files (JPEG, PNG, PDF, DICOM). Max 50MB.</small>
                        </div>

                        <!-- Upload Radiology Report -->
                        <div class="mb-3">
                            <label for="result_file" class="form-label">Upload Radiology Report (PDF)</label>
                            <input type="file" name="result_file" id="result_file" class="form-control @error('result_file') is-invalid @enderror" accept=".pdf">
                            @error('result_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Optional: Upload a formatted radiology report (max 10MB).</small>
                        </div>

                        <!-- Lab Technician Notes -->
                        <div class="mb-3">
                            <label for="lab_technician_notes" class="form-label">Technician Notes</label>
                            <textarea name="lab_technician_notes" id="lab_technician_notes" class="form-control @error('lab_technician_notes') is-invalid @enderror" rows="3" placeholder="Technical notes, image quality, patient cooperation, etc..."></textarea>
                            @error('lab_technician_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="checklist-box">
                            <h6>Report Checklist:</h6>
                            <ul class="mb-0">
                                <li>Verify patient identification on images</li>
                                <li>Review image quality and positioning</li>
                                <li>Document all findings systematically</li>
                                <li>Provide clear impression/conclusion</li>
                                <li>Recommend follow-up if needed</li>
                            </ul>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('lab.show', $labOrder) }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="bi bi-check-circle"></i> Submit Radiology Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
