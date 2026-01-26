<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillItem;
use App\Models\Patient;
use App\Models\PatientCharge;
use App\Models\Service;
use App\Models\Appointment;
use Illuminate\Http\Request;

class BillController extends Controller
{
    public function index(Request $request)
    {
        $query = Bill::with(['patient', 'billedBy']);

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by bill type
        if ($request->filled('bill_type')) {
            $query->where('bill_type', $request->bill_type);
        }

        // Search by patient name or bill number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('bill_number', 'like', "%{$search}%")
                  ->orWhereHas('patient', function($pq) use ($search) {
                      $pq->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%")
                         ->orWhere('patient_id', 'like', "%{$search}%")
                         ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                  });
            });
        }

        $bills = $query->latest()->paginate(20)->appends($request->all());
        
        return view('billing.bills.index', compact('bills'));
    }

    public function create(Request $request)
    {
        $patients = Patient::orderBy('first_name')->get();
        $services = Service::active()->orderBy('category')->orderBy('service_name')->get();
        
        // If coming from appointment, get appointment details
        $appointment = null;
        if ($request->has('appointment_id')) {
            $appointment = Appointment::with('patient')->findOrFail($request->appointment_id);
        }
        
        // Get pending charges if patient_id is provided
        $pendingCharges = [];
        if ($request->has('patient_id')) {
            $pendingCharges = PatientCharge::where('patient_id', $request->patient_id)
                ->where('status', 'pending')
                ->with('service')
                ->orderBy('service_date')
                ->get();
        }
        
        return view('billing.bills.create', compact('patients', 'services', 'appointment', 'pendingCharges'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'bill_type' => 'required|in:opd,ipd,emergency,pharmacy,laboratory',
            'visit_type' => 'nullable|in:opd,ipd,emergency',
            'reference_id' => 'nullable|integer',
            'services' => 'required|array|min:1',
            'services.*.service_id' => 'required|exists:services,id',
            'services.*.quantity' => 'required|integer|min:1',
            'services.*.discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_reason' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Generate bill number
        $billNumber = $this->generateBillNumber($validated['bill_type']);

        // Create bill
        $bill = Bill::create([
            'bill_number' => $billNumber,
            'bill_type' => $validated['bill_type'],
            'patient_id' => $validated['patient_id'],
            'visit_type' => $validated['visit_type'] ?? null,
            'reference_id' => $validated['reference_id'] ?? null,
            'bill_date' => now(),
            'discount_percentage' => $validated['discount_percentage'] ?? 0,
            'discount_reason' => $validated['discount_reason'] ?? null,
            'billed_by_user_id' => auth()->id(),
            'status' => 'draft',
            'notes' => $validated['notes'] ?? null,
        ]);

        // Create bill items
        foreach ($validated['services'] as $serviceData) {
            $service = Service::findOrFail($serviceData['service_id']);
            
            $quantity = $serviceData['quantity'];
            $unitPrice = $service->standard_charge;
            $discountPercentage = $serviceData['discount_percentage'] ?? 0;
            $taxPercentage = $service->taxable ? $service->tax_percentage : 0;
            
            // Calculate amounts
            $subtotal = $unitPrice * $quantity;
            $discountAmount = $subtotal * ($discountPercentage / 100);
            $taxableAmount = $subtotal - $discountAmount;
            $taxAmount = $taxableAmount * ($taxPercentage / 100);
            $totalAmount = $taxableAmount + $taxAmount;
            
            BillItem::create([
                'bill_id' => $bill->id,
                'service_id' => $service->id,
                'service_name' => $service->service_name,
                'service_code' => $service->service_code,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'discount_percentage' => $discountPercentage,
                'discount_amount' => $discountAmount,
                'tax_percentage' => $taxPercentage,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'service_date' => now(),
                'performed_by_user_id' => auth()->id(),
            ]);
        }

        // Calculate bill totals
        $bill->calculateTotals();
        
        // Finalize bill
        $bill->status = 'finalized';
        $bill->save();

        return redirect()->route('bills.show', $bill)->with('success', 'Bill created successfully.');
    }

    public function show(Bill $bill)
    {
        $bill->load(['patient', 'billItems.service', 'payments.receivedBy', 'billedBy']);
        return view('billing.bills.show', compact('bill'));
    }

    public function addPayment(Request $request, Bill $bill)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,card,upi,bank_transfer,cheque,insurance,other',
            'payment_reference' => 'nullable|string',
            'bank_name' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Validate amount doesn't exceed balance
        if ($validated['amount'] > $bill->balance_amount) {
            return redirect()->back()->with('error', 'Payment amount cannot exceed balance amount.');
        }

        $bill->addPayment(
            $validated['amount'],
            $validated['payment_method'],
            $validated['payment_reference'],
            auth()->id()
        );

        // Verify payment for any pending prescriptions
        // If bill is at least 50% paid, verify payment for prescriptions
        $paymentPercentage = ($bill->paid_amount / $bill->total_amount) * 100;
        
        if ($paymentPercentage >= 50) {
            // Find prescriptions linked to this patient (not yet payment verified)
            $prescriptions = \App\Models\Prescription::where('patient_id', $bill->patient_id)
                ->where('payment_verified', false)
                ->where('is_emergency', false)
                ->get();
            
            foreach ($prescriptions as $prescription) {
                $prescription->verifyPayment($bill);
            }
            
            if ($prescriptions->count() > 0) {
                session()->flash('info', $prescriptions->count() . ' prescription(s) payment verified. Medications can now be dispensed.');
            }
        }

        return redirect()->route('bills.show', $bill)->with('success', 'Payment added successfully.');
    }

    public function receipt(Bill $bill)
    {
        $bill->load(['patient', 'billItems.service', 'payments.receivedBy', 'billedBy']);
        return view('billing.bills.receipt', compact('bill'));
    }

    protected function generateBillNumber($billType)
    {
        $prefix = match($billType) {
            'opd' => 'OPD',
            'ipd' => 'IPD',
            'emergency' => 'EMR',
            'pharmacy' => 'PHR',
            'laboratory' => 'LAB',
            default => 'BIL',
        };

        $lastBill = Bill::where('bill_type', $billType)
            ->orderBy('id', 'desc')
            ->first();

        // Extract number from format: PREFIX-YYYY-NNNNN
        if ($lastBill) {
            $parts = explode('-', $lastBill->bill_number);
            $nextNumber = isset($parts[2]) ? intval($parts[2]) + 1 : 1;
        } else {
            $nextNumber = 1;
        }

        return $prefix . '-' . date('Y') . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Generate bill directly from patient's pending charges
     */
    public function generateFromCharges(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'bill_type' => 'required|in:opd,ipd,emergency,pharmacy,laboratory',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_reason' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Get all pending charges for the patient
        $pendingCharges = PatientCharge::where('patient_id', $patient->id)
            ->where('status', 'pending')
            ->with('service')
            ->get();

        if ($pendingCharges->isEmpty()) {
            return back()->with('error', 'No pending charges found for this patient.');
        }

        // Generate bill number
        $billNumber = $this->generateBillNumber($validated['bill_type']);

        // Calculate subtotal from pending charges
        $subtotal = $pendingCharges->sum(function($charge) {
            return $charge->quantity * $charge->unit_price;
        });

        // Calculate discount
        $discountPercentage = $validated['discount_percentage'] ?? 0;
        $discountAmount = $subtotal * ($discountPercentage / 100);
        $afterDiscount = $subtotal - $discountAmount;

        // Calculate tax
        $taxAmount = $pendingCharges->sum('tax_amount');

        // Total amount
        $totalAmount = $afterDiscount + $taxAmount;

        // Create bill
        $bill = Bill::create([
            'bill_number' => $billNumber,
            'patient_id' => $patient->id,
            'bill_date' => now(),
            'bill_type' => $validated['bill_type'],
            'visit_type' => $validated['bill_type'],
            'billed_by_user_id' => auth()->id(),
            'sub_total' => $subtotal,
            'discount_percentage' => $discountPercentage,
            'discount_amount' => $discountAmount,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'paid_amount' => 0,
            'balance_amount' => $totalAmount,
            'insurance_claim_amount' => 0,
            'patient_payable' => $totalAmount,
            'payment_status' => 'unpaid',
            'status' => 'finalized',
            'discount_reason' => $validated['discount_reason'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        // Create bill items from pending charges and mark charges as billed
        foreach ($pendingCharges as $charge) {
            // Create bill item
            $itemSubtotal = $charge->quantity * $charge->unit_price;
            $itemDiscount = $itemSubtotal * ($charge->discount_percentage / 100);
            $itemAfterDiscount = $itemSubtotal - $itemDiscount;
            $itemTax = $charge->taxable ? ($itemAfterDiscount * $charge->tax_percentage / 100) : 0;
            $itemTotal = $itemAfterDiscount + $itemTax;

            // Get service name from service or use notes for medications
            $serviceName = $charge->service ? $charge->service->service_name : ($charge->notes ?? 'Medication');

            BillItem::create([
                'bill_id' => $bill->id,
                'service_id' => $charge->service_id,
                'service_name' => $serviceName,
                'service_code' => $charge->service ? $charge->service->service_code : null,
                'service_date' => $charge->service_date ?? now(),
                'quantity' => $charge->quantity,
                'unit_price' => $charge->unit_price,
                'discount_percentage' => $charge->discount_percentage ?? 0,
                'discount_amount' => $itemDiscount,
                'tax_percentage' => $charge->tax_percentage ?? 0,
                'tax_amount' => $itemTax,
                'total_amount' => $itemTotal,
                'performed_by_user_id' => $charge->added_by,
                'notes' => $charge->notes,
            ]);

            // Mark charge as billed
            $charge->markAsBilled($bill);
        }

        // Lock associated appointment (if this is a consultation bill)
        $appointmentCharge = $pendingCharges->first(function($charge) {
            return $charge->source_type === 'App\Models\Appointment';
        });

        if ($appointmentCharge && $appointmentCharge->source) {
            $appointment = $appointmentCharge->source;
            $appointment->lockAfterBilling($bill, auth()->id());
        }

        return redirect()->route('bills.show', $bill)
            ->with('success', 'Bill generated successfully from pending charges. Bill Number: ' . $bill->bill_number);
    }
}

