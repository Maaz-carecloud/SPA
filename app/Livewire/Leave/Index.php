<?php

namespace App\Livewire\Leave;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LeaveRecord;
use App\Models\LeaveType;
use App\Models\User;
use App\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    public function render()
    {
        return view('livewire.leave.index');
    }
}
