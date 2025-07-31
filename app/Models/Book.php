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
}
