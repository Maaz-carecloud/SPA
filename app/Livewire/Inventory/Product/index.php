<?php

namespace App\Livewire\Inventory\Product;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class Index extends Component
{
    use WithFileUploads;

    public function mount(){
        // Set default values
        $this->is_active = 1; // Default to active
        $this->categories = $this->getCategories(); // Load categories on mount
    }

    //Modal related methods
    public $modalTitle = 'Create Product';
    public $modalAction = 'create-product';
    public $is_edit = false;
    public $deleteId;
    public $is_delete = false;

    //form related methods
    public $product_category_id = '';
    public $name = '';
    public $description = '';
    public $buying_price = '';
    public $selling_price = '';
    public $quantity = '';
    public $barcode = '';
    public $is_active = true;

    // Image handling
    public $currentImage = null;
    public $imageToUpload = null;

    // Categories
    public $categories;
    
    #[On('create-product-close')]
    #[On('edit-product-close')]
    public function resetFieldsAndModal(){
        $this->reset(['product_category_id', 'name', 'description', 'buying_price', 'selling_price', 'quantity', 'barcode', 'is_active', 'currentImage', 'imageToUpload']);
        $this->modalTitle = 'Create Product';
        $this->modalAction = 'create-product';
        $this->is_edit = false;
        $this->is_active = 1; // Default to active
        $this->dispatch('hide-modal');
        $this->dispatch('datatable-reinit');
    }

    #[On('edit-mode')]
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        
        $this->product_category_id = $product->product_category_id;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->buying_price = $product->buying_price;
        $this->selling_price = $product->selling_price;
        $this->quantity = $product->quantity;
        $this->barcode = $product->barcode;
        $this->is_active = $product->is_active;
        $this->currentImage = $product->image;

