<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use App\Models\ClassModel;
use App\Models\Section;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        // Get the first class for testing
        $class = ClassModel::first();
        
        if (!$class) {
            $this->command->info('No classes found. Please create classes first.');
            return;
        }

        // Get or create a section for the class
        $section = Section::where('class_id', $class->id)->first();
        
        if (!$section) {
            $section = Section::create([
                'name' => 'Section A',
                'class_id' => $class->id,
                'capacity' => 30,
            ]);
        }

        $this->command->info("Creating students for Class: {$class->name}, Section: {$section->name}");

        // Create 10 test students
        for ($i = 1; $i <= 10; $i++) {
            // Create user with all required fields
            $user = User::create([
                'name' => "Student {$i}",
                'username' => "student{$i}",
                'email' => "student{$i}@test.com",
                'password' => Hash::make('password'),
                'user_type' => 'student',
                'is_active' => true,
                'email_verified_at' => now(),
                'registration_no' => 'STU' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'cnic' => '35202-00000' . str_pad($i, 3, '0', STR_PAD_LEFT) . '-1', // unique dummy CNIC
                'dob' => now()->subYears(18)->subDays($i),
                'gender' => 'Male',
                'religion' => 'Islam',
                'phone' => '0300123456' . $i,
                'address' => 'Test Address',
                'country' => 'Pakistan',
                'state' => 'Punjab',
                'city' => 'Lahore',
                'avatar' => null,
                'blood_group' => 'A+',
                'transport_status' => 0,
                'transport_id' => null,
                'created_by' => null,
                'updated_by' => null,
            ]);

            // Create student record
            Student::create([
                'user_id' => $user->id,
                'class_id' => $class->id,
                'section_id' => $section->id,
                'roll_no' => $i,
                'admission_date' => now()->subMonths(rand(1, 12)),
                'library_status' => 1,
                'hostel_status' => 0,
                'created_by' => null,
                'updated_by' => null,
            ]);
        }

        $this->command->info('Created 10 test students successfully!');
        $this->command->info('You can now test the Library Members page.');
    }
}
