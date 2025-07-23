<?php

namespace App\Livewire\User\Teacher;

use Livewire\Component;

class View extends Component
{
    public $teacher;
    public $user;

    public function mount($id)
    {
        $this->teacher = \App\Models\Teacher::with(['user', 'designation'])->findOrFail($id);
        $this->user = $this->teacher->user;
    }

    public function render()
    {
        return view('livewire.user.teacher.view', [
            'teacher' => $this->teacher,
            'user' => $this->user,
        ]);
    }
}
