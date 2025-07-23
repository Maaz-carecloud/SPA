<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPurchasedItem extends Model
{
    protected $fillable = [
        'product_purchase_id',
        'product_id',
        'unit_price',
        'quantity',
        'created_by',
        'updated_by',
    ];

    public function productPurchase()
    {
        return $this->belongsTo(ProductPurchase::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
