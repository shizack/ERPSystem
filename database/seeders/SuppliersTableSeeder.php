<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SuppliersTableSeeder extends Seeder
{
    public function run()
    {
        $suppliers = [
            [
                'name' => 'Tech Solutions Inc.',
                'contact_person' => 'John Smith',
                'email' => 'john@techsolutions.com',
                'phone' => '+1234567890',
                'address' => '123 Tech Street, Silicon Valley, CA'
            ],
            [
                'name' => 'Office Plus Ltd',
                'contact_person' => 'Sarah Johnson',
                'email' => 'sarah@officeplus.com',
                'phone' => '+1987654321',
                'address' => '456 Business Ave, New York, NY'
            ],
            // Add more suppliers as needed
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}