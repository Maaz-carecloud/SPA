<x-data-table 
    title="Parents"
    :create-route="route('add-parent')"
    create-button-text="CREATE NEW PARENT"
    :headers="['#', 'Parent Name', 'Email', 'Phone', 'Address', 'Children', 'Status', 'Actions']"
    :sortable-headers="['id', 'name', 'email', 'phone', 'address', null, 'is_active', 'created_at']"
    :items="$parents"
    table-id="parents-table"
    search-placeholder="Search parents..."
    :show-export="true"
>
    @forelse($parents as $parent)
        <tr>
            <td>{{ $loop->iteration + ($parents->currentPage() - 1) * $parents->perPage() }}</td>
            <td>
                <div class="d-flex align-items-center">
                    <div class="avatar-sm me-2">
                        <div class="avatar-title bg-light text-primary rounded-circle">
                            <i class="fas fa-user-friends"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $parent->name }}</h6>
                        <small class="text-muted">ID: {{ $parent->id }}</small>
                    </div>
                </div>
            </td>
            <td>
                @if($parent->email)
                    <span class="badge bg-info">{{ $parent->email }}</span>
                @else
                    <span class="text-muted">-</span>
                @endif
            </td>
            <td>
                @if($parent->phone)
                    <span class="badge bg-secondary">{{ $parent->phone }}</span>
                @else
                    <span class="text-muted">-</span>
                @endif
            </td>
            <td>
                <small>{{ $parent->address ?? '-' }}</small>
            </td>
            <td>
                @if($parent->parent?->students && $parent->parent->students->count())
                    @foreach($parent->parent->students as $student)
                        <span class="badge bg-warning text-dark me-1">{{ $student->user->name ?? 'Student' }}</span>
                    @endforeach
                @else
                    <span class="text-muted">No Children</span>
                @endif
            </td>
            <td>
                @if($parent->is_active)
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-danger">Inactive</span>
                @endif
            </td>
            <td>
                <div class="btn-group" role="group">
                    <a wire:navigate href="{{ route('edit-parent', ['id' => $parent->id]) }}" class="btn btn-sm btn-primary" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a wire:navigate href="{{ route('view-parent', ['id' => $parent->id]) }}" class="btn btn-sm btn-info" title="View">
                        <i class="fas fa-eye"></i>
                    </a>
                    <button wire:confirm="Are you sure you want to delete this parent?" wire:click="deleteParent({{ $parent->id }})" class="btn btn-sm btn-danger" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="8" class="text-center py-4">
                <div class="text-muted">
                    <i class="fas fa-user-friends fa-3x mb-3"></i>
                    <h5>No parents found</h5>
                    <p>Try adjusting your search criteria or create a new parent.</p>
                </div>
            </td>
        </tr>
    @endforelse
</x-data-table>
