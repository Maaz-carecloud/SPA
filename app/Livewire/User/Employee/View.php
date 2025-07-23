<?php

namespace App\Livewire\User\Employee;

use Livewire\Component;
use App\Models\Employee;

class View extends Component
{
    public $employee;

    public function mount($id)
    {
        $this->employee = \App\Models\Employee::with(['user', 'designation'])->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.user.employee.view', [
            'employee' => $this->employee,
        ]);
    }
}
