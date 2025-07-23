<div class="row">
    <div class="col-xl-12 mb-3">
        <div class="card bgs-card h-100">
            <div class="card-header d-flex justify-content-between mb-3">
                <h6 class="card-title"><i class="fas fa-plus-circle me-2"></i>Add New Product</h6>
                <a wire:navigate href='{{ route('products') }}' class="btn btn-sm btn-primary btn-rounded-sm">
                    <i class="fas fa-arrow-left me-1"></i>Go Back
                </a>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="createProduct">
                    <!-- Basic Information Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-muted border-bottom pb-2 mb-3">
                                <i class="fas fa-info-circle me-2"></i>Basic Information
                            </h6>
                        </div>
                        <div class="col-md-6">
                            {{-- <div class="mb-3">
                                <label for="product_category_id" class="form-label fw-semibold">
                                    <i class="fas fa-tags me-1"></i>Category <span class="text-danger">*</span>
                                </label>
                                <div>
                                    <select id="product_category_id" class="form-select select2-category @error('product_category_id') is-invalid @enderror">
                                        <option value="">Choose a category...</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $product_category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('product_category_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div> --}}
                            <x-form.select2
                                name="product_category_id"
                                label="Category"
                                icon="fas fa-tags"
                                :options="$categories->pluck('name', 'id')->toArray()"
                                placeholder="Choose a category..."
                                required
                                model="product_category_id"
                            />
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">
                                    <i class="fas fa-box me-1"></i>Product Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="name" wire:model.lazy="name" class="form-control @error('name') is-invalid @enderror" placeholder="Enter product name">
                                @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Description Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-muted border-bottom pb-2 mb-3">
                                <i class="fas fa-align-left me-2"></i>Product Description
                            </h6>
                            {{-- <div class="mb-3">
                                <label for="description" class="form-label fw-semibold">
                                    <i class="fas fa-file-alt me-1"></i>Description
                                </label>
                                <div wire:ignore>
                                    <textarea id="description" class="form-control ckeditor @error('description') is-invalid @enderror" rows="4" placeholder="Enter detailed product description..."></textarea>
                                </div>
                                @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>--}}
                            <x-form.ckeditor
                                name="description"
                                label="Description"
                                icon="fas fa-file-alt"
                                placeholder="Enter detailed product description..."
                                model="description"
                            />
                        </div>
                    </div>
                    
                    <!-- Image & Barcode Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-muted border-bottom pb-2 mb-3">
                                <i class="fas fa-image me-2"></i>Product Image & Barcode
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="image" class="form-label fw-semibold">
                                    <i class="fas fa-camera me-1"></i>Product Image
                                </label>
                                <input type="file" id="image" wire:model="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                                @error('image') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                @if ($image)
                                    <div class="mt-2">
                                        <small class="text-muted">Preview:</small>
                                        <div class="border rounded p-2 mt-1">
                                            <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="img-thumbnail" style="max-height: 100px;">
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="barcode" class="form-label fw-semibold">
                                    <i class="fas fa-barcode me-1"></i>Barcode
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                    <input type="text" id="barcode" wire:model.lazy="barcode" class="form-control @error('barcode') is-invalid @enderror" placeholder="Enter product barcode">
                                </div>
                                @error('barcode') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                <small class="text-muted">Leave empty to auto-generate or enter custom barcode</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pricing & Inventory Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-muted border-bottom pb-2 mb-3">
                                <i class="fas fa-dollar-sign me-2"></i>Pricing & Inventory
                            </h6>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="buying_price" class="form-label fw-semibold">
                                    <i class="fas fa-shopping-cart me-1"></i>Buying Price <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">Rs.</span>
                                    <input type="number" step="0.01" id="buying_price" wire:model.lazy="buying_price" class="form-control @error('buying_price') is-invalid @enderror" placeholder="0.00">
                                </div>
                                @error('buying_price') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="selling_price" class="form-label fw-semibold">
                                    <i class="fas fa-tag me-1"></i>Selling Price <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">Rs.</span>
                                    <input type="number" step="0.01" id="selling_price" wire:model.lazy="selling_price" class="form-control @error('selling_price') is-invalid @enderror" placeholder="0.00">
                                </div>
                                @error('selling_price') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="quantity" class="form-label fw-semibold">
                                    <i class="fas fa-boxes me-1"></i>Initial Quantity <span class="text-danger">*</span>
                                </label>
                                <input type="number" id="quantity" wire:model.lazy="quantity" class="form-control @error('quantity') is-invalid @enderror" placeholder="0">
                                @error('quantity') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Status Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-muted border-bottom pb-2 mb-3">
                                <i class="fas fa-cogs me-2"></i>Status & Settings
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="is_active" class="form-label fw-semibold">
                                    <i class="fas fa-toggle-on me-1"></i>Status <span class="text-danger">*</span>
                                </label>
                                <select id="is_active" wire:model.lazy="is_active" class="form-select @error('is_active') is-invalid @enderror">
                                    <option value="1" selected>
                                        <i class="fas fa-check-circle"></i> Active
                                    </option>
                                    <option value="0">
                                        <i class="fas fa-times-circle"></i> Inactive
                                    </option>
                                </select>
                                @error('is_active') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-muted">
                                    <i class="fas fa-info-circle me-1"></i>Profit Margin
                                </label>
                                <div class="alert alert-info py-2 px-3" id="profit-margin-display">
                                    <small><i class="fas fa-calculator me-1"></i>Enter prices to see profit margin</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a wire:navigate href='{{ route('products') }}' class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-1"></i>Create Product
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        .select2-container .select2-selection--single {
            height: 38px !important;
            border: 1px solid #ced4da !important;
        }
        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            line-height: 36px !important;
        }
        .bgs-card {
            border: none;
            box-shadow: none;
        }
        .profit-margin-positive {
            color: #198754;
            font-weight: 600;
        }
        .profit-margin-negative {
            color: #dc3545;
            font-weight: 600;
        }
        .form-label {
            color: #495057;
            margin-bottom: 0.5rem;
        }
        .ck-editor__editable {
            min-height: 120px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function calculateProfitMargin() {
            const buyingPrice = parseFloat($('#buying_price').val()) || 0;
            const sellingPrice = parseFloat($('#selling_price').val()) || 0;
            
            if (buyingPrice > 0 && sellingPrice > 0) {
                const profit = sellingPrice - buyingPrice;
                const margin = ((profit / buyingPrice) * 100).toFixed(2);
                
                let marginClass = profit >= 0 ? 'profit-margin-positive' : 'profit-margin-negative';
                let icon = profit >= 0 ? 'fa-arrow-up' : 'fa-arrow-down';
                
                $('#profit-margin-display').html(`
                    <small class="${marginClass}">
                        <i class="fas ${icon} me-1"></i>
                        Profit: Rs.${profit.toFixed(2)} (${margin}%)
                    </small>
                `);
            } else {
                $('#profit-margin-display').html(`
                    <small><i class="fas fa-calculator me-1"></i>Enter prices to see profit margin</small>
                `);
            }
        }
        
        function initializeForm() {
            
            // Bind profit calculation to price inputs
            $('#buying_price, #selling_price').off('input.profit keyup.profit').on('input.profit keyup.profit', calculateProfitMargin);
    
        }
          // Initialize on document ready and wire navigation
        $(document).ready(function() {
            initializeForm();
        });
        
    
    </script>
@endpush