<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Issue extends Model
{
    use HasFactory;

    protected $table = 'issues';
    protected $primaryKey = 'issue_id';

    protected $fillable = [
        'library_id',
        'book_id',
        'library_member_id',
        'serial_no',
        'issue_date',
        'due_date',
        'return_date',
        'note',
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
        return $this->belongsTo(Book::class, 'book_id');
    }

    /**
     * Get the library member who issued the book.
     */
    public function libraryMember(): BelongsTo
    {
        return $this->belongsTo(LibraryMember::class, 'library_member_id', 'id');
    }

    /**
     * Get the fines associated with this issue.
     */
    public function fines()
    {
        return $this->hasMany(Fine::class, 'issue_id', 'issue_id');
    }

    /**
     * Scope for active issues (not returned yet).
     */
    public function scopeActive($query)
    {
        return $query->whereNull('return_date');
    }

    /**
     * Scope for returned issues.
     */
    public function scopeReturned($query)
    {
        return $query->whereNotNull('return_date');
    }

    /**
     * Scope for overdue issues.
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->whereNull('return_date');
    }

    /**
     * Check if the issue is overdue.
     */
    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date < now() && is_null($this->return_date);
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
        return !is_null($this->return_date);
    }

    /**
     * Generate next issue ID.
     */
    public static function generateIssueId(): string
    {
        $lastIssue = static::orderBy('issue_id', 'desc')->first();
        $nextNumber = $lastIssue ? (int) substr($lastIssue->library_id, 3) + 1 : 1;
        
        return 'LIB' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Scope for searching issues.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('library_id', 'like', "%{$search}%")
              ->orWhere('serial_no', 'like', "%{$search}%")
              ->orWhere('note', 'like', "%{$search}%")
              ->orWhereHas('book', function ($bookQuery) use ($search) {
                  $bookQuery->where('name', 'like', "%{$search}%")
                           ->orWhere('author', 'like', "%{$search}%")
                           ->orWhere('subject_code', 'like', "%{$search}%");
              });
        });
    }

    /**
     * Boot method to auto-generate Library ID.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($issue) {
            if (empty($issue->library_id)) {
                $issue->library_id = static::generateIssueId();
            }
            
            if (empty($issue->issue_date)) {
                $issue->issue_date = now();
            }
        });
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
     * Get total paid fine amount for this issue.
     */
    public function getPaidFineAmountAttribute(): float
    {
        return $this->fines()->where('status', 'paid')->sum('paid_amount');
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
    public function addFine(float $amount, string $reason = 'Overdue fine', int $addedBy = null): Fine
    {
        return $this->fines()->create([
            'amount' => $amount,
            'reason' => $reason,
            'status' => 'pending',
            'fine_date' => now()->toDateString(),
            'added_by' => $addedBy,
        ]);
    }
}
