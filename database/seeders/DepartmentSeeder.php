<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear the table to avoid duplicates on re-seeding
        DB::table('departments')->delete();

        $departments = [
            ['name' => 'Cardiology', 'address' => 'Heart and cardiovascular care'],
            ['name' => 'Neurology', 'address' => 'Brain and nervous system'],
            ['name' => 'Pediatrics', 'address' => 'Child healthcare'],
            ['name' => 'Orthopedics', 'address' => 'Bone and joint care'],
            ['name' => 'Emergency', 'address' => '24/7 emergency care'],
            ['name' => 'Radiology', 'address' => 'Medical imaging'],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}