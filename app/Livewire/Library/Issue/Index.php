<?php

namespace App\Livewire\Library\Issue;

use App\Models\Issue;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

class Index extends Component
{
    public $issues;

    public function mount(){
        $this->loadOptions();
        // Initialize modal state
        $this->modalTitle = 'Create Issue';
        $this->modalAction = 'create-issue';
        $this->is_edit = false;
        // Set default issue date
        $this->issue_date = now()->format('Y-m-d');
        $this->updatedIssueDate();
        // Initialize refresh key
        $this->refreshKey = microtime(true);
    }

    public function loadOptions(){
        $this->books = Book::orderBy('name')
                          ->get()
                          ->mapWithKeys(function ($book) {
                              $availability = $book->available_quantity . '/' . $book->quantity . ' available';
                              $status = $book->isAvailable() ? '' : ' (Out of Stock)';
                              return [$book->id => $book->name . ' [' . $availability . ']' . $status];
                          })->toArray();
                          
        $this->users = User::whereNotIn('user_type', ['parent', 'admin'])
                          ->where('is_active', true)
                          ->orderBy('name')
                          ->get()
                          ->mapWithKeys(function ($user) {
                              return [$user->id => $user->name . ' [' . ($user->user_type ?? 'N/A') . ']'];
                          })->toArray();
    }

    // Modal related methods
    public $modalTitle = 'Create Issue';
    public $modalAction = 'create-issue';
    public $is_edit = false;
    public $deleteId;
    public $is_delete = false;

    // Form related methods
    #[Rule('required|exists:users,id')]
    public $user_id;
    #[Rule('required|exists:books,id')]
    public $book_id;
    #[Rule('required|date')]
    public $issue_date;
    #[Rule('nullable|date')]
    public $return_date;
    #[Rule('nullable|string')]
    public $notes;
    #[Rule('nullable|in:issued,returned,overdue')]
    public $status;

    public $due_date; // Auto-calculated, no validation needed

    public $getIssue;
    public $books = [];
    public $users = [];
    public $refreshKey = 0; // For forcing Select2 refresh

    // Return book related fields
    #[Rule('required|date')]
    public $actual_return_date;
    #[Rule('nullable|string')]
    public $return_notes;

    public function updatedIssueDate()
    {
        if ($this->issue_date) {
            $this->due_date = Carbon::parse($this->issue_date)->addDays(14)->format('Y-m-d');
        }
    }

    #[On('create-issue')]
    public function save(){
        // Validate the form
        $this->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id', 
            'issue_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);
        
        try {
            // Check book availability
            $book = Book::findOrFail($this->book_id);
            if (!$book->isAvailable()) {
                $this->dispatch('error', message: 'This book is not available for issue. Available: ' . $book->available_quantity . '/' . $book->quantity);
                return;
            }

            // Check if user already has this book issued
            $existingIssue = Issue::where('user_id', $this->user_id)
                                  ->where('book_id', $this->book_id)
                                  ->where('status', 'issued')
                                  ->exists();
            
            if ($existingIssue) {
                $this->dispatch('error', message: 'This user already has this book issued.');
                return;
            }
            
            // Calculate due date
            $dueDate = Carbon::parse($this->issue_date)->addDays(14)->format('Y-m-d');
            
            $issue = Issue::create([
                'user_id' => $this->user_id,
                'book_id' => $this->book_id,
                'issue_date' => $this->issue_date,
                'due_date' => $dueDate,
                'notes' => $this->notes,
                'status' => 'issued'
            ]);
            
            if ($issue) {
                // Refresh book data to get updated stock
                $book->refresh();
                
                $this->dispatch('success', message: 'Issue created successfully. Book stock updated. Available: ' . $book->available_quantity . '/' . $book->quantity);
                $this->dispatch('hide-modal');
                $this->resetFields();
                $this->loadOptions(); // Reload options to show updated stock
                
                // Force refresh of Select2 dropdowns by updating the key
                $this->refreshKey = microtime(true);
                
                $this->dispatch('datatable-reinit');
            } else {
                $this->dispatch('error', message: 'Failed to create issue');
            }
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error: ' . $e->getMessage());
        }
    }

    #[On('edit-mode')]
    public function loadEditModal($id){
        $this->modalTitle = 'Edit Issue';
        $this->modalAction = 'edit-issue';
        $this->is_edit = true;

        $this->getIssue = Issue::findOrfail($id);
        $this->user_id = $this->getIssue->user_id;
        $this->book_id = $this->getIssue->book_id;
        $this->issue_date = optional($this->getIssue->issue_date)->format('Y-m-d');
        $this->due_date = optional($this->getIssue->due_date)->format('Y-m-d');
        $this->return_date = optional($this->getIssue->return_date)->format('Y-m-d');
        $this->notes = $this->getIssue->notes;
        $this->status = $this->getIssue->status;
    }

    #[On('edit-issue')]
    public function update(){
        $this->validate();

        // Always recalculate due_date to 14 days from issue_date
        $this->due_date = Carbon::parse($this->issue_date)->addDays(14)->format('Y-m-d');

        $i = Issue::findOrFail($this->getIssue->id);
        $i->user_id = $this->user_id;
        $i->book_id = $this->book_id;
        $i->issue_date = $this->issue_date;
        $i->due_date = $this->due_date;
        $i->return_date = $this->return_date;
        $i->notes = $this->notes;
        $i->status = $this->status ?? 'issued';
        $i->save();
        $this->dispatch('success', message: 'Issue updated successfully');

        $this->dispatch('hide-modal');
        $this->resetFields();
        
        // Reload options to ensure fresh stock data after any book changes
        $this->loadOptions();
        $this->refreshKey = microtime(true);
        
        $this->dispatch('datatable-reinit');
    }

