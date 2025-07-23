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
    public $modules;

    public function mount(){
        $this->loadModules();
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
        $this->loadModules();
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
        $this->loadModules();
        $this->dispatch('datatable-reinit');
    }

    public function loadModules(){
        $this->modules = Module::orderByDesc('created_at')->get();
    }

    #[Title('All Modules')]
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.module.index');
    }

}
