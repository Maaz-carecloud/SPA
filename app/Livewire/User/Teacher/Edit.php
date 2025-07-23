<?php

namespace App\Livewire\User\Teacher;

use App\Models\Teacher;
use App\Models\User;
use App\Models\Designation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;
use Livewire\Component;

class Edit extends Component
{
    use WithFileUploads;

    protected $listeners = [
        'submit-edit-form' => 'updateTeacher',
        'load-teacher-for-edit' => 'loadTeacher',
    ];

    public $isModal = false;
    public $isOpen = false;
    public $userData = [
        'name' => '', 'email' => '', 'username' => '', 'password' => '', 'dob' => '', 'gender' => '', 'religion' => '', 'phone' => '', 'address' => '', 'country' => '', 'city' => '', 'state' => '', 'avatar' => null, 'cnic' => '', 'blood_group' => '', 'registration_no' => '', 'transport_status' => '0', 'transport_id' => '', 'is_active' => 1
    ];
    public $teacherData = [
        'designation_id' => '', 'joining_date' => '', 'qualification' => '', 'basic_salary' => ''
    ];
    public $usernameAvailable = true;
    public $teacherId;
    public $userId;
    public $isEditingSelf = false;

    protected $rules = [
        'userData.name' => 'required|string|max:255',
        'userData.email' => 'required|email',
        'userData.username' => 'required|string',
        'userData.cnic' => 'required|string|max:15',
        'userData.password' => 'nullable|string|min:6',
        'userData.dob' => 'nullable|date',
        'userData.gender' => 'nullable|string',
        'userData.avatar' => 'nullable|image|max:2048',
        'userData.religion' => 'nullable|string|max:100',
        'userData.phone' => 'nullable|string|max:20',
        'userData.address' => 'nullable|string|max:255',
        'userData.country' => 'nullable|string|max:100',
        'userData.city' => 'nullable|string|max:100',
        'userData.state' => 'nullable|string|max:100',
        'userData.blood_group' => 'nullable|string|max:10',
        'userData.registration_no' => 'nullable|string|max:50',
        'userData.transport_status' => 'sometimes|in:0,1',
        'userData.transport_id' => 'nullable|integer|min:0',
        'userData.is_active' => 'required|in:0,1',
        'teacherData.designation_id' => 'required|exists:designations,id',
        'teacherData.joining_date' => 'required|date',
        'teacherData.qualification' => 'nullable|string',
        'teacherData.basic_salary' => 'required|numeric',
    ];

