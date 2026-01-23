<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\PrescriptionItem;
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
        $pendingPrescriptions = Prescription::with(['patient', 'doctor', 'consultation.appointment', 'items'])
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
        $prescription = Prescription::with(['patient', 'doctor', 'consultation.appointment', 'items', 'dispensedByUser'])
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
            
            // Update prescription status
            $prescription->status = 'dispensed';
            $prescription->dispensed_by = Auth::id();
            $prescription->dispensed_at = now();
            $prescription->pharmacy_notes = $request->pharmacy_notes;
            $prescription->save();
            
            DB::commit();
            
            return redirect()
                ->route('pharmacy.dashboard')
                ->with('success', 'Prescription dispensed successfully. Prescription #' . $prescription->prescription_number);
                
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
}
