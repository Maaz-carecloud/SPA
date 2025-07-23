<x-sections.default>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Students</h3>
        <button type="button" class="btn theme-filled-btn" data-bs-toggle="modal" data-bs-target="#createModal">
            + Create
        </button>
    </div>
    @php
        $columns = ['#', 'User ID', 'Parent ID', 'Admission Date', 'Class ID', 'Section ID', 'Roll No', 'Library Status', 'Hostel Status', 'Action'];
        $rows = [];
        if($students && count($students)) {
            foreach($students as $index => $student) {
                $rows[] = [
                    $index + 1,
                    e($student->user_id),
                    e($student->parent_id),
                    e($student->admission_date),
                    e($student->class_id),
                    e($student->section_id),
                    e($student->roll_no),
                    e($student->library_status),
                    e($student->hostel_status),
                    '<div class="action-items"><span><a @click.prevent="$dispatch(\'edit-mode\', {id: ' . $student->id . '})" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fa fa-edit"></i></a></span>'
                    . '<span><a href="javascript:void(0)" class="delete-swal" data-id="' . $student->id . '"><i class="fa fa-trash"></i></a></span></div>'
                ];
            }
        }
    @endphp
    <livewire:data-table :columns="$columns" :rows="$rows" table-id="studentsTable" :key="microtime(true)" />
    
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
        <h6 class="mt-4 mb-3 text-theme-primary">Student Academic Information</h6>
        <div class="row g-3">
            <div class="col-md-4 mb-2">
                <x-form.select2 label="Parent" id="parent_id" name="parent_id" :options="$parents->mapWithKeys(fn($parent) => [(string)$parent->id => $parent->user->name])->toArray()" :value="$parent_id ?? ''" wire:model="parent_id" placeholder="Select Parent" />
            </div>
            <div class="col-md-4">
                <x-form.input label="Admission Date" name="admission_date" id="admission_date" type="date" wire:model="admission_date" :required="true" />
            </div>
            <div class="col-md-4">
                <x-form.input label="Roll No" name="roll_no" id="roll_no" wire:model="roll_no" :required="true" placeholder="Enter roll number" />
            </div>
            <div class="col-md-4">
                <x-form.select2 label="Class" id="class_id" name="class_id" :options="$classes->pluck('name', 'id')->mapWithKeys(fn($name, $id) => [(string)$id => $name])->toArray()" :value="$class_id ?? ''" wire:model="class_id" placeholder="Select Class" />
            </div>
            <div class="col-md-4">
                <x-form.select2 label="Section" id="section_id" name="section_id" :options="$sections->pluck('name', 'id')->mapWithKeys(fn($name, $id) => [(string)$id => $name])->toArray()" :value="$section_id ?? ''" wire:model="section_id" placeholder="Select Section" />
            </div>
            <div class="col-md-4">
                <div class="d-flex align-items-baseline gap-3 mt-3">
                    <x-form.checkbox id="library_status" name="library_status" wire:model="library_status" value="1" label="Library Active" />
                    <x-form.checkbox id="hostel_status" name="hostel_status" wire:model="hostel_status" value="1" label="Hostel Active" />
                </div>
            </div>
        </div>
    </x-modal>
</x-sections.default>
  
    