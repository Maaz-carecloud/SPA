<?php

namespace App\Livewire\Library\Member;

use Livewire\Component;
use App\Models\User;
use App\Models\Student;
use App\Models\LibraryMember;
use App\Models\ClassModel;
use Illuminate\Support\Facades\Auth;

class View extends Component
{
    public $student;
    public $libraryMember;
    public $classId;
    
    // Add query string properties
    public $studentId;
    
    protected $queryString = [
        'studentId' => ['except' => '', 'as' => 'student'],
        'classId' => ['except' => '', 'as' => 'class']
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

        // Get the library member record
        $this->libraryMember = LibraryMember::where('user_id', $this->student->id)->first();

        if (!$this->libraryMember) {
            $this->redirect(route('library.members'), navigate: true);
            return;
        }
    }

    public function editMember()
    {
        $this->redirect(route('library.member.edit', ['student' => $this->studentId, 'class' => $this->classId]), navigate: true);
    }

    public function deleteMember()
    {
        try {
            $this->libraryMember->delete();
            
            // Update user's library status
            $this->student->update(['library' => 0]);
            
            session()->flash('success', 'Library member removed successfully.');
            $this->redirect(route('library.members', ['selectedClassId' => $this->classId]), navigate: true);
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to remove library member: ' . $e->getMessage());
        }
    }

    public function goBack()
    {
        $this->redirect(route('library.members', ['selectedClassId' => $this->classId]), navigate: true);
    }

    public function render()
    {
        return view('livewire.library.member.view')->layout('components.layouts.app');
    }
}
