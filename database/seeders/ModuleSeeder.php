<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;

class ModuleSeeder extends Seeder
{
    public function run()
    {
        $modules = [
            'Dashboard',
            'Admissions',
            'Leave Manage',
            'Students',
            'Parents',
            'Teacher',
            'Employee',
            'Academic',
            'Inventory',
            'Attendance',
            'Library',
            'Transportation',
            'Account',
            'Reports',
            'Administrator',
            'Setting',
        ];

        foreach ($modules as $name) {
            Module::updateOrCreate(
                ['name' => $name],
                ['created_by' => 'Super Admin User', 'updated_by' => 'Super Admin User']
            );
        }
    }
}
