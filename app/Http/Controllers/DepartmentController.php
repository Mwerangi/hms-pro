<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Branch;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return redirect()->route('settings.category', ['category' => 'departments']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $branches = Branch::active()->orderBy('name')->get();
        return view('settings.departments.create', compact('branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:departments,code',
            'description' => 'nullable|string',
            'branch_id' => 'nullable|exists:branches,id',
            'is_active' => 'boolean'
        ]);

        Department::create($validated);

        return redirect()->route('settings.category', ['category' => 'departments'])
            ->with('success', 'Department created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        $department->load(['branch', 'users']);
        return view('settings.departments.show', compact('department'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        $branches = Branch::active()->orderBy('name')->get();
        return view('settings.departments.edit', compact('department', 'branches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:departments,code,' . $department->id,
            'description' => 'nullable|string',
            'branch_id' => 'nullable|exists:branches,id',
            'is_active' => 'boolean'
        ]);

        $department->update($validated);

        return redirect()->route('settings.category', ['category' => 'departments'])
            ->with('success', 'Department updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        // Check if department has users
        if ($department->users()->count() > 0) {
            return redirect()->route('settings.category', ['category' => 'departments'])
                ->with('error', 'Cannot delete department with associated users.');
        }

        $department->delete();

        return redirect()->route('settings.category', ['category' => 'departments'])
            ->with('success', 'Department deleted successfully.');
    }
}
