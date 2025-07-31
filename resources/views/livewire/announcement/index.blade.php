<x-sections.default>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Announcements</h3>
        <button type="button" class="btn theme-filled-btn" data-bs-toggle="modal" data-bs-target="#createModal">
            + Create
        </button>
    </div>

    @php
        $columns = ['#', 'Title', 'Content', 'Author', 'Published?', 'Action'];
        $ajaxUrl = route('datatable.announcements');
    @endphp
    <livewire:data-table :columns="$columns" table-id="announcementsTable" :ajax-url="$ajaxUrl"
        :key="microtime(true)" />

    <x-modal id="createModal" :title="$modalTitle" :action="$modalAction" :is_edit="$is_edit" :is_not_crud="false">
        <form>
            <x-form.input id="title" name="title" label="Title" wire:model.defer="title"
                :error="$errors->first('title')" />
            <x-form.textarea id="content" name="content" label="Content" wire:model.defer="content"
                :error="$errors->first('content')" />
            <x-form.checkbox id="isPublished" name="is_published" label="Published?" wire:model.defer="is_published" />
            <div class="row">
                <div class="col-md-6">
                    <x-form.input id="published_at" type="datetime-local" name="published_at" label="Publish Date"
                        wire:model.defer="published_at" />
                </div>
                <div class="col-md-6">
                    <x-form.input id="expires_at" type="datetime-local" name="expires_at" label="Expires At"
                        wire:model.defer="expires_at" />
                </div>
            </div>
            <x-form.input id="image" name="image" label="Image URL" wire:model.defer="image" />
            <x-form.input id="link" name="link" label="Link" wire:model.defer="link" />
            <x-form.select id="status" name="status" label="Status" wire:model.defer="status"
                :options="['active' => 'Active', 'inactive' => 'Inactive', 'archived' => 'Archived']" />
        </form>
    </x-modal>
</x-sections.default>