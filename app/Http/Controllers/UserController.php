<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::with(['roles', 'branch', 'department']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Role filter
        if ($request->filled('role')) {
            $query->role($request->role);
        }

        // Status filter
        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        $users = $query->latest()->paginate(15);
        $roles = Role::all();

        // Statistics
        $stats = [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'inactive' => User::where('is_active', false)->count(),
            'new_this_month' => User::whereMonth('created_at', now()->month)->count(),
        ];

        return view('users.index', compact('users', 'roles', 'stats'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $roles = Role::all();
        $branches = \App\Models\Branch::active()->orderBy('name')->get();
        $departments = \App\Models\Department::active()->orderBy('name')->get();
        return view('users.create', compact('roles', 'branches', 'departments'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|exists:roles,name',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'employee_id' => 'nullable|string|max:50|unique:users,employee_id',
            'branch_id' => 'nullable|exists:branches,id',
            'department_id' => 'nullable|exists:departments,id',
            'specialization' => 'nullable|string|max:100',
            'license_number' => 'nullable|string|max:100',
            'gender' => 'nullable|in:male,female,other',
            'date_of_joining' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'employee_id' => $validated['employee_id'] ?? null,
            'branch_id' => $validated['branch_id'] ?? null,
            'department_id' => $validated['department_id'] ?? null,
            'specialization' => $validated['specialization'] ?? null,
            'license_number' => $validated['license_number'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'date_of_joining' => $validated['date_of_joining'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully!');
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        $user->load(['roles', 'permissions', 'branch', 'department']);
        
        // Get all permissions from user's role(s)
        $rolePermissions = $user->getAllPermissions()->groupBy(function($permission) {
            if (strpos($permission->name, '.') !== false) {
                return explode('.', $permission->name)[0];
            }
            return 'other';
        });
        
        return view('users.show', compact('user', 'rolePermissions'));
    }

    /**
     * Show the form for editing the user
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $branches = \App\Models\Branch::active()->orderBy('name')->get();
        $departments = \App\Models\Department::active()->orderBy('name')->get();
        $user->load('roles');
        return view('users.edit', compact('user', 'roles', 'branches', 'departments'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'role' => 'required|exists:roles,name',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'employee_id' => 'nullable|string|max:50|unique:users,employee_id,' . $user->id,
            'branch_id' => 'nullable|exists:branches,id',
            'department_id' => 'nullable|exists:departments,id',
            'specialization' => 'nullable|string|max:100',
            'license_number' => 'nullable|string|max:100',
            'gender' => 'nullable|in:male,female,other',
            'date_of_joining' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'employee_id' => $validated['employee_id'] ?? null,
            'branch_id' => $validated['branch_id'] ?? null,
            'department_id' => $validated['department_id'] ?? null,
            'specialization' => $validated['specialization'] ?? null,
            'license_number' => $validated['license_number'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'date_of_joining' => $validated['date_of_joining'] ?? null,
            'is_active' => $request->has('is_active'),
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $user->update($userData);
        $user->syncRoles([$validated['role']]);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account!');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully!');
    }

    /**
     * Toggle user status
     */
    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "User {$status} successfully!");
    }
}
