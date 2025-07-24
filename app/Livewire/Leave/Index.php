<?php

namespace App\Livewire\Leave;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\LeaveRecord;
use App\Models\LeaveType;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Index extends Component
{
    use WithFileUploads;

    public $leaves;

    // Modal related methods
    public $modalTitle = 'Create Leave';
    public $modalAction = 'create-leave';
    public $is_edit = false;
    public $deleteId;
    public $is_delete = false;

    // Form related methods
    public $user_type;
    public $user_id;
    public $leave_type_id;
    public $leave_reason;
    public $date_from;
    public $date_to;
    public $attachment;
    public $total_days = 0;
    public $getLeave;

    public $users = [];
    public $students = [];
    public $teachers = [];
    public $employees = [];
    public $leaveTypes = [];

    public $bulk_mode = false;
    public $user_ids = [];
    public $select_all_users = false;

    public function mount(){
        $this->loadLeaves();
        $this->loadData();
        $currentUser = Auth::user();
        $isAdmin = $currentUser->hasRole('admin') ?? $currentUser->user_type === 'admin';
        if (!$isAdmin) {
            $this->user_type = $currentUser->user_type;
            $this->user_id = Auth::id();
            $this->filterUsersByType();
        } else {
            $this->users = collect();
        }
    }

    public function loadData()
    {
        $this->students = User::where('user_type', 'student')->select('id', 'name')->get();
        $this->employees = User::where('user_type', 'employee')->select('id', 'name')->get();
        $this->teachers = User::where('user_type', 'teacher')->select('id', 'name')->get();
        $this->leaveTypes = LeaveType::active()->get();
    }

    public function updatedUserType()
    {
        if (!$this->is_edit) {
            $this->user_id = '';
        }
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

    public function updatedUserId()
    {
        if ($this->user_type === 'student' && $this->user_id) {
            $student = \App\Models\User::with(['student'])->find($this->user_id);
            if ($student && $student->student) {
                // Set class_id last to trigger updatedClassId after pending_section_id is set
            }
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

    public function updatedSelectAllUsers($value)
    {
        if ($value) {
            $this->user_ids = collect($this->users)->pluck('id')->map(fn($id) => (string)$id)->toArray();
        } else {
            $this->user_ids = [];
        }
    }

    public function updatedUserIds()
    {
        $this->select_all_users = count($this->user_ids) === count($this->users);
    }

    #[On('create-leave')]
    public function save()
    {
        if ($this->bulk_mode) {
            $this->validate([
                'user_ids' => 'required|array|min:1',
                'leave_type_id' => 'required|exists:leave_types,id',
                'leave_reason' => 'required|string|max:1000',
                'date_from' => 'required|date|after_or_equal:today',
                'date_to' => 'required|date|after_or_equal:date_from',
                'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);
            foreach ($this->user_ids as $uid) {
                LeaveRecord::create([
                    'user_id' => $uid,
                    'leave_type_id' => $this->leave_type_id,
                    'leave_reason' => $this->leave_reason,
                    'date_from' => $this->date_from,
                    'date_to' => $this->date_to,
                    'total_days' => $this->total_days,
                    'attachment' => $this->attachment ? $this->attachment->store('leave_attachments', 'public') : null,
                    'created_by' => Auth::user()->name,
                    'status' => false,
                ]);
            }
            $this->dispatch('success', message: 'Bulk leave created!');
        } else {
            $this->validate([
                'user_id' => 'required|exists:users,id',
                'leave_type_id' => 'required|exists:leave_types,id',
                'leave_reason' => 'required|string|max:1000',
                'date_from' => 'required|date|after_or_equal:today',
                'date_to' => 'required|date|after_or_equal:date_from',
                'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);
            LeaveRecord::create([
                'user_id' => $this->user_id,
                'leave_type_id' => $this->leave_type_id,
                'leave_reason' => $this->leave_reason,
                'date_from' => $this->date_from,
                'date_to' => $this->date_to,
                'total_days' => $this->total_days,
                'attachment' => $this->attachment ? $this->attachment->store('leave_attachments', 'public') : null,
                'created_by' => Auth::user()->name,
                'status' => false,
            ]);
            $this->dispatch('success', message: 'Leave created!');
        }
        $this->resetFields();
        $this->dispatch('hide-modal');
        $this->loadLeaves();
        $this->dispatch('datatable-reinit');
    }

    #[On('edit-mode')]
    public function loadEditModal($id){
        $this->modalTitle = 'Edit Leave';
        $this->modalAction = 'edit-leave';
        $this->is_edit = true;
        $this->getLeave = LeaveRecord::findOrFail($id);

        $this->user_type = optional($this->getLeave->user)->user_type;
        $this->filterUsersByType(); // update users list before setting user_id
        $this->user_id = (string) $this->getLeave->user_id;

        $this->leave_type_id = $this->getLeave->leave_type_id;
        $this->leave_reason = $this->getLeave->leave_reason;
        $this->date_from = $this->getLeave->date_from ? $this->getLeave->date_from->format('Y-m-d') : null;
        $this->date_to = $this->getLeave->date_to ? $this->getLeave->date_to->format('Y-m-d') : null;
        $this->attachment = null;
        $this->total_days = $this->getLeave->total_days;
        $this->dispatch('refreshCkeditor');
    }

    #[On('edit-leave')]
    public function update(){
        $rules = [
            'user_type' => 'required|in:student,teacher,employee',
            'user_id' => 'required|exists:users,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'leave_reason' => 'required|string|max:1000',
            'date_from' => 'required|date|after_or_equal:today',
            'date_to' => 'required|date|after_or_equal:date_from',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];
        $messages = [
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
        $this->validate($rules, $messages);
        $l = LeaveRecord::findOrFail($this->getLeave->id);
        $l->user_id = $this->user_id;
        $l->leave_type_id = $this->leave_type_id;
        $l->leave_reason = $this->leave_reason;
        $l->date_from = $this->date_from;
        $l->date_to = $this->date_to;
        $l->total_days = $this->total_days;
        if ($this->attachment) {
            $l->attachment = $this->attachment->store('leave_attachments', 'public');
        }
        $l->save();
        $this->dispatch('success', message: 'Leave updated successfully');
        $this->dispatch('hide-modal');
        $this->resetFields();
        $this->loadLeaves();
        $this->dispatch('datatable-reinit');
    }

    #[On('delete-record')]
    public function delete($id) {
        LeaveRecord::findOrFail($id)->delete();
        $this->dispatch('success', message: 'Leave deleted successfully');
        $this->loadLeaves();
        $this->dispatch('datatable-reinit');
    }

    #[On('create-leave-close')]
    #[On('edit-leave-close')]
    public function resetFields(){
        $this->reset(['user_type', 'user_id', 'user_ids', 'bulk_mode', 'select_all_users', 'leave_type_id', 'leave_reason', 'date_from', 'date_to', 'attachment', 'total_days', 'getLeave']);
        $this->modalTitle = 'Create Leave';
        $this->modalAction = 'create-leave';
        $this->is_edit = false;
        $this->dispatch('hide-modal');
        $this->loadLeaves();
        $this->dispatch('datatable-reinit');
    }

    public function loadLeaves(){
        $this->leaves = LeaveRecord::orderByDesc('created_at')->get();
    }

    #[Title('All Leaves')]
    #[Layout('layouts.app')]
    public function render()
    {
        $leaveTypes = $this->leaveTypes;
        $users = $this->users;
        return view('livewire.leave.index', compact('users', 'leaveTypes'));
    }
}
