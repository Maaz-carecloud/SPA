<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'laravel_logger_activity';
    // Add fillable or guarded as needed
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
} 