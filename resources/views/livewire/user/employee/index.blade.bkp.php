<x-data-table 
    title="Employees"
    :create-route="route('add-employee')"
    create-button-text="CREATE NEW EMPLOYEE"
    :headers="['#', 'Employee Name', 'Email', 'Phone', 'Designation', 'Joining Date', 'Status', 'Actions']"
    :sortable-headers="['id', 'name', 'email', 'phone', null, null, 'is_active', 'created_at']"
    :items="$employees"
    table-id="employees-table"
    search-placeholder="Search employees..."
    :show-export="true"
>
    @forelse($employees as $employee)
        <tr>
            <td>{{ $loop->iteration + ($employees->currentPage() - 1) * $employees->perPage() }}</td>
            <td>
                <div class="d-flex align-items-center">
                    <div class="avatar-sm me-2">
                        <div class="avatar-title bg-light text-primary rounded-circle">
                            <i class="fas fa-user-tie"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $employee->name }}</h6>
                        <small class="text-muted">ID: {{ $employee->id }}</small>
                    </div>
                </div>
            </td>
            <td>
                @if($employee->email)
                    <span class="badge bg-info">{{ $employee->email }}</span>
                @else
                    <span class="text-muted">-</span>
                @endif
            </td>
            <td>
                @if($employee->phone)
                    <span class="badge bg-secondary">{{ $employee->phone }}</span>
                @else
                    <span class="text-muted">-</span>
                @endif
            </td>
            <td>
                @if($employee->employee?->designation)
                    <span class="badge bg-warning text-dark">{{ $employee->employee->designation->name }}</span>
                @else
                    <span class="text-muted">No Designation</span>
                @endif
            </td>
            <td>
                @if($employee->employee?->joining_date)
                    <small>{{ \Carbon\Carbon::parse($employee->employee->joining_date)->format('M d, Y') }}</small>
                @else
                    <span class="text-muted">-</span>
                @endif
            </td>
            <td>
                @if($employee->is_active)
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-danger">Inactive</span>
                @endif
            </td>
            <td>
                <div class="btn-group" role="group">
                    <a wire:navigate href="{{ route('edit-employee', ['id' => $employee->id]) }}" class="btn btn-sm btn-primary me-1" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a wire:navigate href="{{ route('view-employee', ['id' => $employee->id]) }}" class="btn btn-sm btn-info me-1" title="View">
                        <i class="fas fa-eye"></i>
                    </a>
                    <button wire:confirm="Are you sure you want to delete this employee?" wire:click="deleteEmployee({{ $employee->id }})" class="btn btn-sm btn-danger" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="8" class="text-center py-4">
                <div class="text-muted">
                    <i class="fas fa-user-tie fa-3x mb-3"></i>
                    <h5>No employees found</h5>
                    <p>Try adjusting your search criteria or create a new employee.</p>
                </div>
            </td>
        </tr>
    @endforelse
</x-data-table>
