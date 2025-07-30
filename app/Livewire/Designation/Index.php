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
    // Remove designations property, DataTable will fetch via AJAX

    public function mount(){
        // No eager loading, DataTable will fetch via AJAX
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
        $this->dispatch('datatable-reinit');
    }

    // Remove loadDesignations, DataTable will fetch via AJAX

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
        $query = Designation::orderByDesc('created_at');

        if ($search) {
            $query->where('name', 'like', "%$search%")
                  ->orWhere('created_by', 'like', "%$search%")
                  ->orWhere('updated_by', 'like', "%$search%") ;
        }

        $total = $query->count();
        $designations = $query->skip($start)->take($length)->get();

        $data = [];
        foreach ($designations as $index => $designation) {
            $actionHtml = '<div class="action-items">'
                . '<span><a href="#" onclick="Livewire.dispatch(\'edit-mode\', {id: ' . $designation->id . '})" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fa fa-edit"></i></a></span>'
                . '<span><a href="javascript:void(0)" class="delete-swal" data-id="' . $designation->id . '"><i class="fa fa-trash"></i></a></span></div>';
            $data[] = [
                $start + $index + 1,
                e($designation->name),
                e($designation->created_by),
                e($designation->updated_by),
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

    #[Title('All Designations')]
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.designation.index');
    }
}
