<div>
    <div class="row g-3">
        <div class="col-md-12">
            <x-form.input 
                label="Role Name"
                name="roleName"
                id="create-role-name" 
                model="roleName"
                placeholder="Enter role name"
                :required="true"
            />
            @error('roleName') 
                <div class="text-danger mt-1">{{ $message }}</div> 
            @enderror
        </div>
    </div>
    
    <h6 class="mt-4 mb-3 text-theme-primary">Assign Permissions</h6>
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
</div>

