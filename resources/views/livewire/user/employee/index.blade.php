<x-sections.default>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Employees</h3>
        <button type="button" class="btn theme-filled-btn" data-bs-toggle="modal" data-bs-target="#createModal">
            + Create
        </button>
    </div>
    @php
        $columns = ['#', 'User ID', 'Designation ID', 'Joining Date', 'Qualification', 'Basic Salary', 'Action'];
        $rows = [];
        if($employees && count($employees)) {
            foreach($employees as $index => $employee) {
                $rows[] = [
                    $index + 1,
                    e($employee->user_id),
                    e($employee->designation_id),
                    e($employee->joining_date),
                    e($employee->qualification),
                    e($employee->basic_salary),
                    '<div class="action-items"><span><a @click.prevent="$dispatch(\'edit-mode\', {id: ' . $employee->id . '})" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fa fa-edit"></i></a></span>'
                    . '<span><a href="javascript:void(0)" class="delete-swal" data-id="' . $employee->id . '"><i class="fa fa-trash"></i></a></span></div>'
                ];
            }
        }
    @endphp
    <livewire:data-table :columns="$columns" :rows="$rows" table-id="employeesTable" :key="microtime(true)" />
    
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
                <x-form.input label="Date of Birth" name="dob" id="create-modal-dob" type="date" wire:model="dob" containerClass="" />
            </div>
            <div class="col-md-3">
                <div>
                    <x-form.label for="create-modal-gender" :required="false">Gender</x-form.label>
                    <select id="create-modal-gender" wire:model="gender" class="form-control @error('gender') is-invalid @enderror">
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                    @error('gender') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
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
                <div>
                    <x-form.label for="create-modal-transport-status" :required="false">Transport Status</x-form.label>
                    <select id="create-modal-transport-status" wire:model="transport_status" class="form-control @error('transport_status') is-invalid @enderror">
                        <option value="0">Inactive</option>
                        <option value="1">Active</option>
                    </select>
                    @error('transport_status') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="col-md-3">
                <x-form.input label="Transport ID" name="transport_id" id="create-modal-transport-id" type="number" wire:model="transport_id" placeholder="Enter transport id" containerClass="" />
            </div>
            <div class="col-md-3">
                <div>
                    <x-form.label for="create-modal-is-active" :required="false">Active</x-form.label>
                    <select id="create-modal-is-active" wire:model="is_active" class="form-control @error('is_active') is-invalid @enderror">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    @error('is_active') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
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

