<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $fillable = [
        'name',
        'description',
        'created_by',
        'updated_by',
        'is_active'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'product_category_id');
    }

}
