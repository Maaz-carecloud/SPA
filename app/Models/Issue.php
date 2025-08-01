<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Issue extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'issue_date',
        'due_date',
        'return_date',
        'notes',
        'status',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
    ];

    /**
     * Get the book that was issued.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Get the user who issued the book.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the fines associated with this issue.
     */
    public function fines()
    {
        return $this->hasMany(Fine::class, 'issue_id', 'id');
    }

    /**
     * Scope for active issues (not returned yet).
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'issued');
    }

    /**
     * Scope for returned issues.
     */
    public function scopeReturned($query)
    {
        return $query->where('status', 'returned');
    }

    /**
     * Scope for overdue issues.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue')
                    ->orWhere(function($q) {
                        $q->where('due_date', '<', now())
                          ->where('status', 'issued');
                    });
    }

    /**
     * Check if the issue is overdue.
     */
    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date < now() && $this->status !== 'returned';
    }

    /**
     * Get days overdue.
     */
    public function getDaysOverdueAttribute(): int
    {
        if (!$this->is_overdue) {
            return 0;
        }
        
        return $this->due_date->diffInDays(now());
    }

    /**
     * Check if the book is returned.
     */
    public function getIsReturnedAttribute(): bool
    {
        return $this->status === 'returned';
    }

    /**
     * Auto-update status based on dates
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($issue) {
            // Only set defaults if not already provided
            if (empty($issue->issue_date)) {
                $issue->issue_date = now();
            }
            
            if (empty($issue->due_date)) {
                // If issue_date is set, calculate from it, otherwise use today + 14 days
                $issueDate = $issue->issue_date;
                if (is_string($issueDate)) {
                    $issueDate = Carbon::parse($issueDate);
                }
                $baseDate = $issueDate ?: now();
                $issue->due_date = $baseDate->copy()->addDays(14);
            }
            
            if (empty($issue->status)) {
                $issue->status = 'issued';
            }
        });

        static::created(function ($issue) {
            // Update book stock when issue is created
            if ($issue->book && $issue->status === 'issued') {
                $issue->book->issueBook();
            }
        });

        static::updating(function ($issue) {
            // Auto-update status when return_date is set
            if ($issue->return_date && $issue->status !== 'returned') {
                $issue->status = 'returned';
            }
            
            // Auto-update status when overdue
            if ($issue->due_date < now() && $issue->status === 'issued') {
                $issue->status = 'overdue';
            }
        });

        static::updated(function ($issue) {
            // Update book stock when issue status changes to returned
            // Note: We also handle this explicitly in returnBook() method for reliability
            if ($issue->wasChanged('status') && $issue->status === 'returned') {
                if ($issue->book) {
                    $issue->book->returnBook();
                }
            }
        });
    }

    /**
     * Calculate fine amount based on overdue days.
     */
    public function calculateFineAmount(): float
    {
        if (!$this->is_overdue) {
            return 0;
        }
        
        return $this->days_overdue * 10; // Rs. 10 per day
    }

    /**
     * Alias for calculateFineAmount() for convenience.
     */
    public function calculateFine(): float
    {
        return $this->calculateFineAmount();
    }

    /**
     * Get days overdue for fine calculation.
     */
    public function getDaysOverdue(): int
    {
        return $this->days_overdue;
    }

    /**
     * Get total fine amount for this issue.
     */
    public function getTotalFineAmountAttribute(): float
    {
        return $this->fines()->sum('amount');
    }

    /**
     * Get total pending fine amount for this issue.
     */
    public function getPendingFineAmountAttribute(): float
    {
        return $this->fines()->where('status', 'pending')->sum('amount');
    }

    /**
     * Check if this issue has any pending fines.
     */
    public function getHasPendingFinesAttribute(): bool
    {
        return $this->fines()->where('status', 'pending')->exists();
    }

    /**
     * Add a fine to this issue.
     */
    public function addFine(float $amount, string $reason = 'Overdue fine', ?string $addedBy = null): Fine
    {
        return $this->fines()->create([
            'amount' => $amount,
            'reason' => $reason,
            'status' => 'pending',
            'fine_date' => now()->toDateString(),
            'added_by' => $addedBy ?? Auth::user()->name ?? 'System',
        ]);
    }

    /**
     * Return the book and calculate fine if overdue.
     */
    public function returnBook(?\Carbon\Carbon $returnDate = null, ?string $notes = null): array
    {
        $returnDate = $returnDate ?? now();
        $returnedBy = Auth::user()->name ?? 'System';
        
        // Update issue with return date (stock will be updated by model event)
        $this->update([
            'return_date' => $returnDate->toDateString(),
            'status' => 'returned',
            'notes' => $notes ? ($this->notes ? $this->notes . "\n\nReturn Notes: " . $notes : "Return Notes: " . $notes) : $this->notes
        ]);

        $fineAmount = 0;
        $message = 'Book returned successfully.';

        // Calculate and add fine if overdue
        if ($returnDate->gt($this->due_date)) {
            $overdueDays = $this->due_date->diffInDays($returnDate);
            $fineAmount = $overdueDays * 10; // Rs. 10 per day

            $this->addFine(
                $fineAmount,
                "Overdue fine: {$overdueDays} days late",
                $returnedBy
            );

            $message = "Book returned with {$overdueDays} days late.";
        }

        return [
            'success' => true,
            'message' => $message,
            'fine_amount' => $fineAmount,
            'overdue_days' => $returnDate->gt($this->due_date) ? $this->due_date->diffInDays($returnDate) : 0
        ];
    }
}
