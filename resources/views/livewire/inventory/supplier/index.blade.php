<x-sections.default>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Suppliers</h3>
        <button type="button" class="btn theme-filled-btn" data-bs-toggle="modal" data-bs-target="#createModal">
            + Create
        </button>
    </div>

    @php
        $columns = ['#', 'Company Name', 'Contact Person', 'Email', 'Phone', 'Address', 'Status', 'Date Added', 'Action'];
        $ajaxUrl = route('datatable.suppliers');
    @endphp
    <livewire:data-table :columns="$columns" table-id="suppliersTable" :ajax-url="$ajaxUrl" :key="microtime(true)" />

    <x-modal id="createModal" :title="$modalTitle" :action="$modalAction" :is_edit="$is_edit" :is_not_crud="false">
        <form>
            <div class="row">
                <div class="col-md-6">
                    <x-form.input id="name" type="text" name="name" label="Contact Person Name" 
                        wire:model="name" placeholder="Enter contact person name" :error="$errors->first('name')" />
                </div>
                <div class="col-md-6">
                    <x-form.input id="company_name" type="text" name="company_name" label="Company Name" 
                        wire:model="company_name" placeholder="Enter company name" :error="$errors->first('company_name')" />
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <x-form.input id="email" type="email" name="email" label="Email Address" 
                        wire:model="email" placeholder="Enter email address" :error="$errors->first('email')" />
                </div>
                <div class="col-md-6">
                    <x-form.input id="phone" type="text" name="phone" label="Phone Number" 
                        wire:model="phone" placeholder="Enter phone number" :error="$errors->first('phone')" />
                </div>
            </div>
            
            <x-form.textarea id="address" name="address" label="Address" 
                wire:model="address" placeholder="Enter complete address" rows="3" :error="$errors->first('address')" />
            
            <x-form.checkbox id="is_active" name="is_active" label="Is Active?" 
                wire:model="is_active" :checked="$is_active" />
        </form>
    </x-modal>
</x-sections.default>
