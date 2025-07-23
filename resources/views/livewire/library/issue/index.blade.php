<div class="container-fluid">
    <style>
        .modal.show {
            display: block !important;
        }
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            width: 100vw;
            height: 100vh;
            background-color: #000;
            opacity: 0.5;
        }
        .modal {
            z-index: 1050;
        }
    </style>
    
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-book-reader me-2"></i>
                        Library Issues
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}" wire:navigate>
                                    <i class="fas fa-home"></i> Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item active">Library Issues</li>
                        </ol>
                    </nav>
                </div>
                
                @if($this->canAddIssue())
                    <a href="{{ route('library.issues.create') }}" 
                       wire:navigate 
                       class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Add New Issue
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bgs-card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <!-- Search -->
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" 
                               wire:model.live.debounce.300ms="search" 
                               class="form-control" 
                               placeholder="Search by book, author, serial no...">
                    </div>
                </div>

                <!-- Library ID (for admin/staff) -->
                @if($this->canManageIssues())
                    <div class="col-md-3">
                        <label class="form-label">Library ID</label>
                        <div class="input-group">
                            <input type="text" 
                                   wire:model="libraryId" 
                                   class="form-control" 
                                   placeholder="Enter Library ID">
                            <button type="button" 
                                    wire:click="searchByLibraryId" 
                                    class="btn btn-outline-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Status Filter -->
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select wire:model.live="statusFilter" class="form-select">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="returned">Returned</option>
                        <option value="overdue">Overdue</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="button" 
                                wire:click="clearSearch" 
                                class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>
                            Clear
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Issues Table -->
    <div class="bgs-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>
                                <button type="button" 
                                        wire:click="sortBy('book_id')" 
                                        class="btn btn-link p-0 text-decoration-none text-dark">
                                    Book
                                    @if($sortField === 'book_id')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @endif
                                </button>
                            </th>
                            <th>
                                <button type="button" 
                                        wire:click="sortBy('serial_no')" 
                                        class="btn btn-link p-0 text-decoration-none text-dark">
                                    Serial No
                                    @if($sortField === 'serial_no')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @endif
                                </button>
                            </th>
                            <th>
                                <button type="button" 
                                        wire:click="sortBy('issue_date')" 
                                        class="btn btn-link p-0 text-decoration-none text-dark">
                                    Issue Date
                                    @if($sortField === 'issue_date')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @endif
                                </button>
                            </th>
                            <th>
                                <button type="button" 
                                        wire:click="sortBy('due_date')" 
                                        class="btn btn-link p-0 text-decoration-none text-dark">
                                    Due Date
                                    @if($sortField === 'due_date')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @endif
                                </button>
                            </th>
                            <th>Status</th>
                            @if($this->canViewIssue() || $this->canEditIssue())
                                <th class="text-center">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($issues as $index => $issue)
                            <tr>
                                <td>{{ $issues->firstItem() + $index }}</td>
                                <td>
                                    <div class="fw-bold">{{ $issue->book->name ?? 'Unknown Book' }}</div>
                                    @if($issue->book && $issue->book->author)
                                        <small class="text-muted">by {{ $issue->book->author }}</small>
                                    @endif
                                </td>
                                <td>
                                    <code>{{ $issue->serial_no }}</code>
                                </td>
                                <td>
                                    {{ $issue->issue_date->format('M d, Y') }}
                                </td>
                                <td>
                                    {{ $issue->due_date->format('M d, Y') }}
                                    @if(is_null($issue->return_date) && $issue->due_date->isPast())
                                        <br>
                                        <small class="text-danger">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            {{ $issue->due_date->diffForHumans() }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    @if($issue->return_date)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check"></i> Returned
                                        </span>
                                        <br>
                                        <small class="text-muted">{{ $issue->return_date->format('M d, Y') }}</small>
                                    @elseif(is_null($issue->return_date) && $issue->due_date->isPast())
                                        <span class="badge bg-danger">
                                            <i class="fas fa-exclamation-triangle"></i> Overdue
                                        </span>
                                    @else
                                        <span class="badge bg-primary">
                                            <i class="fas fa-book"></i> Active
                                        </span>
                                    @endif
                                    
                                    @if($issue->fines && $issue->fines->count() > 0)
                                        <br>
                                        @php
                                            $pendingFines = $issue->fines->where('status', 'pending');
                                            $paidFines = $issue->fines->where('status', 'paid');
                                            $waivedFines = $issue->fines->where('status', 'waived');
                                        @endphp
                                        
                                        @if($pendingFines->count() > 0)
                                            <small class="badge bg-warning text-dark">
                                                <i class="fas fa-dollar-sign"></i> Fine: PKR {{ number_format($pendingFines->sum('amount'), 2) }}
                                            </small>
                                        @endif
                                        
                                        @if($paidFines->count() > 0)
                                            <small class="badge bg-success">
                                                <i class="fas fa-check-circle"></i> Paid: PKR {{ number_format($paidFines->sum('paid_amount'), 2) }}
                                            </small>
                                        @endif
                                        
                                        @if($waivedFines->count() > 0)
                                            <small class="badge bg-info">
                                                <i class="fas fa-times-circle"></i> Waived: PKR {{ number_format($waivedFines->sum('amount'), 2) }}
                                            </small>
                                        @endif
                                    @endif
                                </td>
                                @if($this->canViewIssue() || $this->canEditIssue())
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            @if($this->canViewIssue())
                                                <a href="{{ route('library.issues.view', $issue) }}" 
                                                   wire:navigate 
                                                   class="btn btn-sm btn-info text-white"
                                                   title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endif

                                            @if($this->canEditIssue() && is_null($issue->return_date))
                                                <a href="{{ route('library.issues.edit', $issue) }}" 
                                                   wire:navigate 
                                                   class="btn btn-sm btn-primary"
                                                   title="Edit Issue">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <button type="button" 
                                                        wire:click="returnBook({{ $issue->issue_id }})" 
                                                        class="btn btn-sm btn-success"
                                                        title="Return Book"
                                                        wire:confirm="Are you sure you want to return this book?"
                                                        wire:loading.attr="disabled"
                                                        wire:target="returnBook({{ $issue->issue_id }})">
                                                    <span wire:loading.remove wire:target="returnBook({{ $issue->issue_id }})">
                                                        <i class="fas fa-reply"></i>
                                                    </span>
                                                    <span wire:loading wire:target="returnBook({{ $issue->issue_id }})">
                                                        <i class="fas fa-spinner fa-spin"></i>
                                                    </span>
                                                </button>

                                                @if($issue->due_date->isPast())
                                                    <button type="button" 
                                                            wire:click="addFine({{ $issue->issue_id }})" 
                                                            class="btn btn-sm btn-warning"
                                                            title="Add Fine"
                                                            wire:loading.attr="disabled"
                                                            wire:target="addFine({{ $issue->issue_id }})">
                                                        <span wire:loading.remove wire:target="addFine({{ $issue->issue_id }})">
                                                            <i class="fas fa-dollar-sign"></i>
                                                        </span>
                                                        <span wire:loading wire:target="addFine({{ $issue->issue_id }})">
                                                            <i class="fas fa-spinner fa-spin"></i>
                                                        </span>
                                                    </button>
                                                @endif
                                            @endif
                                            
                                            @if($issue->fines && $issue->fines->where('status', 'pending')->count() > 0)
                                                @foreach($issue->fines->where('status', 'pending') as $fine)
                                                    <button type="button" 
                                                            wire:click="payFine({{ $fine->id }})" 
                                                            class="btn btn-sm btn-success"
                                                            title="Mark Fine as Paid (PKR {{ number_format($fine->amount, 2) }})"
                                                            wire:confirm="Mark this fine as paid?">
                                                        <i class="fas fa-money-bill"></i>
                                                    </button>
                                                    
                                                    <button type="button" 
                                                            wire:click="waiveFine({{ $fine->id }})" 
                                                            class="btn btn-sm btn-info"
                                                            title="Waive Fine (PKR {{ number_format($fine->amount, 2) }})"
                                                            wire:confirm="Waive this fine?">
                                                        <i class="fas fa-hand-paper"></i>
                                                    </button>
                                                @endforeach
                                            @endif
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $this->canViewIssue() || $this->canEditIssue() ? '7' : '6' }}" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-book-open fa-3x mb-3"></i>
                                        <p class="mb-0">No issues found</p>
                                        @if($this->canAddIssue())
                                            <a href="{{ route('library.issues.create') }}" 
                                               wire:navigate 
                                               class="btn btn-primary mt-2">
                                                <i class="fas fa-plus me-1"></i>
                                                Create First Issue
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($issues->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Showing {{ $issues->firstItem() }} to {{ $issues->lastItem() }} of {{ $issues->total() }} results
                    </div>
                    <div>
                        {{ $issues->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Fine Modal -->
    @if($showFineModal)
        <div class="modal fade show d-block" 
             tabindex="-1" 
             role="dialog" 
             aria-labelledby="fineModalLabel" 
             aria-modal="true"
             style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="fineModalLabel">
                            <i class="fas fa-dollar-sign me-2"></i>
                            Manage Fines
                        </h5>
                        <button type="button" 
                                class="btn-close" 
                                wire:click="closeFineModal" 
                                aria-label="Close"></button>
                    </div>
                <div class="modal-body">
                    @if($selectedIssue)
                        <div class="mb-3 p-3 bg-light rounded">
                            <h6 class="mb-2">Issue Details:</h6>
                            <div class="row">
                                <div class="col-6">
                                    <strong>Book:</strong> {{ $selectedIssue->book->name ?? 'Unknown' }}<br>
                                    <strong>Issue ID:</strong> {{ $selectedIssue->library_id }}
                                </div>
                                <div class="col-6">
                                    <strong>Issue Date:</strong> {{ $selectedIssue->issue_date->format('M d, Y') }}<br>
                                    <strong>Due Date:</strong> {{ $selectedIssue->due_date->format('M d, Y') }}
                                    @if($selectedIssue->due_date->isPast())
                                        <br><small class="text-danger">
                                            ({{ $selectedIssue->due_date->diffForHumans() }})
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Debug Info -->
                        <div class="alert alert-info mb-3">
                            <small>
                                <strong>Debug:</strong> 
                                Fine Amount: "{{ $fineAmount }}" | 
                                Fine Reason: "{{ $fineReason }}" | 
                                Selected Issue ID: {{ $selectedIssueId ?? 'none' }}
                            </small>
                        </div>

                        @if($existingFines && count($existingFines) > 0)
                            <div class="mb-3 p-3 border rounded">
                                <h6 class="mb-3">
                                    <i class="fas fa-history me-2"></i>
                                    Previous Fines ({{ count($existingFines) }})
                                </h6>
                                
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Amount</th>
                                                <th>Reason</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($existingFines as $fine)
                                                <tr>
                                                    <td>{{ $fine->fine_date->format('M d, Y') }}</td>
                                                    <td>PKR {{ number_format($fine->amount, 2) }}</td>
                                                    <td>{{ $fine->reason }}</td>
                                                    <td>
                                                        @if($fine->status === 'pending')
                                                            <span class="badge bg-warning text-dark">Pending</span>
                                                        @elseif($fine->status === 'paid')
                                                            <span class="badge bg-success">Paid</span>
                                                            @if($fine->paid_date)
                                                                <br><small class="text-muted">{{ $fine->paid_date->format('M d, Y') }}</small>
                                                            @endif
                                                        @elseif($fine->status === 'waived')
                                                            <span class="badge bg-info">Waived</span>
                                                            @if($fine->paid_date)
                                                                <br><small class="text-muted">{{ $fine->paid_date->format('M d, Y') }}</small>
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr class="table-info">
                                                <td><strong>Total:</strong></td>
                                                <td><strong>PKR {{ number_format($existingFines->sum('amount'), 2) }}</strong></td>
                                                <td><strong>Pending:</strong></td>
                                                <td><strong>PKR {{ number_format($existingFines->where('status', 'pending')->sum('amount'), 2) }}</strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        @endif
                    @endif

                    <div class="border-top pt-3">
                        <h6 class="mb-3">
                            <i class="fas fa-plus me-2"></i>
                            Add New Fine
                        </h6>

                        <form wire:submit.prevent="saveFine">
                            <div class="mb-3">
                                <label for="fineAmount" class="form-label">
                                    Fine Amount <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">PKR</span>
                                    <input type="number" 
                                           wire:model.defer="fineAmount" 
                                           id="fineAmount"
                                           class="form-control @error('fineAmount') is-invalid @enderror" 
                                           placeholder="0.00"
                                           step="0.01"
                                           min="0.01"
                                           required>
                                </div>
                                @error('fineAmount')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Enter amount in PKR (e.g., 50.00)</div>
                            </div>

                            <div class="mb-3">
                                <label for="fineReason" class="form-label">
                                    Reason <span class="text-danger">*</span>
                                </label>
                                <textarea wire:model.defer="fineReason" 
                                          id="fineReason"
                                          class="form-control @error('fineReason') is-invalid @enderror" 
                                          rows="3"
                                          placeholder="Enter reason for fine..."
                                          required></textarea>
                                @error('fineReason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" 
                            wire:click="closeFineModal" 
                            class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i>
                        Cancel
                    </button>
                    <button type="button" 
                            wire:click="saveFine" 
                            class="btn btn-warning"
                            wire:loading.attr="disabled">
                        <span wire:loading.remove>
                            <i class="fas fa-save me-1"></i>
                            Add Fine
                        </span>
                        <span wire:loading>
                            <i class="fas fa-spinner fa-spin me-1"></i>
                            Adding...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
