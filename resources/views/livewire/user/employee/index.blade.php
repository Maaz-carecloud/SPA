<x-sections.default>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Employees</h3>
        <button type="button" class="btn theme-filled-btn" data-bs-toggle="modal" data-bs-target="#createModal">
            + Create
        </button>
    </div>
    @php
        $columns = ['#', 'Employee Name', 'Email', 'Username', 'Phone', 'Status', 'Actions'];
        $ajaxUrl = route('datatable.employees');
    @endphp
    <livewire:data-table :columns="$columns" table-id="employeesTable" :ajax-url="$ajaxUrl" :key="microtime(true)" />
    
    <x-modal id="createModal" :title="$modalTitle" :action="$modalAction" :is_edit="$is_edit">
        <h6 class="mb-3 text-theme-primary">Basic Information</h6>
        <div class="row g-3">
            <div class="col-md-3">
                <x-form.input label="Full Name" name="name" id="create-modal-name" wire:model="name" placeholder="Enter full name" :required="true" containerClass="" autocomplete="name" />
            </div>
            <div class="col-md-3">
                <x-form.input label="Email" name="email" id="create-modal-email" type="email" wire:model="email" placeholder="Enter email" :required="true" containerClass="" autocomplete="email" />
            </div>
            <div class="col-md-3">
                <x-form.input label="Username" name="username" id="create-modal-username" wire:model="username" placeholder="Enter username" :required="true" containerClass="" autocomplete="username" />
            </div>
            <div class="col-md-3">
                <x-form.input label="Password" name="password" id="create-modal-password" type="password" wire:model="password" placeholder="Enter password" :required="true" containerClass="" autocomplete="new-password" />
            </div>
        </div>
        <h6 class="mt-4 mb-3 text-theme-primary">Personal Details</h6>
        <div class="row g-3">
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
                <x-form.input label="Religion" name="religion" id="create-modal-religion" wire:model="religion" placeholder="Enter religion" containerClass="" />
            </div>
            <div class="col-md-3">
                <x-form.input label="Phone" name="phone" id="create-modal-phone" wire:model="phone" placeholder="Enter phone" containerClass="" autocomplete="tel" />
            </div>
            <div class="col-md-3">
                <x-form.input label="CNIC" name="cnic" id="create-modal-cnic" wire:model="cnic" placeholder="12345-1234567-1" :required="true" containerClass="" pattern="\d{5}-\d{7}-\d{1}" title="CNIC format: 12345-1234567-1" />
            </div>
            <div class="col-md-3">
                <x-form.input label="Blood Group" name="blood_group" id="create-modal-blood-group" wire:model="blood_group" placeholder="Enter blood group" containerClass="" />
            </div>
            <div class="col-md-3">
                <x-form.input label="Registration No" name="registration_no" id="create-modal-registration-no" wire:model="registration_no" placeholder="Enter registration no" containerClass="" />
            </div>
            <div class="col-md-3">
                <x-form.input label="Address" name="address" id="create-modal-address" wire:model="address" placeholder="Enter address" containerClass="" />
            </div>
        </div>
        <h6 class="mt-4 mb-3 text-theme-primary">Location & Other</h6>
        <div class="row g-3">
            <div class="col-md-3">
                <x-form.input label="Country" name="country" id="create-modal-country" wire:model="country" placeholder="Enter country" containerClass="" />
            </div>
            <div class="col-md-3">
                <x-form.input label="City" name="city" id="create-modal-city" wire:model="city" placeholder="Enter city" containerClass="" />
            </div>
            <div class="col-md-3">
                <x-form.input label="State" name="state" id="create-modal-state" wire:model="state" placeholder="Enter state" containerClass="" />
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
                <x-form.input label="Transport ID" name="transport_id" id="create-modal-transport-id" type="number" wire:model="transport_id" placeholder="Enter transport id" containerClass="" />
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
                <x-form.input label="Avatar" name="avatar" id="create-modal-avatar" type="file" wire:model="avatar" containerClass="" />
            </div>
        </div>
        <h6 class="mt-4 mb-3 text-theme-primary">Employee Job Information</h6>
        <div class="row g-3">
            <div class="col-md-3">
                <x-form.select2 label="Designation" id="create-modal-designation-id" name="designation_id" :options="collect($designations)->pluck('name', 'id')->toArray()" wire:model="designation_id" placeholder="Select Designation" :required="true" containerClass="" />
            </div>
            <div class="col-md-3">
                <x-form.input label="Joining Date" name="joining_date" id="create-modal-joining-date" type="date" wire:model="joining_date" :required="true" containerClass="" />
            </div>
            <div class="col-md-3">
                <x-form.input label="Qualification" name="qualification" id="create-modal-qualification" wire:model="qualification" placeholder="Enter qualification (optional)" containerClass="" />
            </div>
            <div class="col-md-3">
                <x-form.input label="Basic Salary" name="basic_salary" id="create-modal-basic-salary" type="number" wire:model="basic_salary" placeholder="Enter basic salary" :required="true" containerClass="" />
            </div>
        </div>
    </x-modal>
</x-sections.default>

