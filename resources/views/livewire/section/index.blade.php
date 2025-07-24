<x-sections.default>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Sections</h3>
        <button type="button" class="btn theme-filled-btn" data-bs-toggle="modal" data-bs-target="#createModal">
            + Create
        </button>
    </div>
    @php
        $columns = ['#', 'Name', 'Class', 'Category', 'Capacity', 'Note', 'Action'];
        $rows = [];
        if($sections && count($sections)) {
            foreach($sections as $index => $section) {
                $className = $section->class->name ?? ($classOptions[$section->class_id] ?? $section->class_id);
                $rows[] = [
                    $index + 1,
                    e($section->name),
                    e($className),
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
            <x-form.input label="Name" name="name" model="name" required='true' />
            <x-form.select2 label="Class" id="class_id" name="class_id" :options="$classOptions" model="class_id" placeholder="Select Class" required='true' />
            <x-form.input label="Category" name="category" model="category" />
            <x-form.input label="Capacity" name="capacity" model="capacity" />
            <x-form.ckeditor label="Note" name="note" model="note" />
        </form>
    </x-modal>
</x-sections.default>
