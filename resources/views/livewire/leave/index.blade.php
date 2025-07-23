<div>
<!-- Statistics Cards -->
{{-- <div class="row mb-3">
    <div class="col-xl-3 col-md-6">
        <div class="card bgs-card common-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-md bg-soft-primary rounded-circle me-3 d-flex justify-content-center align-items-center">
                        <span class="avatar-title bg-transparent text-primary font-size-24">
                            <i class="fas fa-calendar-alt"></i>
                        </span>
                    </div>
                    <div>
                        <p class="text-muted mb-0">Total Leaves</p>
                        <h5 class="mb-0">{{ $statistics['total'] }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bgs-card common-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-md bg-soft-success rounded-circle me-3 d-flex justify-content-center align-items-center">
                        <span class="avatar-title bg-transparent text-success font-size-24">
                            <i class="fas fa-check-circle"></i>
                        </span>
                    </div>
                    <div>
                        <p class="text-muted mb-0">Approved</p>
                        <h5 class="mb-0">{{ $statistics['approved'] }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bgs-card common-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-md bg-soft-warning rounded-circle me-3 d-flex justify-content-center align-items-center">
                        <span class="avatar-title bg-transparent text-warning font-size-24">
                            <i class="fas fa-clock"></i>
                        </span>
                    </div>
                    <div>
                        <p class="text-muted mb-0">Pending</p>
                        <h5 class="mb-0">{{ $statistics['pending'] }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bgs-card common-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-md bg-soft-info rounded-circle me-3 d-flex justify-content-center align-items-center">
                        <span class="avatar-title bg-transparent text-info font-size-24">
                            <i class="fas fa-calendar-day"></i>
                        </span>
                    </div>
                    <div>
                        <p class="text-muted mb-0">Total Days</p>
                        <h5 class="mb-0">{{ $statistics['total_days'] }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}

<!-- Additional Filters -->
{{-- <div class="card bgs-card mb-3 common-card pb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-2">
                <label class="form-label">Filter by User</label>
                <select wire:model.live="filterUserId" class="form-select">
                    <option value="">All Users</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Filter by Leave Type</label>
                <select wire:model.live="filterLeaveType" class="form-select">
                    <option value="">All Types</option>
                    @foreach ($leaveTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Filter by Status</label>
                <select wire:model.live="filterStatus" class="form-select">
                    <option value="">All Status</option>
                    <option value="1">Approved</option>
                    <option value="0">Pending/Rejected</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Date From</label>
                <input type="date" wire:model.live="filterDateFrom" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label">Date To</label>
                <input type="date" wire:model.live="filterDateTo" class="form-control">
            </div>
            <div class="col-md-2">
                <div class="h-100 d-flex align-items-center mt-3 justify-content-center">
                    <button wire:click="clearFilters" class="btn theme-filled-btn w-100">
                        Clear Filters
                    </button>
                </div>
            </div>
        </div>
    </div>
</div> --}}

<!-- Data Table -->
<x-data-table 
    title="Leave Management"
    :createRoute="route('leave.create')"
    createButtonText="Add Leave Request"
    searchPlaceholder="Search by employee name, reason..."
    :items="$leaveRecords"
    :isPageHeader="true"
    :showSearch="true"
    :showExport="true"
    :showPagination="true"
    :showPerPage="true"
    :perPageOptions="[10, 20, 30, 50, 100]"
    :headers="['#', 'Employee', 'Leave Type', 'Reason', 'From Date', 'To Date', 'Days', 'Actions']"
    :sortableHeaders="['id', 'user_id', 'leave_type_id', 'leave_reason', 'date_from', 'date_to', 'total_days', null]"
>
    @forelse($leaveRecords as $index => $leave)
        <tr>
            <td style="width: 50px;">{{ $leaveRecords->firstItem() + $index }}</td>
            <td style="min-width: 180px;">
                <div class="d-flex align-items-center">
                    <div class="avatar-xs rounded-circle bg-soft-primary me-2 d-flex justify-content-center align-items-center">
                        <span class="text-primary font-weight-bold">{{ substr($leave->user?->name ?? 'N/A', 0, 1) }}</span>
                    </div>
                    <div>
                        <h6 class="mb-0 text-truncate" style="max-width: 120px;">{{ $leave->user?->name ?? 'N/A' }}</h6>
                        <small class="text-muted">{{ ucfirst($leave->user?->user_type ?? 'N/A') }}</small>
                    </div>
                </div>
            </td>
            <td style="min-width: 120px;">
                <span class="badge bg-secondary">{{ $leave->leaveType?->name ?? 'N/A' }}</span>
            </td>
            <td style="min-width: 150px;">
                <span class="text-truncate d-inline-block" style="max-width: 120px;" title="{{ $leave->leave_reason }}">
                    {{ Str::limit($leave->leave_reason, 25) }}
                </span>
            </td>
            <td style="min-width: 100px;">{{ $leave->date_from?->format('M d, Y') ?? 'N/A' }}</td>
            <td style="min-width: 100px;">{{ $leave->date_to?->format('M d, Y') ?? 'N/A' }}</td>
            <td style="width: 80px;">
                <span class="badge bg-info">{{ $leave->total_days ?? 0 }}d</span>
            </td>
            <td style="width: 100px;">
                <div class="action-items d-flex gap-2">
                    @if($leave->attachment)
                        <button wire:click="downloadAttachment({{ $leave->id }})" class="btn btn-sm btn-outline-primary" title="Download">
                            <i class="fas fa-download"></i>
                        </button>
                    @endif
                    <span href="{{ route('leave.edit', $leave->id) }}" wire:navigate title="Edit">
                        <i class="fa fa-edit"></i>
                    </span>
                    @php
                        $user = Auth::user();
                        $isAdmin = $user && ($user->hasRole('admin') || $user->user_type === 'admin');
                    @endphp
                    @if($isAdmin || $leave->created_by === Auth::user()->name)
                        <span wire:click="delete({{ $leave->id }})" 
                              wire:confirm="Are you sure you want to delete this leave record?" 
                              title="Delete" style="cursor: pointer;">
                            <i class="fa fa-trash"></i>
                        </span>
                    @endif
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="8" class="text-danger text-center">No Leave Records Found</td>
        </tr>
    @endforelse
</x-data-table>
</div>

@push('styles')
    <style>
        .bgs-card {
            border: none;
            box-shadow: none;
            border-radius: 0.5rem;
        }

        .table th {
            border-top: none;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
            white-space: nowrap;
        }

        .badge {
            font-size: 0.7rem;
            padding: 0.4em 0.6em;
        }

        .avatar-xs {
            height: 2rem;
            width: 2rem;
            flex-shrink: 0;
        }

        .avatar-md {
            height: 2.5rem;
            width: 2.5rem;
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

        .text-truncate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Enhanced table responsive behavior */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-responsive table {
            min-width: 800px; /* Minimum width to trigger horizontal scroll */
        }

        /* Action items styling */
        .action-items {
            white-space: nowrap;
        }

        .action-items > * {
            margin-right: 0.5rem;
        }

        .action-items > *:last-child {
            margin-right: 0;
        }

        /* Form switch styling */
        .form-check-input:checked {
            background-color: #198754;
            border-color: #198754;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .table-responsive table {
                min-width: 600px;
            }
            
            .badge {
                font-size: 0.6rem;
                padding: 0.3em 0.5em;
            }
            
            .avatar-xs {
                height: 1.5rem;
                width: 1.5rem;
            }
        }
    </style>
@endpush

<!-- @push('scripts')
    <script>
        // Initialize DataTable for better UX
        document.addEventListener('DOMContentLoaded', function() {
            // Add tooltips
            $('[title]').tooltip();
        });

        // Re-initialize after Livewire updates
        document.addEventListener('livewire:navigated', function() {
            setTimeout(() => {
                $('[title]').tooltip();
            }, 100);

            $('#leaveTable').DataTable({
                stateSave: true,
                fixedHeader: true,
                buttons: [
                    'copy', 'excel', 'pdf'
                ],
                layout: {
                    topStart: 'buttons'
                }
            });
        });
    </script>
@endpush -->
