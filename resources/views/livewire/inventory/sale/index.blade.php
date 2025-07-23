<div>
    {{-- <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-0">
                <i class="fas fa-cash-register me-2"></i>Sale Management
            </h4>
            <p class="text-muted mb-0">Manage product sales and transactions</p>
        </div>
        <a href="{{ route('sales.create') }}" wire:navigate class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Add Sale
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bgs-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-md bg-soft-primary rounded-circle me-3">
                            <span class="avatar-title bg-transparent text-primary font-size-24">
                                <i class="fas fa-cash-register"></i>
                            </span>
                        </div>
                        <div>
                            <p class="text-muted mb-0">Total Sales</p>
                            <h5 class="mb-0">{{ $sales->count() }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bgs-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-md bg-soft-success rounded-circle me-3">
                            <span class="avatar-title bg-transparent text-success font-size-24">
                                <i class="fas fa-dollar-sign"></i>
                            </span>
                        </div>
                        <div>
                            <p class="text-muted mb-0">Total Revenue</p>
                            @php
                                $totalAmount = $sales->sum(function ($sale) {
                                    $subtotal = $sale->items->sum(function ($item) {
                                        return $item->quantity * $item->unit_price;
                                    });
                                    $discountAmount = $subtotal * ($sale->discount / 100);
                                    $taxAmount = ($subtotal - $discountAmount) * ($sale->tax / 100);
                                    return $subtotal - $discountAmount + $taxAmount;
                                });
                            @endphp
                            <h5 class="mb-0">PKR {{ number_format($totalAmount, 0) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bgs-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-md bg-soft-warning rounded-circle me-3">
                            <span class="avatar-title bg-transparent text-warning font-size-24">
                                <i class="fas fa-clock"></i>
                            </span>
                        </div>
                        <div>
                            <p class="text-muted mb-0">Pending Payments</p>
                            <h5 class="mb-0">{{ $sales->where('payment_status', 'due')->count() }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bgs-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-md bg-soft-info rounded-circle me-3">
                            <span class="avatar-title bg-transparent text-info font-size-24">
                                <i class="fas fa-users"></i>
                            </span>
                        </div>
                        <div>
                            <p class="text-muted mb-0">Active Customers</p>
                            <h5 class="mb-0">{{ $sales->pluck('user_id')->unique()->count() }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Sales Table -->
    <x-data-table 
        title="Sales"
        :createRoute="route('sales.create')"
        createButtonText="Add Sale"
        searchPlaceholder="Search sales..."
        :items="$sales"
        :isPageHeader="true"
        :showSearch="true"
        :showExport="true"
        :showPagination="true"
        :showPerPage="true"
        :perPageOptions="[10, 25, 50, 100]"
        :headers="['#', 'Reference No', 'Customer', 'Date', 'File', 'Grand Total', 'Paid', 'Balance', 'Actions']"
        :sortableHeaders="['id', 'reference_no', null, 'sale_date', null, null, null, null, null]"
    >
        @forelse($sales as $index => $sale)
            @php
                $subtotal = $sale->items->sum(fn($item) => $item->quantity * $item->unit_price);
                $discountAmount = $subtotal * ($sale->discount / 100);
                $taxAmount = ($subtotal - $discountAmount) * ($sale->tax / 100);
                $grandTotal = $subtotal - $discountAmount + $taxAmount;
                $totalPaid = $sale->payments->sum('paid_amount');
                $balance = $grandTotal - $totalPaid;
            @endphp
            <tr>
                <td>{{ $sales->firstItem() + $index }}</td>
                <td>
                    <span>{{ $sale->reference_no ?? 'N/A' }}</span>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        
                        <span class="fw-medium">{{ $sale->user->name ?? 'N/A' }}</span>
                    </div>
                </td>
                <td>{{ $sale->sale_date->format('d M Y') }}</td>
                <td>
                    @if ($sale->sale_slip)
                        <a href="{{ Storage::url($sale->sale_slip) }}" target="_blank"
                            class="btn btn-sm btn-outline-info">
                            <i class="fas fa-download"></i>
                        </a>
                    @else
                        <span class="text-muted">No file</span>
                    @endif
                </td>
                <td>
                    <span class="fw-semibold">PKR {{ number_format($grandTotal, 2) }}</span>
                </td>
                <td>
                    <span class="fw-semibold text-success">PKR {{ number_format($totalPaid, 2) }}</span>
                </td>
                <td>
                    <span class="fw-semibold {{ $balance > 0 ? 'text-danger' : 'text-success' }}">
                        PKR {{ number_format($balance, 2) }}
                    </span>
                </td>
                <td>
                    <div class="action-items">
                        <span title="View Sale">
                            <a href="{{ route('sales.view', $sale->id) }}" wire:navigate>
                                <i class="fa fa-eye"></i>
                            </a>
                        </span>
                        <span title="View Payments" wire:click="showPayments({{ $sale->id }})">
                            <i class="fa fa-money-bill-wave"></i>
                        </span>
                        <span title="Edit Sale">
                            <a href="{{ route('sales.edit', $sale->id) }}" wire:navigate>
                                <i class="fa fa-edit"></i>
                            </a>
                        </span>
                        <span title="Delete Sale" wire:click="delete({{ $sale->id }})" wire:confirm="Are you sure you want to delete this sale?">
                            <i class="fa fa-trash"></i>
                        </span>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center py-4">
                    <div class="d-flex flex-column align-items-center">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No sales found</h5>
                        <p class="text-muted">Try adjusting your search criteria</p>
                    </div>
                </td>
            </tr>
        @endforelse
    </x-data-table>

    <!-- Payment Modal -->
    @if ($showPaymentModal && $selectedSale)
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-money-bill-wave me-2"></i>
                            Payment History - {{ $selectedSale->reference_no }}
                        </h5>
                        <button type="button" wire:click="closePaymentModal" class="btn-close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Sale Summary -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card border-primary">
                                    <div class="card-body">
                                        <h6 class="card-title text-primary">Sale Information</h6>
                                        <p class="mb-1"><strong>Customer:</strong> {{ $selectedSale->user->name }}
                                        </p>
                                        <p class="mb-1"><strong>Date:</strong>
                                            {{ $selectedSale->sale_date->format('d M Y') }}</p>
                                        <p class="mb-0"><strong>Status:</strong>
                                            <span
                                                class="badge bg-{{ $selectedSale->payment_status === 'paid' ? 'success' : ($selectedSale->payment_status === 'partial' ? 'warning' : 'danger') }}">
                                                {{ ucfirst(str_replace('_', ' ', $selectedSale->payment_status)) }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-success">
                                    <div class="card-body">
                                        <h6 class="card-title text-success">Payment Summary</h6>
                                        @php
                                            $subtotal = $selectedSale->items->sum(
                                                fn($item) => $item->quantity * $item->unit_price,
                                            );
                                            $discountAmount = $subtotal * ($selectedSale->discount / 100);
                                            $taxAmount = ($subtotal - $discountAmount) * ($selectedSale->tax / 100);
                                            $grandTotal = $subtotal - $discountAmount + $taxAmount;
                                            $totalPaid = $payments->sum('paid_amount');
                                            $balance = $grandTotal - $totalPaid;
                                        @endphp
                                        <p class="mb-1"><strong>Grand Total:</strong> PKR
                                            {{ number_format($grandTotal, 2) }}</p>
                                        <p class="mb-1"><strong>Total Paid:</strong> PKR
                                            {{ number_format($totalPaid, 2) }}</p>
                                        <p class="mb-0"><strong>Balance:</strong>
                                            <span class="text-{{ $balance > 0 ? 'danger' : 'success' }}">
                                                PKR {{ number_format($balance, 2) }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Add Payment Button -->
                        @if (!$showAddPaymentForm && $balance > 0)
                            <div class="text-end mb-3">
                                <button type="button" wire:click="showAddPaymentForm" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>Add Payment
                                </button>
                            </div>
                        @endif

                        <!-- Add Payment Form -->
                        @if ($showAddPaymentForm)
                            <div class="card border-warning mb-4">
                                <div class="card-header bg-warning bg-opacity-10">
                                    <h6 class="card-title mb-0 text-warning">
                                        <i class="fas fa-plus me-2"></i>Add New Payment
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <form wire:submit.prevent="addPayment">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Reference No *</label>
                                                <input type="text" wire:model="paymentReferenceNo"
                                                    class="form-control @error('paymentReferenceNo') is-invalid @enderror"
                                                    readonly>
                                                @error('paymentReferenceNo')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Amount *</label>
                                                <input type="number" wire:model="paymentAmount" step="0.01"
                                                    class="form-control @error('paymentAmount') is-invalid @enderror"
                                                    placeholder="Enter amount">
                                                @error('paymentAmount')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Payment Method *</label>
                                                <select wire:model="paymentMethod"
                                                    class="form-select @error('paymentMethod') is-invalid @enderror">
                                                    <option value="cash">Cash</option>
                                                    <option value="cheque">Cheque</option>
                                                    <option value="credit_card">Credit Card</option>
                                                    <option value="other">Other</option>
                                                </select>
                                                @error('paymentMethod')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Payment Date *</label>
                                                <input type="date" wire:model="paymentDate"
                                                    class="form-control @error('paymentDate') is-invalid @enderror">
                                                @error('paymentDate')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Payment Slip</label>
                                                <input type="text" wire:model="paymentSlip"
                                                    class="form-control @error('paymentSlip') is-invalid @enderror"
                                                    placeholder="Optional slip reference">
                                                @error('paymentSlip')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Description</label>
                                                <input type="text" wire:model="paymentDescription"
                                                    class="form-control @error('paymentDescription') is-invalid @enderror"
                                                    placeholder="Optional description">
                                                @error('paymentDescription')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="text-end mt-3">
                                            <button type="button" wire:click="hideAddPaymentForm"
                                                class="btn btn-secondary me-2">Cancel</button>
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-save me-1"></i>Save Payment
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif

                        <!-- Payments List -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Reference No</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Date</th>
                                        <th>Added By</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($payments as $payment)
                                        <tr>
                                            <td>{{ $payment->reference_no }}</td>
                                            <td class="fw-semibold text-success">PKR
                                                {{ number_format($payment->paid_amount, 2) }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-info">{{ ucfirst($payment->payment_method) }}</span>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}
                                            </td>
                                            <td>{{ $payment->created_by ?? 'N/A' }}</td>
                                            <td>
                                                <button type="button"
                                                    wire:click="deletePayment({{ $payment->id }})"
                                                    wire:confirm="Are you sure you want to delete this payment?"
                                                    class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-3">
                                                <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                                <p class="text-muted mb-0">No payments found</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

