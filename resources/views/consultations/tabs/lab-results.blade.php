<!-- Patient Journey Tracker -->
<div class="section-card" style="background: white; border: 1px solid #e5e7eb; margin-bottom: 24px;">
  <div class="d-flex justify-content-between align-items-center">
    <div>
      <h4 style="margin: 0; font-weight: 600; margin-bottom: 4px; color: #111827;">
        <i class="bi bi-diagram-3"></i> Patient Journey Status
      </h4>
      <p style="margin: 0; color: #6b7280; font-size: 14px;">Track the complete treatment workflow for this consultation</p>
    </div>
    <div class="text-end">
      <div style="font-size: 13px; color: #6b7280;">Consultation Status</div>
      <span class="badge" style="font-size: 14px; padding: 6px 12px; background: #e0e7ff; color: #3730a3; font-weight: 600;">
        {{ ucfirst($consultation->status) }}
      </span>
    </div>
  </div>
</div>

<!-- Workflow Steps -->
<div class="row g-3 mb-4">
  <!-- Step 1: Lab Tests -->
  <div class="col-md-4">
    <div class="section-card" style="border-left: 4px solid {{ $consultation->labOrders->count() > 0 ? '#667eea' : '#d1d5db' }}; height: 100%;">
      <div class="d-flex align-items-start gap-3">
        <div style="width: 44px; height: 44px; border-radius: 10px; background: #f3f4f6; display: flex; align-items: center; justify-content: center; color: #667eea; font-size: 20px; flex-shrink: 0;">
          <i class="bi bi-clipboard-pulse"></i>
        </div>
        <div style="flex: 1;">
          <h6 style="font-weight: 600; margin-bottom: 4px; color: #111827;">Laboratory Tests</h6>
          @if($consultation->labOrders->count() > 0)
            <div style="font-size: 13px; color: #667eea; font-weight: 600; margin-bottom: 8px;">
              <i class="bi bi-check-circle"></i> {{ $consultation->labOrders->count() }} Test(s) Ordered
            </div>
            <div style="font-size: 12px; color: #6b7280;">
              @php
                $allReported = $consultation->labOrders->every(fn($o) => $o->status === 'reported');
                $anyReported = $consultation->labOrders->contains(fn($o) => $o->status === 'reported');
                $pending = $consultation->labOrders->where('status', 'pending')->count();
              @endphp
              @if($allReported)
                ✓ All results available
              @elseif($anyReported)
                {{ $consultation->labOrders->where('status', 'reported')->count() }} completed, {{ $pending }} pending
              @else
                {{ $pending }} test(s) in process
              @endif
            </div>
          @else
            <div style="font-size: 13px; color: #64748b;">No tests ordered</div>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- Step 2: Prescriptions -->
  <div class="col-md-4">
    <div class="section-card" style="border-left: 4px solid {{ $consultation->prescriptions->count() > 0 ? '#667eea' : '#d1d5db' }}; height: 100%;">
      <div class="d-flex align-items-start gap-3">
        <div style="width: 44px; height: 44px; border-radius: 10px; background: #f3f4f6; display: flex; align-items: center; justify-content: center; color: #667eea; font-size: 20px; flex-shrink: 0;">
          <i class="bi bi-prescription2"></i>
        </div>
        <div style="flex: 1;">
          <h6 style="font-weight: 600; margin-bottom: 4px; color: #111827;">Pharmacy</h6>
          @if($consultation->prescriptions->count() > 0)
            <div style="font-size: 13px; color: #667eea; font-weight: 600; margin-bottom: 8px;">
              <i class="bi bi-check-circle"></i> {{ $consultation->prescriptions->count() }} Prescription(s)
            </div>
            <div style="font-size: 12px; color: #6b7280;">
              @php
                $dispensed = $consultation->prescriptions->where('status', 'dispensed')->count();
                $pending = $consultation->prescriptions->where('status', 'pending')->count();
              @endphp
              @if($dispensed > 0)
                {{ $dispensed }} dispensed
              @endif
              @if($pending > 0)
                {{ $pending }} awaiting pharmacy
              @endif
            </div>
            <a href="#" onclick="document.getElementById('prescriptions-tab').click(); return false;" class="btn btn-sm btn-outline-primary mt-2" style="font-size: 12px;">
              <i class="bi bi-arrow-right"></i> View Prescriptions
            </a>
          @else
            <div style="font-size: 13px; color: #64748b;">No medications prescribed</div>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- Step 3: Next Action -->
  <div class="col-md-4">
    <div class="section-card" style="border-left: 4px solid {{ $consultation->status === 'completed' ? '#667eea' : '#d1d5db' }}; height: 100%;">
      <div class="d-flex align-items-start gap-3">
        <div style="width: 44px; height: 44px; border-radius: 10px; background: #f3f4f6; display: flex; align-items: center; justify-content: center; color: #667eea; font-size: 20px; flex-shrink: 0;">
          <i class="bi bi-signpost-2"></i>
        </div>
        <div style="flex: 1;">
          <h6 style="font-weight: 600; margin-bottom: 4px; color: #111827;">Next Step</h6>
          @if($consultation->status === 'in-progress')
            <div style="font-size: 13px; color: #667eea; font-weight: 600; margin-bottom: 8px;">
              <i class="bi bi-hourglass-split"></i> Consultation Ongoing
            </div>
            <div style="font-size: 12px; color: #6b7280;">
              Complete consultation when ready
            </div>
          @elseif($consultation->status === 'completed')
            @php
              $needsPharmacy = $consultation->prescriptions->where('status', 'pending')->count() > 0;
              $allTestsDone = $consultation->labOrders->count() === 0 || $consultation->labOrders->every(fn($o) => $o->status === 'reported');
            @endphp
            
            @if($needsPharmacy)
              <div style="font-size: 13px; color: #667eea; font-weight: 600; margin-bottom: 8px;">
                <i class="bi bi-arrow-right-circle"></i> Go to Pharmacy
              </div>
              <div style="font-size: 12px; color: #6b7280; margin-bottom: 8px;">
                Pick up prescribed medications
              </div>
            @elseif(!$allTestsDone)
              <div style="font-size: 13px; color: #667eea; font-weight: 600; margin-bottom: 8px;">
                <i class="bi bi-hourglass-split"></i> Awaiting Lab Results
              </div>
              <div style="font-size: 12px; color: #6b7280;">
                Return for review after tests
              </div>
            @else
              <div style="font-size: 13px; color: #10b981; font-weight: 600; margin-bottom: 8px;">
                <i class="bi bi-check-circle"></i> Ready for Discharge
              </div>
              <div style="font-size: 12px; color: #6b7280;">
                All steps completed
              </div>
            @endif
            
            @if($consultation->admission_status === 'admitted')
              <div class="alert alert-danger p-2 mt-2" style="font-size: 12px; margin-bottom: 0;">
                <i class="bi bi-hospital"></i> <strong>Patient Admitted to IPD</strong>
              </div>
            @endif
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Lab Tests & Results Section -->
<div class="section-card">
  <div class="section-title">
    <i class="bi bi-clipboard-pulse"></i>
    Laboratory Tests & Results
    @if($consultation->labOrders->count() > 0)
      <span class="badge bg-primary">{{ $consultation->labOrders->count() }}</span>
    @endif
  </div>
  
  @if($consultation->labOrders->count() > 0)
    @foreach($consultation->labOrders as $order)
      <div class="lab-order-card {{ $order->status === 'reported' ? 'reported' : '' }}">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <div>
            <h5 style="font-size: 16px; font-weight: 600; margin-bottom: 4px;">
              {{ $order->order_number }}
            </h5>
            <small class="text-muted">Ordered {{ $order->created_at->diffForHumans() }}</small>
          </div>
          <span class="badge bg-{{ 
            $order->status === 'reported' ? 'success' : 
            ($order->status === 'sample_collected' ? 'info' : 
            ($order->status === 'in_progress' ? 'warning' : 'secondary')) 
          }}">
            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
          </span>
        </div>
        
        <div class="mb-2">
          <strong>Tests Ordered:</strong> {{ $order->tests }}
        </div>
        
        @if($order->order_type)
          <div class="mb-2">
            <span class="badge bg-info">{{ ucfirst($order->order_type) }}</span>
            @if($order->priority === 'urgent')
              <span class="badge bg-danger"><i class="bi bi-exclamation-triangle"></i> URGENT</span>
            @endif
          </div>
        @endif
        
        @if($order->status === 'reported' && $order->results)
          <div class="result-box">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <h6 style="margin: 0; font-weight: 600; color: #10b981;">
                <i class="bi bi-check-circle"></i> Results Available
                @if($order->viewed_at)
                  <span class="badge bg-secondary ms-2" style="font-size: 11px;">
                    <i class="bi bi-eye-fill"></i> Viewed
                  </span>
                @else
                  <span class="badge bg-success ms-2" style="font-size: 11px; animation: pulse 2s infinite;">
                    <i class="bi bi-star-fill"></i> NEW
                  </span>
                @endif
              </h6>
              <div class="text-end">
                <small class="text-muted">Reported: {{ $order->reported_at->format('M d, Y h:i A') }}</small>
                @if($order->viewed_at)
                  <br>
                  <small class="text-muted" style="font-size: 11px;">
                    <i class="bi bi-eye"></i> Viewed: {{ $order->viewed_at->format('M d, Y h:i A') }}
                    @if($order->viewedBy)
                      by {{ $order->viewedBy->name }}
                    @endif
                  </small>
                @endif
              </div>
            </div>
            
            <!-- Structured Test Parameters -->
            @if($order->result_values && is_array($order->result_values) && count(array_filter($order->result_values)) > 0)
              <div style="background: #f0fdf4; border: 1px solid #86efac; padding: 16px; border-radius: 8px; margin-bottom: 16px;">
                <h6 style="font-weight: 600; color: #15803d; margin-bottom: 12px;">
                  <i class="bi bi-clipboard2-data"></i> Test Parameters
                </h6>
                <div class="row g-3">
                  @foreach($order->result_values as $param => $value)
                    @if($value)
                      <div class="col-md-4">
                        <div style="background: white; padding: 12px; border-radius: 6px; border-left: 3px solid #10b981;">
                          <div style="font-size: 11px; text-transform: uppercase; color: #64748b; font-weight: 600; margin-bottom: 4px;">
                            {{ ucfirst(str_replace('_', ' ', $param)) }}
                          </div>
                          <div style="font-size: 18px; font-weight: 700; color: #0f172a;">
                            {{ $value }}
                          </div>
                        </div>
                      </div>
                    @endif
                  @endforeach
                </div>
              </div>
            @endif
            
            <!-- Results Summary/Interpretation -->
            <div style="background: #f8fafc; padding: 12px; border-radius: 6px; margin-top: 8px;">
              <h6 style="font-weight: 600; margin-bottom: 8px; color: #334155;">
                <i class="bi bi-file-text"></i> Results Summary & Interpretation
              </h6>
              <pre style="margin: 0; white-space: pre-wrap; font-family: inherit; font-size: 14px; line-height: 1.6;">{{ $order->results }}</pre>
            </div>
            
            <!-- Lab Technician Notes -->
            @if($order->lab_technician_notes)
              <div style="background: #fef3c7; border: 1px solid #fbbf24; padding: 12px; border-radius: 6px; margin-top: 12px;">
                <h6 style="font-weight: 600; margin-bottom: 8px; color: #92400e;">
                  <i class="bi bi-chat-left-text"></i> Lab Technician Notes
                </h6>
                <div style="font-size: 14px; color: #78350f;">{{ $order->lab_technician_notes }}</div>
              </div>
            @endif
            
            <!-- Attached Files -->
            @if($order->result_file_path)
              <div class="mt-3">
                <strong><i class="bi bi-paperclip"></i> Attached Report:</strong>
                <div class="d-flex gap-2 mt-2 flex-wrap">
                  <a href="{{ Storage::url($order->result_file_path) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                    <i class="bi bi-file-earmark-pdf"></i> View Lab Report
                  </a>
                </div>
              </div>
            @endif
            
            <!-- Imaging Files (for radiology orders) -->
            @if($order->imaging_file_path)
              <div class="mt-3">
                <strong><i class="bi bi-file-earmark-image"></i> Imaging Files:</strong>
                <div class="d-flex gap-2 mt-2 flex-wrap">
                  <a href="{{ Storage::url($order->imaging_file_path) }}" class="btn btn-sm btn-outline-info" target="_blank">
                    <i class="bi bi-image"></i> View Images/Scans
                  </a>
                </div>
              </div>
            @endif
            
            <!-- Radiologist Findings (for radiology orders) -->
            @if($order->radiologist_findings)
              <div style="background: #ede9fe; border: 1px solid #a78bfa; padding: 12px; border-radius: 6px; margin-top: 12px;">
                <h6 style="font-weight: 600; margin-bottom: 8px; color: #5b21b6;">
                  <i class="bi bi-person-badge"></i> Radiologist Findings
                </h6>
                <div style="font-size: 14px; color: #6b21a8;">{{ $order->radiologist_findings }}</div>
              </div>
            @endif
            
            <!-- Reported By Info -->
            @if($order->reportedBy)
              <div class="mt-3" style="padding-top: 12px; border-top: 1px solid #e2e8f0;">
                <small class="text-muted">
                  <i class="bi bi-person-check"></i> <strong>Reported by:</strong> {{ $order->reportedBy->name }}
                  @if($order->reportedBy->roles->first())
                    ({{ ucfirst($order->reportedBy->roles->first()->name) }})
                  @endif
                </small>
              </div>
            @endif
          </div>
        @elseif($order->status === 'sample_collected')
          <div class="alert alert-info mt-2 mb-0">
            <i class="bi bi-hourglass-split"></i> Sample collected. Processing in laboratory...
          </div>
        @elseif($order->status === 'in_progress')
          <div class="alert alert-warning mt-2 mb-0">
            <i class="bi bi-gear"></i> Test in progress at laboratory
          </div>
        @elseif($order->status === 'pending')
          <div class="alert alert-secondary mt-2 mb-0">
            <i class="bi bi-clock"></i> Awaiting sample collection
          </div>
        @endif
      </div>
    @endforeach
  @else
    <div class="empty-state">
      <i class="bi bi-clipboard-pulse"></i>
      <h5>No Lab Tests Ordered</h5>
      <p>No laboratory investigations have been ordered for this consultation.</p>
    </div>
  @endif
