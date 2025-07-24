<x-sections.default>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Roles</h3>
        <button type="button" class="btn theme-filled-btn" data-bs-toggle="modal" data-bs-target="#createModal">
            + Create
        </button>
    </div>
    @php
        $columns = ['#', 'Name', 'Permissions', 'Action'];
        $rows = [];
        if($roles && count($roles)) {
            foreach($roles as $index => $role) {
                $permissionNames = $role->permissions->pluck('name')->implode(', ');
                $rows[] = [
                    $index + 1,
                    e($role->name),
                    e($permissionNames),
                    '<div class="action-items"><span><a @click.prevent="$dispatch(\'edit-mode\', {id: ' . $role->id . '})" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fa fa-edit"></i></a></span>'
                    . '<span><a href="javascript:void(0)" class="delete-swal" data-id="' . $role->id . '"><i class="fa fa-trash"></i></a></span></div>'
                ];
            }
        }
    @endphp
    <livewire:data-table :columns="$columns" :rows="$rows" table-id="rolesTable" :key="microtime(true)" />
    
    <x-modal id="createModal" :title="$modalTitle" :action="$modalAction" :is_edit="$is_edit">
        <form>
            <x-form.input 
                label="Name" 
                name="name" 
                model="name" 
                :required="true" 
                placeholder="Enter role name" 
            />
            <div class="row">
                @foreach($permissions->groupBy('module_name') as $module => $modulePermissions)
                    <div class="col-md-4 mb-2">
                        <div class="border rounded p-2 mb-2">
                            <strong>{{ $module ?? 'Other' }}</strong>
                            <div class="ms-2">
                                @forelse($modulePermissions as $permission)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="create_perm_{{ $permission->id }}" value="{{ $permission->id }}" wire:model="selectedPermissions">
                                        <label class="form-check-label" for="create_perm_{{ $permission->id }}">
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                                @empty
                                    <div class="text-muted small">
                                        Please add permissions first.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @error('selectedPermissions') 
                <span class="text-danger small d-block mt-2">{{ $message }}</span> 
            @enderror
        </form>
    </x-modal>
</x-sections.default>
