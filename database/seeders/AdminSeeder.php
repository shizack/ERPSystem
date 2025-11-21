<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if the admin table is empty before seeding
        if (DB::table('admins')->count() == 0) {
            DB::table('admins')->insert([
                [
                    'full_name' => 'System Admin',
                    'email' => 'admin@resort.com',
                    // Password is 'password'
                    'password' => Hash::make('password'), 
                    'status' => 'ACTIVE',
                    'created_at' => now(),
                ],
            ]);
        }
    }
}