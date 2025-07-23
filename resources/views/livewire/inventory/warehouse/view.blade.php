<div>
    <div class="row">
        <div class="col-xl-12 mb-3">
            <div class="card bgs-card h-100">
                <!-- Header Section -->
                <div class="card-header d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="card-title mb-1">
                            <i class="fas fa-warehouse me-2"></i>{{ $warehouse->name }}
                        </h6>
                        <small class="text-muted">Warehouse Details & Information</small>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('edit-warehouse', $warehouse->id) }}" wire:navigate
                            class="btn btn-sm btn-primary">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <a href="{{ route('warehouses') }}" wire:navigate class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Back to List
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Left Column - Warehouse Details -->
                        <div class="col-lg-8">
                            <!-- Basic Information Card -->
                            <div class="card bgs-card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-info-circle me-2"></i>Warehouse Information
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold text-muted">Warehouse Name</label>
                                            <p class="fs-5 fw-bold text-dark mb-0">{{ $warehouse->name }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold text-muted">Warehouse Code</label>
                                            <p class="fs-5 fw-bold text-dark mb-0">
                                                @if ($warehouse->code)
                                                    <span class="badge bg-primary">{{ $warehouse->code }}</span>
                                                @else
                                                    <span class="text-muted">Not assigned</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold text-muted">Email Address</label>
                                            <p class="fs-6 text-dark mb-0">
                                                @if ($warehouse->email)
                                                    <a href="mailto:{{ $warehouse->email }}"
                                                        class="text-decoration-none">
                                                        <i class="fas fa-envelope me-1"></i>{{ $warehouse->email }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">Not provided</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold text-muted">Phone Number</label>
                                            <p class="fs-6 text-dark mb-0">
                                                @if ($warehouse->phone)
                                                    <a href="tel:{{ $warehouse->phone }}" class="text-decoration-none">
                                                        <i class="fas fa-phone me-1"></i>{{ $warehouse->phone }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">Not provided</span>
                                                @endif
                                            </p>
                                        </div>
                                        @if ($warehouse->address)
                                            <div class="col-12 mb-3">
                                                <label class="form-label fw-semibold text-muted">Address</label>
                                                <p class="text-dark mb-0">
                                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $warehouse->address }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Record Information Card -->
                            <div class="card bgs-card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-history me-2"></i>Record Information
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold text-muted">Created By</label>
                                            <p class="text-dark mb-0">{{ $warehouse->created_by ?? 'System' }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold text-muted">Created Date</label>
                                            <p class="text-dark mb-0">
                                                {{ $warehouse->created_at->format('M d, Y g:i A') }}</p>
                                        </div>
                                        @if ($warehouse->updated_at != $warehouse->created_at)
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold text-muted">Last Updated By</label>
                                                <p class="text-dark mb-0">{{ $warehouse->updated_by ?? 'System' }}
                                                </p>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold text-muted">Last Updated</label>
                                                <p class="text-dark mb-0">
                                                    {{ $warehouse->updated_at->format('M d, Y g:i A') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Quick Actions & Stats -->
                        <div class="col-lg-4">
                            <!-- Warehouse Stats Card -->
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-chart-bar me-2"></i>Warehouse Statistics
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-12 mb-3">
                                            <div class="bg-light p-3 rounded">
                                                <h5 class="text-primary mb-1">{{ $warehouse->id }}</h5>
                                                <small class="text-muted">Warehouse ID</small>
                                            </div>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <div class="bg-light p-3 rounded">
                                                <h5 class="text-success mb-1">
                                                    {{ $warehouse->created_at->diffForHumans() }}</h5>
                                                <small class="text-muted">Since Added</small>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="bg-light p-3 rounded">
                                                <h5 class="text-info mb-1">
                                                    @if ($warehouse->is_active)
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-danger">Inactive</span>
                                                    @endif
                                                </h5>
                                                <small class="text-muted">Status</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
