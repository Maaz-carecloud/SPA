<x-sections.default>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Posts</h3>
            <button type="button" class="btn theme-filled-btn" data-bs-toggle="modal" data-bs-target="#createModal">
                + Create
            </button>
        </div>


        @php
            $columns = ['#', 'Title', 'Description', 'Author', 'Is Published?', 'Action'];
        @endphp
        <livewire:data-table :columns="$columns" table-id="postsTable" ajax-url="/datatable/posts" :key="microtime(true)" />

        <x-modal id="createModal" :title="$modalTitle" :action="$modalAction" :is_edit="$is_edit">
            <form>
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" wire:model="title">
                    @error('title')
                    <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <input type="text" class="form-control" id="description" wire:model="description">
                    @error('description')
                    <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="author" class="form-label">Author</label>
                    <input type="text" class="form-control" id="author" wire:model="author">
                    @error('author')
                    <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1" wire:model="is_published">
                    <label class="form-check-label" for="exampleCheck1">Is Published?</label>
                </div>
            </form>
        </x-modal>
</x-sections.default>