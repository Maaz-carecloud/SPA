<?php

namespace App\Livewire\Inventory\Warehouse;

use Livewire\Component;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\Auth;

class Edit extends Component
{    
    public $warehouse;
    public $name = '';
    public $code = '';
    public $email = '';
    public $phone = '';
    public $address = '';
    public $is_active = 1;

    protected $rules = [
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:255',
        'email' => 'nullable|email',
        'phone' => 'nullable|string|max:255',
        'address' => 'nullable|string|max:1000',
        'is_active' => 'required|boolean',
    ];

    protected $messages = [
        'name.required' => 'Warehouse name is required.',
        'code.required' => 'Warehouse code is required.',
        'email.email' => 'Please enter a valid email address.',
    ];    
    
    public function mount($id)
    {
        $this->warehouse = ProductWarehouse::findOrFail($id);
        $this->name = $this->warehouse->name;
        $this->code = $this->warehouse->code;
        $this->email = $this->warehouse->email;
        $this->phone = $this->warehouse->phone;
        $this->address = $this->warehouse->address;
        $this->is_active = $this->warehouse->is_active;
    }

    public function update()
    {
        // Update validation rules to exclude current record
        $this->rules['name'] = 'required|string|max:255|unique:product_warehouses,name,' . $this->warehouse->id;
        $this->rules['email'] = 'nullable|email|unique:product_warehouses,email,' . $this->warehouse->id;
        $this->rules['phone'] = 'nullable|string|max:255|unique:product_warehouses,phone,' . $this->warehouse->id;
        
        $this->validate();

        try {            
            $this->warehouse->update([
                'name' => $this->name,
                'code' => $this->code,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'is_active' => $this->is_active,
                'updated_by' => Auth::user()->name,
            ]);

            $this->dispatch('success', message: 'Warehouse updated successfully.');
            $this->redirect('/warehouses', navigate: true);
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error updating warehouse: ' . $e->getMessage());
        }
    }    
    public function render()
    {
        return view('livewire.inventory.warehouse.edit');
    }
}
