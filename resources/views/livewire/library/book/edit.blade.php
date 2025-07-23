<div>
    <div class="row">
        <div class="col-lg-12">
            <div class="bgs-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Edit Book</h4>
                    <a href="{{ route('library.books.index') }}" 
                       wire:navigate 
                       class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Books
                    </a>
                </div>
                <div class="card-body">
                    <form wire:submit="save">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Book Title <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           wire:model="name" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           placeholder="Enter book title">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="subject_code" class="form-label">Subject Code <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           wire:model="subject_code" 
                                           class="form-control @error('subject_code') is-invalid @enderror" 
                                           id="subject_code" 
                                           placeholder="Enter subject code">
                                    @error('subject_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="author" class="form-label">Author <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           wire:model="author" 
                                           class="form-control @error('author') is-invalid @enderror" 
                                           id="author" 
                                           placeholder="Enter author name">
                                    @error('author')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price (Rs.) <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           wire:model="price" 
                                           class="form-control @error('price') is-invalid @enderror" 
                                           id="price" 
                                           placeholder="Enter price"
                                           min="1">
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Total Quantity <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           wire:model="quantity" 
                                           class="form-control @error('quantity') is-invalid @enderror" 
                                           id="quantity" 
                                           placeholder="Enter total quantity"
                                           min="1">
                                    @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="due_quantity" class="form-label">Due Quantity <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           wire:model="due_quantity" 
                                           class="form-control @error('due_quantity') is-invalid @enderror" 
                                           id="due_quantity" 
                                           placeholder="Enter due quantity"
                                           min="0">
                                    @error('due_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Number of books currently issued to students</div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="rack" class="form-label">Rack Location <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           wire:model="rack" 
                                           class="form-control @error('rack') is-invalid @enderror" 
                                           id="rack" 
                                           placeholder="Enter rack location (e.g., A-1-001)">
                                    @error('rack')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Physical location of the book in the library</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('library.books.index') }}" 
                               wire:navigate 
                               class="btn btn-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Book
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
