<div>
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-0">
                <i class="fas fa-book text-primary me-2"></i>
                Library Books
            </h4>
            <nav aria-label="breadcrumb" class="mt-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" wire:navigate class="text-decoration-none">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="#" class="text-decoration-none">Library</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Books</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('library.books.create') }}" wire:navigate class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Book
            </a>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bgs-card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input 
                            type="text" 
                            class="form-control" 
                            placeholder="Search books by title, author, or subject..." 
                            wire:model.live="search"
                        >
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" wire:model.live="subject_filter">
                        <option value="">All Subjects</option>
                        <option value="Math">Mathematics</option>
                        <option value="Science">Science</option>
                        <option value="English">English</option>
                        <option value="History">History</option>
                        <option value="Geography">Geography</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" wire:model.live="availability_filter">
                        <option value="">All Books</option>
                        <option value="available">Available</option>
                        <option value="out_of_stock">Out of Stock</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <!-- Books Table -->
    <div class="bgs-card shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="fas fa-list me-2"></i>
                    Books List ({{ $books->total() }} total)
                </h6>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-secondary" wire:click="export">
                        <i class="fas fa-download me-1"></i>Export
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <x-data-table
                :headers="['S.No', 'Book Title', 'Subject', 'Author', 'Price', 'Stock', 'Rack', 'Actions']"
                :items="$books"
                tableId="books-table"
                :showPagination="false"
                :showPerPageFilter="false"
                :showSearch="false"
                :showExport="false"
            >
                @foreach($books as $index => $book)
                    <tr>
                        <td>{{ $books->firstItem() + $index }}</td>
                        <td>
                            <div class="fw-medium">{{ $book->name }}</div>
                            @if($book->edition)
                                <small class="text-muted">{{ $book->edition }} Edition</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $book->subject_code }}</span>
                        </td>
                        <td>{{ $book->author }}</td>
                        <td>
                            <strong class="text-success">Rs. {{ number_format($book->price) }}</strong>
                        </td>
                        <td>
                            <?php echo $book->available_quantity; ?>
                            <span class="badge bg-{{ $book->available_quantity > 0 ? 'success' : 'danger' }}">
                                {{ $book->available_quantity }}/{{ $book->quantity }}
                            </span>
                            @if($book->available_quantity == 0)
                                <br><small class="text-danger">Out of Stock</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $book->rack }}</span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('library.books.view', $book->id) }}" 
                                   wire:navigate
                                   class="btn btn-sm btn-info"
                                   title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('library.books.edit', $book->id) }}" 
                                   wire:navigate
                                   class="btn btn-sm btn-warning"
                                   title="Edit Book">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button wire:click="delete({{ $book->id }})" 
                                        class="btn btn-sm btn-danger"
                                        title="Delete Book"
                                        onclick="return confirm('Are you sure you want to delete this book?\n\nThis action cannot be undone and all associated data will be permanently removed.')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-data-table>
        </div>
        @if($books->hasPages())
            <div class="card-footer bg-white">
                {{ $books->links() }}
            </div>
        @endif
    </div>

    @if($books->isEmpty() && !$search)
        <div class="text-center py-5">
            <div class="text-muted">
                <i class="fas fa-book fa-3x mb-3"></i>
                <h5>No Books Available</h5>
                <p>Start building your library by adding your first book.</p>
                <a href="{{ route('library.books.create') }}" wire:navigate class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add First Book
                </a>
            </div>
        </div>
    @endif
    
</div>
