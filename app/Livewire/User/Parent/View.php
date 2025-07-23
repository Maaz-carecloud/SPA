<?php

namespace App\Livewire\User\Parent;

use Livewire\Component;
use App\Models\ParentModel;

class View extends Component
{
    public $parent;

    public function mount($id)
    {
        $this->parent = ParentModel::with('user')->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.user.parent.view', [
            'parent' => $this->parent,
        ]);
    }
}
