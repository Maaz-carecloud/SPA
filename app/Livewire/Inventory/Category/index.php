<?php

namespace App\Livewire\Inventory\Category;

use App\Models\ProductCategory;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use App\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Builder;

class Index extends Component
{
    

    public function render()
    {
        return view('livewire.inventory.category.index');
    }
}
