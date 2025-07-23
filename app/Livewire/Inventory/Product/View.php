<?php

namespace App\Livewire\Inventory\Product;

use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class View extends Component
{
    public $product;
    public $profit;
    public $profitMargin;
    public $stockStatus;
    
    public function mount($id)
    {
        $this->product = Product::with('category')->findOrFail($id);
        $this->calculateMetrics();
    }
    
    private function calculateMetrics()
    {
        $this->profit = $this->product->selling_price - $this->product->buying_price;
        
        if ($this->product->buying_price > 0) {
            $this->profitMargin = ($this->profit / $this->product->buying_price) * 100;
        } else {
            $this->profitMargin = 0;
        }
        
        // Determine stock status
        if ($this->product->quantity <= 0) {
            $this->stockStatus = 'danger';
        } elseif ($this->product->quantity <= 10) {
            $this->stockStatus = 'warning';
        } else {
            $this->stockStatus = 'success';
        }
    }
    
    public function render()
    {
        return view('livewire.inventory.product.view')
            ->layout('components.layouts.app', ['title' => 'View Product - ' . $this->product->name]);
    }
}
