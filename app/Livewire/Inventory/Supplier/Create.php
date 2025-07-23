<?php

namespace App\Livewire\Inventory\Supplier;

use Livewire\Component;
use App\Models\ProductSupplier;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{    
    public $company_name = '';
    public $name = '';
    public $email = '';
    public $phone = '';
    public $address = '';
    public $is_active = 1;    
    
    protected $rules = [
        'company_name' => 'required|string|max:255|unique:product_suppliers,company_name',
        'name' => 'nullable|string|max:255',
        'email' => 'nullable|email|unique:product_suppliers,email',
        'phone' => 'nullable|string|max:255|unique:product_suppliers,phone',
        'address' => 'nullable|string|max:1000',
        'is_active' => 'required|boolean',
    ];

    protected $messages = [
        'company_name.required' => 'Company name is required.',
        'company_name.unique' => 'Company name already exists.',
        'name.required' => 'Contact person name is required.',
        'name.unique' => 'Contact person name already exists.',
        'email.email' => 'Please enter a valid email address.',
        'email.unique' => 'Email already exists.',
        'phone.unique' => 'Phone number already exists.',
    ];      
    
    public function createSupplier()
    {
        $this->validate();

        try {            
            ProductSupplier::create([
                'company_name' => $this->company_name,
                'name' => $this->name,
                'email' => !empty($this->email) ? $this->email : null,
                'phone' => !empty($this->phone) ? $this->phone : null,
                'address' => !empty($this->address) ? $this->address : null,
                'is_active' => $this->is_active,
                'created_by' => Auth::user()->name,
                'updated_by' => Auth::user()->name,
            ]);

            $this->dispatch('success', message: 'Supplier created successfully.');
            $this->redirect('/suppliers', navigate: true);
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error creating supplier: ' . $e->getMessage());
        }
    }
    
    public function render()
    {
        return view('livewire.inventory.supplier.create');
    }
}
