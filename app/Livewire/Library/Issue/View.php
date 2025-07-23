<?php

namespace App\Livewire\Library\Issue;

use App\Models\Issue;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class View extends Component
{
    public Issue $issue;
    public $showFineModal = false;
    public $fineAmount = '';

    public function mount(Issue $issue)
    {
        // Check if user can view issues
        if (!$this->canViewIssue()) {
            abort(403, 'Unauthorized to view issues.');
        }

        $this->issue = $issue->load(['book']);
    }

    public function canViewIssue()
    {
        $user = Auth::user();
        
        // Admin (user_type 1 or 'admin') has full access
        if (in_array($user->user_type, [1, 'admin'])) {
            return true;
        }
        
        // Admin and staff can view all issues
        if (in_array($user->user_type, [1, 2, 'admin', 'staff']) && $user->can('view issues')) {
            return true;
        }
        
        // Students can view their own issues
        if (in_array($user->user_type, [3, 'student']) && $user->can('view issues')) {
            // Additional logic needed to check if this issue belongs to the student
            return true;
        }
        
        // Parents can view their children's issues
        if (in_array($user->user_type, [4, 'parent']) && $user->can('view issues')) {
            // Additional logic needed to check if this issue belongs to their child
            return true;
        }
        
        return false;
    }

    public function canEditIssue()
    {
        $user = Auth::user();
        // Admin (user_type 1 or 'admin') has full access
        return (in_array($user->user_type, [1, 'admin']) || (in_array($user->user_type, [1, 2, 'admin', 'staff']) && $user->can('edit issues'))) && 
               is_null($this->issue->return_date);
    }

    public function canReturnBook()
    {
        $user = Auth::user();
        // Admin (user_type 1 or 'admin') has full access
        return (in_array($user->user_type, [1, 'admin']) || (in_array($user->user_type, [1, 2, 'admin', 'staff']) && $user->can('edit issues'))) && 
               is_null($this->issue->return_date);
    }

    public function canAddFine()
    {
        $user = Auth::user();
        // Admin (user_type 1 or 'admin') has full access
        return (in_array($user->user_type, [1, 'admin']) || (in_array($user->user_type, [1, 2, 'admin', 'staff']) && $user->can('create fines'))) && 
               $this->isOverdue();
    }

    public function isOverdue()
    {
        return is_null($this->issue->return_date) && 
               now()->toDateString() > $this->issue->due_date;
    }

    public function getStatusProperty()
    {
        if (!is_null($this->issue->return_date)) {
            return [
                'text' => 'Returned',
                'class' => 'success'
            ];
        }

        if ($this->isOverdue()) {
            return [
                'text' => 'Overdue',
                'class' => 'danger'
            ];
        }

        return [
            'text' => 'Active',
            'class' => 'primary'
        ];
    }

    public function getDaysOverdueProperty()
    {
        if (!$this->isOverdue()) {
            return 0;
        }

        return now()->diffInDays($this->issue->due_date);
    }

    public function returnBook()
    {
        if ($this->canReturnBook()) {
            $this->issue->update([
                'return_date' => now()->toDateString(),
                'note' => ($this->issue->note ?: '') . ' - Returned on ' . now()->format('Y-m-d')
            ]);

            // Update book due quantity
            $this->issue->book->decrement('due_quantity');

            $this->dispatch('success', message: 'Book returned successfully!');
            
            // Refresh the issue data
            $this->issue = $this->issue->fresh();
        }
    }

    public function showFineModal()
    {
        if ($this->canAddFine()) {
            $this->showFineModal = true;
            $this->fineAmount = '';
        }
    }

    public function addFine()
    {
        $this->validate([
            'fineAmount' => 'required|numeric|min:0|max:99999.99'
        ], [
            'fineAmount.required' => 'Fine amount is required.',
            'fineAmount.numeric' => 'Fine amount must be a number.',
            'fineAmount.min' => 'Fine amount cannot be negative.',
            'fineAmount.max' => 'Fine amount is too large.',
        ]);

        try {
            // Here you would typically create a fine record
            // For now, we'll just add it to the note
            $this->issue->update([
                'note' => ($this->issue->note ?: '') . ' - Fine added: $' . $this->fineAmount . ' on ' . now()->format('Y-m-d')
            ]);

            $this->dispatch('success', message: 'Fine of $' . $this->fineAmount . ' added successfully!');
            
            $this->showFineModal = false;
            $this->fineAmount = '';
            
            // Refresh the issue data
            $this->issue = $this->issue->fresh();
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error adding fine: ' . $e->getMessage());
        }
    }

    public function closeFineModal()
    {
        $this->showFineModal = false;
        $this->fineAmount = '';
    }

    public function render()
    {
        return view('livewire.library.issue.view');
    }
}
