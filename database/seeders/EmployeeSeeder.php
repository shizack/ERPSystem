<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Define the departments from your schema
        $departments = ['cook', 'room_management', 'gardening', 'frontdesk', 'maintenance'];

        if (DB::table('employees')->count() == 0) {
            $employees = [];

            foreach ($departments as $department) {
                // Create a sample employee for each department
                $employees[] = [
                    'full_name' => ucwords($department) . ' Employee',
                    'email' => str_replace('_', '.', $department) . '@resort.com',
                    // Password is 'password' for all test employees
                    'password' => Hash::make('password'), 
                    'role' => 'employee',
                    'department' => $department,
                    'status' => 'ACTIVE',
                    'created_at' => now(),
                ];
            }

            DB::table('employees')->insert($employees);
        }
    }
}