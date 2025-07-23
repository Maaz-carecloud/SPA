<?php

namespace App\Livewire\Leave;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\LeaveRecord;
use App\Models\LeaveType;
use App\Models\User;
use App\Models\ClassModel;
use App\Models\Section;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Create extends Component
{
    use WithFileUploads;

    public $user_type;
    public $user_id;
    public $leave_type_id;
    public $leave_reason;
    public $date_from;
    public $date_to;
    public $attachment;
    public $class_id;
    public $section_id;
    public $total_days = 0;

    public $users = [];
    public $students = [];
    public $teachers = [];
    public $employees = [];
    public $leaveTypes = [];
    public $classes = [];
    public $sections = [];

    protected $rules = [
        'user_type' => 'required|in:student,teacher,employee',
        'user_id' => 'required|exists:users,id',
        'leave_type_id' => 'required|exists:leave_types,id',
        'leave_reason' => 'required|string|max:1000',
        'date_from' => 'required|date|after_or_equal:today',
        'date_to' => 'required|date|after_or_equal:date_from',
        'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'class_id' => 'nullable|exists:classes,id',
        'section_id' => 'nullable|exists:sections,id',
    ];

    protected $messages = [
        'user_type.required' => 'Please select a user type.',
        'user_type.in' => 'Invalid user type selected.',
        'user_id.required' => 'Please select a user.',
        'leave_type_id.required' => 'Please select a leave type.',
        'leave_reason.required' => 'Please provide a reason for leave.',
        'date_from.required' => 'Please select start date.',
        'date_from.after_or_equal' => 'Start date must be today or later.',
        'date_to.required' => 'Please select end date.',
        'date_to.after_or_equal' => 'End date must be same or after start date.',
        'attachment.mimes' => 'Attachment must be a PDF or image file.',
        'attachment.max' => 'Attachment size must not exceed 2MB.',
    ];

    public function mount()
    {
        $this->loadData();
        
        // Set current user as default if not admin
        $currentUser = Auth::user();
        $isAdmin = $currentUser->hasRole('admin') ?? $currentUser->user_type === 'admin';
        
        if (!$isAdmin) {
            $this->user_type = $currentUser->user_type;
            $this->user_id = Auth::id();
            $this->filterUsersByType();
        } else {
            // For admin users, initialize with empty collection
            $this->users = collect();
        }
    }

    public function loadData()
    {
        $this->students = User::where('user_type', 'student')->select('id', 'name')->get();
        $this->employees = User::where('user_type', 'employee')->select('id', 'name')->get();
        $this->teachers = User::where('user_type', 'teacher')->select('id', 'name')->get();
        $this->leaveTypes = LeaveType::active()->get();
        $this->classes = ClassModel::with('teacher')->get();
        $this->sections = Section::all();
    }

    public function updatedUserType()
    {
        // Reset user selection when user type changes
        $this->user_id = '';
        $this->class_id = '';
        $this->section_id = '';
        
        $this->filterUsersByType();
    }

    public function filterUsersByType()
    {
        switch ($this->user_type) {
            case 'student':
                $this->users = User::where('user_type', 'student')->select('id', 'name')->get();
                break;
            case 'teacher':
                $this->users = User::where('user_type', 'teacher')->select('id', 'name')->get();
                break;
            case 'employee':
                $this->users = User::where('user_type', 'employee')->select('id', 'name')->get();
                break;
            default:
                $this->users = collect();
                break;
        }
    }

    public function updatedDateFrom()
    {
        $this->calculateTotalDays();
    }

    public function updatedDateTo()
    {
        $this->calculateTotalDays();
    }

    public function updatedClassId()
    {
        // Reset section when class changes
        $this->section_id = '';
        
        // Filter sections based on selected class
        if ($this->class_id) {
            $this->sections = Section::where('class_id', $this->class_id)->get();
        } else {
            $this->sections = Section::all();
        }
    }

    public function updatedUserId()
    {
        // When user changes, reset class and section if it's not a student
        if ($this->user_type !== 'student') {
            $this->class_id = '';
            $this->section_id = '';
        }
    }

    public function calculateTotalDays()
    {
        if ($this->date_from && $this->date_to) {
            $from = Carbon::parse($this->date_from);
            $to = Carbon::parse($this->date_to);
            $this->total_days = $from->diffInDays($to) + 1;
        } else {
            $this->total_days = 0;
        }
    }

    public function save()
    {
        // Dynamic validation based on user type
        $rules = $this->rules;
        $messages = $this->messages;
        
        if ($this->user_type === 'student') {
            $rules['class_id'] = 'required|exists:classes,id';
            $rules['section_id'] = 'required|exists:sections,id';
            $messages['class_id.required'] = 'Please select a class for the student.';
            $messages['section_id.required'] = 'Please select a section for the student.';
        }
        
        $this->validate($rules, $messages);

        $data = [
            'user_id' => $this->user_id,
            'leave_type_id' => $this->leave_type_id,
            'leave_reason' => $this->leave_reason,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'total_days' => $this->total_days,
            'class_id' => $this->user_type === 'student' ? $this->class_id : null,
            'section_id' => $this->user_type === 'student' ? $this->section_id : null,
            'created_by' => Auth::user()->name,
            'status' => true, // Default to approved
        ];

        // Handle file upload
        if ($this->attachment) {
            $data['attachment'] = $this->attachment->store('leave_attachments', 'public');
        }

        LeaveRecord::create($data);

        $this->dispatch('success', message: 'Leave record created successfully.');
        return redirect()->route('leave.index');
    }

    public function render()
    {
        return view('livewire.leave.create');
    }
}
