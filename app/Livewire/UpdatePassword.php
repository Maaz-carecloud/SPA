<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class UpdatePassword extends Component
{
    public $old_password = '';
    public $new_password = '';
    public $confirm_password = '';

    protected $rules = [
        'old_password' => 'required',
        'new_password' => 'required|min:8',
        'confirm_password' => 'required|same:new_password',
    ];

    protected $messages = [
        'confirm_password.same' => 'The new password and confirmation do not match.',
        'new_password.min' => 'The new password must be at least 8 characters.',
    ];

    public function mount()
    {
        $this->reset(['old_password', 'new_password', 'confirm_password']);
    }

    public function updatePassword()
    {
        $this->resetErrorBag();
        $this->validate();

        $user = Auth::user();
        if (!Hash::check($this->old_password, $user->password)) {
            $this->dispatch('error', message: 'Old password is incorrect.');
            return;
        }

        $user->password = Hash::make($this->new_password);
        $user->save();
        $this->reset(['old_password', 'new_password', 'confirm_password']);
        $this->dispatch('success', message: 'Password updated successfully!');
    }
    
    #[Title('Update Password')]
    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.update-password');
    }
}
