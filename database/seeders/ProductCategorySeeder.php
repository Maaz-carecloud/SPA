<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['id' => 1, 'name' => 'Stationary'],
            ['id' => 2, 'name' => 'Bag'],
            ['id' => 3, 'name' => 'Pouch'],
            ['id' => 4, 'name' => 'Bottle'],
            ['id' => 5, 'name' => 'Notebook'],
            ['id' => 6, 'name' => 'Book M1'],
            ['id' => 7, 'name' => 'Book M2'],
            ['id' => 8, 'name' => 'Book Prep'],
            ['id' => 9, 'name' => 'Book G1'],
            ['id' => 10, 'name' => 'Book G2'],
            ['id' => 11, 'name' => 'Book G3'],
            ['id' => 12, 'name' => 'Book G4'],
            ['id' => 13, 'name' => 'Book G5'],
            ['id' => 14, 'name' => 'Book G6'],
            ['id' => 15, 'name' => 'Book G7'],
            ['id' => 16, 'name' => 'Book G8'],
            ['id' => 17, 'name' => 'Uniform'],
        ];

        foreach ($categories as $category) {
            ProductCategory::create([
                'id' => $category['id'],
                'name' => $category['name'],
                'description' => null,
                'created_by' => null,
                'updated_by' => null,
                'is_active' => true,
            ]);
        }
    }
}
