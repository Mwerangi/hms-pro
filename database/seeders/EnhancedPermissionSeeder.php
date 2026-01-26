<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class EnhancedPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define all permissions by module
        $permissionsByModule = [
            'Patient Management' => [
                'patients.view',
                'patients.view-details',
                'patients.create',
                'patients.edit',
                'patients.delete',
                'patients.toggle-status',
            ],
            
            'Appointment Management' => [
                'appointments.view-all',
                'appointments.view-own',
                'appointments.create',
                'appointments.edit',
                'appointments.cancel',
                'appointments.checkin',
                'appointments.start-consultation',
                'appointments.complete',
                'appointments.reopen',
            ],
            
            'Nursing Station' => [
                'nursing.view-dashboard',
                'nursing.record-vitals',
                'nursing.checkin-patients',
                'nursing.emergency-registration',
            ],
            
            'OPD/Consultation' => [
                'consultations.view-all',
                'consultations.view-own',
                'consultations.create',
                'consultations.edit',
                'consultations.prescribe',
                'consultations.order-labs',
                'consultations.admit-patient',
                'consultations.complete',
                'consultations.resume',
            ],
            
            'Laboratory' => [
                'lab.view-dashboard',
                'lab.view-orders',
                'lab.collect-sample',
                'lab.process-test',
                'lab.enter-results',
                'lab.approve-results',
                'lab.cancel-order',
                'lab.download-results',
            ],
            
            'Radiology' => [
                'radiology.view-dashboard',
                'radiology.view-orders',
                'radiology.process-imaging',
                'radiology.enter-report',
                'radiology.upload-images',
                'radiology.approve-report',
            ],
            
            'Pharmacy' => [
                'pharmacy.view-dashboard',
                'pharmacy.view-prescriptions',
                'pharmacy.dispense',
                'pharmacy.mark-emergency',
                'pharmacy.cancel-prescription',
                'pharmacy.manage-inventory',
            ],
            
            'IPD Management' => [
                'ipd.view-dashboard',
                'ipd.view-admissions',
                'ipd.create-admission',
                'ipd.edit-admission',
                'ipd.transfer-patient',
                'ipd.discharge-patient',
            ],
            
            'Ward Management' => [
                'wards.view',
                'wards.create',
                'wards.edit',
                'wards.manage-status',
                'wards.assign-nurse',
            ],
            
            'Bed Management' => [
                'beds.view',
                'beds.create',
                'beds.edit',
                'beds.manage-status',
            ],
            
            'Billing & Accounting' => [
                'billing.view-dashboard',
                'billing.view-services',
                'billing.manage-services',
                'billing.view-charges',
                'billing.create-charge',
                'billing.view-bills',
                'billing.create-bill',
                'billing.edit-bill',
                'billing.process-payment',
                'billing.print-receipt',
                'billing.view-reports',
            ],
            
            'User Management' => [
                'users.view',
                'users.create',
                'users.edit',
                'users.delete',
                'users.manage-roles',
                'users.manage-permissions',
                'users.toggle-status',
            ],
            
            'System Settings' => [
                'settings.view',
                'settings.manage-roles',
                'settings.view-logs',
                'settings.manage-system',
            ],
        ];

        // Create all permissions
        foreach ($permissionsByModule as $module => $permissions) {
            foreach ($permissions as $permission) {
                Permission::firstOrCreate([
                    'name' => $permission,
                    'guard_name' => 'web'
                ]);
            }
        }

        $this->command->info('Enhanced permissions created successfully!');
        
        // Now update role assignments
        $this->assignPermissionsToRoles();
    }

    private function assignPermissionsToRoles()
    {
        // Super Admin - ALL PERMISSIONS
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdmin->syncPermissions(Permission::all());

        // Admin - Hospital Management
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->syncPermissions([
            // Patients
            'patients.view', 'patients.view-details', 'patients.create', 'patients.edit', 'patients.toggle-status',
            
            // Appointments
            'appointments.view-all', 'appointments.create', 'appointments.edit', 'appointments.cancel', 
            'appointments.checkin', 'appointments.complete', 'appointments.reopen',
            
            // OPD
            'consultations.view-all',
            
            // IPD
            'ipd.view-dashboard', 'ipd.view-admissions', 'ipd.create-admission', 'ipd.edit-admission',
            'ipd.transfer-patient', 'ipd.discharge-patient',
            'wards.view', 'wards.create', 'wards.edit', 'wards.manage-status', 'wards.assign-nurse',
            'beds.view', 'beds.create', 'beds.edit', 'beds.manage-status',
            
            // Lab & Radiology
            'lab.view-dashboard', 'lab.view-orders', 'lab.download-results',
            'radiology.view-dashboard', 'radiology.view-orders',
            
            // Pharmacy
            'pharmacy.view-dashboard', 'pharmacy.view-prescriptions',
            
            // Billing
            'billing.view-dashboard', 'billing.view-services', 'billing.manage-services',
            'billing.view-charges', 'billing.view-bills', 'billing.create-bill', 
            'billing.process-payment', 'billing.view-reports',
            
            // Users
            'users.view', 'users.create', 'users.edit', 'users.manage-roles', 'users.toggle-status',
            
            // Settings
            'settings.view', 'settings.view-logs',
        ]);

        // Doctor - Medical Operations
        $doctor = Role::firstOrCreate(['name' => 'Doctor']);
        $doctor->syncPermissions([
            // Patients
            'patients.view', 'patients.view-details', 'patients.edit',
            
            // Appointments
            'appointments.view-own', 'appointments.start-consultation', 'appointments.complete',
            
            // Consultations
            'consultations.view-own', 'consultations.create', 'consultations.edit', 
            'consultations.prescribe', 'consultations.order-labs', 'consultations.admit-patient',
            'consultations.complete', 'consultations.resume',
            
            // Lab & Radiology
            'lab.view-orders', 'lab.download-results',
            'radiology.view-orders',
            
            // Pharmacy
            'pharmacy.view-prescriptions',
            
            // IPD
            'ipd.view-admissions', 'ipd.discharge-patient',
            
            // Billing
            'billing.view-charges', 'billing.view-bills',
        ]);

        // Nurse - Nursing Operations
        $nurse = Role::firstOrCreate(['name' => 'Nurse']);
        $nurse->syncPermissions([
            // Patients
            'patients.view', 'patients.view-details', 'patients.edit',
            
            // Appointments
            'appointments.view-all', 'appointments.checkin',
            
            // Nursing
            'nursing.view-dashboard', 'nursing.record-vitals', 'nursing.checkin-patients', 
            'nursing.emergency-registration',
            
            // Consultations
            'consultations.view-all',
            
            // Lab & Radiology
            'lab.view-orders',
            'radiology.view-orders',
            
            // Pharmacy
            'pharmacy.view-prescriptions',
            
            // IPD
            'ipd.view-dashboard', 'ipd.view-admissions', 'ipd.transfer-patient',
            'wards.view', 'beds.view',
        ]);

        // Receptionist - Front Desk
        $receptionist = Role::firstOrCreate(['name' => 'Receptionist']);
        $receptionist->syncPermissions([
            // Patients
            'patients.view', 'patients.view-details', 'patients.create', 'patients.edit', 'patients.toggle-status',
            
            // Appointments
            'appointments.view-all', 'appointments.create', 'appointments.edit', 
            'appointments.cancel', 'appointments.checkin',
            
            // Billing
            'billing.view-dashboard', 'billing.view-bills', 'billing.create-bill', 
            'billing.process-payment', 'billing.print-receipt',
        ]);

        // Pharmacist - Pharmacy Operations
        $pharmacist = Role::firstOrCreate(['name' => 'Pharmacist']);
        $pharmacist->syncPermissions([
            // Patients
            'patients.view',
            
            // Pharmacy
            'pharmacy.view-dashboard', 'pharmacy.view-prescriptions', 'pharmacy.dispense',
            'pharmacy.mark-emergency', 'pharmacy.cancel-prescription', 'pharmacy.manage-inventory',
            
            // Billing
            'billing.view-charges',
        ]);

        // Lab Technician - Laboratory Operations
        $labTech = Role::firstOrCreate(['name' => 'Lab Technician']);
        $labTech->syncPermissions([
            // Patients
            'patients.view',
            
            // Laboratory
            'lab.view-dashboard', 'lab.view-orders', 'lab.collect-sample', 
            'lab.process-test', 'lab.enter-results',
        ]);

        // Radiologist - Radiology Operations
        $radiologist = Role::firstOrCreate(['name' => 'Radiologist']);
        $radiologist->syncPermissions([
            // Patients
            'patients.view',
            
            // Radiology
            'radiology.view-dashboard', 'radiology.view-orders', 'radiology.process-imaging',
            'radiology.enter-report', 'radiology.upload-images', 'radiology.approve-report',
            
            // Lab (for cross-verification)
            'lab.approve-results',
        ]);

        // Accountant - Billing & Finance
        $accountant = Role::firstOrCreate(['name' => 'Accountant']);
        $accountant->syncPermissions([
            // Patients
            'patients.view', 'patients.view-details',
            
            // Billing
            'billing.view-dashboard', 'billing.view-services', 'billing.manage-services',
            'billing.view-charges', 'billing.create-charge', 'billing.view-bills', 
            'billing.create-bill', 'billing.edit-bill', 'billing.process-payment', 
            'billing.print-receipt', 'billing.view-reports',
        ]);

        $this->command->info('Role permissions assigned successfully!');
    }
}
