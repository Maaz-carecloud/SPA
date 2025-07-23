<div>
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h3 class="mb-2">{{ $product->name }}</h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('products') }}" wire:navigate class="text-decoration-none text-primary">
                                    <i class="fas fa-box me-1"></i>Products
                                </a>
                            </li>
                            <li class="breadcrumb-item active">{{ $product->name }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('edit-product', $product->id) }}" wire:navigate class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Edit
                    </a>
                    <a href="{{ route('products') }}" wire:navigate class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Main Content -->
    <div class="row g-4">
        <!-- Product Details -->
        <div class="col-lg-8">
            <div class="card bgs-card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i>Product Details</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <h4 class="mb-3">{{ $product->name }}</h4>
                            <div class="mb-3">
                                @if($product->barcode)
                                    <span class="badge bg-secondary me-2">
                                        <i class="fas fa-barcode me-1"></i>{{ $product->barcode }}
                                    </span>
                                @endif

                                @if($product->quantity <= 0)
                                    <span class="badge bg-danger">
                                        <i class="fas fa-exclamation-triangle me-1"></i>Out of Stock
                                    </span>
                                @elseif($product->quantity <= 10)
                                    <span class="badge bg-warning">
                                        <i class="fas fa-exclamation-triangle me-1"></i>Low Stock
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>In Stock
                                    </span>
                                @endif
                            </div>
                            <p class="text-muted">Product ID: <strong>#{{ $product->id }}</strong></p>
                        </div>
                        <div class="col-md-4 text-center">
                            @if($product->image)
                                <img src="{{ Storage::url($product->image) }}" 
                                        alt="{{ $product->name }}" 
                                        class="img-fluid rounded shadow-sm"
                                        style="max-width: 150px; max-height: 150px; object-fit: cover;">
                            @else
                                <div class="placeholder-image d-flex align-items-center justify-content-center bg-light rounded"
                                        style="width: 150px; height: 150px; margin: 0 auto;">
                                    <div class="text-center">
                                        <i class="fas fa-image text-muted fa-2x mb-2"></i>
                                        <p class="text-muted small mb-0">No Image</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Information Grid -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 border rounded">
                                <label class="text-muted small mb-1">Category</label>
                                <div class="fw-semibold">{{ $product->category->name ?? 'No Category' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 border rounded">
                                <label class="text-muted small mb-1">Stock Quantity</label>
                                <div class="fw-semibold">{{ number_format($product->quantity) }} units</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 border rounded border-danger bg-danger bg-opacity-10">
                                <label class="text-muted small mb-1">Buying Price</label>
                                <div class="fw-semibold text-danger fs-5">₱{{ number_format($product->buying_price, 2) }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 border rounded border-success bg-success bg-opacity-10">
                                <label class="text-muted small mb-1">Selling Price</label>
                                <div class="fw-semibold text-success fs-5">₱{{ number_format($product->selling_price, 2) }}</div>
                            </div>
                        </div>
                        @if($product->description)
                            <div class="col-12">
                                <div class="p-3 border rounded">
                                    <label class="text-muted small mb-2">Description</label>
                                    <div>{!! $product->description !!}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Inventory Status -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fas fa-warehouse me-2"></i>Inventory Status</h5>
                </div>
                <div class="card-body text-center">
                    @if($product->quantity <= 0)
                        <div class="mb-3">
                            <i class="fas fa-exclamation-triangle fa-3x text-danger"></i>
                        </div>
                        <h5 class="text-danger">Out of Stock</h5>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>Product needs restocking immediately
                        </div>
                    @elseif($product->quantity <= 10)
                        <div class="mb-3">
                            <i class="fas fa-exclamation-triangle fa-3x text-warning"></i>
                        </div>
                        <h5 class="text-warning">Low Stock</h5>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>Consider restocking soon
                        </div>
                    @else
                        <div class="mb-3">
                            <i class="fas fa-check-circle fa-3x text-success"></i>
                        </div>
                        <h5 class="text-success">In Stock</h5>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>Stock levels are adequate
                        </div>
                    @endif
                    <p class="text-muted mt-3 mb-0">
                        <strong>{{ number_format($product->quantity) }}</strong> units available
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- @push('styles')
<style>
    /* Clean minimal styles */
    .card {
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: box-shadow 0.15s ease-in-out;
    }

    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        font-weight: 600;
    }

    .btn {
        transition: all 0.2s ease-in-out;
    }

    .btn:hover {
        transform: translateY(-1px);
    }

    .description-content {
        max-height: 200px;
        overflow-y: auto;
    }    
    
    /* Fix: Properly separate print media query */
    @media print {
        .btn, .card-header, .breadcrumb {
            display: none !important;
        }
        
        .card {
            border: 1px solid #000 !important;
            box-shadow: none !important;
            break-inside: avoid;
        }
    }
    
    /* Responsive improvements for small screens */
    @media (max-width: 767px) {
        .container-xxl {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }
        
        .col-md-6 {
            margin-bottom: 1rem;
        }
    }

    @media (min-width: 992px) {
        .col-lg-8 {
            width: 66.666667%;
        }
        .col-lg-4 {
            width: 33.333333%;
        }
    }
</style>
@endpush --}}
{{-- @push('scripts')
    <script>
    // Simple Share Function
    function shareProduct() {
        const productData = {
            title: '{{ $product->name }}',
            text: 'Check out this product: {{ $product->name }} - ₱{{ number_format($product->selling_price, 2) }}',
            url: window.location.href
        };

        if (navigator.share) {
            navigator.share(productData)
                .then(() => showAlert('Product shared successfully!', 'success'))
                .catch(err => console.error('Error sharing:', err));
        } else {
            // Fallback - copy to clipboard
            navigator.clipboard.writeText(window.location.href)
                .then(() => showAlert('Link copied to clipboard!', 'success'))
                .catch(() => showAlert('Could not share product', 'error'));
        }
    }

    // Export to CSV Function
    function exportToExcel() {
        const productData = [
            ['Field', 'Value'],
            ['Product Name', '{{ $product->name }}'],
            ['Category', '{{ $product->category->name ?? "No Category" }}'],
            ['Barcode', '{{ $product->barcode ?? "N/A" }}'],
            ['Buying Price', '₱{{ number_format($product->buying_price, 2) }}'],
            ['Selling Price', '₱{{ number_format($product->selling_price, 2) }}'],
            ['Quantity', '{{ $product->quantity }}'],
            ['Profit per Unit', '₱{{ number_format($profit, 2) }}'],
            ['Profit Margin', '{{ number_format($profitMargin, 1) }}%'],
            ['Total Stock Value', '₱{{ number_format($product->selling_price * $product->quantity, 2) }}'],
            ['Potential Profit', '₱{{ number_format($profit * $product->quantity, 2) }}'],
            ['Created', '{{ $product->created_at->format("F j, Y g:i A") }}'],
            ['Last Updated', '{{ $product->updated_at->format("F j, Y g:i A") }}']
        ];

        const csv = productData.map(row => row.map(field => `"${field}"`).join(',')).join('\n');
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `product-{{ $product->id }}-{{ str_replace(' ', '-', $product->name) }}.csv`;
        a.click();
        window.URL.revokeObjectURL(url);
        
        showAlert('Product data exported successfully!', 'success');
    }

   // Simple Alert Function
function showAlert(message, type = 'info') {
    const alertClass = type === 'success' ? 'alert-success' : type === 'error' ? 'alert-danger' : 'alert-info';
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
             style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', alertHtml);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        const alert = document.querySelector('.alert:last-of-type');
        if (alert) alert.remove();
    }, 5000);
}
    </script>
@endpush --}}



