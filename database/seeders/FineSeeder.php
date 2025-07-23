<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Issue;
use App\Models\Fine;

class FineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some overdue issues to add fines to
        $overdueIssues = Issue::whereNull('return_date')
                             ->where('due_date', '<', now())
                             ->limit(5)
                             ->get();

        foreach ($overdueIssues as $issue) {
            // Calculate days overdue
            $daysOverdue = now()->diffInDays($issue->due_date);
            
            // Add a fine (PKR 10 per day overdue)
            $fineAmount = $daysOverdue * 10;
            
            Fine::create([
                'issue_id' => $issue->issue_id,
                'amount' => $fineAmount,
                'reason' => "Overdue fine for {$daysOverdue} days",
                'status' => 'pending',
                'fine_date' => now()->toDateString(),
                'added_by' => 1, // Assuming admin user ID is 1
            ]);
        }

        // Add some paid fines
        $paidIssues = Issue::whereNotNull('return_date')
                          ->limit(3)
                          ->get();

        foreach ($paidIssues as $issue) {
            $fine = Fine::create([
                'issue_id' => $issue->issue_id,
                'amount' => 50.00,
                'reason' => 'Late return fine',
                'status' => 'paid',
                'fine_date' => $issue->return_date->subDays(2),
                'paid_date' => $issue->return_date,
                'paid_amount' => 50.00,
                'added_by' => 1,
                'paid_by' => 1,
            ]);
        }

        // Add a waived fine
        $issue = Issue::first();
        if ($issue) {
            Fine::create([
                'issue_id' => $issue->issue_id,
                'amount' => 25.00,
                'reason' => 'Minor delay waived',
                'status' => 'waived',
                'fine_date' => now()->subDays(5),
                'paid_date' => now()->subDays(3),
                'payment_note' => 'Fine waived due to technical issue',
                'added_by' => 1,
                'paid_by' => 1,
            ]);
        }
    }
}
