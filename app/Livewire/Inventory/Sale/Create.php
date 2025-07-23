<?php

namespace App\Livewire\Inventory\Sale;

use App\Models\ProductSale;
use App\Models\Product;
use App\Models\User;
use App\Models\ProductSalePaid;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class Create extends Component
{
    use WithFileUploads;

    public $reference_no;
    public $sale_date;
    public $user_id;
    public $description = '';
    public $payment_status = 'due';
    public $refund_status = 'not_refunded';
    public $discount = 0.00;
    public $tax = 0.00;
    public $sale_slip;
    public $productItems = [];

    protected $rules = [
        'reference_no' => 'required|string|max:255|unique:product_sales,reference_no',
        'sale_date' => 'required|date',
        'user_id' => 'required|exists:users,id',
        'description' => 'nullable|string',
        'payment_status' => 'required|in:select_payment_status,due,partial,paid',
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
        'sale_date.required' => 'Sale date is required.',
        'user_id.required' => 'Customer is required.',
        'productItems.required' => 'At least one product item is required.',
        'productItems.*.product_id.required' => 'Product selection is required.',
        'productItems.*.quantity.required' => 'Quantity is required.',
        'productItems.*.quantity.min' => 'Quantity must be at least 0.01.',
        'productItems.*.unit_price.required' => 'Unit price is required.',
        'productItems.*.unit_price.min' => 'Unit price must be 0 or greater.',
    ];

    public function mount()
    {
        $this->sale_date = now()->format('Y-m-d');
        $this->generateReferenceNo();
        $this->addProductItem();
    }

    public function generateReferenceNo()
    {
        $prefix = 'SAL';
        $date = now()->format('Ymd');
        $count = ProductSale::whereDate('created_at', now())->count() + 1;
        $this->reference_no = $prefix . '-' . $date . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }    
      public function addProductItem()
    {
        $this->productItems[] = [
            'product_id' => '',
            'quantity' => '',
            'unit_price' => 0,
            'subtotal' => 0
        ];
    }

    public function removeProductItem($index)
    {
        if (count($this->productItems) > 1) {
            unset($this->productItems[$index]);
            $this->productItems = array_values($this->productItems);
        }
    }    
    

    public function updatedProductItems($value, $key)
    {
        $keys = explode('.', $key);
        $index = $keys[0];
        $field = $keys[1];

        if ($field === 'product_id' && $value) {
            $product = Product::find($value);
            if ($product) {
                // Auto-populate unit price
                $this->productItems[$index]['unit_price'] = $product->selling_price;
                
                // Recalculate subtotal if quantity exists
                $quantity = floatval($this->productItems[$index]['quantity'] ?? 0);
                $this->productItems[$index]['subtotal'] = $quantity * $product->selling_price;
            }
        }

        if (in_array($field, ['quantity', 'unit_price'])) {
            $quantity = floatval($this->productItems[$index]['quantity'] ?? 0);
            $unitPrice = floatval($this->productItems[$index]['unit_price'] ?? 0);
            $this->productItems[$index]['subtotal'] = $quantity * $unitPrice;
        }
    }

    public function getSubtotalProperty()
    {
        return collect($this->productItems)->sum('subtotal');
    }

    public function getDiscountAmountProperty()
    {
        return $this->subtotal * ($this->discount / 100);
    }

    public function getTaxAmountProperty()
    {
        return ($this->subtotal - $this->discountAmount) * ($this->tax / 100);
    }

    public function getGrandTotalProperty()
    {
        return $this->subtotal - $this->discountAmount + $this->taxAmount;
    }

    public function createSale()
    {
        $this->validate();

        try {
            $sale = ProductSale::create([
                'reference_no' => $this->reference_no,
                'sale_date' => $this->sale_date,
                'user_id' => $this->user_id,
                'sale_slip' => $this->sale_slip?->store('sales', 'public'),
                'description' => $this->description,
                'payment_status' => $this->payment_status,
                'refund_status' => $this->refund_status,
                'discount' => $this->discount,
                'tax' => $this->tax,
                'created_by' => Auth::user()->name,
                'updated_by' => Auth::user()->name,
            ]);            
            foreach ($this->productItems as $item) {
                if ($item['product_id']) {
                    $sale->items()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'created_by' => Auth::user()->name,
                        'updated_by' => Auth::user()->name,
                    ]);
                }
            }            
            // If payment status is 'paid', create a payment record for the full amount
            if ($this->payment_status === 'paid') {
                $grandTotal = $this->grandTotal;
                $sale->payments()->create([
                    'reference_no' => 'PAY-' . $this->reference_no,
                    'paid_amount' => $grandTotal,
                    'payment_method' => 'cash',
                    'payment_date' => $this->sale_date,
                    'description' => 'Full payment on sale creation',
                    'created_by' => Auth::user()->name,
                    'updated_by' => Auth::user()->name,
                ]);
            }

            $this->dispatch('success', message: 'Sale created successfully.');
            $this->redirect('/sales', navigate: true);

        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error creating sale: ' . $e->getMessage());
        }
    }    
        
    #[Title('Create Sale')]
    #[Layout('components.layouts.app')]
    public function render()
    {
        $products = Product::all();

        // Get all active users with their user_type
        $allUsers = User::where('is_active', 1)
            ->get()
            ->map(function($user) {
                $userType = $user->user_type ? ucfirst($user->user_type) : 'User';
                
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'type' => $userType
                ];
            });

        return view('livewire.inventory.sale.create', compact('products', 'allUsers'));
    }
}
