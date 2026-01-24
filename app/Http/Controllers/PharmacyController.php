<?php

namespace App\Http\Controllers;

use App\Models\PatientCharge;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PharmacyController extends Controller
{
    /**
     * Display the pharmacy dashboard
     */
    public function dashboard(Request $request)
    {
        // Get filter date (default to today)
        $filterDate = $request->get('date', now()->toDateString());
        
        // Get pending prescriptions (not dispensed)
        $pendingPrescriptions = Prescription::with(['patient', 'doctor', 'consultation.appointment', 'items', 'bill'])
            ->where('status', '!=', 'dispensed')
            ->where('status', '!=', 'cancelled')
            ->whereDate('prescription_date', $filterDate)
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Get dispensed prescriptions for the day
        $dispensedPrescriptions = Prescription::with(['patient', 'doctor', 'dispensedByUser', 'items'])
            ->where('status', 'dispensed')
            ->whereDate('dispensed_at', $filterDate)
            ->orderBy('dispensed_at', 'desc')
            ->get();
        
        // Get statistics
        $stats = [
            'pending_today' => Prescription::where('status', '!=', 'dispensed')
                ->where('status', '!=', 'cancelled')
                ->whereDate('prescription_date', now()->toDateString())
                ->count(),
            'dispensed_today' => Prescription::where('status', 'dispensed')
                ->whereDate('dispensed_at', now()->toDateString())
                ->count(),
            'total_prescriptions' => Prescription::count(),
        ];
        
        return view('pharmacy.dashboard', compact('pendingPrescriptions', 'dispensedPrescriptions', 'stats', 'filterDate'));
    }
    
    /**
     * Show prescription details for dispensing
     */
    public function show($id)
    {
        $prescription = Prescription::with(['patient', 'doctor', 'consultation.appointment', 'items', 'dispensedByUser', 'bill', 'emergencyApprovedBy'])
            ->findOrFail($id);
        
        return view('pharmacy.show', compact('prescription'));
    }
    
    /**
     * Dispense a prescription
     */
    public function dispense(Request $request, $id)
    {
        $request->validate([
            'pharmacy_notes' => 'nullable|string|max:1000',
        ]);
        
        try {
            DB::beginTransaction();
            
            $prescription = Prescription::findOrFail($id);
            
            // Check payment verification before dispensing
            if (!$prescription->hasPaymentVerification()) {
                DB::rollBack();
                return redirect()
                    ->back()
                    ->with('error', 'Cannot dispense medication: Payment verification required. Patient must pay at least 50% of the bill or prescription must be marked as emergency.');
            }
            
            // Update prescription status
            $prescription->status = 'dispensed';
            $prescription->dispensed_by = Auth::id();
            $prescription->dispensed_at = now();
            $prescription->pharmacy_notes = $request->pharmacy_notes;
            $prescription->save();

            // Note: Charges already added when prescription was created
            // No need to add charges again during dispensing
            
            DB::commit();
            
            $message = 'Prescription dispensed successfully. Prescription #' . $prescription->prescription_number;
            if ($prescription->is_emergency) {
                $message .= ' (Emergency Override Applied)';
            }
            
            return redirect()
                ->route('pharmacy.dashboard')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Failed to dispense prescription: ' . $e->getMessage());
        }
    }
    
    /**
     * Cancel a prescription
     */
    public function cancel(Request $request, $id)
    {
        $request->validate([
            'cancellation_reason' => 'required|string|max:500',
        ]);
        
        try {
            $prescription = Prescription::findOrFail($id);
            
            $prescription->status = 'cancelled';
            $prescription->pharmacy_notes = 'CANCELLED: ' . $request->cancellation_reason;
            $prescription->save();
            
            return redirect()
                ->route('pharmacy.dashboard')
                ->with('success', 'Prescription cancelled successfully.');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to cancel prescription: ' . $e->getMessage());
        }
    }

    /**
     * Mark prescription as emergency to bypass payment verification
     */
    public function markEmergency(Request $request, $id)
    {
        $request->validate([
            'emergency_reason' => 'required|string|max:500',
        ]);
        
        try {
            $prescription = Prescription::findOrFail($id);
            
            // Only allow doctors and admins to mark as emergency
            if (!in_array(auth()->user()->role, ['doctor', 'admin'])) {
                return redirect()
                    ->back()
                    ->with('error', 'Only doctors and administrators can mark prescriptions as emergency.');
            }
            
            $prescription->markAsEmergency(auth()->id(), $request->emergency_reason);
            
            return redirect()
                ->back()
                ->with('success', 'Prescription marked as emergency. Payment verification bypassed.');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to mark prescription as emergency: ' . $e->getMessage());
        }
    }

}

// Note: addPharmacyCharges() method removed
// Charges are now added when prescription is created in ConsultationController
// This ensures medication costs are included in the bill BEFORE payment

