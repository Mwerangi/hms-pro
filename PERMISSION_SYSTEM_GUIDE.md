# Permission System Implementation Guide

## Overview
This HMS system now has a complete Role-Based Access Control (RBAC) system using Spatie Laravel Permission package. The system includes 137+ granular permissions across 10 major modules.

## System Architecture

### Roles
The system includes the following predefined roles:
- **Super Admin** - Full system access (protected)
- **Admin** - Administrative access (protected)
- **Doctor** - Medical staff access (protected)
- **Nurse** - Nursing access (protected)
- **Receptionist** - Front desk access (protected)
- **Pharmacist** - Pharmacy operations
- **Lab Technician** - Laboratory operations
- **Radiologist** - Radiology operations
- **Accountant** - Billing & financial operations

### Permission Structure
Permissions follow the `module.action` naming convention:
- `patients.view` - View patient records
- `patients.create` - Create new patients
- `appointments.manage` - Manage appointments
- `lab.enter-results` - Enter lab results
- `pharmacy.dispense` - Dispense medications

## Accessing the Role Management System

### Web Interface
Navigate to: **Settings → Roles & Permissions**

Or directly access:
- `/settings/roles` - Role management dashboard
- `/settings/roles/create` - Create new role
- `/settings/roles/{role}` - View role details
- `/settings/roles/{role}/edit` - Edit role permissions
- `/settings/permissions` - View all system permissions

### Features
1. **Role Dashboard** - View all roles with statistics
2. **Create Role** - Create custom roles with permission assignment
3. **Edit Role** - Modify role permissions (protected roles cannot be deleted)
4. **View Permissions** - Browse all 137+ system permissions organized by module
5. **User Management** - See which users have specific roles

## Using Permissions in Code

### 1. Protecting Routes with Middleware

#### Single Permission
```php
Route::get('/patients', [PatientController::class, 'index'])
    ->middleware('permission:patients.view');
```

#### Multiple Permissions (OR logic)
```php
Route::get('/appointments', [AppointmentController::class, 'index'])
    ->middleware('permission:appointments.view|appointments.manage');
```

#### Group Protection
```php
Route::middleware(['auth', 'permission:patients.view'])->group(function () {
    Route::get('/patients', [PatientController::class, 'index']);
    Route::get('/patients/{patient}', [PatientController::class, 'show']);
});
```

### 2. Checking Permissions in Controllers

#### Check Single Permission
```php
public function index()
{
    if (!auth()->user()->hasPermissionTo('patients.view')) {
        abort(403, 'Unauthorized');
    }
    
    // Your code here
}
```

#### Check Multiple Permissions (OR)
```php
public function index()
{
    if (!auth()->user()->hasAnyPermission(['patients.view', 'patients.manage'])) {
        abort(403, 'Unauthorized');
    }
    
    // Your code here
}
```

#### Check Multiple Permissions (AND)
```php
public function delete($id)
{
    if (!auth()->user()->hasAllPermissions(['patients.view', 'patients.delete'])) {
        abort(403, 'Unauthorized');
    }
    
    // Your code here
}
```

### 3. Blade Template Directives

#### Check Permission in Views
```blade
@can('patients.create')
    <a href="{{ route('patients.create') }}" class="btn btn-primary">
        Create Patient
    </a>
@endcan
```

#### Check Multiple Permissions
```blade
@canany(['patients.edit', 'patients.delete'])
    <div class="actions">
        @can('patients.edit')
            <a href="{{ route('patients.edit', $patient) }}">Edit</a>
        @endcan
        
        @can('patients.delete')
            <form action="{{ route('patients.destroy', $patient) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">Delete</button>
            </form>
        @endcan
    </div>
@endcanany
```

#### Check Role
```blade
@role('Doctor')
    <p>Welcome, Doctor!</p>
@endrole

@hasrole('Admin|Super Admin')
    <a href="/admin">Admin Panel</a>
@endhasrole
```

### 4. Programmatic Permission Assignment

#### Assign Permission to User
```php
$user = User::find(1);
$user->givePermissionTo('patients.view');
$user->givePermissionTo(['patients.view', 'patients.create']);
```

#### Remove Permission from User
```php
$user->revokePermissionTo('patients.view');
```

#### Assign Role to User
```php
$user->assignRole('Doctor');
$user->assignRole(['Doctor', 'Nurse']);
```

#### Sync Permissions to Role (replace all)
```php
$role = Role::findByName('Doctor');
$role->syncPermissions(['patients.view', 'appointments.manage']);
```

#### Give Permission to Role
```php
$role = Role::findByName('Nurse');
$role->givePermissionTo('patients.view');
```

## Complete Permission List by Module

### Patient Management Module
- `patients.view` - View patient records
- `patients.create` - Register new patients
- `patients.edit` - Edit patient information
- `patients.delete` - Delete patient records
- `patients.view-billing` - View patient billing
- `patients.view-history` - View medical history
- `patients.export` - Export patient data

### Appointments Module
- `appointments.view` - View appointments
- `appointments.create` - Create appointments
- `appointments.edit` - Edit appointments
- `appointments.cancel` - Cancel appointments
- `appointments.manage` - Manage all appointments

