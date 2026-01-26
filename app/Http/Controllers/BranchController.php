<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return redirect()->route('settings.category', ['category' => 'branches']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('settings.branches.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:branches,code',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'is_active' => 'boolean'
        ]);

        Branch::create($validated);

        return redirect()->route('settings.category', ['category' => 'branches'])
            ->with('success', 'Branch created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch)
    {
        $branch->load(['departments', 'users']);
        return view('settings.branches.show', compact('branch'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Branch $branch)
    {
        return view('settings.branches.edit', compact('branch'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:branches,code,' . $branch->id,
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'is_active' => 'boolean'
        ]);

        $branch->update($validated);

        return redirect()->route('settings.category', ['category' => 'branches'])
            ->with('success', 'Branch updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        // Check if branch has departments or users
        if ($branch->departments()->count() > 0 || $branch->users()->count() > 0) {
            return redirect()->route('settings.category', ['category' => 'branches'])
                ->with('error', 'Cannot delete branch with associated departments or users.');
        }

        $branch->delete();

        return redirect()->route('settings.category', ['category' => 'branches'])
            ->with('success', 'Branch deleted successfully.');
    }
}
