<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPurchase extends Model
{
    protected $fillable = [
        'product_supplier_id',
        'product_warehouse_id',
        'purchase_date',
        'purchase_slip',
        'reference_no',
        'description',
        'payment_status',
        'refund_status',
        'discount',
        'tax',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
    ];

    public function supplier()
    {
        return $this->belongsTo(ProductSupplier::class, 'product_supplier_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(ProductWarehouse::class, 'product_warehouse_id');
    }

    public function purchasedItems()
    {
        return $this->hasMany(ProductPurchasedItem::class, 'product_purchase_id');
    }

    public function payments()
    {
        return $this->hasMany(ProductPurchasePaid::class, 'product_purchase_id');
    }

    public static function generateReferenceNo()
    {
        $prefix = 'PUR';
        $date = date('Ymd');
        $lastPurchase = self::whereDate('created_at', date('Y-m-d'))->latest()->first();
        
        if ($lastPurchase) {
            $lastNumber = (int) substr($lastPurchase->reference_no, -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . '-' . $date . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
}
