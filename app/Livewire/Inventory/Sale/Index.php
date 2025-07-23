<?php

namespace App\Livewire\Inventory\Sale;

use App\Models\ProductSale;
use App\Models\ProductSalePaid;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class Index extends Component
{
    use WithPagination;    
    public $search = '';
    public $perPage = 25;
    public $sortField = 'id';
    public $sortDirection = 'desc';
      
    // Payment Modal Properties
    public $selectedSale = null;
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

    protected $rules = [
        'paymentReferenceNo' => 'required|string|max:255|unique:product_sale_paids,reference_no',
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
    ];

    public function mount()
    {
        $this->paymentDate = now()->format('Y-m-d');
    }

    public function updatingSearch()
    {
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

    public function showPayments($saleId)
    {
        $this->selectedSale = ProductSale::with([
            'user', 
            'items.product'
        ])->findOrFail($saleId);
        
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

    public function showAddPaymentForm()
    {
        $this->showAddPaymentForm = true;
        $this->generatePaymentReferenceNo();
    }

    public function hideAddPaymentForm()
    {
        $this->showAddPaymentForm = false;
        $this->resetPaymentForm();
    }

    private function generatePaymentReferenceNo()
    {
        $prefix = 'SP'; // Sale Payment
        $date = now()->format('Ymd');
        $count = ProductSalePaid::whereDate('created_at', now())->count() + 1;
        $this->paymentReferenceNo = $prefix . $date . str_pad($count, 3, '0', STR_PAD_LEFT);
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

            // Refresh the payments and sale data
            $this->selectedSale->refresh();
            $this->selectedSale->load(['user', 'items']);
            $this->payments = $this->selectedSale->payments;

            // Update sale payment status
            $this->updateSalePaymentStatus();

            $this->hideAddPaymentForm();
            $this->dispatch('success', message: 'Payment added successfully.');

        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error adding payment: ' . $e->getMessage());
        }
    }

    private function validatePaymentAmount()
    {
        if (!$this->selectedSale) {
            return;
        }

        $subtotal = $this->selectedSale->items->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });
        $discountAmount = $subtotal * ($this->selectedSale->discount / 100);
        $taxAmount = ($subtotal - $discountAmount) * ($this->selectedSale->tax / 100);
        $grandTotal = $subtotal - $discountAmount + $taxAmount;

        $totalPaid = collect($this->payments)->sum('paid_amount');
        $remainingAmount = $grandTotal - $totalPaid;

        if ($this->paymentAmount > $remainingAmount) {
            $this->addError('paymentAmount', 'Payment amount cannot exceed the remaining balance of $' . number_format($remainingAmount, 2));
        }
    }

    private function updateSalePaymentStatus()
    {
        if (!$this->selectedSale) {
            return;
        }

        $subtotal = $this->selectedSale->items->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });
        $discountAmount = $subtotal * ($this->selectedSale->discount / 100);
        $taxAmount = ($subtotal - $discountAmount) * ($this->selectedSale->tax / 100);
        $grandTotal = $subtotal - $discountAmount + $taxAmount;
        $totalPaid = collect($this->payments)->sum('paid_amount');
        
        if ($totalPaid >= $grandTotal) {
            $paymentStatus = 'paid';
        } elseif ($totalPaid > 0) {
            $paymentStatus = 'partial';
        } else {
            $paymentStatus = 'due';
        }
        
        $this->selectedSale->update([
            'payment_status' => $paymentStatus,
            'updated_by' => Auth::user()->name,
        ]);
    }    
    
    public function deletePayment($paymentId)
    {
        try {
            $payment = ProductSalePaid::findOrFail($paymentId);
            
            // Check if this payment belongs to the selected sale
            if ($payment->product_sale_id !== $this->selectedSale->id) {
                $this->dispatch('error', message: 'Unauthorized action.');
                return;
            }

            $payment->delete();

            // Refresh the payments and sale data
            $this->selectedSale->refresh();
            $this->selectedSale->load(['user', 'items']);
            $this->payments = $this->selectedSale->payments;

            // Update sale payment status
            $this->updateSalePaymentStatus();

            $this->dispatch('success', message: 'Payment deleted successfully.');

        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error deleting payment: ' . $e->getMessage());
        }
    }    
    
    public function render()
    {
        $sales = ProductSale::with(['user', 'items', 'payments'])
            ->where(function($query) {
                if (!empty($this->search)) {
                    $query->where('reference_no', 'like', '%' . $this->search . '%')
                          ->orWhereHas('user', function($q) {
                              $q->where('name', 'like', '%' . $this->search . '%');
                          })
                          ->orWhere('description', 'like', '%' . $this->search . '%');
                }
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.inventory.sale.index', compact('sales'));
    }

    public function delete($id)
    {
        try {
            $sale = ProductSale::findOrFail($id);
            
            // Delete related items and payments
            $sale->items()->delete();
            $sale->payments()->delete();
            $sale->delete();
            
            $this->dispatch('success', message: 'Sale deleted successfully.');
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error deleting sale: ' . $e->getMessage());
        }
    }
}
