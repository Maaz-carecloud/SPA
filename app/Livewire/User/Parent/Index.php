<?php

namespace App\Livewire\User\Parent;

use App\Models\User;
use App\Models\ParentModel;
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
    public $name, $email, $username, $password, $user_type = 'parent', $dob, $gender, $religion, $phone, $address, $country, $city, $state, $avatar, $cnic, $blood_group, $registration_no, $transport_status, $transport_id, $is_active = 1, $library, $created_by, $updated_by;
    // Parent fields
    public $father_profession, $mother_name, $mother_contact, $mother_profession, $ntn_no;

    // Modal state
    public $modalTitle = 'Create Parent';
    public $modalAction = 'create-parent';
    public $is_edit = false;
    public $getParent;
    public $deleteId;
    public $is_delete = false;

    public function mount() {
        // No eager loading, DataTable will fetch via AJAX
    }

    // Server-side DataTable AJAX handler
    public function getDataTableRows()
    {
        $request = request();
        $search = $request->input('search.value');
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        if ($length == -1) {
            $length = 1000; // Safe upper limit for 'All'
        }
        $query = ParentModel::with('user')
            ->orderByDesc('created_at');

        if ($search) {
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('username', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%")
                  ->orWhere('gender', 'like', "%$search%") ;
            });
        }

        $total = $query->count();
        $parents = $query->skip($start)->take($length)->get();

        $data = [];
        foreach ($parents as $index => $parent) {
            $user = $parent->user;
            $data[] = [
                $start + $index + 1,
                e(optional($user)->name),
                e(optional($user)->email),
                e(optional($user)->username),
                e(optional($user)->phone),
                e(optional($user)->gender),
                optional($user)->is_active ? 'Active' : 'Inactive',
                '<div class="action-items"><span><a href="#" onclick="Livewire.dispatch(\'edit-mode\', {id: ' . $parent->id . '})" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fa fa-edit"></i></a></span>'
                . '<span><a href="javascript:void(0)" class="delete-swal" data-id="' . $parent->id . '"><i class="fa fa-trash"></i></a></span></div>'
            ];
        }

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data,
        ]);
    }

    #[On('create-parent')]
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
            'father_profession' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'mother_contact' => 'nullable|string|max:255',
            'mother_profession' => 'nullable|string|max:255',
            'ntn_no' => 'nullable|string|max:255',
        ]);
        $avatarPath = $this->avatar ? $this->avatar->store('avatars', 'public') : null;
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'password' => Hash::make($this->password),
            'user_type' => 'parent',
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
        ParentModel::create([
            'user_id' => $user->id,
            'father_profession' => $this->father_profession,
            'mother_name' => $this->mother_name,
            'mother_contact' => $this->mother_contact,
            'mother_profession' => $this->mother_profession,
            'ntn_no' => $this->ntn_no,
            'created_by' => Auth::user() ? Auth::user()->name : null,
            'updated_by' => Auth::user() ? Auth::user()->name : null,
        ]);
        $this->dispatch('success', message: 'Parent created successfully!');
        $this->dispatch('hide-modal');
        $this->resetFields();
        $this->dispatch('datatable-reinit');
        // Removed loadParents call as it is no longer needed
    }

    #[On('edit-mode')]
    public function loadEditModal($id) {
        $this->modalTitle = 'Edit Parent';
        $this->modalAction = 'edit-parent';
        $this->is_edit = true;
        $parent = ParentModel::with('user')->findOrFail($id);
        $user = $parent->user;
        $this->getParent = $parent;
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
        $this->father_profession = $parent->father_profession;
        $this->mother_name = $parent->mother_name;
        $this->mother_contact = $parent->mother_contact;
        $this->mother_profession = $parent->mother_profession;
        $this->ntn_no = $parent->ntn_no;
    }

    #[On('edit-parent')]
    public function update() {
        $parent = $this->getParent;
        $user = $parent->user;
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
            'father_profession' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'mother_contact' => 'nullable|string|max:255',
            'mother_profession' => 'nullable|string|max:255',
            'ntn_no' => 'nullable|string|max:255',
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
        $parent->update([
            'father_profession' => $this->father_profession,
            'mother_name' => $this->mother_name,
            'mother_contact' => $this->mother_contact,
            'mother_profession' => $this->mother_profession,
            'ntn_no' => $this->ntn_no,
            'updated_by' => Auth::user() ? Auth::user()->name : null,
        ]);
        $this->dispatch('success', message: 'Parent updated successfully!');
        $this->dispatch('hide-modal');
        $this->resetFields();
        $this->dispatch('datatable-reinit');
        // Removed loadParents call as it is no longer needed
    }

    #[On('delete-record')]
    public function delete($id) {
        $parent = ParentModel::findOrFail($id);
        $user = $parent->user;
        $parent->delete();
        if ($user) { $user->delete(); }
        $this->dispatch('success', message: 'Parent and user deleted successfully.');
        // Removed loadParents call as it is no longer needed
        $this->dispatch('datatable-reinit');
    }

    #[On('create-parent-close')]
    #[On('edit-parent-close')]
    public function resetFields() {
        foreach ([
            'name','email','username','password','dob','gender','religion','phone','address','country','city','state','avatar','cnic','blood_group','registration_no','transport_status','transport_id','is_active','library','father_profession','mother_name','mother_contact','mother_profession','ntn_no','getParent'
        ] as $field) {
            $this->$field = null;
        }
        $this->modalTitle = 'Create Parent';
        $this->modalAction = 'create-parent';
        $this->is_edit = false;
        $this->dispatch('hide-modal');
        // Removed loadParents call as it is no longer needed
        $this->dispatch('datatable-reinit');
    }

    #[Title('All Parents')]
    #[Layout('layouts.app')]
    public function render() {
        return view('livewire.user.parent.index');
    }
}
