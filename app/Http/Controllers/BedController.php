<?php

namespace App\Http\Controllers;

use App\Models\Bed;
use App\Models\Ward;
use Illuminate\Http\Request;

class BedController extends Controller
{
    public function index()
    {
        $beds = Bed::with('ward')->orderBy('ward_id')->orderBy('bed_number')->paginate(50);
        return view('beds.index', compact('beds'));
    }

    public function create()
    {
        $wards = Ward::where('is_active', true)->orderBy('ward_name')->get();
        return view('beds.create', compact('wards'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ward_id' => 'required|exists:wards,id',
            'bed_number' => 'required',
            'bed_label' => 'nullable',
            'bed_type' => 'required|in:standard,electric,icu,nicu,isolation',
            'has_oxygen' => 'boolean',
            'has_ventilator' => 'boolean',
            'has_monitor' => 'boolean',
            'additional_charge_per_day' => 'required|numeric|min:0',
            'notes' => 'nullable',
        ]);

        $validated['status'] = 'available';
        $validated['is_active'] = true;

        $bed = Bed::create($validated);
        $bed->ward->updateBedCounts();

        return redirect()->route('wards.show', $bed->ward_id)->with('success', 'Bed created successfully.');
    }

    public function show(Bed $bed)
    {
        $bed->load(['ward', 'currentAdmission.patient']);
        return view('beds.show', compact('bed'));
    }

    public function edit(Bed $bed)
    {
        $wards = Ward::where('is_active', true)->orderBy('ward_name')->get();
        return view('beds.edit', compact('bed', 'wards'));
    }

    public function update(Request $request, Bed $bed)
    {
        $validated = $request->validate([
            'ward_id' => 'required|exists:wards,id',
            'bed_number' => 'required',
            'bed_label' => 'nullable',
            'bed_type' => 'required|in:standard,electric,icu,nicu,isolation',
            'has_oxygen' => 'boolean',
            'has_ventilator' => 'boolean',
            'has_monitor' => 'boolean',
            'additional_charge_per_day' => 'required|numeric|min:0',
            'notes' => 'nullable',
        ]);

        $bed->update($validated);
        $bed->ward->updateBedCounts();

        return redirect()->route('beds.show', $bed)->with('success', 'Bed updated successfully.');
    }

    public function destroy(Bed $bed)
    {
        if ($bed->status === 'occupied') {
            return redirect()->back()->with('error', 'Cannot delete an occupied bed.');
        }

        $ward = $bed->ward;
        $bed->delete();
        $ward->updateBedCounts();

        return redirect()->route('wards.show', $ward)->with('success', 'Bed deleted successfully.');
    }

    public function toggleStatus(Bed $bed)
    {
        $bed->is_active = !$bed->is_active;
        $bed->save();
        $bed->ward->updateBedCounts();

        $status = $bed->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Bed {$status} successfully.");
    }

    public function markForCleaning(Bed $bed)
    {
        $bed->markForCleaning();
        return redirect()->back()->with('success', 'Bed marked for cleaning.');
    }

    public function markAvailable(Bed $bed)
    {
        $bed->markAsAvailable();
        return redirect()->back()->with('success', 'Bed is now available.');
    }
}
