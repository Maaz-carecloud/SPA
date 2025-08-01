<?php

namespace App\Livewire\Inventory\Supplier;

use App\Models\ProductSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

class Index extends Component
{
    public $suppliers;

    public function mount(){
        // Set default values
        $this->is_active = 1; // Default to active
    }

    //Modal related methods
    public $modalTitle = 'Create Supplier';
    public $modalAction = 'create-supplier';
    public $is_edit = false;
    public $deleteId;
    public $is_delete = false;

    //form related methods
    #[Rule('required')]
    public $name;
    #[Rule('required')]
    public $company_name;
    #[Rule('required|email')]
    public $email;
    #[Rule('required')]
    public $phone;
    #[Rule('nullable')]
    public $address;
    #[Rule('nullable')]
    public $is_active;

    public $getSuppliers;

    #[On('create-supplier')]
    public function save(){
        $this->validate();
        ProductSupplier::create([
            'name' => $this->name,
            'company_name' => $this->company_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'is_active' => $this->is_active ?? 1,
            'created_by' => Auth::user()->name ?? 'System',
            'updated_by' => Auth::user()->name ?? 'System'
        ]);
        $this->dispatch('success', message: 'Supplier created successfully');
        $this->dispatch('hide-modal');
        $this->resetFields();
        $this->dispatch('datatable-reinit');
    }

    #[On('edit-mode')]
    public function loadEditModal($id){
        $this->modalTitle = 'Edit Supplier';
        $this->modalAction = 'edit-supplier';
        $this->is_edit = true;

        $this->getSuppliers = ProductSupplier::findOrfail($id);
        $this->name = $this->getSuppliers->name;
        $this->company_name = $this->getSuppliers->company_name;
        $this->email = $this->getSuppliers->email;
        $this->phone = $this->getSuppliers->phone;
        $this->address = $this->getSuppliers->address;
        $this->is_active = $this->getSuppliers->is_active;
    }

    #[On('edit-supplier')]
    public function update(){
        $this->validate();

        $s = ProductSupplier::findOrFail($this->getSuppliers->id);
        $s->name = $this->name;
        $s->company_name = $this->company_name;
        $s->email = $this->email;
        $s->phone = $this->phone;
        $s->address = $this->address;
        $s->is_active = $this->is_active ?? 1;
        $s->updated_by = Auth::user()->name ?? 'System';
        $s->save();
        $this->dispatch('success', message: 'Supplier updated successfully');

        $this->dispatch('hide-modal');
        $this->resetFields();
        $this->dispatch('datatable-reinit');
    }

    #[On('delete-record')]
    public function delete($id) {
        ProductSupplier::findOrFail($id)->delete();
        $this->dispatch('success', message: 'Supplier deleted successfully');
        $this->dispatch('datatable-reinit');
    }

    #[On('create-supplier-close')]
    #[On('edit-supplier-close')]
    public function resetFields(){
        $this->reset(['name', 'company_name', 'email', 'phone', 'address', 'is_active', 'getSuppliers']);
        $this->modalTitle = 'Create Supplier';
        $this->modalAction = 'create-supplier';
        $this->is_edit = false;
        $this->is_active = 1; // Default to active
        $this->dispatch('hide-modal');
        $this->dispatch('datatable-reinit');
    }

    #[Title('Suppliers')]
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.inventory.supplier.index');
    }

    // Server-side DataTables AJAX handler
    public function getDataTableRows(Request $request)
    {
        $length = $request->input('length');
        $start = $request->input('start');

        $query = ProductSupplier::query();

        // Search
        if (!empty($request['search']['value'])) {
            $search = $request['search']['value'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('company_name', 'like', '%' . $search . '%')
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

        $suppliers = $query->orderBy('created_at', 'desc')->get();

        $rows = [];
        foreach ($suppliers as $index => $supplier) {
            $statusBadge = $supplier->is_active ? 
                '<span class="badge bg-success">Active</span>' : 
                '<span class="badge bg-danger">Inactive</span>';

            $rows[] = [
                $index + 1,
                e($supplier->company_name ?? 'N/A'),
                e($supplier->name ?? 'N/A'),
                e($supplier->email ?? 'N/A'),
                e($supplier->phone ?? 'N/A'),
                e($supplier->address ?? 'N/A'),
                $statusBadge,
                e($supplier->created_at ? $supplier->created_at->format('M d, Y') : 'N/A'),
                '<div class="action-items">'
                . '<span><a href="#" onclick="Livewire.dispatch(\'edit-mode\', {id: ' . $supplier->id . '})" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fa fa-edit"></i></a></span>'
                . '<span><a href="javascript:void(0)" class="delete-swal" data-id="' . $supplier->id . '"><i class="fa fa-trash"></i></a></span>'
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
