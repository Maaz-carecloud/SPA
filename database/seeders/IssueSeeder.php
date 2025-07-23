<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Issue;
use App\Models\Book;
use App\Models\LibraryMember;
use Carbon\Carbon;

class IssueSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get some book IDs and library member IDs to use as foreign keys
        $bookIds = Book::pluck('id')->toArray();
        $libraryMemberIds = LibraryMember::pluck('id')->toArray();
        
        if (empty($bookIds)) {
            $this->command->info('No books found. Please run BookSeeder first.');
            return;
        }
        
        if (empty($libraryMemberIds)) {
            $this->command->info('No library members found. Please run LibraryMemberSeeder first.');
            return;
        }

        $issues = [
            [
                'library_id' => 'LIB001',
                'book_id' => $bookIds[array_rand($bookIds)],
                'library_member_id' => $libraryMemberIds[array_rand($libraryMemberIds)],
                'serial_no' => 'SN001',
                'issue_date' => Carbon::now()->subDays(15)->format('Y-m-d'),
                'due_date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                'return_date' => null,
                'note' => 'First issue - overdue',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'library_id' => 'LIB002',
                'book_id' => $bookIds[array_rand($bookIds)],
                'library_member_id' => $libraryMemberIds[array_rand($libraryMemberIds)],
                'serial_no' => 'SN002',
                'issue_date' => Carbon::now()->subDays(10)->format('Y-m-d'),
                'due_date' => Carbon::now()->addDays(4)->format('Y-m-d'),
                'return_date' => null,
                'note' => 'Active issue',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'library_id' => 'LIB003',
                'book_id' => $bookIds[array_rand($bookIds)],
                'library_member_id' => $libraryMemberIds[array_rand($libraryMemberIds)],
                'serial_no' => 'SN003',
                'issue_date' => Carbon::now()->subDays(20)->format('Y-m-d'),
                'due_date' => Carbon::now()->subDays(6)->format('Y-m-d'),
                'return_date' => Carbon::now()->subDays(3)->format('Y-m-d'),
                'note' => 'Returned late',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'library_id' => 'LIB004',
                'book_id' => $bookIds[array_rand($bookIds)],
                'library_member_id' => $libraryMemberIds[array_rand($libraryMemberIds)],
                'serial_no' => 'SN004',
                'issue_date' => Carbon::now()->subDays(7)->format('Y-m-d'),
                'due_date' => Carbon::now()->addDays(7)->format('Y-m-d'),
                'return_date' => null,
                'note' => 'Current issue',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'library_id' => 'LIB005',
                'book_id' => $bookIds[array_rand($bookIds)],
                'library_member_id' => $libraryMemberIds[array_rand($libraryMemberIds)],
                'serial_no' => 'SN005',
                'issue_date' => Carbon::now()->subDays(30)->format('Y-m-d'),
                'due_date' => Carbon::now()->subDays(16)->format('Y-m-d'),
                'return_date' => Carbon::now()->subDays(14)->format('Y-m-d'),
                'note' => 'Returned on time',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        // Add more random issues
        for ($i = 6; $i <= 20; $i++) {
            $issueDate = Carbon::now()->subDays(rand(1, 60));
            $dueDate = $issueDate->copy()->addDays(14); // 2 weeks loan period
            $isReturned = rand(0, 1);
            $returnDate = $isReturned ? $dueDate->copy()->addDays(rand(-3, 5)) : null;
            
            $issues[] = [
                'library_id' => 'LIB' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'book_id' => $bookIds[array_rand($bookIds)],
                'library_member_id' => $libraryMemberIds[array_rand($libraryMemberIds)],
                'serial_no' => 'SN' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'issue_date' => $issueDate->format('Y-m-d'),
                'due_date' => $dueDate->format('Y-m-d'),
                'return_date' => $returnDate ? $returnDate->format('Y-m-d') : null,
                'note' => $isReturned ? 'Book returned' : 'Active loan',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        foreach ($issues as $issue) {
            Issue::create($issue);
        }
        
        $this->command->info('Issues table seeded successfully!');
    }
}
