<div>
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-0">
                <i class="fas fa-eye me-2"></i>Sale Details
            </h4>
            <p class="text-muted mb-0">View sale information and transaction details</p>
        </div>
        <div>
            <a href="{{ route('sales') }}" wire:navigate class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Sales
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Sale Information -->
        <div class="col-lg-8">
            <div class="card bgs-card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Sale Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="border-start border-primary border-4 ps-3">
                                <h6 class="text-primary mb-1">Reference Number</h6>
                                <p class="mb-0 fw-semibold">{{ $sale->reference_no }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border-start border-info border-4 ps-3">
                                <h6 class="text-info mb-1">Sale Date</h6>
                                <p class="mb-0">{{ $sale->sale_date->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border-start border-success border-4 ps-3">
                                <h6 class="text-success mb-1">Customer</h6>
                                <p class="mb-0">{{ $sale->user->name ?? 'N/A' }}</p>
                                <small class="text-muted">{{ $sale->user->role ?? 'Customer' }}</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border-start border-warning border-4 ps-3">
                                <h6 class="text-warning mb-1">Payment Status</h6>
                                <span
                                    class="badge bg-{{ $sale->payment_status === 'paid' ? 'success' : ($sale->payment_status === 'partial' ? 'warning' : 'danger') }}">
                                    {{ ucfirst(str_replace('_', ' ', $sale->payment_status)) }}
                                </span>
                            </div>
                        </div>
                        @if ($sale->description)
                            <div class="col-12">
                                <div class="border-start border-secondary border-4 ps-3">
                                    <h6 class="text-secondary mb-1">Description</h6>
                                    <p class="mb-0">{{ $sale->description }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sale Items -->
            <div class="card bgs-card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-box me-2"></i>Sale Items
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Product</th>
                                    <th width="15%">Quantity</th>
                                    <th width="20%">Unit Price</th>
                                    <th width="20%">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sale->items as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs bg-soft-primary rounded-circle me-2">
                                                    <span class="avatar-title bg-transparent text-primary font-size-12">
                                                        <i class="fas fa-box"></i>
                                                    </span>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $item->product->name ?? 'N/A' }}</h6>
                                                    <small
                                                        class="text-muted">{{ $item->product->product_code ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">{{ number_format($item->quantity, 2) }}</span>
                                            <small
                                                class="text-muted d-block">{{ $item->product->unit ?? 'pcs' }}</small>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">PKR
                                                {{ number_format($item->unit_price, 2) }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-semibold text-success">PKR
                                                {{ number_format($item->quantity * $item->unit_price, 2) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Audit Information -->
            <div class="card bgs-card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>Audit Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border-start border-success border-4 ps-3">
                                <h6 class="text-success mb-1">Created By</h6>
                                <p class="mb-0">{{ $sale->created_by ?? 'N/A' }}</p>
                                <small class="text-muted">{{ $sale->created_at->format('d M Y, h:i A') }}</small>
                            </div>
                        </div>
                        @if ($sale->updated_at != $sale->created_at)
                            <div class="col-md-6">
                                <div class="border-start border-warning border-4 ps-3">
                                    <h6 class="text-warning mb-1">Last Updated By</h6>
                                    <p class="mb-0">{{ $sale->updated_by ?? 'N/A' }}</p>
                                    <small class="text-muted">{{ $sale->updated_at->format('d M Y, h:i A') }}</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary and Actions -->
        <div class="col-lg-4">
            <!-- Sale Summary -->
            <div class="card bgs-card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-calculator me-2"></i>Sale Summary
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span class="fw-semibold">PKR {{ number_format($this->subtotal, 2) }}</span>
                    </div>
                    @if ($sale->discount > 0)
                        <div class="d-flex justify-content-between mb-2">
                            <span>Discount ({{ $sale->discount }}%):</span>
                            <span class="text-success">- PKR {{ number_format($this->discountAmount, 2) }}</span>
                        </div>
                    @endif
                    @if ($sale->tax > 0)
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax ({{ $sale->tax }}%):</span>
                            <span class="text-warning">+ PKR {{ number_format($this->taxAmount, 2) }}</span>
                        </div>
                    @endif
                    <hr class="my-3">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="h6">Grand Total:</span>
                        <span class="h6 text-primary">PKR {{ number_format($grandTotal, 2) }}</span>
                    </div>

                    <!-- Payment Progress -->
                    @php
                        $totalPaid = $sale->payments->sum('paid_amount');
                        $balance = $grandTotal - $totalPaid;
                        $progressPercentage = $grandTotal > 0 ? ($totalPaid / $grandTotal) * 100 : 0;
                    @endphp
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <small class="text-muted">Payment Progress</small>
                            <small class="text-muted">{{ number_format($progressPercentage, 1) }}%</small>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-{{ $progressPercentage >= 100 ? 'success' : ($progressPercentage > 0 ? 'warning' : 'danger') }}"
                                style="width: {{ min($progressPercentage, 100) }}%"></div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Paid:</span>
                        <span class="fw-semibold text-success">PKR {{ number_format($totalPaid, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Balance:</span>
                        <span class="fw-semibold {{ $balance > 0 ? 'text-danger' : 'text-success' }}">
                            PKR {{ number_format($balance, 2) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Sale Status -->
            <div class="card bgs-card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-flag me-2"></i>Sale Status
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Payment Status:</span>
                                <span
                                    class="badge bg-{{ $sale->payment_status === 'fully_paid' ? 'success' : ($sale->payment_status === 'partial_paid' ? 'warning' : 'danger') }}">
                                    {{ ucfirst(str_replace('_', ' ', $sale->payment_status)) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Refund Status:</span>
                                <span
                                    class="badge bg-{{ $sale->refund_status === 'refunded' ? 'warning' : 'success' }}">
                                    {{ ucfirst(str_replace('_', ' ', $sale->refund_status)) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sale Attachments -->
            @if ($sale->sale_slip)
                <div class="card bgs-card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-paperclip me-2"></i>Attachments
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between p-3 border rounded">
                            <div class="d-flex align-items-center">
                                <div class="avatar-xs bg-soft-info rounded me-2">
                                    <span class="avatar-title bg-transparent text-info font-size-16">
                                        <i class="fas fa-file"></i>
                                    </span>
                                </div>
                                <div>
                                    <h6 class="mb-0">Sale Slip</h6>
                                    <small class="text-muted">Uploaded file</small>
                                </div>
                            </div>
                            <a href="{{ Storage::url($sale->sale_slip) }}" target="_blank"
                                class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
    <style>
        @media print {

            .btn,
            .card-header,
            .border-start {
                display: none !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }
        }
    </style>
@endpush
