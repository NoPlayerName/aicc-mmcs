<?php

namespace App\Http\Livewire\Components;

use App\Services\Auth\AuthService;
use Livewire\Component;

class Topbar extends Component
{

    public function logout()
    {
        AuthService::logout();
    }
    public function render()
    {
        return view('livewire.components.topbar');
    }
}
