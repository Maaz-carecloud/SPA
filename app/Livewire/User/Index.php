<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Index extends Component
{
    public $users;
    public $name, $email, $password, $is_edit = false, $user_id, $getUser;
    public $modalTitle = 'Create User';
    public $modalAction = 'create-user';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
    ];


    public function mount()
    {
        $this->loadUsers();
    }

    #[On('create-user')]
    public function save(){
        $this->validate();
        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password)
        ]);
        $this->dispatch('success', message: 'User created successfully');
        $this->dispatch('hide-modal');
        $this->resetFields();
        $this->dispatch('datatable-reinit');
    }

    #[On('edit-mode')]
    public function loadEditModal($id){
        $this->modalTitle = 'Edit User';
        $this->modalAction = 'edit-user';
        $this->is_edit = true;

        $this->getUser = User::findOrFail($id);
        $this->name = $this->getUser->name;
        $this->email = $this->getUser->email;
        $this->password = '';
    }

    #[On('edit-user')]
    public function update(){
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->getUser->id,
            'password' => 'nullable|min:6',
        ]);
        $u = User::findOrFail($this->getUser->id);
        $u->name = $this->name;
        $u->email = $this->email;
        if ($this->password) {
            $u->password = Hash::make($this->password);
        }
        $u->save();
        $this->dispatch('success', message: 'User updated successfully');
        $this->dispatch('hide-modal');
        $this->resetFields();
        $this->dispatch('datatable-reinit');
    }

    #[On('delete-record')]
    public function delete($id) {
        User::findOrFail($id)->delete();
        $this->dispatch('success', message: 'User deleted successfully');
        $this->loadUsers();
        $this->dispatch('datatable-reinit');
    }

    #[On('create-user-close')]
    #[On('edit-user-close')]
    public function resetFields(){
        $this->reset(['name', 'email', 'password', 'getUser']);
        $this->modalTitle = 'Create User';
        $this->modalAction = 'create-user';
        $this->is_edit = false;
        $this->dispatch('hide-modal');
        $this->loadUsers();
        $this->dispatch('datatable-reinit');
    }

    public function loadUsers(){
        $this->users = User::orderByDesc('created_at')->get();
    }

    #[Title('All Users')]
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.user.index');
    }
}
