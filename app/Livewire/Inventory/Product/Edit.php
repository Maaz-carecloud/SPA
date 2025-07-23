<?php

namespace App\Livewire\Inventory\Product;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;
    
    public $productId;
    public $product_category_id;
    public $name;
    public $description;
    public $buying_price;
    public $selling_price;
    public $quantity;
    public $image;
    public $newImage;
    public $barcode;
    public $is_active;

    protected $rules = [
        'product_category_id' => 'required|exists:product_categories,id',
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'buying_price' => 'required|numeric|min:0',
        'selling_price' => 'required|numeric|min:0',
        'quantity' => 'required|integer|min:0',
        'newImage' => 'nullable|image|max:2048',
        'barcode' => 'nullable|string|max:255',
        'is_active' => 'required|boolean',
    ];    
    
    public function mount($id)
    {
        $product = Product::findOrFail($id);
        $this->productId = $product->id;
        $this->product_category_id = $product->product_category_id;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->buying_price = $product->buying_price;
        $this->selling_price = $product->selling_price;
        $this->quantity = $product->quantity;
        $this->image = $product->image;
        $this->barcode = $product->barcode;
        $this->is_active = $product->is_active;
    }

    public function syncDescription($content)
    {
        $this->description = $content;
    }    
    
    public function updateProduct()
    {
        // Custom validation for barcode uniqueness (excluding current product)
        $this->validate([
            'product_category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'buying_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'newImage' => 'nullable|image|max:2048',
            'barcode' => 'nullable|string|max:255|unique:products,barcode,' . $this->productId,
            'is_active' => 'required|boolean',
        ]);
        
        $product = Product::findOrFail($this->productId);
        
        $imagePath = $this->image; // Keep existing image
        if ($this->newImage) {
            // Delete old image if exists
            if ($this->image && Storage::disk('public')->exists($this->image)) {
                Storage::disk('public')->delete($this->image);
            }
            $imagePath = $this->newImage->store('products', 'public');
        }
        
        $product->update([
            'product_category_id' => $this->product_category_id,
            'name' => $this->name,
            'description' => $this->description,
            'buying_price' => $this->buying_price,
            'selling_price' => $this->selling_price,
            'quantity' => $this->quantity,
            'image' => $imagePath,
            'barcode' => $this->barcode,
            'is_active' => $this->is_active,
            'updated_by' => Auth::user()->name,
        ]);
        
        $this->dispatch('success', message: 'Product updated successfully.');
        $this->redirect('/products', navigate: true);
    }

    public function render()
    {
        $categories = ProductCategory::where('is_active', 1)->get();
        return view('livewire.inventory.product.edit', [
            'categories' => $categories,
        ]);
    }
}
