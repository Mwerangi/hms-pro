<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\PatientCharge;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\LabOrder;
use App\Models\Admission;
use App\Models\Service;
use App\Models\Ward;
use App\Models\Bed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsultationController extends Controller
{
    public function index()
    {
        $consultations = Consultation::with(['patient', 'doctor', 'appointment'])
            ->latest()
            ->paginate(20);

        return view('consultations.index', compact('consultations'));
    }

    public function create(Request $request)
    {
        $appointmentId = $request->get('appointment_id');
        $appointment = null;
        $patient = null;

        if ($appointmentId) {
            $appointment = Appointment::with('patient')->findOrFail($appointmentId);
            $patient = $appointment->patient;
            
            // Pre-fill vitals if recorded by nursing station
            if ($appointment->vitals_recorded_at) {
                $appointment->vitals_prefilled = true;
            }
        }

        return view('consultations.create', compact('appointment', 'patient'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:users,id',
            // Vitals
            'temperature' => 'nullable|string|max:10',
            'blood_pressure' => 'nullable|string|max:20',
            'pulse_rate' => 'nullable|string|max:10',
            'weight' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'spo2' => 'nullable|string|max:10',
            'respiratory_rate' => 'nullable|string|max:10',
            // Consultation
            'chief_complaint' => 'nullable|string',
            'history_of_present_illness' => 'nullable|string',
            'allergies' => 'nullable|string',
            'general_examination' => 'nullable|string',
            'provisional_diagnosis' => 'nullable|string',
            'final_diagnosis' => 'nullable|string',
            'treatment_plan' => 'nullable|string',
            'doctor_notes' => 'nullable|string',
            'advice_instructions' => 'nullable|string',
        ]);

        $consultation = Consultation::create($validated);

        return redirect()->route('consultations.show', $consultation)
            ->with('success', 'Consultation started successfully.');
    }

    public function show(Consultation $consultation)
    {
        $consultation->load([
            'patient', 
            'doctor', 
            'appointment', 
            'prescriptions.items', 
            'prescriptions.dispensedBy',
            'labOrders.collectedBy', 
            'labOrders.processedBy', 
            'labOrders.reportedBy', 
            'labOrders.viewedBy'
        ]);
        
        // Mark all reported lab results as viewed when doctor opens consultation
        if (auth()->check() && auth()->user()->hasRole('doctor')) {
            $consultation->labOrders()
                ->where('status', 'reported')
                ->whereNull('viewed_at')
                ->update([
                    'viewed_at' => now(),
                    'viewed_by' => auth()->id()
                ]);
        }
        
        // Check for pending and reported lab results
        $pendingLabResults = $consultation->labOrders()->whereNotIn('status', ['reported', 'cancelled'])->count();
        $reportedLabResults = $consultation->labOrders()->where('status', 'reported')->count();
        $hasPrescription = $consultation->prescriptions()->exists();
        
        return view('consultations.show', compact('consultation', 'pendingLabResults', 'reportedLabResults', 'hasPrescription'));
    }

    /**
     * Resume consultation to review lab results
     */
    public function resume(Consultation $consultation)
    {
        // Reopen consultation if it was completed
        if ($consultation->status === 'completed') {
            $consultation->status = 'in-progress';
            $consultation->save();
        }

        return redirect()->route('consultations.edit', $consultation)
            ->with('info', 'Consultation resumed. You can now review lab results and update treatment.');
    }

    /**
     * Admit patient to IPD
     */
    public function admitPatient(Request $request, Consultation $consultation)
    {
        $validated = $request->validate([
            'reason_for_admission' => 'required|string',
            'diagnosis' => 'required|string',
            'chief_complaints' => 'required|string',
            'ward_id' => 'required|exists:wards,id',
            'bed_id' => 'nullable|exists:beds,id',
            'admission_type' => 'required|in:emergency,elective,transfer,delivery',
            'admission_category' => 'required|in:medical,surgical,maternity,pediatric,icu',
            'payment_mode' => 'required|in:cash,insurance,company,government',
            'insurance_company' => 'nullable|string',
            'insurance_policy_number' => 'nullable|string',
            'estimated_stay_days' => 'nullable|numeric',
            'advance_payment' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Create admission
            $admission = Admission::create([
                'admission_number' => Admission::generateAdmissionNumber(),
                'patient_id' => $consultation->patient_id,
                'ward_id' => $validated['ward_id'],
                'bed_id' => $validated['bed_id'] ?? null,
                'doctor_id' => $consultation->doctor_id,
                'admitted_by' => auth()->id(),
                'admission_date' => now(),
                'admission_type' => $validated['admission_type'],
                'admission_category' => $validated['admission_category'],
                'reason_for_admission' => $validated['reason_for_admission'],
                'provisional_diagnosis' => $validated['diagnosis'],
                'complaints' => $validated['chief_complaints'],
                'blood_pressure' => $consultation->appointment->blood_pressure ?? null,
                'temperature' => $consultation->appointment->temperature ?? null,
                'pulse_rate' => $consultation->appointment->pulse_rate ?? null,
                'respiratory_rate' => $consultation->appointment->respiratory_rate ?? null,
                'oxygen_saturation' => $consultation->appointment->oxygen_saturation ?? null,
                'payment_mode' => $validated['payment_mode'],
                'insurance_company' => $validated['insurance_company'] ?? null,
                'insurance_policy_number' => $validated['insurance_policy_number'] ?? null,
                'estimated_stay_days' => $validated['estimated_stay_days'] ?? null,
                'advance_payment' => $validated['advance_payment'] ?? 0,
                'status' => 'admitted',
            ]);

            // Mark bed as occupied if assigned
            if ($admission->bed_id) {
                $admission->bed->markAsOccupied();
            }

            // Update consultation notes
            $consultation->doctor_notes = ($consultation->doctor_notes ? $consultation->doctor_notes . "\n\n" : '') 
                . "--- PATIENT ADMITTED TO IPD ---\n"
                . "Admission #: " . $admission->admission_number . "\n"
                . "Ward: " . $admission->ward->ward_name . "\n"
                . ($admission->bed ? "Bed: " . $admission->bed->bed_number . "\n" : '')
                . "Admission Type: " . ucfirst($admission->admission_type);
            $consultation->save();

            // Mark consultation as completed
            $consultation->complete();

            DB::commit();

            return redirect()->route('admissions.show', $admission)
                ->with('success', 'Patient admitted successfully to IPD.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to admit patient: ' . $e->getMessage());
        }
    }

    public function edit(Consultation $consultation)
    {
        if (!$consultation->canBeEdited()) {
            return redirect()->back()
                ->with('error', 'This consultation cannot be edited.');
        }

        // Get active medicines for prescription dropdown
        $medicines = \App\Models\Medicine::active()
            ->orderBy('medicine_name')
            ->get();

        return view('consultations.edit', compact('consultation', 'medicines'));
    }

    public function update(Request $request, Consultation $consultation)
    {
        $validated = $request->validate([
            'temperature' => 'nullable|string|max:10',
            'blood_pressure' => 'nullable|string|max:20',
            'pulse_rate' => 'nullable|string|max:10',
            'weight' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'spo2' => 'nullable|string|max:10',
            'respiratory_rate' => 'nullable|string|max:10',
            'chief_complaint' => 'nullable|string',
            'history_of_present_illness' => 'nullable|string',
            'allergies' => 'nullable|string',
            'general_examination' => 'nullable|string',
            'provisional_diagnosis' => 'nullable|string',
            'final_diagnosis' => 'nullable|string',
            'treatment_plan' => 'nullable|string',
            'doctor_notes' => 'nullable|string',
            'advice_instructions' => 'nullable|string',
        ]);

        $consultation->update($validated);

        return redirect()->route('consultations.show', $consultation)
            ->with('success', 'Consultation updated successfully.');
    }

    public function destroy(Consultation $consultation)
    {
        $consultation->delete();

        return redirect()->route('consultations.index')
            ->with('success', 'Consultation deleted successfully.');
    }

    // Start consultation from appointment
    public function startFromAppointment(Appointment $appointment)
    {
        // Check if consultation already exists
        $existingConsultation = Consultation::where('appointment_id', $appointment->id)->first();
        
        if ($existingConsultation) {
            return redirect()->route('consultations.show', $existingConsultation);
        }

        $consultation = Consultation::create([
            'appointment_id' => $appointment->id,
            'patient_id' => $appointment->patient_id,
            'doctor_id' => $appointment->doctor_id,
            'started_at' => now(),
        ]);

        return redirect()->route('consultations.edit', $consultation);
    }

    // Complete consultation
    public function complete(Consultation $consultation)
    {
        $consultation->complete();

        return redirect()->route('consultations.show', $consultation)
            ->with('success', 'Consultation completed successfully.');
    }

    // Add prescription to consultation
    public function addPrescription(Request $request, Consultation $consultation)
    {
        $validated = $request->validate([
            'special_instructions' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.medicine_id' => 'required|exists:medicines,id',
            'items.*.dosage' => 'required|string',
            'items.*.frequency' => 'required|string',
            'items.*.duration' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.instructions' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $prescription = Prescription::create([
                'consultation_id' => $consultation->id,
                'patient_id' => $consultation->patient_id,
                'doctor_id' => $consultation->doctor_id,
                'special_instructions' => $validated['special_instructions'] ?? null,
            ]);

            foreach ($validated['items'] as $item) {
                // Get medicine details
                $medicine = \App\Models\Medicine::findOrFail($item['medicine_id']);
                
                // Calculate total price
                $totalPrice = $item['quantity'] * $medicine->unit_price;
                
                PrescriptionItem::create([
                    'prescription_id' => $prescription->id,
                    'medicine_id' => $medicine->id,
                    'medicine_name' => $medicine->medicine_name,
                    'medicine_type' => $medicine->medicine_type,
                    'dosage' => $item['dosage'],
                    'frequency' => $item['frequency'],
                    'duration' => $item['duration'],
                    'quantity' => $item['quantity'],
                    'price_per_unit' => $medicine->unit_price,
                    'total_price' => $totalPrice,
                    'instructions' => $item['instructions'] ?? null,
                ]);
            }

            // Add medication charges immediately (Hybrid Approach)
            $this->addPrescriptionCharges($prescription);

            DB::commit();

            return redirect()->route('consultations.show', $consultation)
                ->with('success', 'Prescription added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create prescription: ' . $e->getMessage());
        }
    }

    // Add lab order to consultation
    public function addLabOrder(Request $request, Consultation $consultation)
    {
        $validated = $request->validate([
            'test_type' => 'required|in:blood,urine,stool,imaging,pathology,other',
            'tests_ordered' => 'required|string',
            'urgency' => 'required|in:routine,urgent,stat',
            'clinical_notes' => 'nullable|string',
        ]);

        $labOrder = LabOrder::create([
            'consultation_id' => $consultation->id,
            'patient_id' => $consultation->patient_id,
            'doctor_id' => $consultation->doctor_id,
            'test_type' => $validated['test_type'],
            'tests_ordered' => $validated['tests_ordered'],
            'urgency' => $validated['urgency'],
            'clinical_notes' => $validated['clinical_notes'] ?? null,
        ]);

        // Auto-add lab test charges from service catalog
        $this->addLabTestCharges($labOrder, $validated['tests_ordered']);

        return redirect()->route('consultations.show', $consultation)
            ->with('success', 'Lab order added successfully.');
    }

    /**
     * Auto-add lab test charges from service catalog
     */
    protected function addLabTestCharges(LabOrder $labOrder, string $testsOrdered)
    {
        // Parse the tests ordered (comma-separated or line-separated)
        $tests = preg_split('/[,\n]+/', $testsOrdered);
        
        foreach ($tests as $test) {
            $test = trim($test);
            if (empty($test)) continue;
            
            // Try to match test name with service catalog
            // This is a basic implementation - you can make it smarter with fuzzy matching
            $service = Service::where('category', 'laboratory')
                ->where('is_active', true)
                ->where(function($query) use ($test) {
                    $query->where('service_name', 'LIKE', "%{$test}%")
                          ->orWhere('service_code', 'LIKE', "%{$test}%");
                })
                ->first();
            
            if ($service) {
                PatientCharge::createFromService(
                    $service,
                    $labOrder->patient,
                    LabOrder::class,
                    $labOrder->id
                );
            }
        }
    }

    /**
     * Add prescription charges when prescription is created
     * Hybrid Approach: Dispensing fee + Medication costs
     */
    protected function addPrescriptionCharges(Prescription $prescription)
    {
        // 1. Add dispensing fee (professional service from catalog)
        $dispensingService = Service::where('service_code', 'PHARM-001')
            ->where('is_active', true)
            ->first();
        
        if ($dispensingService) {
            PatientCharge::createFromService(
                $dispensingService,
                $prescription->patient,
                Prescription::class,
                $prescription->id
            );
        }

        // 2. Add medication costs (from prescription items)
        foreach ($prescription->items as $item) {
            // Get medicine name from relationship or fallback to medicine_name field
            $medicineName = $item->medicine ? $item->medicine->full_name : $item->medicine_name;
            
            $charge = new PatientCharge([
                'patient_id' => $prescription->patient_id,
                'service_id' => null, // Medications don't link to service catalog
                'source_type' => Prescription::class,
                'source_id' => $prescription->id,
                'quantity' => $item->quantity,
                'unit_price' => $item->price_per_unit,
                'taxable' => false, // Medications typically tax-exempt in Tanzania
                'tax_percentage' => 0,
                'added_by' => auth()->id(),
                'service_date' => now(),
                'notes' => "Medication: {$medicineName} ({$item->dosage})",
            ]);

            $charge->calculateTotals();
            $charge->save();
        }
    }
}

