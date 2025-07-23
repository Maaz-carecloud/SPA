<?php

namespace App\Livewire\Inventory\Supplier;

use Livewire\Component;
use App\Models\ProductSupplier;
use Illuminate\Support\Facades\Auth;

class Edit extends Component
{    public $supplier;
    public $company_name = '';
    public $name = '';
    public $email = '';
    public $phone = '';
    public $address = '';
    public $is_active = 1;   
     
    protected $rules = [
        'company_name' => 'required|string|max:255',
        'name' => 'nullable|string|max:255',
        'email' => 'nullable|email',
        'phone' => 'nullable|string|max:255',
        'address' => 'nullable|string|max:1000',
        'is_active' => 'required|boolean',
    ];    
    
    protected $messages = [
        'company_name.required' => 'Company name is required.',
        'email.email' => 'Please enter a valid email address.',
    ];
    
    public function mount($id)
    {
        $this->supplier = ProductSupplier::findOrFail($id);
        $this->company_name = $this->supplier->company_name;
        $this->name = $this->supplier->name;
        $this->email = $this->supplier->email;
        $this->phone = $this->supplier->phone;
        $this->address = $this->supplier->address;
        $this->is_active = $this->supplier->is_active;
    }

    public function update()
    {       
         // Update validation rules to exclude current record
        $this->rules['company_name'] = 'required|string|max:255|unique:product_suppliers,company_name,' . $this->supplier->id;
        $this->rules['name'] = 'nullable|string|max:255';
        $this->rules['email'] = 'nullable|email|unique:product_suppliers,email,' . $this->supplier->id;
        $this->rules['phone'] = 'nullable|string|max:255|unique:product_suppliers,phone,' . $this->supplier->id;
        
        $this->validate();        
        
        try {            
            $this->supplier->update([
                'company_name' => $this->company_name,
                'name' => $this->name,
                'email' => !empty($this->email) ? $this->email : null,
                'phone' => !empty($this->phone) ? $this->phone : null,
                'address' => !empty($this->address) ? $this->address : null,
                'is_active' => $this->is_active,
                'updated_by' => Auth::user()->name,
            ]);

            $this->dispatch('success', message: 'Supplier updated successfully.');
            $this->redirect('/suppliers', navigate: true);
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error updating supplier: ' . $e->getMessage());
        }
    }    
    
    public function render()
    {
        return view('livewire.inventory.supplier.edit');
    }
}
