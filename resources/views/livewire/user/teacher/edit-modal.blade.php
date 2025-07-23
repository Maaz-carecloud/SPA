<div wire:ignore.self>
    <x-form.modal 
        id="editTeacherModal" 
        title="Edit Teacher" 
        size="modal-xl" 
        backdrop="static"
        wireCloseMethod="closeModal"
    >
        <form wire:submit.prevent="updateTeacher">
            <h6 class="mb-3 text-theme-primary">Basic Information</h6>
            <div class="row g-3">
                <div class="col-md-3">
                    <x-form.input 
                        label="Full Name"
                        name="userData.name"
                        id="edit-modal-name" 
                        model="lazy:userData.name"
                        placeholder="Enter full name"
                        :required="true"
                        containerClass=""
                        autocomplete="name"
                    />
                </div>
                <div class="col-md-3">
                    <x-form.input 
                        label="Email"
                        name="userData.email"
                        id="edit-modal-email" 
                        type="email"
                        model="lazy:userData.email"
                        placeholder="Enter email"
                        :required="true"
                        containerClass=""
                        autocomplete="email"
                        :readonly="$isEditingSelf"
                        :disabled="$isEditingSelf"
                    />
                    @if($isEditingSelf)
                        <small class="text-muted">You cannot change your own email address.</small>
                    @endif
                </div>
                <div class="col-md-3">
                    <x-form.input 
                        label="Username"
                        name="userData.username"
                        id="edit-modal-username" 
                        model="lazy:userData.username"
                        placeholder="Enter username"
                        :required="true"
                        containerClass=""
                        autocomplete="username"
                        :readonly="$isEditingSelf"
                        :disabled="$isEditingSelf"
                    />
                    @if(!$usernameAvailable && !$isEditingSelf)
                        <span class="text-danger small">Username is already taken.</span>
                    @endif
                    @if($isEditingSelf)
                        <small class="text-muted">You cannot change your own username.</small>
                    @endif
                </div>
                <div class="col-md-3">
                    <x-form.input 
                        label="Password"
                        name="userData.password"
                        id="edit-modal-password" 
                        type="password"
                        model="lazy:userData.password"
                        placeholder="Leave blank to keep current password"
                        containerClass=""
                        autocomplete="new-password"
                    />
                </div>
            </div>
            
            <h6 class="mt-4 mb-3 text-theme-primary">Personal Details</h6>
            <div class="row g-3">
                <div class="col-md-3">
                    <x-form.input 
                        label="Date of Birth"
                        name="userData.dob"
                        id="edit-modal-dob" 
                        type="date"
                        model="lazy:userData.dob"
                        containerClass=""
                    />
                </div>
                <div class="col-md-3">
                    <div>
                        <x-form.label for="edit-modal-gender" :required="false">Gender</x-form.label>
                        <select id="edit-modal-gender" wire:model.lazy="userData.gender" class="form-control @error('userData.gender') is-invalid @enderror">
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                        @error('userData.gender') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <x-form.input 
                        label="Religion"
                        name="userData.religion"
                        id="edit-modal-religion" 
                        model="lazy:userData.religion"
                        placeholder="Enter religion"
                        containerClass=""
                    />
                </div>
                <div class="col-md-3">
                    <x-form.input 
                        label="Phone"
                        name="userData.phone"
                        id="edit-modal-phone" 
                        model="lazy:userData.phone"
                        placeholder="Enter phone"
                        containerClass=""
                        autocomplete="tel"
                    />
                </div>
                <div class="col-md-3">
                    <x-form.input 
                        label="CNIC"
                        name="userData.cnic"
                        id="edit-modal-cnic" 
                        model="lazy:userData.cnic"
                        placeholder="12345-1234567-1"
                        :required="true"
                        containerClass=""
                        pattern="\d{5}-\d{7}-\d{1}"
                        title="CNIC format: 12345-1234567-1"
                    />
                </div>
                <div class="col-md-3">
                    <x-form.input 
                        label="Blood Group"
                        name="userData.blood_group"
                        id="edit-modal-blood-group" 
                        model="lazy:userData.blood_group"
                        placeholder="Enter blood group"
                        containerClass=""
                    />
                </div>
                <div class="col-md-3">
                    <x-form.input 
                        label="Registration No"
                        name="userData.registration_no"
                        id="edit-modal-registration-no" 
                        model="lazy:userData.registration_no"
                        placeholder="Enter registration no"
                        containerClass=""
                    />
                </div>
                <div class="col-md-3">
                    <x-form.input 
                        label="Address"
                        name="userData.address"
                        id="edit-modal-address" 
                        model="lazy:userData.address"
                        placeholder="Enter address"
                        containerClass=""
                    />
                </div>
            </div>
            
            <h6 class="mt-4 mb-3 text-theme-primary">Location & Other</h6>
            <div class="row g-3">
                <div class="col-md-3">
                    <x-form.input 
                        label="Country"
                        name="userData.country"
                        id="edit-modal-country" 
                        model="lazy:userData.country"
                        placeholder="Enter country"
                        containerClass=""
                    />
                </div>
                <div class="col-md-3">
                    <x-form.input 
                        label="City"
                        name="userData.city"
                        id="edit-modal-city" 
                        model="lazy:userData.city"
                        placeholder="Enter city"
                        containerClass=""
                    />
                </div>
                <div class="col-md-3">
                    <x-form.input 
                        label="State"
                        name="userData.state"
                        id="edit-modal-state" 
                        model="lazy:userData.state"
                        placeholder="Enter state"
                        containerClass=""
                    />
                </div>
                <div class="col-md-3">
                    <div>
                        <x-form.label for="edit-modal-transport-status" :required="false">Transport Status</x-form.label>
                        <select id="edit-modal-transport-status" wire:model.lazy="userData.transport_status" class="form-control @error('userData.transport_status') is-invalid @enderror">
                            <option value="">Select</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        @error('userData.transport_status') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <x-form.input 
                        label="Transport ID"
                        name="userData.transport_id"
                        id="edit-modal-transport-id" 
                        type="number"
                        model="lazy:userData.transport_id"
                        placeholder="Enter transport id"
                        containerClass=""
                    />
                </div>
                <div class="col-md-3">
                    <div>
                        <x-form.label for="edit-modal-is-active" :required="false">Active</x-form.label>
                        <select id="edit-modal-is-active" wire:model.lazy="userData.is_active" class="form-control @error('userData.is_active') is-invalid @enderror">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        @error('userData.is_active') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <x-form.input 
                        label="Avatar"
                        name="userData.avatar"
                        id="edit-modal-avatar" 
                        type="file"
                        model="userData.avatar"
                        containerClass=""
                    />
                </div>
            </div>
            
            <h6 class="mt-4 mb-3 text-theme-primary">Teacher Job Information</h6>
            <div class="row g-3">
                <div class="col-md-3">
                    <x-form.select2
                        label="Designation"
                        id="edit-modal-designation-id"
                        name="teacherData.designation_id"
                        :options="collect($designations)->pluck('name', 'id')->toArray()"
                        :value="$teacherData['designation_id'] ?? ''"
                        wire:model.lazy="teacherData.designation_id"
                        placeholder="Select Designation"
                        :required="true"
                        containerClass=""
                    />
                </div>
                <div class="col-md-3">
                    <x-form.input 
                        label="Joining Date"
                        name="teacherData.joining_date"
                        id="edit-modal-joining-date" 
                        type="date"
                        model="lazy:teacherData.joining_date"
                        :required="true"
                        containerClass=""
                    />
                </div>
                <div class="col-md-3">
                    <x-form.input 
                        label="Qualification"
                        name="teacherData.qualification"
                        id="edit-modal-qualification" 
                        model="lazy:teacherData.qualification"
                        placeholder="Enter qualification (optional)"
                        containerClass=""
                    />
                </div>
                <div class="col-md-3">
                    <x-form.input 
                        label="Basic Salary"
                        name="teacherData.basic_salary"
                        id="edit-modal-basic-salary" 
                        type="number"
                        model="lazy:teacherData.basic_salary"
                        placeholder="Enter basic salary"
                        :required="true"
                        containerClass=""
                    />
                </div>
            </div>
        </form>
        
        <x-slot name="footer">
            <button type="button" class="btn theme-unfilled-btn" data-bs-dismiss="modal" wire:click="closeModal">Cancel</button>
            <button type="button" class="btn theme-filled-btn" wire:click="updateTeacher">Update Teacher</button>
        </x-slot>
    </x-form.modal>
</div>



