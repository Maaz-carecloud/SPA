<?php

namespace App\Livewire\Section;

use App\Models\Section;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Models\ClassModel;

class Index extends Component
{
    // Remove sections property, DataTable will fetch via AJAX

    public function mount(){
        $this->classOptions = ClassModel::orderBy('name')->pluck('name', 'id')->toArray();
    }

    // Modal related methods
    public $modalTitle = 'Create Section';
    public $modalAction = 'create-section';
    public $is_edit = false;
    public $deleteId;
    public $is_delete = false;

    // Form related methods
    #[Rule('required')]
    public $name;
    #[Rule('required')]
    public $class_id;
    #[Rule('nullable')]
    public $category;
    #[Rule('nullable')]
    public $capacity;
    #[Rule('nullable')]
    public $note;
    #[Rule('nullable')]
    public $created_by;
    #[Rule('nullable')]
    public $updated_by;

    public $getSection;

    #[On('create-section')]
    public function save(){
        $this->validate();
        Section::create([
            'name' => $this->name,
            'class_id' => $this->class_id,
            'category' => $this->category,
            'capacity' => $this->capacity,
            'note' => $this->note,
            'created_by' => Auth::user()->name,
            'updated_by' => $this->updated_by
        ]);
        $this->dispatch('success', message: 'Section created successfully');
        $this->dispatch('hide-modal');
        $this->resetFields();
        $this->dispatch('datatable-reinit');
    }

    #[On('edit-mode')]
    public function loadEditModal($id){
        $this->modalTitle = 'Edit Section';
        $this->modalAction = 'edit-section';
        $this->is_edit = true;

        $this->getSection = Section::findOrfail($id);
        $this->name = $this->getSection->name;
        $this->class_id = $this->getSection->class_id;
        $this->category = $this->getSection->category;
        $this->capacity = $this->getSection->capacity;
        $this->note = $this->getSection->note;
        $this->created_by = $this->getSection->created_by;
        $this->updated_by = $this->getSection->updated_by;
    }

    #[On('edit-section')]
    public function update(){
        $this->validate();
        $s = Section::findOrFail($this->getSection->id);
        $s->name = $this->name;
        $s->class_id = $this->class_id;
        $s->category = $this->category;
        $s->capacity = $this->capacity;
        $s->note = $this->note;
        $s->created_by = $this->created_by;
        $s->updated_by = Auth::user()->name;
        $s->save();
        $this->dispatch('success', message: 'Section updated successfully');
        $this->dispatch('hide-modal');
        $this->resetFields();
        $this->dispatch('datatable-reinit');
    }

    #[On('delete-record')]
    public function delete($id) {
        Section::findOrFail($id)->delete();
        $this->dispatch('success', message: 'Section deleted successfully');
        $this->dispatch('datatable-reinit');
    }

    #[On('create-section-close')]
    #[On('edit-section-close')]
    public function resetFields(){
        $this->reset(['name', 'class_id', 'category', 'capacity', 'note', 'created_by', 'updated_by', 'getSection']);
        $this->modalTitle = 'Create Section';
        $this->modalAction = 'create-section';
        $this->is_edit = false;
        $this->dispatch('hide-modal');
        $this->dispatch('datatable-reinit');
    }

    // Remove loadSections, DataTable will fetch via AJAX

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
        $query = Section::with('class')->orderByDesc('created_at');

        if ($search) {
            $query->where('name', 'like', "%$search%")
                  ->orWhere('category', 'like', "%$search%")
                  ->orWhere('capacity', 'like', "%$search%")
                  ->orWhere('note', 'like', "%$search%")
                  ->orWhereHas('class', function($q) use ($search) {
                      $q->where('name', 'like', "%$search%") ;
                  });
        }

        $total = $query->count();
        $sections = $query->skip($start)->take($length)->get();

        $data = [];
        foreach ($sections as $index => $section) {
            $className = $section->class->name ?? ($this->classOptions[$section->class_id] ?? $section->class_id);
            $data[] = [
                $start + $index + 1,
                e($section->name),
                e($className),
                e($section->category),
                e($section->capacity),
                e($section->note),
                '<div class="action-items"><span><a href="#" onclick="Livewire.dispatch(\'edit-mode\', {id: ' . $section->id . '})" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fa fa-edit"></i></a></span>'
                . '<span><a href="javascript:void(0)" class="delete-swal" data-id="' . $section->id . '"><i class="fa fa-trash"></i></a></span></div>'
            ];
        }

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data,
        ]);
    }

    public $classOptions = [];

    #[Title('All Sections')]
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.section.index', [
            'classOptions' => $this->classOptions,
        ]);
    }
}
