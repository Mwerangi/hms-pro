<?php

namespace App\Http\Controllers;

use App\Models\Ward;
use App\Models\Bed;
use App\Models\Admission;
use App\Models\Patient;
use Illuminate\Http\Request;

class IpdController extends Controller
{
    public function dashboard()
    {
        // Statistics
        $stats = [
            'total_beds' => Bed::where('is_active', true)->count(),
            'available_beds' => Bed::where('status', 'available')->where('is_active', true)->count(),
            'occupied_beds' => Bed::where('status', 'occupied')->count(),
            'current_patients' => Admission::where('status', 'admitted')->count(),
            'total_wards' => Ward::where('is_active', true)->count(),
            'admissions_today' => Admission::whereDate('admission_date', today())->count(),
            'discharges_today' => Admission::whereDate('discharge_date', today())->count(),
        ];

        $stats['occupancy_rate'] = $stats['total_beds'] > 0 
            ? round(($stats['occupied_beds'] / $stats['total_beds']) * 100, 1) 
            : 0;

        // Ward-wise bed availability
        $wards = Ward::with(['beds' => function($query) {
                $query->where('is_active', true);
            }])
            ->where('is_active', true)
            ->get()
            ->map(function($ward) {
                $ward->occupancy_rate = $ward->occupancy_rate;
                return $ward;
            });

        // Recent admissions
        $recentAdmissions = Admission::with(['patient', 'ward', 'bed', 'doctor'])
            ->where('status', 'admitted')
            ->orderBy('admission_date', 'desc')
            ->limit(10)
            ->get();

        // Critical/ICU patients
        $criticalPatients = Admission::with(['patient', 'ward', 'bed', 'doctor'])
            ->whereHas('ward', function($query) {
                $query->whereIn('ward_type', ['icu', 'nicu', 'picu']);
            })
            ->where('status', 'admitted')
            ->get();

        return view('ipd.dashboard', compact('stats', 'wards', 'recentAdmissions', 'criticalPatients'));
    }
}
