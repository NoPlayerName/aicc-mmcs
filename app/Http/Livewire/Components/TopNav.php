<?php

namespace App\Http\Livewire\Components;

use App\Http\Livewire\BaseLivewireComponent;
use Livewire\Component;

class TopNav extends BaseLivewireComponent
{
    public function render()
    {
        $this->mountBase();
        return view('livewire.components.top_nav');
    }
}
