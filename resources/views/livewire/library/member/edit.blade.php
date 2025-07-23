<div>
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-0">
                <i class="fas fa-user-edit me-2"></i>Edit Library Member
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('library.members') }}">Library Members</a>
                    </li>
                    <li class="breadcrumb-item active">Edit Member</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Student Info Card -->
    <div class="card bgs-card mb-4">
        <div class="card-header">
            <h6 class="card-title mb-0">
                <i class="fas fa-user me-2"></i>Student Information
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2 text-center">
                    <img src="{{ $student->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($student->name) . '&size=80&background=007bff&color=ffffff' }}" 
                         alt="{{ $student->name }}" 
                         class="rounded-circle mb-2" 
                         width="80" height="80">
                </div>
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Name:</strong> {{ $student->name }}</p>
                            <p class="mb-1"><strong>Email:</strong> {{ $student->email }}</p>
                            <p class="mb-1"><strong>Registration No:</strong> {{ $student->registration_no ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            @if($student->student)
                                <p class="mb-1"><strong>Class:</strong> {{ $student->student->class->name ?? 'N/A' }}</p>
                                <p class="mb-1"><strong>Section:</strong> {{ $student->student->section->name ?? 'N/A' }}</p>
                                <p class="mb-1"><strong>Roll No:</strong> {{ $student->student->roll_no ?? 'N/A' }}</p>
                            @endif
                            <p class="mb-1"><strong>Join Date:</strong> {{ $libraryMember->library_join_date ? $libraryMember->library_join_date->format('M d, Y') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Library Member Form -->
    <div class="card bgs-card">
        <div class="card-header">
            <h6 class="card-title mb-0">
                <i class="fas fa-edit me-2"></i>Edit Library Membership Details
            </h6>
        </div>
        <div class="card-body">
            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form wire:submit="save">
                <div class="row">
                    <div class="col-md-8">
                        <!-- Library ID -->
                        <div class="mb-3">
                            <label for="libraryId" class="form-label">
                                Library ID <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   wire:model="libraryId" 
                                   class="form-control @error('libraryId') is-invalid @enderror" 
                                   id="libraryId">
                            @error('libraryId')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Library Fee -->
                        <div class="mb-3">
                            <label for="lBalance" class="form-label">
                                Library Fee/Balance <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">Rs.</span>
                                <input type="number" 
                                       step="0.01" 
                                       min="0"
                                       wire:model="lBalance" 
                                       class="form-control @error('lBalance') is-invalid @enderror" 
                                       id="lBalance">
                                @error('lBalance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">Current library fee/deposit amount</div>
                        </div>

                        <!-- Member Info (Read-only) -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Member Since</label>
                                <input type="text" 
                                       value="{{ $libraryMember->library_join_date ? $libraryMember->library_join_date->format('M d, Y') : 'N/A' }}" 
                                       class="form-control" 
                                       readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Member ID</label>
                                <input type="text" 
                                       value="{{ $libraryMember->id }}" 
                                       class="form-control" 
                                       readonly>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" 
                                    class="btn btn-primary" 
                                    wire:loading.attr="disabled">
                                <span wire:loading.remove>
                                    <i class="fas fa-save me-1"></i>Update Library Member
                                </span>
                                <span wire:loading>
                                    <i class="fas fa-spinner fa-spin me-1"></i>Updating...
                                </span>
                            </button>
                            
                            <button type="button" 
                                    wire:click="cancel" 
                                    class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Loading States -->
    <div wire:loading.delay class="d-flex justify-content-center mt-3">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>

@push('styles')
<style>
    .breadcrumb {
        background: none;
        padding: 0;
        margin: 0;
        font-size: 0.875rem;
    }
    
    .breadcrumb-item + .breadcrumb-item::before {
        content: ">";
        color: #6c757d;
    }
    
    .breadcrumb-item a {
        color: #007bff;
        text-decoration: none;
    }
    
    .breadcrumb-item a:hover {
        text-decoration: underline;
    }
    
    .breadcrumb-item.active {
        color: #6c757d;
    }
</style>
@endpush
