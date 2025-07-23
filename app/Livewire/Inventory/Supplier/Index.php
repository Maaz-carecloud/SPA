<?php

namespace App\Livewire\Inventory\Supplier;

use App\Models\ProductSupplier;
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
        $suppliers = $this->getTableData();
        return view('livewire.inventory.supplier.index');
    }
}
