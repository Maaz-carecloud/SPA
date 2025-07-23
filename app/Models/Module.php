<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = ['name', 'created_by', 'updated_by'];

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'module_name', 'name');
    }
}
