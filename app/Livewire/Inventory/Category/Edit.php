<?php

namespace App\Livewire\Inventory\Category;

use App\Models\ProductCategory;
use Livewire\Component;

class Edit extends Component
{
    public $categoryId;
    public $name;
    public $description;

    protected $rules = [
        'name' => 'required|string',
        'description' => 'nullable|string|max:255',
    ];

    public function mount($id)
    {
        $category = ProductCategory::findOrFail($id);
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;
    }

    public function updateCategory()
    {
        $this->validate([
            'name' => 'required|string|unique:product_categories,name,' . $this->categoryId,
            'description' => 'nullable|string|max:255',
        ]);
        $category = ProductCategory::findOrFail($this->categoryId);
        $category->name = $this->name;
        $category->description = $this->description;
        $category->save();
        $this->dispatch('success', message: 'Category updated successfully.');
        $this->redirect('/categories', navigate: true);
    }

    public function render()
    {
        return view('livewire.inventory.category.edit');
    }
}
