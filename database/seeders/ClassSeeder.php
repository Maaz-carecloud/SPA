<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassModel;
use Carbon\Carbon;
use App\Models\User;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */    public function run(): void
    {
        // Clear existing data safely (respecting foreign key constraints)
        ClassModel::query()->delete();

        // Get all teacher user IDs
        $teacherUserIds = User::where('user_type', 'teacher')->pluck('id')->toArray();
        if (empty($teacherUserIds)) {
            throw new \Exception('No users with user_type=teacher found.');
        }

        $classes = [
            [
                'id' => 12,
                'name' => 'Grade 12',
                'class_numeric' => 12,
                'teacher_id' => null, // Placeholder, will be replaced with random teacher_id
'created_by' => 'Super Admin User',
'updated_by' => 'Super Admin User',
                'created_at' => Carbon::parse('2023-09-04 10:43:54'),
                'updated_at' => Carbon::parse('2023-09-04 10:43:54'),
            ],
            [
                'id' => 15,
                'name' => 'Grade 2',
                'class_numeric' => 2,
                'teacher_id' => null, // Placeholder, will be replaced with random teacher_id
'created_by' => 'Super Admin User',
'updated_by' => 'Super Admin User',
                'created_at' => Carbon::parse('2023-05-16 10:43:54'),
                'updated_at' => Carbon::parse('2025-05-02 09:13:35'),
            ],
            [
                'id' => 16,
                'name' => 'Grade 3',
                'class_numeric' => 3,
                'teacher_id' => null, // Placeholder, will be replaced with random teacher_id
'created_by' => 'Super Admin User',
'updated_by' => 'Super Admin User',
                'created_at' => Carbon::parse('2023-05-16 11:11:19'),
                'updated_at' => Carbon::parse('2025-05-02 09:13:26'),
            ],
            [
                'id' => 17,
                'name' => 'Grade 4',
                'class_numeric' => 4,
                'teacher_id' => null, // Placeholder, will be replaced with random teacher_id
'created_by' => 'Super Admin User',
'updated_by' => 'Super Admin User',
                'created_at' => Carbon::parse('2023-05-16 11:11:40'),
                'updated_at' => Carbon::parse('2025-05-02 09:13:13'),
            ],
            [
                'id' => 19,
                'name' => 'Grade 5',
                'class_numeric' => 5,
                'teacher_id' => null, // Placeholder, will be replaced with random teacher_id
'created_by' => 'Super Admin User',
'updated_by' => 'Super Admin User',
                'created_at' => Carbon::parse('2023-05-16 11:13:56'),
                'updated_at' => Carbon::parse('2023-05-16 11:13:56'),
            ],
            [
                'id' => 20,
                'name' => 'Grade 6',
                'class_numeric' => 6,
                'teacher_id' => null, // Placeholder, will be replaced with random teacher_id
'created_by' => 'Super Admin User',
'updated_by' => 'Super Admin User',
                'created_at' => Carbon::parse('2023-05-16 11:18:24'),
                'updated_at' => Carbon::parse('2023-05-16 11:18:24'),
            ],
            [
                'id' => 21,
                'name' => 'Grade 7',
                'class_numeric' => 7,
                'teacher_id' => null, // Placeholder, will be replaced with random teacher_id
'created_by' => 'Super Admin User',
'updated_by' => 'Super Admin User',
                'created_at' => Carbon::parse('2023-05-16 11:19:16'),
                'updated_at' => Carbon::parse('2023-05-16 11:19:16'),
            ],
            [
                'id' => 22,
                'name' => 'Grade 8',
                'class_numeric' => 8,
                'teacher_id' => null, // Placeholder, will be replaced with random teacher_id
'created_by' => 'Super Admin User',
'updated_by' => 'Super Admin User',
                'created_at' => Carbon::parse('2023-05-16 11:19:30'),
                'updated_at' => Carbon::parse('2023-05-16 11:19:30'),
            ],
            [
                'id' => 23,
                'name' => 'Grade 9',
                'class_numeric' => 9,
                'teacher_id' => null, // Placeholder, will be replaced with random teacher_id
'created_by' => 'Super Admin User',
'updated_by' => 'Super Admin User',
                'created_at' => Carbon::parse('2023-05-16 11:19:45'),
                'updated_at' => Carbon::parse('2023-05-16 11:19:45'),
            ],
            [
                'id' => 24,
                'name' => 'Grade 10',
                'class_numeric' => 10,
                'teacher_id' => null, // Placeholder, will be replaced with random teacher_id
'created_by' => 'Super Admin User',
'updated_by' => 'Super Admin User',
                'created_at' => Carbon::parse('2023-05-16 11:20:02'),
                'updated_at' => Carbon::parse('2023-05-16 11:20:02'),
            ],
            [
                'id' => 25,
                'name' => 'Grade 11',
                'class_numeric' => 11,
                'teacher_id' => null, // Placeholder, will be replaced with random teacher_id
'created_by' => 'Super Admin User',
'updated_by' => 'Super Admin User',
                'created_at' => Carbon::parse('2023-05-16 11:20:26'),
                'updated_at' => Carbon::parse('2023-05-16 11:20:26'),
            ],
            [
                'id' => 26,
                'name' => 'Montessori 2',
                'class_numeric' => 15,
                'teacher_id' => null, // Placeholder, will be replaced with random teacher_id
'created_by' => 'Super Admin User',
'updated_by' => 'Super Admin User',
                'created_at' => Carbon::parse('2023-05-16 11:27:35'),
                'updated_at' => Carbon::parse('2025-05-02 10:16:44'),
            ],
            [
                'id' => 27,
                'name' => 'Prep',
                'class_numeric' => 13,
                'teacher_id' => null, // Placeholder, will be replaced with random teacher_id
'created_by' => 'Super Admin User',
'updated_by' => 'Super Admin User',
                'created_at' => Carbon::parse('2023-05-16 11:27:49'),
                'updated_at' => Carbon::parse('2025-05-02 09:24:07'),
            ],
            [
                'id' => 28,
                'name' => 'Grade 1',
                'class_numeric' => 1,
                'teacher_id' => null, // Placeholder, will be replaced with random teacher_id
'created_by' => 'Super Admin User',
'updated_by' => 'Super Admin User',
                'created_at' => Carbon::parse('2023-05-16 11:28:04'),
                'updated_at' => Carbon::parse('2025-05-02 07:58:56'),
            ],
            [
                'id' => 29,
                'name' => 'Academic Session 2024-25 Passing Out Students',
                'class_numeric' => 2024,
                'teacher_id' => null, // Placeholder, will be replaced with random teacher_id
'created_by' => 'Super Admin User',
'updated_by' => 'Super Admin User',
                'created_at' => Carbon::parse('2025-05-02 09:29:37'),
                'updated_at' => Carbon::parse('2025-05-02 09:29:37'),
            ],
            [
                'id' => 30,
                'name' => 'Montessori 1',
                'class_numeric' => 16,
                'teacher_id' => null, // Placeholder, will be replaced with random teacher_id
                'created_by' => 'Super Admin User',
                'updated_by' => 'Super Admin User',
                'created_at' => Carbon::parse('2025-05-02 07:57:21'),
                'updated_at' => Carbon::parse('2025-05-02 07:58:40'),
            ],
        ];

        // Assign a random teacher_id (from users table) to each class
        foreach ($classes as &$class) {
            // Find a random teacher for this class
            $randomUserId = $teacherUserIds[array_rand($teacherUserIds)];
            // Get the teacher model for this user
            $teacher = \App\Models\Teacher::where('user_id', $randomUserId)->first();
            if ($teacher) {
                $class['teacher_id'] = $teacher->id;
            } else {
                // If no teacher found for this user, pick any teacher
                $class['teacher_id'] = \App\Models\Teacher::inRandomOrder()->value('id');
            }
        }
        unset($class);

        foreach ($classes as $class) {
            ClassModel::create($class);
        }

        $this->command->info('Classes seeded successfully!');
    }
}
