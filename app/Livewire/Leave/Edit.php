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
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Edit extends Component
{
    use WithFileUploads;

    public LeaveRecord $leave;
    public $user_type;
    public $user_id;
    public $leave_type_id;
    public $leave_reason;
    public $date_from;
    public $date_to;
    public $attachment;
    public $class_id;
    public $section_id;
    public $status;
    public $total_days = 0;
    public $currentAttachment;

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
        'date_from' => 'required|date',
        'date_to' => 'required|date|after_or_equal:date_from',
        'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'class_id' => 'nullable|exists:classes,id',
        'section_id' => 'nullable|exists:sections,id',
        'status' => 'boolean',
    ];

    protected $messages = [
        'user_type.required' => 'Please select a user type.',
        'user_type.in' => 'Invalid user type selected.',
        'user_id.required' => 'Please select a user.',
        'leave_type_id.required' => 'Please select a leave type.',
        'leave_reason.required' => 'Please provide a reason for leave.',
        'date_from.required' => 'Please select start date.',
        'date_to.required' => 'Please select end date.',
        'date_to.after_or_equal' => 'End date must be same or after start date.',
        'attachment.mimes' => 'Attachment must be a PDF or image file.',
        'attachment.max' => 'Attachment size must not exceed 2MB.',
    ];

    public function mount($id)
    {
        $this->leave = LeaveRecord::findOrFail($id);
        
        // Only allow editing by the creator or admin
        $user = Auth::user();
        $isAdmin = false;
        
        if ($user) {
            $isAdmin = $user->hasRole('admin') || $user->user_type === 'admin';
        }
        
        if (!$isAdmin && $this->leave->created_by !== Auth::user()->name) {
            abort(403, 'Unauthorized action.');
        }

        $this->loadData();
        $this->populateForm();
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

    public function populateForm()
    {
        // Set user type based on the leave record's user
        $this->user_type = $this->leave->user->user_type;
        $this->user_id = $this->leave->user_id;
        $this->leave_type_id = $this->leave->leave_type_id;
        $this->leave_reason = $this->leave->leave_reason;
        $this->date_from = $this->leave->date_from ? $this->leave->date_from->format('Y-m-d') : '';
        $this->date_to = $this->leave->date_to ? $this->leave->date_to->format('Y-m-d') : '';
        $this->class_id = $this->leave->class_id;
        $this->section_id = $this->leave->section_id;
        $this->status = $this->leave->status;
        $this->total_days = $this->leave->total_days;
        $this->currentAttachment = $this->leave->attachment;

        // Filter users by type after setting user_type
        $this->filterUsersByType();
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
        if ($this->user_type) {
            switch ($this->user_type) {
                case 'student':
                    $this->users = $this->students;
                    break;
                case 'teacher':
                    $this->users = $this->teachers;
                    break;
                case 'employee':
                    $this->users = $this->employees;
                    break;
                default:
                    $this->users = collect();
            }
        } else {
            $this->users = collect();
        }
    }

    public function updatedUserId()
    {
        // Reset class and section when user changes
        $this->class_id = '';
        $this->section_id = '';
    }

    protected function rules()
    {
        $rules = [
            'user_type' => 'required|in:student,teacher,employee',
            'user_id' => 'required|exists:users,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'leave_reason' => 'required|string|max:1000',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'status' => 'boolean',
        ];

        // Add class and section validation for students
        if ($this->user_type === 'student') {
            $rules['class_id'] = 'required|exists:classes,id';
            $rules['section_id'] = 'required|exists:sections,id';
        }

        return $rules;
    }

    public function updatedDateFrom()
    {
        $this->calculateTotalDays();
    }

    public function updatedDateTo()
    {
        $this->calculateTotalDays();
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

    public function removeAttachment()
    {
        if ($this->leave->attachment) {
            Storage::disk('public')->delete($this->leave->attachment);
            $this->leave->update(['attachment' => null]);
            $this->currentAttachment = null;
            $this->dispatch('success', message: 'Attachment removed successfully.');
        }
    }

    public function downloadAttachment()
    {
        if (!$this->currentAttachment) {
            $this->dispatch('error', message: 'No attachment found.');
            return;
        }

        $filePath = storage_path('app/public/' . $this->currentAttachment);
        
        if (!file_exists($filePath)) {
            $this->dispatch('error', message: 'File not found.');
            return;
        }

        return response()->download($filePath);
    }

    public function update()
    {
        $this->validate();

        $data = [
            'user_id' => $this->user_id,
            'leave_type_id' => $this->leave_type_id,
            'leave_reason' => $this->leave_reason,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'total_days' => $this->total_days,
            'status' => $this->status,
            'updated_by' => Auth::user()->name,
        ];
        // Add class and section for students
        if ($this->user_type === 'student') {
            $data['class_id'] = $this->class_id;
            $data['section_id'] = $this->section_id;
        } else {
            // Clear class and section for non-students
            $data['class_id'] = null;
            $data['section_id'] = null;
        }

        // Handle file upload
        if ($this->attachment) {
            // Delete old attachment if exists
            if ($this->leave->attachment) {
                Storage::disk('public')->delete($this->leave->attachment);
            }
            $data['attachment'] = $this->attachment->store('leave_attachments', 'public');
        }

        $this->leave->update($data);

        $this->dispatch('success', message: 'Leave record updated successfully.');
        return redirect()->route('leave.index');
    }

    public function render()
    {
        return view('livewire.leave.edit');
    }
}
