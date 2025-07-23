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

class Edit extends Component
{
    use WithFileUploads;

    public $sale;
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
        'reference_no' => 'required|string|max:255',
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

    public function mount($id)
    {
        $this->sale = ProductSale::with('items')->findOrFail($id);
        $this->reference_no = $this->sale->reference_no;
        $this->sale_date = $this->sale->sale_date->format('Y-m-d');
        $this->user_id = $this->sale->user_id;
        $this->description = $this->sale->description;
        $this->payment_status = $this->sale->payment_status;
        $this->refund_status = $this->sale->refund_status;
        $this->discount = $this->sale->discount;
        $this->tax = $this->sale->tax;

        // Load existing items
        foreach ($this->sale->items as $item) {
            $this->productItems[] = [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'subtotal' => $item->quantity * $item->unit_price
            ];
        }
    }    
    
    public function addProductItem()
    {
        $this->productItems[] = [
            'id' => null,
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
                $this->productItems[$index]['unit_price'] = $product->selling_price ?? 0;
                
                // Recalculate subtotal only if quantity exists
                $quantity = floatval($this->productItems[$index]['quantity'] ?? 0);
                $this->productItems[$index]['subtotal'] = $quantity * ($product->selling_price ?? 0);
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
    }    public function updateSale()
    {
        $this->validate([
            'reference_no' => 'required|string|max:255|unique:product_sales,reference_no,' . $this->sale->id,
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
        ]);

        try {
            $this->sale->update([
                'reference_no' => $this->reference_no,
                'sale_date' => $this->sale_date,
                'user_id' => $this->user_id,
                'sale_slip' => $this->sale_slip ? $this->sale_slip->store('sales', 'public') : $this->sale->sale_slip,
                'description' => $this->description,
                'payment_status' => $this->payment_status,
                'refund_status' => $this->refund_status,
                'discount' => $this->discount,
                'tax' => $this->tax,
                'updated_by' => Auth::user()->name,
            ]);            
            // Delete existing items
            $this->sale->items()->delete();

            // Create new items
            foreach ($this->productItems as $item) {
                if ($item['product_id']) {
                    $this->sale->items()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'created_by' => Auth::user()->name,
                        'updated_by' => Auth::user()->name,
                    ]);
                }
            }

            // Handle payment status changes
            $this->handlePaymentStatusChange($this->sale->payment_status, $this->payment_status);

            $this->dispatch('success', message: 'Sale updated successfully.');
            $this->redirect('/sales', navigate: true);

        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error updating sale: ' . $e->getMessage());
        }
    }    
    
    private function handlePaymentStatusChange($oldStatus, $newStatus)
    {
        $grandTotal = $this->grandTotal;
        $currentPaid = $this->sale->payments->sum('paid_amount');

        if ($newStatus === 'paid' && $currentPaid < $grandTotal) {
            // Create payment for remaining amount when changing to 'paid'
            $remainingAmount = $grandTotal - $currentPaid;
            $this->sale->payments()->create([
                'reference_no' => 'PAY-' . $this->reference_no . '-EDIT',
                'paid_amount' => $remainingAmount,
                'payment_method' => 'cash',
                'payment_date' => now()->format('Y-m-d'),
                'description' => 'Payment on status change to paid',
                'created_by' => Auth::user()->name,
                'updated_by' => Auth::user()->name,
            ]);
        } elseif ($newStatus === 'due' && $currentPaid > 0) {
            // When changing to 'due', remove all payments
            $this->sale->payments()->delete();
        } elseif ($newStatus === 'partial') {
            // When changing to 'partial', ensure payments don't exceed total
            if ($currentPaid >= $grandTotal) {
                // Remove excess payments, keep some amount as partial
                $this->sale->payments()->delete();
                // Create a partial payment (50% of total)
                $partialAmount = $grandTotal * 0.5;
                $this->sale->payments()->create([
                    'reference_no' => 'PAY-' . $this->reference_no . '-PARTIAL',
                    'paid_amount' => $partialAmount,
                    'payment_method' => 'cash',
                    'payment_date' => now()->format('Y-m-d'),
                    'description' => 'Partial payment on status change',
                    'created_by' => Auth::user()->name,
                    'updated_by' => Auth::user()->name,
                ]);
            }
        }
    }

    #[Title('Edit Sale')]
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

        return view('livewire.inventory.sale.edit', compact('products', 'allUsers'));
    }
}
