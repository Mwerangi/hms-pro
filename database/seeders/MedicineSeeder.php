<?php

namespace Database\Seeders;

use App\Models\Medicine;
use Illuminate\Database\Seeder;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $medicines = [
            // Pain & Fever
            ['medicine_code' => 'MED-001', 'medicine_name' => 'Paracetamol', 'generic_name' => 'Acetaminophen', 'brand_name' => 'Panadol', 'medicine_type' => 'tablet', 'strength' => '500mg', 'manufacturer' => 'GSK', 'description' => 'Pain reliever and fever reducer', 'unit_price' => 50.00, 'unit_of_measure' => 'tablet', 'current_stock' => 1000, 'reorder_level' => 200, 'is_active' => true, 'requires_prescription' => false],
            ['medicine_code' => 'MED-002', 'medicine_name' => 'Ibuprofen', 'generic_name' => 'Ibuprofen', 'brand_name' => 'Brufen', 'medicine_type' => 'tablet', 'strength' => '400mg', 'manufacturer' => 'Abbott', 'description' => 'NSAID - Pain, inflammation, fever', 'unit_price' => 100.00, 'unit_of_measure' => 'tablet', 'current_stock' => 800, 'reorder_level' => 150, 'is_active' => true, 'requires_prescription' => false],
            ['medicine_code' => 'MED-003', 'medicine_name' => 'Diclofenac', 'generic_name' => 'Diclofenac Sodium', 'brand_name' => 'Voltaren', 'medicine_type' => 'tablet', 'strength' => '50mg', 'manufacturer' => 'Novartis', 'description' => 'NSAID - Strong pain relief', 'unit_price' => 150.00, 'unit_of_measure' => 'tablet', 'current_stock' => 500, 'reorder_level' => 100, 'is_active' => true, 'requires_prescription' => true],

            // Antibiotics
            ['medicine_code' => 'MED-004', 'medicine_name' => 'Amoxicillin', 'generic_name' => 'Amoxicillin', 'brand_name' => 'Amoxil', 'medicine_type' => 'capsule', 'strength' => '500mg', 'manufacturer' => 'GSK', 'description' => 'Broad-spectrum antibiotic', 'unit_price' => 200.00, 'unit_of_measure' => 'capsule', 'current_stock' => 600, 'reorder_level' => 150, 'is_active' => true, 'requires_prescription' => true],
            ['medicine_code' => 'MED-005', 'medicine_name' => 'Azithromycin', 'generic_name' => 'Azithromycin', 'brand_name' => 'Zithromax', 'medicine_type' => 'tablet', 'strength' => '500mg', 'manufacturer' => 'Pfizer', 'description' => 'Macrolide antibiotic', 'unit_price' => 500.00, 'unit_of_measure' => 'tablet', 'current_stock' => 400, 'reorder_level' => 100, 'is_active' => true, 'requires_prescription' => true],
            ['medicine_code' => 'MED-006', 'medicine_name' => 'Ciprofloxacin', 'generic_name' => 'Ciprofloxacin', 'brand_name' => 'Cipro', 'medicine_type' => 'tablet', 'strength' => '500mg', 'manufacturer' => 'Bayer', 'description' => 'Fluoroquinolone antibiotic', 'unit_price' => 300.00, 'unit_of_measure' => 'tablet', 'current_stock' => 350, 'reorder_level' => 80, 'is_active' => true, 'requires_prescription' => true],
            ['medicine_code' => 'MED-007', 'medicine_name' => 'Metronidazole', 'generic_name' => 'Metronidazole', 'brand_name' => 'Flagyl', 'medicine_type' => 'tablet', 'strength' => '400mg', 'manufacturer' => 'Sanofi', 'description' => 'Antibiotic & antiprotozoal', 'unit_price' => 150.00, 'unit_of_measure' => 'tablet', 'current_stock' => 500, 'reorder_level' => 100, 'is_active' => true, 'requires_prescription' => true],

            // Malaria
            ['medicine_code' => 'MED-008', 'medicine_name' => 'Artemether + Lumefantrine', 'generic_name' => 'ACT', 'brand_name' => 'Coartem', 'medicine_type' => 'tablet', 'strength' => '80mg/480mg', 'manufacturer' => 'Novartis', 'description' => 'First-line malaria treatment', 'unit_price' => 800.00, 'unit_of_measure' => 'tablet', 'current_stock' => 1000, 'reorder_level' => 300, 'is_active' => true, 'requires_prescription' => true],
            ['medicine_code' => 'MED-009', 'medicine_name' => 'Quinine', 'generic_name' => 'Quinine Sulfate', 'brand_name' => null, 'medicine_type' => 'tablet', 'strength' => '300mg', 'manufacturer' => 'Generic', 'description' => 'Malaria treatment - severe cases', 'unit_price' => 250.00, 'unit_of_measure' => 'tablet', 'current_stock' => 400, 'reorder_level' => 100, 'is_active' => true, 'requires_prescription' => true],

            // Gastrointestinal
            ['medicine_code' => 'MED-010', 'medicine_name' => 'Omeprazole', 'generic_name' => 'Omeprazole', 'brand_name' => 'Losec', 'medicine_type' => 'capsule', 'strength' => '20mg', 'manufacturer' => 'AstraZeneca', 'description' => 'Proton pump inhibitor - Ulcers, GERD', 'unit_price' => 200.00, 'unit_of_measure' => 'capsule', 'current_stock' => 600, 'reorder_level' => 150, 'is_active' => true, 'requires_prescription' => true],
            ['medicine_code' => 'MED-011', 'medicine_name' => 'Ranitidine', 'generic_name' => 'Ranitidine', 'brand_name' => 'Zantac', 'medicine_type' => 'tablet', 'strength' => '150mg', 'manufacturer' => 'GSK', 'description' => 'H2 blocker - Acid reflux', 'unit_price' => 100.00, 'unit_of_measure' => 'tablet', 'current_stock' => 500, 'reorder_level' => 100, 'is_active' => true, 'requires_prescription' => false],
            ['medicine_code' => 'MED-012', 'medicine_name' => 'Oral Rehydration Salts', 'generic_name' => 'ORS', 'brand_name' => 'ORS', 'medicine_type' => 'other', 'strength' => '20.5g sachet', 'manufacturer' => 'WHO Formula', 'description' => 'Diarrhea & dehydration treatment', 'unit_price' => 500.00, 'unit_of_measure' => 'sachet', 'current_stock' => 1500, 'reorder_level' => 500, 'is_active' => true, 'requires_prescription' => false],

            // Respiratory
            ['medicine_code' => 'MED-013', 'medicine_name' => 'Salbutamol', 'generic_name' => 'Salbutamol', 'brand_name' => 'Ventolin', 'medicine_type' => 'inhaler', 'strength' => '100mcg', 'manufacturer' => 'GSK', 'description' => 'Bronchodilator - Asthma', 'unit_price' => 5000.00, 'unit_of_measure' => 'inhaler', 'current_stock' => 150, 'reorder_level' => 30, 'is_active' => true, 'requires_prescription' => true],
            ['medicine_code' => 'MED-014', 'medicine_name' => 'Cetirizine', 'generic_name' => 'Cetirizine', 'brand_name' => 'Zyrtec', 'medicine_type' => 'tablet', 'strength' => '10mg', 'manufacturer' => 'UCB', 'description' => 'Antihistamine - Allergies', 'unit_price' => 150.00, 'unit_of_measure' => 'tablet', 'current_stock' => 400, 'reorder_level' => 100, 'is_active' => true, 'requires_prescription' => false],

            // Cardiovascular
            ['medicine_code' => 'MED-015', 'medicine_name' => 'Amlodipine', 'generic_name' => 'Amlodipine', 'brand_name' => 'Norvasc', 'medicine_type' => 'tablet', 'strength' => '5mg', 'manufacturer' => 'Pfizer', 'description' => 'Calcium channel blocker - Hypertension', 'unit_price' => 200.00, 'unit_of_measure' => 'tablet', 'current_stock' => 800, 'reorder_level' => 200, 'is_active' => true, 'requires_prescription' => true],
            ['medicine_code' => 'MED-016', 'medicine_name' => 'Enalapril', 'generic_name' => 'Enalapril', 'brand_name' => 'Renitec', 'medicine_type' => 'tablet', 'strength' => '10mg', 'manufacturer' => 'MSD', 'description' => 'ACE inhibitor - Hypertension', 'unit_price' => 150.00, 'unit_of_measure' => 'tablet', 'current_stock' => 700, 'reorder_level' => 150, 'is_active' => true, 'requires_prescription' => true],
            ['medicine_code' => 'MED-017', 'medicine_name' => 'Atorvastatin', 'generic_name' => 'Atorvastatin', 'brand_name' => 'Lipitor', 'medicine_type' => 'tablet', 'strength' => '20mg', 'manufacturer' => 'Pfizer', 'description' => 'Statin - Cholesterol', 'unit_price' => 300.00, 'unit_of_measure' => 'tablet', 'current_stock' => 500, 'reorder_level' => 100, 'is_active' => true, 'requires_prescription' => true],

            // Diabetes
            ['medicine_code' => 'MED-018', 'medicine_name' => 'Metformin', 'generic_name' => 'Metformin', 'brand_name' => 'Glucophage', 'medicine_type' => 'tablet', 'strength' => '500mg', 'manufacturer' => 'Merck', 'description' => 'Type 2 diabetes', 'unit_price' => 100.00, 'unit_of_measure' => 'tablet', 'current_stock' => 1000, 'reorder_level' => 250, 'is_active' => true, 'requires_prescription' => true],
            ['medicine_code' => 'MED-019', 'medicine_name' => 'Insulin (Human)', 'generic_name' => 'Human Insulin', 'brand_name' => 'Humulin', 'medicine_type' => 'injection', 'strength' => '100IU/ml', 'manufacturer' => 'Eli Lilly', 'description' => 'Diabetes - Insulin therapy', 'unit_price' => 15000.00, 'unit_of_measure' => 'vial', 'current_stock' => 100, 'reorder_level' => 30, 'is_active' => true, 'requires_prescription' => true],

            // Vitamins
            ['medicine_code' => 'MED-020', 'medicine_name' => 'Multivitamin', 'generic_name' => 'Multivitamin', 'brand_name' => 'Centrum', 'medicine_type' => 'tablet', 'strength' => 'Daily', 'manufacturer' => 'Pfizer', 'description' => 'Daily multivitamin supplement', 'unit_price' => 200.00, 'unit_of_measure' => 'tablet', 'current_stock' => 600, 'reorder_level' => 150, 'is_active' => true, 'requires_prescription' => false],
            ['medicine_code' => 'MED-021', 'medicine_name' => 'Folic Acid', 'generic_name' => 'Folic Acid', 'brand_name' => null, 'medicine_type' => 'tablet', 'strength' => '5mg', 'manufacturer' => 'Generic', 'description' => 'Pregnancy & anemia', 'unit_price' => 50.00, 'unit_of_measure' => 'tablet', 'current_stock' => 800, 'reorder_level' => 200, 'is_active' => true, 'requires_prescription' => false],
            ['medicine_code' => 'MED-022', 'medicine_name' => 'Ferrous Sulfate', 'generic_name' => 'Iron', 'brand_name' => null, 'medicine_type' => 'tablet', 'strength' => '200mg', 'manufacturer' => 'Generic', 'description' => 'Iron deficiency anemia', 'unit_price' => 100.00, 'unit_of_measure' => 'tablet', 'current_stock' => 700, 'reorder_level' => 150, 'is_active' => true, 'requires_prescription' => false],

            // Topical
            ['medicine_code' => 'MED-023', 'medicine_name' => 'Hydrocortisone Cream', 'generic_name' => 'Hydrocortisone', 'brand_name' => null, 'medicine_type' => 'cream', 'strength' => '1%', 'manufacturer' => 'Generic', 'description' => 'Anti-inflammatory - Skin', 'unit_price' => 3000.00, 'unit_of_measure' => 'tube', 'current_stock' => 200, 'reorder_level' => 50, 'is_active' => true, 'requires_prescription' => false],
            ['medicine_code' => 'MED-024', 'medicine_name' => 'Clotrimazole Cream', 'generic_name' => 'Clotrimazole', 'brand_name' => 'Canesten', 'medicine_type' => 'cream', 'strength' => '1%', 'manufacturer' => 'Bayer', 'description' => 'Antifungal - Skin infections', 'unit_price' => 5000.00, 'unit_of_measure' => 'tube', 'current_stock' => 150, 'reorder_level' => 40, 'is_active' => true, 'requires_prescription' => false],

            // Pediatrics
            ['medicine_code' => 'MED-025', 'medicine_name' => 'Paracetamol Syrup', 'generic_name' => 'Acetaminophen', 'brand_name' => 'Calpol', 'medicine_type' => 'syrup', 'strength' => '120mg/5ml', 'manufacturer' => 'GSK', 'description' => 'Pediatric pain & fever', 'unit_price' => 3500.00, 'unit_of_measure' => 'bottle', 'current_stock' => 300, 'reorder_level' => 80, 'is_active' => true, 'requires_prescription' => false],
        ];

        foreach ($medicines as $medicine) {
            Medicine::create($medicine);
        }
    }
}
