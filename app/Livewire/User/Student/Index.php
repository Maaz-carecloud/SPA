<?php

namespace App\Livewire\User\Student;

use App\Models\User;
use App\Models\Student;
use App\Models\ParentModel;
use App\Models\ClassModel;
use App\Models\Section;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

class Index extends Component
{
    use WithFileUploads;

    // User fields
    public $name, $email, $username, $password, $user_type = 'student', $dob, $gender, $religion, $phone, $address, $country, $city, $state, $avatar, $cnic, $blood_group, $registration_no, $transport_status, $transport_id, $is_active = 1, $library, $created_by, $updated_by;
    // Student fields
    public $parent_id, $admission_date, $class_id, $section_id, $roll_no, $library_status = 0, $hostel_status = 0;

    // Modal state
    public $modalTitle = 'Create Student';
    public $modalAction = 'create-student';
    public $is_edit = false;
    public $getStudent;
    public $students;
    public $deleteId;
    public $is_delete = false;

    public function mount() { $this->loadStudents(); }

    public function loadStudents() {
        $this->students = Student::with(['user', 'parent.user', 'class', 'section'])
            ->orderByDesc('created_at')
            ->get();
    }

    #[On('create-student')]
    public function save() {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:6',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string',
            'religion' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'avatar' => 'nullable|image|max:2048',
            'cnic' => ['required', 'string', 'max:30', 'regex:/^[0-9]{5}-[0-9]{7}-[0-9]{1}$/'],
            'blood_group' => 'nullable|string|max:10',
            'registration_no' => 'nullable|string|max:50',
            'transport_status' => 'nullable|in:0,1',
            'transport_id' => 'nullable|string|max:50',
            'is_active' => 'required|in:0,1',
            'parent_id' => 'nullable|exists:parents,id',
            'admission_date' => 'required|date',
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'roll_no' => 'required|integer|unique:students,roll_no',
            'library_status' => 'boolean',
            'hostel_status' => 'boolean',
        ]);
        $avatarPath = $this->avatar ? $this->avatar->store('avatars', 'public') : null;
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'password' => Hash::make($this->password),
            'user_type' => 'student',
            'dob' => $this->dob,
            'gender' => $this->gender,
            'religion' => $this->religion,
            'phone' => $this->phone,
            'address' => $this->address,
            'country' => $this->country,
            'city' => $this->city,
            'state' => $this->state,
            'avatar' => $avatarPath,
            'cnic' => $this->cnic,
            'blood_group' => $this->blood_group,
            'registration_no' => $this->registration_no,
            'transport_status' => $this->transport_status,
            'transport_id' => $this->transport_id,
            'is_active' => $this->is_active,
            'library' => $this->library,
            'created_by' => Auth::user() ? Auth::user()->name : null,
            'updated_by' => Auth::user() ? Auth::user()->name : null,
        ]);
        Student::create([
            'user_id' => $user->id,
            'parent_id' => $this->parent_id,
            'admission_date' => $this->admission_date,
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'roll_no' => $this->roll_no,
            'library_status' => $this->library_status,
            'hostel_status' => $this->hostel_status,
            'created_by' => Auth::user() ? Auth::user()->name : null,
            'updated_by' => Auth::user() ? Auth::user()->name : null,
        ]);
        $this->dispatch('success', message: 'Student created successfully!');
        $this->dispatch('hide-modal');
        $this->resetFields();
        $this->dispatch('datatable-reinit');
        $this->loadStudents();
    }

    #[On('edit-mode')]
    public function loadEditModal($id) {
        $this->modalTitle = 'Edit Student';
        $this->modalAction = 'edit-student';
        $this->is_edit = true;
        $student = Student::with('user')->findOrFail($id);
        $user = $student->user;
        $this->getStudent = $student;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->username = $user->username;
        $this->password = '';
        $this->dob = $user->dob;
        $this->gender = $user->gender;
        $this->religion = $user->religion;
        $this->phone = $user->phone;
        $this->address = $user->address;
        $this->country = $user->country;
        $this->city = $user->city;
        $this->state = $user->state;
        $this->avatar = null;
        $this->cnic = $user->cnic;
        $this->blood_group = $user->blood_group;
        $this->registration_no = $user->registration_no;
        $this->transport_status = $user->transport_status;
        $this->transport_id = $user->transport_id;
        $this->is_active = $user->is_active;
        $this->library = $user->library;
        $this->parent_id = $student->parent_id;
        $this->admission_date = $student->admission_date;
        $this->class_id = $student->class_id;
        $this->section_id = $student->section_id;
        $this->roll_no = $student->roll_no;
        $this->library_status = $student->library_status;
        $this->hostel_status = $student->hostel_status;
    }

    #[On('edit-student')]
    public function update() {
        $student = $this->getStudent;
        $user = $student->user;
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'username' => 'required|string|unique:users,username,' . $user->id,
            'dob' => 'nullable|date',
            'gender' => 'nullable|string',
            'religion' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'avatar' => 'nullable|image|max:2048',
            'cnic' => ['required', 'string', 'max:30', 'regex:/^[0-9]{5}-[0-9]{7}-[0-9]{1}$/'],
            'blood_group' => 'nullable|string|max:10',
            'registration_no' => 'nullable|string|max:50',
            'transport_status' => 'nullable|in:0,1',
            'transport_id' => 'nullable|string|max:50',
            'is_active' => 'required|in:0,1',
            'parent_id' => 'nullable|exists:parents,id',
            'admission_date' => 'required|date',
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'roll_no' => 'required|integer|unique:students,roll_no,' . $student->id,
            'library_status' => 'boolean',
            'hostel_status' => 'boolean',
        ]);
        $avatarPath = $this->avatar ? $this->avatar->store('avatars', 'public') : $user->avatar;
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'password' => $this->password ? Hash::make($this->password) : $user->password,
            'dob' => $this->dob,
            'gender' => $this->gender,
            'religion' => $this->religion,
            'phone' => $this->phone,
            'address' => $this->address,
            'country' => $this->country,
            'city' => $this->city,
            'state' => $this->state,
            'avatar' => $avatarPath,
            'cnic' => $this->cnic,
            'blood_group' => $this->blood_group,
            'registration_no' => $this->registration_no,
            'transport_status' => $this->transport_status,
            'transport_id' => $this->transport_id,
            'is_active' => $this->is_active,
            'library' => $this->library,
            'updated_by' => Auth::user() ? Auth::user()->name : null,
        ]);
        $student->update([
            'parent_id' => $this->parent_id,
            'admission_date' => $this->admission_date,
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'roll_no' => $this->roll_no,
            'library_status' => $this->library_status,
            'hostel_status' => $this->hostel_status,
            'updated_by' => Auth::user() ? Auth::user()->name : null,
        ]);
        $this->dispatch('success', message: 'Student updated successfully!');
        $this->dispatch('hide-modal');
        $this->resetFields();
        $this->dispatch('datatable-reinit');
        $this->loadStudents();
    }

    #[On('delete-record')]
    public function delete($id) {
        $student = Student::findOrFail($id);
        $user = $student->user;
        $student->delete();
        if ($user) { $user->delete(); }
        $this->dispatch('success', message: 'Student and user deleted successfully.');
        $this->loadStudents();
        $this->dispatch('datatable-reinit');
    }

    #[On('create-student-close')]
    #[On('edit-student-close')]
    public function resetFields() {
        foreach ([
            'name','email','username','password','dob','gender','religion','phone','address','country','city','state','avatar','cnic','blood_group','registration_no','transport_status','transport_id','is_active','library','parent_id','admission_date','class_id','section_id','roll_no','library_status','hostel_status','getStudent'
        ] as $field) {
            $this->$field = null;
        }
        $this->modalTitle = 'Create Student';
        $this->modalAction = 'create-student';
        $this->is_edit = false;
        $this->dispatch('hide-modal');
        $this->loadStudents();
        $this->dispatch('datatable-reinit');
    }

    #[Title('All Students')]
    #[Layout('layouts.app')]
    public function render() {
        return view('livewire.user.student.index', [
            'students' => $this->students,
            'parents' => ParentModel::with('user')->get(),
            'classes' => ClassModel::all(),
            'sections' => Section::all(),
        ]);
    }
}