    #[On('delete-record')]
    public function delete($id) {
        Issue::findOrFail($id)->delete();
        $this->dispatch('success', message: 'Issue deleted successfully');
        $this->dispatch('datatable-reinit');
    }

    #[On('create-issue-close')]
    #[On('edit-issue-close')]
    public function resetFields(){
        $this->reset(['user_id', 'book_id', 'issue_date', 'due_date', 'return_date', 'notes', 'status', 'getIssue', 'actual_return_date', 'return_notes']);
        $this->modalTitle = 'Create Issue';
        $this->modalAction = 'create-issue';
        $this->is_edit = false;
        $this->loadOptions(); // Reload options
        $this->refreshKey = microtime(true); // Force refresh of Select2 dropdowns
        
        // Refresh the DataTable when modal is closed
        $this->dispatch('datatable-reinit');
    }

    #[On('return-mode')]
    public function loadReturnModal($id){
        $this->getIssue = Issue::findOrfail($id);
        $this->actual_return_date = now()->format('Y-m-d'); // Default to today
        $this->return_notes = '';
    }

    #[On('returnBook')]
    public function returnBook(){
        $this->validate([
            'actual_return_date' => 'required|date',
            'return_notes' => 'nullable|string'
        ]);

        $issue = Issue::findOrFail($this->getIssue->id);
        
        // Convert string date to Carbon instance
        $returnDate = Carbon::parse($this->actual_return_date);
        
        $result = $issue->returnBook($returnDate, $this->return_notes);
        
        if ($result['success']) {
            // Refresh the book to get updated stock
            $issue->book->refresh();
            
            $message = $result['message'] . ' Book stock updated. Available: ' . $issue->book->available_quantity . '/' . $issue->book->quantity;
            if ($result['fine_amount'] > 0) {
                $message .= ' Fine of Rs. ' . number_format($result['fine_amount'], 2) . ' has been recorded.';
            }
            $this->dispatch('success', message: $message);
            
            // Reset form fields
            $this->reset(['getIssue', 'actual_return_date', 'return_notes']);
            
            // Reload options to show updated stock
            $this->loadOptions();
            
            // Force refresh of Select2 dropdowns by updating the key
            $this->refreshKey = microtime(true);
            
            // Close the modal using JavaScript
            $this->dispatch('close-modal-js', modal: 'returnModal');
            $this->dispatch('datatable-reinit');
        } else {
            $this->dispatch('error', message: $result['message']);
        }
    }

    #[Title('Library Issues')]
    #[Layout('layouts.app')]
    public function render()
    {
        // Ensure options are always available
        if (empty($this->books) || empty($this->users)) {
            $this->loadOptions();
        }
        
        return view('livewire.library.issue.index');
    }

    // Server-side DataTables AJAX handler
    public function getDataTableRows(Request $request)
    {
        $length = $request->input('length');
        $start = $request->input('start');

        $query = Issue::with(['user', 'book']);

        // Search
        if (!empty($request['search']['value'])) {
            $search = $request['search']['value'];
            $query->where(function($q) use ($search) {
                $q->where('notes', 'like', '%' . $search . '%')
                  ->orWhere('status', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('book', function($bookQuery) use ($search) {
                      $bookQuery->where('name', 'like', '%' . $search . '%')
                               ->orWhere('author', 'like', '%' . $search . '%');
                  });
            });
        }

        $total = $query->count();

        if ($length == -1) {
            $length = $total;
        }

        $query->skip($start)->take($length);

        $issues = $query->orderBy('created_at', 'desc')->get();

        $rows = [];
        foreach ($issues as $index => $issue) {
            $statusBadge = '';
            if ($issue->status === 'returned') {
                $statusBadge = '<span class="badge bg-success">Returned</span>';
            } elseif ($issue->status === 'overdue' || $issue->is_overdue) {
                $statusBadge = '<span class="badge bg-danger">Overdue</span>';
            } else {
                $statusBadge = '<span class="badge bg-warning">Active</span>';
            }

            $rows[] = [
                $index + 1,
                e($issue->user->name ?? 'N/A'),
                e($issue->book->name ?? 'N/A'),
                e($issue->issue_date ? $issue->issue_date->format('d M Y') : 'N/A'),
                e($issue->due_date ? $issue->due_date->format('d M Y') : 'N/A'),
                e($issue->return_date ? $issue->return_date->format('d M Y') : 'Not Returned'),
                e($issue->notes ? Str::limit($issue->notes, 50) : 'No notes'),
                $statusBadge,
                '<div class="action-items">'
                . '<span><a href="#" onclick="Livewire.dispatch(\'edit-mode\', {id: ' . $issue->id . '})" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fa fa-edit"></i></a></span>'
                . ($issue->status !== 'returned' ? '<span><a href="#" onclick="Livewire.dispatch(\'return-mode\', {id: ' . $issue->id . '})" data-bs-toggle="modal" data-bs-target="#returnModal" title="Return Book"><i class="fa fa-undo text-primary"></i></a></span>' : '')
                . '<span><a href="javascript:void(0)" class="delete-swal" data-id="' . $issue->id . '"><i class="fa fa-trash"></i></a></span>'
                . '</div>',
            ];
        }

        return response()->json([
            'draw' => intval($request['draw']),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $rows,
        ]);
    }
}
