<x-sections.default>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Sections</h3>
        <button type="button" class="btn theme-filled-btn" data-bs-toggle="modal" data-bs-target="#createModal">
            + Create
        </button>
    </div>
    @php
        $columns = ['#', 'Name', 'Class ID', 'Category', 'Capacity', 'Note', 'Action'];
        $rows = [];
        if($sections && count($sections)) {
            foreach($sections as $index => $section) {
                $rows[] = [
                    $index + 1,
                    e($section->name),
                    e($section->class_id),
                    e($section->category),
                    e($section->capacity),
                    e($section->note),
                    '<div class="action-items"><span><a @click.prevent="$dispatch(\'edit-mode\', {id: ' . $section->id . '})" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fa fa-edit"></i></a></span>'
                    . '<span><a href="javascript:void(0)" class="delete-swal" data-id="' . $section->id . '"><i class="fa fa-trash"></i></a></span></div>'
                ];
            }
        }
    @endphp
    <livewire:data-table :columns="$columns" :rows="$rows" table-id="sectionsTable" :key="microtime(true)" />
    
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
                <label for="class_id" class="form-label">Class ID</label>
                <input type="text" class="form-control" id="class_id" wire:model="class_id">
                @error('class_id')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <input type="text" class="form-control" id="category" wire:model="category">
                @error('category')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label for="capacity" class="form-label">Capacity</label>
                <input type="text" class="form-control" id="capacity" wire:model="capacity">
                @error('capacity')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label for="note" class="form-label">Note</label>
                <input type="text" class="form-control" id="note" wire:model="note">
                @error('note')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
        </form>
    </x-modal>
</x-sections.default>
