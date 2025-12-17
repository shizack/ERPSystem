<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'Supplier #1 - Towels',
                'contact_person' => 'John Doe',
                'email' => 'supplier1@example.com',
                'phone' => '09123456789',
                'address' => '123 Towel St., Manila, Philippines',
                'tax_identification_number' => '123-456-789-000',
            ],
            [
                'name' => 'Supplier #2 - Toilet Papers',
                'contact_person' => 'Jane Smith',
                'email' => 'supplier2@example.com',
                'phone' => '09123456780',
                'address' => '456 Tissue Ave., Quezon City, Philippines',
                'tax_identification_number' => '987-654-321-000',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::updateOrCreate(
                ['email' => $supplier['email']],
                $supplier
            );
        }
    }
}<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'Supplier #1 - Towels',
                'contact_person' => 'John Doe',
                'email' => 'supplier1@example.com',
                'phone' => '09123456789',
                'address' => '123 Towel St., Manila, Philippines',
                'tax_identification_number' => '123-456-789-000',
            ],
            [
                'name' => 'Supplier #2 - Toilet Papers',
                'contact_person' => 'Jane Smith',
                'email' => 'supplier2@example.com',
                'phone' => '09123456780',
                'address' => '456 Tissue Ave., Quezon City, Philippines',
                'tax_identification_number' => '987-654-321-000',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::updateOrCreate(
                ['email' => $supplier['email']],
                $supplier
            );
        }
    }
}
