<div>
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-0">
                <i class="fas fa-edit me-2"></i>Edit Purchase
            </h4>
            <p class="text-muted mb-0">Update purchase information and items</p>
        </div>
        <a href="{{ route('purchases') }}" wire:navigate class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Purchases
        </a>
    </div>

    <form wire:submit.prevent="updatePurchase">
        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Purchase Information -->
                <div class="card bgs-card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>Purchase Information
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
                                <label for="purchase_date" class="form-label fw-semibold">
                                    <i class="fas fa-calendar me-1"></i>Purchase Date *
                                </label>
                                <input type="date" id="purchase_date" wire:model="purchase_date"
                                    class="form-control @error('purchase_date') is-invalid @enderror">
                                @error('purchase_date')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="product_supplier_id" class="form-label fw-semibold">
                                    <i class="fas fa-building me-1"></i>Supplier *
                                </label>
                                <div wire:ignore>
                                    <select id="product_supplier_id"
                                        class="form-select select2-supplier @error('product_supplier_id') is-invalid @enderror">
                                        <option value="">Select Supplier</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}"
                                                {{ $product_supplier_id == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->company_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('product_supplier_id')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="product_warehouse_id" class="form-label fw-semibold">
                                    <i class="fas fa-warehouse me-1"></i>Warehouse *
                                </label>
                                <div wire:ignore>
                                    <select id="product_warehouse_id"
                                        class="form-select select2-warehouse @error('product_warehouse_id') is-invalid @enderror">
                                        <option value="">Select Warehouse</option>
                                        @foreach ($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}"
                                                {{ $product_warehouse_id == $warehouse->id ? 'selected' : '' }}>
                                                {{ $warehouse->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('product_warehouse_id')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="description" class="form-label fw-semibold">
                                    <i class="fas fa-comment me-1"></i>Description
                                </label>
                                <textarea id="description" wire:model="description" rows="3"
                                    class="form-control @error('description') is-invalid @enderror" placeholder="Enter purchase description (optional)"></textarea>
                                @error('description')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div> <!-- Product Items -->
                <div class="card bgs-card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-box me-2"></i>Purchase Items
                        </h6>
                        <button type="button" wire:click="addProductItem" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i>Add Item
                        </button>
                    </div>
                    <div class="card-body">
                        @error('productItems')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th width="25%">Category</th>
                                        <th width="30%">Product</th>
                                        <th width="15%">Quantity</th>
                                        <th width="15%">Unit Price</th>
                                        <th width="10%">Subtotal</th>
                                        <th width="5%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($productItems as $index => $item)
                                        <tr>
                                            <td>
                                                <select wire:model.live="productItems.{{ $index }}.category_id"
                                                    class="form-select select2-category-{{ $index }} @error('productItems.' . $index . '.category_id') is-invalid @enderror">
                                                    <option value="">Select Category</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}"
                                                            {{ $item['category_id'] == $category->id ? 'selected' : '' }}>
                                                            {{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('productItems.' . $index . '.category_id')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </td>
                                            <td>
                                                <select wire:model.live="productItems.{{ $index }}.product_id"
                                                    class="form-select select2-product-{{ $index }} @error('productItems.' . $index . '.product_id') is-invalid @enderror"
                                                    @if (empty($item['category_id'])) disabled @endif>
                                                    <option value="">
                                                        @if (empty($item['category_id']))
                                                            Select Category First
                                                        @else
                                                            Select Product
                                                        @endif
                                                    </option>
                                                    @if (!empty($item['category_id']))
                                                        @foreach ($products->where('product_category_id', $item['category_id']) as $product)
                                                            <option value="{{ $product->id }}"
                                                                {{ $item['product_id'] == $product->id ? 'selected' : '' }}>
                                                                {{ $product->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('productItems.' . $index . '.product_id')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" min="0.01"
                                                    wire:model.live="productItems.{{ $index }}.quantity"
                                                    class="form-control @error('productItems.' . $index . '.quantity') is-invalid @enderror"
                                                    placeholder="0.00">
                                                @error('productItems.' . $index . '.quantity')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" min="0"
                                                    wire:model.live="productItems.{{ $index }}.unit_price"
                                                    class="form-control @error('productItems.' . $index . '.unit_price') is-invalid @enderror"
                                                    placeholder="0.00">
                                                @error('productItems.' . $index . '.unit_price')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="text"
                                                    value="PKR {{ number_format($item['subtotal'] ?? 0, 2) }}"
                                                    class="form-control" readonly>
                                            </td>
                                            <td>
                                                <button type="button"
                                                    wire:click="removeProductItem({{ $index }})"
                                                    class="btn btn-sm btn-outline-danger"
                                                    @if (count($productItems) <= 1) disabled @endif>
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Payment & Tax Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-credit-card me-2"></i>Payment & Tax
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="payment_status" class="form-label fw-semibold">Payment Status</label>
                                <select id="payment_status" wire:model="payment_status" class="form-select">
                                    <option value="pending">Pending</option>
                                    <option value="partial_paid">Partial Paid</option>
                                    <option value="fully_paid">Fully Paid</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="refund_status" class="form-label fw-semibold">Refund Status</label>
                                <select id="refund_status" wire:model="refund_status" class="form-select">
                                    <option value="not_refunded">Not Refunded</option>
                                    <option value="refunded">Refunded</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="discount" class="form-label fw-semibold">Discount (%)</label>
                                <input type="number" step="0.01" min="0" max="100" id="discount"
                                    wire:model.live="discount" class="form-control" placeholder="0.00">
                            </div>
                            <div class="col-12">
                                <label for="tax" class="form-label fw-semibold">Tax (%)</label>
                                <input type="number" step="0.01" min="0" max="100" id="tax"
                                    wire:model.live="tax" class="form-control" placeholder="0.00">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Purchase Summary -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-calculator me-2"></i>Purchase Summary
                        </h6>
                    </div>
                    <div class="card-body">
                        @php
                            $subtotal = collect($productItems)->sum('subtotal');
                            $discountAmount = $subtotal * ($discount / 100);
                            $taxAmount = ($subtotal - $discountAmount) * ($tax / 100);
                            $grandTotal = $subtotal - $discountAmount + $taxAmount;
                        @endphp

                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span class="fw-medium">PKR {{ number_format($subtotal, 2) }}</span>
                        </div>

                        @if ($discount > 0)
                            <div class="d-flex justify-content-between mb-2 text-success">
                                <span>Discount ({{ $discount }}%):</span>
                                <span>- PKR {{ number_format($discountAmount, 2) }}</span>
                            </div>
                        @endif

                        @if ($tax > 0)
                            <div class="d-flex justify-content-between mb-2 text-info">
                                <span>Tax ({{ $tax }}%):</span>
                                <span>+ PKR {{ number_format($taxAmount, 2) }}</span>
                            </div>
                        @endif

                        <hr>
                        <div class="d-flex justify-content-between mb-0">
                            <span class="fw-bold">Grand Total:</span>
                            <span class="fw-bold text-primary fs-5">PKR {{ number_format($grandTotal, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i>Update Purchase
                            </button>
                            <a href="{{ route('purchases') }}" wire:navigate class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <style>
        .select2-container .select2-selection--single {
            height: 38px !important;
            border: 1px solid #ced4da !important;
        }

        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            line-height: 36px !important;
            padding-left: 12px !important;
        }

        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
            top: 1px !important;
            right: 3px !important;
        }

        .select2-dropdown {
            border: 1px solid #ced4da !important;
        }

        .select2-container--open .select2-selection--single {
            border-color: #86b7fe !important;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
        }

        /* Validation error styling for Select2 */
        .select2-container--bootstrap-5.is-invalid .select2-selection--single {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25) !important;
        }

        .is-invalid+.select2-container--bootstrap-5 .select2-selection--single {
            border-color: #dc3545 !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let isSelect2Initialized = false;

            function initializeSelect2() {
                if (isSelect2Initialized) return;

                // Initialize Select2 for suppliers
                $('#product_supplier_id').select2({
                    theme: 'bootstrap-5',
                    placeholder: 'Select Supplier',
                    allowClear: true,
                    width: '100%'
                }).on('change', function() {
                    @this.set('product_supplier_id', $(this).val());

                    // Remove validation error styling when value is selected
                    const value = $(this).val();
                    if (value) {
                        $(this).next('.select2-container').removeClass('is-invalid');
                        $(this).removeClass('is-invalid');
                    }
                });
                // Initialize Select2 for warehouses
                $('#product_warehouse_id').select2({
                    theme: 'bootstrap-5',
                    placeholder: 'Select Warehouse',
                    allowClear: true,
                    width: '100%'
                }).on('change', function() {
                    @this.set('product_warehouse_id', $(this).val());

                    // Remove validation error styling when value is selected
                    const value = $(this).val();
                    if (value) {
                        $(this).next('.select2-container').removeClass('is-invalid');
                        $(this).removeClass('is-invalid');
                    }
                });
                // Initialize Select2 for products (dynamic)
                initializeProductSelect2();

                // Apply validation styling if there are errors
                applyValidationStyling();

                isSelect2Initialized = true;
            }

            function applyValidationStyling() {
                // Apply validation styling for supplier field
                if ($('#product_supplier_id').hasClass('is-invalid')) {
                    $('#product_supplier_id').next('.select2-container').addClass('is-invalid');
                }

                // Apply validation styling for warehouse field
                if ($('#product_warehouse_id').hasClass('is-invalid')) {
                    $('#product_warehouse_id').next('.select2-container').addClass('is-invalid');
                }
            }

            function initializeProductSelect2() {
                // Initialize Select2 for category selects
                $('[class*="select2-category-"]').each(function() {
                    const $this = $(this);
                    const index = $this.attr('wire:model').match(/\d+/)[0];

                    if (!$this.hasClass('select2-hidden-accessible')) {
                        $this.select2({
                            theme: 'bootstrap-5',
                            placeholder: 'Select Category',
                            allowClear: true,
                            width: '100%'
                        }).on('change', function() {
                            @this.set('productItems.' + index + '.category_id', $(this).val());
                        });
                    }
                });

                // Initialize Select2 for product selects
                $('[class*="select2-product-"]').each(function() {
                    const $this = $(this);
                    const index = $this.attr('wire:model').match(/\d+/)[0];

                    if (!$this.hasClass('select2-hidden-accessible')) {
                        $this.select2({
                            theme: 'bootstrap-5',
                            placeholder: 'Select Product',
                            allowClear: true,
                            width: '100%'
                        }).on('change', function() {
                            @this.set('productItems.' + index + '.product_id', $(this).val());
                        });
                    }
                });
            }

            function destroySelect2() {
                // Destroy main selects
                if ($('#product_supplier_id').hasClass('select2-hidden-accessible')) {
                    $('#product_supplier_id').select2('destroy');
                }
                if ($('#product_warehouse_id').hasClass('select2-hidden-accessible')) {
                    $('#product_warehouse_id').select2('destroy');
                }

                // Destroy product item Select2 instances
                $('[class*="select2-category-"], [class*="select2-product-"]').each(function() {
                    if ($(this).hasClass('select2-hidden-accessible')) {
                        $(this).select2('destroy');
                    }
                });

                isSelect2Initialized = false;
            }

            // Initialize on page load
            initializeSelect2();
            // Re-initialize after Livewire updates
            Livewire.hook('morph.updated', ({
                el,
                component
            }) => {
                setTimeout(() => {
                    destroySelect2();
                    initializeSelect2();
                    applyValidationStyling();
                }, 100);
            });

            // Handle dynamic product row additions
            document.addEventListener('livewire:navigated', function() {
                setTimeout(() => {
                    destroySelect2();
                    initializeSelect2();
                }, 100);
            });

            // Listen for Livewire updates that add product items
            window.addEventListener('livewire:load', function() {
                Livewire.on('productItemAdded', function() {
                    setTimeout(() => {
                        initializeProductSelect2();
                    }, 100);
                });
            });
        });
    </script>
</div>
