<?php

namespace App\Livewire\Inventory\Sale;

use App\Models\ProductSale;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class View extends Component
{
    public $sale;
    public $grandTotal;

    public function mount($id)
    {
        $this->sale = ProductSale::with([
            'user', 
            'items.product'
        ])->findOrFail($id);

        $this->calculateGrandTotal();
    }

    private function calculateGrandTotal()
    {
        $subtotal = $this->sale->items->sum(function($item) {
            return $item->quantity * $item->unit_price;
        });
        
        $discountAmount = $subtotal * ($this->sale->discount / 100);
        $taxAmount = ($subtotal - $discountAmount) * ($this->sale->tax / 100);
        $this->grandTotal = $subtotal - $discountAmount + $taxAmount;
    }

    public function getSubtotalProperty()
    {
        return $this->sale->items->sum(function($item) {
            return $item->quantity * $item->unit_price;
        });
    }

    public function getDiscountAmountProperty()
    {
        return $this->subtotal * ($this->sale->discount / 100);
    }

    public function getTaxAmountProperty()
    {
        return ($this->subtotal - $this->discountAmount) * ($this->sale->tax / 100);
    }

    #[Title('View Sale')]
    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.inventory.sale.view');
    }
}
