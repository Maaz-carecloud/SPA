<?php

namespace App\Livewire\Inventory\Sale;

use App\Models\ProductSale;
use App\Models\ProductSalePaid;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductSaleItem;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Index extends Component
{
    public $sales;

    public function mount(){
        $this->reference_no = ProductSale::generateReferenceNo();
        $this->sale_date = now()->format('Y-m-d');
        $this->productItems = [['product_id' => '', 'quantity' => 1, 'unit_price' => 0, 'subtotal' => 0]];
    }

    //Modal related methods
    public $modalTitle = 'Create Sale';
    public $modalAction = 'create-sale';
    public $is_edit = false;
    public $deleteId;
    public $is_delete = false;

    // Payment Modal Properties
    public $showPaymentModal = false;
    public $selectedSale = null;
    public $payments = [];
    
    // Add Payment Form Properties
    public $showAddPaymentForm = false;
    public $paymentReferenceNo = '';
    public $paymentAmount = '';
    public $paymentMethod = 'cash';
    public $paymentDate = '';
    public $paymentDescription = '';
    public $paymentSlip = '';

    //form related methods
    public $reference_no;
    public $sale_date;
    public $user_id;
    public $description;
    public $payment_status = 'pending';
    public $refund_status = 'not_refunded';
    public $discount = 0.00;
    public $tax = 0.00;
    public $productItems = [];

    public $getSale;

    // Validation rules
    protected function rules()
    {
        return [
            'reference_no' => 'required',
            'sale_date' => 'required|date',
            'user_id' => 'required|exists:users,id',
            'description' => 'nullable',
            'payment_status' => 'required|in:pending,partial_paid,fully_paid',
            'refund_status' => 'required|in:refunded,not_refunded',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'productItems' => 'required|array|min:1',
            'productItems.*.product_id' => 'required|exists:products,id',
            'productItems.*.quantity' => 'required|numeric|min:1',
            'productItems.*.unit_price' => 'required|numeric|min:0',
        ];
    }

    #[On('create-sale')]
    public function save(){
        $this->validate();
        
        try {
            DB::beginTransaction();

            $sale = ProductSale::create([
                'reference_no' => $this->reference_no,
                'sale_date' => $this->sale_date,
                'user_id' => $this->user_id,
                'description' => $this->description,
                'payment_status' => $this->payment_status,
                'refund_status' => $this->refund_status,
                'discount' => $this->discount,
                'tax' => $this->tax,
                'created_by' => Auth::user()->name,
                'updated_by' => Auth::user()->name,
            ]);

            // Create sale items and update stock
            foreach ($this->productItems as $item) {
                if (!empty($item['product_id']) && !empty($item['quantity'])) {
                    ProductSaleItem::create([
                        'product_sale_id' => $sale->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'created_by' => Auth::user()->name,
                        'updated_by' => Auth::user()->name,
                    ]);

                    // Update product stock (decrease)
                    $product = Product::find($item['product_id']);
                    if ($product) {
                        $product->decrement('quantity', $item['quantity']);
                    }
                }
            }

            DB::commit();

            $this->dispatch('success', message: 'Sale created successfully');
            $this->dispatch('hide-modal');
            $this->resetFields();
            $this->dispatch('datatable-reinit');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('error', message: 'Error creating sale: ' . $e->getMessage());
        }
    }

    #[On('edit-mode')]
    public function loadEditModal($id){
        $this->modalTitle = 'Edit Sale';
        $this->modalAction = 'edit-sale';
        $this->is_edit = true;

        $this->getSale = ProductSale::with('items')->findOrfail($id);
        $this->reference_no = $this->getSale->reference_no;
        $this->sale_date = is_object($this->getSale->sale_date) 
            ? $this->getSale->sale_date->format('Y-m-d') 
            : $this->getSale->sale_date;
        $this->user_id = $this->getSale->user_id;
        $this->description = $this->getSale->description;
        $this->payment_status = $this->getSale->payment_status;
        $this->refund_status = $this->getSale->refund_status;
        $this->discount = $this->getSale->discount;
        $this->tax = $this->getSale->tax;
        
        // Load sale items
        $this->productItems = [];
        foreach ($this->getSale->items as $item) {
            $this->productItems[] = [
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'subtotal' => $item->quantity * $item->unit_price
            ];
        }
    }

    #[On('edit-sale')]
    public function update(){
        $this->validate();

        try {
            DB::beginTransaction();

            $sale = ProductSale::findOrFail($this->getSale->id);
            
            // Reverse previous stock changes
            foreach ($sale->items as $oldItem) {
                $product = Product::find($oldItem->product_id);
                if ($product) {
                    $product->increment('quantity', $oldItem->quantity);
                }
            }

            // Delete old sale items
            $sale->items()->delete();

            // Update sale
            $sale->update([
                'reference_no' => $this->reference_no,
                'sale_date' => $this->sale_date,
                'user_id' => $this->user_id,
                'description' => $this->description,
                'payment_status' => $this->payment_status,
                'refund_status' => $this->refund_status,
                'discount' => $this->discount,
                'tax' => $this->tax,
                'updated_by' => Auth::user()->name,
            ]);

            // Create new sale items and update stock
            foreach ($this->productItems as $item) {
                if (!empty($item['product_id']) && !empty($item['quantity'])) {
                    ProductSaleItem::create([
                        'product_sale_id' => $sale->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'created_by' => Auth::user()->name,
                        'updated_by' => Auth::user()->name,
                    ]);

                    // Update product stock (decrease)
                    $product = Product::find($item['product_id']);
                    if ($product) {
                        $product->decrement('quantity', $item['quantity']);
                    }
                }
            }

            DB::commit();

            $this->dispatch('success', message: 'Sale updated successfully');
            $this->dispatch('hide-modal');
            $this->resetFields();
            $this->dispatch('datatable-reinit');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('error', message: 'Error updating sale: ' . $e->getMessage());
        }
    }

    #[On('delete-record')]
    public function delete($id) {
        try {
            DB::beginTransaction();

            $sale = ProductSale::with('items')->findOrFail($id);
            
            // Reverse stock changes
            foreach ($sale->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('quantity', $item->quantity);
                }
            }

            $sale->delete();
            DB::commit();

            $this->dispatch('success', message: 'Sale deleted successfully');
            $this->dispatch('datatable-reinit');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('error', message: 'Error deleting sale: ' . $e->getMessage());
        }
    }

    public function addProductItem()
    {
        $this->productItems[] = [
            'product_id' => '',
            'quantity' => 1,
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

    public function updatedProductItems($value, $name)
    {
        $segments = explode('.', $name);
        if (count($segments) >= 2) {
            $index = $segments[0];
            $field = $segments[1];
            
            if (isset($this->productItems[$index])) {
                if ($field === 'product_id' && !empty($value)) {
                    $product = Product::find($value);
                    if ($product) {
                        $this->productItems[$index]['unit_price'] = $product->selling_price ?? 0;
                    }
                    $this->calculateSubtotal($index);
                } elseif ($field === 'quantity' || $field === 'unit_price') {
                    $this->calculateSubtotal($index);
                }
            }
        }
    }

    public function calculateSubtotal($index)
    {
        if (isset($this->productItems[$index])) {
            $quantity = floatval($this->productItems[$index]['quantity'] ?? 0);
            $unitPrice = floatval($this->productItems[$index]['unit_price'] ?? 0);
            $this->productItems[$index]['subtotal'] = $quantity * $unitPrice;
        }
    }

    // Payment Modal Methods
    public function viewPayments($saleId)
    {
        $this->selectedSale = ProductSale::with(['user', 'items.product'])->findOrFail($saleId);
        $this->payments = $this->selectedSale->payments;
        $this->showPaymentModal = true;
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
        $this->selectedSale = null;
        $this->payments = [];
        $this->hideAddPaymentForm();
    }

    public function openPaymentForm()
    {
        $this->showAddPaymentForm = true;
        $this->generatePaymentReferenceNo();
        $this->paymentDate = now()->format('Y-m-d');
    }

    public function hideAddPaymentForm()
    {
        $this->showAddPaymentForm = false;
        $this->resetPaymentForm();
    }

    public function resetPaymentForm()
    {
        $this->paymentReferenceNo = '';
        $this->paymentAmount = '';
        $this->paymentMethod = 'cash';
        $this->paymentDate = now()->format('Y-m-d');
        $this->paymentDescription = '';
        $this->paymentSlip = '';
    }

    public function generatePaymentReferenceNo()
    {
        $prefix = 'PAY-SALE';
        $date = date('Ymd');
        $lastPayment = ProductSalePaid::whereDate('created_at', date('Y-m-d'))->latest()->first();
        
        if ($lastPayment) {
            $lastNumber = (int) substr($lastPayment->reference_no, -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        $this->paymentReferenceNo = $prefix . '-' . $date . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    public function addPayment()
    {
        $this->validate([
            'paymentReferenceNo' => 'required|string|max:255|unique:product_sale_paids,reference_no',
            'paymentAmount' => 'required|numeric|min:0.01',
            'paymentMethod' => 'required|in:cash,cheque,credit_card,other',
            'paymentDate' => 'required|date',
            'paymentDescription' => 'nullable|string|max:1000',
        ]);

        try {
            ProductSalePaid::create([
                'product_sale_id' => $this->selectedSale->id,
                'reference_no' => $this->paymentReferenceNo,
                'paid_amount' => $this->paymentAmount,
                'payment_method' => $this->paymentMethod,
                'payment_date' => $this->paymentDate,
                'description' => $this->paymentDescription,
                'paid_slip' => $this->paymentSlip,
                'created_by' => Auth::user()->name,
                'updated_by' => Auth::user()->name,
            ]);

            // Refresh payments list
            $this->payments = $this->selectedSale->payments()->get();
            
            $this->dispatch('success', message: 'Payment added successfully');
            $this->hideAddPaymentForm();
            $this->dispatch('datatable-reinit');

        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error adding payment: ' . $e->getMessage());
        }
    }

    public function deletePayment($paymentId)
    {
        try {
            $payment = ProductSalePaid::findOrFail($paymentId);
            $payment->delete();

            // Refresh payments list
            $this->payments = $this->selectedSale->payments()->get();
            
            $this->dispatch('success', message: 'Payment deleted successfully');
            $this->dispatch('datatable-reinit');

        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error deleting payment: ' . $e->getMessage());
        }
    }

    #[On('create-sale-close')]
    #[On('edit-sale-close')]
    public function resetFields(){
        $this->reset([
            'reference_no', 'sale_date', 'user_id',
            'description', 'payment_status', 'refund_status', 'discount', 'tax', 'productItems', 'getSale'
        ]);
        $this->modalTitle = 'Create Sale';
        $this->modalAction = 'create-sale';
        $this->is_edit = false;
        $this->payment_status = 'pending';
        $this->refund_status = 'not_refunded';
        $this->discount = 0.00;
        $this->tax = 0.00;
        $this->reference_no = ProductSale::generateReferenceNo();
        $this->sale_date = now()->format('Y-m-d');
        $this->productItems = [['product_id' => '', 'quantity' => 1, 'unit_price' => 0, 'subtotal' => 0]];
        $this->dispatch('hide-modal');
        $this->dispatch('datatable-reinit');
    }

    public function getDataTableRows(Request $request)
    {
        $length = $request->input('length');
        $start = $request->input('start');

        $query = ProductSale::with(['user', 'items', 'payments']);

        // Search
        if (!empty($request['search']['value'])) {
            $searchValue = $request['search']['value'];
            $query->where(function($q) use ($searchValue) {
                $q->where('reference_no', 'like', '%' . $searchValue . '%')
                  ->orWhereHas('user', function($uq) use ($searchValue) {
                      $uq->where('name', 'like', '%' . $searchValue . '%');
                  })
                  ->orWhere('description', 'like', '%' . $searchValue . '%');
            });
        }

        $totalRecords = $query->count();
        $sales = $query->skip($start)->take($length)->orderBy('id', 'desc')->get();

        $data = [];
        $counter = $start + 1;

        foreach ($sales as $sale) {
            $subtotal = $sale->items->sum(function($item) {
                return $item->quantity * $item->unit_price;
            });
            $discountAmount = $subtotal * ($sale->discount / 100);
            $taxAmount = ($subtotal - $discountAmount) * ($sale->tax / 100);
            $grandTotal = $subtotal - $discountAmount + $taxAmount;
            $totalPaid = $sale->payments->sum('paid_amount');
            $balance = $grandTotal - $totalPaid;

            $paymentStatusBadge = match($sale->payment_status) {
                'pending' => '<span class="badge bg-warning">Pending</span>',
                'partial_paid' => '<span class="badge bg-info">Partial Paid</span>',
                'fully_paid' => '<span class="badge bg-success">Fully Paid</span>',
                default => '<span class="badge bg-secondary">Unknown</span>'
            };

            $data[] = [
                $counter++,
                $sale->reference_no,
                $sale->user->name ?? 'N/A',
                $sale->sale_date,
                'PKR ' . number_format($grandTotal, 2),
                'PKR ' . number_format($totalPaid, 2),
                'PKR ' . number_format($balance, 2),
                $paymentStatusBadge,
                '
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        Actions
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" wire:click="$dispatch(\'edit-mode\', {id: ' . $sale->id . '})" data-bs-toggle="modal" data-bs-target="#createModal">Edit</a></li>
                        <li><a class="dropdown-item" href="#" wire:click="viewPayments(' . $sale->id . ')">Payments</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#" onclick="confirm(\'Are you sure?\') || event.stopImmediatePropagation()" wire:click="$dispatch(\'delete-record\', {id: ' . $sale->id . '})">Delete</a></li>
                    </ul>
                </div>
                '
            ];
        }

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data
        ]);
    }

    #[Title('Sales Management')]
    #[Layout('layouts.app')]
    public function render()
    {
        $users = User::all();
        $products = Product::all();
        
        return view('livewire.inventory.sale.index', [
            'users' => $users,
            'products' => $products
        ]);
    }
}
