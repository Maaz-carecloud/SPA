<?php

namespace App\Livewire;

use Livewire\Component;

class DataTable extends Component
{
    public $columns = [];
    public $rows = [];
    public $tableId = 'datatable';

    public function mount($columns = [], $rows = [], $tableId = 'datatable')
    {
        $this->columns = $columns;
        $this->rows = $rows;
        $this->tableId = $tableId;
    }

    public function render()
    {
        return view('livewire.data-table');
    }
} 