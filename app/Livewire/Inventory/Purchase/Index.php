<?php

namespace App\Livewire\Inventory\Purchase;

use App\Models\ProductPurchase;
use App\Models\ProductPurchasePaid;
use App\Models\ProductSupplier;
use App\Models\ProductWarehouse;
use App\Models\Product;
use App\Models\ProductPurchasedItem;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Index extends Component
{
    public $purchases;

    public function mount(){
        $this->reference_no = ProductPurchase::generateReferenceNo();
        $this->purchase_date = now()->format('Y-m-d');
        $this->productItems = [['product_id' => '', 'quantity' => 1, 'unit_price' => 0, 'subtotal' => 0]];
    }

    //Modal related methods
    public $modalTitle = 'Create Purchase';
    public $modalAction = 'create-purchase';
    public $is_edit = false;
    public $deleteId;
    public $is_delete = false;

    // Payment Modal Properties
    public $showPaymentModal = false;
    public $selectedPurchase = null;
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
    public $purchase_date;
    public $product_supplier_id;
    public $product_warehouse_id;
    public $description;
    public $payment_status = 'pending';
    public $refund_status = 'not_refunded';
    public $discount = 0.00;
    public $tax = 0.00;
    public $productItems = [];

    public $getPurchase;

    // Validation rules for productItems
    protected function rules()
    {
        return [
            'reference_no' => 'required',
            'purchase_date' => 'required|date',
            'product_supplier_id' => 'required|exists:product_suppliers,id',
            'product_warehouse_id' => 'required|exists:product_warehouses,id',
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

    #[On('create-purchase')]
    public function save(){
        $this->validate();
        
        try {
            DB::beginTransaction();

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

            // Create purchase items and update stock
            foreach ($this->productItems as $item) {
                if (!empty($item['product_id']) && !empty($item['quantity'])) {
                    ProductPurchasedItem::create([
                        'product_purchase_id' => $purchase->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'created_by' => Auth::user()->name,
                        'updated_by' => Auth::user()->name,
                    ]);

                    // Update product stock
                    $product = Product::find($item['product_id']);
                    if ($product) {
                        $product->increment('quantity', $item['quantity']);
                    }
                }
            }

            DB::commit();

            $this->dispatch('success', message: 'Purchase created successfully');
            $this->dispatch('hide-modal');
            $this->resetFields();
            $this->dispatch('datatable-reinit');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('error', message: 'Error creating purchase: ' . $e->getMessage());
        }
    }

    #[On('edit-mode')]
    public function loadEditModal($id){
        $this->modalTitle = 'Edit Purchase';
        $this->modalAction = 'edit-purchase';
        $this->is_edit = true;

        $this->getPurchase = ProductPurchase::with('purchasedItems')->findOrfail($id);
        $this->reference_no = $this->getPurchase->reference_no;
        $this->purchase_date = is_object($this->getPurchase->purchase_date) 
            ? $this->getPurchase->purchase_date->format('Y-m-d') 
            : $this->getPurchase->purchase_date;
        $this->product_supplier_id = $this->getPurchase->product_supplier_id;
        $this->product_warehouse_id = $this->getPurchase->product_warehouse_id;
        $this->description = $this->getPurchase->description;
        $this->payment_status = $this->getPurchase->payment_status;
        $this->refund_status = $this->getPurchase->refund_status;
        $this->discount = $this->getPurchase->discount;
        $this->tax = $this->getPurchase->tax;
        
        // Load purchase items
        $this->productItems = [];
        foreach ($this->getPurchase->purchasedItems as $item) {
            $this->productItems[] = [
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'subtotal' => $item->quantity * $item->unit_price
            ];
        }
    }

    #[On('edit-purchase')]
    public function update(){
        $this->validate();

        try {
            DB::beginTransaction();

            $purchase = ProductPurchase::findOrFail($this->getPurchase->id);
            
            // Reverse previous stock changes
            foreach ($purchase->purchasedItems as $oldItem) {
                $product = Product::find($oldItem->product_id);
                if ($product) {
                    $product->decrement('quantity', $oldItem->quantity);
                }
            }

            // Delete old purchase items
            $purchase->purchasedItems()->delete();

            // Update purchase
            $purchase->update([
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

            // Create new purchase items and update stock
            foreach ($this->productItems as $item) {
                if (!empty($item['product_id']) && !empty($item['quantity'])) {
                    ProductPurchasedItem::create([
                        'product_purchase_id' => $purchase->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'created_by' => Auth::user()->name,
                        'updated_by' => Auth::user()->name,
                    ]);

                    // Update product stock
                    $product = Product::find($item['product_id']);
                    if ($product) {
                        $product->increment('quantity', $item['quantity']);
                    }
                }
            }

            DB::commit();

            $this->dispatch('success', message: 'Purchase updated successfully');
            $this->dispatch('hide-modal');
            $this->resetFields();
            $this->dispatch('datatable-reinit');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('error', message: 'Error updating purchase: ' . $e->getMessage());
        }
    }

    #[On('delete-record')]
    public function delete($id) {
        try {
            DB::beginTransaction();

            $purchase = ProductPurchase::with('purchasedItems')->findOrFail($id);
            
            // Reverse stock changes
            foreach ($purchase->purchasedItems as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->decrement('quantity', $item->quantity);
                }
            }

            $purchase->delete();
            DB::commit();

            $this->dispatch('success', message: 'Purchase deleted successfully');
            $this->dispatch('datatable-reinit');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('error', message: 'Error deleting purchase: ' . $e->getMessage());
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
                        $this->productItems[$index]['unit_price'] = $product->buying_price ?? 0;
                    }
                    $this->calculateSubtotal($index);
                } elseif ($field === 'quantity' || $field === 'unit_price') {
                    $this->calculateSubtotal($index);
                }
            }
        }
    }

    private function calculateSubtotal($index)
    {
        $quantity = (float) ($this->productItems[$index]['quantity'] ?? 0);
        $unitPrice = (float) ($this->productItems[$index]['unit_price'] ?? 0);
        $this->productItems[$index]['subtotal'] = $quantity * $unitPrice;
    }

    #[On('viewPayments')]
    public function viewPayments($purchaseId)
    {
        $this->selectedPurchase = ProductPurchase::with(['payments', 'supplier', 'warehouse', 'purchasedItems'])
            ->findOrFail($purchaseId);
        $this->payments = $this->selectedPurchase->payments;
        $this->showPaymentModal = true;
        $this->resetPaymentForm();
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
        $this->showAddPaymentForm = false;
        $this->selectedPurchase = null;
        $this->payments = [];
        $this->resetPaymentForm();
        $this->dispatch('datatable-reinit');
    }

    public function openPaymentForm()
    {
        $this->showAddPaymentForm = true;
        $this->paymentDate = now()->format('Y-m-d');
        $this->paymentReferenceNo = 'PAY-' . date('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
    }

    public function hideAddPaymentForm()
    {
        $this->showAddPaymentForm = false;
        $this->resetPaymentForm();
    }

    private function resetPaymentForm()
    {
        $this->paymentReferenceNo = '';
        $this->paymentAmount = '';
        $this->paymentMethod = 'cash';
        $this->paymentDate = '';
        $this->paymentDescription = '';
        $this->paymentSlip = '';
        $this->resetErrorBag(['paymentReferenceNo', 'paymentAmount', 'paymentMethod', 'paymentDate']);
    }

    public function addPayment()
    {
        $this->validate([
            'paymentReferenceNo' => 'required|string|max:255|unique:product_purchase_paids,reference_no',
            'paymentAmount' => 'required|numeric|min:0.01',
            'paymentMethod' => 'required|in:cash,cheque,credit_card,other',
            'paymentDate' => 'required|date',
            'paymentDescription' => 'nullable|string|max:1000',
        ]);

        try {
            ProductPurchasePaid::create([
                'product_purchase_id' => $this->selectedPurchase->id,
                'reference_no' => $this->paymentReferenceNo,
                'paid_amount' => $this->paymentAmount,
                'payment_method' => $this->paymentMethod,
                'payment_date' => $this->paymentDate,
                'description' => $this->paymentDescription,
                'paid_slip' => $this->paymentSlip,
                'created_by' => Auth::user()->name,
                'updated_by' => Auth::user()->name,
            ]);

            // Refresh payments
            $this->selectedPurchase->refresh();
            $this->selectedPurchase->load(['payments', 'supplier', 'warehouse', 'purchasedItems']);
            $this->payments = $this->selectedPurchase->payments;

            // Update payment status
            $this->updatePurchasePaymentStatus();

            $this->hideAddPaymentForm();
            $this->dispatch('success', message: 'Payment added successfully.');
            $this->dispatch('datatable-reinit');

        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error adding payment: ' . $e->getMessage());
        }
    }

    public function deletePayment($paymentId)
    {
        try {
            $payment = ProductPurchasePaid::findOrFail($paymentId);
            
            if ($payment->product_purchase_id !== $this->selectedPurchase->id) {
                $this->dispatch('error', message: 'Unauthorized action.');
                return;
            }
            
            $paymentReference = $payment->reference_no;
            $paymentAmount = $payment->paid_amount;

            $payment->delete();

            // Refresh payments
            $this->selectedPurchase->refresh();
            $this->selectedPurchase->load(['payments', 'supplier', 'warehouse', 'purchasedItems']);
            $this->payments = $this->selectedPurchase->payments;

            // Update payment status
            $this->updatePurchasePaymentStatus();

            $this->dispatch('success', message: "Payment {$paymentReference} (PKR " . number_format($paymentAmount, 2) . ") deleted successfully.");
            $this->dispatch('datatable-reinit');

        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error deleting payment: ' . $e->getMessage());
        }
    }

    private function updatePurchasePaymentStatus()
    {
        if (!$this->selectedPurchase) {
            return;
        }

        $subtotal = $this->selectedPurchase->purchasedItems->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });
        $discountAmount = $subtotal * ($this->selectedPurchase->discount / 100);
        $taxAmount = ($subtotal - $discountAmount) * ($this->selectedPurchase->tax / 100);
        $grandTotal = $subtotal - $discountAmount + $taxAmount;
        $totalPaid = collect($this->payments)->sum('paid_amount');

        if ($totalPaid >= $grandTotal) {
            $paymentStatus = 'fully_paid';
        } elseif ($totalPaid > 0) {
            $paymentStatus = 'partial_paid';
        } else {
            $paymentStatus = 'pending';
        }
        
        $this->selectedPurchase->update([
            'payment_status' => $paymentStatus,
            'updated_by' => Auth::user()->name,
        ]);
    }

    #[On('create-purchase-close')]
    #[On('edit-purchase-close')]
    public function resetFields(){
        $this->reset([
            'reference_no', 'purchase_date', 'product_supplier_id', 'product_warehouse_id',
            'description', 'payment_status', 'refund_status', 'discount', 'tax', 'productItems', 'getPurchase'
        ]);
        $this->modalTitle = 'Create Purchase';
        $this->modalAction = 'create-purchase';
        $this->is_edit = false;
        $this->payment_status = 'pending';
        $this->refund_status = 'not_refunded';
        $this->discount = 0.00;
        $this->tax = 0.00;
        $this->reference_no = ProductPurchase::generateReferenceNo();
        $this->purchase_date = now()->format('Y-m-d');
        $this->productItems = [['product_id' => '', 'quantity' => 1, 'unit_price' => 0, 'subtotal' => 0]];
        $this->dispatch('hide-modal');
        $this->dispatch('datatable-reinit');
    }

    public function getDataTableRows(Request $request)
    {
        $length = $request->input('length');
        $start = $request->input('start');

        $query = ProductPurchase::with(['supplier', 'warehouse', 'purchasedItems', 'payments']);

        // Search
        if (!empty($request['search']['value'])) {
            $searchValue = $request['search']['value'];
            $query->where(function($q) use ($searchValue) {
                $q->where('reference_no', 'like', '%' . $searchValue . '%')
                  ->orWhereHas('supplier', function($sq) use ($searchValue) {
                      $sq->where('company_name', 'like', '%' . $searchValue . '%');
                  })
                  ->orWhereHas('warehouse', function($wq) use ($searchValue) {
                      $wq->where('name', 'like', '%' . $searchValue . '%');
                  });
            });
        }

        $total = $query->count();

        if ($length == -1) {
            $length = $total;
        }

        $query->skip($start)->take($length);
        $purchases = $query->orderBy('created_at', 'desc')->get();

        $rows = [];
        foreach ($purchases as $index => $purchase) {
            $subtotal = $purchase->purchasedItems->sum(function ($item) {
                return $item->quantity * $item->unit_price;
            });
            $discountAmount = $subtotal * ($purchase->discount / 100);
            $taxAmount = ($subtotal - $discountAmount) * ($purchase->tax / 100);
            $grandTotal = $subtotal - $discountAmount + $taxAmount;
            $totalPaid = $purchase->payments->sum('paid_amount');
            $remainingBalance = $grandTotal - $totalPaid;

            $paymentStatusBadge = match($purchase->payment_status) {
                'pending' => '<span class="badge bg-warning">Pending</span>',
                'partial_paid' => '<span class="badge bg-info">Partial Paid</span>',
                'fully_paid' => '<span class="badge bg-success">Fully Paid</span>',
                default => '<span class="badge bg-secondary">Unknown</span>'
            };

            $rows[] = [
                $start + $index + 1,
                e($purchase->reference_no),
                e($purchase->supplier->company_name ?? 'N/A'),
                e($purchase->warehouse->name ?? 'N/A'),
                $purchase->purchase_date->format('d M Y'),
                'PKR ' . number_format($grandTotal, 2),
                'PKR ' . number_format($totalPaid, 2),
                'PKR ' . number_format($remainingBalance, 2),
                $paymentStatusBadge,
                '<div class="action-items">'
                . '<span><a href="#" title="Edit Purchase" onclick="Livewire.dispatch(\'edit-mode\', {id: ' . $purchase->id . '})" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fa fa-edit"></i></a></span>'
                . '<span><a href="#" title="View Payments" onclick="Livewire.dispatch(\'viewPayments\', {purchaseId: ' . $purchase->id . '})"><i class="fa fa-credit-card"></i></a></span>'
                . '<span><a href="javascript:void(0)" class="delete-swal" data-id="' . $purchase->id . '" title="Delete Purchase"><i class="fa fa-trash"></i></a></span>'
                . '</div>',
            ];
        }

        return response()->json([
            'draw' => intval($request['search']['draw'] ?? $request['draw']),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $rows,
        ]);
    }

    #[Title('Purchase Management')]
    #[Layout('layouts.app')]
    public function render()
    {
        $suppliers = ProductSupplier::where('is_active', true)->orderBy('company_name')->get();
        $warehouses = ProductWarehouse::where('is_active', true)->orderBy('name')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();
        
        return view('livewire.inventory.purchase.index', compact('suppliers', 'warehouses', 'products'));
    }
}
