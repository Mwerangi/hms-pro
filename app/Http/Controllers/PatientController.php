<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PatientController extends Controller
{
    /**
     * Display a listing of patients
     */
    public function index(Request $request)
    {
        $query = Patient::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('patient_id', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Gender filter
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // Blood group filter
        if ($request->filled('blood_group')) {
            $query->where('blood_group', $request->blood_group);
        }

        // Status filter
        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $patients = $query->latest()->paginate(15);

        // Statistics
        $stats = [
            'total' => Patient::count(),
            'active' => Patient::where('is_active', true)->count(),
            'male' => Patient::where('gender', 'male')->count(),
            'female' => Patient::where('gender', 'female')->count(),
            'new_today' => Patient::whereDate('created_at', today())->count(),
            'new_this_week' => Patient::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];

        return view('patients.index', compact('patients', 'stats'));
    }

    /**
     * Show the form for creating a new patient
     */
    public function create()
    {
        return view('patients.create');
    }

    /**
     * Store a newly created patient
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'blood_group' => 'nullable|string|max:10',
            'marital_status' => 'nullable|string|max:20',
            'phone' => 'required|string|max:20|unique:patients,phone',
            'email' => 'nullable|email|max:100|unique:patients,email',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'required|string|max:100',
            'emergency_contact_name' => 'required|string|max:100',
            'emergency_contact_phone' => 'required|string|max:20',
            'emergency_contact_relationship' => 'required|string|max:50',
            'allergies' => 'nullable|string',
            'chronic_conditions' => 'nullable|string',
            'current_medications' => 'nullable|string',
            'medical_history' => 'nullable|string',
            'occupation' => 'nullable|string|max:100',
            'insurance_provider' => 'nullable|string|max:100',
            'insurance_number' => 'nullable|string|max:100',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('patients', 'public');
        }

        $patient = Patient::create($validated);

        return redirect()->route('patients.index')
            ->with('success', 'Patient registered successfully! Patient ID: ' . $patient->patient_id);
    }

    /**
     * Display the specified patient
     */
    public function show(Patient $patient)
    {
        // Load all medical history relationships
        $patient->load([
            'latestAppointment',
            'appointments' => function($query) {
                $query->latest()->limit(10);
            },
            'bills' => function($query) {
                $query->latest()->limit(10);
            }
        ]);

        // Get admission history
        $admissions = \App\Models\Admission::where('patient_id', $patient->id)
            ->with(['ward', 'bed', 'doctor'])
            ->latest()
            ->get();

        // Get consultation history with related prescriptions
        $consultations = \App\Models\Consultation::where('patient_id', $patient->id)
            ->with(['doctor', 'appointment', 'prescriptions.items.medicine'])
            ->latest()
            ->limit(10)
            ->get();

        // Get prescriptions not linked to consultations
        $standalonePrescriptions = \App\Models\Prescription::where('patient_id', $patient->id)
            ->whereNull('consultation_id')
            ->with(['items.medicine', 'doctor'])
            ->latest()
            ->limit(10)
            ->get();

        // Get lab orders
        $labOrders = \App\Models\LabOrder::where('patient_id', $patient->id)
            ->latest()
            ->limit(10)
            ->get();
        
        return view('patients.show', compact('patient', 'admissions', 'consultations', 'standalonePrescriptions', 'labOrders'));
    }

    /**
     * Show the form for editing the patient
     */
    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    /**
     * Update the specified patient
     */
    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'blood_group' => 'nullable|string|max:10',
            'marital_status' => 'nullable|string|max:20',
            'phone' => 'required|string|max:20|unique:patients,phone,' . $patient->id,
            'email' => 'nullable|email|max:100|unique:patients,email,' . $patient->id,
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'required|string|max:100',
            'emergency_contact_name' => 'required|string|max:100',
            'emergency_contact_phone' => 'required|string|max:20',
            'emergency_contact_relationship' => 'required|string|max:50',
            'allergies' => 'nullable|string',
            'chronic_conditions' => 'nullable|string',
            'current_medications' => 'nullable|string',
            'medical_history' => 'nullable|string',
            'occupation' => 'nullable|string|max:100',
            'insurance_provider' => 'nullable|string|max:100',
            'insurance_number' => 'nullable|string|max:100',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($patient->photo) {
                Storage::disk('public')->delete($patient->photo);
            }
            $validated['photo'] = $request->file('photo')->store('patients', 'public');
        }

        $patient->update($validated);

        return redirect()->route('patients.index')
            ->with('success', 'Patient updated successfully!');
    }

    /**
     * Remove the specified patient
     */
    public function destroy(Patient $patient)
    {
        // Soft delete
        $patient->delete();

        return redirect()->route('patients.index')
            ->with('success', 'Patient record archived successfully!');
    }

    /**
     * Toggle patient status
     */
    public function toggleStatus(Patient $patient)
    {
        $patient->update(['is_active' => !$patient->is_active]);

        $status = $patient->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Patient {$status} successfully!");
    }
}
