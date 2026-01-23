<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillItem;
use App\Models\Patient;
use App\Models\Service;
use App\Models\Appointment;
use Illuminate\Http\Request;

class BillController extends Controller
{
    public function index(Request $request)
    {
        $query = Bill::with(['patient', 'billedBy']);

        // Filter by payment status
        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by bill type
        if ($request->has('bill_type')) {
            $query->where('bill_type', $request->bill_type);
        }

        // Search by patient name or bill number
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('bill_number', 'like', "%{$search}%")
                  ->orWhereHas('patient', function($pq) use ($search) {
                      $pq->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        $bills = $query->latest()->paginate(20);
        
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
        
        return view('billing.bills.create', compact('patients', 'services', 'appointment'));
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
            'visit_type' => $validated['visit_type'],
            'reference_id' => $validated['reference_id'],
            'bill_date' => now(),
            'discount_percentage' => $validated['discount_percentage'] ?? 0,
            'discount_reason' => $validated['discount_reason'],
            'billed_by_user_id' => auth()->id(),
            'status' => 'draft',
            'notes' => $validated['notes'],
        ]);

        // Create bill items
        foreach ($validated['services'] as $serviceData) {
            $service = Service::findOrFail($serviceData['service_id']);
            
            $billItem = BillItem::create([
                'bill_id' => $bill->id,
                'service_id' => $service->id,
                'service_name' => $service->service_name,
                'service_code' => $service->service_code,
                'quantity' => $serviceData['quantity'],
                'unit_price' => $service->standard_charge,
                'discount_percentage' => $serviceData['discount_percentage'] ?? 0,
                'tax_percentage' => $service->taxable ? $service->tax_percentage : 0,
                'service_date' => now(),
                'performed_by_user_id' => auth()->id(),
            ]);

            $billItem->calculateTotals();
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

        $nextNumber = $lastBill ? intval(substr($lastBill->bill_number, strlen($prefix) + 1)) + 1 : 1;

        return $prefix . '-' . date('Y') . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }
}
