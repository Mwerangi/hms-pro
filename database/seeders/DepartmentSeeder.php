<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Branch;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the main hospital branch
        $mainBranch = Branch::where('code', 'HQ')->first();

        $departments = [
            [
                'name' => 'Emergency Department',
                'code' => 'ER',
                'description' => 'Emergency and urgent care services',
                'branch_id' => $mainBranch?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Cardiology',
                'code' => 'CARD',
                'description' => 'Heart and cardiovascular care',
                'branch_id' => $mainBranch?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Pediatrics',
                'code' => 'PED',
                'description' => 'Children healthcare services',
                'branch_id' => $mainBranch?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Obstetrics & Gynecology',
                'code' => 'OBGYN',
                'description' => 'Women health and maternity services',
                'branch_id' => $mainBranch?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Surgery',
                'code' => 'SURG',
                'description' => 'Surgical procedures and operations',
                'branch_id' => $mainBranch?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Radiology',
                'code' => 'RAD',
                'description' => 'Medical imaging and diagnostics',
                'branch_id' => $mainBranch?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Laboratory',
                'code' => 'LAB',
                'description' => 'Clinical laboratory services',
                'branch_id' => $mainBranch?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Pharmacy',
                'code' => 'PHARM',
                'description' => 'Medication dispensing and management',
                'branch_id' => $mainBranch?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Outpatient Department',
                'code' => 'OPD',
                'description' => 'Outpatient consultations and services',
                'branch_id' => $mainBranch?->id,
                'is_active' => true,
            ],
            [
                'name' => 'Inpatient Department',
                'code' => 'IPD',
                'description' => 'Inpatient wards and care',
                'branch_id' => $mainBranch?->id,
                'is_active' => true,
            ],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
