<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            // Patient Management
            'view-patients',
            'create-patients',
            'edit-patients',
            'delete-patients',
            
            // Appointment Management
            'view-appointments',
            'create-appointments',
            'edit-appointments',
            'cancel-appointments',
            
            // OPD Management
            'view-opd',
            'create-consultation',
            'view-consultation',
            'create-prescription',
            
            // IPD Management
            'view-ipd',
            'admit-patient',
            'discharge-patient',
            'transfer-patient',
            
            // Laboratory
            'view-lab-tests',
            'create-lab-test',
            'enter-lab-results',
            'approve-lab-results',
            
            // Pharmacy
            'view-pharmacy',
            'dispense-medicine',
            'manage-inventory',
            'view-stock',
            
            // Billing
            'view-billing',
            'create-invoice',
            'process-payment',
            'view-reports',
            
            // User Management
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            
            // Settings
            'manage-settings',
            'view-audit-logs',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create Roles and Assign Permissions

        // 1. Super Admin - Full Access
        $superAdmin = Role::create(['name' => 'super-admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // 2. Admin - Hospital Management
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo([
            'view-patients', 'create-patients', 'edit-patients',
            'view-appointments', 'create-appointments', 'edit-appointments', 'cancel-appointments',
            'view-opd', 'view-ipd',
            'view-lab-tests', 'view-pharmacy', 'view-stock',
            'view-billing', 'create-invoice', 'view-reports',
            'view-users', 'create-users', 'edit-users',
            'manage-settings', 'view-audit-logs',
        ]);

        // 3. Doctor - Medical Staff
        $doctor = Role::create(['name' => 'doctor']);
        $doctor->givePermissionTo([
            'view-patients', 'edit-patients',
            'view-appointments',
            'view-opd', 'create-consultation', 'view-consultation', 'create-prescription',
            'view-ipd', 'discharge-patient',
            'view-lab-tests', 'create-lab-test',
            'view-pharmacy',
        ]);

        // 4. Nurse - Nursing Staff
        $nurse = Role::create(['name' => 'nurse']);
        $nurse->givePermissionTo([
            'view-patients', 'edit-patients',
            'view-appointments',
            'view-opd', 'view-consultation',
            'view-ipd', 'transfer-patient',
            'view-lab-tests',
            'view-pharmacy',
        ]);

        // 5. Receptionist - Front Desk
        $receptionist = Role::create(['name' => 'receptionist']);
        $receptionist->givePermissionTo([
            'view-patients', 'create-patients', 'edit-patients',
            'view-appointments', 'create-appointments', 'edit-appointments', 'cancel-appointments',
            'view-billing', 'create-invoice', 'process-payment',
        ]);

        // 6. Pharmacist - Pharmacy Operations
        $pharmacist = Role::create(['name' => 'pharmacist']);
        $pharmacist->givePermissionTo([
            'view-patients',
            'view-pharmacy', 'dispense-medicine', 'manage-inventory', 'view-stock',
        ]);

        // 7. Lab Technician - Laboratory
        $labTech = Role::create(['name' => 'lab-technician']);
        $labTech->givePermissionTo([
            'view-patients',
            'view-lab-tests', 'enter-lab-results',
        ]);

        // 8. Radiologist - Radiology
        $radiologist = Role::create(['name' => 'radiologist']);
        $radiologist->givePermissionTo([
            'view-patients',
            'view-lab-tests', 'enter-lab-results', 'approve-lab-results',
        ]);

        // 9. Accountant - Billing & Finance
        $accountant = Role::create(['name' => 'accountant']);
        $accountant->givePermissionTo([
            'view-patients',
            'view-billing', 'create-invoice', 'process-payment', 'view-reports',
        ]);

        // Create Default Super Admin User
        $superAdminUser = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@hms.com',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
        ]);
        $superAdminUser->assignRole('super-admin');

        // Create Sample Doctor
        $doctorUser = User::create([
            'name' => 'Dr. Sarah Johnson',
            'email' => 'doctor@hms.com',
            'password' => Hash::make('doctor123'),
            'email_verified_at' => now(),
        ]);
        $doctorUser->assignRole('doctor');

        // Create Sample Receptionist
        $receptionistUser = User::create([
            'name' => 'John Receptionist',
            'email' => 'receptionist@hms.com',
            'password' => Hash::make('receptionist123'),
            'email_verified_at' => now(),
        ]);
        $receptionistUser->assignRole('receptionist');

        // Create Sample Pharmacist
        $pharmacistUser = User::create([
            'name' => 'Mary Pharmacist',
            'email' => 'pharmacist@hms.com',
            'password' => Hash::make('pharmacist123'),
            'email_verified_at' => now(),
        ]);
        $pharmacistUser->assignRole('pharmacist');

        $this->command->info('Roles and permissions seeded successfully!');
        $this->command->info('Default users created:');
        $this->command->info('Super Admin: admin@hms.com / admin123');
        $this->command->info('Doctor: doctor@hms.com / doctor123');
        $this->command->info('Receptionist: receptionist@hms.com / receptionist123');
        $this->command->info('Pharmacist: pharmacist@hms.com / pharmacist123');
    }
}