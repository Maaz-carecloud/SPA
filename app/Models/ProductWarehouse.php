<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductWarehouse extends Model
{
    protected $fillable = [
        'name',
        'code',
        'email',
        'phone',
        'address',
        'created_by',
        'updated_by',
        'is_active'
    ];
    
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_warehouse_product', 'warehouse_id', 'product_id');
    }

    public function purchases()
    {
        return $this->hasMany(ProductPurchase::class, 'product_warehouse_id');
    }
}
