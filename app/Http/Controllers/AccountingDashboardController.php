<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientCharge;
use App\Models\Bill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountingDashboardController extends Controller
{
    /**
     * Display accountant dashboard with patients having pending charges
     */
    public function index(Request $request)
    {
        $filterDate = $request->get('date', now()->toDateString());
        
        // Get patient IDs with pending charges
        $patientIdsQuery = PatientCharge::select('patient_id')
            ->where('status', 'pending')
            ->when($request->filled('date'), function($query) use ($filterDate) {
                $query->whereDate('service_date', $filterDate);
            })
            ->distinct()
            ->pluck('patient_id');
        
        // Get patients with their pending charges
        $patientsWithCharges = Patient::whereIn('id', $patientIdsQuery)
            ->with(['charges' => function($query) {
                $query->where('status', 'pending')
                      ->with(['service', 'addedBy']);
            }])
            ->get()
            ->map(function($patient) {
                $patient->pending_charges_count = $patient->charges
                    ->where('status', 'pending')
                    ->count();
                $patient->total_pending_amount = $patient->charges
                    ->where('status', 'pending')
                    ->sum('total_amount');
                return $patient;
            });

        // Statistics
        $stats = [
            'patients_with_pending' => $patientsWithCharges->count(),
            'total_pending_amount' => PatientCharge::where('status', 'pending')->sum('total_amount'),
            'pending_charges_today' => PatientCharge::where('status', 'pending')
                ->whereDate('service_date', now()->toDateString())
                ->count(),
            'bills_created_today' => Bill::whereDate('created_at', now()->toDateString())->count(),
        ];

        return view('accounting.dashboard', compact('patientsWithCharges', 'stats', 'filterDate'));
    }

    /**
     * Show detailed charges for a specific patient
     */
    public function patientCharges(Patient $patient)
    {
        $charges = PatientCharge::where('patient_id', $patient->id)
            ->where('status', 'pending')
            ->with(['service', 'addedBy'])
            ->orderBy('service_date', 'desc')
            ->get();

        $totalAmount = $charges->sum('total_amount');

        return view('accounting.patient-charges', compact('patient', 'charges', 'totalAmount'));
    }
}

