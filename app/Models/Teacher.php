<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'user_id',
        'designation_id',
        'joining_date',
        'qualification',
        'basic_salary',
        'created_by',
        'updated_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function classes()
    {
        return $this->hasMany(ClassModel::class, 'teacher_id');
    }
}
