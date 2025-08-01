<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject_code',
        'author',
        'price',
        'quantity',
        'due_quantity',
        'rack',
    ];

    protected $casts = [
        'price' => 'integer',
        'quantity' => 'integer',
        'due_quantity' => 'integer',
    ];

    public function getAvailableQuantityAttribute(): int
    {
        return $this->quantity - $this->due_quantity;
    }

    public function scopeAvailable($query)
    {
        return $query->whereRaw('quantity > due_quantity');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('author', 'like', "%{$search}%")
              ->orWhere('subject_code', 'like', "%{$search}%");
        });
    }

    /**
     * Get all issues for this book.
     */
    public function issues()
    {
        return $this->hasMany(Issue::class, 'book_id', 'id');
    }

    /**
     * Issue a book (increase due_quantity).
     */
    public function issueBook(): bool
    {
        if ($this->available_quantity > 0) {
            $this->increment('due_quantity');
            $this->refresh(); // Refresh to get updated values
            return true;
        }
        return false;
    }

    /**
     * Return a book (decrease due_quantity).
     */
    public function returnBook(): bool
    {
        if ($this->due_quantity > 0) {
            $this->decrement('due_quantity');
            $this->refresh(); // Refresh to get updated values
            return true;
        }
        return false;
    }

    /**
     * Check if book is available for issuing.
     */
    public function isAvailable(): bool
    {
        return $this->available_quantity > 0;
    }
}
