<x-data-table 
    title="Suppliers"
    :createRoute="route('add-supplier')"
    createButtonText="Create New Supplier"
    searchPlaceholder="Search by Company Name..."
    :items="$suppliers"
    :isPageHeader="true"
    :showSearch="true"
    :showExport="true"
    :showPagination="true"
    :showPerPage="true"
    :perPageOptions="[10, 20, 30, 50, 100]"
    :headers="['#', 'Company Name', 'Contact Person', 'Email', 'Phone', 'Address', 'Status', 'Date Added', 'Actions']"
    :sortableHeaders="['id', 'company_name', 'contact_person', 'email', 'phone', 'address', null, 'created_at', null]"
>
        @forelse($suppliers as $index => $supplier)
            <tr>
                <td>{{ $suppliers->firstItem() + $index }}</td>
                <td>{{ $supplier->company_name ?? 'N/A' }}</td>
                <td>{{ $supplier->contact_person ?? 'N/A' }}</td>
                <td>{{ $supplier->email ?? 'N/A' }}</td>
                <td>{{ $supplier->phone ?? 'N/A' }}</td>
                <td>{{ $supplier->address ?? 'N/A' }}</td>
                <td>
                    <span class="badge {{ $supplier->is_active ? 'bg-success' : 'bg-danger' }}">
                        {{ $supplier->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>{{ $supplier->created_at ? $supplier->created_at->format('Y-m-d') : 'N/A' }}</td>
                <td>
                    <div class="action-items">
                        <span href="{{ route('view-supplier', $supplier->id) }}" wire:navigate>
                            <i class="fa fa-eye"></i>
                        </span>
                        <span href="{{ route('edit-supplier', $supplier->id) }}" wire:navigate>
                            <i class="fa fa-edit"></i>
                        </span>
                        <span wire:click="delete({{ $supplier->id }})" wire:confirm="Are you sure you want to delete this supplier?">
                            <i class="fa fa-trash"></i>
                        </span>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-danger text-center">No Suppliers Found</td>
            </tr>
        @endforelse
</x-data-table>


@push('styles')
    <style>
        .avatar-sm {
            width: 35px;
            height: 35px;
        }
        .contact-info {
            line-height: 1.3;
        }

        .badge {
            font-size: 0.75rem;
        }

        .btn-group .btn {
            border-radius: 0.375rem;
        }

        .btn-group .btn:not(:last-child) {
            margin-right: 0.25rem;
        }

        .table th {
            border-top: none;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .bgs-card {
            border: none;
            box-shadow: none;
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }

        .table-dark th {
            background-color: #495057 !important;
            color: white !important;
        }

        .table td {
            vertical-align: middle;
        }
    </style>
@endpush
