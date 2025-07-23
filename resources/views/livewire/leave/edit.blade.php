<div>
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-0">
                <i class="fas fa-edit me-2"></i>Edit Leave Request
            </h4>
            <p class="text-muted mb-0">
                Update leave request for {{ $leave->user->name ?? 'N/A' }}
                <span class="badge {{ $leave->status ? 'bg-success' : 'bg-warning' }} ms-2">
                    {{ $leave->status ? 'Approved' : 'Pending' }}
                </span>
            </p>
        </div>
        <a href="{{ route('leave.index') }}" wire:navigate class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Leave List
        </a>
    </div>

    <!-- Edit Form -->
    <div class="row">
        <div class="col-12">
            <div class="card bgs-card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>Leave Request Details
                    </h6>
                </div>
                <div class="card-body">
                    <form wire:submit="update" enctype="multipart/form-data">
                        <div class="row g-3">
                            <!-- User Type Selection -->
                            <div class="col-md-6">
                                <label for="user_type" class="form-label">
                                    <i class="fas fa-users me-1"></i>User Type <span class="text-danger">*</span>
                                </label>
                                <select wire:model.live="user_type"
                                    class="form-select @error('user_type') is-invalid @enderror" id="user_type">
                                    <option value="">Select User Type</option>
                                    <option value="student">Student</option>
                                    <option value="teacher">Teacher</option>
                                    <option value="employee">Employee</option>
                                </select>
                                @error('user_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- User Selection -->
                            <div class="col-md-6">
                                <label for="user_id" class="form-label">
                                    <i class="fas fa-user me-1"></i>
                                    @if ($user_type == 'student')
                                        Student
                                    @elseif($user_type == 'teacher')
                                        Teacher
                                    @elseif($user_type == 'employee')
                                        Employee
                                    @else
                                        User
                                    @endif
                                    <span class="text-danger">*</span>
                                </label>
                                <select wire:model.live="user_id"
                                    class="form-select @error('user_id') is-invalid @enderror" id="user_id"
                                    @if (!$user_type) disabled @endif>
                                    <option value="">
                                        @if ($user_type)
                                            Select {{ ucfirst($user_type) }}
                                        @else
                                            Select User Type First
                                        @endif
                                    </option>
                                    @if ($user_type && count($users) > 0)
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if (!$user_type)
                                    <small class="text-muted">Please select a user type first</small>
                                @endif
                            </div>

                            <!-- Class (Only for Students) -->
                            @if ($user_type == 'student')
                                <div class="col-md-6">
                                    <label for="class_id" class="form-label">
                                        <i class="fas fa-chalkboard-teacher me-1"></i>Class <span
                                            class="text-danger">*</span>
                                    </label>
                                    <select wire:model.live="class_id"
                                        class="form-select @error('class_id') is-invalid @enderror" id="class_id">
                                        <option value="">Select Class</option>
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->id }}">
                                                {{ $class->name }}
                                                @if ($class->teacher)
                                                    ({{ $class->teacher->name }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('class_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Section (Only for Students) -->
                                <div class="col-md-6">
                                    <label for="section_id" class="form-label">
                                        <i class="fas fa-layer-group me-1"></i>Section <span
                                            class="text-danger">*</span>
                                    </label>
                                    <select wire:model="section_id"
                                        class="form-select @error('section_id') is-invalid @enderror" id="section_id">
                                        <option value="">Select Section</option>
                                        @foreach ($sections as $section)
                                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('section_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            <!-- Leave Type -->
                            <div class="col-md-6">
                                <label for="leave_type_id" class="form-label">
                                    <i class="fas fa-tag me-1"></i>Leave Type <span class="text-danger">*</span>
                                </label>
                                <select wire:model="leave_type_id"
                                    class="form-select @error('leave_type_id') is-invalid @enderror" id="leave_type_id">
                                    <option value="">Select Leave Type</option>
                                    @foreach ($leaveTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                @error('leave_type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date From -->
                            <div class="col-md-4">
                                <label for="date_from" class="form-label">
                                    <i class="fas fa-calendar-day me-1"></i>From Date <span class="text-danger">*</span>
                                </label>
                                <input type="date" wire:model="date_from"
                                    class="form-control @error('date_from') is-invalid @enderror" id="date_from">
                                @error('date_from')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date To -->
                            <div class="col-md-4">
                                <label for="date_to" class="form-label">
                                    <i class="fas fa-calendar-day me-1"></i>To Date <span class="text-danger">*</span>
                                </label>
                                <input type="date" wire:model="date_to"
                                    class="form-control @error('date_to') is-invalid @enderror" id="date_to"
                                    min="{{ $date_from }}">
                                @error('date_to')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Total Days (Auto-calculated) -->
                            <div class="col-md-4">
                                <label for="total_days" class="form-label">
                                    <i class="fas fa-calculator me-1"></i>Total Days
                                </label>
                                <input type="number" value="{{ $total_days }}" class="form-control"
                                    id="total_days" readonly>
                                <small class="text-muted">Automatically calculated</small>
                            </div>

                            <!-- Leave Reason -->
                            <div class="col-12">
                                <label for="leave_reason" class="form-label">
                                    <i class="fas fa-comment me-1"></i>Leave Reason <span class="text-danger">*</span>
                                </label>
                                <textarea wire:model="leave_reason" class="form-control @error('leave_reason') is-invalid @enderror"
                                    id="leave_reason" rows="4" placeholder="Please provide a detailed reason for your leave request..."></textarea>
                                @error('leave_reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Maximum 1000 characters</small>
                            </div>

                            <!-- Status (For Admins) -->
                            @if (auth()->user()->hasRole('admin') || auth()->user()->user_type === 'admin')
                                <div class="col-md-6">
                                    <label for="status" class="form-label">
                                        <i class="fas fa-check-circle me-1"></i>Status
                                    </label>
                                    <select wire:model="status"
                                        class="form-select @error('status') is-invalid @enderror" id="status">
                                        <option value="0">Pending</option>
                                        <option value="1">Approved</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            <!-- Current Attachment -->
                            @if ($currentAttachment)
                                <div class="col-12">
                                    <label class="form-label">
                                        <i class="fas fa-paperclip me-1"></i>Current Attachment
                                    </label>
                                    <div class="card bg-light">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-file-alt text-primary me-2 fs-4"></i>
                                                <div>
                                                    <small class="text-muted">Current file:</small>
                                                    <br>
                                                    <strong>{{ basename($currentAttachment) }}</strong>
                                                </div>
                                            </div>
                                            <div class="btn-group">
                                                <button wire:click="downloadAttachment"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-download me-1"></i>Download
                                                </button>
                                                <button type="button" wire:click="removeAttachment"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Are you sure you want to remove this attachment?')">
                                                    <i class="fas fa-trash me-1"></i>Remove
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- New Attachment -->
                            <div class="col-12">
                                <label for="attachment" class="form-label">
                                    <i class="fas fa-paperclip me-1"></i>
                                    {{ $currentAttachment ? 'Replace Attachment (Optional)' : 'Supporting Document (Optional)' }}
                                </label>
                                <input type="file" wire:model="attachment"
                                    class="form-control @error('attachment') is-invalid @enderror" id="attachment"
                                    accept=".pdf,.jpg,.jpeg,.png">
                                @error('attachment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    Supported formats: PDF, JPG, JPEG, PNG (Max: 2MB)
                                </small>

                                <!-- Loading indicator for file upload -->
                                <div wire:loading wire:target="attachment" class="mt-2">
                                    <div class="d-flex align-items-center text-primary">
                                        <div class="spinner-border spinner-border-sm me-2" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <small>Uploading file...</small>
                                    </div>
                                </div>

                                <!-- File preview -->
                                @if ($attachment)
                                    <div class="mt-2">
                                        <div class="alert alert-success d-flex align-items-center">
                                            <i class="fas fa-check-circle me-2"></i>
                                            <div>
                                                <strong>New File Selected:</strong>
                                                {{ $attachment->getClientOriginalName() }}
                                                <br><small>Size: {{ number_format($attachment->getSize() / 1024, 2) }}
                                                    KB</small>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <button type="button" wire:click="$redirect('{{ route('leave.index') }}')"
                                            class="btn btn-secondary">
                                            <i class="fas fa-times me-1"></i>Cancel
                                        </button>
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                            <span wire:loading.remove wire:target="update">
                                                <i class="fas fa-save me-1"></i>Update Leave Request
                                            </span>
                                            <span wire:loading wire:target="update">
                                                <i class="fas fa-spinner fa-spin me-1"></i>Updating...
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Leave History Card -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card bgs-card border-info">
                <div class="card-header bg-soft-info">
                    <h6 class="card-title mb-0 text-info">
                        <i class="fas fa-history me-2"></i>Leave Request Details
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td><strong>Created By:</strong></td>
                                    <td>{{ $leave->added_by ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Created On:</strong></td>
                                    <td>{{ $leave->created_at ? $leave->created_at->format('M d, Y h:i A') : 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Last Updated:</strong></td>
                                    <td>{{ $leave->updated_at ? $leave->updated_at->format('M d, Y h:i A') : 'N/A' }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td><strong>Current Status:</strong></td>
                                    <td>
                                        <span class="badge {{ $leave->status ? 'bg-success' : 'bg-warning' }}">
                                            {{ $leave->status ? 'Approved' : 'Pending' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Total Days:</strong></td>
                                    <td><span class="badge bg-primary">{{ $leave->total_days }} day(s)</span></td>
                                </tr>
                                @if ($leave->class_id)
                                    <tr>
                                        <td><strong>Class/Section:</strong></td>
                                        <td>{{ $leave->class->name ?? 'N/A' }} / {{ $leave->section->name ?? 'N/A' }}
                                        </td>
                                    </tr>
                                @endif
                            </table>
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

        .bg-soft-info {
            background-color: rgba(13, 202, 240, 0.15);
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .text-danger {
            color: #dc3545 !important;
        }

        .is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: #dc3545;
        }

        .alert {
            border-radius: 0.5rem;
        }

        .badge {
            font-size: 0.7rem;
            padding: 0.4em 0.6em;
        }

        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, .125);
            background-color: rgba(0, 0, 0, .03);
        }

        .btn {
            border-radius: 0.375rem;
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .table-borderless td {
            border: none;
            padding: 0.25rem 0.5rem;
        }

        .bg-light {
            background-color: #f8f9fa !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Auto-hide flash messages
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);

        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            $('[title]').tooltip();
        });

        // Date validation
        document.addEventListener('livewire:init', function() {
            // Ensure to-date is not before from-date
            document.getElementById('date_from').addEventListener('change', function() {
                const toDate = document.getElementById('date_to');
                toDate.min = this.value;
                if (toDate.value && toDate.value < this.value) {
                    toDate.value = this.value;
                }
            });
        });

        // Listen for success events
        Livewire.on('success', (event) => {
            // Show success message
            const alertHtml = `
                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                    <i class="fas fa-check-circle me-2"></i>${event.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            document.querySelector('.container').insertAdjacentHTML('beforeend', alertHtml);
        });
    </script>
@endpush
