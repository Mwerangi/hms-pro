<?php

namespace App\Http\Controllers;

use App\Models\Ward;
use App\Models\Bed;
use App\Models\Setting;
use Illuminate\Http\Request;

class WardController extends Controller
{
    public function index()
    {
        $wards = Ward::with('nurse')
            ->withCount(['beds', 'activeBeds', 'availableBeds', 'occupiedBeds'])
            ->orderBy('ward_number')
            ->get();

        return view('wards.index', compact('wards'));
    }

    public function create()
    {
        $nurses = \App\Models\User::role('nurse')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('wards.create', compact('nurses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ward_number' => 'required|unique:wards',
            'ward_name' => 'required',
            'ward_type' => 'required|in:general,semi-private,private,icu,nicu,picu,emergency',
            'floor' => 'nullable',
            'building' => 'nullable',
            'description' => 'nullable',
            'nurse_id' => 'nullable|exists:users,id',
            'contact_number' => 'nullable',
            'base_charge_per_day' => 'required|numeric|min:0',
        ]);

        $validated['total_beds'] = 0;
        $validated['available_beds'] = 0;
        $validated['occupied_beds'] = 0;
        $validated['is_active'] = true;

        Ward::create($validated);

        return redirect()->route('wards.index')->with('success', 'Ward created successfully.');
    }

    public function show(Ward $ward)
    {
        $ward->load([
            'beds' => function($query) {
                $query->orderBy('bed_number');
            }, 
            'beds.currentAdmission' => function($query) {
                $query->with(['patient', 'doctor', 'admittedBy', 'dischargedBy']);
            },
            'nurse'
        ]);

        $stats = [
            'total_beds' => $ward->beds()->where('is_active', true)->count(),
            'available' => $ward->beds()->where('status', 'available')->where('is_active', true)->count(),
            'occupied' => $ward->beds()->where('status', 'occupied')->count(),
            'cleaning' => $ward->beds()->where('status', 'under_cleaning')->count(),
            'maintenance' => $ward->beds()->where('status', 'maintenance')->count(),
        ];

        $nurses = \App\Models\User::role('nurse')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        // Get ward charge from settings based on ward type
        $wardChargeKey = $this->getWardChargeSettingKey($ward->ward_type);
        $wardCharge = Setting::where('key', $wardChargeKey)->value('value') ?? $ward->base_charge_per_day;

        return view('wards.show', compact('ward', 'stats', 'nurses', 'wardCharge'));
    }

    public function edit(Ward $ward)
    {
        $nurses = \App\Models\User::role('nurse')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        // Get ward charge from settings based on ward type
        $wardChargeKey = $this->getWardChargeSettingKey($ward->ward_type);
        $wardCharge = Setting::where('key', $wardChargeKey)->value('value') ?? $ward->base_charge_per_day;

        return view('wards.show', compact('ward', 'stats', 'nurses', 'wardCharge'));
    }

    public function update(Request $request, Ward $ward)
    {
        $validated = $request->validate([
            'ward_number' => 'required|unique:wards,ward_number,' . $ward->id,
            'ward_name' => 'required',
            'ward_type' => 'required|in:general,semi-private,private,icu,nicu,picu,emergency',
            'floor' => 'nullable',
            'building' => 'nullable',
            'description' => 'nullable',
            'nurse_id' => 'nullable|exists:users,id',
            'contact_number' => 'nullable',
            'base_charge_per_day' => 'required|numeric|min:0',
        ]);

        $ward->update($validated);

        return redirect()->route('wards.show', $ward)->with('success', 'Ward updated successfully.');
    }

    public function destroy(Ward $ward)
    {
        if ($ward->beds()->where('status', 'occupied')->exists()) {
            return redirect()->back()->with('error', 'Cannot delete ward with occupied beds.');
        }

        $ward->delete();

        return redirect()->route('wards.index')->with('success', 'Ward deleted successfully.');
    }

    public function toggleStatus(Ward $ward)
    {
        $ward->is_active = !$ward->is_active;
        $ward->save();

        $status = $ward->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Ward {$status} successfully.");
    }

    /**
     * Assign or reassign a nurse to a ward
     */
    public function assignNurse(Request $request, Ward $ward)
    {
        $validated = $request->validate([
            'nurse_id' => 'nullable|exists:users,id',
        ]);

        $ward->nurse_id = $validated['nurse_id'];
        $ward->save();

        $message = $validated['nurse_id'] 
            ? 'Nurse assigned successfully.' 
            : 'Nurse removed from ward.';

        return redirect()->back()->with('success', $message);
    }

    /**
     * Get available beds for a specific ward (API endpoint)
     */
    public function getAvailableBeds(Ward $ward)
    {
        $beds = $ward->beds()
            ->where('status', 'available')
            ->where('is_active', true)
            ->select('id', 'bed_number', 'bed_type')
            ->orderBy('bed_number')
            ->get();

        return response()->json([
            'ward' => [
                'id' => $ward->id,
                'name' => $ward->ward_name,
                'available_count' => $ward->available_beds
            ],
            'beds' => $beds
        ]);
    }

    /**
     * Map ward type to settings key
     */
    private function getWardChargeSettingKey($wardType)
    {
        $mapping = [
            'general' => 'ipd_general_ward_charge',
            'semi-private' => 'ipd_private_ward_charge',
            'private' => 'ipd_private_ward_charge',
            'icu' => 'ipd_icu_charge',
            'nicu' => 'ipd_nicu_charge',
            'picu' => 'ipd_pediatric_charge',
            'emergency' => 'ipd_general_ward_charge',
            'maternity' => 'ipd_maternity_charge',
            'pediatric' => 'ipd_pediatric_charge',
        ];

        return $mapping[$wardType] ?? 'ipd_general_ward_charge';
    }
}
