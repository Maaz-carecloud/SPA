<?php

namespace App\Livewire\Inventory\Warehouse;

use App\Models\ProductWarehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

class Index extends Component
{
    public $warehouses;

    public function mount(){
        // Set default values
        $this->is_active = 1; // Default to active
    }

    //Modal related methods
    public $modalTitle = 'Create Warehouse';
    public $modalAction = 'create-warehouse';
    public $is_edit = false;
    public $deleteId;
    public $is_delete = false;

    //form related methods
    #[Rule('required')]
    public $name;
    #[Rule('required')]
    public $code;
    #[Rule('nullable|email')]
    public $email;
    #[Rule('nullable')]
    public $phone;
    #[Rule('nullable')]
    public $address;
    #[Rule('nullable')]
    public $is_active;

    public $getWarehouses;

    #[On('create-warehouse')]
    public function save(){
        $this->validate();
        ProductWarehouse::create([
            'name' => $this->name,
            'code' => $this->code,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'is_active' => $this->is_active ?? 1,
            'created_by' => Auth::user()->name ?? 'System',
            'updated_by' => Auth::user()->name ?? 'System'
        ]);
        $this->dispatch('success', message: 'Warehouse created successfully');
        $this->dispatch('hide-modal');
        $this->resetFields();
        $this->dispatch('datatable-reinit');
    }

    #[On('edit-mode')]
    public function loadEditModal($id){
        $this->modalTitle = 'Edit Warehouse';
        $this->modalAction = 'edit-warehouse';
        $this->is_edit = true;

        $this->getWarehouses = ProductWarehouse::findOrfail($id);
        $this->name = $this->getWarehouses->name;
        $this->code = $this->getWarehouses->code;
        $this->email = $this->getWarehouses->email;
        $this->phone = $this->getWarehouses->phone;
        $this->address = $this->getWarehouses->address;
        $this->is_active = $this->getWarehouses->is_active;
    }

    #[On('edit-warehouse')]
    public function update(){
        $this->validate();

        $w = ProductWarehouse::findOrFail($this->getWarehouses->id);
        $w->name = $this->name;
        $w->code = $this->code;
        $w->email = $this->email;
        $w->phone = $this->phone;
        $w->address = $this->address;
        $w->is_active = $this->is_active ?? 1;
        $w->updated_by = Auth::user()->name ?? 'System';
        $w->save();
        $this->dispatch('success', message: 'Warehouse updated successfully');

        $this->dispatch('hide-modal');
        $this->resetFields();
        $this->dispatch('datatable-reinit');
    }

    #[On('delete-record')]
    public function delete($id) {
        ProductWarehouse::findOrFail($id)->delete();
        $this->dispatch('success', message: 'Warehouse deleted successfully');
        $this->dispatch('datatable-reinit');
    }

    #[On('create-warehouse-close')]
    #[On('edit-warehouse-close')]
    public function resetFields(){
        $this->reset(['name', 'code', 'email', 'phone', 'address', 'is_active', 'getWarehouses']);
        $this->modalTitle = 'Create Warehouse';
        $this->modalAction = 'create-warehouse';
        $this->is_edit = false;
        $this->is_active = 1; // Default to active
        $this->dispatch('hide-modal');
        $this->dispatch('datatable-reinit');
    }

    #[Title('Warehouses')]
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.inventory.warehouse.index');
    }

    // Server-side DataTables AJAX handler
    public function getDataTableRows(Request $request)
    {
        $length = $request->input('length');
        $start = $request->input('start');

        $query = ProductWarehouse::query();

        // Search
        if (!empty($request['search']['value'])) {
            $search = $request['search']['value'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('code', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%')
                  ->orWhere('address', 'like', '%' . $search . '%');
            });
        }

        $total = $query->count();

        if ($length == -1) {
            // Set a reasonable max limit for 'All'
            $length = $total;
        }

        $query->skip($start)->take($length);

        $warehouses = $query->orderBy('created_at', 'desc')->get();

        $rows = [];
        foreach ($warehouses as $index => $warehouse) {
            $statusBadge = $warehouse->is_active ? 
                '<span class="badge bg-success">Active</span>' : 
                '<span class="badge bg-danger">Inactive</span>';

            $rows[] = [
                $index + 1,
                e($warehouse->name ?? 'N/A'),
                e($warehouse->code ?? 'N/A'),
                e($warehouse->email ?? 'N/A'),
                e($warehouse->phone ?? 'N/A'),
                e($warehouse->address ?? 'N/A'),
                $statusBadge,
                e($warehouse->created_at ? $warehouse->created_at->format('M d, Y') : 'N/A'),
                '<div class="action-items">'
                . '<span><a href="#" onclick="Livewire.dispatch(\'edit-mode\', {id: ' . $warehouse->id . '})" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fa fa-edit"></i></a></span>'
                . '<span><a href="javascript:void(0)" class="delete-swal" data-id="' . $warehouse->id . '"><i class="fa fa-trash"></i></a></span>'
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
