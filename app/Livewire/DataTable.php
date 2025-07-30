<?php

namespace App\Livewire;

use Livewire\Component;

class DataTable extends Component
{
    public $columns = [];
    public $rows = [];
    public $tableId = 'datatable';
    public $ajaxUrl = '/datatable/posts'; // Default value, can be overridden

    public function mount($columns = [], $rows = [], $tableId = 'datatable', $ajaxUrl = '/datatable/posts')
    {
        $this->columns = $columns;
        $this->rows = $rows;
        $this->tableId = $tableId;
        $this->ajaxUrl = $ajaxUrl;
    }

    public function render()
    {
        return view('livewire.data-table');
    }
} 