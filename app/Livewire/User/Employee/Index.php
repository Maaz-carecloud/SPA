<?php

namespace App\Livewire\User\Employee;

use App\Models\User;
use App\Models\Employee;
use App\Models\Designation;
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
    public $name, $email, $username, $password, $user_type = 'employee', $dob, $gender, $religion, $phone, $address, $country, $city, $state, $avatar, $cnic, $blood_group, $registration_no, $transport_status, $transport_id, $is_active = 1, $library, $created_by, $updated_by;
    // Employee fields
    public $designation_id, $joining_date, $qualification, $basic_salary;

    // Modal state
    public $modalTitle = 'Create Employee';
    public $modalAction = 'create-employee';
    public $is_edit = false;
    public $getEmployee;
    public $employees;
    public $deleteId;
    public $is_delete = false;

    public function mount() { $this->loadEmployees(); }

    public function loadEmployees() {
        $this->employees = Employee::with('user')->orderByDesc('created_at')->get();
    }

    #[On('create-employee')]
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
            'designation_id' => 'required|exists:designations,id',
            'joining_date' => 'required|date',
            'qualification' => 'nullable|string|max:255',
            'basic_salary' => 'required|numeric|min:0',
        ]);
        $avatarPath = $this->avatar ? $this->avatar->store('avatars', 'public') : null;
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'password' => Hash::make($this->password),
            'user_type' => 'employee',
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
        Employee::create([
            'user_id' => $user->id,
            'designation_id' => $this->designation_id,
            'joining_date' => $this->joining_date,
            'qualification' => $this->qualification,
            'basic_salary' => $this->basic_salary,
            'created_by' => Auth::user() ? Auth::user()->name : null,
            'updated_by' => Auth::user() ? Auth::user()->name : null,
        ]);
        $this->dispatch('success', message: 'Employee created successfully!');
        $this->dispatch('hide-modal');
        $this->resetFields();
        $this->dispatch('datatable-reinit');
        $this->loadEmployees();
    }

    #[On('edit-mode')]
    public function loadEditModal($id) {
        $this->modalTitle = 'Edit Employee';
        $this->modalAction = 'edit-employee';
        $this->is_edit = true;
        $employee = Employee::with('user')->findOrFail($id);
        $user = $employee->user;
        $this->getEmployee = $employee;
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
        $this->designation_id = $employee->designation_id;
        $this->joining_date = $employee->joining_date;
        $this->qualification = $employee->qualification;
        $this->basic_salary = $employee->basic_salary;
    }

    #[On('edit-employee')]
    public function update() {
        $employee = $this->getEmployee;
        $user = $employee->user;
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
            'designation_id' => 'required|exists:designations,id',
            'joining_date' => 'required|date',
            'qualification' => 'nullable|string|max:255',
            'basic_salary' => 'required|numeric|min:0',
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
        $employee->update([
            'designation_id' => $this->designation_id,
            'joining_date' => $this->joining_date,
            'qualification' => $this->qualification,
            'basic_salary' => $this->basic_salary,
            'updated_by' => Auth::user() ? Auth::user()->name : null,
        ]);
        $this->dispatch('success', message: 'Employee updated successfully!');
        $this->dispatch('hide-modal');
        $this->resetFields();
        $this->dispatch('datatable-reinit');
        $this->loadEmployees();
    }

    #[On('delete-record')]
    public function delete($id) {
        $employee = Employee::findOrFail($id);
        $user = $employee->user;
        $employee->delete();
        if ($user) { $user->delete(); }
        $this->dispatch('success', message: 'Employee and user deleted successfully.');
        $this->loadEmployees();
        $this->dispatch('datatable-reinit');
    }

    #[On('create-employee-close')]
    #[On('edit-employee-close')]
    public function resetFields() {
        foreach ([
            'name','email','username','password','dob','gender','religion','phone','address','country','city','state','avatar','cnic','blood_group','registration_no','transport_status','transport_id','is_active','library','designation_id','joining_date','qualification','basic_salary','getEmployee'
        ] as $field) {
            $this->$field = null;
        }
        $this->modalTitle = 'Create Employee';
        $this->modalAction = 'create-employee';
        $this->is_edit = false;
        $this->dispatch('hide-modal');
        $this->loadEmployees();
        $this->dispatch('datatable-reinit');
    }

    #[Title('All Employees')]
    #[Layout('layouts.app')]
    public function render() {
        return view('livewire.user.employee.index', [
            'employees' => $this->employees,
            'designations' => Designation::all(),
        ]);
    }
}
