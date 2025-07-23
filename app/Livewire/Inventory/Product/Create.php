<?php

namespace App\Livewire\Inventory\Product;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;
    
    public $product_category_id;
    public $name;
    public $description;
    public $buying_price;
    public $selling_price;
    public $quantity;
    public $image;
    public $barcode;
    public $is_active = 1;

    protected $rules = [
        'product_category_id' => 'required|exists:product_categories,id',
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'buying_price' => 'required|numeric|min:0',
        'selling_price' => 'required|numeric|min:0',
        'quantity' => 'required|integer|min:0',
        'image' => 'nullable|image|max:2048',
        'barcode' => 'nullable|string|max:255|unique:products,barcode',
        'is_active' => 'required|boolean',
    ];

    public function syncDescription($content)
    {
        $this->description = $content;
    }    
    
    public function createProduct()
    {
        $this->validate();
        
        $imagePath = null;

        if ($this->image) {
            $imagePath = $this->image->store('products', 'public');
        }
        
        Product::create([
            'product_category_id' => $this->product_category_id,
            'name' => $this->name,
            'description' => $this->description,
            'buying_price' => $this->buying_price,
            'selling_price' => $this->selling_price,
            'quantity' => $this->quantity,
            'image' => $imagePath,
            'barcode' => $this->barcode,
            'is_active' => $this->is_active,
            'created_by' => Auth::user()->name,
            'updated_by' => Auth::user()->name,
        ]);
        
        $this->dispatch('success', message: 'Product created successfully.');
        $this->redirect('/products', navigate: true);
    }

    public function render()
    {
        $categories = ProductCategory::where('is_active', 1)->get();

        return view('livewire.inventory.product.create', [
            'categories' => $categories,
        ]);
    }
}
