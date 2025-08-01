<x-sections.default>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Warehouses</h3>
        <button type="button" class="btn theme-filled-btn" data-bs-toggle="modal" data-bs-target="#createModal">
            + Create
        </button>
    </div>

    @php
        $columns = ['#', 'Warehouse Name', 'Code', 'Email', 'Phone', 'Address', 'Status', 'Date Added', 'Action'];
        $ajaxUrl = route('datatable.warehouses');
    @endphp
    <livewire:data-table :columns="$columns" table-id="warehousesTable" :ajax-url="$ajaxUrl" :key="microtime(true)" />

    <x-modal id="createModal" :title="$modalTitle" :action="$modalAction" :is_edit="$is_edit" :is_not_crud="false">
        <form>
            <div class="row">
                <div class="col-md-6">
                    <x-form.input id="name" type="text" name="name" label="Warehouse Name" 
                        wire:model="name" placeholder="Enter warehouse name" :error="$errors->first('name')" />
                </div>
                <div class="col-md-6">
                    <x-form.input id="code" type="text" name="code" label="Warehouse Code" 
                        wire:model="code" placeholder="Enter warehouse code" :error="$errors->first('code')" />
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
                wire:model="address" placeholder="Enter warehouse address" rows="3" :error="$errors->first('address')" />
            
            <x-form.checkbox id="is_active" name="is_active" label="Is Active?" 
                wire:model="is_active" :checked="$is_active" />
        </form>
    </x-modal>
</x-sections.default>
    
