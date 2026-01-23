@if($consultation->prescriptions->count() > 0)
  @foreach($consultation->prescriptions as $prescription)
    <div class="section-card">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="section-title mb-0">
          <i class="bi bi-prescription2"></i>
          {{ $prescription->prescription_number }}
        </div>
        <div class="d-flex gap-2 align-items-center">
          <span class="badge bg-{{ $prescription->status === 'dispensed' ? 'success' : 'warning' }}">
            {{ ucfirst($prescription->status) }}
          </span>
          @if($prescription->status !== 'dispensed' && $prescription->status !== 'cancelled')
            <a href="{{ route('pharmacy.show', $prescription->id) }}" 
               class="btn btn-sm btn-outline-primary"
               title="View in Pharmacy">
              <i class="bi bi-capsule"></i> View in Pharmacy
            </a>
          @endif
        </div>
      </div>
      
      <div class="mb-3">
        <small class="text-muted">
          Prescribed on: {{ $prescription->created_at->format('F d, Y h:i A') }}
          @if($prescription->status === 'dispensed' && $prescription->dispensed_at)
            <br>
            <span class="text-success">
              <i class="bi bi-check-circle-fill"></i> Dispensed on: {{ $prescription->dispensed_at->format('F d, Y h:i A') }}
              @if($prescription->dispensedBy)
                by <strong>{{ $prescription->dispensedBy->name }}</strong>
                @if($prescription->dispensedBy->roles->first())
                  ({{ ucfirst($prescription->dispensedBy->roles->first()->name) }})
                @endif
              @endif
            </span>
          @endif
        </small>
      </div>
      
      <table class="prescription-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Medicine</th>
            <th>Dosage</th>
            <th>Frequency</th>
            <th>Duration</th>
            <th>Qty</th>
            <th>Instructions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($prescription->items as $index => $item)
            <tr>
              <td>{{ $index + 1 }}</td>
              <td><strong>{{ $item->medicine_name }}</strong></td>
              <td>{{ $item->dosage }}</td>
              <td>{{ $item->frequency }}</td>
              <td>{{ $item->duration }}</td>
              <td>{{ $item->quantity }}</td>
              <td>{{ $item->instructions ?? '-' }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
      
      @if($prescription->notes)
        <div class="mt-3">
          <label class="data-label d-block mb-2">Additional Notes</label>
          <div class="text-content">
            {{ $prescription->notes }}
          </div>
        </div>
      @endif
    </div>
  @endforeach
  
  <div class="d-flex justify-content-end mt-3">
    <button class="btn btn-primary">
      <i class="bi bi-printer"></i> Print All Prescriptions
    </button>
  </div>
@else
  <div class="empty-state">
    <i class="bi bi-prescription2"></i>
    <h5>No Prescriptions</h5>
    <p>No medications have been prescribed for this consultation.</p>
  </div>
@endif
