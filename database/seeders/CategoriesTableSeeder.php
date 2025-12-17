<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Electronics', 'description' => 'Electronic devices and components'],
            ['name' => 'Office Supplies', 'description' => 'Office and stationery items'],
            ['name' => 'Furniture', 'description' => 'Office and home furniture'],
            ['name' => 'IT Equipment', 'description' => 'Computers, servers, and networking equipment'],
            ['name' => 'Maintenance', 'description' => 'Cleaning and maintenance supplies'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
\ No newline at end of file<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Electronics', 'description' => 'Electronic devices and components'],
            ['name' => 'Office Supplies', 'description' => 'Office and stationery items'],
            ['name' => 'Furniture', 'description' => 'Office and home furniture'],
            ['name' => 'IT Equipment', 'description' => 'Computers, servers, and networking equipment'],
            ['name' => 'Maintenance', 'description' => 'Cleaning and maintenance supplies'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}