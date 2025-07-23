<?php

namespace App\Livewire\Activity;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ActivityLog;

class Index extends Component
{
    public function render()
    {
        return view('livewire.activity.index', [
            'activities' => ActivityLog::orderByDesc('created_at')->get(),
        ]);
    }
}
