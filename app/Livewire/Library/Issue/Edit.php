<?php

namespace App\Livewire\Library\Issue;

use App\Models\Issue;
use App\Models\Book;
use App\Models\LibraryMember;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Edit extends Component
{
    public Issue $issue;
    
    public $library_id = '';
    public $book_id = '';
    public $serial_no = '';
    public $issue_date = '';
    public $due_date = '';
    public $return_date = '';
    public $note = '';
    public $author = '';
    public $subject_code = '';
    public $library_member_id = '';
    
    public $books = [];
    public $libraryMembers = [];
    public $selectedBook = null;
    public $selectedMember = null;

    protected function rules()
    {
        return [
            'library_id' => 'required|string|max:128|unique:issues,library_id,' . $this->issue->issue_id . ',issue_id',
            'book_id' => 'required|exists:books,id',
            'library_member_id' => 'required|exists:library_members,id',
            'serial_no' => 'required|string|max:40',
            'issue_date' => 'required|date',
            'due_date' => 'required|date',
            'return_date' => 'nullable|date|after_or_equal:issue_date',
            'note' => 'nullable|string',
        ];
    }

    protected $messages = [
        'library_id.required' => 'Library ID is required.',
        'library_id.unique' => 'This Library ID already exists.',
        'book_id.required' => 'Please select a book.',
        'book_id.exists' => 'Selected book does not exist.',
        'library_member_id.required' => 'Please select a library member.',
        'library_member_id.exists' => 'Selected library member does not exist.',
        'serial_no.required' => 'Serial number is required.',
        'issue_date.required' => 'Issue date is required.',
        'due_date.required' => 'Due date is required.',
        'due_date.after' => 'Due date must be after issue date.',
        'return_date.after_or_equal' => 'Return date must be after or equal to issue date.',
    ];

    public function mount(Issue $issue)
    {
        // Check if user can edit issues
        if (!$this->canEditIssue($issue)) {
            abort(403, 'Unauthorized to edit this issue.');
        }

        $this->issue = $issue;
        $this->loadBooks();
        $this->loadLibraryMembers();
        $this->populateFields();
    }

    public function canEditIssue($issue)
    {
        $user = Auth::user();
        
        // Admin (user_type 1 or 'admin') has full access
        if (in_array($user->user_type, [1, 'admin'])) {
            return is_null($issue->return_date);
        }
        
        // Admin and staff can edit if issue is not returned
        if (in_array($user->user_type, [1, 2, 'admin', 'staff']) && $user->can('edit issues')) {
            return is_null($issue->return_date);
        }
        
        return false;
    }

    public function loadBooks()
    {
        $this->books = Book::orderBy('name')->get();
    }

    public function loadLibraryMembers()
    {
        $this->libraryMembers = LibraryMember::with(['student.user'])
                                            ->orderBy('name')
                                            ->get();
    }

    public function populateFields()
    {
        $this->library_id = $this->issue->library_id;
        $this->book_id = $this->issue->book_id;
        $this->library_member_id = $this->issue->library_member_id;
        $this->serial_no = $this->issue->serial_no;
        $this->issue_date = $this->issue->issue_date ? $this->issue->issue_date->format('Y-m-d') : '';
        $this->due_date = $this->issue->due_date ? $this->issue->due_date->format('Y-m-d') : '';
        $this->return_date = $this->issue->return_date ? $this->issue->return_date->format('Y-m-d') : '';
        $this->note = $this->issue->note;
        
        if ($this->book_id) {
            $this->selectedBook = Book::find($this->book_id);
            if ($this->selectedBook) {
                $this->author = $this->selectedBook->author ?? '';
                $this->subject_code = $this->selectedBook->subject_code ?? '';
            }
        }
        
        if ($this->library_member_id) {
            $this->selectedMember = LibraryMember::with(['student.user'])
                                                 ->where('id', $this->library_member_id)
                                                 ->first();
        }
    }

    public function updatedBookId()
    {
        if ($this->book_id) {
            $this->selectedBook = Book::find($this->book_id);
            if ($this->selectedBook) {
                $this->author = $this->selectedBook->author ?? '';
                $this->subject_code = $this->selectedBook->subject_code ?? '';
            }
        } else {
            $this->selectedBook = null;
            $this->author = '';
            $this->subject_code = '';
        }
    }

    public function updatedLibraryMemberId()
    {
        if ($this->library_member_id) {
            $this->selectedMember = LibraryMember::with(['student.user'])
                                                 ->where('id', $this->library_member_id)
                                                 ->first();
        } else {
            $this->selectedMember = null;
        }
    }

    public function updatedIssueDate()
    {
        if ($this->issue_date && !$this->return_date) {
            // Automatically update due date to 14 days after issue date if not returned
            $issueDate = Carbon::parse($this->issue_date);
            $this->due_date = $issueDate->addDays(14)->toDateString();
        }
    }

    public function returnBook()
    {
        if (is_null($this->return_date)) {
            $this->return_date = now()->toDateString();
            $this->note = ($this->note ?: '') . ' - Returned on ' . now()->format('Y-m-d');
        }
    }

    public function update()
    {
        $this->validate();

        try {
            $oldBookId = $this->issue->book_id;
            $wasReturned = !is_null($this->issue->return_date);
            $isNowReturned = !is_null($this->return_date) && $this->return_date !== '';

            $this->issue->update([
                'library_id' => $this->library_id,
                'book_id' => $this->book_id,
                'library_member_id' => $this->library_member_id,
                'serial_no' => $this->serial_no,
                'issue_date' => $this->issue_date,
                'due_date' => $this->due_date,
                'return_date' => $this->return_date ?: null, // Convert empty string to null
                'note' => $this->note,
            ]);

            // Update book due quantities if needed
            if ($oldBookId != $this->book_id) {
                // If book changed, update both books
                if (!$wasReturned) {
                    Book::find($oldBookId)?->decrement('due_quantity');
                    Book::find($this->book_id)?->increment('due_quantity');
                }
            }

            // If book was returned now
            if (!$wasReturned && $isNowReturned) {
                Book::find($this->book_id)?->decrement('due_quantity');
            }

            // If book return was undone
            if ($wasReturned && !$isNowReturned) {
                Book::find($this->book_id)?->increment('due_quantity');
            }

            $this->dispatch('success', message: 'Issue updated successfully!');
            
            return redirect()->route('library.issues.index');
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error updating issue: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('library.issues.index');
    }

    public function validate($rules = null, $messages = [], $attributes = [])
    {
        // Convert empty return_date to null before validation
        if ($this->return_date === '') {
            $this->return_date = null;
        }
        
        return parent::validate($rules, $messages, $attributes);
    }

    public function render()
    {
        return view('livewire.library.issue.edit', [
            'books' => $this->books,
            'libraryMembers' => $this->libraryMembers
        ]);
    }
}
