<?php

namespace App\Http\Livewire\Ace;

use App\Http\Livewire\BaseLivewireComponent;
use Livewire\Attributes\Title;
use Livewire\Component;

class MaterialLadleTransfer extends BaseLivewireComponent
{

    public function mount()
    {
        $this->mountBase();
    }
    public function addInoculant()
    {
        $this->dispatch('showFormInoculant');
    }
    #[Title('ACE Ladle Transfer Input ')]
    public function render()
    {
        return view('livewire.ace.material-ladle-transfer');
    }
}
