<div wire:ignore.self>
    <x-form.modal id="editParentModal" title="Edit Parent" size="modal-xl" backdrop="static" wireCloseMethod="closeModal">
        <div>
            <form wire:submit.prevent="updateParent">
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
                    </div>
                    <div class="col-md-3">
                        <x-form.input label="Phone" id="user_phone" name="user_phone" wire:model.lazy="user_phone" placeholder="Enter phone" />
                    </div>
                </div>
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
                        <x-form.input label="CNIC" id="user_cnic" name="user_cnic" wire:model.lazy="user_cnic" placeholder="Enter CNIC" />
                    </div>
                    <div class="col-md-3">
                        <x-form.input label="Blood Group" id="user_blood_group" name="user_blood_group" wire:model.lazy="user_blood_group" placeholder="Enter blood group" />
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-3">
                        <x-form.input label="Registration No" id="user_registration_no" name="user_registration_no" wire:model.lazy="user_registration_no" placeholder="Enter registration no" />
                    </div>
                    <div class="col-md-3">
                        <x-form.input label="Address" id="user_address" name="user_address" wire:model.lazy="user_address" placeholder="Enter address" />
                    </div>
                    <div class="col-md-3">
                        <x-form.input label="Country" id="user_country" name="user_country" wire:model.lazy="user_country" placeholder="Enter country" />
                    </div>
                    <div class="col-md-3">
                        <x-form.input label="City" id="user_city" name="user_city" wire:model.lazy="user_city" placeholder="Enter city" />
                    </div>
                </div>
                <div class="row g-3">
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
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <x-form.input label="Avatar" id="user_avatar" name="user_avatar" type="file" wire:model="user_avatar" />
                    </div>
                </div>
                <h6 class="mt-4 mb-3 text-theme-primary">Parent Additional Information</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <x-form.input label="Father's Profession" id="father_profession" name="father_profession" wire:model.lazy="father_profession" placeholder="Enter father's profession" />
                    </div>
                    <div class="col-md-4">
                        <x-form.input label="Mother's Name" id="mother_name" name="mother_name" wire:model.lazy="mother_name" placeholder="Enter mother's name" />
                    </div>
                    <div class="col-md-4">
                        <x-form.input label="Mother's Contact" id="mother_contact" name="mother_contact" wire:model.lazy="mother_contact" placeholder="Enter mother's contact" />
                    </div>
                    <div class="col-md-4">
                        <x-form.input label="Mother's Profession" id="mother_profession" name="mother_profession" wire:model.lazy="mother_profession" placeholder="Enter mother's profession" />
                    </div>
                    <div class="col-md-4">
                        <x-form.input label="NTN No" id="ntn_no" name="ntn_no" wire:model.lazy="ntn_no" placeholder="Enter NTN number" />
                    </div>
                </div>
                <x-slot name="footer">
                    <button type="button" class="btn theme-unfilled-btn" data-bs-dismiss="modal" wire:click="closeModal">Cancel</button>
                    <button type="submit" class="btn theme-filled-btn" wire:click='updateParent'>Update Parent</button>
                </x-slot>
            </form>
        </div>
    </x-form.modal>
</div>