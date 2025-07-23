<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-plus-circle me-2"></i>
                        Create New Issue
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
                            <li class="breadcrumb-item active">Create New</li>
                        </ol>
                    </nav>
                </div>
                
                <button type="button" 
                        wire:click="cancel" 
                        class="btn btn-outline-secondary">
                    <i class="fas fa-times me-2"></i>
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="bgs-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-book-reader me-2"></i>
                        Issue Details
                    </h5>
                    <small class="text-muted">
                        <i class="fas fa-calendar-day me-1"></i>
                        Issue Date: {{ now()->format('M d, Y') }} (Today)
                    </small>
                </div>
                <div class="card-body">
                    <form wire:submit="save">
                        <!-- Hidden Issue Date (always today) -->
                        <input type="hidden" wire:model="issue_date" value="{{ now()->toDateString() }}">
                        
                        <div class="row g-3">
                            <!-- Issue ID -->
                            <div class="col-md-6">
                                <label for="library_id" class="form-label">
                                    Issue ID <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       wire:model="library_id" 
                                       id="library_id"
                                       class="form-control @error('library_id') is-invalid @enderror" 
                                       placeholder="Auto-generated (e.g., LIB001)"
                                       readonly>
                                @error('library_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    This ID is automatically generated
                                </div>
                            </div>

                            <!-- Library Member Selection -->
                            <div class="col-md-6">
                                <label for="library_member_id" class="form-label">
                                    Library Member <span class="text-danger">*</span>
                                </label>
                                <select wire:model.live="library_member_id" 
                                        id="library_member_id"
                                        class="form-select @error('library_member_id') is-invalid @enderror">
                                    <option value="">Select Student / Member</option>
                                    @foreach($libraryMembers as $member)
                                        <option value="{{ $member->id }}">
                                            {{ $member->name }} - ID: {{ $member->library_id }} (User: {{ $member->user_id }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('library_member_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                
                                @if($selectedMember)
                                    <div class="mt-2 p-2 bg-light rounded">
                                        <small class="text-muted">
                                            <strong>Selected:</strong> {{ $selectedMember->name }} 
                                            (Library ID: {{ $selectedMember->library_id }}, User ID: {{ $selectedMember->user_id }})
                                        </small>
                                    </div>
                                @endif
                            </div>

                            <!-- Book Selection -->
                            <div class="col-12">
                                <label for="book_id" class="form-label">
                                    Book <span class="text-danger">*</span>
                                </label>
                                <select wire:model.live="book_id" 
                                        id="book_id"
                                        class="form-select @error('book_id') is-invalid @enderror">
                                    <option value="">Select a book</option>
                                    @foreach($books as $book)
                                        <option value="{{ $book->id }}">
                                            {{ $book->name }} - {{ $book->author }} 
                                            (Available: {{ $book->available_quantity }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('book_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                
                                @if($selectedBook)
                                    <div class="mt-2 p-3 bg-light rounded">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>{{ $selectedBook->name }}</strong><br>
                                                <small class="text-muted">Author: {{ $selectedBook->author }}</small>
                                            </div>
                                            <div class="col-md-6 text-end">
                                                <span class="badge bg-{{ $selectedBook->available_quantity > 0 ? 'success' : 'danger' }}">
                                                    {{ $selectedBook->available_quantity }} Available
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Author (Auto-filled from Book) -->
                            <div class="col-md-6">
                                <label for="author" class="form-label">
                                    Author
                                </label>
                                <input type="text" 
                                       wire:model="author" 
                                       id="author"
                                       class="form-control" 
                                       placeholder="Auto-filled when book is selected"
                                       disabled
                                       readonly>
                                <div class="form-text">
                                    This field is automatically filled when you select a book
                                </div>
                            </div>

                            <!-- Subject Code (Auto-filled from Book) -->
                            <div class="col-md-6">
                                <label for="subject_code" class="form-label">
                                    Subject Code
                                </label>
                                <input type="text" 
                                       wire:model="subject_code" 
                                       id="subject_code"
                                       class="form-control" 
                                       placeholder="Auto-filled when book is selected"
                                       disabled
                                       readonly>
                                <div class="form-text">
                                    This field is automatically filled when you select a book
                                </div>
                            </div>

                            <!-- Serial Number -->
                            <div class="col-md-6">
                                <label for="serial_no" class="form-label">
                                    Serial Number <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       wire:model="serial_no" 
                                       id="serial_no"
                                       class="form-control @error('serial_no') is-invalid @enderror" 
                                       placeholder="Enter serial number">
                                @error('serial_no')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Due Date -->
                            <div class="col-md-6">
                                <label for="due_date" class="form-label">
                                    Due Date <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       wire:model="due_date" 
                                       id="due_date"
                                       class="form-control @error('due_date') is-invalid @enderror">
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Standard loan period is 14 days from today ({{ now()->format('M d, Y') }})
                                </div>
                            </div>

                            <!-- Note -->
                            <div class="col-12">
                                <label for="note" class="form-label">
                                    Note
                                </label>
                                <textarea wire:model="note" 
                                          id="note"
                                          class="form-control @error('note') is-invalid @enderror" 
                                          rows="3"
                                          placeholder="Enter any additional notes..."></textarea>
                                @error('note')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" 
                                            wire:click="cancel" 
                                            class="btn btn-secondary">
                                        <i class="fas fa-times me-1"></i>
                                        Cancel
                                    </button>
                                    <button type="submit" 
                                            class="btn btn-primary"
                                            wire:loading.attr="disabled">
                                        <span wire:loading.remove>
                                            <i class="fas fa-save me-1"></i>
                                            Create Issue
                                        </span>
                                        <span wire:loading>
                                            <i class="fas fa-spinner fa-spin me-1"></i>
                                            Creating...
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
</div>
