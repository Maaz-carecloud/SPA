<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSalePaid extends Model
{
    protected $fillable = [
        'product_sale_id',
        'reference_no',
        'paid_amount',
        'payment_method',
        'payment_date',
        'paid_slip',
        'description',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'paid_amount' => 'double',
        'payment_date' => 'date',
        'created_by' => 'integer',
        'updated_by' => 'integer'
    ];

    public function productSale()
    {
        return $this->belongsTo(ProductSale::class);
    }

    public function getPaymentMethodAttribute($value)
    {
        return match ($value) {
            'cash' => 'Cash',
            'cheque' => 'Cheque',
            'credit_card' => 'Credit Card',
            'other' => 'Other',
            default => 'Unknown',
        };
    }

    public function setPaymentMethodAttribute($value)
    {
        $this->attributes['payment_method'] = match ($value) {
            'Cash' => 'cash',
            'Cheque' => 'cheque',
            'Credit Card' => 'credit_card',
            'Other' => 'other',
            default => 'cash',
        };
    }
   
}