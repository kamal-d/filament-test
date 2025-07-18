<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Product::factory()->count(10)->create([
            'product_color_id' => \App\Models\ProductColor::factory(),
            'product_category_id' => \App\Models\ProductCategory::factory(),
            'status' => 'draft',
        ]);
    }
}
