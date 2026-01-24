# Pharmacy Payment Controls - UI Implementation

## Overview
Visual controls in the pharmacy interface to prevent medication dispensing until payment is verified. The dispense button becomes active only when payment requirements are met.

---

## Payment Status Indicators

### Dashboard View (Pharmacy)
The pharmacy dashboard now displays a **Payment Status** column for all pending prescriptions:

#### Status Badges:
1. **🔒 Pending** (Gray badge)
   - Payment verification required
   - Patient has not paid ≥50% of bill
   - Tooltip: "Payment verification required"

2. **✓ Verified** (Green badge)
   - Payment verified (≥50% paid)
   - Medication can be dispensed
   - Tooltip: "Payment verified - Can dispense"

3. **⚠️ Emergency** (Red badge)
   - Emergency override applied
   - Payment bypassed for urgent care
   - Tooltip: "Emergency - Payment bypassed"

---

## Prescription Detail View

### Payment Status Alerts

#### 1. Payment Verified ✓ (Green Alert)
```
✓ Payment Verified
Patient has paid 75.5% of the bill. Medication can be dispensed.
```

#### 2. Payment Required ⚠️ (Yellow Alert)
```
⚠️ Payment Verification Required
Patient must pay at least 50% of the bill before medication can be dispensed.

Current payment: TSh 15,000 (33.3% of TSh 45,000)

Options:
• Patient should make payment at billing counter
• Doctor/Admin can mark as emergency if urgent
```

#### 3. Emergency Override ⚠️ (Red Alert)
```
⚠️ EMERGENCY PRESCRIPTION
Payment verification bypassed for urgent medical care.

Approved by: Dr. John Doe
Reason: Severe anaphylactic reaction - life-threatening
```

---

## Dispense Button States

### State 1: INACTIVE (Payment Not Verified)
```html
[🔒 Dispense Prescription (Locked)]  [Mark as Emergency]  [Cancel]
```
- **Button**: Disabled, gray color, lock icon
- **Tooltip**: "Payment verification required before dispensing"
- **Pharmacy Notes**: Disabled (cannot enter text)
- **Available Actions**: Mark as Emergency (Doctor/Admin), Cancel

### State 2: ACTIVE (Payment Verified)
```html
[✓ Dispense Prescription]  [Cancel]
```
- **Button**: Enabled, green color, check icon
- **Pharmacy Notes**: Enabled (can enter text)
- **Info Alert**: "Please verify all medicines before dispensing. This action cannot be undone."

### State 3: ACTIVE (Emergency Override)
```html
[✓ Dispense Prescription]  [Cancel]
```
- **Button**: Enabled, green color
- **Emergency Alert**: Displays emergency justification and approver
- **No "Mark as Emergency" button** (already marked)

---

## Emergency Override Flow

### Trigger: "Mark as Emergency" Button
- **Visible**: Only when payment NOT verified and NOT already emergency
- **Access**: Doctors and Administrators only
- **Action**: Opens modal for medical justification

### Emergency Modal
**Title**: ⚠️ Mark as Emergency

**Warning Alert**:
```
⚠️ Emergency Override
This will bypass payment verification and allow immediate dispensing. 
Use only for life-threatening situations.
```

**Form Fields**:
- **Medical Justification** (Required)
  - Textarea (4 rows)
  - Placeholder: "Provide detailed medical justification for emergency override 
    (e.g., anaphylactic shock, severe hemorrhage, etc.)"
  - Note: "This action will be logged with your name and timestamp."

**Actions**:
- Cancel (Secondary)
- Confirm Emergency Override (Danger/Red)

---

## User Experience Flow

### Scenario 1: Normal Payment Flow
```
1. Pharmacy receives prescription → Dashboard shows "🔒 Pending"
2. Pharmacist clicks "View" → Sees yellow alert: "Payment Required"
3. Dispense button DISABLED and LOCKED
4. Patient goes to billing counter
5. Patient pays ≥50% → System auto-verifies prescription
6. Dashboard updates to "✓ Verified"
7. Pharmacist refreshes view → Green alert: "Payment Verified"
8. Dispense button ENABLED
9. Pharmacist dispenses medication
```

### Scenario 2: Emergency Override
```
1. Pharmacy receives prescription → Dashboard shows "🔒 Pending"
2. Patient in critical condition (e.g., anaphylaxis)
3. Doctor opens prescription → Clicks "Mark as Emergency"
4. Enters justification: "Anaphylactic shock - life-threatening"
5. Confirms override
6. Dashboard updates to "⚠️ Emergency"
7. Pharmacist sees red alert with emergency details
8. Dispense button ENABLED (payment bypassed)
9. Pharmacist dispenses immediately
```

