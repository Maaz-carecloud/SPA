<?php

namespace App\Livewire\Inventory\Supplier;

use App\Models\ProductSupplier;
use Livewire\Component;

class View extends Component
{
    public $supplier;
    public $totalProducts;
    public $totalPurchases;
    public $purchaseValue;
    public $lastPurchaseDate;
    
    public function mount($id)
    {
        $this->supplier = ProductSupplier::findOrFail($id);
        $this->calculateMetrics();
    }
    
    private function calculateMetrics()
    {
        // Calculate related metrics (you can expand this based on your relationships)
        $this->totalProducts = 0; // Will need to implement product-supplier relationship count
        $this->totalPurchases = 0; // Count of purchases from this supplier
        $this->purchaseValue = 0; // Total value of purchases from this supplier
        $this->lastPurchaseDate = null; // Last purchase date
        
        // Note: These would need actual implementation based on your relationships
        // For now, setting mock values
        $this->totalProducts = rand(5, 50);
        $this->totalPurchases = rand(1, 30);
        $this->purchaseValue = rand(5000, 200000);
        $this->lastPurchaseDate = now()->subDays(rand(1, 30))->format('Y-m-d');
    }
    
    public function render()
    {
        return view('livewire.inventory.supplier.view');
    }
}
