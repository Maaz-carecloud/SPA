<x-sections.default>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Purchase Management</h3>
        <button type="button" class="btn theme-filled-btn" data-bs-toggle="modal" data-bs-target="#createModal">
            + Create Purchase
        </button>
    </div>

    @php
        $columns = ['#', 'Reference No', 'Supplier', 'Warehouse', 'Purchase Date', 'Grand Total', 'Total Paid', 'Balance', 'Payment Status', 'Action'];
    @endphp
    <livewire:data-table :columns="$columns" table-id="purchasesTable" ajax-url="/datatable/purchases" :key="microtime(true)" />

    <x-modal id="createModal" :title="$modalTitle" :action="$modalAction" :is_edit="$is_edit">
        <form>
            <div class="row">
                <!-- Reference Number -->
                <div class="col-md-6 mb-3">
                    <label for="reference_no" class="form-label">Reference Number <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control @error('reference_no') is-invalid @enderror" 
                           id="reference_no" 
                           wire:model="reference_no" 
                           readonly>
                    @error('reference_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Purchase Date -->
                <div class="col-md-6 mb-3">
                    <label for="purchase_date" class="form-label">Purchase Date <span class="text-danger">*</span></label>
                    <input type="date" 
                           class="form-control @error('purchase_date') is-invalid @enderror" 
                           id="purchase_date" 
                           wire:model="purchase_date">
                    @error('purchase_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Supplier -->
                <div class="col-md-6 mb-3">
                    <label for="product_supplier_id" class="form-label">Supplier <span class="text-danger">*</span></label>
                    <select class="form-select @error('product_supplier_id') is-invalid @enderror" 
                            id="product_supplier_id" 
                            wire:model="product_supplier_id">
                        <option value="">Select Supplier</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->company_name }}</option>
                        @endforeach
                    </select>
                    @error('product_supplier_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Warehouse -->
                <div class="col-md-6 mb-3">
                    <label for="product_warehouse_id" class="form-label">Warehouse <span class="text-danger">*</span></label>
                    <select class="form-select @error('product_warehouse_id') is-invalid @enderror" 
                            id="product_warehouse_id" 
                            wire:model="product_warehouse_id">
                        <option value="">Select Warehouse</option>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
                    @error('product_warehouse_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <!-- Product Items Section -->
            <div class="row">
                <div class="col-12">
                    <h6 class="mb-3">Product Items</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th width="40%">Product <span class="text-danger">*</span></th>
                                    <th width="15%">Quantity <span class="text-danger">*</span></th>
                                    <th width="20%">Unit Price <span class="text-danger">*</span></th>
                                    <th width="20%">Subtotal</th>
                                    <th width="5%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productItems as $index => $item)
                                <tr>
                                    <td>
                                        <select class="form-select form-select-sm @error('productItems.' . $index . '.product_id') is-invalid @enderror" 
                                                wire:model.live="productItems.{{ $index }}.product_id">
                                            <option value="">Select Product</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('productItems.' . $index . '.product_id') 
                                            <div class="invalid-feedback">{{ $message }}</div> 
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="number" 
                                               class="form-control form-control-sm @error('productItems.' . $index . '.quantity') is-invalid @enderror" 
                                               wire:model.live="productItems.{{ $index }}.quantity" 
                                               min="1" 
                                               step="1">
                                        @error('productItems.' . $index . '.quantity') 
                                            <div class="invalid-feedback">{{ $message }}</div> 
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="number" 
                                               class="form-control form-control-sm @error('productItems.' . $index . '.unit_price') is-invalid @enderror" 
                                               wire:model.live="productItems.{{ $index }}.unit_price" 
                                               min="0" 
                                               step="0.01">
                                        @error('productItems.' . $index . '.unit_price') 
                                            <div class="invalid-feedback">{{ $message }}</div> 
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" 
                                               class="form-control form-control-sm" 
                                               value="{{ number_format($item['subtotal'] ?? 0, 2) }}" 
                                               readonly>
                                    </td>
                                    <td>
                                        @if(count($productItems) > 1)
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    wire:click="removeProductItem({{ $index }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5">
                                        <button type="button" 
                                                class="btn btn-sm theme-unfilled-btn" 
                                                wire:click="addProductItem">
                                            <i class="fas fa-plus"></i> Add Item
                                        </button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @error('productItems') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
            </div>

            <!-- Additional Details -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="discount" class="form-label">Discount (%)</label>
                    <input type="number" 
                           class="form-control @error('discount') is-invalid @enderror" 
                           id="discount" 
                           wire:model="discount" 
                           min="0" 
                           max="100" 
                           step="0.01">
                    @error('discount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="tax" class="form-label">Tax (%)</label>
                    <input type="number" 
                           class="form-control @error('tax') is-invalid @enderror" 
                           id="tax" 
                           wire:model="tax" 
                           min="0" 
                           max="100" 
                           step="0.01">
                    @error('tax') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="payment_status" class="form-label">Payment Status <span class="text-danger">*</span></label>
                    <select class="form-select @error('payment_status') is-invalid @enderror" 
                            id="payment_status" 
                            wire:model="payment_status">
                        <option value="pending">Pending</option>
                        <option value="partial_paid">Partial Paid</option>
                        <option value="fully_paid">Fully Paid</option>
                    </select>
                    @error('payment_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="refund_status" class="form-label">Refund Status <span class="text-danger">*</span></label>
                    <select class="form-select @error('refund_status') is-invalid @enderror" 
                            id="refund_status" 
                            wire:model="refund_status">
                        <option value="not_refunded">Not Refunded</option>
                        <option value="refunded">Refunded</option>
                    </select>
                    @error('refund_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12 mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" 
                              rows="3" 
                              wire:model="description"
                              placeholder="Additional notes or description..."></textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </form>
    </x-modal>

    <!-- Payment Modal -->
    @if($showPaymentModal && $selectedPurchase)
    <div class="modal fade show" id="paymentModal" tabindex="-1" style="display: block;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Payment Management - {{ $selectedPurchase->reference_no }}</h5>
                    <button type="button" class="btn-close" wire:click="closePaymentModal"></button>
                </div>
                <div class="modal-body">
                    <!-- Purchase Summary -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Supplier:</strong> {{ $selectedPurchase->supplier->company_name ?? 'N/A' }}<br>
                                    <strong>Purchase Date:</strong> {{ $selectedPurchase->purchase_date }}<br>
                                    <strong>Warehouse:</strong> {{ $selectedPurchase->warehouse->name ?? 'N/A' }}
                                </div>
                                <div class="col-md-6">
                                    @php
                                        $subtotal = $selectedPurchase->purchasedItems->sum(function($item) {
                                            return $item->quantity * $item->unit_price;
                                        });
                                        $discountAmount = $subtotal * ($selectedPurchase->discount / 100);
                                        $taxAmount = ($subtotal - $discountAmount) * ($selectedPurchase->tax / 100);
                                        $grandTotal = $subtotal - $discountAmount + $taxAmount;
                                        $totalPaid = collect($payments)->sum('paid_amount');
                                        $balance = $grandTotal - $totalPaid;
                                    @endphp
                                    <strong>Grand Total:</strong> PKR {{ number_format($grandTotal, 2) }}<br>
                                    <strong>Total Paid:</strong> PKR {{ number_format($totalPaid, 2) }}<br>
                                    <strong>Balance:</strong> PKR {{ number_format($balance, 2) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add Payment Form -->
                    @if($showAddPaymentForm)
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0">Add New Payment</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Reference No <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('paymentReferenceNo') is-invalid @enderror" wire:model="paymentReferenceNo">
                                    @error('paymentReferenceNo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Amount <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('paymentAmount') is-invalid @enderror" wire:model="paymentAmount" min="0.01" step="0.01">
                                    @error('paymentAmount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                                    <select class="form-select @error('paymentMethod') is-invalid @enderror" wire:model="paymentMethod">
                                        <option value="cash">Cash</option>
                                        <option value="cheque">Cheque</option>
                                        <option value="credit_card">Credit Card</option>
                                        <option value="other">Other</option>
                                    </select>
                                    @error('paymentMethod') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Payment Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('paymentDate') is-invalid @enderror" wire:model="paymentDate">
                                    @error('paymentDate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" wire:model="paymentDescription" rows="2"></textarea>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn theme-filled-btn" wire:click="addPayment">Add Payment</button>
                                <button type="button" class="btn theme-unfilled-btn" wire:click="hideAddPaymentForm">Cancel</button>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Payments List -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Payment History</h6>
                        @if(!$showAddPaymentForm)
                            <button type="button" class="btn btn-sm theme-filled-btn" wire:click="openPaymentForm">
                                <i class="fas fa-plus"></i> Add Payment
                            </button>
                        @endif
                    </div>

                    @if(count($payments) > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Reference No</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $payment)
                                <tr>
                                    <td>{{ $payment->reference_no }}</td>
                                    <td>PKR {{ number_format($payment->paid_amount, 2) }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                    <td>{{ $payment->payment_date }}</td>
                                    <td>{{ $payment->description ?? '-' }}</td>
                                    <td>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger" 
                                                onclick="confirm('Are you sure you want to delete this payment?') || event.stopImmediatePropagation()" 
                                                wire:click="deletePayment({{ $payment->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-credit-card fa-3x mb-3"></i>
                        <p>No payments recorded yet.</p>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn theme-unfilled-btn" wire:click="closePaymentModal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
</x-sections.default>
