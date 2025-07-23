<div>
    <!-- Header Section -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-0">
                <i class="fas fa-eye me-2"></i>Purchase Details
            </h4>
            <p class="text-muted mb-0">View complete purchase information</p>
        </div>
        <div>
            <a href="{{ route('purchases.edit', $purchase->id) }}" wire:navigate class="btn btn-warning me-2">
                <i class="fas fa-edit me-1"></i>Edit
            </a>
            <a href="{{ route('purchases') }}" wire:navigate class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Purchases
            </a>
        </div>
    </div>

    

    <!-- Purchase Information -->
    <div class="row">
        <div class="col-12">
            <div class="card bgs-card mb-4">
                <div class="card-body">
                    <!-- Header with Logo and School Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-graduation-cap fa-2x text-warning"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">Bagh Grammar School</h5>
                                    <p class="text-muted mb-0">Purchase Invoice</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p class="mb-1"><strong>Create Date:</strong>
                                {{ \Carbon\Carbon::parse($purchase->created_at)->format('d M Y') }}</p>
                        </div>
                    </div>

                    <!-- Purchase Details -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="mb-3">From</h6>
                            <div class="bg-light p-3 rounded">
                                <h6 class="mb-1">{{ $purchase->supplier->company_name ?? 'N/A' }}</h6>
                                @if ($purchase->supplier->name)
                                    <p class="mb-1">{{ $purchase->supplier->name }}</p>
                                @endif
                                <p class="mb-1">Phone: {{ $purchase->supplier->phone ?? 'N/A' }}</p>
                                <p class="mb-0">Email: {{ $purchase->supplier->email ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">To</h6>
                            <div class="bg-light p-3 rounded">
                                <h6 class="mb-1">Bagh Grammar School</h6>
                                <p class="mb-1">Warehouse Name: {{ $purchase->warehouse->name ?? 'N/A' }}</p>
                                <p class="mb-1">Phone: {{ $purchase->warehouse->phone ?? 'N/A' }}</p>
                                <p class="mb-0">Email: {{ $purchase->warehouse->email ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Reference and Status Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-6">
                                    <strong>Reference No:</strong><br>
                                    <span class="text-primary">{{ $purchase->reference_no }}</span>
                                </div>
                                <div class="col-6">
                                    <strong>Purchase Date:</strong><br>
                                    <span>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-6">
                                    <strong>Payment Status:</strong><br>
                                    @php
                                        $statusClasses = [
                                            'pending' => 'warning',
                                            'partial_paid' => 'info',
                                            'fully_paid' => 'success',
                                        ];
                                        $statusClass = $statusClasses[$purchase->payment_status] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $purchase->payment_status)) }}
                                    </span>
                                </div>
                                <div class="col-6">
                                    <strong>Refund Status:</strong><br>
                                    <span
                                        class="badge bg-{{ $purchase->refund_status === 'refunded' ? 'info' : 'secondary' }}">
                                        {{ ucfirst(str_replace('_', ' ', $purchase->refund_status)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Purchase Items Table -->
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="50%">Description</th>
                                    <th width="15%" class="text-center">Unit Price</th>
                                    <th width="15%" class="text-center">Quantity</th>
                                    <th width="15%" class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchase->purchasedItems as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->product->name ?? 'N/A' }}</td>
                                        <td class="text-center">{{ number_format($item->unit_price, 2) }}</td>
                                        <td class="text-center">{{ number_format($item->quantity, 2) }}</td>
                                        <td class="text-end">
                                            {{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Totals Section -->
                    <div class="row">
                        <div class="col-md-6">
                            @if ($purchase->description)
                                <div class="mb-3">
                                    <h6>Description:</h6>
                                    <p class="text-muted">{{ $purchase->description }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded">
                                <div class="d-flex justify-content-between mb-2">
                                    <span><strong>Total Amount (PKR)</strong></span>
                                    <span class="fw-bold">{{ number_format($this->subtotal, 2) }}</span>
                                </div>

                                @if ($purchase->discount > 0)
                                    <div class="d-flex justify-content-between mb-2">
                                        <span><strong>Discount ({{ $purchase->discount }}%)</strong></span>
                                        <span class="fw-bold">{{ number_format($this->discountAmount, 2) }}</span>
                                    </div>
                                @endif

                                @if ($purchase->tax > 0)
                                    <div class="d-flex justify-content-between mb-2">
                                        <span><strong>Tax ({{ $purchase->tax }}%)</strong></span>
                                        <span class="fw-bold">{{ number_format($this->taxAmount, 2) }}</span>
                                    </div>
                                @endif

                                <div class="d-flex justify-content-between mb-2">
                                    <span><strong>Paid (PKR)</strong></span>
                                    <span class="fw-bold">0.00</span>
                                </div>

                                <hr>

                                <div class="d-flex justify-content-between">
                                    <span><strong>Balance (PKR)</strong></span>
                                    <span class="fw-bold text-primary fs-5">{{ number_format($grandTotal, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Info -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <strong>Create By:</strong><br>
                                <span>{{ $purchase->created_by ?? 'N/A' }}</span><br>
                                <small class="text-muted">Date:
                                    {{ \Carbon\Carbon::parse($purchase->created_at)->format('d M Y') }}</small>
                            </div>
                        </div>
                        @if ($purchase->updated_by && $purchase->updated_at != $purchase->created_at)
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <strong>Last Updated By:</strong><br>
                                    <span>{{ $purchase->updated_by ?? 'N/A' }}</span><br>
                                    <small class="text-muted">Date:
                                        {{ \Carbon\Carbon::parse($purchase->updated_at)->format('d M Y') }}</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
        <script>
            document.addEventListener('livewire:navigated', function() {
                // Add print functionality
                const printBtn = document.querySelector('.btn-primary');
                if (printBtn) {
                    printBtn.addEventListener('click', function() {
                        window.print();
                    });
                }
            });
        </script>
    @endpush

    <style>
        @media print {

            .btn,
            .d-flex.gap-2,
            .mb-4:first-child {
                display: none !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }

            .card-body {
                padding: 0 !important;
            }
        }
    </style>
</div>
