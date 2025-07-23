<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LeaveRecord extends Model
{
    protected $fillable = [
        'user_id',
        'leave_type_id',
        'leave_reason',
        'attachment',
        'status',
        'date_from',
        'date_to',
        'total_days',
        'created_by',
        'updated_by',
        'class_id',
        'section_id',
    ];

    protected $casts = [
        'date_from' => 'date',
        'date_to' => 'date',
        'status' => 'boolean',
    ];

    // Automatically calculate total days when dates are set
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($leaveRecord) {
            if ($leaveRecord->date_from && $leaveRecord->date_to) {
                $from = Carbon::parse($leaveRecord->date_from);
                $to = Carbon::parse($leaveRecord->date_to);
                $leaveRecord->total_days = $from->diffInDays($to) + 1; // +1 to include both start and end date
            }
        });
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', false);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeDateRange($query, $from, $to)
    {
        return $query->whereBetween('date_from', [$from, $to])
                    ->orWhereBetween('date_to', [$from, $to]);
    }

    // Accessors
    public function getStatusColorAttribute()
    {
        return $this->status ? 'green' : 'red';
    }

    public function getIsActiveAttribute()
    {
        return $this->status === true;
    }

    public function getIsInactiveAttribute()
    {
        return $this->status === false;
    }

    // Methods
    public function activate()
    {
        $this->update(['status' => true]);
    }

    public function deactivate()
    {
        $this->update(['status' => false]);
    }
}