### Scenario 3: Partial Payment
```
1. Bill Total: TSh 100,000
2. Patient pays TSh 30,000 (30%) → Dispense button LOCKED
3. Patient pays additional TSh 25,000 (total 55%) → Dispense button UNLOCKED
4. Pharmacist can now dispense
5. Patient owes balance: TSh 45,000 (to be collected later)
```

---

## Visual Design

### Color Coding
- **Gray/Secondary**: Payment pending, locked state
- **Green/Success**: Payment verified, can proceed
- **Red/Danger**: Emergency override, urgent care
- **Yellow/Warning**: Action required, payment needed

### Icons
- 🔒 `bi-lock-fill`: Locked/Payment required
- ✓ `bi-check-circle-fill`: Verified/Approved
- ⚠️ `bi-exclamation-triangle-fill`: Emergency/Warning
- 👁️ `bi-eye`: View details

### Interactive Elements
- **Tooltips**: Hover over badges for detailed status
- **Disabled states**: Grayed out, cursor not-allowed
- **Modal confirmations**: Emergency override requires explicit confirmation

---

## Technical Implementation

### Frontend (Blade Templates)

#### Payment Status Check
```php
@php
    $hasPaymentVerification = $prescription->hasPaymentVerification();
    $paymentPercentage = 0;
    if ($prescription->bill) {
        $paymentPercentage = ($prescription->bill->paid_amount / $prescription->bill->total_amount) * 100;
    }
@endphp
```

#### Conditional Button Rendering
```php
@if($hasPaymentVerification)
    <button type="submit" class="btn btn-success">
        <i class="bi bi-check-circle me-2"></i>Dispense Prescription
    </button>
@else
    <button type="button" class="btn btn-secondary" disabled 
            data-bs-toggle="tooltip" 
            title="Payment verification required before dispensing">
        <i class="bi bi-lock me-2"></i>Dispense Prescription (Locked)
    </button>
@endif
```

#### Dashboard Payment Badges
```php
@if($prescription->is_emergency)
    <span class="badge bg-danger">
        <i class="bi bi-exclamation-triangle-fill"></i> Emergency
    </span>
@elseif($prescription->hasPaymentVerification())
    <span class="badge bg-success">
        <i class="bi bi-check-circle-fill"></i> Verified
    </span>
@else
    <span class="badge bg-secondary">
        <i class="bi bi-lock-fill"></i> Pending
    </span>
@endif
```

### Backend (PharmacyController)

#### Load Relationships
```php
public function dashboard()
{
    $pendingPrescriptions = Prescription::with([
        'patient', 'doctor', 'items', 'bill' // Include bill for payment check
    ])->where('status', '!=', 'dispensed')->get();
    
    return view('pharmacy.dashboard', compact('pendingPrescriptions'));
}

public function show($id)
{
    $prescription = Prescription::with([
        'patient', 'doctor', 'items', 'bill', 'emergencyApprovedBy'
    ])->findOrFail($id);
    
    return view('pharmacy.show', compact('prescription'));
}
```

#### Dispense with Verification
```php
public function dispense(Request $request, $id)
{
    $prescription = Prescription::findOrFail($id);
    
    // Check payment verification
    if (!$prescription->hasPaymentVerification()) {
        return back()->with('error', 'Cannot dispense: Payment verification required...');
    }
    
    // Dispense medication...
}
```

---

## Benefits

### ✅ Revenue Protection
- Visual lock prevents accidental dispensing without payment
- Clear payment status at a glance
- Pharmacist cannot bypass (only doctor/admin for emergencies)

### ✅ User-Friendly
- Color-coded badges for quick status recognition
- Helpful tooltips explain why button is locked
- Clear instructions on next steps (go to billing)

### ✅ Medical Care Priority
- Emergency override visible and accessible to doctors
- Red alert emphasizes urgency
- Emergency justification displayed for transparency

### ✅ Audit Compliance
- All emergency overrides logged with approver name
- Payment status clearly documented
- Visual indicators match backend business logic

---

## Summary

The pharmacy UI now provides **clear visual controls** to enforce payment verification:

1. **Dashboard**: Payment status column with color-coded badges
2. **Detail View**: Prominent payment alerts with current status
3. **Dispense Button**: Locked until payment verified (or emergency)
4. **Emergency Override**: Modal for doctors to bypass (with justification)
5. **Tooltips**: Contextual help on hover

Pharmacists can **immediately see** which prescriptions are ready to dispense (green verified) and which require payment (gray locked) or are emergencies (red emergency).
