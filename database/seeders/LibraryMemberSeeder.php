<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\LibraryMember;
use Carbon\Carbon;

class LibraryMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all students who don't have library member records
        $students = Student::with('user')
            ->whereNotIn('id', function($query) {
                $query->select('user_id')->from('library_members')->whereNotNull('user_id');
            })
            ->get();

        $memberCounter = LibraryMember::max('id') ?? 0;

        foreach ($students as $student) {
            if ($student->user) {
                $memberCounter++;
                
                // Generate library ID (year + sequential number)
                $year = date('Y');
                $sequence = str_pad($memberCounter, 2, '0', STR_PAD_LEFT);
                $libraryId = $year . $sequence;

                LibraryMember::create([
                    'library_id' => $libraryId,
                    'user_id' => $student->id,
                    'name' => $student->user->name,
                    'email' => $student->user->email ?? null,
                    'phone' => null, // You can add phone logic if needed
                    'fee' => 0.00,
                    'library_join_date' => Carbon::now(),
                ]);
            }
        }
    }
}
