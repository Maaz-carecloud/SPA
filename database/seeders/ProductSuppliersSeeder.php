<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductSupplier;
use Carbon\Carbon;

class ProductSuppliersSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'id' => 1,
                'company_name' => 'Triangle Uniform',
                'name' => 'Triangle Uniform',
                'email' => 'triangleuniform@bgs.edu.pk',
                'phone' => null,
                'address' => null,
                'created_by' => 'Super Admin User',
                'updated_by' => 'Super Admin User',
                'is_active' => 1,
                'created_at' => Carbon::parse('2025-06-13 02:12:28'),
                'updated_at' => Carbon::parse('2025-06-13 02:34:47'),
            ],
            [
                'id' => 9,
                'company_name' => 'Oxford Uniform Press',
                'name' => 'Oxford Uniform Press',
                'email' => 'oxformuniformpress@bgs.edu.pk',
                'phone' => null,
                'address' => null,
                'created_by' => 'Super Admin User',
                'updated_by' => 'Super Admin User',
                'is_active' => 1,
                'created_at' => Carbon::parse('2025-06-13 02:29:31'),
                'updated_at' => Carbon::parse('2025-06-13 02:35:04'),
            ],
            [
                'id' => 10,
                'company_name' => 'Trade Links',
                'name' => 'Trade Links',
                'email' => 'tradelinks@bgs.edu.pk',
                'phone' => null,
                'address' => null,
                'created_by' => 'Super Admin User',
                'updated_by' => 'Super Admin User',
                'is_active' => 1,
                'created_at' => Carbon::parse('2025-06-13 02:41:15'),
                'updated_at' => Carbon::parse('2025-06-13 02:41:15'),
            ],
        ];

        foreach ($suppliers as $supplier) {
            ProductSupplier::updateOrCreate(['id' => $supplier['id']], $supplier);
        }
    }
}
