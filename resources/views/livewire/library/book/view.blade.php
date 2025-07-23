<div>
    <div class="row">
        <div class="col-lg-12">
            <div class="bgs-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Book Details</h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('library.books.edit', $book->id) }}" 
                           wire:navigate 
                           class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Book
                        </a>
                        <a href="{{ route('library.books.index') }}" 
                           wire:navigate 
                           class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Books
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Book ID</label>
                                        <div class="fw-bold">{{ $book->id }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Subject Code</label>
                                        <div>
                                            <span class="badge bg-info fs-6">{{ $book->subject_code }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Book Title</label>
                                        <div class="fw-bold fs-5">{{ $book->name }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Author</label>
                                        <div class="fw-bold">{{ $book->author }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Price</label>
                                        <div class="fw-bold text-success">Rs. {{ number_format($book->price) }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Rack Location</label>
                                        <div class="fw-bold">{{ $book->rack }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Added On</label>
                                        <div>{{ $book->created_at->format('M d, Y \a\t h:i A') }}</div>
                                    </div>
                                </div>
                                @if($book->updated_at != $book->created_at)
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Last Updated</label>
                                        <div>{{ $book->updated_at->format('M d, Y \a\t h:i A') }}</div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="bgs-card bg-light">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">Inventory Status</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span>Total Quantity:</span>
                                            <span class="fw-bold">{{ $book->quantity }}</span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span>Currently Issued:</span>
                                            <span class="fw-bold text-warning">{{ $book->due_quantity }}</span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span>Available:</span>
                                            <span class="fw-bold {{ $book->available_quantity > 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $book->available_quantity }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    @if($book->available_quantity > 0)
                                        <div class="alert alert-success mb-0">
                                            <i class="fas fa-check-circle"></i> Available for issue
                                        </div>
                                    @else
                                        <div class="alert alert-warning mb-0">
                                            <i class="fas fa-exclamation-triangle"></i> Out of stock
                                        </div>
                                    @endif
                                    
                                    <div class="mt-3">
                                        <div class="progress">
                                            <div class="progress-bar bg-warning" 
                                                 role="progressbar" 
                                                 style="width: {{ ($book->due_quantity / $book->quantity) * 100 }}%"
                                                 aria-valuenow="{{ $book->due_quantity }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="{{ $book->quantity }}">
                                            </div>
                                        </div>
                                        <small class="text-muted">
                                            {{ round(($book->due_quantity / $book->quantity) * 100, 1) }}% issued
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
