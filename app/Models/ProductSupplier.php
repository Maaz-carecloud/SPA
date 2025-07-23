<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSupplier extends Model
{
    protected $fillable = [
        'name',
        'company_name',
        'email',
        'phone',
        'address',
        'created_by',
        'updated_by',
        'is_active'
    ];
    
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_supplier_product', 'supplier_id', 'product_id');
    }

    public function purchases()
    {
        return $this->hasMany(ProductPurchase::class, 'supplier_id');
    }
}
