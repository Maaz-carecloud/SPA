<div>
    <!-- Bulk Create Button -->
    <button wire:click="openModal" class="btn theme-unfilled-btn">
       Emergency Bulk Leave
    </button>

    <!-- Bulk Create Modal -->
    @if($showModal)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Emergency Bulk Leave Creation
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeModal"></button>
                    </div>
                    
                    <form wire:submit.prevent="create">
                        <div class="modal-body">
                            <div class="alert alert-warning mb-0 p-2">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Emergency Leave Notice:</strong> This feature is designed for emergency situations 
                                where bulk leave needs to be applied to multiple users simultaneously (e.g., weather emergencies, 
                                public holidays, institutional closures).
                            </div>

                            <div class="row g-4">
                                <!-- User Type Selection -->
                                <div class="col-12">
                                    <div class="card bgs-card px-0 pb-0">
                                        <div class="card-header">
                                            <h6 class="theme-title">
                                                Select Users
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <!-- User Type Filter -->
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Filter by User Type</label>
                                                    <select wire:model.live="selectedUserType" class="form-select">
                                                        <option value="all">All Users</option>
                                                        <option value="student">Students Only</option>
                                                        <option value="teacher">Teachers Only</option>
                                                        <option value="employee">Employees Only</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 d-flex align-items-end">
                                                    <div class="form-check">
                                                        <input type="checkbox" wire:model.live="selectAll" 
                                                               class="form-check-input" id="selectAll">
                                                        <label class="form-check-label" for="selectAll">
                                                            Select All ({{ $users->count() }} users)
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Users List -->
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="rounded p-3 bg-light border-0" style="max-height: 300px; overflow-y: auto;">
                                                        @if($users->count() > 0)
                                                            <div class="row">
                                                                @foreach($users as $user)
                                                                    <div class="col-md-6 col-lg-4 mb-2">
                                                                        <div class="form-check">
                                                                            <input type="checkbox" 
                                                                                   wire:model.live="selectedUsers" 
                                                                                   value="{{ $user->id }}" 
                                                                                   class="form-check-input" 
                                                                                   id="user_{{ $user->id }}">
                                                                            <label class="form-check-label" for="user_{{ $user->id }}">
                                                                                <div class="d-flex align-items-center">
                                                                                    <img src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=24&background=007bff&color=ffffff' }}" 
                                                                                         alt="{{ $user->name }}" 
                                                                                         class="rounded-circle me-2" 
                                                                                         width="24" height="24">
                                                                                    <div>
                                                                                        <div class="fw-medium">{{ $user->name }}</div>
                                                                                        <small class="text-muted">
                                                                                            {{ $user->email }}
                                                                                            @if($user->registration_no)
                                                                                                | ID: {{ $user->registration_no }}
                                                                                            @endif
                                                                                        </small>
                                                                                    </div>
                                                                                </div>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <div class="text-center text-muted">
                                                                <div class="text-danger">No users found for the selected type.</div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    @error('selectedUsers')
                                                        <div class="text-danger mt-1">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Selection Summary -->
                                            @if(count($selectedUsers) > 0)
                                                <div class="alert alert-info mt-3">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    <strong>{{ count($selectedUsers) }}</strong> user(s) selected for bulk leave creation.
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Leave Details -->
                                <div class="col-12">
                                    <div class="card bgs-card px-0 pt-0">
                                        <div class="card-header">
                                            <h6 class="theme-title">
                                                Leave Details
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <!-- Leave Type -->
                                                <div class="col-md-3">
                                                    <label class="form-label">Leave Type <span class="text-danger">*</span></label>
                                                    <select wire:model.live="leave_type_id" 
                                                            class="form-select @error('leave_type_id') is-invalid @enderror">
                                                        <option value="">Select Leave Type</option>
                                                        @foreach($leaveTypes as $type)
                                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('leave_type_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- Date From -->
                                                <div class="col-md-3">
                                                    <label class="form-label">From Date <span class="text-danger">*</span></label>
                                                    <input type="date" wire:model.live="date_from" 
                                                           class="form-control @error('date_from') is-invalid @enderror">
                                                    @error('date_from')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- Date To -->
                                                <div class="col-md-3">
                                                    <label class="form-label">To Date <span class="text-danger">*</span></label>
                                                    <input type="date" wire:model.live="date_to" 
                                                           class="form-control @error('date_to') is-invalid @enderror">
                                                    @error('date_to')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- Total Days -->
                                                <div class="col-md-3">
                                                    <label class="form-label">Total Days</label>
                                                    <input type="text" value="{{ $total_days }}" 
                                                           class="form-control" readonly>
                                                </div>

                                                <!-- Leave Reason -->
                                                <div class="col-6">
                                                    <label class="form-label">Leave Reason <span class="text-danger">*</span></label>
                                                    <textarea wire:model="leave_reason" 
                                                              class="form-control @error('leave_reason') is-invalid @enderror" 
                                                              rows="2" 
                                                              placeholder="Enter the reason for emergency leave..."></textarea>
                                                    @error('leave_reason')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- Emergency Note -->
                                                <div class="col-6">
                                                    <label class="form-label">Emergency Note (Optional)</label>
                                                    <textarea wire:model="emergency_note" 
                                                              class="form-control @error('emergency_note') is-invalid @enderror" 
                                                              rows="2" 
                                                              placeholder="Additional emergency context or instructions..."></textarea>
                                                    <small class="text-muted">This note will be appended to the leave reason for all selected users.</small>
                                                    @error('emergency_note')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- Attachment -->
                                                <div class="col-12">
                                                    <label class="form-label">Supporting Document (Optional)</label>
                                                    <input type="file" wire:model="attachment" 
                                                           class="form-control @error('attachment') is-invalid @enderror" 
                                                           accept=".pdf,.jpg,.jpeg,.png">
                                                    <small class="text-muted">Max size: 2MB. Formats: PDF, JPG, PNG</small>
                                                    @error('attachment')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    
                                                    <div wire:loading wire:target="attachment" class="mt-2">
                                                        <div class="d-flex align-items-center text-primary">
                                                            <div class="spinner-border spinner-border-sm me-2"></div>
                                                            Uploading file...
                                                        </div>
                                                    </div>

                                                    @if($attachment)
                                                        <div class="mt-2">
                                                            <div class="alert alert-success">
                                                                <i class="fas fa-check-circle me-2"></i>
                                                                <strong>File Selected:</strong> {{ $attachment->getClientOriginalName() }}
                                                                <br><small>Size: {{ number_format($attachment->getSize() / 1024, 2) }} KB</small>
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
                        
                        <div class="modal-footer">
                            <button type="button" class="btn theme-unfilled-btn" wire:click="closeModal">
                                Cancel
                            </button>
                            <button type="submit" class="btn theme-filled-btn" 
                                    wire:loading.attr="disabled" 
                                    wire:target="create"
                                    {{ count($selectedUsers) === 0 ? 'disabled' : '' }}>
                                <span wire:loading wire:target="create">
                                    <span class="spinner-border spinner-border-sm me-1"></span>
                                    Creating...
                                </span>
                                <span wire:loading.remove wire:target="create">
                                    Create {{ count($selectedUsers) }} Leave Record(s)
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

@push('styles')
    <style>
        .modal.show {
            display: block !important;
        }
        
        .form-check-label {
            cursor: pointer;
        }
        
        .form-check-input:checked + .form-check-label {
            color: #0d6efd;
        }
        
        .alert {
            border-radius: 0.5rem;
        }
        
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 0.5rem;
        }
    </style>
@endpush
