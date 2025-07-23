<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $fillable = ['name', 'created_by', 'updated_by'];

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
