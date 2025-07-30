<x-sections.default>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Parents</h3>
        <button type="button" class="btn theme-filled-btn" data-bs-toggle="modal" data-bs-target="#createModal">
            + Create
        </button>
    </div>
    @php
        $columns = ['#', 'Parent Name', 'Email', 'Username', 'Phone', 'Gender', 'Status', 'Actions'];
        $ajaxUrl = route('datatable.parents');
    @endphp
    <livewire:data-table :columns="$columns" table-id="parentsTable" :ajax-url="$ajaxUrl" :key="microtime(true)" />
    
    <x-modal id="createModal" :title="$modalTitle" :action="$modalAction" :is_edit="$is_edit">
        <h6 class="mb-3 text-theme-primary">Basic Information</h6>
        <div class="row g-3">
            <div class="col-md-3">
                <x-form.input label="Full Name" name="name" id="create-modal-name" wire:model="name" placeholder="Enter full name" :required="true" autocomplete="name" />
            </div>
            <div class="col-md-3">
                <x-form.input label="Email" name="email" id="create-modal-email" type="email" wire:model="email" placeholder="Enter email" :required="true" autocomplete="email" />
            </div>
            <div class="col-md-3">
                <x-form.input label="Username" name="username" id="create-modal-username" wire:model="username" placeholder="Enter username" :required="true" autocomplete="username" />
            </div>
            <div class="col-md-3">
                <x-form.input label="Password" name="password" id="create-modal-password" type="password" wire:model="password" placeholder="Enter password" :required="true" autocomplete="new-password" />
            </div>
        </div>
        <div class="row g-3">
            <div class="col-md-3">
                <x-form.input label="Date of Birth" name="dob" id="create-modal-dob" type="date" wire:model="dob" />
            </div>
            <div class="col-md-3">
                <x-form.select 
                    label="Gender" 
                    name="gender" 
                    :options="['male' => 'Male', 'female' => 'Female', 'other' => 'Other']" 
                    model="gender" 
                    placeholder="Select Gender"
                />
            </div>
            <div class="col-md-3">
                <x-form.input label="CNIC" name="cnic" id="create-modal-cnic" wire:model="cnic" placeholder="12345-1234567-1" :required="true" pattern="\d{5}-\d{7}-\d{1}" title="CNIC format: 12345-1234567-1" />
            </div>
            <div class="col-md-3">
                <x-form.input label="Blood Group" name="blood_group" id="create-modal-blood-group" wire:model="blood_group" placeholder="Enter blood group" />
            </div>
        </div>
        <div class="row g-3">
            <div class="col-md-3">
                <x-form.input label="Registration No" name="registration_no" id="create-modal-registration-no" wire:model="registration_no" placeholder="Enter registration no" />
            </div>
            <div class="col-md-3">
                <x-form.input label="Address" name="address" id="create-modal-address" wire:model="address" placeholder="Enter address" />
            </div>
            <div class="col-md-3">
                <x-form.input label="Country" name="country" id="create-modal-country" wire:model="country" placeholder="Enter country" />
            </div>
            <div class="col-md-3">
                <x-form.input label="City" name="city" id="create-modal-city" wire:model="city" placeholder="Enter city" />
            </div>
        </div>
        <div class="row g-3">
            <div class="col-md-3">
                <x-form.input label="State" name="state" id="create-modal-state" wire:model="state" placeholder="Enter state" />
            </div>
            <div class="col-md-3">
                <x-form.select 
                    label="Transport Status" 
                    name="transport_status" 
                    :options="['0' => 'Inactive', '1' => 'Active']" 
                    model="transport_status" 
                    placeholder="Select Status"
                />
            </div>
            <div class="col-md-3">
                <x-form.input label="Transport ID" name="transport_id" id="create-modal-transport-id" type="number" wire:model="transport_id" placeholder="Enter transport id" />
            </div>
            <div class="col-md-3">
                <x-form.select 
                    label="Active" 
                    name="is_active" 
                    :options="['1' => 'Active', '0' => 'Inactive']" 
                    model="is_active" 
                    placeholder="Select Status"
                />
            </div>
            <div class="col-md-6">
                <x-form.input label="Avatar" name="avatar" id="create-modal-avatar" type="file" wire:model="avatar" />
            </div>
        </div>
        <h6 class="mt-4 mb-3 text-theme-primary">Parent Additional Information</h6>
        <div class="row g-3">
            <div class="col-md-4">
                <x-form.input label="Father's Profession" name="father_profession" id="father_profession" wire:model="father_profession" placeholder="Enter father's profession" />
            </div>
            <div class="col-md-4">
                <x-form.input label="Mother's Name" name="mother_name" id="mother_name" wire:model="mother_name" placeholder="Enter mother's name" />
            </div>
            <div class="col-md-4">
                <x-form.input label="Mother's Contact" name="mother_contact" id="mother_contact" wire:model="mother_contact" placeholder="Enter mother's contact" />
            </div>
            <div class="col-md-4">
                <x-form.input label="Mother's Profession" name="mother_profession" id="mother_profession" wire:model="mother_profession" placeholder="Enter mother's profession" />
            </div>
            <div class="col-md-4">
                <x-form.input label="NTN No" name="ntn_no" id="ntn_no" wire:model="ntn_no" placeholder="Enter NTN number" />
            </div>
        </div>
    </x-modal>
</x-sections.default>
    
