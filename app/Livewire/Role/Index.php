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
    // Remove roles property, DataTable will fetch via AJAX

    public function mount(){
        $this->permissions = \App\Models\Permission::orderBy('module_name')->get();
        // No eager loading, DataTable will fetch via AJAX
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
        $this->dispatch('datatable-reinit');
    }

    // Remove loadRoles, DataTable will fetch via AJAX

    // Server-side DataTable AJAX handler
    public function getDataTableRows()
    {
        $request = request();
        $search = $request->input('search.value');
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        if ($length == -1) {
            $length = 1000; // Safe upper limit for 'All'
        }
        $query = Role::with('permissions')->orderByDesc('created_at');

        if ($search) {
            $query->where('name', 'like', "%$search%")
                  ->orWhereHas('permissions', function($q) use ($search) {
                      $q->where('name', 'like', "%$search%") ;
                  });
        }

        $total = $query->count();
        $roles = $query->skip($start)->take($length)->get();

        $data = [];
        foreach ($roles as $index => $role) {
            $permissionNames = $role->permissions->pluck('name')->implode(', ');
            $actionHtml = '<div class="action-items">'
                . '<span><a href="#" @click.prevent="$dispatch(\'edit-mode\', {id: ' . $role->id . '})" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fa fa-edit"></i></a></span>'
                . '<span><a href="javascript:void(0)" class="delete-swal" data-id="' . $role->id . '"><i class="fa fa-trash"></i></a></span></div>';
            $data[] = [
                $start + $index + 1,
                e($role->name),
                e($permissionNames),
                $actionHtml
            ];
        }

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data,
        ]);
    }

    #[Title('All Roles')]
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.role.index');
    }
}
