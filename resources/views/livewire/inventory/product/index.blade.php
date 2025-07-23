<x-data-table 
    title="Products"
    :createRoute="route('add-product')"
    createButtonText="Create New Product"
    searchPlaceholder="Search by Product Name..."
    :items="$products"
    :isPageHeader="true"
    :showSearch="true"
    :showExport="true"
    :showPagination="true"
    :showPerPage="true"
    :perPageOptions="[10, 20, 30, 50, 100]"
    :headers="['#', 'Product Name', 'Barcode', 'Category', 'Buy Price', 'Sell Price', 'Stock', 'Status', 'Date Added', 'Actions']"
    :sortableHeaders="['id', 'name', 'barcode', null, 'buying_price', 'selling_price', 'quantity', null, 'created_at', null]"
>
        @forelse($products as $index => $product)
            <tr>
                <td>{{ $products->firstItem() + $index }}</td>
                <td>{{ $product->name ?? 'N/A' }}</td>
                <td>{{ $product->barcode ?? 'N/A' }}</td>
                <td>{{ $product->category->name ?? 'N/A' }}</td>
                <td>${{ number_format($product->buying_price ?? 0, 2) }}</td>
                <td>${{ number_format($product->selling_price ?? 0, 2) }}</td>
                <td>{{ $product->quantity ?? 'N/A' }}</td>
                <td>
                    <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-danger' }}">
                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>{{ $product->created_at ? $product->created_at->format('Y-m-d') : 'N/A' }}</td>
                <td>
                    <div class="action-items">
                        <span href="{{ route('view-product', $product->id) }}" wire:navigate>
                            <i class="fa fa-eye"></i>
                        </span>
                        <span href="{{ route('edit-product', $product->id) }}" wire:navigate>
                            <i class="fa fa-edit"></i>
                        </span>
                        <span wire:click="delete({{ $product->id }})" wire:confirm="Are you sure you want to delete this product?">
                            <i class="fa fa-trash"></i>
                        </span>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="10" class="text-danger text-center">No Products Found</td>
            </tr>
        @endforelse
</x-data-table>
  
    