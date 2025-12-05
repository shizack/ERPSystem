<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('admins')->truncate();

        $now = now();
        $password = Hash::make('password');

        DB::table('admins')->insert([
            [
                'full_name' => 'Marrieta S. Salve',
                'email' => 'marrieta.salve@resort.com',
                'password' => $password,
                'status' => 'ACTIVE',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'full_name' => 'Meroce Backlund',
                'email' => 'meroce.backlund@resort.com',
                'password' => $password,
                'status' => 'ACTIVE',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
