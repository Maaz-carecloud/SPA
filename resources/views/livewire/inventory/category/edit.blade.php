<div class="row">
    <div class="col-xl-12 mb-3">
        <div class="card bgs-card h-100">
            <div class="card-header d-flex justify-content-between mb-3">
                <h6 class="card-title">Edit Category</h6>
                <a wire:navigate href='{{ route('categories') }}' class="btn btn-sm btn-primary btn-rounded-sm"><i class="fas fa-arrow-left me-1"></i>Go Back</a>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="updateCategory">
                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name</label>
                        <input type="text" id="name" wire:model.lazy="name" class="form-control @error('name') is-invalid @enderror" placeholder="Enter category name">
                        @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" wire:model.lazy="description" class="form-control @error('description') is-invalid @enderror" placeholder="Enter category description"></textarea>
                        @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" class="btn btn-success">Update Category</button>
                </form>
            </div>
        </div>
    </div>
</div>
@push('scripts')
@endpush
