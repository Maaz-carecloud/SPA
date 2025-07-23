<div>
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-0">
                <i class="fas fa-user-circle me-2"></i>Library Member Details
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
                    <li class="breadcrumb-item active">View Member</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <button wire:click="editMember" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i>Edit
            </button>
            <button wire:click="goBack" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to List
            </button>
        </div>
    </div>

    <!-- Student & Library Member Info Card -->
    <div class="card bgs-card mb-4">
        <div class="card-header">
            <h6 class="card-title mb-0">
                <i class="fas fa-id-card me-2"></i>Member Information
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 text-center">
                    <img src="{{ $student->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($student->name) . '&size=120&background=007bff&color=ffffff' }}" 
                         alt="{{ $student->name }}" 
                         class="rounded-circle mb-3" 
                         width="120" height="120">
                    
                    <div class="badge bg-success fs-6 mb-2">
                        <i class="fas fa-book-reader me-1"></i>Active Member
                    </div>
                    
                    <div class="text-muted small">
                        Member since {{ $libraryMember->library_join_date ? $libraryMember->library_join_date->format('M d, Y') : 'N/A' }}
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <!-- Personal Information -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-user me-1"></i>Personal Information
                            </h6>
                            <table class="table table-sm">
                                <tr>
                                    <td class="fw-medium" style="width: 40%;">Full Name:</td>
                                    <td>{{ $student->name }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">Email:</td>
                                    <td>{{ $student->email }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">Phone:</td>
                                    <td>{{ $student->phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">Registration No:</td>
                                    <td>{{ $student->registration_no ?? 'N/A' }}</td>
                                </tr>
                                @if($student->cnic)
                                <tr>
                                    <td class="fw-medium">CNIC:</td>
                                    <td>{{ $student->cnic }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>

                        <!-- Academic Information -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-graduation-cap me-1"></i>Academic Information
                            </h6>
                            <table class="table table-sm">
                                @if($student->student)
                                <tr>
                                    <td class="fw-medium" style="width: 40%;">Class:</td>
                                    <td>{{ $student->student->class->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">Section:</td>
                                    <td>{{ $student->student->section->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">Roll No:</td>
                                    <td>{{ $student->student->roll_no ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">Admission Date:</td>
                                    <td>{{ $student->student->admission_date ? \Carbon\Carbon::parse($student->student->admission_date)->format('M d, Y') : 'N/A' }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Library Membership Details Card -->
    <div class="card bgs-card mb-4">
        <div class="card-header">
            <h6 class="card-title mb-0">
                <i class="fas fa-book me-2"></i>Library Membership Details
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-sm">
                        <tr>
                            <td class="fw-medium" style="width: 40%;">Library ID:</td>
                            <td>
                                <span class="badge bg-primary">{{ $libraryMember->library_id }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Member ID:</td>
                            <td>{{ $libraryMember->id }}</td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Join Date:</td>
                            <td>{{ $libraryMember->library_join_date ? $libraryMember->library_join_date->format('M d, Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Status:</td>
                            <td>
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>Active
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-sm">
                        <tr>
                            <td class="fw-medium" style="width: 40%;">Current Balance:</td>
                            <td>
                                <span class="h6 text-success">Rs. {{ number_format($libraryMember->fee, 2) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Last Updated:</td>
                            <td>{{ $libraryMember->updated_at ? $libraryMember->updated_at->format('M d, Y h:i A') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Created:</td>
                            <td>{{ $libraryMember->created_at ? $libraryMember->created_at->format('M d, Y h:i A') : 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons Card -->
    <div class="card bgs-card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0">Member Actions</h6>
                    <small class="text-muted">Manage this library member</small>
                </div>
                <div class="d-flex gap-2">
                    <button wire:click="editMember" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i>Edit Member
                    </button>
                    
                    <button wire:click="deleteMember" 
                            class="btn btn-danger"
                            onclick="return confirm('Are you sure you want to remove this library member? This action cannot be undone.')">
                        <i class="fas fa-trash me-1"></i>Remove Member
                    </button>
                </div>
            </div>
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
    
    .table td {
        border: none;
        padding: 0.5rem 0;
    }
    
    .table tr:last-child td {
        border-bottom: none;
    }
</style>
@endpush
