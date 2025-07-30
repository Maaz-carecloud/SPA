<?php

namespace App\Livewire\Module;

use App\Models\Module;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    // Remove modules property, DataTable will fetch via AJAX

    public function mount(){
        // No eager loading, DataTable will fetch via AJAX
    }

    // Modal related methods
    public $modalTitle = 'Create Module';
    public $modalAction = 'create-module';
    public $is_edit = false;
    public $deleteId;
    public $is_delete = false;

    // Form related methods
    #[Rule('required')]
    public $name;
    #[Rule('nullable')]
    public $created_by;
    #[Rule('nullable')]
    public $updated_by;

    public $getModule;

    #[On('create-module')]
    public function save(){
        $this->validate();
        Module::create([
            'name' => $this->name,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by
        ]);
        $this->dispatch('success', message: 'Module created successfully');
        $this->dispatch('hide-modal');
        $this->resetFields();
        $this->dispatch('datatable-reinit');
    }

    #[On('edit-mode')]
    public function loadEditModal($id){
        $this->modalTitle = 'Edit Module';
        $this->modalAction = 'edit-module';
        $this->is_edit = true;

        $this->getModule = Module::findOrfail($id);
        $this->name = $this->getModule->name;
        $this->created_by = $this->getModule->created_by;
        $this->updated_by = $this->getModule->updated_by;
    }

    #[On('edit-module')]
    public function update(){
        $this->validate();
        $m = Module::findOrFail($this->getModule->id);
        $m->name = $this->name;
        $m->created_by = Auth::user()->name;
        $m->updated_by = $this->updated_by;
        $m->save();
        $this->dispatch('success', message: 'Module updated successfully');
        $this->dispatch('hide-modal');
        $this->resetFields();
        $this->dispatch('datatable-reinit');
    }

    #[On('delete-record')]
    public function delete($id) {
        Module::findOrFail($id)->delete();
        $this->dispatch('success', message: 'Module deleted successfully');
        $this->dispatch('datatable-reinit');
    }

    #[On('create-module-close')]
    #[On('edit-module-close')]
    public function resetFields(){
        $this->reset(['name', 'created_by', 'updated_by', 'getModule']);
        $this->modalTitle = 'Create Module';
        $this->modalAction = 'create-module';
        $this->is_edit = false;
        $this->dispatch('hide-modal');
        $this->dispatch('datatable-reinit');
    }

    // Remove loadModules, DataTable will fetch via AJAX

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
        $query = Module::orderByDesc('created_at');

        if ($search) {
            $query->where('name', 'like', "%$search%")
                  ->orWhere('created_by', 'like', "%$search%")
                  ->orWhere('updated_by', 'like', "%$search%") ;
        }

        $total = $query->count();
        $modules = $query->skip($start)->take($length)->get();

        $data = [];
        foreach ($modules as $index => $module) {
            $actionHtml = '<div class="action-items">'
                . '<span><a href="#" onclick="Livewire.dispatch(\'edit-mode\', {id: ' . $module->id . '})" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fa fa-edit"></i></a></span>'
                . '<span><a href="javascript:void(0)" class="delete-swal" data-id="' . $module->id . '"><i class="fa fa-trash"></i></a></span></div>';
            $data[] = [
                $start + $index + 1,
                e($module->name),
                e($module->created_by),
                e($module->updated_by),
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

    #[Title('All Modules')]
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.module.index');
    }

}
