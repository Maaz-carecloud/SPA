<div>
    @if($step === 1)
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card bgs-card shadow border-0 mt-4">
                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0">Employee User Information</h5>
                </div>
                <div class="card-body p-4">
                    <form wire:submit.prevent="nextStep">
                        <h6 class="mb-3 text-secondary">Basic Information</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="user_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" id="user_name" wire:model.lazy="user_name" class="form-control @error('user_name') is-invalid @enderror" placeholder="Enter full name">
                                @error('user_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="user_email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" id="user_email" wire:model.lazy="user_email" class="form-control @error('user_email') is-invalid @enderror" placeholder="Enter email">
                                @error('user_email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label for="user_username" class="form-label">Username <span class="text-danger">*</span></label>
                                <input type="text" id="user_username" wire:model.lazy="user_username" class="form-control @error('user_username') is-invalid @enderror" placeholder="Enter username">
                                @if(!is_null($username_available))
                                    @if($username_available)
                                        <span class="text-success small">Username is available</span>
                                    @else
                                        <span class="text-danger small">Username is already taken</span>
                                    @endif
                                @endif
                                @error('user_username') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="user_password" class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" id="user_password" wire:model.lazy="user_password" class="form-control @error('user_password') is-invalid @enderror" placeholder="Enter password">
                                @error('user_password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <input type="hidden" wire:model="user_type" value="employee">
                        <h6 class="mt-4 mb-3 text-secondary">Personal Details</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="user_dob" class="form-label">Date of Birth</label>
                                <input type="date" id="user_dob" wire:model.lazy="user_dob" class="form-control @error('user_dob') is-invalid @enderror">
                                @error('user_dob') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="user_gender" class="form-label">Gender</label>
                                <select id="user_gender" wire:model.lazy="user_gender" class="form-control @error('user_gender') is-invalid @enderror">
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                                @error('user_gender') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="user_phone" class="form-label">Phone</label>
                                <input type="text" id="user_phone" wire:model.lazy="user_phone" class="form-control @error('user_phone') is-invalid @enderror" placeholder="Enter phone">
                                @error('user_phone') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="row g-3 mt-2">
                            <div class="col-md-4">
                                <label for="user_cnic" class="form-label">CNIC</label>
                                <input type="text" id="user_cnic" wire:model.lazy="user_cnic" class="form-control @error('user_cnic') is-invalid @enderror" placeholder="xxxxx-xxxxxxx-x">
                                @error('user_cnic') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="user_blood_group" class="form-label">Blood Group</label>
                                <input type="text" id="user_blood_group" wire:model.lazy="user_blood_group" class="form-control @error('user_blood_group') is-invalid @enderror" placeholder="A+">
                                @error('user_blood_group') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="user_registration_no" class="form-label">Registration No</label>
                                <input type="text" id="user_registration_no" wire:model.lazy="user_registration_no" class="form-control @error('user_registration_no') is-invalid @enderror" placeholder="Registration No">
                                @error('user_registration_no') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="row g-3 mt-2">
                            <div class="col-md-4">
                                <label for="user_religion" class="form-label">Religion</label>
                                <input type="text" id="user_religion" wire:model.lazy="user_religion" class="form-control @error('user_religion') is-invalid @enderror" placeholder="Religion">
                                @error('user_religion') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-8">
                                <label for="user_address" class="form-label">Address</label>
                                <input type="text" id="user_address" wire:model.lazy="user_address" class="form-control @error('user_address') is-invalid @enderror" placeholder="Address">
                                @error('user_address') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <h6 class="mt-4 mb-3 text-secondary">Location & Other</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="user_country" class="form-label">Country</label>
                                <input type="text" id="user_country" wire:model.lazy="user_country" class="form-control @error('user_country') is-invalid @enderror" placeholder="Country">
                                @error('user_country') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="user_city" class="form-label">City</label>
                                <input type="text" id="user_city" wire:model.lazy="user_city" class="form-control @error('user_city') is-invalid @enderror" placeholder="City">
                                @error('user_city') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="user_state" class="form-label">State</label>
                                <input type="text" id="user_state" wire:model.lazy="user_state" class="form-control @error('user_state') is-invalid @enderror" placeholder="State">
                                @error('user_state') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label for="user_avatar" class="form-label">Avatar</label>
                                <input type="file" id="user_avatar" wire:model="user_avatar" class="form-control @error('user_avatar') is-invalid @enderror">
                                @error('user_avatar') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                @if($user_avatar)
                                    <img src="{{ $user_avatar->temporaryUrl() }}" class="mt-2" width="80">
                                @endif
                            </div>
                            <div class="col-md-3">
                                <label for="user_transport_status" class="form-label">Transport Status</label>
                                <select id="user_transport_status" wire:model.lazy="user_transport_status" class="form-control @error('user_transport_status') is-invalid @enderror">
                                    <option value="">Select</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                                @error('user_transport_status') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="user_transport_id" class="form-label">Transport ID</label>
                                <input type="text" id="user_transport_id" wire:model.lazy="user_transport_id" class="form-control @error('user_transport_id') is-invalid @enderror" placeholder="Transport ID">
                                @error('user_transport_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="row g-3 mt-2">
                            <div class="col-md-3">
                                <label for="user_is_active" class="form-label">Active</label>
                                <select id="user_is_active" wire:model.lazy="user_is_active" class="form-control @error('user_is_active') is-invalid @enderror">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                @error('user_is_active') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary px-4 py-2">Next <i class="bi bi-arrow-right"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
    @if($step === 2)
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card bgs-card shadow border-0 mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Employee Job Information</h5>
                </div>
                <div class="card-body p-4">
                    <form wire:submit.prevent="updateEmployee">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="designation_id" class="form-label">Designation <span class="text-danger">*</span></label>
                                <select id="designation_id" wire:model.lazy="designation_id" class="form-control @error('designation_id') is-invalid @enderror">
                                    <option value="">Select Designation</option>
                                    @foreach($designations as $designation)
                                        <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                                    @endforeach
                                </select>
                                @error('designation_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="joining_date" class="form-label">Joining Date <span class="text-danger">*</span></label>
                                <input type="date" id="joining_date" wire:model.lazy="joining_date" class="form-control @error('joining_date') is-invalid @enderror">
                                @error('joining_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label for="qualification" class="form-label">Qualification</label>
                                <input type="text" id="qualification" wire:model.lazy="qualification" class="form-control @error('qualification') is-invalid @enderror" placeholder="Qualification">
                                @error('qualification') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="basic_salary" class="form-label">Basic Salary <span class="text-danger">*</span></label>
                                <input type="number" id="basic_salary" wire:model.lazy="basic_salary" class="form-control @error('basic_salary') is-invalid @enderror" placeholder="Basic Salary">
                                @error('basic_salary') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-secondary px-4 py-2" wire:click="prevStep"><i class="bi bi-arrow-left"></i> Previous</button>
                            <button type="submit" class="btn btn-success px-4 py-2">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