### Nursing Module
- `nursing.view-patients` - View nursing patients
- `nursing.vitals` - Record vitals
- `nursing.medications` - Administer medications
- `nursing.care-plans` - Manage care plans

### OPD (Consultations) Module
- `consultations.view` - View consultations
- `consultations.create` - Create consultations
- `consultations.edit` - Edit consultations
- `consultations.prescribe` - Write prescriptions
- `consultations.refer` - Refer patients

### Laboratory Module
- `lab.view-orders` - View lab orders
- `lab.receive-samples` - Receive samples
- `lab.enter-results` - Enter test results
- `lab.approve-results` - Approve results
- `lab.generate-reports` - Generate reports

### Radiology Module
- `radiology.view-orders` - View imaging orders
- `radiology.schedule` - Schedule imaging
- `radiology.upload-images` - Upload images
- `radiology.report` - Create reports

### Pharmacy Module
- `pharmacy.view-prescriptions` - View prescriptions
- `pharmacy.dispense` - Dispense medications
- `pharmacy.inventory` - Manage inventory
- `pharmacy.stock-in` - Add stock
- `pharmacy.stock-out` - Remove stock

### IPD (Inpatient) Module
- `ipd.view-admissions` - View admissions
- `ipd.admit-patient` - Admit patients
- `ipd.discharge` - Discharge patients
- `ipd.transfer` - Transfer patients
- `ipd.bed-management` - Manage beds

### Billing Module
- `billing.view` - View bills
- `billing.create` - Create bills
- `billing.payments` - Process payments
- `billing.refunds` - Process refunds
- `billing.discounts` - Apply discounts

### User Management Module
- `users.view` - View users
- `users.create` - Create users
- `users.edit` - Edit users
- `users.delete` - Delete users
- `users.manage-roles` - Assign roles
- `users.reset-password` - Reset passwords

## Example: Protecting a Complete Controller

```php
<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function __construct()
    {
        // Apply permission middleware to all methods
        $this->middleware('permission:patients.view')->only(['index', 'show']);
        $this->middleware('permission:patients.create')->only(['create', 'store']);
        $this->middleware('permission:patients.edit')->only(['edit', 'update']);
        $this->middleware('permission:patients.delete')->only(['destroy']);
    }

    public function index()
    {
        $patients = Patient::paginate(20);
        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        // Validation and creation logic
    }

    // ... other methods
}
```

## Best Practices

1. **Always Use Permissions, Not Roles** - Check permissions in code, not roles
   ```php
   // ❌ Bad
   if ($user->hasRole('Doctor')) { }
   
   // ✅ Good
   if ($user->hasPermissionTo('consultations.create')) { }
   ```

2. **Protect Both Routes and Controllers** - Defense in depth
   ```php
   // Route protection
   Route::post('/patients', [PatientController::class, 'store'])
       ->middleware('permission:patients.create');
   
   // Controller protection
   public function store(Request $request)
   {
       $this->authorize('create', Patient::class);
       // ...
   }
   ```

3. **Use Descriptive Permission Names** - Follow the module.action convention
   ```php
   // ✅ Good
   'patients.view', 'lab.enter-results', 'pharmacy.dispense'
   
   // ❌ Bad
   'view', 'edit', 'delete'
   ```

4. **Group Related Permissions** - Use middleware groups for related routes
   ```php
   Route::prefix('patients')->middleware('permission:patients.view')->group(function () {
       Route::get('/', [PatientController::class, 'index']);
       Route::get('/{patient}', [PatientController::class, 'show']);
   });
   ```

5. **Test Permission Checks** - Always test permission scenarios
   ```php
   public function test_user_cannot_create_patient_without_permission()
   {
       $user = User::factory()->create();
       
       $response = $this->actingAs($user)
           ->post('/patients', $patientData);
       
       $response->assertStatus(403);
   }
   ```

## Seeding Permissions

The `EnhancedPermissionSeeder` creates all 137+ permissions and assigns them to roles:

```bash
php artisan db:seed --class=EnhancedPermissionSeeder
```

This will:
1. Create all module permissions
2. Assign permissions to appropriate roles
3. Protect system roles (Super Admin, Admin, etc.)

## Troubleshooting

### Permission not working
1. Clear permission cache: `php artisan permission:cache-reset`
2. Check if user has the role: `$user->roles`
3. Check if role has the permission: `$role->permissions`

### User has permission but still unauthorized
1. Verify middleware is registered in `bootstrap/app.php`
2. Check route middleware order (auth before permission)
3. Clear application cache: `php artisan cache:clear`

### Custom permissions not appearing
1. Run seeder again: `php artisan db:seed --class=EnhancedPermissionSeeder`
2. Or create manually:
   ```php
   Permission::create(['name' => 'custom.permission', 'guard_name' => 'web']);
   ```

## Summary

The HMS permission system provides:
- ✅ 137+ granular permissions across 10 modules
- ✅ 9 predefined roles with appropriate permissions
- ✅ Web-based role management interface
- ✅ Middleware for route protection
- ✅ Blade directives for view-level checks
- ✅ Protected system roles to prevent accidental deletion
- ✅ Comprehensive documentation and examples

For more information, visit the Spatie Laravel Permission documentation: https://spatie.be/docs/laravel-permission
