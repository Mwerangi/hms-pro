<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Patient;
use App\Models\Ward;
use App\Models\Bed;
use App\Models\User;
use Illuminate\Http\Request;

class AdmissionController extends Controller
{
    public function index()
    {
        $admissions = Admission::with(['patient', 'ward', 'bed', 'doctor'])
            ->orderBy('admission_date', 'desc')
            ->paginate(20);

        return view('admissions.index', compact('admissions'));
    }

    public function create()
    {
        $patients = Patient::where('is_active', true)->orderBy('first_name')->get();
        $wards = Ward::where('is_active', true)->orderBy('ward_name')->get();
        $doctors = User::role('doctor')->orderBy('name')->get();

        return view('admissions.create', compact('patients', 'wards', 'doctors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'ward_id' => 'required|exists:wards,id',
            'bed_id' => 'nullable|exists:beds,id',
            'doctor_id' => 'required|exists:users,id',
            'admission_date' => 'required|date',
            'admission_type' => 'required|in:emergency,elective,transfer,delivery',
            'admission_category' => 'required|in:medical,surgical,maternity,pediatric,icu',
            'reason_for_admission' => 'required',
            'provisional_diagnosis' => 'nullable',
            'complaints' => 'nullable',
            'medical_history' => 'nullable',
            'blood_pressure' => 'nullable',
            'temperature' => 'nullable|numeric',
            'pulse_rate' => 'nullable|integer',
            'respiratory_rate' => 'nullable|integer',
            'oxygen_saturation' => 'nullable|integer',
            'payment_mode' => 'required|in:cash,insurance,company,government',
            'insurance_company' => 'nullable',
            'insurance_policy_number' => 'nullable',
            'estimated_stay_days' => 'nullable|numeric',
            'advance_payment' => 'nullable|numeric|min:0',
            'emergency_contact_name' => 'nullable',
            'emergency_contact_relation' => 'nullable',
            'emergency_contact_phone' => 'nullable',
            'admission_notes' => 'nullable',
            'special_instructions' => 'nullable',
        ]);

        $validated['admission_number'] = Admission::generateAdmissionNumber();
        $validated['admitted_by'] = auth()->id();
        $validated['status'] = 'admitted';
        $validated['advance_payment'] = $validated['advance_payment'] ?? 0;

        $admission = Admission::create($validated);

        // Mark bed as occupied if assigned
        if ($admission->bed_id) {
            $admission->bed->markAsOccupied();
        }

        return redirect()->route('admissions.show', $admission)->with('success', 'Patient admitted successfully.');
    }

    public function show(Admission $admission)
    {
        $admission->load(['patient', 'ward', 'bed', 'doctor', 'admittedBy', 'dischargedBy']);
        return view('admissions.show', compact('admission'));
    }

    public function edit(Admission $admission)
    {
        if ($admission->status !== 'admitted') {
            return redirect()->back()->with('error', 'Can only edit active admissions.');
        }

        $patients = Patient::where('status', 'active')->orderBy('full_name')->get();
        $wards = Ward::where('is_active', true)->orderBy('ward_name')->get();
        $doctors = User::role('doctor')->orderBy('name')->get();

        return view('admissions.edit', compact('admission', 'patients', 'wards', 'doctors'));
    }

    public function update(Request $request, Admission $admission)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'provisional_diagnosis' => 'nullable',
            'medical_history' => 'nullable',
            'payment_mode' => 'required|in:cash,insurance,company,government',
            'insurance_company' => 'nullable',
            'insurance_policy_number' => 'nullable',
            'admission_notes' => 'nullable',
            'special_instructions' => 'nullable',
        ]);

        $admission->update($validated);

        return redirect()->route('admissions.show', $admission)->with('success', 'Admission updated successfully.');
    }

    public function dischargeForm(Admission $admission)
    {
        if ($admission->status !== 'admitted') {
            return redirect()->back()->with('error', 'Patient is not currently admitted.');
        }

        return view('admissions.discharge', compact('admission'));
    }

    public function discharge(Request $request, Admission $admission)
    {
        $validated = $request->validate([
            'discharge_date' => 'required|date',
            'discharge_summary' => 'required',
            'discharge_instructions' => 'nullable',
            'follow_up_instructions' => 'nullable',
        ]);

        $validated['status'] = 'discharged';
        $validated['discharged_by'] = auth()->id();

        $admission->update($validated);

        // Mark bed as available for cleaning
        if ($admission->bed) {
            $admission->bed->markForCleaning();
        }

        return redirect()->route('admissions.show', $admission)->with('success', 'Patient discharged successfully.');
    }

    public function transfer(Request $request, Admission $admission)
    {
        $validated = $request->validate([
            'ward_id' => 'required|exists:wards,id',
            'bed_id' => 'nullable|exists:beds,id',
        ]);

        // Free up old bed
        if ($admission->bed) {
            $admission->bed->markForCleaning();
        }

        // Update admission
        $admission->update($validated);

        // Occupy new bed
        if ($admission->bed) {
            $admission->bed->markAsOccupied();
        }

        return redirect()->route('admissions.show', $admission)->with('success', 'Patient transferred successfully.');
    }

    public function destroy(Admission $admission)
    {
        if ($admission->status === 'admitted') {
            return redirect()->back()->with('error', 'Cannot delete an active admission. Please discharge the patient first.');
        }

        $admission->delete();

        return redirect()->route('admissions.index')->with('success', 'Admission record deleted successfully.');
    }
}
