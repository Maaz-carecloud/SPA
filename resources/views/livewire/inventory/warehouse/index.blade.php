<x-data-table 
    title="Warehouses"
    :createRoute="route('add-warehouse')"
    createButtonText="Create New Warehouse"
    searchPlaceholder="Search by Warehouse Name..."
    :items="$warehouses"
    :isPageHeader="true"
    :showSearch="true"
    :showExport="true"
    :showPagination="true"
    :showPerPage="true"
    :perPageOptions="[10, 20, 30, 50, 100]"
    :headers="['#', 'Warehouse Name', 'Code', 'Email', 'Contact', 'Address', 'Status', 'Date Added', 'Actions']"
    :sortableHeaders="['id', 'name', 'code', 'email', 'contact', 'address', null, 'created_at', null]"
>
        @forelse($warehouses as $index => $warehouse)
            <tr>
                <td>{{ $warehouses->firstItem() + $index }}</td>
                <td>{{ $warehouse->name ?? 'N/A' }}</td>
                <td>{{ $warehouse->code ?? 'N/A' }}</td>
                <td>{{ $warehouse->email ?? 'N/A' }}</td>
                <td>{{ $warehouse->phone ?? 'N/A' }}</td>
                <td>{{ $warehouse->address ?? 'N/A' }}</td>
                <td>
                    <span class="badge {{ $warehouse->is_active ? 'bg-success' : 'bg-danger' }}">
                        {{ $warehouse->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>{{ $warehouse->created_at ? $warehouse->created_at->format('Y-m-d') : 'N/A' }}</td>
                <td>
                    <div class="action-items">
                        <span href="{{ route('view-warehouse', $warehouse->id) }}" wire:navigate>
                            <i class="fa fa-eye"></i>
                        </span>
                        <span href="{{ route('edit-warehouse', $warehouse->id) }}" wire:navigate>
                            <i class="fa fa-edit"></i>
                        </span>
                        <span wire:click="delete({{ $warehouse->id }})" wire:confirm="Are you sure you want to delete this warehouse?">
                            <i class="fa fa-trash"></i>
                        </span>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-danger text-center">No Warehouses Found</td>
            </tr>
        @endforelse
</x-data-table>
  
    
