@push('styles')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endpush

<div class="row">
    <div class="col-xl-12 mb-3">
        <div class="card bgs-card h-100">
            <div class="card-header d-flex justify-content-between mb-3">
                <h6 class="card-title"><i class="fas fa-edit me-2"></i>Edit Product</h6>
                <a wire:navigate href='{{ route('products') }}' class="btn btn-sm btn-primary btn-rounded-sm">
                    <i class="fas fa-arrow-left me-1"></i>Go Back
                </a>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="updateProduct">
                    <!-- Basic Information Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-muted border-bottom pb-2 mb-3">
                                <i class="fas fa-info-circle me-2"></i>Basic Information
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="product_category_id" class="form-label fw-semibold">
                                    <i class="fas fa-tags me-1"></i>Category <span class="text-danger">*</span>
                                </label>
                                <div wire:ignore>
                                    <select id="product_category_id" class="form-select select2-category @error('product_category_id') is-invalid @enderror">
                                        <option value="">Choose a category...</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $product_category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('product_category_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
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
                            <div class="mb-3">
                                <label for="description" class="form-label fw-semibold">
                                    <i class="fas fa-file-alt me-1"></i>Description
                                </label>
                                <div wire:ignore>
                                    <textarea id="description" class="form-control ckeditor @error('description') is-invalid @enderror" rows="4" placeholder="Enter detailed product description...">{{ $description }}</textarea>
                                </div>
                                @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>                        </div>
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
                                <label for="newImage" class="form-label fw-semibold">
                                    <i class="fas fa-camera me-1"></i>Product Image
                                </label>
                                <input type="file" id="newImage" wire:model="newImage" class="form-control @error('newImage') is-invalid @enderror" accept="image/*">
                                @error('newImage') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                
                                @if ($newImage)
                                    <div class="mt-2">
                                        <small class="text-muted">New Image Preview:</small>
                                        <div class="border rounded p-2 mt-1">
                                            <img src="{{ $newImage->temporaryUrl() }}" alt="New Preview" class="img-thumbnail" style="max-height: 100px;">
                                        </div>
                                    </div>
                                @elseif($image)
                                    <div class="mt-2">
                                        <small class="text-muted">Current Image:</small>
                                        <div class="border rounded p-2 mt-1">
                                            <img src="{{ asset('storage/' . $image) }}" alt="Current Image" class="img-thumbnail" style="max-height: 100px;">
                                        </div>
                                    </div>
                                @else
                                    <div class="mt-2">
                                        <small class="text-muted">No image uploaded</small>
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
                                    <input type="text" id="barcode" wire:model.lazy="barcode" class="form-control @error('barcode') is-invalid @enderror" placeholder="Enter product barcode" value="{{ $barcode }}">
                                    <button type="button" class="btn btn-outline-secondary" onclick="generateBarcode()">
                                        <i class="fas fa-random"></i> Generate
                                    </button>
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
                                    <i class="fas fa-boxes me-1"></i>Current Quantity <span class="text-danger">*</span>
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
                                    <option value="1">
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
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Update Product
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
        let editorInstance = null;
        let isEditorInitialized = false;
        let isSelect2Initialized = false;
          function initializeCKEditor() {
            // Wait for CKEditor to be available
            if (typeof ClassicEditor === 'undefined') {
                console.log('CKEditor not available yet, retrying...');
                setTimeout(initializeCKEditor, 100);
                return;
            }
            
            // Destroy existing editor if any
            if (editorInstance) {
                editorInstance.destroy().catch(console.error);
                editorInstance = null;
                isEditorInitialized = false;
            }
            
            const editorElement = document.querySelector('#description');
            if (!editorElement || isEditorInitialized) {
                return;
            }
            
            // Get initial content from Livewire
            const initialContent = @js($description ?? '');
            
            ClassicEditor
                .create(editorElement, {
                    toolbar: [
                        'heading', '|',
                        'bold', 'italic', 'underline', '|',
                        'bulletedList', 'numberedList', '|',
                        'indent', 'outdent', '|',
                        'blockQuote', 'insertTable', '|',
                        'undo', 'redo'
                    ],
                    placeholder: 'Enter detailed product description...'
                })
                .then(editor => {
                    editorInstance = editor;
                    isEditorInitialized = true;
                    
                    // Set initial content from Livewire
                    if (initialContent) {
                        editor.setData(initialContent);
                    }
                    
                    // Sync CKEditor content with Livewire on change
                    editor.model.document.on('change:data', () => {
                        @this.set('description', editor.getData());
                    });
                    
                    console.log('CKEditor initialized successfully with content');
                })
                .catch(error => {
                    console.error('Error initializing CKEditor:', error);
                    isEditorInitialized = false;
                });
        }
          function initializeSelect2() {
            // Wait for Select2 to be available
            if (typeof jQuery === 'undefined' || typeof jQuery.fn.select2 === 'undefined') {
                console.log('Select2 not available yet, retrying...');
                setTimeout(initializeSelect2, 100);
                return;
            }
            
            const selectElement = $('.select2-category');
            
            // Destroy existing Select2 if any
            if (selectElement.hasClass('select2-hidden-accessible')) {
                selectElement.select2('destroy');
                isSelect2Initialized = false;
            }
            
            if (!selectElement.length || isSelect2Initialized) {
                return;
            }
            
            // Get initial value from Livewire
            const initialValue = @js($product_category_id ?? '');
            
            // Initialize Select2
            selectElement.select2({
                theme: 'bootstrap-5',
                placeholder: 'Choose a category...',
                allowClear: true,
                width: '100%'
            });
            
            // Set initial value if it exists
            if (initialValue) {
                selectElement.val(initialValue).trigger('change.select2');
            }
            
            isSelect2Initialized = true;
            
            // Handle Select2 change event
            let lastCategoryValue = selectElement.val();
            selectElement.off('change.livewire').on('change.livewire', function (e) {
                const data = $(this).val();
                
                // Only update if value actually changed
                if (data !== lastCategoryValue) {
                    lastCategoryValue = data;
                    
                    // Store CKEditor content before Livewire update
                    if (editorInstance) {
                        @this.set('description', editorInstance.getData());
                    }
                    
                    @this.set('product_category_id', data);
                }
            });
            
            console.log('Select2 initialized successfully with value:', initialValue);
        }
        
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
            // Initialize Select2
            initializeSelect2();
            
            // Initialize CKEditor with delay for DOM readiness
            setTimeout(() => {
                initializeCKEditor();
            }, 500);
            
            // Bind profit calculation to price inputs
            $('#buying_price, #selling_price').off('input.profit keyup.profit').on('input.profit keyup.profit', calculateProfitMargin);
            
            // Calculate profit margin on load
            calculateProfitMargin();
            
            // Form validation
            $('form').off('submit.validation').on('submit.validation', function(e) {
                let isValid = true;
                
                // Sync CKEditor content before submission
                if (editorInstance) {
                    @this.set('description', editorInstance.getData());
                }
                
                // Check required fields
                $('input[required], select[required]').each(function() {
                    if (!$(this).val()) {
                        $(this).addClass('is-invalid');
                        isValid = false;
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    if (typeof notyf !== 'undefined') {
                        notyf.error('Please fill in all required fields.');
                    }
                    return false;
                }
                
                // Show loading state
                $(this).find('button[type="submit"]').html('<i class="fas fa-spinner fa-spin me-1"></i>Updating...');
            });
            
            // Real-time validation
            $('input, select').off('blur.validation').on('blur.validation', function() {
                if ($(this).attr('required') && !$(this).val()) {
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
        }
          // Initialize on document ready and wire navigation
        $(document).ready(function() {
            setTimeout(() => {
                initializeForm();
            }, 500);
        });
        
        // Handle wire:navigate navigation
        document.addEventListener('livewire:navigated', () => {
            // Re-initialize form components after navigation
            setTimeout(() => {
                initializeForm();
            }, 600);
        });
        
        // Livewire hooks for proper re-initialization
        document.addEventListener('livewire:initialized', () => {
            // Initialize form on first load
            setTimeout(() => {
                initializeForm();
            }, 800);
            
            Livewire.hook('morph.updated', ({ el, component }) => {
                // Re-initialize only if this component was updated
                if (component.fingerprint.name === 'inventory.product.edit') {
                    setTimeout(() => {
                        initializeForm();
                    }, 300);
                }
            });
            
            Livewire.hook('morph.removing', ({ el, component }) => {
                // Store content before DOM morphing
                if (component.fingerprint.name === 'inventory.product.edit' && editorInstance) {
                    @this.set('description', editorInstance.getData());
                }
            });
        });
          // Cleanup on page unload
        window.addEventListener('beforeunload', function() {
            if (editorInstance) {
                editorInstance.destroy().catch(console.error);
            }
        });
        
        // Generate random barcode
        function generateBarcode() {
            const timestamp = Date.now().toString();
            const random = Math.random().toString(36).substring(2, 8).toUpperCase();
            const barcode = timestamp.substring(-8) + random;
            document.getElementById('barcode').value = barcode;
            document.getElementById('barcode').dispatchEvent(new Event('input'));
        }
    </script>
@endpush