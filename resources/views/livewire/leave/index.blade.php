<x-sections.default>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>All Leaves</h3>
        <button type="button" class="btn theme-filled-btn" data-bs-toggle="modal" data-bs-target="#createModal">
            + Create
        </button>
    </div>
    @php
        $columns = ['#', 'Employee', 'Leave Type', 'Reason', 'From', 'To', 'Status', 'Action'];
        $rows = [];
        if($leaves && count($leaves)) {
            foreach($leaves as $index => $leave) {
                $rows[] = [
                $index + 1,
                e(optional($leave->user)->name),
                e(optional($leave->leaveType)->name),
                $leave->leave_reason, // Render as raw HTML in the table
                $leave->date_from ? e($leave->date_from->format('Y-m-d')) : '',
                $leave->date_to ? e($leave->date_to->format('Y-m-d')) : '',
                e($leave->status == 1) ? 'Approved' : 'Pending',
                '<div class="action-items">' .
                    '<span><a @click.prevent="$dispatch(\'edit-mode\', {id: ' . $leave->id . '})" data-bs-toggle="modal"
                            data-bs-target="#createModal"><i class="fa fa-edit"></i></a></span>' .
                    '<span><a href="javascript:void(0)" class="delete-swal" data-id="' . $leave->id . '"><i
                                class="fa fa-trash"></i></a></span>' .
                    '</div>'
                ];
            }
        }
    @endphp
    <livewire:data-table :columns="$columns" :rows="$rows" table-id="leavesTable" :key="microtime(true)" render-html-cols="[3]" />

    <x-modal id="createModal" :title="$modalTitle" :action="$modalAction" :is_edit="$is_edit">
        <form enctype="multipart/form-data">
            <div class="row g-3">
                <!-- Bulk Mode Toggle -->
                <div class="mb-2">
                    <input type="checkbox" wire:model="bulk_mode" id="bulk_mode">
                    <label for="bulk_mode">Bulk Leave (multiple users)</label>
                </div>
                <!-- User Type Selection -->
                <div class="col-md-4">
                    <label for="user_type" class="form-label">
                        User Type <span class="text-danger">*</span>
                    </label>
                    <select wire:model.live="user_type" class="form-select @error('user_type') is-invalid @enderror"
                        id="user_type">
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
                @if($bulk_mode)
                    <div class="col-md-4">
                        <label for="user_ids" class="form-label">
                            Select Users <span class="text-danger">*</span>
                        </label>
                        <div class="form-check mb-1">
                            <input type="checkbox" class="form-check-input" id="select_all_users" wire:model="select_all_users">
                            <label class="form-check-label" for="select_all_users">
                                Select All ({{ count($users) }})
                            </label>
                        </div>
                        <select wire:model="user_ids" multiple class="form-select @error('user_ids') is-invalid @enderror" id="user_ids">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('user_ids')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Hold Ctrl (Windows) or Cmd (Mac) to select multiple users.</small>
                        @if(count($user_ids) > 0)
                            <div class="alert alert-info mt-2 p-2">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>{{ count($user_ids) }}</strong> user(s) selected for bulk leave creation.
                            </div>
                        @endif
                    </div>
                @else
                <div class="col-md-4">
                    <label for="user_id" class="form-label">
                        @if($user_type == 'student')
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
                    <select wire:model="user_id" class="form-select @error('user_id') is-invalid @enderror" id="user_id"
                        @if(!$user_type) disabled @endif>
                        <option value="">
                            @if($user_type)
                            Select {{ ucfirst($user_type) }}
                            @else
                            Select User Type First
                            @endif
                        </option>
                        @if($user_type && count($users) > 0)
                        @foreach($users as $user)
                        <option value="{{ (string) $user->id }}">{{ $user->name }}</option>
                        @endforeach
                        @endif
                    </select>
                    @error('user_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if(!$user_type)
                    <small class="text-muted">Please select a user type first</small>
                    @endif
                </div>
                @endif

                <!-- Leave Type -->
                <div class="col-md-4">
                    <label for="leave_type_id" class="form-label">
                        Leave Type <span class="text-danger">*</span>
                    </label>
                    <select wire:model="leave_type_id" class="form-select @error('leave_type_id') is-invalid @enderror"
                        id="leave_type_id">
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
                <div class="col-md-4">
                    <label for="date_from" class="form-label">
                        From Date <span class="text-danger">*</span>
                    </label>
                    <input type="date" wire:model="date_from"
                        class="form-control @error('date_from') is-invalid @enderror" id="date_from"
                        min="{{ date('Y-m-d') }}">
                    @error('date_from')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Date To -->
                <div class="col-md-4">
                    <label for="date_to" class="form-label">
                        To Date <span class="text-danger">*</span>
                    </label>
                    <input type="date" wire:model="date_to" class="form-control @error('date_to') is-invalid @enderror"
                        id="date_to" min="{{ $date_from ?? date('Y-m-d') }}">
                    @error('date_to')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Total Days (Auto-calculated) -->
                <div class="col-md-4">
                    <label for="total_days" class="form-label">
                        Total Days
                    </label>
                    <input type="number" wire:model="total_days" class="form-control" id="total_days" readonly>
                    <small class="text-muted">Automatically calculated</small>
                </div>

                <!-- Leave Reason -->
                <div class="col-12">
                    <x-form.ckeditor label="Leave Reason" name="leave_reason" model="leave_reason" required=true
                        placeholder="Please provide a detailed reason for your leave request..."
                        class="@error('leave_reason') is-invalid @enderror" />
                    @error('leave_reason')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Maximum 1000 characters</small>
                </div>

                <!-- Attachment -->
                <div class="col-12">
                    <label for="attachment" class="form-label">
                        Supporting Document (Optional)
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
                    @if($attachment)
                    <div class="mt-2">
                        <div class="alert alert-success d-flex align-items-center">
                            <i class="fas fa-check-circle me-2"></i>
                            <div>
                                <strong>File Selected:</strong> {{ is_object($attachment) ?
                                $attachment->getClientOriginalName() : $attachment }}
                                @if(is_object($attachment))<br><small>Size: {{ number_format($attachment->getSize() /
                                    1024, 2) }} KB</small>@endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </form>
    </x-modal>
</x-sections.default>