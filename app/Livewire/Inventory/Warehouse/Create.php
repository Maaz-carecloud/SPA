<?php

namespace App\Livewire\Inventory\Warehouse;

use Livewire\Component;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{    
    public $name = '';
    public $code = '';
    public $email = '';
    public $phone = '';
    public $address = '';
    public $is_active = 1;

    protected $rules = [
        'name' => 'required|string|max:255|unique:product_warehouses,name',
        'code' => 'required|string|max:255',
        'email' => 'nullable|email|unique:product_warehouses,email',
        'phone' => 'nullable|string|max:255|unique:product_warehouses,phone',
        'address' => 'nullable|string|max:1000',
        'is_active' => 'required|boolean',
    ];

    protected $messages = [
        'name.required' => 'Warehouse name is required.',
        'name.unique' => 'Warehouse name already exists.',
        'code.required' => 'Warehouse code is required.',
        'email.email' => 'Please enter a valid email address.',
        'email.unique' => 'Email already exists.',
        'phone.unique' => 'Phone number already exists.',
    ];

    public function createWarehouse()
    {
        $this->validate();

        try {            
            ProductWarehouse::create([
                'name' => $this->name,
                'code' => $this->code,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'is_active' => $this->is_active,
                'created_by' => Auth::user()->name,
                'updated_by' => Auth::user()->name,
            ]);

            $this->dispatch('success', message: 'Warehouse created successfully.');
            $this->redirect('/warehouses', navigate: true);
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error creating warehouse: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.inventory.warehouse.create');
    }
}