</div>

<!-- Prescription Summary (if exists) -->
@if($consultation->prescriptions->count() > 0)
  <div class="section-card" style="margin-top: 24px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div class="section-title mb-0">
        <i class="bi bi-prescription2"></i>
        Prescription Summary
        <span class="badge bg-info">{{ $consultation->prescriptions->count() }}</span>
      </div>
      <a href="#" onclick="document.getElementById('prescriptions-tab').click(); return false;" class="btn btn-sm btn-primary">
        <i class="bi bi-arrow-right"></i> View Full Details
      </a>
    </div>
    
    @foreach($consultation->prescriptions as $prescription)
      <div style="background: #f8fafc; padding: 16px; border-radius: 8px; border-left: 4px solid #3b82f6; margin-bottom: 12px;">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h6 style="margin: 0; font-weight: 600;">{{ $prescription->prescription_number }}</h6>
          <span class="badge bg-{{ $prescription->status === 'dispensed' ? 'success' : 'warning' }}">
            {{ ucfirst($prescription->status) }}
          </span>
        </div>
        <div style="font-size: 14px; color: #64748b; margin-bottom: 8px;">
          {{ $prescription->items->count() }} medicine(s) | Prescribed {{ $prescription->created_at->format('M d, Y h:i A') }}
          @if($prescription->status === 'dispensed' && $prescription->dispensed_at)
            <br>
            <span class="text-success">
              <i class="bi bi-check-circle-fill"></i> Dispensed {{ $prescription->dispensed_at->format('M d, Y h:i A') }}
              @if($prescription->dispensedBy)
                by {{ $prescription->dispensedBy->name }}
              @endif
            </span>
          @endif
        </div>
        <div style="font-size: 13px;">
          <strong>Medicines:</strong>
          @foreach($prescription->items as $item)
            <span class="badge bg-white me-1" style="color: #667eea; border: 1px solid #667eea;">{{ $item->medicine_name }}</span>
          @endforeach
        </div>
        @if($prescription->status === 'pending')
          <div class="alert alert-warning mt-2 mb-0" style="font-size: 13px; padding: 8px;">
            <i class="bi bi-exclamation-triangle"></i> <strong>Action Required:</strong> Patient needs to visit pharmacy to collect medications
          </div>
        @endif
      </div>
    @endforeach
  </div>
@endif