    protected $messages = [
        // User Data Validation Messages
        'userData.name.required' => 'The user name field is required.',
        'userData.name.string' => 'The user name must be a valid text.',
        'userData.name.max' => 'The user name may not be greater than 255 characters.',
        'userData.email.required' => 'The email field is required.',
        'userData.email.email' => 'The email must be a valid email address.',
        'userData.username.required' => 'The username field is required.',
        'userData.username.string' => 'The username must be a valid text.',
        'userData.cnic.required' => 'The CNIC field is required.',
        'userData.cnic.string' => 'The CNIC must be a valid text.',
        'userData.cnic.max' => 'The CNIC may not be greater than 15 characters.',
        'userData.password.string' => 'The password must be a valid text.',
        'userData.password.min' => 'The password must be at least 6 characters.',
        'userData.dob.date' => 'The date of birth must be a valid date.',
        'userData.gender.string' => 'The gender must be a valid text.',
        'userData.avatar.image' => 'The avatar must be an image file.',
        'userData.avatar.max' => 'The avatar file size must not exceed 2MB.',
        'userData.religion.string' => 'The religion must be a valid text.',
        'userData.religion.max' => 'The religion may not be greater than 100 characters.',
        'userData.phone.string' => 'The phone must be a valid text.',
        'userData.phone.max' => 'The phone may not be greater than 20 characters.',
        'userData.address.string' => 'The address must be a valid text.',
        'userData.address.max' => 'The address may not be greater than 255 characters.',
        'userData.country.string' => 'The country must be a valid text.',
        'userData.country.max' => 'The country may not be greater than 100 characters.',
        'userData.city.string' => 'The city must be a valid text.',
        'userData.city.max' => 'The city may not be greater than 100 characters.',
        'userData.state.string' => 'The state must be a valid text.',
        'userData.state.max' => 'The state may not be greater than 100 characters.',
        'userData.blood_group.string' => 'The blood group must be a valid text.',
        'userData.blood_group.max' => 'The blood group may not be greater than 10 characters.',
        'userData.registration_no.string' => 'The registration number must be a valid text.',
        'userData.registration_no.max' => 'The registration number may not be greater than 50 characters.',
        'userData.transport_status.in' => 'The transport status must be Active or Inactive.',
        'userData.transport_id.integer' => 'The transport ID must be a number.',
        'userData.transport_id.min' => 'The transport ID must be a positive number.',
        'userData.is_active.required' => 'The active status field is required.',
        'userData.is_active.in' => 'The active status must be Active or Inactive.',
        
        // Teacher Data Validation Messages
        'teacherData.designation_id.required' => 'The designation field is required.',
        'teacherData.designation_id.exists' => 'The selected designation is invalid.',
        'teacherData.joining_date.required' => 'The joining date field is required.',
        'teacherData.joining_date.date' => 'The joining date must be a valid date.',
        'teacherData.qualification.string' => 'The qualification must be a valid text.',
        'teacherData.basic_salary.required' => 'The basic salary field is required.',
        'teacherData.basic_salary.numeric' => 'The basic salary must be a number.',
    ];

    public function updatedUserDataUsername()
    {
        if ($this->userId) {
            $this->usernameAvailable = !User::where('username', $this->userData['username'])
                ->where('id', '!=', $this->userId)
                ->exists();
        }
    }
    
    public function mount($id = null, $modal = false)
    {
        $this->isModal = $modal;
        
        // Always start with a clean state
        $this->resetState();
        $this->isModal = $modal; // Set again after reset
        
        if ($id) {
            $this->loadTeacher($id);
        }
    }

    public function openModal($teacherId = null)
    {
        Log::info('Edit Modal openModal called with teacherId: ' . json_encode($teacherId));
        
        try {
            // Handle both array format and direct teacherId
            if (is_array($teacherId)) {
                $userId = $teacherId['teacherId'] ?? null;
            } else {
                $userId = $teacherId;
            }
            
            if (!$userId) {
                Log::error('Teacher ID not provided');
                $this->dispatch('error', ['message' => 'Teacher ID not provided.']);
                return;
            }
            
            // Reset component state first
            $this->resetState();
            $this->userId = $userId;
            
            // Find the user first
            $user = User::with('teacher', 'teacher.designation')->find($userId);
            if (!$user) {
                Log::error('User not found for ID: ' . $userId);
                $this->dispatch('error', ['message' => 'Teacher not found.']);
                return;
            }
            
            $teacher = $user->teacher;
            if (!$teacher) {
                Log::error('Teacher record not found for user ID: ' . $userId);
                $this->dispatch('error', ['message' => 'Teacher record not found.']);
                return;
            }

            $this->teacherId = $teacher->id;
            
            // Load user data
            $this->userData = [
                'name' => $user->name ?? '',
                'email' => $user->email ?? '',
                'username' => $user->username ?? '',
                'password' => '',
                'dob' => $user->dob ?? '',
                'gender' => $user->gender ?? '',
                'religion' => $user->religion ?? '',
                'phone' => $user->phone ?? '',
                'address' => $user->address ?? '',
                'country' => $user->country ?? '',
                'city' => $user->city ?? '',
                'state' => $user->state ?? '',
                'avatar' => null,
                'cnic' => $user->cnic ?? '',
                'blood_group' => $user->blood_group ?? '',
                'registration_no' => $user->registration_no ?? '',
                'transport_status' => $user->transport_status ?? '0',
                'transport_id' => $user->transport_id ?? '',
                'is_active' => $user->is_active ?? 1,
            ];
            
            // Load teacher data
            $this->teacherData = [
                'designation_id' => $teacher->designation_id ?? '',
                'joining_date' => $teacher->joining_date ?? '',
                'qualification' => $teacher->qualification ?? '',
                'basic_salary' => $teacher->basic_salary ?? '',
            ];
            
            // Check if current user is editing themselves
            $this->isEditingSelf = Auth::check() && Auth::id() === $user->id;
            
            $this->usernameAvailable = true;
            $this->resetErrorBag();
            $this->isOpen = true;
            $this->isModal = true;
            
            // Force a refresh to ensure data is rendered
            $this->render();
            
            // Dispatch single event to show modal
            $this->dispatch('showEditModal');
            
            Log::info('Edit modal data loaded successfully for user: ' . $this->userId);
            
        } catch (\Exception $e) {
            Log::error('Error opening edit modal: ' . $e->getMessage());
            $this->dispatch('error', ['message' => 'Unable to open edit modal. Please try again.']);
        }
    }

