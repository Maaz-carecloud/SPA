<?php

namespace App\Livewire\User\Student;

use Livewire\Component;
use App\Models\Student;

class View extends Component
{
    public $student;

    public function mount($id)
    {
        $this->student = Student::with(['user', 'parent.user', 'class', 'section'])->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.user.student.view', [
            'student' => $this->student,
        ]);
    }
}
