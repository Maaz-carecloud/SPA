<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class InventoryProductSeeder extends Seeder
{
    public function run(): void
    {
        // Read the CSV file
        $csvPath = base_path('database/seeders/products.csv');
        if (!file_exists($csvPath)) {
            throw new \Exception('products.csv not found in database/seeders');
        }
        $rows = array_map('str_getcsv', file($csvPath));
        $header = array_map('trim', array_shift($rows));

        foreach ($rows as $row) {
            $data = array_combine($header, $row);
            // Nullify empty strings, 'NULL' strings, and 0 for user foreign keys
            foreach ($data as $k => $v) {
                if ($v === '' || strtoupper($v) === 'NULL') {
                    $data[$k] = null;
                }
            }
            // Special handling for user foreign keys
            foreach (['created_by', 'updated_by'] as $userField) {
                if (isset($data[$userField]) && (is_null($data[$userField]) || $data[$userField] == 0)) {
                    $data[$userField] = null;
                }
            }
            // Convert date fields if present and valid
            foreach (['created_at', 'updated_at'] as $dateField) {
                if (!empty($data[$dateField])) {
                    try {
                        $data[$dateField] = Carbon::parse($data[$dateField]);
                    } catch (\Exception $e) {
                        $data[$dateField] = now();
                    }
                } else {
                    $data[$dateField] = now();
                }
            }
            Product::updateOrCreate(['id' => $data['id']], $data);
        }
    }
}