    protected function resetState()
    {
        $this->isOpen = false;
        $this->isModal = false;
        $this->resetErrorBag();
        $this->resetValidation();
        
        $this->userData = [
            'name' => '', 'email' => '', 'username' => '', 'password' => '', 'dob' => '', 'gender' => '', 'religion' => '', 'phone' => '', 'address' => '', 'country' => '', 'city' => '', 'state' => '', 'avatar' => null, 'cnic' => '', 'blood_group' => '', 'registration_no' => '', 'transport_status' => '0', 'transport_id' => '', 'is_active' => 1
        ];
        $this->teacherData = [
            'designation_id' => '', 'joining_date' => '', 'qualification' => '', 'basic_salary' => ''
        ];
        $this->teacherId = null;
        $this->userId = null;
        $this->usernameAvailable = true;
    }

    public function openEditModal($teacherId)
    {
        $this->openModal($teacherId);
    }

    public function loadTeacher($event = null)
    {
        if ($event === null) {
            return;
        }
        
        // Reset form first
        $this->reset(['userData', 'teacherData', 'teacherId']);
        $this->resetErrorBag();
        $this->resetValidation();
        
        // Handle both direct call and event dispatch
        $teacherId = is_array($event) ? ($event['teacherId'] ?? null) : $event;
        
        if (!$teacherId) {
            return;
        }
        
        try {
            // Find teacher by user_id instead of teacher id
            $teacher = Teacher::with('user')->where('user_id', $teacherId)->firstOrFail();
            $user = $teacher->user;
            
            $this->teacherId = $teacherId;
            $this->userId = $user->id;
            
            // Check if user is editing themselves
            $this->isEditingSelf = Auth::check() && Auth::id() === $user->id;
            
            $this->userData = [
                'name' => $user->name,
                'email' => $user->email,
                'username' => $user->username,
                'password' => '', // Don't load existing password
                'dob' => $user->dob,
                'gender' => $user->gender,
                'religion' => $user->religion,
                'phone' => $user->phone,
                'address' => $user->address,
                'country' => $user->country,
                'city' => $user->city,
                'state' => $user->state,
                'avatar' => null, // Don't load existing avatar
                'cnic' => $user->cnic,
                'blood_group' => $user->blood_group,
                'registration_no' => $user->registration_no,
                'transport_status' => $user->transport_status ?? '0',
                'transport_id' => $user->transport_id,
                'is_active' => $user->is_active
            ];
            
            $this->teacherData = [
                'designation_id' => $teacher->designation_id,
                'joining_date' => $teacher->joining_date,
                'qualification' => $teacher->qualification,
                'basic_salary' => $teacher->basic_salary
            ];
        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => 'Failed to load teacher data.']);
        }
    }

    public function updateTeacher()
    {
        try {
            // Build validation rules excluding restricted fields for self-editing
            $validationRules = $this->rules;
            $additionalRules = [];
            
            // Add unique validation for CNIC for all users
            $additionalRules['userData.cnic'] = 'required|string|max:15|unique:users,cnic,' . $this->userId;
            
            if ($this->isEditingSelf) {
                // Remove email and username from validation when editing self
                unset($validationRules['userData.email']);
                unset($validationRules['userData.username']);
            } else {
                // Add unique validation for email and username for other users
                $additionalRules['userData.email'] = 'required|email|unique:users,email,' . $this->userId;
                $additionalRules['userData.username'] = 'required|string|unique:users,username,' . $this->userId;
            }
            
            $this->validate(array_merge($validationRules, $additionalRules));
            
            Log::info('Updating teacher - Start validation passed');
            
            $teacher = Teacher::with('user')->where('user_id', $this->userId)->firstOrFail();
            $user = $teacher->user;
            $userData = $this->userData;
            
            // Remove email and username from update data when user is editing themselves
            if ($this->isEditingSelf) {
                unset($userData['email']);
                unset($userData['username']);
            }
            
            // Handle password update (only if provided)
            if (!empty($userData['password']) && trim($userData['password']) !== '') {
                $userData['password'] = bcrypt($userData['password']);
            } else {
                unset($userData['password']); // Don't update password if empty
            }
            
            // Handle avatar
            if ($userData['avatar']) {
                $userData['avatar'] = $userData['avatar']->store('avatars', 'public');
            } else {
                unset($userData['avatar']);
            }
            
            $userData['updated_by'] = Auth::user() ? Auth::user()->name : null;
            
            // Handle other nullable fields that might cause issues with empty strings
            $nullableFields = ['dob', 'gender', 'religion', 'phone', 'address', 'country', 'city', 'state', 'blood_group', 'registration_no', 'transport_id'];
            foreach ($nullableFields as $field) {
                if (empty($userData[$field]) || trim($userData[$field]) === '') {
                    $userData[$field] = null;
                }
            }
            
            // Handle transport_status specifically - it's a tinyInteger that cannot be null
            if (empty($userData['transport_status']) || trim($userData['transport_status']) === '') {
                $userData['transport_status'] = 0; // Default to 0 (Inactive)
            }
            
            Log::info('Updating user');
            $user->update($userData);
            Log::info('User updated');
            
            $teacher->update(array_merge($this->teacherData, [
                'updated_by' => Auth::user() ? Auth::user()->name : null,
            ]));
            Log::info('Teacher record updated');
            
            if ($this->isModal) {
                Log::info('Dispatching events for modal mode');
                $this->dispatch('success', ['message' => 'Teacher updated successfully!']);
                
                // Close modal and dispatch refresh
                $this->closeModal();
                $this->dispatch('teacherUpdated')->to('user.teacher.index');
            } else {
                $this->dispatch('success', ['message' => 'Teacher updated successfully.']);
                return $this->redirect('/teachers', true);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Re-throw validation exceptions to show field errors
            Log::error('Validation error: ' . json_encode($e->errors()));
            throw $e;
        } catch (\Exception $e) {
            // Handle any other errors
            Log::error('Error updating teacher: ' . $e->getMessage());
            $this->dispatch('error', ['message' => 'An error occurred while updating the teacher: ' . $e->getMessage()]);
        }
    }

    /**
     * Close the modal and reset form state
     */
    public function closeModal()
    {
        // Reset form state
        $this->resetState();
        
        // Close the modal via JavaScript
        $this->js('
            const modal = bootstrap.Modal.getInstance(document.getElementById("editTeacherModal"));
            if (modal) {
                modal.hide();
            }
        ');
    }

    #[Title('Edit Teacher')]
    public function render()
    {
        return view('livewire.user.teacher.edit-modal', [
            'designations' => Designation::all(),
            'usernameAvailable' => $this->usernameAvailable,
        ]);
    }
}