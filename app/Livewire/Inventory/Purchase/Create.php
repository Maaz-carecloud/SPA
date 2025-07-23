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

class Create extends Component
{
    use WithFileUploads;

    public $reference_no;
    public $purchase_date;
    public $product_id;
    public $product_supplier_id;
    public $product_warehouse_id;
    public $description = '';
    public $payment_status = 'pending';
    public $refund_status = 'not_refunded';
    public $discount = 0.00;
    public $tax = 0.00;
    public $purchase_slip;    
    public $selectedProducts = [];
    public $productItems = [];

    protected $rules = [
        'reference_no' => 'required|string|max:255|unique:product_purchases,reference_no',
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

    protected $messages = [
        'reference_no.required' => 'Reference number is required.',
        'reference_no.unique' => 'Reference number already exists.',
        'purchase_date.required' => 'Purchase date is required.',
        'product_supplier_id.required' => 'Supplier is required.',
        'product_warehouse_id.required' => 'Warehouse is required.',
        'productItems.required' => 'At least one product is required.',
        'productItems.min' => 'At least one product is required.',
    ];    
    
    public function mount()
    {
        $this->reference_no = 'PUR-' . now()->format('dmY') . '-' . str_pad(ProductPurchase::count() + 1, 3, '0', STR_PAD_LEFT);
        $this->purchase_date = now()->format('Y-m-d');
        $this->addProductItem();
    }
    
    public function addProductItem()
    {
        $this->productItems[] = [
            'product_id' => '',
            'category_id' => '',
            'quantity' => 1,
            'unit_price' => 0,
            'subtotal' => 0
        ];
        
        $this->dispatch('productItemAdded');
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
        // Trigger re-render to update calculations
        $this->dispatch('calculation-updated');
    }

    public function updatedTax()
    {
        // Trigger re-render to update calculations
        $this->dispatch('calculation-updated');
    }

    public function removeProductItem($index)
    {
        if (count($this->productItems) > 1) {
            unset($this->productItems[$index]);
            $this->productItems = array_values($this->productItems);        }
    }

    public function getGrandTotalProperty()
    {
        $subtotal = collect($this->productItems)->sum('subtotal');
        $discountAmount = $subtotal * ($this->discount / 100);
        $taxAmount = ($subtotal - $discountAmount) * ($this->tax / 100);
        return $subtotal - $discountAmount + $taxAmount;
    }

    public function createPurchase()
    {
        $this->validate();

        try {
            $purchase = ProductPurchase::create([
                'reference_no' => $this->reference_no,
                'purchase_date' => $this->purchase_date,
                'product_supplier_id' => $this->product_supplier_id,
                'product_warehouse_id' => $this->product_warehouse_id,
                'description' => $this->description,
                'payment_status' => $this->payment_status,
                'refund_status' => $this->refund_status,
                'discount' => $this->discount,
                'tax' => $this->tax,
                'created_by' => Auth::user()->name,
                'updated_by' => Auth::user()->name,
            ]);

            // Create purchase items
            foreach ($this->productItems as $item) {
                $purchase->purchasedItems()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'created_by' => Auth::user()->name,
                    'updated_by' => Auth::user()->name,
                ]);

                // Update product quantity
                $product = Product::find($item['product_id']);
                if ($product) {
                    $product->increment('quantity', $item['quantity']);
                }
            }

            $this->dispatch('success', message: 'Purchase created successfully.');
            $this->redirect('/purchases', navigate: true);

        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error creating purchase: ' . $e->getMessage());
        }
    }    
    
    #[Title('Add Purchase')]
    #[Layout('components.layouts.app')]
    public function render()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $categories = ProductCategory::where('is_active', true)->orderBy('name')->get();
        $suppliers = ProductSupplier::where('is_active', true)->orderBy('company_name')->get();
        $warehouses = ProductWarehouse::where('is_active', true)->orderBy('name')->get();

        return view('livewire.inventory.purchase.create', compact('products', 'categories', 'suppliers', 'warehouses'));
    }
}
