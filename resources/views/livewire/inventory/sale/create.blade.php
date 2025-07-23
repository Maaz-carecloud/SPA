<div>
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-0">
                <i class="fas fa-plus-circle me-2"></i>Add New Sale
            </h4>
            <p class="text-muted mb-0">Create a new product sale entry</p>
        </div>
        <a href="{{ route('sales') }}" wire:navigate class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Sales
        </a>
    </div>

    <form wire:submit.prevent="createSale">
        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Sale Information -->
                <div class="card bgs-card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>Sale Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="reference_no" class="form-label fw-semibold">
                                    <i class="fas fa-hashtag me-1"></i>Reference Number *
                                </label>
                                <input type="text" id="reference_no" wire:model="reference_no"
                                    class="form-control @error('reference_no') is-invalid @enderror"
                                    placeholder="Enter reference number">
                                @error('reference_no')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="sale_date" class="form-label fw-semibold">
                                    <i class="fas fa-calendar me-1"></i>Sale Date *
                                </label>
                                <input type="date" id="sale_date" wire:model="sale_date"
                                    class="form-control @error('sale_date') is-invalid @enderror">
                                @error('sale_date')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="user_id" class="form-label fw-semibold">
                                    <i class="fas fa-user me-1"></i>Customer *
                                </label>
                                <select wire:model.live="user_id" id="user_id"
                                    class="form-select @error('user_id') is-invalid @enderror">
                                    <option value="">Choose customer</option>
                                    @foreach ($allUsers as $user)
                                        <option value="{{ $user['id'] }}"
                                            {{ $user_id == $user['id'] ? 'selected' : '' }}>
                                            {{ $user['name'] }} ({{ $user['type'] }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="sale_slip" class="form-label fw-semibold">
                                    <i class="fas fa-file me-1"></i>Sale Slip
                                </label>
                                <input type="file" id="sale_slip" wire:model="sale_slip"
                                    class="form-control @error('sale_slip') is-invalid @enderror"
                                    accept=".pdf,.jpg,.jpeg,.png">
                                @error('sale_slip')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="description" class="form-label fw-semibold">
                                    <i class="fas fa-comment me-1"></i>Description
                                </label>
                                <textarea id="description" wire:model="description" rows="3"
                                    class="form-control @error('description') is-invalid @enderror" placeholder="Enter sale description (optional)"></textarea>
                                @error('description')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Items -->
                <div class="card bgs-card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-box me-2"></i>Sale Items
                        </h6>
                        <button type="button" wire:click="addProductItem" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i>Add Item
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- Product Items List -->
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th width="35%">Product</th>
                                        <th width="15%">Quantity</th>
                                        <th width="20%">Unit Price</th>
                                        <th width="20%">Subtotal</th>
                                        <th width="10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($productItems as $index => $item)
                                        <tr wire:key="item-{{ $index }}">
                                            <td>
                                                <select wire:model.live="productItems.{{ $index }}.product_id"
                                                    class="form-select @error('productItems.' . $index . '.product_id') is-invalid @enderror">
                                                    <option value="">Select Product</option>
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->id }}"
                                                            {{ ($item['product_id'] ?? '') == $product->id ? 'selected' : '' }}>
                                                            {{ $product->name }} - PKR
                                                            {{ $product->barcode ? $product->barcode : 'N/A' }} -
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('productItems.' . $index . '.product_id')
                                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="number"
                                                    wire:model.live="productItems.{{ $index }}.quantity"
                                                    class="form-control @error('productItems.' . $index . '.quantity') is-invalid @enderror"
                                                    step="0.01" min="0.01" placeholder="Enter quantity"
                                                    value="{{ $item['quantity'] ?? '' }}">
                                                @error('productItems.' . $index . '.quantity')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="number"
                                                    wire:model.live="productItems.{{ $index }}.unit_price"
                                                    class="form-control @error('productItems.' . $index . '.unit_price') is-invalid @enderror"
                                                    step="0.01" min="0" placeholder="Price"
                                                    value="{{ $item['unit_price'] ?? 0 }}">
                                                @error('productItems.' . $index . '.unit_price')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="text"
                                                    value="{{ number_format($item['subtotal'] ?? 0, 2) }}"
                                                    class="form-control" readonly>
                                            </td>
                                            <td>
                                                @if (count($productItems) > 1)
                                                    <button type="button"
                                                        wire:click="removeProductItem({{ $index }})"
                                                        class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @error('productItems')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Sale Summary -->
                <div class="card bgs-card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-calculator me-2"></i>Sale Summary
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label for="discount" class="form-label">Discount (%)</label>
                                <input type="number" id="discount" wire:model.live="discount"
                                    class="form-control @error('discount') is-invalid @enderror" step="0.01"
                                    min="0" max="100" placeholder="0.00">
                                @error('discount')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label for="tax" class="form-label">Tax (%)</label>
                                <input type="number" id="tax" wire:model.live="tax"
                                    class="form-control @error('tax') is-invalid @enderror" step="0.01"
                                    min="0" placeholder="0.00">
                                @error('tax')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="border-top pt-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span class="fw-semibold">PKR {{ number_format($this->subtotal, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Discount:</span>
                                <span class="text-success">- PKR {{ number_format($this->discountAmount, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax:</span>
                                <span class="text-warning">+ PKR {{ number_format($this->taxAmount, 2) }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span class="h6">Grand Total:</span>
                                <span class="h6 text-primary">PKR {{ number_format($this->grandTotal, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sale Options -->
                <div class="card bgs-card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-cog me-2"></i>Sale Options
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="payment_status" class="form-label">Payment Status *</label>
                            <select id="payment_status" wire:model="payment_status"
                                class="form-select @error('payment_status') is-invalid @enderror">
                                <option value="select_payment_status">Select Payment Status</option>
                                <option value="due">Due</option>
                                <option value="partial">Partial</option>
                                <option value="paid">Paid</option>
                            </select>
                            @error('payment_status')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="refund_status" class="form-label">Refund Status *</label>
                            <select id="refund_status" wire:model="refund_status"
                                class="form-select @error('refund_status') is-invalid @enderror">
                                <option value="not_refunded">Not Refunded</option>
                                <option value="refunded">Refunded</option>
                            </select>
                            @error('refund_status')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card bgs-card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i>Create Sale
                            </button>
                            <a href="{{ route('sales') }}" wire:navigate class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle notifications
            window.addEventListener('success', e => {
                if (typeof notyf !== 'undefined') {
                    notyf.success(e.detail.message);
                }
            });

            window.addEventListener('error', e => {
                if (typeof notyf !== 'undefined') {
                    notyf.error(e.detail.message);
                }
            });
        });
    </script>
@endpush
