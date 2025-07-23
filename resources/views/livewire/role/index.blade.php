<x-sections.default>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Roles</h3>
        <button type="button" class="btn theme-filled-btn" data-bs-toggle="modal" data-bs-target="#createModal">
            + Create
        </button>
    </div>
    @php
        $columns = ['#', 'Name', 'Action'];
        $rows = [];
        if($roles && count($roles)) {
            foreach($roles as $index => $role) {
                $rows[] = [
                    $index + 1,
                    e($role->name),
                    '<div class="action-items"><span><a @click.prevent="$dispatch(\'edit-mode\', {id: ' . $role->id . '})" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fa fa-edit"></i></a></span>'
                    . '<span><a href="javascript:void(0)" class="delete-swal" data-id="' . $role->id . '"><i class="fa fa-trash"></i></a></span></div>'
                ];
            }
        }
    @endphp
    <livewire:data-table :columns="$columns" :rows="$rows" table-id="rolesTable" :key="microtime(true)" />
    
    <x-modal id="createModal" :title="$modalTitle" :action="$modalAction" :is_edit="$is_edit">
        <form>
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" wire:model="name">
                @error('name')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
        </form>
    </x-modal>
</x-sections.default>
