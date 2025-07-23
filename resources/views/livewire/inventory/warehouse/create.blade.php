<div>
    <div class="row">
        <div class="col-xl-12 mb-3">
            <div class="card bgs-card h-100">
                <div class="card-header d-flex justify-content-between mb-3">
                    <h6 class="card-title"><i class="fas fa-plus-circle me-2"></i>Add New Warehouse</h6>
                    <a wire:navigate href='{{ route('warehouses') }}' class="btn btn-sm btn-primary btn-rounded-sm">
                        <i class="fas fa-arrow-left me-1"></i>Go Back
                    </a>
                </div>
                <div class="card-body">
                    @if (session()->has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form wire:submit.prevent="createWarehouse">
                        <!-- Basic Information Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-muted border-bottom pb-2 mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Basic Information
                                </h6>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label fw-semibold">
                                        <i class="fas fa-warehouse me-1"></i>Warehouse Name <span
                                            class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="name" wire:model="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="Enter warehouse name">
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="code" class="form-label fw-semibold">
                                        <i class="fas fa-qrcode me-1"></i>Warehouse Code <span
                                            class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="code" wire:model="code"
                                        class="form-control @error('code') is-invalid @enderror"
                                        placeholder="Enter warehouse code">
                                    @error('code')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-muted border-bottom pb-2 mb-3">
                                    <i class="fas fa-address-book me-2"></i>Contact Information
                                </h6>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-semibold">
                                        <i class="fas fa-envelope me-1"></i>Email Address
                                    </label>
                                    <input type="email" id="email" wire:model="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        placeholder="Enter email address">
                                    @error('email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label fw-semibold">
                                        <i class="fas fa-phone me-1"></i>Phone Number
                                    </label>
                                    <input type="text" id="phone" wire:model="phone"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        placeholder="Enter phone number">
                                    @error('phone')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Location & Status Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-muted border-bottom pb-2 mb-3">
                                    <i class="fas fa-map-marker-alt me-2"></i>Location & Status
                                </h6>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="address" class="form-label fw-semibold">
                                        <i class="fas fa-map me-1"></i>Address
                                    </label>
                                    <textarea id="address" wire:model="address" rows="4" class="form-control @error('address') is-invalid @enderror"
                                        placeholder="Enter warehouse address"></textarea>
                                    @error('address')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="is_active" class="form-label fw-semibold">
                                        <i class="fas fa-toggle-on me-1"></i>Status <span class="text-danger">*</span>
                                    </label>
                                    <select id="is_active" wire:model="is_active"
                                        class="form-select @error('is_active') is-invalid @enderror">
                                        <option value="">Select Status</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    @error('is_active')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                                    <a href="{{ route('warehouses') }}" wire:navigate class="btn btn-secondary">
                                        <i class="fas fa-times me-1"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save me-1"></i>Save Warehouse
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
