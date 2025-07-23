<?php

namespace App\Livewire\Inventory\Purchase;

use App\Models\ProductPurchase;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class View extends Component
{
    public $purchase;
    public $grandTotal;

    public function mount($id)
    {
        $this->purchase = ProductPurchase::with([
            'supplier', 
            'warehouse', 
            'purchasedItems.product'
        ])->findOrFail($id);

        $this->calculateGrandTotal();
    }

    private function calculateGrandTotal()
    {
        $subtotal = $this->purchase->purchasedItems->sum(function($item) {
            return $item->quantity * $item->unit_price;
        });
        
        $discountAmount = $subtotal * ($this->purchase->discount / 100);
        $taxAmount = ($subtotal - $discountAmount) * ($this->purchase->tax / 100);
        $this->grandTotal = $subtotal - $discountAmount + $taxAmount;
    }

    public function getSubtotalProperty()
    {
        return $this->purchase->purchasedItems->sum(function($item) {
            return $item->quantity * $item->unit_price;
        });
    }

    public function getDiscountAmountProperty()
    {
        return $this->subtotal * ($this->purchase->discount / 100);
    }

    public function getTaxAmountProperty()
    {
        return ($this->subtotal - $this->discountAmount) * ($this->purchase->tax / 100);
    }

    #[Title('View Purchase')]
    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.inventory.purchase.view');
    }
}
