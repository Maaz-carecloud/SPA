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

class Create extends Component
{
    public $library_id = '';
    public $book_id = '';
    public $serial_no = '';
    public $issue_date = '';
    public $due_date = '';
    public $note = '';
    public $library_member_id = '';
    public $author = '';
    public $subject_code = '';
    
    public $books = [];
    public $libraryMembers = [];
    public $selectedBook = null;
    public $selectedMember = null;

    protected $rules = [
        'library_id' => 'required|string|max:128|unique:issues,library_id',
        'book_id' => 'required|exists:books,id',
        'library_member_id' => 'required|exists:library_members,id',
        'serial_no' => 'required|string|max:40',
        'issue_date' => 'required|date',
        'due_date' => 'required|date|after:issue_date',
        'note' => 'nullable|string',
    ];

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
    ];

    public function mount()
    {
        // Check if user can create issues
        if (!$this->canCreateIssue()) {
            abort(403, 'Unauthorized to create issues.');
        }

        $this->loadBooks();
        $this->loadLibraryMembers();
        $this->setDefaultValues();
    }

    public function canCreateIssue()
    {
        $user = Auth::user();
        // Admin (user_type 1 or 'admin') has full access, others need permission
        return in_array($user->user_type, [1, 'admin']) || (in_array($user->user_type, [1, 2, 'admin', 'staff']) && $user->can('create issues'));
    }

    public function loadBooks()
    {
        $this->books = Book::where('quantity', '>', 0)
                          ->whereColumn('due_quantity', '<', 'quantity')
                          ->orderBy('name')
                          ->get();
    }

    public function loadLibraryMembers()
    {
        $this->libraryMembers = LibraryMember::with(['student.user'])
                                            ->orderBy('name')
                                            ->get();
    }

    public function setDefaultValues()
    {
        $this->issue_date = now()->toDateString(); // Always set to today
        $this->due_date = now()->addDays(14)->toDateString(); // Default 2 weeks loan
        $this->generateIssueId();
    }

    public function generateIssueId()
    {
        $lastIssue = Issue::orderBy('issue_id', 'desc')->first();
        $lastNumber = $lastIssue ? (int)substr($lastIssue->library_id, 3) : 0;
        $this->library_id = 'LIB' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    }

    public function updatedBookId()
    {
        if ($this->book_id) {
            $this->selectedBook = Book::find($this->book_id);
            if ($this->selectedBook) {
                // Auto-fill author and subject code
                $this->author = $this->selectedBook->author ?? '';
                $this->subject_code = $this->selectedBook->subject_code ?? '';
                
                // Auto-generate serial number based on book
                $this->serial_no = 'SN' . str_pad($this->selectedBook->id, 3, '0', STR_PAD_LEFT) . 
                                  str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            }
        } else {
            $this->author = '';
            $this->subject_code = '';
            $this->serial_no = '';
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
        // Always keep issue date as today and update due date accordingly
        $this->issue_date = now()->toDateString();
        $issueDate = Carbon::parse($this->issue_date);
        $this->due_date = $issueDate->addDays(14)->toDateString();
    }

    public function save()
    {
        $this->validate();

        try {
            Issue::create([
                'library_id' => $this->library_id,
                'book_id' => $this->book_id,
                'library_member_id' => $this->library_member_id,
                'serial_no' => $this->serial_no,
                'issue_date' => $this->issue_date,
                'due_date' => $this->due_date,
                'note' => $this->note ?: 'Book issued',
            ]);

            // Update book due quantity
            $book = Book::find($this->book_id);
            if ($book) {
                $book->increment('due_quantity');
            }

            $this->dispatch('success', message: 'Issue created successfully!');
            
            return redirect()->route('library.issues.index');
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error creating issue: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('library.issues.index');
    }

    public function render()
    {
        return view('livewire.library.issue.create', [
            'books' => $this->books,
            'libraryMembers' => $this->libraryMembers
        ]);
    }
}
