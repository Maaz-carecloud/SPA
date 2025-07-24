<?php

namespace App\Livewire\Role;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public $roles;

    public function mount(){
        $this->loadRoles();
        $this->permissions = \App\Models\Permission::orderBy('module_name')->get();
    }

    // Modal related methods
    public $modalTitle = 'Create Role';
    public $modalAction = 'create-role';
    public $is_edit = false;
    public $deleteId;
    public $is_delete = false;

    // Form related methods
    #[Rule('required')]
    public $name;

    public $getRole;

    public $selectedPermissions = [];
    public $permissions;

    #[On('create-role')]
    public function save(){
        $this->validate();
        $role = Role::create([
            'name' => $this->name
        ]);
        $permissionNames = \App\Models\Permission::whereIn('id', $this->selectedPermissions)->pluck('name')->toArray();
        $role->syncPermissions($permissionNames);
        $this->dispatch('success', message: 'Role created successfully');
        $this->dispatch('hide-modal');
        $this->resetFields();
        $this->dispatch('datatable-reinit');
    }

    #[On('edit-mode')]
    public function loadEditModal($id){
        $this->modalTitle = 'Edit Role';
        $this->modalAction = 'edit-role';
        $this->is_edit = true;

        $this->getRole = Role::findOrfail($id);
        $this->name = $this->getRole->name;
        $this->selectedPermissions = $this->getRole->permissions->pluck('id')->toArray();
    }

    #[On('edit-role')]
    public function update(){
        $this->validate();
        $r = Role::findOrFail($this->getRole->id);
        $r->name = $this->name;
        $r->save();
        $permissionNames = \App\Models\Permission::whereIn('id', $this->selectedPermissions)->pluck('name')->toArray();
        $r->syncPermissions($permissionNames);
        $this->dispatch('success', message: 'Role updated successfully');
        $this->dispatch('hide-modal');
        $this->resetFields();
        $this->dispatch('datatable-reinit');
    }

    #[On('delete-record')]
    public function delete($id) {
        Role::findOrFail($id)->delete();
        $this->dispatch('success', message: 'Role deleted successfully');
        $this->loadRoles();
        $this->dispatch('datatable-reinit');
    }

    #[On('create-role-close')]
    #[On('edit-role-close')]
    public function resetFields(){
        $this->reset(['name', 'getRole', 'selectedPermissions']);
        $this->modalTitle = 'Create Role';
        $this->modalAction = 'create-role';
        $this->is_edit = false;
        $this->dispatch('hide-modal');
        $this->loadRoles();
        $this->dispatch('datatable-reinit');
    }

    public function loadRoles(){
        $this->roles = Role::with('permissions')->orderByDesc('created_at')->get();
    }

    #[Title('All Roles')]
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.role.index');
    }
}
