<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    protected $fillable = [
        'module_name',
        'name',
        'guard_name',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}