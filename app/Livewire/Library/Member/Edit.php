<?php

namespace App\Livewire\Library\Member;

use Livewire\Component;
use App\Models\User;
use App\Models\Student;
use App\Models\LibraryMember;
use App\Models\ClassModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class Edit extends Component
{
    public $student;
    public $libraryMember;
    public $classId;
    public $libraryId;
    public $lBalance;
    
    // Add query string properties
    public $studentId;
    
    protected $queryString = [
        'studentId' => ['except' => '', 'as' => 'student'],
        'classId' => ['except' => '', 'as' => 'class']
    ];

    protected function rules()
    {
        return [
            'libraryId' => [
                'required',
                'string',
                'max:40',
                Rule::unique('library_members', 'library_id')->ignore($this->libraryMember->id, 'id')
            ],
            'lBalance' => 'required|numeric|min:0',
        ];
    }

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

        // Get the library member record
        $this->libraryMember = LibraryMember::where('user_id', $this->student->id)->first();

        if (!$this->libraryMember) {
            $this->redirect(route('library.members'), navigate: true);
            return;
        }

        // Populate form fields
        $this->libraryId = $this->libraryMember->library_id;
        $this->lBalance = $this->libraryMember->fee;
    }

    public function save()
    {
        $this->validate();

        try {
            // Update library member record
            $this->libraryMember->update([
                'library_id' => $this->libraryId,
                'name' => $this->student->name,
                'email' => $this->student->email,
                'phone' => $this->student->phone,
                'fee' => $this->lBalance,
            ]);

            $this->dispatch('success', message: 'Library member updated successfully.');
            
            $this->redirect(route('library.members', ['selectedClassId' => $this->classId]), navigate: true);
            
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Failed to update library member: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        $this->redirect(route('library.members', ['selectedClassId' => $this->classId]), navigate: true);
    }

    public function render()
    {
        return view('livewire.library.member.edit')->layout('components.layouts.app');
    }
}
