<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = [
            [
                'name' => 'Main Hospital',
                'code' => 'HQ',
                'address' => 'Hospital Main Campus',
                'phone' => null,
                'email' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Branch A',
                'code' => 'BR-A',
                'address' => 'Branch A Location',
                'phone' => null,
                'email' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Branch B',
                'code' => 'BR-B',
                'address' => 'Branch B Location',
                'phone' => null,
                'email' => null,
                'is_active' => true,
            ],
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }
    }
}
