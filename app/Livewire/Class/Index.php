<?php

namespace App\Livewire\Class;

use App\Models\ClassModel;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    // Remove classes property, DataTable will fetch via AJAX

    public $teacherOptions = [];

    public function mount(){
        $this->teacherOptions = \App\Models\User::where('user_type', 'teacher')->pluck('name', 'id')->toArray();
    }

    // Modal related methods
    public $modalTitle = 'Create Class';
    public $modalAction = 'create-class';
    public $is_edit = false;
    public $deleteId;
    public $is_delete = false;

    // Form related methods
    #[Rule('required')]
    public $name;
    #[Rule('required')]
    public $class_numeric;
    #[Rule('nullable')]
    public $teacher_id;
    #[Rule('nullable')]
    public $created_by;
    #[Rule('nullable')]
    public $updated_by;

    public $getClass;

    #[On('create-class')]
    public function save(){
        $this->validate();
        ClassModel::create([
            'name' => $this->name,
            'class_numeric' => $this->class_numeric,
            'teacher_id' => $this->teacher_id,
            'created_by' => Auth::user()->name,
            'updated_by' => $this->updated_by
        ]);
        $this->dispatch('success', message: 'Class created successfully');
        $this->dispatch('hide-modal');
        $this->resetFields();
        $this->dispatch('datatable-reinit');
    }

    #[On('edit-mode')]
    public function loadEditModal($id){
        $this->modalTitle = 'Edit Class';
        $this->modalAction = 'edit-class';
        $this->is_edit = true;

        $this->getClass = ClassModel::findOrfail($id);
        $this->name = $this->getClass->name;
        $this->class_numeric = $this->getClass->class_numeric;
        $this->teacher_id = $this->getClass->teacher_id;
        $this->created_by = $this->getClass->created_by;
        $this->updated_by = $this->getClass->updated_by;
    }

    #[On('edit-class')]
    public function update(){
        $this->validate();
        $c = ClassModel::findOrFail($this->getClass->id);
        $c->name = $this->name;
        $c->class_numeric = $this->class_numeric;
        $c->teacher_id = $this->teacher_id;
        $c->created_by = $this->created_by;
        $c->updated_by = Auth::user()->name;
        $c->save();
        $this->dispatch('success', message: 'Class updated successfully');
        $this->dispatch('hide-modal');
        $this->resetFields();
        $this->dispatch('datatable-reinit');
    }

    #[On('delete-record')]
    public function delete($id) {
        ClassModel::findOrFail($id)->delete();
        $this->dispatch('success', message: 'Class deleted successfully');
        $this->dispatch('datatable-reinit');
    }

    #[On('create-class-close')]
    #[On('edit-class-close')]
    public function resetFields(){
        $this->reset(['name', 'class_numeric', 'teacher_id', 'created_by', 'updated_by', 'getClass']);
        $this->modalTitle = 'Create Class';
        $this->modalAction = 'create-class';
        $this->is_edit = false;
        $this->dispatch('hide-modal');
        $this->dispatch('datatable-reinit');
    }

    // Remove loadClasses, DataTable will fetch via AJAX

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
        $query = ClassModel::with('teacher.user')->orderByDesc('created_at');

        if ($search) {
            $query->where('name', 'like', "%$search%")
                  ->orWhere('class_numeric', 'like', "%$search%")
                  ->orWhereHas('teacher.user', function($q) use ($search) {
                      $q->where('name', 'like', "%$search%") ;
                  });
        }

        $total = $query->count();
        $classes = $query->skip($start)->take($length)->get();

        $data = [];
        foreach ($classes as $index => $class) {
            $data[] = [
                $start + $index + 1,
                e($class->name),
                e($class->class_numeric),
                $class->teacher && $class->teacher->user ? e($class->teacher->user->name) : 'N/A',
                '<div class="action-items"><span><a href="#" onclick="Livewire.dispatch(\'edit-mode\', {id: ' . $class->id . '})" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fa fa-edit"></i></a></span>'
                . '<span><a href="javascript:void(0)" class="delete-swal" data-id="' . $class->id . '"><i class="fa fa-trash"></i></a></span></div>'
            ];
        }

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data,
        ]);
    }

    #[Title('All Classes')]
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.class.index', [
            'teacherOptions' => $this->teacherOptions,
        ]);
    }
}
