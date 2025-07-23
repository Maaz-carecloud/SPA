<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSaleItem extends Model
{
    protected $fillable = [
        'product_sale_id',
        'product_id',
        'serial_no',
        'unit_price',
        'quantity',
        'created_by',
        'updated_by'
    ];

    public function productSale()
    {
        return $this->belongsTo(ProductSale::class, 'product_sale_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
