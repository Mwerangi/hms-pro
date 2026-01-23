<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Display a listing of appointments
     */
    public function index(Request $request)
    {
        $query = Appointment::with(['patient', 'doctor']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('appointment_number', 'like', "%{$search}%")
                  ->orWhere('token_number', 'like', "%{$search}%")
                  ->orWhereHas('patient', function($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        // Date filter
        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        } else {
            // Default to today's appointments
            $query->whereDate('appointment_date', today());
        }

        // Doctor filter
        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('appointment_type', $request->type);
        }

        $appointments = $query->orderBy('appointment_time', 'asc')->paginate(20);

        // Get doctors for filter dropdown
        $doctors = User::role('Doctor')->get();

        // Statistics
        $stats = [
            'today_total' => Appointment::whereDate('appointment_date', today())->count(),
            'waiting' => Appointment::whereDate('appointment_date', today())->where('status', 'waiting')->count(),
            'in_consultation' => Appointment::whereDate('appointment_date', today())->where('status', 'in-consultation')->count(),
            'completed' => Appointment::whereDate('appointment_date', today())->where('status', 'completed')->count(),
            'cancelled' => Appointment::whereDate('appointment_date', today())->where('status', 'cancelled')->count(),
            'scheduled' => Appointment::whereDate('appointment_date', today())->where('status', 'scheduled')->count(),
        ];

        return view('appointments.index', compact('appointments', 'doctors', 'stats'));
    }

    /**
     * Show the form for creating a new appointment
     */
    public function create()
    {
        // Get only patients who don't have active appointments
        // Active appointments are: scheduled, waiting, in-consultation
        $patients = Patient::where('is_active', true)
            ->whereDoesntHave('appointments', function($query) {
                $query->whereIn('status', ['scheduled', 'waiting', 'in-consultation']);
            })
            ->get();
        
        $doctors = User::role('Doctor')->get();
        
        return view('appointments.create', compact('patients', 'doctors'));
    }

    /**
     * Store a newly created appointment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'appointment_type' => 'required|in:new,followup,emergency',
            'reason_for_visit' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Check if patient already has an active appointment
        $existingAppointment = Appointment::where('patient_id', $validated['patient_id'])
            ->whereIn('status', ['scheduled', 'waiting', 'in-consultation'])
            ->first();

        if ($existingAppointment) {
            return back()->withInput()->withErrors([
                'patient_id' => 'This patient already has an active appointment (Token: ' . $existingAppointment->token_number . '). Please complete or cancel the existing appointment first.'
            ]);
        }

        // Combine date and time
        $validated['appointment_time'] = Carbon::parse($validated['appointment_date'] . ' ' . $request->appointment_time);

        // Check for time slot conflicts
        $conflict = Appointment::where('doctor_id', $validated['doctor_id'])
            ->whereDate('appointment_date', $validated['appointment_date'])
            ->where('appointment_time', $validated['appointment_time'])
            ->whereNotIn('status', ['cancelled', 'completed'])
            ->exists();

        if ($conflict) {
            return back()->withInput()->withErrors(['appointment_time' => 'This time slot is already booked for the selected doctor.']);
        }

        $appointment = Appointment::create($validated);

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment booked successfully! Token Number: ' . $appointment->token_number);
    }

    /**
     * Display the specified appointment
     */
    public function show(Appointment $appointment)
    {
        $appointment->load(['patient', 'doctor', 'consultation']);
        
        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified appointment
     */
    public function edit(Appointment $appointment)
    {
        if (!$appointment->canBeRescheduled()) {
            return back()->with('error', 'This appointment cannot be rescheduled.');
        }

        $patients = Patient::where('is_active', true)->get();
        $doctors = User::role('Doctor')->get();
        
        return view('appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    /**
     * Update the specified appointment
     */
    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'appointment_type' => 'required|in:new,followup,emergency',
            'reason_for_visit' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Combine date and time
        $validated['appointment_time'] = Carbon::parse($validated['appointment_date'] . ' ' . $request->appointment_time);

        // Check for time slot conflicts (excluding current appointment)
        $conflict = Appointment::where('doctor_id', $validated['doctor_id'])
            ->whereDate('appointment_date', $validated['appointment_date'])
            ->where('appointment_time', $validated['appointment_time'])
            ->where('id', '!=', $appointment->id)
            ->whereNotIn('status', ['cancelled', 'completed'])
            ->exists();

        if ($conflict) {
            return back()->withInput()->withErrors(['appointment_time' => 'This time slot is already booked for the selected doctor.']);
        }

        // Reset status to scheduled if rescheduling a cancelled appointment
        if ($appointment->status === 'cancelled') {
            $validated['status'] = 'scheduled';
            $validated['cancellation_reason'] = null;
            $validated['cancelled_at'] = null;
        }

        $appointment->update($validated);

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment updated successfully!');
    }

    /**
     * Remove the specified appointment
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment deleted successfully!');
    }

    /**
     * Check in patient (mark as waiting)
     */
    public function checkIn(Appointment $appointment)
    {
        if ($appointment->status !== 'scheduled') {
            return back()->with('error', 'Only scheduled appointments can be checked in.');
        }

        $appointment->markAsWaiting();

        return back()->with('success', 'Patient checked in successfully!');
    }

    /**
     * Start consultation
     */
    public function startConsultation(Appointment $appointment)
    {
        if (!in_array($appointment->status, ['scheduled', 'waiting'])) {
            return back()->with('error', 'Cannot start consultation for this appointment.');
        }

        $appointment->startConsultation();

        return back()->with('success', 'Consultation started!');
    }

    /**
     * Complete consultation
     */
    public function completeConsultation(Appointment $appointment)
    {
        if ($appointment->status !== 'in-consultation') {
            return back()->with('error', 'Only active consultations can be completed.');
        }

        $appointment->completeConsultation();

        return back()->with('success', 'Consultation completed!');
    }

    /**
     * Cancel appointment
     */
    public function cancel(Request $request, Appointment $appointment)
    {
        if (!$appointment->canBeCancelled()) {
            return back()->with('error', 'This appointment cannot be cancelled.');
        }

        $request->validate([
            'cancellation_reason' => 'required|string|max:500',
        ]);

        $appointment->cancel($request->cancellation_reason);

        return back()->with('success', 'Appointment cancelled successfully!');
    }

    /**
     * Create walk-in appointment
     */
    public function createWalkIn(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'chief_complaint' => 'required|string',
            'is_emergency' => 'required|boolean',
            'doctor_id' => 'nullable|exists:users,id',
        ]);

        // Check if patient already has an active appointment
        $existingAppointment = Appointment::where('patient_id', $validated['patient_id'])
            ->whereIn('status', ['scheduled', 'waiting', 'in-consultation'])
            ->first();

        if ($existingAppointment) {
            return back()->withErrors([
                'patient_id' => 'This patient already has an active appointment (Token: ' . $existingAppointment->token_number . '). Please complete or cancel the existing appointment first.'
            ]);
        }

        $appointment = Appointment::createWalkIn(
            $validated['patient_id'],
            $validated['chief_complaint'],
            $validated['is_emergency'],
            $validated['doctor_id'] ?? null
        );

        $patient = \App\Models\Patient::find($validated['patient_id']);
        $type = $validated['is_emergency'] ? 'Emergency' : 'Walk-in';

        return redirect()->route('nursing.dashboard')
            ->with('success', "{$type} registration successful! {$patient->full_name} (Token: {$appointment->token_number}) sent to nursing station.");
    }

    /**
     * Doctor's appointment dashboard
     */
    public function doctorDashboard(Request $request)
    {
        $doctorId = $request->user()->id;
        $date = $request->filled('date') ? Carbon::parse($request->date) : today();

        $appointments = Appointment::with(['patient', 'consultation'])
            ->where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $date)
            ->orderBy('priority_order', 'asc') // Emergency (1) → Scheduled (2) → Walk-in (3)
            ->orderBy('checked_in_at', 'asc') // FIFO within same priority
            ->get();

        $stats = [
            'total' => $appointments->count(),
            'scheduled' => $appointments->where('status', 'scheduled')->count(),
            'waiting' => $appointments->where('status', 'waiting')->count(),
            'in_consultation' => $appointments->where('status', 'in-consultation')->count(),
            'completed' => $appointments->where('status', 'completed')->count(),
        ];

        // Get consultations with pending or newly reported lab results (unviewed)
        $consultationsWithPendingResults = \App\Models\Consultation::with(['patient', 'labOrders'])
            ->where('doctor_id', $doctorId)
            ->whereHas('labOrders', function($query) {
                $query->where(function($q) {
                    // Pending or in-progress tests
                    $q->whereIn('status', ['pending', 'sample-collected', 'in-progress', 'completed'])
                    // OR reported but not yet viewed
                    ->orWhere(function($subQ) {
                        $subQ->where('status', 'reported')
                             ->whereNull('viewed_at');
                    });
                });
            })
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('appointments.doctor-dashboard', compact('appointments', 'stats', 'date', 'consultationsWithPendingResults'));
    }
}
