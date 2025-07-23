<div class="row">
    <div class="col-xl-12 mb-3">
        <div class="card bgs-card h-100">
            <div class="card-header d-flex justify-content-between mb-3">
                <h6 class="card-title">Add Category</h6>
                <a wire:navigate href='{{ route('categories') }}' class="btn btn-sm btn-primary btn-rounded-sm"><i class="fas fa-arrow-left me-1"></i>Go Back</a>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="createCategory">
                    <x-form.input
                        name="name"
                        label="Category Name"
                        model="name"
                        placeholder="Enter category name"
                        required
                    />
                    
                    <x-form.textarea
                        name="description"
                        label="Description"
                        model="description"
                        placeholder="Enter category description"
                        rows="4"
                    />
                    
                    <button type="submit" class="btn btn-success">Create Category</button>
                </form>
            </div>
        </div>
    </div>
</div>
@push('scripts')
@endpush
