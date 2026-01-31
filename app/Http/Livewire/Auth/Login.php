<?php

namespace App\Http\Livewire\Auth;

use App\Services\Auth\AuthService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Login extends Component
{
    public $user;
    public $password;

    public function login()
    {
        $this->validate(
            [
                'user' => ['required', 'string'],
                'password' => ['required', 'string'],
            ]
        );

        if (AuthService::login($this->user, $this->password)) {
            session()->flash('message', 'Login berhasil!');
            return redirect()->intended('/');
        } else {
            session()->flash('error', 'Login gagal. Silakan periksa kredensial Anda.');
            $this->reset(['user', 'password']);
        }
    }

    #[Layout('layouts.auth.app')]
    #[Title('Login')]
    public function render()
    {
        return view('livewire.auth.login');
    }
}
