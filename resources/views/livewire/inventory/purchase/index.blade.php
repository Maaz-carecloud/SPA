<div>
    <!-- Page Header -->
    {{-- <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-0">
                <i class="fas fa-shopping-cart me-2"></i>Purchase Management
            </h4>
            <p class="text-muted mb-0">Manage product purchases and transactions</p>
        </div> <a href="{{ route('purchases.create') }}" wire:navigate class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Add Purchase
        </a>
    </div> --}}

    <!-- Statistics Cards -->
    {{-- <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bgs-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-md bg-soft-primary rounded-circle me-3">
                            <span class="avatar-title bg-transparent text-primary font-size-24">
                                <i class="fas fa-shopping-cart"></i>
                            </span>
                        </div>
                        <div>
                            <p class="text-muted mb-0">Total Purchases</p>
                            <h5 class="mb-0">{{ $purchases->count() }}</h5>
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
                            <p class="text-muted mb-0">Total Amount</p>
                            @php
                                $totalAmount = $purchases->sum(function ($purchase) {
                                    $subtotal = $purchase->purchasedItems->sum(function ($item) {
                                        return $item->quantity * $item->unit_price;
                                    });
                                    $discountAmount = $subtotal * ($purchase->discount / 100);
                                    $taxAmount = ($subtotal - $discountAmount) * ($purchase->tax / 100);
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
                            <h5 class="mb-0">{{ $purchases->where('payment_status', 'pending')->count() }}</h5>
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
                                <i class="fas fa-building"></i>
                            </span>
                        </div>
                        <div>
                            <p class="text-muted mb-0">Active Suppliers</p>
                            <h5 class="mb-0">{{ $purchases->pluck('product_supplier_id')->unique()->count() }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- Filters Card -->
    <div class="card bgs-card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control"
                        placeholder="Search by reference no, product, supplier...">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Payment Status</label>
                    <select wire:model.live="filterStatus" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="partial_paid">Partial Paid</option>
                        <option value="fully_paid">Fully Paid</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Date From</label>
                    <input type="date" wire:model.live="dateFrom" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Date To</label>
                    <input type="date" wire:model.live="dateTo" class="form-control">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button wire:click="resetFilters" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-refresh"></i>
                    </button>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Advanced Search Panel (Hidden by default) -->
    {{-- <div class="card bgs-card mb-4" id="advancedSearch">
        <div class="card-header">
            <h6 class="card-title mb-0">
                <i class="fas fa-search me-2"></i>Advanced Search & Filters
            </h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Supplier</label>
                    <select wire:model.live="filterSupplier" class="form-select">
                        <option value="">All Suppliers</option>
                        @foreach (\App\Models\ProductSupplier::where('is_active', true)->orderBy('company_name')->get() as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->company_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Warehouse</label>
                    <select wire:model.live="filterWarehouse" class="form-select">
                        <option value="">All Warehouses</option>
                        @foreach (\App\Models\ProductWarehouse::where('is_active', true)->orderBy('name')->get() as $warehouse)
                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Refund Status</label>
                    <select wire:model.live="filterRefund" class="form-select">
                        <option value="">All Refund Status</option>
                        <option value="not_refunded">Not Refunded</option>
                        <option value="refunded">Refunded</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Amount Range</label>
                    <div class="input-group">
                        <input type="number" wire:model.live="minAmount" class="form-control" placeholder="Min">
                        <span class="input-group-text">-</span>
                        <input type="number" wire:model.live="maxAmount" class="form-control" placeholder="Max">
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    
    {{-- <!-- Advanced Filter Toggle Button -->
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-outline-primary" onclick="toggleAdvancedSearch()">
            <i class="fas fa-filter me-1"></i>Advanced Filter
        </button>
    </div> --}}

    <!-- Purchases Table -->
    <x-data-table 
        title="Purchases"
        :createRoute="route('purchases.create')"
        createButtonText="Add Purchase"
        searchPlaceholder="Search purchases..."
        :items="$purchases"
        :isPageHeader="true"
        :showSearch="true"
        :showExport="true"
        :showPagination="true"
        :showPerPage="true"
        :perPageOptions="[10, 25, 50, 100]"
        :headers="['#', 'Reference No', 'Product', 'Date', 'Supplier', 'Warehouse', 'Grand Total', 'Paid', 'Balance', 'Actions']"
        :sortableHeaders="['id', 'reference_no', null, 'purchase_date', null, null, null, null, null, null]"
    >
        @forelse($purchases as $index => $purchase)
            <tr>
                <td>{{ $purchases->firstItem() + $index }}</td>
                <td>
                    {{ $purchase->reference_no ?? 'N/A' }}
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <div>
                            <span class="fw-medium">{{ $purchase->purchasedItems->count() }}
                                items</span>
                            
                        </div>
                    </div>
                </td>
                <td>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}</td>
                <td>
                    <div class="d-flex align-items-center">
                        
                        <div>
                            <span
                                >{{ $purchase->supplier->company_name ?? 'N/A' }}</span>
                            
                        </div>
                    </div>
                </td>
                <td>
                    <span>{{ $purchase->warehouse->name ?? 'N/A' }}</span>
                </td>
                <td>
                    @php
                        $subtotal = $purchase->purchasedItems->sum(function ($item) {
                            return $item->quantity * $item->unit_price;
                        });
                        $discountAmount = $subtotal * ($purchase->discount / 100);
                        $taxAmount = ($subtotal - $discountAmount) * ($purchase->tax / 100);
                        $grandTotal = $subtotal - $discountAmount + $taxAmount;
                        // Calculate total paid
                        $totalPaid = $purchase->payments->sum('paid_amount');
                        $balance = $grandTotal - $totalPaid;
                    @endphp
                    <span>PKR {{ number_format($grandTotal, 2) }}</span>
                </td>
                <td>
                    <span>PKR {{ number_format($totalPaid, 2) }}</span>
                </td>
                <td>
                    @if ($balance <= 0)
                        <span>PKR 0.00</span>
                    @else
                        <span>PKR {{ number_format($balance, 2) }}</span>
                    @endif
                </td>
                <td>
                    <div class="action-items">
                        <span title="View Purchase">
                            <a href="{{ route('purchases.view', $purchase->id) }}" wire:navigate>
                                <i class="fa fa-eye"></i>
                            </a>
                        </span>
                        <span title="View Payments" wire:click="viewPayments({{ $purchase->id }})">
                            <i class="fa fa-credit-card"></i>
                        </span>
                        <span title="Edit Purchase">
                            <a href="{{ route('purchases.edit', $purchase->id) }}" wire:navigate>
                                <i class="fa fa-edit"></i>
                            </a>
                        </span>
                        <span title="Delete Purchase" wire:click="delete({{ $purchase->id }})" wire:confirm="Are you sure you want to delete this purchase?">
                            <i class="fa fa-trash"></i>
                        </span>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="10" class="text-center py-5">
                    <div class="d-flex flex-column align-items-center">
                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">No purchases found</h6>
                        <p class="text-muted mb-0">Get started by creating your first purchase</p>
                    </div>
                </td>
            </tr>
        @endforelse
    </x-data-table>

    <!-- View Payments Modal -->
    @if ($showPaymentModal && $selectedPurchase)
        <div class="modal fade show" id="viewPaymentsModal" tabindex="-1" aria-labelledby="viewPaymentsModalLabel"
            style="display: block; background-color: rgba(0,0,0,0.5);" wire:ignore.self>
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewPaymentsModalLabel">
                            <i class="fas fa-credit-card me-2"></i>View Payments -
                            {{ $selectedPurchase->reference_no }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="closePaymentModal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Purchase Summary -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Purchase Details</h6>
                                        <p class="mb-1"><strong>Reference:</strong>
                                            {{ $selectedPurchase->reference_no }}</p>
                                        <p class="mb-1"><strong>Date:</strong>
                                            {{ \Carbon\Carbon::parse($selectedPurchase->purchase_date)->format('d M Y') }}
                                        </p>
                                        <p class="mb-1"><strong>Supplier:</strong>
                                            {{ $selectedPurchase->supplier->company_name ?? 'N/A' }}</p>
                                        <p class="mb-0"><strong>Warehouse:</strong>
                                            {{ $selectedPurchase->warehouse->name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Payment Summary</h6>
                                        @php
                                            $subtotal = $selectedPurchase->purchasedItems->sum(function ($item) {
                                                return $item->quantity * $item->unit_price;
                                            });
                                            $discountAmount = $subtotal * ($selectedPurchase->discount / 100);
                                            $taxAmount = ($subtotal - $discountAmount) * ($selectedPurchase->tax / 100);
                                            $grandTotal = $subtotal - $discountAmount + $taxAmount;
                                            $totalPaid = $payments->sum('paid_amount');
                                            $balance = $grandTotal - $totalPaid;
                                        @endphp
                                        <p class="mb-1"><strong>Total Amount:</strong> PKR
                                            {{ number_format($grandTotal, 2) }}</p>
                                        <p class="mb-1"><strong>Total Paid:</strong> <span class="text-success">PKR
                                                {{ number_format($totalPaid, 2) }}</span></p>
                                        <p class="mb-0"><strong>Balance:</strong>
                                            <span class="{{ $balance > 0 ? 'text-danger' : 'text-success' }}">
                                                PKR {{ number_format($balance, 2) }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payments Table -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Reference No</th>
                                        <th>Amount</th>
                                        <th>Payment Method</th>
                                        <th>Paid By</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($payments as $index => $payment)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}
                                            </td>
                                            <td>{{ $payment->reference_no }}</td>
                                            <td><strong>PKR {{ number_format($payment->paid_amount, 2) }}</strong></td>
                                            <td>
                                                <span class="badge bg-info">{{ $payment->payment_method }}</span>
                                            </td>
                                            <td>{{ $payment->created_by ?? 'N/A' }}</td>
                                            <td>                                                <div class="d-flex gap-1">
                                                    <button class="btn btn-sm btn-outline-danger"
                                                        title="Delete Payment"
                                                        onclick="confirmPaymentDelete({{ $payment->id }}, '{{ addslashes($payment->reference_no) }}')"
                                                        data-bs-toggle="tooltip"
                                                        type="button"
                                                        @if($deletingPaymentId == $payment->id) disabled @endif>
                                                        @if($deletingPaymentId == $payment->id)
                                                            <i class="fas fa-spinner fa-spin"></i>
                                                        @else
                                                            <i class="fas fa-trash"></i>
                                                        @endif
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                                                    <h6 class="text-muted">No payments found</h6>
                                                    <p class="text-muted mb-0">No payments have been made for this
                                                        purchase yet.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div> <!-- Add Payment Form -->
                    @if ($showAddPaymentForm)
                        <div class="payment-form-section border-top">
                            <h6 class="mb-3">
                                <i class="fas fa-plus-circle me-2"></i>Add New Payment
                            </h6>

                            @php
                                $subtotal = $selectedPurchase->purchasedItems->sum(function ($item) {
                                    return $item->quantity * $item->unit_price;
                                });
                                $discountAmount = $subtotal * ($selectedPurchase->discount / 100);
                                $taxAmount = ($subtotal - $discountAmount) * ($selectedPurchase->tax / 100);
                                $grandTotal = $subtotal - $discountAmount + $taxAmount;
                                $totalPaid = $payments->sum('paid_amount');
                                $remainingBalance = $grandTotal - $totalPaid;
                            @endphp

                            @if ($remainingBalance > 0)
                                <div class="balance-warning">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Remaining Balance:</strong> PKR {{ number_format($remainingBalance, 2) }}
                                </div>
                            @endif

                            <form wire:submit.prevent="addPayment">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="paymentReferenceNo" class="form-label fw-semibold">
                                            <i class="fas fa-hashtag me-1"></i>Reference Number *
                                        </label>
                                        <input type="text" id="paymentReferenceNo" wire:model="paymentReferenceNo"
                                            class="form-control @error('paymentReferenceNo') is-invalid @enderror"
                                            placeholder="Enter payment reference">
                                        @error('paymentReferenceNo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="paymentAmount" class="form-label fw-semibold">
                                            <i class="fas fa-money-bill me-1"></i>Payment Amount (PKR) *
                                        </label>
                                        <input type="number" id="paymentAmount" wire:model="paymentAmount"
                                            step="0.01" min="0.01" max="{{ $remainingBalance }}"
                                            class="form-control payment-amount-input @error('paymentAmount') is-invalid @enderror"
                                            placeholder="0.00">
                                        @error('paymentAmount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Maximum: PKR {{ number_format($remainingBalance, 2) }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="paymentMethod" class="form-label fw-semibold">
                                            <i class="fas fa-credit-card me-1"></i>Payment Method *
                                        </label>
                                        <select id="paymentMethod" wire:model="paymentMethod"
                                            class="form-select payment-method-select @error('paymentMethod') is-invalid @enderror">
                                            <option value="cash">💵 Cash</option>
                                            <option value="cheque">📝 Cheque</option>
                                            <option value="credit_card">💳 Credit Card</option>
                                            <option value="other">🔄 Other</option>
                                        </select>
                                        @error('paymentMethod')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="paymentDate" class="form-label fw-semibold">
                                            <i class="fas fa-calendar me-1"></i>Payment Date *
                                        </label>
                                        <input type="date" id="paymentDate" wire:model="paymentDate"
                                            class="form-control @error('paymentDate') is-invalid @enderror">
                                        @error('paymentDate')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="paymentSlip" class="form-label fw-semibold">
                                            <i class="fas fa-receipt me-1"></i>Payment Slip/Reference
                                        </label>
                                        <input type="text" id="paymentSlip" wire:model="paymentSlip"
                                            class="form-control @error('paymentSlip') is-invalid @enderror"
                                            placeholder="Enter slip number or reference">
                                        @error('paymentSlip')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <!-- Quick Amount Buttons -->
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-bolt me-1"></i>Quick Amount
                                        </label>
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-outline-primary btn-sm"
                                                wire:click="$set('paymentAmount', {{ $remainingBalance }})">
                                                Full Amount
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm"
                                                wire:click="$set('paymentAmount', {{ $remainingBalance / 2 }})">
                                                Half Amount
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label for="paymentDescription" class="form-label fw-semibold">
                                            <i class="fas fa-comment me-1"></i>Description
                                        </label>
                                        <textarea id="paymentDescription" wire:model="paymentDescription" rows="3"
                                            class="form-control @error('paymentDescription') is-invalid @enderror"
                                            placeholder="Enter payment description (optional)"></textarea>
                                        @error('paymentDescription')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mt-3 d-flex gap-2 align-items-center">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save me-1"></i>Save Payment
                                    </button>
                                    <button type="button" class="btn btn-secondary" wire:click="hideAddPaymentForm">
                                        <i class="fas fa-times me-1"></i>Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                    <div class="modal-footer">
                        @if (!$showAddPaymentForm)
                            <button type="button" class="btn btn-primary" wire:click="openPaymentForm">
                                <i class="fas fa-plus me-1"></i>Add Payment
                            </button>
                        @endif
                        <button type="button" class="btn btn-secondary" wire:click="closePaymentModal">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('styles')
    <style>
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_processing,
        .dataTables_wrapper .dataTables_paginate {
            margin-bottom: 1rem;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.375rem 0.75rem;
            margin: 0 2px;
            border-radius: 0.375rem;
        }

        .btn-group-sm>.btn,
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            border-radius: 0.25rem;
        }

        #advancedSearch {
            display: none;
        }

        /* Payment Form Styling */
        .payment-form-section {
            background-color: #f8f9fa;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-top: 1rem;
        }

        .payment-form-section h6 {
            color: #495057;
            border-bottom: 2px solid #007bff;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
        }

        .payment-amount-input:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }

        .payment-method-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m1 6 7 7 7-7'/%3e%3c/svg%3e");
        }

        .balance-warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 0.375rem;
            padding: 0.75rem;
            margin-bottom: 1rem;
        }
    </style>
@endpush

@push('scripts')
    <script>
        let purchasesTable;

        function initializeDataTable() {
            // Only initialize if table exists and has data
            const table = $('#purchasesTable');
            if (table.length === 0 || table.find('tbody tr').length === 0) {
                return;
            }

            // Destroy existing DataTable instance safely
            if ($.fn.dataTable.isDataTable('#purchasesTable')) {
                try {
                    $('#purchasesTable').DataTable().destroy();
                } catch (e) {
                    console.log('DataTable destroy error (safe to ignore):', e);
                }
            }

            // Clear any DataTable classes that might interfere
            table.removeClass('dataTable');

            try {
                purchasesTable = $('#purchasesTable').DataTable({
                    responsive: true,
                    pageLength: 25,
                    order: [
                        [0, 'desc']
                    ],
                    searching: false, // Disable DataTable search since we use Livewire
                    paging: false, // Disable DataTable pagination since we use Livewire
                    info: false, // Disable info since we handle it via Livewire
                    dom: '<"row"<"col-sm-12 col-md-12"B>>' +
                        '<"row"<"col-sm-12"tr>>',
                    buttons: [{
                            extend: 'excel',
                            text: '<i class="fas fa-file-excel me-1"></i> Excel',
                            className: 'btn btn-success btn-sm',
                            title: 'Purchases List - ' + new Date().toLocaleDateString(),
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7] // Exclude actions column
                            }
                        },
                        {
                            extend: 'pdf',
                            text: '<i class="fas fa-file-pdf me-1"></i> PDF',
                            className: 'btn btn-danger btn-sm',
                            title: 'Purchases List',
                            orientation: 'landscape',
                            pageSize: 'A4',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7] // Exclude actions column
                            }
                        },
                        {
                            extend: 'print',
                            text: '<i class="fas fa-print me-1"></i> Print',
                            className: 'btn btn-info btn-sm',
                            title: 'Purchases List',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7] // Exclude actions column
                            }
                        }
                    ],
                    columnDefs: [{
                            targets: [8], // Actions column
                            orderable: false,
                            searchable: false
                        },
                        {
                            targets: [5], // Products column
                            searchable: false
                        }
                    ],
                    language: {
                        emptyTable: "No purchases available",
                        zeroRecords: "No matching purchases found"
                    },
                    drawCallback: function(settings) {
                        // Re-initialize tooltips after table redraw
                        $('[title]').tooltip();
                    }
                });
            } catch (e) {
                console.error('DataTable initialization error:', e);
                // If DataTable fails, just continue without it
            }
        }

        function confirmDelete(id, reference) {
            if (confirm(`Are you sure you want to delete purchase "${reference}"?`)) {
                @this.call('delete', id);
            }
        }

        function toggleAdvancedSearch() {
            const advancedSearch = document.getElementById('advancedSearch');
            if (advancedSearch.style.display === 'none' || advancedSearch.style.display === '') {
                advancedSearch.style.display = 'block';
            } else {
                advancedSearch.style.display = 'none';
            }
        }

        // Initialize DataTable when document is ready
        document.addEventListener('DOMContentLoaded', function() {
            initializeDataTable();

            // Initialize tooltips
            $('[title]').tooltip();
        });

        // Re-initialize DataTable after Livewire updates
        document.addEventListener('livewire:navigated', function() {
            setTimeout(() => {
                initializeDataTable();
            }, 100);
        }); // Listen for Livewire updates
        window.addEventListener('livewire:load', function() {
            Livewire.hook('morph.updated', ({
                el,
                component
            }) => {
                setTimeout(() => {
                    initializeDataTable();
                }, 100);
            });
        });

        // Calculate remaining balance for payment amount validation
        function calculateRemainingBalance() {
            const paymentAmountInput = document.getElementById('paymentAmount');
            if (paymentAmountInput) {
                paymentAmountInput.addEventListener('input', function() {
                    // This will trigger Livewire validation
                    @this.set('paymentAmount', this.value);
                });
            }
        } // Auto-focus payment amount when form shows
        document.addEventListener('livewire:updated', function() {
            const paymentAmountInput = document.getElementById('paymentAmount');
            if (paymentAmountInput && document.querySelector('.payment-form-section')) {
                setTimeout(() => {
                    paymentAmountInput.focus();
                }, 100);
            }
        });        // Payment deletion confirmation
        function confirmPaymentDelete(paymentId, referenceNo) {
            if (confirm(`Are you sure you want to delete payment "${referenceNo}"?\n\nThis action cannot be undone.`)) {
                @this.call('deletePayment', paymentId);
            }
        }

        // Format payment amount as user types
        document.addEventListener('input', function(e) {
            if (e.target.id === 'paymentAmount') {
                let value = e.target.value;
                if (value && !isNaN(value)) {
                    // Optional: Add thousand separators as user types
                    // This is just for display, actual value remains numeric
                }
            }
        });
    </script>
@endpush
</div>
