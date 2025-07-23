<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-book-reader me-2"></i>
                        Issue Details #{{ $issue->library_id }}
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}" wire:navigate>
                                    <i class="fas fa-home"></i> Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('library.issues.index') }}" wire:navigate>Library Issues</a>
                            </li>
                            <li class="breadcrumb-item active">Issue Details</li>
                        </ol>
                    </nav>
                </div>
                
                
            </div>
        </div>
    </div>

    <!-- Issue Information -->
    <div class="row">
        <!-- Main Issue Details -->
        <div class="col-lg-8">
            <div class="bgs-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Issue Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Issue ID</label>
                            <p class="mb-0">
                                <code class="fs-6">{{ $issue->library_id }}</code>
                            </p>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Serial Number</label>
                            <p class="mb-0">
                                <code class="fs-6">{{ $issue->serial_no }}</code>
                            </p>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Issue Date</label>
                            <p class="mb-0">
                                <i class="fas fa-calendar-alt text-primary me-1"></i>
                                {{ $issue->issue_date->format('M d, Y') }}
                            </p>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Due Date</label>
                            <p class="mb-0">
                                <i class="fas fa-calendar-check text-warning me-1"></i>
                                {{ $issue->due_date->format('M d, Y') }}
                                @if($this->isOverdue())
                                    <br>
                                    <small class="text-danger">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        {{ $this->daysOverdue }} days overdue
                                    </small>
                                @endif
                            </p>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Return Date</label>
                            <p class="mb-0">
                                @if($issue->return_date)
                                    <i class="fas fa-calendar-check text-success me-1"></i>
                                    {{ $issue->return_date->format('M d, Y') }}
                                @else
                                    <span class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        Not returned yet
                                    </span>
                                @endif
                            </p>
                        </div>

                        @if($issue->note)
                            <div class="col-12">
                                <label class="form-label fw-bold">Notes</label>
                                <div class="bg-light p-3 rounded">
                                    {{ $issue->note }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Status & Book Information -->
        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-flag me-2"></i>
                        Status
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <span class="badge bg-{{ $this->status['class'] }} p-3 fs-6">
                            {{ $this->status['text'] }}
                        </span>
                    </div>
                    
                    @if($this->isOverdue())
                        <div class="alert alert-warning mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>{{ $this->daysOverdue }}</strong> days overdue
                        </div>
                    @elseif(is_null($issue->return_date))
                        <div class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            {{ $issue->due_date->diffForHumans() }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Book Information -->
            <div class="bgs-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-book me-2"></i>
                        Book Information
                    </h5>
                </div>
                <div class="card-body">
                    @if($issue->book)
                        <div class="mb-3">
                            <h6 class="fw-bold">{{ $issue->book->name }}</h6>
                            @if($issue->book->author)
                                <p class="text-muted mb-2">
                                    <i class="fas fa-user me-1"></i>
                                    by {{ $issue->book->author }}
                                </p>
                            @endif
                            @if($issue->book->subject_code)
                                <p class="text-muted mb-2">
                                    <i class="fas fa-tag me-1"></i>
                                    {{ $issue->book->subject_code }}
                                </p>
                            @endif
                        </div>
                        
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-end">
                                    <div class="fw-bold text-primary">{{ $issue->book->quantity }}</div>
                                    <small class="text-muted">Total</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="fw-bold text-success">{{ $issue->book->available_quantity }}</div>
                                <small class="text-muted">Available</small>
                            </div>
                        </div>
                        
                        @if($issue->book->rack)
                            <div class="mt-3 text-center">
                                <span class="badge bg-secondary">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    Rack: {{ $issue->book->rack }}
                                </span>
                            </div>
                        @endif
                    @else
                        <div class="text-muted text-center">
                            <i class="fas fa-question-circle fa-3x mb-2"></i>
                            <p>Book information not available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Fine Modal -->
    @if($showFineModal)
        <div class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                            Add Fine
                        </h5>
                        <button type="button" 
                                wire:click="closeFineModal" 
                                class="btn-close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit="addFine">
                            <div class="mb-3">
                                <label for="fineAmount" class="form-label">
                                    Fine Amount <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" 
                                           wire:model="fineAmount" 
                                           id="fineAmount"
                                           class="form-control @error('fineAmount') is-invalid @enderror" 
                                           step="0.01"
                                           min="0"
                                           placeholder="0.00">
                                </div>
                                @error('fineAmount')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                This book is <strong>{{ $this->daysOverdue }} days</strong> overdue.
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" 
                                wire:click="closeFineModal" 
                                class="btn btn-secondary">
                            Cancel
                        </button>
                        <button type="button" 
                                wire:click="addFine" 
                                class="btn btn-warning">
                            <i class="fas fa-plus me-1"></i>
                            Add Fine
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
