<?php

namespace Database\Seeders;

use App\Models\ProductWarehouse;
use Illuminate\Database\Seeder;

class ProductWarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouses = [
            ['id' => 1, 'name' => 'A1', 'code' => 'A1', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 2, 'name' => 'A3', 'code' => 'A3', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 3, 'name' => 'A4', 'code' => 'A4', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 4, 'name' => 'A5', 'code' => 'A5', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 5, 'name' => 'A6', 'code' => 'A6', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 6, 'name' => 'A13', 'code' => 'A13', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 7, 'name' => 'A12', 'code' => 'A12', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 8, 'name' => 'A11', 'code' => 'A11', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 9, 'name' => 'A10', 'code' => 'A10', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 10, 'name' => 'A9', 'code' => 'A9', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 11, 'name' => 'A8', 'code' => 'A8', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 12, 'name' => 'A7', 'code' => 'A7', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 13, 'name' => 'B1', 'code' => 'B1', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 14, 'name' => 'B2', 'code' => 'B2', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 15, 'name' => 'B3', 'code' => 'B3', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 16, 'name' => 'B4', 'code' => 'B4', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 17, 'name' => 'B5', 'code' => 'B5', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 18, 'name' => 'B6', 'code' => 'B6', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 19, 'name' => 'B7', 'code' => 'B7', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 20, 'name' => 'B8', 'code' => 'B8', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 21, 'name' => 'B10', 'code' => 'B10', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 22, 'name' => 'B11', 'code' => 'B11', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 23, 'name' => 'B12', 'code' => 'B12', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 24, 'name' => 'B13', 'code' => 'B13', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 25, 'name' => 'B14', 'code' => 'B14', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 26, 'name' => 'B16', 'code' => 'B16', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 27, 'name' => 'J2', 'code' => 'J2', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 28, 'name' => 'B18', 'code' => 'B18', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 29, 'name' => 'B19', 'code' => 'B19', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 30, 'name' => 'B20', 'code' => 'B20', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 31, 'name' => 'B24 & B25', 'code' => 'B24 & B25', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 32, 'name' => 'FO', 'code' => 'FO', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 33, 'name' => 'G2', 'code' => 'G2', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 34, 'name' => 'F3', 'code' => 'F3', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 35, 'name' => 'H3', 'code' => 'H3', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 36, 'name' => 'H4', 'code' => 'H4', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 37, 'name' => 'G7', 'code' => 'G7', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 38, 'name' => 'H7', 'code' => 'H7', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 39, 'name' => 'F1', 'code' => 'F1', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 40, 'name' => 'M1', 'code' => 'M1', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 41, 'name' => 'K1', 'code' => 'K1', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 42, 'name' => 'K2', 'code' => 'K2', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 43, 'name' => 'M2', 'code' => 'M2', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 44, 'name' => 'O1', 'code' => 'O1', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 45, 'name' => 'O2', 'code' => 'O2', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 46, 'name' => 'H1', 'code' => 'H1', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 47, 'name' => 'L2', 'code' => 'L2', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 48, 'name' => 'AF1', 'code' => 'AF1', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 49, 'name' => 'AF2', 'code' => 'AF2', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 50, 'name' => 'BA6', 'code' => 'BA6', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 51, 'name' => 'BA7', 'code' => 'BA7', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 52, 'name' => 'BF1', 'code' => 'BF1', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 53, 'name' => 'BA2', 'code' => 'BA2', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 54, 'name' => 'BA3', 'code' => 'BA3', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 55, 'name' => 'BG1', 'code' => 'BG1', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 56, 'name' => 'Store', 'code' => 'Store', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 57, 'name' => 'Q1', 'code' => 'Q1', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 58, 'name' => 'T1', 'code' => 'T1', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 59, 'name' => 'V1', 'code' => 'V1', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 60, 'name' => 'R1', 'code' => 'R1', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 61, 'name' => 'Q2', 'code' => 'Q2', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 62, 'name' => 'T2', 'code' => 'T2', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 63, 'name' => 'R2', 'code' => 'R2', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 64, 'name' => 'X1', 'code' => 'X1', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 65, 'name' => 'Z3', 'code' => 'Z3', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 66, 'name' => 'Z2', 'code' => 'Z2', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 67, 'name' => 'AA1', 'code' => 'AA1', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 68, 'name' => 'AB3', 'code' => 'AB3', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 69, 'name' => 'AB4', 'code' => 'AB4', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 70, 'name' => 'AC1', 'code' => 'AC1', 'email' => null, 'phone' => null, 'address' => null],
            ['id' => 71, 'name' => 'A2', 'code' => '2', 'email' => '', 'phone' => '', 'address' => ''],
        ];

        foreach ($warehouses as $warehouse) {
            ProductWarehouse::create([
                'id' => $warehouse['id'],
                'name' => $warehouse['name'],
                'code' => $warehouse['code'],
                'email' => !empty($warehouse['email']) ? $warehouse['email'] : null,
                'phone' => !empty($warehouse['phone']) ? $warehouse['phone'] : null,
                'address' => !empty($warehouse['address']) ? $warehouse['address'] : null,
                'created_by' => null,
                'updated_by' => null,
                'is_active' => true,
            ]);
        }
    }
}
