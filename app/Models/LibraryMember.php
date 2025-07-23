<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class LibraryMember extends Model
{
    protected $table = 'library_members';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'library_id',
        'user_id',
        'name',
        'email',
        'phone',
        'fee',
        'library_join_date',
    ];

    protected $casts = [
        'library_join_date' => 'date',
        'fee' => 'decimal:2',
    ];

    /**
     * Get the student that owns the library member.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'user_id');
    }

    /**
     * Get the user through the student relationship.
     */
    public function user()
    {
        return $this->student->user();
    }

    /**
     * Generate next library ID
     */
    public static function generateLibraryId(): string
    {
        $lastMember = static::orderBy('id', 'desc')->first();
        
        if ($lastMember && $lastMember->library_id) {
            // Extract numeric part and increment
            $lastId = intval(substr($lastMember->library_id, -2));
            $newId = $lastId + 1;
        } else {
            // First member for this year
            $newId = 1;
        }
        
        $year = date('Y');
        return $year . str_pad($newId, 2, '0', STR_PAD_LEFT);
    }
}
