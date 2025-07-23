<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'Super Admin',
            'Admin',
            'Teacher',
            'Student',
            'Parents',
            'Accountant',
            'Librarian',
            'Receptionist',
            'Moderator',
            'Sanitary Worker',
            'Front desk',
            'Principal',
            'Vice Principal',
            'Assistant to Principal',
            'Assistant to Vice Principal',
            'Coordinator',
            'Teacher Assistant',
            'Internee',
            'Admin staff',
            'Marketing Consultant',
            'FINANCE OFFICER - Inventory',
            'Inventory',
            'Full right - Inventory & Accounts',
            'Admin Payroll Manage (Only authorized user)',
            'Admin Payroll Approver (Only authorized user)',
            'Maintainance Supervisior',
            'Assistant - Maid',
            'High School Coordinator',
            'Custom Permissions',
            'Teacher.',
            'Audit',
            'Shop Inventory',
            'JUNIOR DATA SCIENTEST',
            'LED-Access',
            'Coordinator - Custom',
            'SA / BA',
            'Coordinator 1',
            'Coordinator 2',
            'Coordinator 3',
            'Coordinator 4',
            'Coordinator 5',
            'Coordinator 6',
            'Designer',
            'HR/PSO',
            'School Stationary Incharge',
            'Consultant',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name' => $role,
                'guard_name' => 'web',
            ]);
        }
    }
}