        $this->modalTitle = 'Edit Product';
        $this->modalAction = 'edit-product';
        $this->is_edit = true;
        $this->deleteId = $id;
    }

    #[On('create-product')]
    public function store()
    {
        $this->validate([
            'product_category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'buying_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'barcode' => 'nullable|string|max:255|unique:products,barcode',
            'imageToUpload' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ], [
            'product_category_id.required' => 'Please select a category.',
            'product_category_id.exists' => 'Selected category is invalid.',
            'name.required' => 'Product name is required.',
            'buying_price.required' => 'Buying price is required.',
            'buying_price.numeric' => 'Buying price must be a valid number.',
            'selling_price.required' => 'Selling price is required.',
            'selling_price.numeric' => 'Selling price must be a valid number.',
            'quantity.required' => 'Quantity is required.',
            'quantity.integer' => 'Quantity must be a whole number.',
            'barcode.unique' => 'This barcode is already in use.',
            'imageToUpload.image' => 'Please upload a valid image file.',
            'imageToUpload.mimes' => 'Image must be jpeg, png, jpg, or gif format.',
            'imageToUpload.max' => 'Image size must not exceed 2MB.'
        ]);

        $imagePath = null;
        if ($this->imageToUpload) {
            $imagePath = $this->imageToUpload->store('products', 'public');
        }

        Product::create([
            'product_category_id' => $this->product_category_id,
            'name' => $this->name,
            'description' => $this->description,
            'buying_price' => $this->buying_price,
            'selling_price' => $this->selling_price,
            'quantity' => $this->quantity,
            'image' => $imagePath,
            'barcode' => $this->barcode ?: null,
            'is_active' => $this->is_active,
            'created_by' => Auth::id(),
        ]);

        session()->flash('success', 'Product created successfully!');
        $this->resetFields();
        $this->dispatch('success', message: 'Product created successfully');
        $this->dispatch('hide-modal');
        $this->dispatch('datatable-reinit');
    }

    #[On('edit-product')]
    public function update()
    {
        $this->validate([
            'product_category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'buying_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'barcode' => 'nullable|string|max:255|unique:products,barcode,' . $this->deleteId,
            'imageToUpload' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ], [
            'product_category_id.required' => 'Please select a category.',
            'product_category_id.exists' => 'Selected category is invalid.',
            'name.required' => 'Product name is required.',
            'buying_price.required' => 'Buying price is required.',
            'buying_price.numeric' => 'Buying price must be a valid number.',
            'selling_price.required' => 'Selling price is required.',
            'selling_price.numeric' => 'Selling price must be a valid number.',
            'quantity.required' => 'Quantity is required.',
            'quantity.integer' => 'Quantity must be a whole number.',
            'barcode.unique' => 'This barcode is already in use.',
            'imageToUpload.image' => 'Please upload a valid image file.',
            'imageToUpload.mimes' => 'Image must be jpeg, png, jpg, or gif format.',
            'imageToUpload.max' => 'Image size must not exceed 2MB.'
        ]);

        $product = Product::findOrFail($this->deleteId);
        
        $imagePath = $product->image;
        if ($this->imageToUpload) {
            // Delete old image if exists
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $this->imageToUpload->store('products', 'public');
        }

        $product->update([
            'product_category_id' => $this->product_category_id,
            'name' => $this->name,
            'description' => $this->description,
            'buying_price' => $this->buying_price,
            'selling_price' => $this->selling_price,
            'quantity' => $this->quantity,
            'image' => $imagePath,
            'barcode' => $this->barcode ?: null,
            'is_active' => $this->is_active,
            'updated_by' => Auth::id(),
        ]);

        session()->flash('success', 'Product updated successfully!');
        $this->resetFields();
        $this->dispatch('success', message: 'Product updated successfully');
        $this->dispatch('hide-modal');
        $this->dispatch('datatable-reinit');
    }

    #[On('delete-record')]
    public function delete($id)
    {
        $product = Product::findOrFail($id);
        
        // Delete image if exists
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();
        
        session()->flash('success', 'Product deleted successfully!');
        $this->dispatch('success', message: 'Product deleted successfully');
        $this->dispatch('datatable-reinit');
    }

    // DataTable handler method
    public function getDataTableRows(Request $request)
    {
        try {
            $query = Product::with('category');

            // Search functionality
            if ($request->has('search') && !empty($request->search['value'])) {
                $searchValue = $request->search['value'];
                $query->where(function ($q) use ($searchValue) {
                    $q->where('name', 'like', '%' . $searchValue . '%')
                      ->orWhere('barcode', 'like', '%' . $searchValue . '%')
                      ->orWhereHas('category', function ($categoryQuery) use ($searchValue) {
                          $categoryQuery->where('name', 'like', '%' . $searchValue . '%');
                      });
                });
            }

            // Get total count before pagination
            $totalRecords = Product::count();
            $filteredRecords = $query->count();

            // Sorting
            if ($request->has('order')) {
                $orderColumnIndex = $request->order[0]['column'];
                $orderDirection = $request->order[0]['dir'];
                
                $columns = ['id', 'name', 'barcode', 'category', 'buying_price', 'selling_price', 'quantity', 'is_active', 'created_at'];
                if (isset($columns[$orderColumnIndex])) {
                    if ($columns[$orderColumnIndex] === 'category') {
                        $query->join('product_categories', 'products.product_category_id', '=', 'product_categories.id')
                              ->orderBy('product_categories.name', $orderDirection)
                              ->select('products.*');
                    } else {
                        $query->orderBy($columns[$orderColumnIndex], $orderDirection);
                    }
                }
            } else {
                $query->orderBy('id', 'desc');
            }

            // Pagination
            $start = $request->start ?? 0;
            $length = $request->length ?? 10;
            $products = $query->offset($start)->limit($length)->get();

            $data = [];
            foreach ($products as $index => $product) {
                $data[] = [
                    $start + $index + 1,  // Column 0: #
                    $product->name ?? 'N/A',  // Column 1: Product Name
                    $product->barcode ?? 'N/A',  // Column 2: Barcode
                    $product->category->name ?? 'N/A',  // Column 3: Category
                    '$' . number_format($product->buying_price ?? 0, 2),  // Column 4: Buy Price
                    '$' . number_format($product->selling_price ?? 0, 2),  // Column 5: Sell Price
                    $product->quantity ?? 0,  // Column 6: Stock
                    $product->is_active   // Column 7: Status
                        ? '<span class="badge bg-success">Active</span>' 
                        : '<span class="badge bg-danger">Inactive</span>',
                    $product->created_at ? $product->created_at->format('Y-m-d') : 'N/A',  // Column 8: Date Added
                    '<div class="action-items">
                            <span onclick="Livewire.dispatch(\'edit-mode\', { id: ' . $product->id . ' })" data-bs-toggle="modal" data-bs-target="#createModal" style="cursor: pointer;">
                                <i class="fa fa-edit"></i>
                            </span>
                            <span wire:click="delete(' . $product->id . ')" wire:confirm="Are you sure you want to delete this product?" style="cursor: pointer;">
                                <i class="fa fa-trash"></i>
                            </span>
                        </div>
                    '
                ];
            }

            return response()->json([
                'draw' => intval($request->draw ?? 1),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            Log::error('DataTable Error: ' . $e->getMessage());
            return response()->json([
                'draw' => intval($request->draw ?? 1),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage()
            ]);
        }
    }

    public function getCategories()
    {
        return ProductCategory::where('is_active', true)->get();
    }

    public function render()
    {
        return view('livewire.inventory.product.index', [
            'categories' => $this->categories ?? $this->getCategories(),
        ]);
    }
}
