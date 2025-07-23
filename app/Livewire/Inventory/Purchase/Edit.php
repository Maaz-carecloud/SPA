<?php

namespace App\Livewire\Inventory\Purchase;

use App\Models\ProductPurchase;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductSupplier;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class Edit extends Component
{
    use WithFileUploads;

    public $purchase;
    public $reference_no;
    public $purchase_date;
    public $product_supplier_id;
    public $product_warehouse_id;
    public $description = '';
    public $payment_status = 'pending';
    public $refund_status = 'not_refunded';
    public $discount = 0.00;
    public $tax = 0.00;
    public $purchase_slip;

    public $productItems = [];
    public $selectedCategory = '';
    public $filteredProducts = [];

    protected $rules = [
        'reference_no' => 'required|string|max:255',
        'purchase_date' => 'required|date',
        'product_supplier_id' => 'required|exists:product_suppliers,id',
        'product_warehouse_id' => 'required|exists:product_warehouses,id',
        'description' => 'nullable|string',
        'payment_status' => 'required|in:pending,partial_paid,fully_paid',
        'refund_status' => 'required|in:refunded,not_refunded',
        'discount' => 'nullable|numeric|min:0',
        'tax' => 'nullable|numeric|min:0',
        'productItems' => 'required|array|min:1',
        'productItems.*.product_id' => 'required|exists:products,id',
        'productItems.*.quantity' => 'required|numeric|min:0.01',
        'productItems.*.unit_price' => 'required|numeric|min:0',
    ];

    public function mount($id)
    {
        $this->purchase = ProductPurchase::with('purchasedItems')->findOrFail($id);
        $this->reference_no = $this->purchase->reference_no;
        $this->purchase_date = $this->purchase->purchase_date;
        $this->product_supplier_id = $this->purchase->product_supplier_id;
        $this->product_warehouse_id = $this->purchase->product_warehouse_id;
        $this->description = $this->purchase->description ?? '';
        $this->payment_status = $this->purchase->payment_status;
        $this->refund_status = $this->purchase->refund_status;
        $this->discount = $this->purchase->discount;
        $this->tax = $this->purchase->tax;        // Load existing purchase items
        $this->productItems = $this->purchase->purchasedItems->map(function($item) {
            $product = Product::find($item->product_id);
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'category_id' => $product ? $product->product_category_id : '',
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'subtotal' => $item->quantity * $item->unit_price
            ];
        })->toArray();

        if (empty($this->productItems)) {
            $this->addProductItem();
        }
    }   
    
    public function addProductItem()
    {
        $this->productItems[] = [
            'id' => null,
            'product_id' => '',
            'category_id' => '',
            'quantity' => 1,
            'unit_price' => 0,
            'subtotal' => 0
        ];
        
        $this->dispatch('productItemAdded');
    }    
    
    public function removeProductItem($index)
    {
        if (count($this->productItems) > 1) {
            unset($this->productItems[$index]);
            $this->productItems = array_values($this->productItems);
        }
    }

    public function updatedProductItems($value, $name)
    {
        $segments = explode('.', $name);
        if (count($segments) >= 2) {
            $index = $segments[0];
            $field = $segments[1];
            
            if (isset($this->productItems[$index])) {
                if ($field === 'category_id') {
                    // Reset product selection when category changes
                    $this->productItems[$index]['product_id'] = '';
                    $this->productItems[$index]['unit_price'] = 0;
                    $this->productItems[$index]['subtotal'] = 0;
                } elseif ($field === 'product_id' && !empty($value)) {
                    // Auto-populate unit price when product is selected
                    $product = Product::find($value);
                    if ($product) {
                        $this->productItems[$index]['unit_price'] = $product->buying_price ?? 0;
                    }
                    // Recalculate subtotal
                    $quantity = (float) ($this->productItems[$index]['quantity'] ?? 0);
                    $unitPrice = (float) ($this->productItems[$index]['unit_price'] ?? 0);
                    $this->productItems[$index]['subtotal'] = $quantity * $unitPrice;
                } elseif ($field === 'quantity' || $field === 'unit_price') {
                    $quantity = (float) ($this->productItems[$index]['quantity'] ?? 0);
                    $unitPrice = (float) ($this->productItems[$index]['unit_price'] ?? 0);
                    $this->productItems[$index]['subtotal'] = $quantity * $unitPrice;
                }
            }
        }
    }

    public function updatedDiscount()
    {
        // Trigger recalculation when discount changes
    }

    public function updatedTax()
    {
        // Trigger recalculation when tax changes
    }

    public function getGrandTotalProperty()
    {
        $subtotal = collect($this->productItems)->sum('subtotal');
        $discountAmount = $subtotal * ($this->discount / 100);
        $taxAmount = ($subtotal - $discountAmount) * ($this->tax / 100);
        return $subtotal - $discountAmount + $taxAmount;
    }

    public function updatePurchase()
    {
        $this->rules['reference_no'] = 'required|string|max:255|unique:product_purchases,reference_no,' . $this->purchase->id;
        $this->validate();

        try {
            $this->purchase->update([
                'reference_no' => $this->reference_no,
                'purchase_date' => $this->purchase_date,
                'product_supplier_id' => $this->product_supplier_id,
                'product_warehouse_id' => $this->product_warehouse_id,
                'description' => $this->description,
                'payment_status' => $this->payment_status,
                'refund_status' => $this->refund_status,
                'discount' => $this->discount,
                'tax' => $this->tax,
                'updated_by' => Auth::user()->name,
            ]);

            // Delete existing purchase items
            $this->purchase->purchasedItems()->delete();

            // Create new purchase items
            foreach ($this->productItems as $item) {
                $this->purchase->purchasedItems()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'created_by' => Auth::user()->name,
                    'updated_by' => Auth::user()->name,
                ]);
            }

            $this->dispatch('success', message: 'Purchase updated successfully.');
            $this->redirect('/purchases', navigate: true);

        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error updating purchase: ' . $e->getMessage());
        }
    }   
    
    #[Title('Edit Purchase')]
    #[Layout('components.layouts.app')]
    public function render()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $categories = ProductCategory::where('is_active', true)->orderBy('name')->get();
        $suppliers = ProductSupplier::where('is_active', true)->orderBy('company_name')->get();
        $warehouses = ProductWarehouse::where('is_active', true)->orderBy('name')->get();

        return view('livewire.inventory.purchase.edit', compact('products', 'categories', 'suppliers', 'warehouses'));
    }
}
