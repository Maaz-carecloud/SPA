<?php

namespace App\Livewire\Library\Member;

use Livewire\Component;
use App\Models\User;
use App\Models\Student;
use App\Models\LibraryMember;
use App\Models\ClassModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class Create extends Component
{
    public $student;
    public $classId;
    public $libraryId;
    public $lBalance = '0.00';
    
    // Add query string properties
    public $studentId;
    
    protected $queryString = [
        'studentId' => ['except' => '', 'as' => 'student'],
        'classId' => ['except' => '', 'as' => 'class']
    ];
    
    protected $rules = [
        'libraryId' => 'required|string|max:40|unique:library_members,library_id',
        'lBalance' => 'required|numeric|min:0',
    ];

    protected $messages = [
        'libraryId.required' => 'Library ID is required.',
        'libraryId.unique' => 'This Library ID already exists.',
        'lBalance.required' => 'Library fee is required.',
        'lBalance.numeric' => 'Library fee must be a valid number.',
        'lBalance.min' => 'Library fee cannot be negative.',
    ];

    public function mount()
    {

        
        if (!$this->studentId) {
            $this->redirect(route('library.members'), navigate: true);
            return;
        }

        $this->student = User::with(['student.class', 'student.section'])
            ->where('id', $this->studentId)
            ->where('user_type', 'student')
            ->where('is_active', true)
            ->first();


        if (!$this->student || !$this->student->student) {
            $this->redirect(route('library.members'), navigate: true);
            return;
        }

        // Check if student is already a library member
        if ($this->student->library == 1) {
            $this->redirect(route('library.members'), navigate: true);
            return;
        }

        $this->libraryId = LibraryMember::generateLibraryId();
    }

    public function save()
    {
        $this->validate();

        try {
            // Create library member record
            LibraryMember::create([
                'library_id' => $this->libraryId,
                'user_id' => $this->student->id, // Use the user ID directly
                'name' => $this->student->name,
                'email' => $this->student->email,
                'phone' => $this->student->phone,
                'fee' => $this->lBalance,
                'library_join_date' => now()->toDateString(),
            ]);

            // Update user's library status
            $this->student->update(['library' => 1]);

            $this->dispatch('success', message: 'Library member added successfully.');
            
            $this->redirect(route('library.members', ['selectedClassId' => $this->classId]), navigate: true);
            
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Failed to add library member: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        $this->redirect(route('library.members', ['selectedClassId' => $this->classId]), navigate: true);
    }

    public function render()
    {
        return view('livewire.library.member.create');
    }
}
