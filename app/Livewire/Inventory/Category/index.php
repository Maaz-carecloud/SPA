<?php

namespace App\Livewire\Inventory\Category;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

class Index extends Component
{
    public $categories;

    public function mount(){
        // Set default values
        $this->is_active = 1; // Default to active
    }

    //Modal related methods
    public $modalTitle = 'Create Category';
    public $modalAction = 'create-category';
    public $is_edit = false;
    public $deleteId;
    public $is_delete = false;

    //form related methods
    #[Rule('required')]
    public $name;
    #[Rule('nullable')]
    public $description;
    #[Rule('nullable')]
    public $is_active;

    public $getCategories;

    #[On('create-category')]
    public function save(){
        $this->validate();
        ProductCategory::create([
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active ?? 1,
            'created_by' => Auth::user()->name ?? 'System',
            'updated_by' => Auth::user()->name ?? 'System'
        ]);
        $this->dispatch('success', message: 'Category created successfully');
        $this->dispatch('hide-modal');
        $this->resetFields();
        $this->dispatch('datatable-reinit');
    }

    #[On('edit-mode')]
    public function loadEditModal($id){
        $this->modalTitle = 'Edit Category';
        $this->modalAction = 'edit-category';
        $this->is_edit = true;

        $this->getCategories = ProductCategory::findOrfail($id);
        $this->name = $this->getCategories->name;
        $this->description = $this->getCategories->description;
        $this->is_active = $this->getCategories->is_active;
    }

    #[On('edit-category')]
    public function update(){
        $this->validate();

        $c = ProductCategory::findOrFail($this->getCategories->id);
        $c->name = $this->name;
        $c->description = $this->description;
        $c->is_active = $this->is_active ?? 1;
        $c->updated_by = Auth::user()->name ?? 'System';
        $c->save();
        $this->dispatch('success', message: 'Category updated successfully');

        $this->dispatch('hide-modal');
        $this->resetFields();
        $this->dispatch('datatable-reinit');
    }

    #[On('delete-record')]
    public function delete($id) {
        ProductCategory::findOrFail($id)->delete();
        $this->dispatch('success', message: 'Category deleted successfully');
        $this->dispatch('datatable-reinit');
    }

    #[On('create-category-close')]
    #[On('edit-category-close')]
    public function resetFields(){
        $this->reset(['name', 'description', 'is_active', 'getCategories']);
        $this->modalTitle = 'Create Category';
        $this->modalAction = 'create-category';
        $this->is_edit = false;
        $this->is_active = 1; // Default to active
        $this->dispatch('hide-modal');
        $this->dispatch('datatable-reinit');
    }

    #[Title('Product Categories')]
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.inventory.category.index');
    }

    // Server-side DataTables AJAX handler
    public function getDataTableRows(Request $request)
    {
        $length = $request->input('length');
        $start = $request->input('start');

        $query = ProductCategory::query();

        // Search
        if (!empty($request['search']['value'])) {
            $search = $request['search']['value'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('created_by', 'like', '%' . $search . '%');
            });
        }

        $total = $query->count();

        if ($length == -1) {
            // Set a reasonable max limit for 'All'
            $length = $total;
        }

        $query->skip($start)->take($length);

        $categories = $query->orderBy('created_at', 'desc')->get();

        $rows = [];
        foreach ($categories as $index => $category) {
            $statusBadge = $category->is_active ? 
                '<span class="badge bg-success">Active</span>' : 
                '<span class="badge bg-danger">Inactive</span>';

            $rows[] = [
                $index + 1,
                e($category->name),
                e($category->description ?? 'No description'),
                e($category->created_by ?? 'System'),
                $statusBadge,
                e($category->created_at ? $category->created_at->format('M d, Y') : 'N/A'),
                '<div class="action-items">'
                . '<span><a href="#" onclick="Livewire.dispatch(\'edit-mode\', {id: ' . $category->id . '})" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fa fa-edit"></i></a></span>'
                . '<span><a href="javascript:void(0)" class="delete-swal" data-id="' . $category->id . '"><i class="fa fa-trash"></i></a></span>'
                . '</div>',
            ];
        }

        return response()->json([
            'draw' => intval($request['draw']),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $rows,
        ]);
    }
}
