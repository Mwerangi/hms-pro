<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ward;
use App\Models\Bed;

class WardSeeder extends Seeder
{
    public function run(): void
    {
        // General Ward A
        $ward1 = Ward::create([
            'ward_number' => 'W-001',
            'ward_name' => 'General Ward A',
            'ward_type' => 'general',
            'floor' => '1',
            'building' => 'Main Building',
            'total_beds' => 20,
            'available_beds' => 20,
            'occupied_beds' => 0,
            'description' => 'General medical and surgical ward',
            'nurse_in_charge' => 'Sr. Mary Johnson',
            'contact_number' => '+1234567890',
            'base_charge_per_day' => 1000.00,
            'is_active' => true,
        ]);

        // Create 20 beds for General Ward A
        for ($i = 1; $i <= 20; $i++) {
            Bed::create([
                'ward_id' => $ward1->id,
                'bed_number' => 'B-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'bed_label' => 'Bed ' . $i,
                'bed_type' => 'standard',
                'status' => 'available',
                'has_oxygen' => true,
                'has_ventilator' => false,
                'has_monitor' => false,
                'additional_charge_per_day' => 0,
                'is_active' => true,
            ]);
        }

        // ICU
        $ward2 = Ward::create([
            'ward_number' => 'ICU-001',
            'ward_name' => 'Intensive Care Unit',
            'ward_type' => 'icu',
            'floor' => '2',
            'building' => 'Main Building',
            'total_beds' => 10,
            'available_beds' => 10,
            'occupied_beds' => 0,
            'description' => 'Critical care unit with advanced monitoring',
            'nurse_in_charge' => 'Sr. Patricia Wilson',
            'contact_number' => '+1234567891',
            'base_charge_per_day' => 5000.00,
            'is_active' => true,
        ]);

        // Create 10 ICU beds
        for ($i = 1; $i <= 10; $i++) {
            Bed::create([
                'ward_id' => $ward2->id,
                'bed_number' => 'ICU-' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'bed_label' => 'ICU Bed ' . $i,
                'bed_type' => 'icu',
                'status' => 'available',
                'has_oxygen' => true,
                'has_ventilator' => true,
                'has_monitor' => true,
                'additional_charge_per_day' => 2000.00,
                'is_active' => true,
            ]);
        }

        // Private Ward
        $ward3 = Ward::create([
            'ward_number' => 'PVT-001',
            'ward_name' => 'Private Ward',
            'ward_type' => 'private',
            'floor' => '3',
            'building' => 'Main Building',
            'total_beds' => 15,
            'available_beds' => 15,
            'occupied_beds' => 0,
            'description' => 'Private rooms with attached bathroom',
            'nurse_in_charge' => 'Sr. Linda Brown',
            'contact_number' => '+1234567892',
            'base_charge_per_day' => 3000.00,
            'is_active' => true,
        ]);

        // Create 15 private beds
        for ($i = 1; $i <= 15; $i++) {
            Bed::create([
                'ward_id' => $ward3->id,
                'bed_number' => 'PVT-' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'bed_label' => 'Private Room ' . $i,
                'bed_type' => 'electric',
                'status' => 'available',
                'has_oxygen' => true,
                'has_ventilator' => false,
                'has_monitor' => true,
                'additional_charge_per_day' => 500.00,
                'is_active' => true,
            ]);
        }

        // Semi-Private Ward
        $ward4 = Ward::create([
            'ward_number' => 'SP-001',
            'ward_name' => 'Semi-Private Ward',
            'ward_type' => 'semi-private',
            'floor' => '2',
            'building' => 'Main Building',
            'total_beds' => 12,
            'available_beds' => 12,
            'occupied_beds' => 0,
            'description' => 'Semi-private rooms with 2-3 beds',
            'nurse_in_charge' => 'Sr. Sarah Davis',
            'contact_number' => '+1234567893',
            'base_charge_per_day' => 2000.00,
            'is_active' => true,
        ]);

        // Create 12 semi-private beds
        for ($i = 1; $i <= 12; $i++) {
            Bed::create([
                'ward_id' => $ward4->id,
                'bed_number' => 'SP-' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'bed_label' => 'Semi-Private ' . $i,
                'bed_type' => 'standard',
                'status' => 'available',
                'has_oxygen' => true,
                'has_ventilator' => false,
                'has_monitor' => false,
                'additional_charge_per_day' => 200.00,
                'is_active' => true,
            ]);
        }

        // NICU
        $ward5 = Ward::create([
            'ward_number' => 'NICU-001',
            'ward_name' => 'Neonatal Intensive Care Unit',
            'ward_type' => 'nicu',
            'floor' => '2',
            'building' => 'Maternity Wing',
            'total_beds' => 8,
            'available_beds' => 8,
            'occupied_beds' => 0,
            'description' => 'Specialized care for newborns',
            'nurse_in_charge' => 'Sr. Emma Taylor',
            'contact_number' => '+1234567894',
            'base_charge_per_day' => 6000.00,
            'is_active' => true,
        ]);

        // Create 8 NICU beds
        for ($i = 1; $i <= 8; $i++) {
            Bed::create([
                'ward_id' => $ward5->id,
                'bed_number' => 'NICU-' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'bed_label' => 'NICU Bed ' . $i,
                'bed_type' => 'nicu',
                'status' => 'available',
                'has_oxygen' => true,
                'has_ventilator' => true,
                'has_monitor' => true,
                'additional_charge_per_day' => 1500.00,
                'is_active' => true,
            ]);
        }
    }
}
