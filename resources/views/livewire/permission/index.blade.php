<x-sections.default>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Permissions</h3>
        <button type="button" class="btn theme-filled-btn" data-bs-toggle="modal" data-bs-target="#createModal">
            + Create
        </button>
    </div>
    @php
        $columns = ['#', 'Name', 'Module Name', 'Guard Name', 'Action'];
        $rows = [];
        if($permissions && count($permissions)) {
            foreach($permissions as $index => $permission) {
                $rows[] = [
                    $index + 1,
                    e($permission->name),
                    e($permission->module_name),
                    e($permission->guard_name),
                    '<div class="action-items"><span><a @click.prevent="$dispatch(\'edit-mode\', {id: ' . $permission->id . '})" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fa fa-edit"></i></a></span>'
                    . '<span><a href="javascript:void(0)" class="delete-swal" data-id="' . $permission->id . '"><i class="fa fa-trash"></i></a></span></div>'
                ];
            }
        }
    @endphp
    <livewire:data-table :columns="$columns" :rows="$rows" table-id="permissionsTable" :key="microtime(true)" />
    
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
