<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NursingStationController extends Controller
{
    /**
     * Display nursing station dashboard
     */
    public function dashboard(Request $request)
    {
        $date = $request->filled('date') ? Carbon::parse($request->date) : today();
        
        // Get appointments for today that are waiting (checked in but vitals not recorded)
        $pendingVitals = Appointment::with(['patient', 'doctor'])
            ->whereDate('appointment_date', $date)
            ->where('status', 'waiting')
            ->whereNull('vitals_recorded_at')
            ->orderBy('checked_in_at', 'asc')
            ->get();
        
        // Get appointments with vitals recorded today
        $completedVitals = Appointment::with(['patient', 'doctor', 'vitalsRecordedBy'])
            ->whereDate('appointment_date', $date)
            ->whereNotNull('vitals_recorded_at')
            ->orderBy('vitals_recorded_at', 'desc')
            ->get();
        
        $stats = [
            'total_today' => Appointment::whereDate('appointment_date', $date)->count(),
            'pending_vitals' => $pendingVitals->count(),
            'completed_vitals' => $completedVitals->count(),
            'ready_for_doctor' => Appointment::whereDate('appointment_date', $date)
                ->whereNotNull('vitals_recorded_at')
                ->whereIn('status', ['waiting', 'in-consultation'])
                ->count(),
        ];
        
        return view('nursing.dashboard', compact('pendingVitals', 'completedVitals', 'stats', 'date'));
    }
    
    /**
     * Record patient vitals
     */
    public function recordVitals(Request $request, Appointment $appointment)
    {
        // Sanitize vital signs - remove units and extra characters
        $input = $request->all();
        
        // Clean temperature (remove °, F, C, etc.)
        if (!empty($input['temperature'])) {
            $input['temperature'] = preg_replace('/[^0-9.]/', '', $input['temperature']);
        }
        
        // Clean blood pressure (keep only numbers and /)
        if (!empty($input['blood_pressure'])) {
            $input['blood_pressure'] = preg_replace('/[^0-9\/]/', '', $input['blood_pressure']);
        }
        
        // Clean pulse rate (remove bpm, etc.)
        if (!empty($input['pulse_rate'])) {
            $input['pulse_rate'] = preg_replace('/[^0-9]/', '', $input['pulse_rate']);
        }
        
        // Clean respiratory rate
        if (!empty($input['respiratory_rate'])) {
            $input['respiratory_rate'] = preg_replace('/[^0-9]/', '', $input['respiratory_rate']);
        }
        
        // Clean SpO2 (remove %)
        if (!empty($input['spo2'])) {
            $input['spo2'] = preg_replace('/[^0-9]/', '', $input['spo2']);
        }
        
        // Clean weight (remove kg, lbs, etc.)
        if (!empty($input['weight'])) {
            $input['weight'] = preg_replace('/[^0-9.]/', '', $input['weight']);
        }
        
        // Clean height (remove cm, ft, in, etc.)
        if (!empty($input['height'])) {
            $input['height'] = preg_replace('/[^0-9.]/', '', $input['height']);
        }
        
        // Replace request data with sanitized input
        $request->merge($input);
        
        $validated = $request->validate([
            'blood_pressure' => 'required|string|max:20',
            'pulse_rate' => 'required|string|max:20',
            'temperature' => 'required|string|max:20',
            'respiratory_rate' => 'nullable|string|max:20',
            'spo2' => 'nullable|string|max:20',
            'weight' => 'nullable|string|max:20',
            'height' => 'nullable|string|max:20',
            'doctor_id' => 'nullable|exists:users,id', // Allow reassigning doctor
        ]);
        
        $validated['vitals_recorded_at'] = now();
        $validated['vitals_recorded_by'] = auth()->id();
        
        // If doctor is being assigned/changed by nurse
        if (isset($validated['doctor_id']) && $validated['doctor_id'] != $appointment->doctor_id) {
            $validated['assigned_by'] = auth()->id();
            $validated['doctor_assigned_at'] = now();
        }
        
        $appointment->update($validated);
        
        return redirect()->route('nursing.dashboard')->with('success', 'Vitals recorded successfully for ' . $appointment->patient->full_name . '! Patient ready for doctor.');
    }
    
    /**
     * Show vitals recording form
     */
    public function showVitalsForm(Appointment $appointment)
    {
        $appointment->load(['patient', 'doctor']);
        return view('nursing.record-vitals', compact('appointment'));
    }
}
