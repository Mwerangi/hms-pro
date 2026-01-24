<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::query();
        
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        $services = $query->orderBy('category')->orderBy('service_name')->paginate(15);
        return view('billing.services.index', compact('services'));
    }

    public function create()
    {
        return view('billing.services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_code' => 'required|unique:services',
            'service_name' => 'required',
            'category' => 'required|in:consultation,laboratory,radiology,procedure,pharmacy,room_charge,nursing_care,emergency,surgery,other',
            'department' => 'nullable',
            'description' => 'nullable',
            'standard_charge' => 'required|numeric|min:0',
            'taxable' => 'boolean',
            'tax_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        $validated['is_active'] = true;
        $validated['taxable'] = $request->has('taxable');
        $validated['tax_percentage'] = $validated['taxable'] ? ($validated['tax_percentage'] ?? 0) : 0;

        Service::create($validated);

        return redirect()->route('services.index')->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        return view('billing.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'service_code' => 'required|unique:services,service_code,' . $service->id,
            'service_name' => 'required',
            'category' => 'required|in:consultation,laboratory,radiology,procedure,pharmacy,room_charge,nursing_care,emergency,surgery,other',
            'department' => 'nullable',
            'description' => 'nullable',
            'standard_charge' => 'required|numeric|min:0',
            'taxable' => 'boolean',
            'tax_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        $validated['taxable'] = $request->has('taxable');
        $validated['tax_percentage'] = $validated['taxable'] ? ($validated['tax_percentage'] ?? 0) : 0;

        $service->update($validated);

        return redirect()->route('services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('services.index')->with('success', 'Service deleted successfully.');
    }

    public function toggleStatus(Service $service)
    {
        $service->is_active = !$service->is_active;
        $service->save();

        $status = $service->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Service {$status} successfully.");
    }
}
