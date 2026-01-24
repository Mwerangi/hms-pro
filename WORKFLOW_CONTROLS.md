# Hospital Workflow Control System

## Overview
Flexible workflow control system with safeguards to manage patient file lifecycle from appointment creation to medication dispensing. Ensures revenue collection while allowing emergency medical care.

---

## File Lifecycle Workflow

### 1. **Appointment Creation** (Receptionist)
- Patient walks in or books appointment
- **Auto-charge**: GP Consultation (TSh 30,000) added to pending charges
- **Status**: `scheduled` → `waiting` → `in-consultation` → `completed`

### 2. **Doctor Consultation** (Doctor)
- Records diagnosis, prescriptions, lab orders
- Orders tests (auto-charges added to pending charges)
- Completes consultation
- **Status**: Appointment changes to `completed`
- **Ready for billing**: `isReadyForBilling()` returns true

### 3. **Bill Generation** (Accountant)
- Views patients with pending charges in accounting dashboard
- One-click bill generation from accumulated charges
- **Action**: `Bill::generateFromCharges()`
- **Result**: 
  - All pending charges converted to bill items
  - Appointment locked (`is_locked = true`)
  - Bill linked to appointment (`bill_id` set)
  - **Cannot edit appointment after this point**

### 4. **Payment Collection** (Accountant)
- Patient pays bill (full or partial)
- **Minimum threshold**: 50% of total bill
- **Action**: `Bill::addPayment()`
- **Result**:
  - If ≥50% paid: Prescriptions automatically verified
  - `Prescription::payment_verified = true`
  - Pharmacy can now dispense medications

### 5. **Medication Dispensing** (Pharmacy)
- Pharmacy checks prescription
- **Verification**: `Prescription::hasPaymentVerification()`
  - Returns TRUE if:
    - ✅ Bill paid ≥50% OR
    - ✅ Marked as emergency (`is_emergency = true`)
  - Returns FALSE if payment not verified
- **Action**: Dispense only if verification passes
- **Auto-charges**: Dispensing fee + medication costs added to pending charges

---

## Workflow Controls

### A. Appointment Locking
**Purpose**: Prevent edits after billing to maintain audit integrity

**When locked**:
- ✅ Bill generated from pending charges
- ✅ `appointment.is_locked = true`
- ✅ `appointment.bill_id` links to bill
- ✅ `appointment.locked_by` = accountant user ID
- ✅ `appointment.locked_at` = timestamp

**Restrictions**:
- ❌ Cannot edit appointment details
- ❌ Cannot reschedule
- ❌ Cannot change doctor
- ✅ View-only access for non-accountants

**Reopen capability** (Accountant/Admin only):
```php
// Accountant can reopen locked file with reason
$appointment->reopen(userId: auth()->id(), reason: "Incorrect diagnosis code");
```

**Audit trail**:
- `reopened_by` = accountant user ID
- `reopened_at` = timestamp
- `reopen_reason` = explanation text
- `is_locked` = false (file reopened)

### B. Payment Verification
**Purpose**: Ensure revenue collection before medication dispensing

**50% Payment Threshold**:
- Minimum payment required to dispense medications
- Flexible approach: Not full payment, but substantial down payment
- Prevents full revenue loss while allowing patient care

**Verification logic**:
```php
public function hasPaymentVerification(): bool
{
    // Emergency override (no payment needed)
    if ($this->is_emergency) {
        return true;
    }
    
    // Check if bill exists and is at least 50% paid
    if ($this->bill) {
        $paymentPercentage = ($this->bill->paid_amount / $this->bill->total_amount) * 100;
        return $paymentPercentage >= 50;
    }
    
    return false;
}
```

**Auto-verification**:
- When patient pays ≥50% of bill
- System automatically verifies all pending prescriptions
- Pharmacy notified: "Payment verified. Can dispense."

### C. Emergency Override
**Purpose**: Allow urgent medication dispensing without payment

**Who can mark as emergency**:
- ✅ Doctors
- ✅ Administrators
- ❌ Pharmacy staff (cannot bypass on their own)

**Process**:
```php
// Doctor marks prescription as emergency
$prescription->markAsEmergency(
    userId: auth()->id(),
    reason: "Severe anaphylactic reaction - life-threatening"
);
```

**Audit trail**:
- `is_emergency` = true
- `emergency_approved_by` = doctor/admin user ID
- `emergency_reason` = medical justification
- Payment verification bypassed
- Pharmacy can dispense immediately

---

## Controller Enforcement

### 1. AppointmentController
**Edit/Update prevention**:
```php
public function edit(Appointment $appointment)
{
    if (!$appointment->canBeEdited()) {
        return back()->with('error', 'This appointment is locked. Contact accounting to reopen.');
    }
    // ... allow editing
}
```

**Reopen locked file** (Accountant only):
```php
public function reopen(Request $request, Appointment $appointment)
{
    // Only accountants and admins
    if (!in_array(auth()->user()->role, ['accountant', 'admin'])) {
        return back()->with('error', 'Only accountants can reopen locked appointments.');
    }
    
    $appointment->reopen(auth()->id(), $request->reopen_reason);
    return back()->with('success', 'File reopened successfully.');
}
```

