<?php

namespace App\Livewire\User\Teacher;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Designation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class Create extends Component
{
    use WithFileUploads;

    protected $listeners = [
        'submit-create-form' => 'createTeacher',
    ];

    public $isModal = false;
    public $userData = [
        'name' => '', 'email' => '', 'username' => '', 'password' => '', 'dob' => '', 'gender' => '', 'religion' => '', 'phone' => '', 'address' => '', 'country' => '', 'city' => '', 'state' => '', 'avatar' => null, 'cnic' => '', 'blood_group' => '', 'registration_no' => '', 'transport_status' => '0', 'transport_id' => '', 'is_active' => 1
    ];
    public $teacherData = [
        'designation_id' => '', 'joining_date' => '', 'qualification' => '', 'basic_salary' => ''
    ];
    public $usernameAvailable = true;
    public $cnicAvailable = true;

    protected $rules = [
        'userData.name' => 'required|string|max:255',
        'userData.email' => 'required|email|unique:users,email',
        'userData.username' => 'required|string|unique:users,username',
        'userData.password' => 'required|string|min:6',
        'userData.dob' => 'nullable|date',
        'userData.gender' => 'nullable|string',
        'userData.cnic' => 'required|string|max:30|regex:/^\d{5}-\d{7}-\d{1}$/|unique:users,cnic',
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
        'userData.email.unique' => 'This email is already taken.',
        'userData.username.required' => 'The username field is required.',
        'userData.username.string' => 'The username must be a valid text.',
        'userData.username.unique' => 'This username is already taken.',
        'userData.password.required' => 'The password field is required.',
        'userData.password.string' => 'The password must be a valid text.',
        'userData.password.min' => 'The password must be at least 6 characters.',
        'userData.dob.date' => 'The date of birth must be a valid date.',
        'userData.gender.string' => 'The gender must be a valid text.',
        'userData.cnic.string' => 'The CNIC must be valid.',
        'userData.cnic.max' => 'The CNIC may not be greater than 30 characters.',
        'userData.cnic.regex' => 'The CNIC format is invalid. Please use format: 12345-1234567-1',
        'userData.cnic.unique' => 'This CNIC is already registered.',
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
        $this->usernameAvailable = !User::where('username', $this->userData['username'])->exists();
    }
    
    public function updatedUserDataCnic()
    {
        // Only check if CNIC is not empty
        if (!empty($this->userData['cnic']) && trim($this->userData['cnic']) !== '') {
            // First check format
            if (!preg_match('/^\d{5}-\d{7}-\d{1}$/', $this->userData['cnic'])) {
                $this->cnicAvailable = true; // Don't show "already taken" for format errors
                return;
            }
            
            // Then check availability
            $this->cnicAvailable = !User::where('cnic', $this->userData['cnic'])->exists();
        } else {
            $this->cnicAvailable = true;
        }
    }



    public function updatedTeacherDataDesignationId()
    {
        $this->validateOnly('teacherData.designation_id');
    }

    public function mount($modal = false)
    {
        $this->isModal = $modal;
    }

    public function openCreateModal()
    {
        $this->isModal = true;
        
        // Reset with default values
        $this->userData = [
            'name' => '', 'email' => '', 'username' => '', 'password' => '', 'dob' => '', 'gender' => '', 'religion' => '', 'phone' => '', 'address' => '', 'country' => '', 'city' => '', 'state' => '', 'avatar' => null, 'cnic' => '', 'blood_group' => '', 'registration_no' => '', 'transport_status' => '0', 'transport_id' => '', 'is_active' => 1
        ];
        $this->teacherData = [
            'designation_id' => '', 'joining_date' => '', 'qualification' => '', 'basic_salary' => ''
        ];
        $this->usernameAvailable = true;
        $this->cnicAvailable = true;
        $this->resetErrorBag();
        
        // Dispatch event to show the modal
        $this->dispatch('showCreateModal');
    }

    public function closeModal($dispatchEvent = true)
    {
        // Reset with default values
        $this->userData = [
            'name' => '', 'email' => '', 'username' => '', 'password' => '', 'dob' => '', 'gender' => '', 'religion' => '', 'phone' => '', 'address' => '', 'country' => '', 'city' => '', 'state' => '', 'avatar' => null, 'cnic' => '', 'blood_group' => '', 'registration_no' => '', 'transport_status' => '0', 'transport_id' => '', 'is_active' => 1
        ];
        $this->teacherData = [
            'designation_id' => '', 'joining_date' => '', 'qualification' => '', 'basic_salary' => ''
        ];
        $this->usernameAvailable = true;
        $this->cnicAvailable = true;
        $this->resetErrorBag();
        $this->isModal = false;
        
        if ($dispatchEvent) {
            $this->dispatch('hideCreateModal');
        }
    }

    public function createTeacher()
    {
        try {
            // Add logging to debug
            Log::info('Creating teacher - Start validation');
            $this->validate();
            Log::info('Validation passed');
            
            $userData = $this->userData;
            $userData['user_type'] = 'teacher';
            $userData['password'] = bcrypt($userData['password']);
            $userData['avatar'] = $userData['avatar'] ? $userData['avatar']->store('avatars', 'public') : null;
            $userData['created_by'] = Auth::user() ? Auth::user()->name : null;
            
            // Handle empty CNIC - convert empty string to null to avoid unique constraint issues
            if (empty($userData['cnic']) || trim($userData['cnic']) === '') {
                $userData['cnic'] = null;
            }
            
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
            
            Log::info('Creating user');
            $user = User::create($userData);
            Log::info('User created with ID: ' . $user->id);
            
            Teacher::create(array_merge($this->teacherData, [
                'user_id' => $user->id,
                'created_by' => Auth::user() ? Auth::user()->name : null,
            ]));
            Log::info('Teacher record created');
            
            if ($this->isModal) {
                Log::info('Dispatching events for modal mode');
                $this->dispatch('success', ['message' => 'Teacher created successfully!']);
                $this->reset(); // Reset form fields
                
                // Close modal and dispatch refresh
                $this->closeModal();
                $this->dispatch('teacherCreated')->to('user.teacher.index');
            } else {
                $this->dispatch('success', ['message' => 'Teacher created successfully.']);
                return $this->redirect('/teachers', true);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Re-throw validation exceptions to show field errors
            Log::error('Validation error: ' . json_encode($e->errors()));
            throw $e;
        } catch (\Exception $e) {
            // Handle any other errors
            Log::error('Error creating teacher: ' . $e->getMessage());
            $this->dispatch('error', ['message' => 'An error occurred while creating the teacher: ' . $e->getMessage()]);
        }
    }

    #[Title('Add Teacher')]
    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.user.teacher.create-modal', [
            'designations' => Designation::all(),
            'usernameAvailable' => $this->usernameAvailable,
            'cnicAvailable' => $this->cnicAvailable,
        ]);
    }
}