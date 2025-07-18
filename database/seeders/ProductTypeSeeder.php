<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\ProductType::factory()->count(10)->create();
        // You can customize the number of ProductTypes created by changing the count value
        // For example, to create 5 ProductTypes, use: \App\Models\ProductType::factory()->count(5)->create();
    }
}
