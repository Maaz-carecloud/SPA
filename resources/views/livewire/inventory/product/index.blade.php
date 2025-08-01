<x-sections.default>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Products</h3>
        <button type="button" class="btn theme-filled-btn" data-bs-toggle="modal" data-bs-target="#createModal">
            + Create
        </button>
    </div>

    @php
        $columns = ['#', 'Product Name', 'Barcode', 'Category', 'Buy Price', 'Sell Price', 'Stock', 'Status', 'Date Added', 'Action'];
        $ajaxUrl = route('datatable.products');
    @endphp
    <livewire:data-table :columns="$columns" table-id="productsTable" :ajax-url="$ajaxUrl" :key="microtime(true)" />

    <x-modal id="createModal" :title="$modalTitle" :action="$modalAction" :is_edit="$is_edit" :is_not_crud="false">
        <form>
            <div class="row">
                <div class="col-md-6">
                    <x-form.input id="name" type="text" name="name" label="Product Name" 
                        wire:model="name" placeholder="Enter product name" :error="$errors->first('name')" />
                </div>
                <div class="col-md-6">
                    <x-form.input id="barcode" type="text" name="barcode" label="Barcode" 
                        wire:model="barcode" placeholder="Enter barcode (optional)" :error="$errors->first('barcode')" />
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <x-form.select2 
                        id="product_category_id" 
                        name="product_category_id" 
                        label="Category" 
                        wire:model="product_category_id" 
                        :options="$categories ? $categories->pluck('name', 'id')->toArray() : []"
                        placeholder="Select Category"
                        :error="$errors->first('product_category_id')"
                        wire:ignore
                    />
                </div>
                <div class="col-md-6">
                    <x-form.input id="quantity" type="number" name="quantity" label="Stock Quantity" 
                        wire:model="quantity" placeholder="Enter stock quantity" min="0" :error="$errors->first('quantity')" />
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <x-form.price-input id="buying_price" name="buying_price" label="Buying Price" 
                        wire:model="buying_price" placeholder="0.00" :error="$errors->first('buying_price')" />
                </div>
                <div class="col-md-6">
                    <x-form.price-input id="selling_price" name="selling_price" label="Selling Price" 
                        wire:model="selling_price" placeholder="0.00" :error="$errors->first('selling_price')" />
                </div>
            </div>
            
            <x-form.textarea id="description" name="description" label="Description" 
                wire:model="description" placeholder="Enter product description (optional)" rows="3" :error="$errors->first('description')" />
            
            <div class="mb-3">
                <label for="imageToUpload" class="form-label">Product Image</label>
                <input type="file" class="form-control" id="imageToUpload" name="imageToUpload" 
                    wire:model="imageToUpload" accept="image/*">
                @if($errors->first('imageToUpload'))
                    <div class="invalid-feedback d-block">{{ $errors->first('imageToUpload') }}</div>
                @endif
            </div>
            
            @if($currentImage && $is_edit)
                <div class="mb-3">
                    <label class="form-label">Current Image</label>
                    <div>
                        <img src="{{ asset('storage/' . $currentImage) }}" alt="Current Product Image" style="max-width: 100px; max-height: 100px;" class="img-thumbnail">
                    </div>
                </div>
            @endif
            
            <x-form.checkbox id="is_active" name="is_active" label="Is Active?" 
                wire:model="is_active" :checked="$is_active" />
        </form>
    </x-modal>
</x-sections.default>