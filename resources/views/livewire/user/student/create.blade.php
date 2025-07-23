<div wire:ignore.self>
    <x-form.modal id="createStudentModal" title="Create New Student" size="modal-xl" backdrop="static" wireCloseMethod="closeModal">
        <div>
            <form wire:submit.prevent="createStudent">
                <h6 class="mb-3 text-theme-primary">Basic Information</h6>
                <div class="row g-3">
                    <div class="col-md-3">
                        <x-form.input label="Full Name" id="user_name" name="user_name" wire:model.lazy="user_name" :required="true" placeholder="Enter full name" />
                    </div>
                    <div class="col-md-3">
                        <x-form.input label="Email" id="user_email" name="user_email" type="email" wire:model.lazy="user_email" :required="true" placeholder="Enter email" />
                    </div>
                    <div class="col-md-3">
                        <x-form.input label="Username" id="user_username" name="user_username" wire:model.lazy="user_username" :required="true" placeholder="Enter username" />
                        @if(!is_null($username_available))
                            @if($username_available)
                                <span class="text-success small">Username is available</span>
                            @else
                                <span class="text-danger small">Username is already taken</span>
                            @endif
                        @endif
                    </div>
                    <div class="col-md-3">
                        <x-form.input label="Password" id="user_password" name="user_password" type="password" wire:model.lazy="user_password" :required="true" placeholder="Enter password" />
                    </div>
                </div>
                <input type="hidden" wire:model="user_type" value="student">
                <h6 class="mt-4 mb-3 text-theme-primary">Personal Details</h6>
                <div class="row g-3">
                    <div class="col-md-3">
                        <x-form.input label="Date of Birth" id="user_dob" name="user_dob" type="date" wire:model.lazy="user_dob" />
                    </div>
                    <div class="col-md-3">
                        <x-form.select 
                            label="Gender" 
                            id="user_gender" 
                            name="user_gender" 
                            :options="['' => 'Select Gender', 'male' => 'Male', 'female' => 'Female', 'other' => 'Other']" 
                            model="user_gender" 
                        />
                    </div>
                    <div class="col-md-3">
                        <x-form.input label="Religion" id="user_religion" name="user_religion" wire:model.lazy="user_religion" placeholder="Enter religion" />
                    </div>
                    <div class="col-md-3">
                        <x-form.input label="Phone" id="user_phone" name="user_phone" wire:model.lazy="user_phone" placeholder="Enter phone" />
                    </div>
                    <div class="col-md-3">
                        <x-form.input label="CNIC" id="user_cnic" name="user_cnic" wire:model.lazy="user_cnic" placeholder="Enter CNIC" />
                    </div>
                    <div class="col-md-3">
                        <x-form.input label="Blood Group" id="user_blood_group" name="user_blood_group" wire:model.lazy="user_blood_group" placeholder="Enter blood group" />
                    </div>
                    <div class="col-md-3">
                        <x-form.input label="Registration No" id="user_registration_no" name="user_registration_no" wire:model.lazy="user_registration_no" placeholder="Enter registration no" />
                    </div>
                    <div class="col-md-3">
                        <x-form.input label="Address" id="user_address" name="user_address" wire:model.lazy="user_address" placeholder="Enter address" />
                    </div>
                </div>
                <h6 class="mt-4 mb-3 text-theme-primary">Location & Other</h6>
                <div class="row g-3">
                    <div class="col-md-3">
                        <x-form.input label="Country" id="user_country" name="user_country" wire:model.lazy="user_country" placeholder="Enter country" />
                    </div>
                    <div class="col-md-3">
                        <x-form.input label="City" id="user_city" name="user_city" wire:model.lazy="user_city" placeholder="Enter city" />
                    </div>
                    <div class="col-md-3">
                        <x-form.input label="State" id="user_state" name="user_state" wire:model.lazy="user_state" placeholder="Enter state" />
                    </div>
                    <div class="col-md-3">
                        <x-form.select 
                            label="Transport Status" 
                            id="user_transport_status" 
                            name="user_transport_status" 
                            :options="['' => 'Select', '1' => 'Active', '0' => 'Inactive']" 
                            model="user_transport_status" 
                        />
                    </div>
                    <div class="col-md-3">
                        <x-form.input label="Transport ID" id="user_transport_id" name="user_transport_id" wire:model.lazy="user_transport_id" placeholder="Enter transport id" />
                    </div>
                    <div class="col-md-3">
                        <x-form.select 
                            label="Is Active" 
                            id="user_is_active" 
                            name="user_is_active" 
                            :options="['1' => 'Active', '0' => 'Inactive']" 
                            model="user_is_active" 
                        />
                    </div>
                    <div class="col-md-6">
                        <x-form.input label="Avatar" id="user_avatar" name="user_avatar" type="file" wire:model="user_avatar" />
                    </div>
                </div>
                <h6 class="mt-4 mb-3 text-theme-primary">Student Academic Information</h6>
                <div class="row g-3">
                    <div class="col-md-4 mb-2">
                        <x-form.select2 
                            label="Parent" 
                            id="parent_id" 
                            name="parent_id" 
                            :options="$parents->mapWithKeys(fn($parent) => [(string)$parent->id => $parent->user->name])->toArray()" 
                            :value="$parent_id ?? ''" 
                            model="parent_id" 
                            placeholder="Select Parent" 
                        />
                    </div>
                    <div class="col-md-4">
                        <x-form.input label="Admission Date" id="admission_date" name="admission_date" type="date" wire:model.lazy="admission_date" :required="true" />
                    </div>
                    <div class="col-md-4">
                        <x-form.input label="Roll No" id="roll_no" name="roll_no" wire:model.lazy="roll_no" :required="true" placeholder="Enter roll number" />
                    </div>
                    <div class="col-md-4">
                        <x-form.select2 
                            label="Class" 
                            id="class_id" 
                            name="class_id" 
                            :options="$classes->pluck('name', 'id')->mapWithKeys(fn($name, $id) => [(string)$id => $name])->toArray()" 
                            :value="$class_id ?? ''" 
                            model="class_id" 
                            placeholder="Select Class" 
                        />
                    </div>
                    <div class="col-md-4">
                        <x-form.select2 
                            label="Section" 
                            id="section_id" 
                            name="section_id" 
                            :options="$sections->pluck('name', 'id')->mapWithKeys(fn($name, $id) => [(string)$id => $name])->toArray()" 
                            :value="$section_id ?? ''" 
                            model="section_id" 
                            placeholder="Select Section" 
                        />
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-baseline gap-3 mt-3">
                            <x-form.checkbox id="library_status" name="library_status" wire:model.lazy="library_status" value="1" label="Library Active" />
                            <x-form.checkbox id="hostel_status" name="hostel_status" wire:model.lazy="hostel_status" value="1" label="Hostel Active" />
                        </div>
                    </div>
                </div>

                <x-slot name="footer">
                    <button type="button" class="btn theme-unfilled-btn" data-bs-dismiss="modal" wire:click="closeModal">Cancel</button>
                    <button type="button" class="btn theme-filled-btn" wire:click="createStudent">Create Student</button>
                </x-slot>
            </form>
        </div>
    </x-form.modal>
</div>
