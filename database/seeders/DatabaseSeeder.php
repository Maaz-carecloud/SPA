<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        // Post::factory(5000)->create();

        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            ProductCategorySeeder::class,
            ProductWarehouseSeeder::class,
            DesignationSeeder::class,
            EmployeeSeeder::class,
            TeacherSeeder::class,
            ClassSeeder::class,
            SectionSeeder::class,
            ModuleSeeder::class,
            LeaveTypeSeeder::class,
            ProductSuppliersSeeder::class,
            InventoryProductSeeder::class,
            BookSeeder::class,
            ParentSeeder::class,
            StudentSeeder::class,
        ]);
    }
}
