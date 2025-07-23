<div wire:ignore.self>
    <x-form.modal id="editStudentModal" title="Edit Student" size="modal-xl" backdrop="static" wireCloseMethod="closeModal">
        <div>
            <form wire:submit.prevent="updateStudent">
                <h6 class="mb-3 text-secondary">Basic Information</h6>
                <div class="row g-3">
                    <div class="col-md-3">
                        <x-form.input label="Full Name" id="userData_name" name="userData.name" model="lazy:userData.name" :required="true" placeholder="Enter full name" />
                    </div>
                    <div class="col-md-3">
                        <x-form.input label="Email" id="userData_email" name="userData.email" type="email" model="lazy:userData.email" :required="true" placeholder="Enter email" />
                    </div>
                    <div class="col-md-3">
                        <x-form.input label="Username" id="userData_username" name="userData.username" model="lazy:userData.username" :required="true" placeholder="Enter username" />
                    </div>
                    <div class="col-md-3">
                        <x-form.input label="Password" id="userData_password" name="userData.password" type="password" model="lazy:userData.password" placeholder="Enter password (leave blank to keep current)" />
                    </div>
                </div>
                <input type="hidden" model="user_type" value="student">
                <h6 class="mt-4 mb-3 text-secondary">Personal Details</h6>
                <div class="row g-3">
                    <div class="col-md-3">
                        <x-form.input label="Date of Birth" id="userData_dob" name="userData.dob" type="date" model="lazy:userData.dob" />
                    </div>
                    <div class="col-md-3">
                        <x-form.select 
                            label="Gender" 
                            id="userData_gender" 
                            name="userData.gender" 
                            :options="['' => 'Select Gender', 'male' => 'Male', 'female' => 'Female', 'other' => 'Other']" 
                            model="lazy:userData.gender" 
                        />
                    </div>
                    <div class="col-md-3">
                        <x-form.input label="Religion" id="userData_religion" name="userData.religion" model="lazy:userData.religion" placeholder="Enter religion" />
                    </div>
                    <div class="col-md-3">
                        <x-form.input label="Phone" id="userData_phone" name="userData.phone" model="lazy:userData.phone" placeholder="Enter phone" />
                    </div>
                    <div class="col-md-3">
                        <x-form.input label="CNIC" id="userData_cnic" name="userData.cnic" model="lazy:userData.cnic" placeholder="Enter CNIC" />
                    </div>
                    <div class="col-md-3">
                        <x-form.input label="Blood Group" id="userData_blood_group" name="userData.blood_group" model="lazy:userData.blood_group" placeholder="Enter blood group" />
                    </div>
                    <div class="col-md-3">
                        <x-form.input label="Registration No" id="userData_registration_no" name="userData.registration_no" model="lazy:userData.registration_no" placeholder="Enter registration no" />
                    </div>
                    <div class="col-md-3">
                        <x-form.input label="Address" id="userData_address" name="userData.address" model="lazy:userData.address" placeholder="Enter address" />
                    </div>
                </div>
                <h6 class="mt-4 mb-3 text-secondary">Location & Other</h6>
                <div class="row g-3">
                    <div class="col-md-3">
                        <x-form.input label="Country" id="userData_country" name="userData.country" model="lazy:userData.country" placeholder="Enter country" />
                    </div>
                    <div class="col-md-3">
                        <x-form.input label="City" id="userData_city" name="userData.city" model="lazy:userData.city" placeholder="Enter city" />
                    </div>
                    <div class="col-md-3">
                        <x-form.input label="State" id="userData_state" name="userData.state" model="lazy:userData.state" placeholder="Enter state" />
                    </div>
                    <div class="col-md-3">
                        <x-form.select 
                            label="Transport Status" 
                            id="userData_transport_status" 
                            name="userData.transport_status" 
                            :options="['' => 'Select', '1' => 'Active', '0' => 'Inactive']" 
                            model="lazy:userData.transport_status" 
                        />
                    </div>
                    <div class="col-md-3">
                        <x-form.input label="Transport ID" id="userData_transport_id" name="userData.transport_id" model="lazy:userData.transport_id" placeholder="Enter transport id" />
                    </div>
                    <div class="col-md-3">
                        <x-form.select 
                            label="Is Active" 
                            id="userData_is_active" 
                            name="userData.is_active" 
                            :options="['1' => 'Active', '0' => 'Inactive']" 
                            model="lazy:userData.is_active" 
                        />
                    </div>
                    <div class="col-md-6">
                        <x-form.input label="Avatar" id="userData_avatar" name="userData.avatar" type="file" model="userData.avatar" />
                    </div>
                </div>
                <h6 class="mt-4 mb-3 text-secondary">Student Academic Information</h6>
                <div class="row g-3">
                    <div class="col-md-4 mb-2">
                        <x-form.select2 
                            label="Parent" 
                            id="studentData_parent_id" 
                            name="studentData.parent_id" 
                            :options="$parents->mapWithKeys(fn($parent) => [(string)$parent->id => $parent->user->name])->toArray()" 
                            :value="$studentData['parent_id'] ?? ''" 
                            model="studentData.parent_id" 
                            placeholder="Select Parent" 
                        />
                    </div>
                    <div class="col-md-4">
                        <x-form.input label="Admission Date" id="studentData_admission_date" name="studentData.admission_date" type="date" model="studentData.admission_date" :required="true" />
                    </div>
                    <div class="col-md-4">
                        <x-form.input label="Roll No" id="studentData_roll_no" name="studentData.roll_no" model="studentData.roll_no" :required="true" placeholder="Enter roll number" />
                    </div>
                    <div class="col-md-4">
                        <x-form.select2 
                            label="Class" 
                            id="studentData_class_id" 
                            name="studentData.class_id" 
                            :options="collect($classes)->pluck('name', 'id')->toArray()"
                            :value="$studentData['class_id'] ?? ''" 
                            model="studentData.class_id" 
                            placeholder="Select Class" 
                        />
                    </div>
                    <div class="col-md-4">
                        <x-form.select2 
                            label="Section" 
                            id="studentData_section_id" 
                            name="studentData.section_id" 
                            :options="collect($sections)->pluck('name', 'id')->toArray()" 
                            :value="$studentData['section_id'] ?? ''" 
                            model="studentData.section_id" 
                            placeholder="Select Section" 
                        />
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-baseline gap-3 mt-3">
                            <x-form.checkbox id="studentData_library_status" name="studentData.library_status" model="studentData.library_status" value="1" label="Library Active" />
                            <x-form.checkbox id="studentData_hostel_status" name="studentData.hostel_status" model="studentData.hostel_status" value="1" label="Hostel Active" />
                        </div>
                    </div>
                </div>
                <x-slot name="footer">
                    <button type="button" class="btn theme-unfilled-btn" data-bs-dismiss="modal" wire:click="closeModal">Cancel</button>
                    <button type="button" class="btn theme-filled-btn" wire:click="updateStudent">Update Student</button>
                </x-slot>
            </form>
        </div>
    </x-form.modal>
</div>


