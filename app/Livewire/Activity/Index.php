<?php

namespace App\Livewire\Activity;

use Livewire\Component;
use App\Models\ActivityLog;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

class Index extends Component
{
    public $activities;

    public function mount(){
        $this->loadActivities();
    }

    #[On('reset-table')]
    public function truncate_table(){
        ActivityLog::truncate();
        $this->dispatch('success', message: 'Table is cleared successfully!');
        $this->loadActivities();
        $this->dispatch('datatable-reinit');
    }

    #[Title('Activity Logs')]
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.activity.index');
    }

    public function loadActivities(){
        $this->activities = ActivityLog::with('user')->orderByDesc('created_at')->get();
    }
}
