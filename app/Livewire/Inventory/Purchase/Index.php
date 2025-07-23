<?php

namespace App\Livewire\Inventory\Purchase;

use App\Models\ProductPurchase;
use App\Models\ProductPurchasePaid;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;    
    
    public $search = '';
    public $perPage = 25;
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $filterStatus = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $filterSupplier = '';
    public $filterWarehouse = '';
    public $filterRefund = '';
    public $minAmount = '';
    public $maxAmount = '';
      
    // Payment Modal Properties
    public $selectedPurchase = null;
    public $showPaymentModal = false;
    public $payments = [];
    
    // Add Payment Form Properties
    public $showAddPaymentForm = false;
    public $paymentReferenceNo = '';
    public $paymentAmount = '';
    public $paymentMethod = 'cash';
    public $paymentDate = '';
    public $paymentDescription = '';
    public $paymentSlip = '';

    // Loading states
    public $deletingPaymentId = null;

    protected $rules = [
        'paymentReferenceNo' => 'required|string|max:255|unique:product_purchase_paids,reference_no',
        'paymentAmount' => 'required|numeric|min:0.01',
        'paymentMethod' => 'required|in:cash,cheque,credit_card,other',
        'paymentDate' => 'required|date',
        'paymentDescription' => 'nullable|string|max:1000',
        'paymentSlip' => 'nullable|string|max:255',
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 25],
        'sortField' => ['except' => 'id'],
        'sortDirection' => ['except' => 'desc'],
        'filterStatus' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'filterSupplier' => ['except' => ''],
        'filterWarehouse' => ['except' => ''],
        'filterRefund' => ['except' => ''],
        'minAmount' => ['except' => ''],
        'maxAmount' => ['except' => ''],
    ];
    
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingDateFrom()
    {
        $this->resetPage();
    }

    public function updatingDateTo()
    {
        $this->resetPage();
    }    
    
    public function updatingFilterSupplier()
    {
        $this->resetPage();
    }

    public function updatingFilterWarehouse()
    {
        $this->resetPage();
    }

    public function updatingFilterRefund()
    {
        $this->resetPage();
    }

    public function updatingMinAmount()
    {
        $this->resetPage();
    }

    public function updatingMaxAmount()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterStatus = '';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->filterSupplier = '';
        $this->filterWarehouse = '';
        $this->filterRefund = '';
        $this->minAmount = '';
        $this->maxAmount = '';
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function delete($id)
    {
        try {
            $purchase = ProductPurchase::findOrFail($id);
            $purchase->delete();
            $this->dispatch('success', message: 'Purchase deleted successfully.');
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error deleting purchase: ' . $e->getMessage());
        }
    }    
    
    public function viewPayments($purchaseId)
    {
        $this->selectedPurchase = ProductPurchase::with(['payments', 'supplier', 'warehouse', 'purchasedItems'])
            ->findOrFail($purchaseId);
        $this->payments = $this->selectedPurchase->payments;
        $this->showPaymentModal = true;
        $this->resetPaymentForm();
        $this->dispatch('show-payment-modal');
    }    
    
    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
        $this->showAddPaymentForm = false;
        $this->selectedPurchase = null;
        $this->payments = [];
        $this->resetPaymentForm();
    }    
    
    public function openPaymentForm()
    {
        $this->showAddPaymentForm = true;
        $this->paymentDate = now()->format('Y-m-d');
        $this->paymentReferenceNo = 'PAY-' . date('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        $this->dispatch('success', message: 'Payment form opened successfully!');
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
        $this->resetErrorBag();
    }

    public function addPayment()
    {
        // Custom validation for payment amount
        $this->validatePaymentAmount();
        
        $this->validate();

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

            // Refresh the payments and purchase data
            $this->selectedPurchase->refresh();
            $this->selectedPurchase->load(['payments', 'supplier', 'warehouse', 'purchasedItems']);
            $this->payments = $this->selectedPurchase->payments;

            // Update purchase payment status
            $this->updatePurchasePaymentStatus();

            $this->hideAddPaymentForm();
            $this->dispatch('success', message: 'Payment added successfully.');

        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error adding payment: ' . $e->getMessage());
        }
    }

    private function validatePaymentAmount()
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
        $totalPaid = $this->payments->sum('paid_amount');
        $remainingBalance = $grandTotal - $totalPaid;

        if ($this->paymentAmount > $remainingBalance) {
            $this->addError('paymentAmount', 'Payment amount cannot exceed the remaining balance of PKR ' . number_format($remainingBalance, 2));
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
        $totalPaid = $this->payments->sum('paid_amount');

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

    public function deletePayment($paymentId)
    {
    
        try {
            $this->deletingPaymentId = $paymentId;

            $payment = ProductPurchasePaid::findOrFail($paymentId);
            
            // Check if this payment belongs to the selected purchase
            if ($payment->product_purchase_id !== $this->selectedPurchase->id) {
                $this->dispatch('error', message: 'Unauthorized action.');
                return;
            }
            
            $paymentReference = $payment->reference_no;
            $paymentAmount = $payment->paid_amount;

            // Delete the payment
            $payment->delete();

            // Refresh the payments and purchase data
            $this->selectedPurchase->refresh();
            $this->selectedPurchase->load(['payments', 'supplier', 'warehouse', 'purchasedItems']);
            $this->payments = $this->selectedPurchase->payments;
            // Update purchase payment status
            $this->updatePurchasePaymentStatus();

            $this->dispatch('success', message: "Payment {$paymentReference} (PKR " . number_format($paymentAmount, 2) . ") deleted successfully.");

        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error deleting payment: ' . $e->getMessage());
        
        } finally {
            $this->deletingPaymentId = null;
        }
    }

    #[Title('Purchase Management')]
    #[Layout('components.layouts.app')]
    public function render()
    {
        $purchases = ProductPurchase::with(['supplier', 'warehouse', 'purchasedItems', 'payments'])
            ->where(function($query) {
                if (!empty($this->search)) {
                    $query->where('reference_no', 'like', '%' . $this->search . '%')
                          ->orWhereHas('supplier', function($q) {
                              $q->where('company_name', 'like', '%' . $this->search . '%');
                          })
                          ->orWhereHas('warehouse', function($q) {
                              $q->where('name', 'like', '%' . $this->search . '%');
                          });
                }
            })
            ->when($this->filterStatus, function($query) {
                $query->where('payment_status', $this->filterStatus);
            })
            ->when($this->filterSupplier, function($query) {
                $query->where('product_supplier_id', $this->filterSupplier);
            })
            ->when($this->filterWarehouse, function($query) {
                $query->where('product_warehouse_id', $this->filterWarehouse);
            })
            ->when($this->filterRefund, function($query) {
                $query->where('refund_status', $this->filterRefund);
            })
            ->when($this->dateFrom, function($query) {
                $query->whereDate('purchase_date', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function($query) {
                $query->whereDate('purchase_date', '<=', $this->dateTo);
            })
            ->when($this->minAmount || $this->maxAmount, function($query) {
                $query->whereHas('purchasedItems', function($q) {
                    $q->selectRaw('SUM(quantity * unit_price) as total')
                      ->groupBy('product_purchase_id')
                      ->havingRaw('total >= ?', [$this->minAmount ?: 0]);
                    
                    if ($this->maxAmount) {
                        $q->havingRaw('total <= ?', [$this->maxAmount]);
                    }
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage); // Use paginate instead of get() for proper pagination

        return view('livewire.inventory.purchase.index', compact('purchases'));
    }
}
