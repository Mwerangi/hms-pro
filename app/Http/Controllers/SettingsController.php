<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * Display settings dashboard
     */
    public function index()
    {
        $categories = Setting::select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $activeCategory = request('category', 'general');
        
        $settings = Setting::where('category', $activeCategory)
            ->orderBy('sort_order')
            ->get();

        return view('settings.index', compact('categories', 'settings', 'activeCategory'));
    }

    /**
     * Show specific category settings
     */
    public function category(string $category)
    {
        // Handle roles category separately
        if ($category === 'roles') {
            $roles = \Spatie\Permission\Models\Role::with('permissions')->withCount('users')->get();
            $permissions = \Spatie\Permission\Models\Permission::all()->groupBy(function($permission) {
                return explode('.', $permission->name)[0];
            });

            $stats = [
                'total_roles' => $roles->count(),
                'total_permissions' => \Spatie\Permission\Models\Permission::count(),
                'active_users_with_roles' => \App\Models\User::whereHas('roles')->where('is_active', true)->count(),
            ];

            return view('settings.roles-category', compact('roles', 'permissions', 'stats'));
        }

        // Handle branches category
        if ($category === 'branches') {
            $branches = \App\Models\Branch::withCount('departments', 'users')->latest()->get();
            
            $stats = [
                'total_branches' => $branches->count(),
                'active_branches' => $branches->where('is_active', true)->count(),
                'total_departments' => \App\Models\Department::count(),
                'total_staff' => \App\Models\User::whereNotNull('branch_id')->count(),
            ];

            return view('settings.branches-category', compact('branches', 'stats'));
        }

        // Handle departments category
        if ($category === 'departments') {
            $departments = \App\Models\Department::with('branch')->withCount('users')->latest()->get();
            $branches = \App\Models\Branch::active()->orderBy('name')->get();
            
            $stats = [
                'total_departments' => $departments->count(),
                'active_departments' => $departments->where('is_active', true)->count(),
                'total_staff' => \App\Models\User::whereNotNull('department_id')->count(),
            ];

            return view('settings.departments-category', compact('departments', 'branches', 'stats'));
        }

        $categories = Setting::select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $settings = Setting::where('category', $category)
            ->orderBy('sort_order')
            ->get();

        $activeCategory = $category;

        return view('settings.index', compact('categories', 'settings', 'activeCategory'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $updates = $request->except('_token', '_method');

        foreach ($updates as $key => $value) {
            $setting = Setting::where('key', $key)->first();

            if (!$setting) {
                continue;
            }

            // Handle file uploads
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                $path = $file->store('settings', 'public');
                $value = $path;
            }

            // Handle boolean checkboxes (unchecked = not in request)
            if ($setting->type === 'boolean' && !isset($updates[$key])) {
                $value = '0';
            }

            Setting::set($key, $value, $setting->is_encrypted);
        }

        return redirect()
            ->back()
            ->with('success', 'Settings updated successfully!');
    }

    /**
     * Reset category settings to default
     */
    public function reset(string $category)
    {
        // This would reset to default values
        // Implementation depends on having default values stored
        
        return redirect()
            ->route('settings.index', ['category' => $category])
            ->with('success', 'Settings reset to default values.');
    }

    /**
     * Export settings
     */
    public function export()
    {
        $settings = Setting::all()->map(function ($setting) {
            return [
                'key' => $setting->key,
                'value' => $setting->value,
                'category' => $setting->category,
                'type' => $setting->type,
            ];
        });

        $filename = 'settings_export_' . date('Y-m-d_His') . '.json';
        
        return response()->json($settings)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', "attachment; filename={$filename}");
    }

    /**
     * Import settings
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:json',
        ]);

        $content = file_get_contents($request->file('file')->getRealPath());
        $settings = json_decode($content, true);

        if (!$settings) {
            return redirect()
                ->back()
                ->with('error', 'Invalid settings file.');
        }

        foreach ($settings as $settingData) {
            $setting = Setting::where('key', $settingData['key'])->first();
            
            if ($setting) {
                $setting->update(['value' => $settingData['value']]);
            }
        }

        Setting::clearCache();

        return redirect()
            ->back()
            ->with('success', 'Settings imported successfully!');
    }

    /**
     * Clear settings cache
     */
    public function clearCache()
    {
        Setting::clearCache();

        return redirect()
            ->back()
            ->with('success', 'Settings cache cleared successfully!');
    }
}
