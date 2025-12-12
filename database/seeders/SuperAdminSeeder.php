<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('admins')->truncate();

        DB::table('admins')->insert([
            'full_name' => 'Super Admin',
            'email' => 'superadmin@resort.com',
            'password' => Hash::make('supersecurepassword'),
            'status' => 'ACTIVE',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
