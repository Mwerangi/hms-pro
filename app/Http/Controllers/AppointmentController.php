<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\PatientCharge;
use App\Models\Service;
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
        $user = $request->user();

        // If user is a doctor, apply doctor-specific filtering logic
        if ($user->hasRole('Doctor')) {
            // Show only appointments assigned to this doctor OR unassigned appointments
            $query->where(function($q) use ($user) {
                $q->where('doctor_id', $user->id) // Assigned to this doctor
                  ->orWhereNull('doctor_id'); // OR unassigned (available to all doctors)
            });
        }

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

        // Doctor filter (for admin/receptionist views)
        if ($request->filled('doctor_id') && !$user->hasRole('Doctor')) {
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

        // Statistics - adjusted for doctor role
        $statsQuery = Appointment::whereDate('appointment_date', today());
        if ($user->hasRole('Doctor')) {
            $statsQuery->where(function($q) use ($user) {
                $q->where('doctor_id', $user->id)->orWhereNull('doctor_id');
            });
        }
        
        $stats = [
            'today_total' => (clone $statsQuery)->count(),
            'waiting' => (clone $statsQuery)->where('status', 'waiting')->count(),
            'in_consultation' => (clone $statsQuery)->where('status', 'in-consultation')->count(),
            'completed' => (clone $statsQuery)->where('status', 'completed')->count(),
            'cancelled' => (clone $statsQuery)->where('status', 'cancelled')->count(),
            'scheduled' => (clone $statsQuery)->where('status', 'scheduled')->count(),
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

        // Auto-add consultation charge from service catalog
        $this->addConsultationCharge($appointment);

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment booked successfully! Token Number: ' . $appointment->token_number);
    }

    /**
     * Display the specified appointment
     */
    public function show(Appointment $appointment)
    {
        $user = auth()->user();
        
        // If user is a doctor, ensure they can only view appointments assigned to them or unassigned
        if ($user->hasRole('Doctor') && $appointment->doctor_id && $appointment->doctor_id !== $user->id) {
            abort(403, 'You are not authorized to view this appointment.');
        }

        $appointment->load(['patient', 'doctor', 'consultation']);
        
        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified appointment
     */
    public function edit(Appointment $appointment)
    {
        $user = auth()->user();
        
        // If user is a doctor, ensure they can only edit appointments assigned to them or unassigned
        if ($user->hasRole('Doctor') && $appointment->doctor_id && $appointment->doctor_id !== $user->id) {
            abort(403, 'You are not authorized to edit this appointment.');
        }

        if (!$appointment->canBeEdited()) {
            return back()->with('error', 'This appointment is locked and cannot be edited. Please contact the accounting department to reopen it.');
        }

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
        $user = auth()->user();
        
        // If user is a doctor, ensure they can only update appointments assigned to them or unassigned
        if ($user->hasRole('Doctor') && $appointment->doctor_id && $appointment->doctor_id !== $user->id) {
            abort(403, 'You are not authorized to update this appointment.');
        }

        if (!$appointment->canBeEdited()) {
            return back()->with('error', 'This appointment is locked and cannot be updated. Please contact the accounting department to reopen it.');
        }

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

        $user = auth()->user();

        // If appointment is unassigned, assign it to the current doctor
        if (!$appointment->doctor_id && $user->hasRole('Doctor')) {
            $appointment->update([
                'doctor_id' => $user->id,
                'doctor_assigned_at' => now(),
                'assigned_by' => $user->id,
            ]);
        }

        // Verify the doctor is authorized to start this consultation
        if ($user->hasRole('Doctor') && $appointment->doctor_id !== $user->id) {
            return back()->with('error', 'You are not authorized to start this consultation. This appointment is assigned to another doctor.');
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
     * Reopen a locked appointment (accountant only)
     */
    public function reopen(Request $request, Appointment $appointment)
    {
        // Only allow accountants and admins to reopen locked appointments
        if (!in_array(auth()->user()->role, ['accountant', 'admin'])) {
            return back()->with('error', 'Only accountants and administrators can reopen locked appointments.');
        }

        if (!$appointment->is_locked) {
            return back()->with('error', 'This appointment is not locked.');
        }

        $request->validate([
            'reopen_reason' => 'required|string|max:500',
        ]);

        $appointment->reopen(auth()->id(), $request->reopen_reason);

        return back()->with('success', 'Appointment file reopened successfully. Reason: ' . $request->reopen_reason);
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

        // Show appointments assigned to this doctor OR unassigned appointments
        $appointments = Appointment::with(['patient', 'consultation'])
            ->where(function($q) use ($doctorId) {
                $q->where('doctor_id', $doctorId) // Assigned to this doctor
                  ->orWhereNull('doctor_id'); // OR unassigned
            })
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

    /**
     * Auto-add consultation charge when appointment is created
     */
    protected function addConsultationCharge(Appointment $appointment)
    {
        // Find consultation service from catalog based on appointment type
        // You can customize this logic based on doctor specialty, appointment type, etc.
        $serviceCode = 'CONS-001'; // Default: General Practitioner Consultation
        
        // If you want different consultation charges based on doctor specialty:
        // $serviceCode = match($appointment->doctor->specialty ?? null) {
        //     'Pediatrician' => 'CONS-003',
        //     'Gynecologist' => 'CONS-004',
        //     default => 'CONS-002', // Specialist Consultation
        // };
        
        $service = Service::where('service_code', $serviceCode)
            ->where('is_active', true)
            ->first();
        
        if ($service) {
            PatientCharge::createFromService(
                $service,
                $appointment->patient,
                Appointment::class,
                $appointment->id
            );
        }
    }
}

