<?php

namespace App\Livewire\Auth;

use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Login extends Component
{
    #[Validate('required', message: 'Please enter username or email.')]
    public $login_input = '';

    #[Validate('required', message: 'Please enter your password.')]
    #[Validate('min:6', message: 'Password must be at least 6 characters.')]
    public $password = '';

    public $remember = false;

    function mount()
    {
        //If the user is already authenticated, redirect them to the previous page
        if ( Auth::check() ) 
        {
            $this->redirect( url()->previous());
        }
    }
   
    #[Title('Login')]
    public function render()
    {
        return view('livewire.auth.login');
    }

    public function login()
    {
        $this->doLogin();
    }

    private function doLogin()
    {
        $this->validate();

        $field = filter_var($this->login_input, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $field => $this->login_input,
            'password' => $this->password,
        ];

        $user = User::where($field, $this->login_input)->first();
        
        if ($user && $user->is_active === 0) {
            $this->dispatch('error', message: 'Your account is inactive. Please contact the administrator.');
            return;
        }

        if (Auth::attempt($credentials, $this->remember)) 
        {
            session()->regenerate();
            $this->redirect('/dashboard');
        } 
        else
        {
            $this->dispatch('error', message: 'Login failed. Please check your credentials.');
        }
    }
}
