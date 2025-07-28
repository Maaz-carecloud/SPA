<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $designations = [
            // Administrative Staff
            'Principal',
            'Vice Principal',
            'Headmaster',
            'Headmistress',
            'Academic Coordinator',
            'Section Head',
            'Administrator',
            'Examination Controller',
            'Admission Officer',

            // Academic Staff
            'Teacher',
            'Teacher Assistant',
            'Class Teacher',
            'Prep Teacher',
            'M2 Teacher',
            'Prep Urdu Teacher',
            'Grade 1 Teacher',
            'Sports Teacher',
            'Art Teacher',
            'Robotic Intern',
            'Science Lab In-Charge',
            'Rosetta Lab InCharge',
            'Montessori Teacher',
            'Assistant Teacher',
            'Lecturer',
            'Senior Teacher',
            'Junior Teacher',
            'Lab Instructor',
            'Quran Teacher',
            'Islamiat Teacher',
            'physical instructor',

            // Support Staff
            'Office Clerk',
            'Librarian',
            'IT Technician',
            'Computer Operator',
            'Peon',
            'Office Boy',
            'Aya',
            'Nanny',
            'Security Guard',
            'Janitor',
            'Sweeper',
            'employee',
        ];

        foreach ($designations as $designation) {
            DB::table('designations')->insert([
                'name' => $designation,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}