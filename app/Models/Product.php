<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = [
        'product_category_id',
        'name',
        'description',
        'buying_price',
        'selling_price',
        'quantity',
        'image',
        'barcode',
        'created_by',
        'updated_by',
        'is_active'
    ];

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();
        
        // Handle image deletion when product is deleted
        static::deleting(function ($product) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function purchases()
    {
        return $this->hasMany(ProductPurchase::class);
    }

    public function suppliers()
    {
        return $this->belongsToMany(ProductSupplier::class, 'product_supplier_product', 'product_id', 'supplier_id');
    }

    public function warehouses()
    {
        return $this->belongsToMany(ProductWarehouse::class, 'product_warehouse_product', 'product_id', 'warehouse_id');
    }

    public function sales()
    {
        return $this->hasMany(ProductSaleItem::class, 'product_id');
    }
}
