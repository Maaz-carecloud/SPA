<?php

namespace App\Livewire\Permission;

use App\Models\Permission;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public $permissions;
    public $modules = [];

    public function mount(){
        $this->loadPermissions();
        $this->modules = \App\Models\Module::orderBy('name')->pluck('name', 'name')->toArray();
    }

    // Modal related methods
    public $modalTitle = 'Create Permission';
    public $modalAction = 'create-permission';
    public $is_edit = false;
    public $deleteId;
    public $is_delete = false;

    // Form related methods
    #[Rule('required')]
    public $name;
    #[Rule('required')]
    public $module_name;
    #[Rule('nullable')]
    public $guard_name;

    public $getPermission;

    #[On('create-permission')]
    public function save(){
        $this->validate();
        Permission::create([
            'name' => $this->name,
            'module_name' => $this->module_name,
            'guard_name' => $this->guard_name
        ]);
        $this->dispatch('success', message: 'Permission created successfully');
        $this->dispatch('hide-modal');
        $this->resetFields();
        $this->dispatch('datatable-reinit');
    }

    #[On('edit-mode')]
    public function loadEditModal($id){
        $this->modalTitle = 'Edit Permission';
        $this->modalAction = 'edit-permission';
        $this->is_edit = true;

        $this->getPermission = Permission::findOrfail($id);
        $this->name = $this->getPermission->name;
        $this->module_name = $this->getPermission->module_name;
        $this->guard_name = $this->getPermission->guard_name;
    }

    #[On('edit-permission')]
    public function update(){
        $this->validate();
        $p = Permission::findOrFail($this->getPermission->id);
        $p->name = $this->name;
        $p->module_name = $this->module_name;
        $p->guard_name = $this->guard_name;
        $p->save();
        $this->dispatch('success', message: 'Permission updated successfully');
        $this->dispatch('hide-modal');
        $this->resetFields();
        $this->dispatch('datatable-reinit');
    }

    #[On('delete-record')]
    public function delete($id) {
        Permission::findOrFail($id)->delete();
        $this->dispatch('success', message: 'Permission deleted successfully');
        $this->loadPermissions();
        $this->dispatch('datatable-reinit');
    }

    #[On('create-permission-close')]
    #[On('edit-permission-close')]
    public function resetFields(){
        $this->reset(['name', 'module_name', 'guard_name', 'getPermission']);
        $this->modalTitle = 'Create Permission';
        $this->modalAction = 'create-permission';
        $this->is_edit = false;
        $this->dispatch('hide-modal');
        $this->loadPermissions();
        $this->dispatch('datatable-reinit');
    }

    public function loadPermissions(){
        $this->permissions = Permission::orderByDesc('created_at')->get();
    }

    #[Title('All Permissions')]
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.permission.index', [
            'modules' => $this->modules,
        ]);
    }
}
