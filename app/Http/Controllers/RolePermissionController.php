<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class RolePermissionController extends Controller
{
    /**
     * Display role management dashboard
     */
    public function index()
    {
        $roles = Role::with('permissions')->withCount('users')->get();
        $permissions = Permission::all()->groupBy(function($permission) {
            return $this->getPermissionModule($permission->name);
        });

        $stats = [
            'total_roles' => $roles->count(),
            'total_permissions' => Permission::count(),
            'active_users_with_roles' => \App\Models\User::whereHas('roles')->where('is_active', true)->count(),
        ];

        return view('settings.roles.index', compact('roles', 'permissions', 'stats'));
    }

    /**
     * Display all permissions
     */
    public function permissions()
    {
        $permissions = Permission::all()->groupBy(function($permission) {
            return $this->getPermissionModule($permission->name);
        });

        return view('settings.permissions.index', compact('permissions'));
    }

    /**
     * Show form to create new role
     */
    public function create()
    {
        $permissions = Permission::all()->groupBy(function($permission) {
            return $this->getPermissionModule($permission->name);
        });

        return view('settings.roles.create', compact('permissions'));
    }

    /**
     * Store new role
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name|max:255',
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
        ]);

        if (!empty($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return redirect()->route('settings.category', ['category' => 'roles'])
            ->with('success', "Role '{$role->name}' created successfully!");
    }

    /**
     * Show specific role details
     */
    public function show(Role $role)
    {
        $role->load('permissions', 'users');
        
        $permissions = Permission::all()->groupBy(function($permission) {
            return $this->getPermissionModule($permission->name);
        });

        return view('settings.roles.show', compact('role', 'permissions'));
    }

    /**
     * Show form to edit role
     */
    public function edit(Role $role)
    {
        // Prevent editing Super Admin role
        if ($role->name === 'Super Admin') {
            return back()->with('error', 'Super Admin role cannot be modified.');
        }

        $permissions = Permission::all()->groupBy(function($permission) {
            return $this->getPermissionModule($permission->name);
        });

        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('settings.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update role
     */
    public function update(Request $request, Role $role)
    {
        // Prevent editing Super Admin role
        if ($role->name === 'Super Admin') {
            return back()->with('error', 'Super Admin role cannot be modified.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->update([
            'name' => $validated['name'],
        ]);

        // Sync permissions
        if (isset($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        } else {
            $role->syncPermissions([]);
        }

        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('settings.roles.show', $role)
            ->with('success', "Role '{$role->name}' updated successfully!");
    }

    /**
     * Delete role
     */
    public function destroy(Role $role)
    {
        // Prevent deleting protected roles
        $protectedRoles = ['Super Admin', 'Admin', 'Doctor', 'Nurse', 'Receptionist'];
        
        if (in_array($role->name, $protectedRoles)) {
            return back()->with('error', "Protected role '{$role->name}' cannot be deleted.");
        }

        // Check if role has users
        if ($role->users()->count() > 0) {
            return back()->with('error', "Cannot delete role '{$role->name}' because it is assigned to {$role->users()->count()} user(s).");
        }

        $roleName = $role->name;
        $role->delete();

        return redirect()->route('settings.category', ['category' => 'roles'])
            ->with('success', "Role '{$roleName}' deleted successfully!");
    }

    /**
     * Sync permissions for a role (AJAX)
     */
    public function syncPermissions(Request $request, Role $role)
    {
        $validated = $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->syncPermissions($validated['permissions']);

        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return response()->json([
            'success' => true,
            'message' => 'Permissions updated successfully',
            'permissions_count' => count($validated['permissions']),
        ]);
    }

    /**
     * View all permissions
     */
    public function permissionsIndex()
    {
        $permissions = Permission::all()->groupBy(function($permission) {
            return $this->getPermissionModule($permission->name);
        });

        return view('settings.permissions.index', compact('permissions'));
    }

    /**
     * Extract module name from permission name
     * Handles both dot notation (patients.view) and hyphenated format (view-patients)
     */
    private function getPermissionModule($permissionName)
    {
        // Check if it uses dot notation (e.g., patients.view, appointments.create)
        if (strpos($permissionName, '.') !== false) {
            $parts = explode('.', $permissionName);
            $module = $parts[0];
        } else {
            // Handle hyphenated format (e.g., view-patients, create-appointments)
            // Extract the module name from the end
            $parts = explode('-', $permissionName);
            
            // Common action prefixes to remove
            $actionPrefixes = ['view', 'create', 'edit', 'delete', 'manage', 'process', 'cancel', 'approve'];
            
            // If first part is an action, the rest is the module
            if (in_array($parts[0], $actionPrefixes) && count($parts) > 1) {
                array_shift($parts); // Remove the action prefix
                $module = implode('-', $parts);
            } else {
                // Otherwise, just use the last part as module
                $module = end($parts);
            }
        }

        // Normalize and format the module name
        return ucwords(str_replace(['-', '_'], ' ', $module));
    }
}
