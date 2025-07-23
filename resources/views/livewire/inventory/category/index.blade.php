<x-data-table 
    title="Categories"
    :createRoute="route('add-category')"
    createButtonText="Create New Category"
    searchPlaceholder="Search by Category Name..."
    :items="$categories"
    :isPageHeader="true"
    :showSearch="true"
    :showExport="true"
    :showPagination="true"
    :showPerPage="true"
    :perPageOptions="[10, 20, 30, 50, 100]"
    :headers="['#', 'Category Name', 'Description', 'Created By', 'Updated By', 'Status', 'Date Added', 'Actions']"
    :sortableHeaders="['id', 'name', 'description', 'created_by', 'updated_by', null, 'created_at', null]"
>
        @forelse($categories as $index => $category)
            <tr>
                <td>{{ $categories->firstItem() + $index }}</td>
                <td>{{ $category->name ?? 'N/A' }}</td>
                <td>{{ $category->description ?? 'N/A' }}</td>
                <td>{{ $category->created_by ?? 'N/A' }}</td>
                <td>{{ $category->updated_by ?? 'N/A' }}</td>
                <td>
                    <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-danger' }}">
                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>{{ $category->created_at ? $category->created_at->format('Y-m-d') : 'N/A' }}</td>
                <td>
                    <div class="action-items">
                        <span href="{{ route('edit-category', $category->id) }}" wire:navigate>
                            <i class="fa fa-edit"></i>
                        </span>
                        <span wire:click="delete({{ $category->id }})" wire:confirm="Are you sure you want to delete this category?">
                            <i class="fa fa-trash"></i>
                        </span>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-danger text-center">No Categories Found</td>
            </tr>
        @endforelse
</x-data-table>
  
    