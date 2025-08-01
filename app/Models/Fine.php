<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Fine extends Model
{
    use HasFactory;

    protected $table = 'fines';
    protected $primaryKey = 'id';

    protected $fillable = [
        'issue_id',
        'amount',
        'reason',
        'status',
        'fine_date',
        'paid_date',
        'paid_amount',
        'payment_note',
        'added_by',
        'paid_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'fine_date' => 'date',
        'paid_date' => 'date',
    ];

    /**
     * Get the issue that this fine belongs to.
     */
    public function issue(): BelongsTo
    {
        return $this->belongsTo(Issue::class, 'issue_id', 'id');
    }

    /**
     * Scope for pending fines.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for paid fines.
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope for waived fines.
     */
    public function scopeWaived($query)
    {
        return $query->where('status', 'waived');
    }

    /**
     * Check if the fine is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the fine is paid.
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Check if the fine is waived.
     */
    public function isWaived(): bool
    {
        return $this->status === 'waived';
    }

    /**
     * Mark the fine as paid.
     */
    public function markAsPaid(float $amount = null, string $note = null): bool
    {
        return $this->update([
            'status' => 'paid',
            'paid_date' => now()->toDateString(),
            'paid_amount' => $amount ?? $this->amount,
            'payment_note' => $note,
            'paid_by' => Auth::user()->name ?? 'System',
        ]);
    }

    /**
     * Mark the fine as waived.
     */
    public function markAsWaived(string $note = null): bool
    {
        return $this->update([
            'status' => 'waived',
            'paid_date' => now()->toDateString(),
            'payment_note' => $note,
            'paid_by' => Auth::user()->name ?? 'System',
        ]);
    }

    /**
     * Get the outstanding amount for this fine.
     */
    public function getOutstandingAmountAttribute(): float
    {
        if ($this->isPaid()) {
            return max(0, $this->amount - ($this->paid_amount ?? 0));
        }
        
        return $this->isWaived() ? 0 : $this->amount;
    }
}
