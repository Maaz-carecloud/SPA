<x-sections.default>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Product Categories</h3>
        <button type="button" class="btn theme-filled-btn" data-bs-toggle="modal" data-bs-target="#createModal">
            + Create
        </button>
    </div>

    @php
        $columns = ['#', 'Category Name', 'Description', 'Created By', 'Status', 'Date Added', 'Action'];
        $ajaxUrl = route('datatable.categories');
    @endphp
    <livewire:data-table :columns="$columns" table-id="categoriesTable" :ajax-url="$ajaxUrl" :key="microtime(true)" />

    <x-modal id="createModal" :title="$modalTitle" :action="$modalAction" :is_edit="$is_edit" :is_not_crud="false">
        <form>
            <x-form.input id="name" type="text" name="name" label="Category Name" 
                wire:model="name" placeholder="Enter category name" :error="$errors->first('name')" />
            
            <x-form.textarea id="description" name="description" label="Description" 
                wire:model="description" placeholder="Enter category description" rows="3" :error="$errors->first('description')" />
            
            <x-form.checkbox id="is_active" name="is_active" label="Is Active?" 
                wire:model="is_active" :checked="$is_active" />
        </form>
    </x-modal>
</x-sections.default>