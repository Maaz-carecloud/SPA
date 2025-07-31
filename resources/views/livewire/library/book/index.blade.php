<x-sections.default>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Library Books</h3>
        <button type="button" class="btn theme-filled-btn" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus me-2"></i>Add New Book
        </button>
    </div>
    @php
        $columns = ['S.No', 'Book Title', 'Subject', 'Author', 'Price', 'Stock', 'Rack', 'Actions'];
        $ajaxUrl = route('datatable.library.books');
    @endphp
    <livewire:data-table :columns="$columns" :ajax-url="$ajaxUrl" table-id="books_table" :key="microtime(true)" />

    <x-modal id="createModal" :title="$modalTitle" :action="$modalAction" :is_edit="$is_edit">
        <form>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Book Title <span class="text-danger">*</span></label>
                    <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Enter book title">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="subject_code" class="form-label">Subject Code <span class="text-danger">*</span></label>
                    <input type="text" wire:model="subject_code" class="form-control @error('subject_code') is-invalid @enderror" id="subject_code" placeholder="Enter subject code">
                    @error('subject_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="author" class="form-label">Author <span class="text-danger">*</span></label>
                    <input type="text" wire:model="author" class="form-control @error('author') is-invalid @enderror" id="author" placeholder="Enter author name">
                    @error('author')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="price" class="form-label">Price (Rs.) <span class="text-danger">*</span></label>
                    <input type="number" wire:model="price" class="form-control @error('price') is-invalid @enderror" id="price" placeholder="Enter price" min="1">
                    @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="quantity" class="form-label">Total Quantity <span class="text-danger">*</span></label>
                    <input type="number" wire:model="quantity" class="form-control @error('quantity') is-invalid @enderror" id="quantity" placeholder="Enter total quantity" min="1">
                    @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="due_quantity" class="form-label">Due Quantity <span class="text-danger">*</span></label>
                    <input type="number" wire:model="due_quantity" class="form-control @error('due_quantity') is-invalid @enderror" id="due_quantity" placeholder="Enter due quantity" min="0">
                    @error('due_quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">Number of books currently issued to students</div>
                </div>
                <div class="col-md-12 mb-3">
                    <label for="rack" class="form-label">Rack Location <span class="text-danger">*</span></label>
                    <input type="text" wire:model="rack" class="form-control @error('rack') is-invalid @enderror" id="rack" placeholder="Enter rack location (e.g., A-1-001)">
                    @error('rack')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">Physical location of the book in the library</div>
                </div>
            </div>
        </form>
    </x-modal>
</x-sections.default>
