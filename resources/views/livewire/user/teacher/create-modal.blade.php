<div wire:ignore.self>
    <x-form.modal 
        id="createTeacherModal" 
        title="Create New Teacher" 
        size="modal-xl" 
        backdrop="static"
    >
        <form wire:submit.prevent="createTeacher">
            <h6 class="mb-3 text-theme-primary">Basic Information</h6>
                <div class="row g-3">
                    <div class="col-md-3">
                        <x-form.input 
                            label="Full Name"
                            name="userData.name"
                            id="create-modal-name" 
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
                            id="create-modal-email" 
                            type="email"
                            model="lazy:userData.email"
                            placeholder="Enter email"
                            :required="true"
                            containerClass=""
                            autocomplete="email"
                        />
                    </div>
                    <div class="col-md-3">
                        <x-form.input 
                            label="Username"
                            name="userData.username"
                            id="create-modal-username" 
                            model="lazy:userData.username"
                            placeholder="Enter username"
                            :required="true"
                            containerClass=""
                            autocomplete="username"
                        />
                        @if(!$usernameAvailable)
                            <span class="text-danger small">Username is already taken.</span>
                        @endif
                    </div>
                    <div class="col-md-3">
                        <x-form.input 
                            label="Password"
                            name="userData.password"
                            id="create-modal-password" 
                            type="password"
                            model="lazy:userData.password"
                            placeholder="Enter password"
                            :required="true"
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
                            id="create-modal-dob" 
                            type="date"
                            model="lazy:userData.dob"
                            containerClass=""
                        />
                    </div>
                    <div class="col-md-3">
                        <div>
                            <x-form.label for="create-modal-gender" :required="false">Gender</x-form.label>
                            <select id="create-modal-gender" wire:model.lazy="userData.gender" class="form-control @error('userData.gender') is-invalid @enderror">
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
                            id="create-modal-religion" 
                            model="lazy:userData.religion"
                            placeholder="Enter religion"
                            containerClass=""
                        />
                    </div>
                    <div class="col-md-3">
                        <x-form.input 
                            label="Phone"
                            name="userData.phone"
                            id="create-modal-phone" 
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
                            id="create-modal-cnic" 
                            model="lazy:userData.cnic"
                            placeholder="12345-1234567-1"
                            :required="true"
                            containerClass=""
                            pattern="\d{5}-\d{7}-\d{1}"
                            title="CNIC format: 12345-1234567-1"
                        />
                        @if(!$cnicAvailable)
                            <span class="text-danger small">CNIC is already registered.</span>
                        @endif
                    </div>
                    <div class="col-md-3">
                        <x-form.input 
                            label="Blood Group"
                            name="userData.blood_group"
                            id="create-modal-blood-group" 
                            model="lazy:userData.blood_group"
                            placeholder="Enter blood group"
                            containerClass=""
                        />
                    </div>
                    <div class="col-md-3">
                        <x-form.input 
                            label="Registration No"
                            name="userData.registration_no"
                            id="create-modal-registration-no" 
                            model="lazy:userData.registration_no"
                            placeholder="Enter registration no"
                            containerClass=""
                        />
                    </div>
                    <div class="col-md-3">
                        <x-form.input 
                            label="Address"
                            name="userData.address"
                            id="create-modal-address" 
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
                            id="create-modal-country" 
                            model="lazy:userData.country"
                            placeholder="Enter country"
                            containerClass=""
                        />
                    </div>
                    <div class="col-md-3">
                        <x-form.input 
                            label="City"
                            name="userData.city"
                            id="create-modal-city" 
                            model="lazy:userData.city"
                            placeholder="Enter city"
                            containerClass=""
                        />
                    </div>
                    <div class="col-md-3">
                        <x-form.input 
                            label="State"
                            name="userData.state"
                            id="create-modal-state" 
                            model="lazy:userData.state"
                            placeholder="Enter state"
                            containerClass=""
                        />
                    </div>
                    <div class="col-md-3">
                        <div>
                            <x-form.label for="create-modal-transport-status" :required="false">Transport Status</x-form.label>
                            <select id="create-modal-transport-status" wire:model.lazy="userData.transport_status" class="form-control @error('userData.transport_status') is-invalid @enderror">
                                <option value="0">Inactive</option>
                                <option value="1">Active</option>
                            </select>
                            @error('userData.transport_status') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <x-form.input 
                            label="Transport ID"
                            name="userData.transport_id"
                            id="create-modal-transport-id" 
                            type="number"
                            model="lazy:userData.transport_id"
                            placeholder="Enter transport id"
                            containerClass=""
                        />
                    </div>
                    <div class="col-md-3">
                        <div>
                            <x-form.label for="create-modal-is-active" :required="false">Active</x-form.label>
                            <select id="create-modal-is-active" wire:model.lazy="userData.is_active" class="form-control @error('userData.is_active') is-invalid @enderror">
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
                            id="create-modal-avatar" 
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
                        id="create-modal-designation-id"
                        name="teacherData.designation_id"
                        :options="collect($designations)->pluck('name', 'id')->toArray()"
                        wire:model="teacherData.designation_id"
                        placeholder="Select Designation"
                        :required="true"
                        containerClass=""
                    />
                </div>
                <div class="col-md-3">
                    <x-form.input 
                        label="Joining Date"
                        name="teacherData.joining_date"
                        id="create-modal-joining-date" 
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
                        id="create-modal-qualification" 
                        model="lazy:teacherData.qualification"
                        placeholder="Enter qualification (optional)"
                        containerClass=""
                    />
                </div>
                <div class="col-md-3">
                    <x-form.input 
                        label="Basic Salary"
                        name="teacherData.basic_salary"
                        id="create-modal-basic-salary" 
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
            <button type="button" class="btn theme-filled-btn" wire:click="createTeacher">Create Teacher</button>
        </x-slot>
    </x-form.modal>
</div>
