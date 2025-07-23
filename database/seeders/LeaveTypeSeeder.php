<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LeaveType;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leaveTypes = [            [
                'name' => 'Sick Leave',
                'description' => 'Leave for illness or medical reasons',
                'is_active' => true,
'created_by' => 'Super Admin User', // Admin user
            ],
            [
                'name' => 'Annual Leave',
                'description' => 'Yearly vacation leave',
                'is_active' => true,
'created_by' => 'Super Admin User',
            ],
            [
                'name' => 'Short Leave',
                'description' => 'Leave for short duration or urgent matters',
                'is_active' => true,
'created_by' => 'Super Admin User',
            ],
            [
                'name' => 'Maternity Leave',
                'description' => 'Leave for maternity purposes',
                'is_active' => true,
'created_by' => 'Super Admin User',
            ],
            [
                'name' => 'Paternity Leave',
                'description' => 'Leave for paternity purposes',
                'is_active' => true,
'created_by' => 'Super Admin User',
            ],
            [
                'name' => 'Study Leave',
                'description' => 'Leave for educational purposes',
                'is_active' => true,
'created_by' => 'Super Admin User',
            ],
            [
                'name' => 'Personal Leave',
                'description' => 'Leave for personal matters',
                'is_active' => true,
'created_by' => 'Super Admin User',
            ],
            // add umrah , death in family and any other leave types as needed
            [
                'name' => 'Umrah Leave',
                'description' => 'Leave for performing Umrah pilgrimage',
                'is_active' => true,
'created_by' => 'Super Admin User',
            ],
            [
                'name' => 'Death in Family Leave',
                'description' => 'Leave for bereavement due to death in the family',
                'is_active' => true,
'created_by' => 'Super Admin User',
            ],
            [
                'name' => 'Any Other',
                'description' => 'Leave for any other leave',
                'is_active' => true,
'created_by' => 'Super Admin User',
            ],
        ];

        foreach ($leaveTypes as $leaveType) {
            LeaveType::create($leaveType);
        }
    }
}
