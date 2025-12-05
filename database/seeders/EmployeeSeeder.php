<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('employees')->truncate();

        $now = now();
        $password = Hash::make('password');

        $employees = [
            [
                'full_name' => 'Rheamae Giltendez',
                'email' => 'rheamae.giltendez@resort.com',
                'password' => $password,
                'role' => 'employee',
                'department' => 'frontdesk',
                'status' => 'ACTIVE',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'full_name' => 'Evelin Sinugbohan',
                'email' => 'evelin.sinugbohan@resort.com',
                'password' => $password,
                'role' => 'employee',
                'department' => 'cook',
                'status' => 'ACTIVE',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'full_name' => 'Amelita Rayco',
                'email' => 'amelita.rayco@resort.com',
                'password' => $password,
                'role' => 'employee',
                'department' => 'cook',
                'status' => 'ACTIVE',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'full_name' => 'Melinda Mata',
                'email' => 'melinda.mata@resort.com',
                'password' => $password,
                'role' => 'employee',
                'department' => 'room_management',
                'status' => 'ACTIVE',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'full_name' => 'Rosemar Ybanez',
                'email' => 'rosemar.ybanez@resort.com',
                'password' => $password,
                'role' => 'employee',
                'department' => 'room_management',
                'status' => 'ACTIVE',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'full_name' => 'Relly Esgana',
                'email' => 'relly.esgana@resort.com',
                'password' => $password,
                'role' => 'employee',
                'department' => 'room_management',
                'status' => 'ACTIVE',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'full_name' => 'Macario Aniana Jr.',
                'email' => 'macario.aniana@resort.com',
                'password' => $password,
                'role' => 'employee',
                'department' => 'room_management',
                'status' => 'ACTIVE',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'full_name' => 'David Bayon-on Jr.',
                'email' => 'david.bayonon@resort.com',
                'password' => $password,
                'role' => 'employee',
                'department' => 'maintenance',
                'status' => 'ACTIVE',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('employees')->insert($employees);
    }
}
