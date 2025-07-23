<?php

namespace App\Livewire\Inventory\Category;

use App\Models\ProductCategory;
use Livewire\Component;

class Create extends Component
{
    public $name , $description;

    protected $rules = [
        'name' => 'required|string|unique:product_categories,name',
        'description' => 'nullable|string|max:255',
    ];

    public function createCategory()
    {
        $this->validate();
        ProductCategory::create(['name' => $this->name , 'description' => $this->description]);
        $this->dispatch('success', message: 'Category created successfully.');
        $this->redirect('/categories', navigate: true);
    }

    public function render()
    {
        return view('livewire.inventory.category.create');
    }
}
