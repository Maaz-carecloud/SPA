<?php

namespace App\Livewire\Designation;

use App\Models\Designation;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public $designations;

    public function mount(){
        $this->loadDesignations();
    }

    // Modal related methods
    public $modalTitle = 'Create Designation';
    public $modalAction = 'create-designation';
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

    public $getDesignation;

    #[On('create-designation')]
    public function save(){
        $this->validate();
        Designation::create([
            'name' => $this->name,
            'created_by' => Auth::user()->name,
            'updated_by' => $this->updated_by
        ]);
        $this->dispatch('success', message: 'Designation created successfully');
        $this->dispatch('hide-modal');
        $this->resetFields();
        $this->dispatch('datatable-reinit');
    }

    #[On('edit-mode')]
    public function loadEditModal($id){
        $this->modalTitle = 'Edit Designation';
        $this->modalAction = 'edit-designation';
        $this->is_edit = true;

        $this->getDesignation = Designation::findOrfail($id);
        $this->name = $this->getDesignation->name;
        $this->created_by = $this->getDesignation->created_by;
        $this->updated_by = $this->getDesignation->updated_by;
    }

    #[On('edit-designation')]
    public function update(){
        $this->validate();
        $d = Designation::findOrFail($this->getDesignation->id);
        $d->name = $this->name;
        $d->created_by = $this->created_by;
        $d->updated_by = Auth::user()->name;
        $d->save();
        $this->dispatch('success', message: 'Designation updated successfully');
        $this->dispatch('hide-modal');
        $this->resetFields();
        $this->dispatch('datatable-reinit');
    }

    #[On('delete-record')]
    public function delete($id) {
        Designation::findOrFail($id)->delete();
        $this->dispatch('success', message: 'Designation deleted successfully');
        $this->loadDesignations();
        $this->dispatch('datatable-reinit');
    }

    #[On('create-designation-close')]
    #[On('edit-designation-close')]
    public function resetFields(){
        $this->reset(['name', 'created_by', 'updated_by', 'getDesignation']);
        $this->modalTitle = 'Create Designation';
        $this->modalAction = 'create-designation';
        $this->is_edit = false;
        $this->dispatch('hide-modal');
        $this->loadDesignations();
        $this->dispatch('datatable-reinit');
    }

    public function loadDesignations(){
        $this->designations = Designation::orderByDesc('created_at')->get();
    }

    #[Title('All Designations')]
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.designation.index');
    }
}