### 2. BillController
**Auto-lock appointment when bill generated**:
```php
public function generateFromCharges(Patient $patient)
{
    // Create bill from pending charges
    $bill = Bill::create([...]);
    
    // Lock associated appointment
    $appointment = /* find appointment from charges */;
    $appointment->lockAfterBilling($bill, auth()->id());
    
    return redirect()->route('bills.show', $bill);
}
```

**Auto-verify prescriptions when payment ≥50%**:
```php
public function addPayment(Request $request, Bill $bill)
{
    // Add payment
    $bill->addPayment(...);
    
    // Check if 50% threshold reached
    if (($bill->paid_amount / $bill->total_amount) >= 0.50) {
        // Verify all pending prescriptions
        $prescriptions = Prescription::where('patient_id', $bill->patient_id)
            ->where('payment_verified', false)
            ->where('is_emergency', false)
            ->get();
        
        foreach ($prescriptions as $prescription) {
            $prescription->verifyPayment($bill);
        }
    }
}
```

### 3. PharmacyController
**Payment verification before dispensing**:
```php
public function dispense(Request $request, Prescription $prescription)
{
    // Check payment verification
    if (!$prescription->hasPaymentVerification()) {
        return back()->with('error', 'Cannot dispense: Payment verification required. Patient must pay at least 50% or prescription must be marked as emergency.');
    }
    
    // Dispense medication
    $prescription->status = 'dispensed';
    $prescription->save();
    
    // Add pharmacy charges
    $this->addPharmacyCharges($prescription);
}
```

**Emergency override** (Doctor/Admin only):
```php
public function markEmergency(Request $request, Prescription $prescription)
{
    // Only doctors and admins
    if (!in_array(auth()->user()->role, ['doctor', 'admin'])) {
        return back()->with('error', 'Only doctors can mark as emergency.');
    }
    
    $prescription->markAsEmergency(auth()->id(), $request->emergency_reason);
    return back()->with('success', 'Prescription marked as emergency. Payment bypassed.');
}
```

---

## Routes

### Appointment Management
```php
// Reopen locked appointment (accountant only)
POST /appointments/{appointment}/reopen
```

### Pharmacy Management
```php
// Mark prescription as emergency (doctor/admin only)
POST /pharmacy/prescriptions/{prescription}/mark-emergency
```

---

## Database Schema

### Appointments Table
```sql
is_billed BOOLEAN DEFAULT FALSE
bill_id BIGINT UNSIGNED NULL (FK to bills)
is_locked BOOLEAN DEFAULT FALSE
locked_by BIGINT UNSIGNED NULL (FK to users)
locked_at TIMESTAMP NULL
reopened_by BIGINT UNSIGNED NULL (FK to users)
reopened_at TIMESTAMP NULL
reopen_reason TEXT NULL
```

### Prescriptions Table
```sql
payment_verified BOOLEAN DEFAULT FALSE
bill_id BIGINT UNSIGNED NULL (FK to bills)
is_emergency BOOLEAN DEFAULT FALSE
emergency_approved_by BIGINT UNSIGNED NULL (FK to users)
emergency_reason TEXT NULL
```

---

## Benefits

### ✅ Revenue Protection
- No medication dispensing without payment (≥50% threshold)
- File locking prevents post-billing changes
- Audit trail tracks all workflow actions

### ✅ Medical Care Priority
- Emergency override for urgent cases
- 50% payment threshold (not 100%) allows patient access
- Doctors can approve emergency dispensing

### ✅ Accountability
- All workflow changes logged with user ID and timestamp
- Reopen reasons tracked
- Emergency approvals documented

### ✅ Flexibility
- Accountants can reopen files with justification
- Partial payment accepted (50% minimum)
- Emergency override for life-threatening situations

---

## Usage Examples

### Example 1: Normal Patient Flow
```
1. Patient arrives → Receptionist creates appointment
   ↳ Auto-charge: GP Consultation (TSh 30,000)

2. Doctor examines patient → Orders CBC test + Prescribes medications
   ↳ Auto-charge: CBC (TSh 15,000)

3. Accountant generates bill → Total: TSh 45,000
   ↳ Appointment locked (cannot edit)

4. Patient pays TSh 25,000 (55% of total)
   ↳ Prescription payment verified

5. Pharmacy dispenses medications
   ↳ Auto-charge: Dispensing fee (TSh 5,000) + Medication costs
```

### Example 2: Emergency Case
```
1. Emergency patient → Severe allergic reaction
2. Doctor prescribes emergency medications
   ↳ Doctor marks prescription as EMERGENCY
   ↳ Reason: "Anaphylactic shock - life-threatening"
3. Pharmacy dispenses immediately (no payment needed)
4. Patient pays later (bill generated after stabilization)
```

### Example 3: Error Correction
```
1. Accountant generates bill → Appointment locked
2. Discovers incorrect diagnosis code
3. Accountant reopens file
   ↳ Reason: "Incorrect ICD-10 code - need to update"
4. Doctor updates diagnosis
5. Accountant generates corrected bill
```

---

## Summary

This flexible workflow system balances **revenue protection** with **patient care**:

- **Strict controls**: Locked files, payment verification, role-based access
- **Emergency exceptions**: Override for urgent medical needs
- **Partial payments**: 50% threshold (not 100%) allows patient access
- **Audit compliance**: Complete tracking of all workflow actions
- **Error correction**: Accountant reopen capability with justification

The system ensures financial sustainability while prioritizing patient safety and medical ethics.
