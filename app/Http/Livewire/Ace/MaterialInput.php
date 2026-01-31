<?php

namespace App\Http\Livewire\Ace;

use App\Http\Livewire\BaseLivewireComponent;
use Livewire\Attributes\Title;
use Livewire\Component;

class MaterialInput extends BaseLivewireComponent
{
    public function mount()
    {
        $this->mountBase();
    }
    public function Proccess()
    {
        $this->dispatch('showFormInput');
    }

    #[Title('ACE Material Input')]
    public function render()
    {
        return view('livewire.ace.material-input');
    }
}
