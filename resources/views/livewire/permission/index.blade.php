<x-sections.default>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Permissions</h3>
        <button type="button" class="btn theme-filled-btn" data-bs-toggle="modal" data-bs-target="#createModal">
            + Create
        </button>
    </div>
    @php
        $columns = ['#', 'Name', 'Module Name', 'Guard Name', 'Action'];
        $ajaxUrl = route('datatable.permissions');
    @endphp
    <livewire:data-table :columns="$columns" :ajax-url="$ajaxUrl" table-id="permissionsTable" :key="microtime(true)" />
    
    <x-modal id="createModal" :title="$modalTitle" :action="$modalAction" :is_edit="$is_edit">
        <form>
            <x-form.input 
                label="Name" 
                name="name" 
                model="name" 
                :required="true" 
                placeholder="Enter permission name" 
            />
            <x-form.select2
                label="Module Name"
                id="module_name"
                name="module_name"
                model="module_name"
                :options="$modules"
                :required="true"
                placeholder="Select module name"
            />
        </form>
    </x-modal>
</x-sections.default>
