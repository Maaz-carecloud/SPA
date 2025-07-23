<div>
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-0">
                <i class="fas fa-eye me-2"></i>Leave Request Details
            </h4>
            <p class="text-muted mb-0">
                View leave request for {{ $leave->user->name ?? 'N/A' }}
                <span class="badge {{ $leave->status ? 'bg-success' : 'bg-warning' }} ms-2">
                    {{ $leave->status ? 'Approved' : 'Pending' }}
                </span>
            </p>
        </div>
        <div class="d-flex gap-2">
            {{-- Edit Button - Only for creator or admin --}}
            @php
                $user = Auth::user();
                $isAdmin = false;
                if ($user) {
                    $isAdmin = $user->hasRole('admin') || $user->user_type === 'admin';
                }
            @endphp

            @if ($isAdmin || $leave->created_by === Auth::user()->name)
                <a href="{{ route('leave.edit', $leave->id) }}" wire:navigate class="btn btn-warning">
                    <i class="fas fa-edit me-1"></i>Edit
                </a>
            @endif

            <a href="{{ route('leave.index') }}" wire:navigate class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to List
            </a>
        </div>
    </div> <!-- User Details Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bgs-card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-user me-2"></i>User Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Profile Picture and Basic Info -->
                        <div class="col-md-4">
                            <div class="text-center">
                                <img src="{{ $leave->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($leave->user->name ?? 'User') . '&size=120&background=007bff&color=ffffff' }}"
                                    alt="{{ $leave->user->name ?? 'N/A' }}" class="rounded-circle mb-3" width="120"
                                    height="120">
                                <h5 class="mb-1">{{ $leave->user->name ?? 'N/A' }}</h5>
                                <span class="badge bg-primary">{{ ucfirst($leave->user->user_type ?? 'N/A') }}</span>
                            </div>
                        </div>

                        <!-- Personal Details -->
                        <div class="col-md-8">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted">Employee ID</label>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-id-badge text-primary me-2"></i>
                                        <span>{{ $leave->user->registration_no ?? $leave->user->id }}</span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted">Email</label>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-envelope text-primary me-2"></i>
                                        <span>{{ $leave->user->email ?? 'N/A' }}</span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted">Phone</label>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-phone text-primary me-2"></i>
                                        <span>{{ $leave->user->phone ?? 'N/A' }}</span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted">Gender</label>
                                    <div class="d-flex align-items-center">
                                        <i
                                            class="fas fa-{{ $leave->user->gender === 'male' ? 'mars' : ($leave->user->gender === 'female' ? 'venus' : 'genderless') }} text-primary me-2"></i>
                                        <span>{{ ucfirst($leave->user->gender ?? 'N/A') }}</span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted">Date of Birth</label>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-birthday-cake text-primary me-2"></i>
                                        <span>{{ $leave->user->dob ? \Carbon\Carbon::parse($leave->user->dob)->format('d M Y') : 'N/A' }}</span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted">CNIC</label>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-id-card text-primary me-2"></i>
                                        <span>{{ $leave->user->cnic ?? 'N/A' }}</span>
                                    </div>
                                </div>

                                @if ($leave->user->blood_group)
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-muted">Blood Group</label>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-tint text-danger me-2"></i>
                                            <span>{{ $leave->user->blood_group }}</span>
                                        </div>
                                    </div>
                                @endif

                                @if ($leave->user->address)
                                    <div class="col-12">
                                        <label class="form-label fw-bold text-muted">Address</label>
                                        <div class="d-flex align-items-start">
                                            <i class="fas fa-map-marker-alt text-primary me-2 mt-1"></i>
                                            <span>{{ $leave->user->address }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leave Details Card -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card bgs-card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>Leave Information
                    </h6>
                </div>

                <div class="card-body">
                    <div class="row g-4">
                        <!-- Leave Type -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted">Leave Type</label>
                            <div>
                                <span
                                    class="badge bg-soft-info text-info fs-6">{{ $leave->leaveType->name ?? 'N/A' }}</span>
                                @if ($leave->leaveType->description)
                                    <small class="d-block text-muted mt-1">{{ $leave->leaveType->description }}</small>
                                @endif
                            </div>
                        </div>

                        <!-- Date Range -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted">Date Range</label>
                            <div>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar-day text-primary me-2"></i>
                                    <span>{{ $leave->date_from->format('d M Y') }} to
                                        {{ $leave->date_to->format('d M Y') }}</span>
                                </div>
                                <small class="text-muted">{{ $leave->total_days }} day(s)</small>
                            </div>
                        </div>

                        <!-- Class & Section (for students) -->
                        @if ($leave->class_id || $leave->section_id)
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted">Class & Section</label>
                                <div>
                                    @if ($leave->class)
                                        <span
                                            class="badge bg-soft-secondary text-secondary">{{ $leave->class->name }}</span>
                                    @endif
                                    @if ($leave->section)
                                        <span
                                            class="badge bg-soft-secondary text-secondary ms-1">{{ $leave->section->name }}</span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Leave Reason -->
                        <div class="col-12">
                            <label class="form-label fw-bold text-muted">Leave Reason</label>
                            <div class="p-3 bg-light rounded">
                                {{ $leave->leave_reason }}
                            </div>
                        </div>

                        <!-- Attachment -->
                        @if ($leave->attachment)
                            <div class="col-12">
                                <label class="form-label fw-bold text-muted">Attachment</label>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-paperclip text-primary"></i>
                                    <button wire:click="downloadAttachment" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-download me-1"></i>Download Attachment
                                    </button>
                                    <small class="text-muted">{{ basename($leave->attachment) }}</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline & Actions Card -->
        <div class="col-lg-4">
            <div class="card bgs-card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>Timeline & Actions
                    </h6>
                </div>
                <div class="card-body">
                    <!-- Creation Info -->
                    <div class="timeline-item mb-3">
                        <div class="d-flex align-items-start">
                            <div
                                class="avatar-sm bg-soft-primary rounded-circle me-3 d-flex align-items-center justify-content-center">
                                <i class="fas fa-plus text-primary"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-medium">Leave Request Created</div>
                                <small class="text-muted">
                                    {{ $leave->created_at ? $leave->created_at->format('d M Y, h:i A') : 'N/A' }}
                                </small>
                                @if ($leave->addedBy)
                                    <div class="d-flex align-items-center mt-1">
                                        <img src="{{ $leave->addedBy->profile_photo_url }}"
                                            alt="{{ $leave->addedBy->name }}" class="avatar-xs rounded-circle me-1">
                                        <small class="text-muted">by {{ $leave->addedBy->name }}</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Last Update Info -->
                    @if ($leave->updated_at && $leave->updated_at != $leave->created_at)
                        <div class="timeline-item mb-3">
                            <div class="d-flex align-items-start">
                                <div
                                    class="avatar-sm bg-soft-warning rounded-circle me-3 d-flex align-items-center justify-content-center">
                                    <i class="fas fa-edit text-warning"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-medium">Last Updated</div>
                                    <small class="text-muted">
                                        {{ $leave->updated_at->format('d M Y, h:i A') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Status Change (if approved) -->
                    @if ($leave->status)
                        <div class="timeline-item mb-3">
                            <div class="d-flex align-items-start">
                                <div
                                    class="avatar-sm bg-soft-success rounded-circle me-3 d-flex align-items-center justify-content-center">
                                    <i class="fas fa-check text-success"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-medium">Leave Approved</div>
                                    <small class="text-muted">Status: Approved</small>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="card bgs-card mt-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Quick Stats
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h5 class="mb-1 text-primary">{{ $leave->total_days }}</h5>
                                <small class="text-muted">Days Requested</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h5 class="mb-1 {{ $leave->status ? 'text-success' : 'text-warning' }}">
                                {{ $leave->status ? 'Approved' : 'Pending' }}
                            </h5>
                            <small class="text-muted">Current Status</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        .bgs-card {
            border: none;
            box-shadow: none;
            border-radius: 0.5rem;
        }

        .avatar-sm {
            height: 2.5rem;
            width: 2.5rem;
        }

        .avatar-xs {
            height: 1.5rem;
            width: 1.5rem;
        }

        .bg-soft-primary {
            background-color: rgba(13, 110, 253, 0.15);
        }

        .bg-soft-success {
            background-color: rgba(25, 135, 84, 0.15);
        }

        .bg-soft-warning {
            background-color: rgba(255, 193, 7, 0.15);
        }

        .bg-soft-info {
            background-color: rgba(13, 202, 240, 0.15);
        }

        .bg-soft-secondary {
            background-color: rgba(108, 117, 125, 0.15);
        }

        .timeline-item {
            position: relative;
        }

        .timeline-item:not(:last-child)::after {
            content: '';
            position: absolute;
            left: 1.25rem;
            top: 2.5rem;
            width: 2px;
            height: calc(100% - 1rem);
            background-color: #e9ecef;
        }
    </style>
@endpush

