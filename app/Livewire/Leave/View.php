<?php

namespace App\Livewire\Leave;

use Livewire\Component;
use App\Models\LeaveRecord;
use Illuminate\Support\Facades\Auth;

class View extends Component
{
    public LeaveRecord $leave;
    public $showModal = false;

    public function mount($id)
    {
        $this->leave = LeaveRecord::with([
            'user', 
            'leaveType', 
            'class', 
            'section', 
            'addedBy'
        ])->findOrFail($id);
        
        // Check if user can view this leave record
        $user = Auth::user();
        $isAdmin = false;
        
        if ($user) {
            $isAdmin = $user->hasRole('admin') || $user->user_type === 'admin';
        }
          if (!$isAdmin && $this->leave->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this leave record.');        }
    }

    public function downloadAttachment()
    {
        if (!$this->leave->attachment) {
            session()->flash('error', 'No attachment found.');
            return;
        }

        $filePath = storage_path('app/public/' . $this->leave->attachment);
        
        if (!file_exists($filePath)) {
            session()->flash('error', 'File not found.');
            return;
        }

        return response()->download($filePath);
    }

    public function render()
    {
        return view('livewire.leave.view')
            ->layout('components.layouts.app');
    }
}
