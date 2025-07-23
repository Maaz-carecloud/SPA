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
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" wire:model="name">
                @error('name')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label for="module_name" class="form-label">Module Name</label>
                <input type="text" class="form-control" id="module_name" wire:model="module_name">
                @error('module_name')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label for="guard_name" class="form-label">Guard Name</label>
                <input type="text" class="form-control" id="guard_name" wire:model="guard_name">
                @error('guard_name')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
        </form>
    </x-modal>
</x-sections.default>
