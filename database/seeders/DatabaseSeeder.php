<?php

namespace Database\Seeders;

// Note: Ensure this use statement is present if you created the seeders in the standard directory
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SuperAdminSeeder::class,
            SupplierSeeder::class,
        ]);
    }
}