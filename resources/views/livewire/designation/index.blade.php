<x-sections.default>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Designations</h3>
        <button type="button" class="btn theme-filled-btn" data-bs-toggle="modal" data-bs-target="#createModal">
            + Create
        </button>
    </div>
    @php
        $columns = ['#', 'Name', 'Created By', 'Updated By', 'Action'];
        $rows = [];
        if($designations && count($designations)) {
            foreach($designations as $index => $designation) {
                $rows[] = [
                    $index + 1,
                    e($designation->name),
                    e($designation->created_by),
                    e($designation->updated_by),
                    '<div class="action-items"><span><a @click.prevent="$dispatch(\'edit-mode\', {id: ' . $designation->id . '})" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fa fa-edit"></i></a></span>'
                    . '<span><a href="javascript:void(0)" class="delete-swal" data-id="' . $designation->id . '"><i class="fa fa-trash"></i></a></span></div>'
                ];
            }
        }
    @endphp
    <livewire:data-table :columns="$columns" :rows="$rows" table-id="designationsTable" :key="microtime(true)" />
    
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
                <label for="created_by" class="form-label">Created By</label>
                <input type="text" class="form-control" id="created_by" wire:model="created_by">
                @error('created_by')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label for="updated_by" class="form-label">Updated By</label>
                <input type="text" class="form-control" id="updated_by" wire:model="updated_by">
                @error('updated_by')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
        </form>
    </x-modal>
</x-sections.default>
