<x-sections.default>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Classes</h3>
        <button type="button" class="btn theme-filled-btn" data-bs-toggle="modal" data-bs-target="#createModal">
            + Create
        </button>
    </div>
    @php
        $columns = ['#', 'Name', 'Class Numeric', 'Teacher Name', 'Action'];
        $rows = [];
        if($classes && count($classes)) {
            foreach($classes as $index => $class) {
                $rows[] = [
                    $index + 1,
                    e($class->name),
                    e($class->class_numeric),
                    optional($class->teacher->user)->name ?? 'N/A',
                    '<div class="action-items"><span><a @click.prevent="$dispatch(\'edit-mode\', {id: ' . $class->id . '})" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fa fa-edit"></i></a></span>'
                    . '<span><a href="javascript:void(0)" class="delete-swal" data-id="' . $class->id . '"><i class="fa fa-trash"></i></a></span></div>'
                ];
            }
        }
    @endphp
    <livewire:data-table :columns="$columns" :rows="$rows" table-id="classesTable" :key="microtime(true)" />
    
    <x-modal id="createModal" :title="$modalTitle" :action="$modalAction" :is_edit="$is_edit">
        <form>
            <x-form.input label="Name" name="name" id="name" model="name" />
            <x-form.input label="Class Numeric" name="class_numeric" id="class_numeric" model="class_numeric" />
            <x-form.select2 label="Teacher Name" name="teacher_id" id="teacher_id" :options="$teacherOptions" model="teacher_id" placeholder="Select Teacher" />
        </form>
    </x-modal>
</x-sections.default>