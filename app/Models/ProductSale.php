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

    public static function generateReferenceNo()
    {
        $prefix = 'SAL';
        $date = date('Ymd');
        $lastSale = self::whereDate('created_at', date('Y-m-d'))->latest()->first();
        
        if ($lastSale) {
            $lastNumber = (int) substr($lastSale->reference_no, -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . '-' . $date . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
}
