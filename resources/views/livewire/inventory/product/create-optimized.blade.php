<div class="row">
    <div class="col-xl-12 mb-3">
        <div class="card bgs-card h-100">
            <div class="card-header d-flex justify-content-between mb-3">
                <h6 class="card-title">
                    <i class="fas fa-plus-circle me-2"></i>Add New Product
                </h6>
                <a wire:navigate href='{{ route('products') }}' class="btn btn-sm btn-primary btn-rounded-sm">
                    <i class="fas fa-arrow-left me-1"></i>Go Back
                </a>
            </div>
            
            <div class="card-body">
                <form wire:submit.prevent="createProduct">
                    <!-- Basic Information Section -->
                    <x-form.section title="Basic Information" icon="fas fa-info-circle">
                        <div class="row">
                            <div class="col-md-6">
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
                                <x-form.input
                                    name="name"
                                    label="Product Name"
                                    icon="fas fa-box"
                                    model="name"
                                    placeholder="Enter product name"
                                    required
                                />
                            </div>
                        </div>
                    </x-form.section>
                    
                    <!-- Description Section -->
                    <x-form.section title="Product Description" icon="fas fa-align-left">
                        <x-form.ckeditor
                            name="description"
                            label="Description"
                            icon="fas fa-file-alt"
                            placeholder="Enter detailed product description..."
                            model="description"
                        />
                    </x-form.section>
                    
                    <!-- Image & Barcode Section -->
                    <x-form.section title="Product Image & Barcode" icon="fas fa-image">
                        <div class="row">
                            <div class="col-md-6">
                                <x-form.file
                                    name="image"
                                    label="Product Image"
                                    icon="fas fa-camera"
                                    accept="image/*"
                                    model="image"
                                    :preview="$image"
                                />
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <x-form.label for="barcode">
                                        <i class="fas fa-barcode me-1"></i>Barcode
                                    </x-form.label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-barcode"></i>
                                        </span>
                                        <input 
                                            type="text" 
                                            id="barcode" 
                                            wire:model.live.debounce.500ms="barcode" 
                                            class="form-control @error('barcode') is-invalid @enderror" 
                                            placeholder="Enter product barcode"
                                        >
                                    </div>
                                    @error('barcode') 
                                        <div class="invalid-feedback">{{ $message }}</div> 
                                    @enderror
                                    <small class="text-muted">
                                        Leave empty to auto-generate or enter custom barcode
                                    </small>
                                </div>
                            </div>
                        </div>
                    </x-form.section>
                    
                    <!-- Pricing & Inventory Section -->
                    <x-form.section title="Pricing & Inventory" icon="fas fa-dollar-sign">
                        <div class="row">
                            <div class="col-md-4">
                                <x-form.price-input
                                    name="buying_price"
                                    label="Buying Price"
                                    icon="fas fa-shopping-cart"
                                    model="buying_price"
                                    required
                                />
                            </div>
                            <div class="col-md-4">
                                <x-form.price-input
                                    name="selling_price"
                                    label="Selling Price"
                                    icon="fas fa-tag"
                                    model="selling_price"
                                    required
                                />
                            </div>
                            <div class="col-md-4">
                                <x-form.input
                                    type="number"
                                    name="quantity"
                                    label="Initial Quantity"
                                    icon="fas fa-boxes"
                                    model="quantity"
                                    placeholder="0"
                                    required
                                />
                            </div>
                        </div>
                    </x-form.section>
                    
                    <!-- Status Section -->
                    <x-form.section title="Status & Settings" icon="fas fa-cogs">
                        <div class="row">
                            <div class="col-md-6">
                                <x-form.select
                                    name="is_active"
                                    label="Status"
                                    icon="fas fa-toggle-on"
                                    :options="[1 => 'Active', 0 => 'Inactive']"
                                    model="is_active"
                                    required
                                />
                            </div>
                            <div class="col-md-6">
                                <x-form.profit-margin 
                                    :buying-price="$buying_price ?? 0" 
                                    :selling-price="$selling_price ?? 0" 
                                />
                            </div>
                        </div>
                    </x-form.section>
                    
                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a wire:navigate href='{{ route('products') }}' class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="createProduct">
                                        <i class="fas fa-save me-1"></i>Create Product
                                    </span>
                                    <span wire:loading wire:target="createProduct">
                                        <i class="fas fa-spinner fa-spin me-1"></i>Creating...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
