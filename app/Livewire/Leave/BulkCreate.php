<?php

namespace App\Livewire\Leave;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\LeaveRecord;
use App\Models\LeaveType;
use App\Models\User;
use App\Models\Student;
use App\Models\ClassModel;
use App\Models\Section;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BulkCreate extends Component
{
    use WithFileUploads;

    public $showModal = false;
    public $selectedUserType = 'all';
    public $selectedUsers = [];
    public $selectAll = false;
    public $leave_type_id;
    public $leave_reason;
    public $date_from;
    public $date_to;
    public $attachment;
    public $status = false;
    public $total_days = 0;
    public $emergency_note = '';

    public $users = [];
    public $students = [];
    public $teachers = [];
    public $employees = [];
    public $leaveTypes = [];
    public $classes = [];
    public $sections = [];

    protected $rules = [
        'selectedUsers' => 'required|array|min:1',
        'selectedUsers.*' => 'exists:users,id',
        'leave_type_id' => 'required|exists:leave_types,id',
        'leave_reason' => 'required|string|max:1000',
        'date_from' => 'required|date',
        'date_to' => 'required|date|after_or_equal:date_from',
        'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'status' => 'boolean',
        'emergency_note' => 'nullable|string|max:500',
    ];

    protected $messages = [
        'selectedUsers.required' => 'Please select at least one user.',
        'selectedUsers.min' => 'Please select at least one user.',
        'leave_type_id.required' => 'Please select a leave type.',
        'leave_reason.required' => 'Please provide a reason for leave.',
        'date_from.required' => 'Please select start date.',
        'date_to.required' => 'Please select end date.',
        'date_to.after_or_equal' => 'End date must be same or after start date.',
        'attachment.mimes' => 'Attachment must be a PDF or image file.',
        'attachment.max' => 'Attachment size must not exceed 2MB.',
        'emergency_note.max' => 'Emergency note must not exceed 500 characters.',
    ];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->students = User::where('user_type', 'student')
            ->where('is_active', true)
            ->select('id', 'name', 'email', 'registration_no')
            ->orderBy('name')
            ->get();
            
        $this->employees = User::where('user_type', 'employee')
            ->where('is_active', true)
            ->select('id', 'name', 'email', 'registration_no')
            ->orderBy('name')
            ->get();
            
        $this->teachers = User::where('user_type', 'teacher')
            ->where('is_active', true)
            ->select('id', 'name', 'email', 'registration_no')
            ->orderBy('name')
            ->get();
            
        $this->leaveTypes = LeaveType::active()->get();
        $this->classes = ClassModel::with('teacher')->get();
        $this->sections = Section::all();
        
        $this->filterUsersByType();
    }

    public function openModal()
    {
        $this->showModal = true;
        $this->resetForm();
        $this->loadData();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'selectedUserType', 'selectedUsers', 'selectAll', 'leave_type_id',
            'leave_reason', 'date_from', 'date_to', 'attachment', 'status',
            'total_days', 'emergency_note'
        ]);
        $this->selectedUserType = 'all';
        $this->filterUsersByType();
    }

    public function updatedSelectedUserType()
    {
        $this->selectedUsers = [];
        $this->selectAll = false;
        $this->filterUsersByType();
    }

    public function filterUsersByType()
    {
        switch ($this->selectedUserType) {
            case 'student':
                $this->users = $this->students;
                break;
            case 'teacher':
                $this->users = $this->teachers;
                break;
            case 'employee':
                $this->users = $this->employees;
                break;
            case 'all':
            default:
                $this->users = collect()
                    ->merge($this->students)
                    ->merge($this->teachers)
                    ->merge($this->employees)
                    ->sortBy('name');
                break;
        }
    }

    public function updatedSelectAll()
    {
        if ($this->selectAll) {
            $this->selectedUsers = $this->users->pluck('id')->toArray();
        } else {
            $this->selectedUsers = [];
        }
    }

    public function updatedSelectedUsers()
    {
        $this->selectAll = count($this->selectedUsers) === $this->users->count();
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

    public function create()
    {
        
        $this->validate();

        // Debug: Check if validation passed
        if (empty($this->selectedUsers)) {
            $this->dispatch('error', message: 'No users selected.');
            return;
        }


        try {
            DB::beginTransaction();

            $attachmentPath = null;
            if ($this->attachment) {
                $attachmentPath = $this->attachment->store('leave_attachments', 'public');
            }

            $createdCount = 0;
            $failedUsers = [];

            foreach ($this->selectedUsers as $userId) {
                $user = User::find($userId);
                if (!$user) {
                    $failedUsers[] = "User ID {$userId} not found";
                    continue;
                }

                try {
                    $leaveData = [
                        'user_id' => $userId,
                        'leave_type_id' => $this->leave_type_id,
                        'leave_reason' => $this->leave_reason,
                        'date_from' => $this->date_from,
                        'date_to' => $this->date_to,
                        'total_days' => $this->total_days,
                        'status' => false, // false = pending, true = approved
                        'attachment' => $attachmentPath,
                        'created_by' => Auth::user()->name,
                    ];
                    
                    // Add emergency note to reason if provided
                    if ($this->emergency_note) {
                        $leaveData['leave_reason'] = $this->leave_reason . "\n\n[Emergency Note: " . $this->emergency_note . "]";
                    }

                    // Add class and section for students
                    if ($user->user_type === 'student') {
                        // Check if user has a student record with class and section
                        $student = \App\Models\Student::where('user_id', $userId)->first();
                        if ($student) {
                            $leaveData['class_id'] = $student->class_id ?? null;
                            $leaveData['section_id'] = $student->section_id ?? null;
                        }
                    }

                    
                    $leave = LeaveRecord::create($leaveData);
                    
                    $createdCount++;

                } catch (\Exception $e) {
                    $failedUsers[] = "{$user->name} ({$user->email}): " . $e->getMessage();
                    \Log::error('Bulk leave creation failed for user ' . $userId, [
                        'error' => $e->getMessage(),
                        'leaveData' => $leaveData,
                        'user' => $user->toArray()
                    ]);
                }
            }

            DB::commit();
            
            $message = "Successfully created {$createdCount} leave record(s).";
            if (!empty($failedUsers)) {
                $message .= " Failed for " . count($failedUsers) . " user(s).";
            }

            $this->dispatch('success', message: $message);
            
            if (!empty($failedUsers)) {
                $this->dispatch('warning', message: 'Some records failed: ' . implode(', ', array_slice($failedUsers, 0, 3)) . (count($failedUsers) > 3 ? '...' : ''));
            }

            $this->closeModal();
            $this->dispatch('refreshLeaveList');

        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatch('error', message: 'Bulk leave creation failed: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.leave.bulk-create');
    }
}
