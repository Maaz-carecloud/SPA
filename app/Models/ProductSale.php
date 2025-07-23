<?php

namespace App\Models;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;

class ProductSale extends Model
{
    protected $fillable = [
        'user_id',
        'reference_no',
        'sale_date',
        'sale_slip',
        'description',
        'payment_status',
        'refund_status',
        'discount',
        'tax',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'sale_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(ProductSaleItem::class, 'product_sale_id');
    }

    public function payments()
    {
        return $this->hasMany(ProductSalePaid::class, 'product_sale_id');
    }
}
