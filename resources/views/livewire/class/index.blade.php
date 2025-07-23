<x-sections.default>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Classes</h3>
        <button type="button" class="btn theme-filled-btn" data-bs-toggle="modal" data-bs-target="#createModal">
            + Create
        </button>
    </div>
    @php
        $columns = ['#', 'Name', 'Class Numeric', 'Teacher ID', 'Action'];
        $rows = [];
        if($classes && count($classes)) {
            foreach($classes as $index => $class) {
                $rows[] = [
                    $index + 1,
                    e($class->name),
                    e($class->class_numeric),
                    e($class->teacher_id),
                    '<div class="action-items"><span><a @click.prevent="$dispatch(\'edit-mode\', {id: ' . $class->id . '})" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fa fa-edit"></i></a></span>'
                    . '<span><a href="javascript:void(0)" class="delete-swal" data-id="' . $class->id . '"><i class="fa fa-trash"></i></a></span></div>'
                ];
            }
        }
    @endphp
    <livewire:data-table :columns="$columns" :rows="$rows" table-id="classesTable" :key="microtime(true)" />
    
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
                <label for="class_numeric" class="form-label">Class Numeric</label>
                <input type="text" class="form-control" id="class_numeric" wire:model="class_numeric">
                @error('class_numeric')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label for="teacher_id" class="form-label">Teacher ID</label>
                <input type="text" class="form-control" id="teacher_id" wire:model="teacher_id">
                @error('teacher_id')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
        </form>
    </x-modal>
</x-sections.default>