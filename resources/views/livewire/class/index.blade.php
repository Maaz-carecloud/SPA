<x-sections.default>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Classes</h3>
        <button type="button" class="btn theme-filled-btn" data-bs-toggle="modal" data-bs-target="#createModal">
            + Create
        </button>
    </div>
    @php
        $columns = ['#', 'Name', 'Class Numeric', 'Teacher Name', 'Action'];
        $ajaxUrl = route('datatable.classes');
    @endphp
    <livewire:data-table :columns="$columns" table-id="classesTable" :ajax-url="$ajaxUrl" :key="microtime(true)" />
    
    <x-modal id="createModal" :title="$modalTitle" :action="$modalAction" :is_edit="$is_edit">
        <form>
            <x-form.input label="Name" name="name" id="name" model="name" />
            <x-form.input label="Class Numeric" name="class_numeric" id="class_numeric" model="class_numeric" />
            <x-form.select2 label="Teacher Name" name="teacher_id" id="teacher_id" :options="$teacherOptions" model="teacher_id" placeholder="Select Teacher" />
        </form>
    </x-modal>
</x-sections.default>