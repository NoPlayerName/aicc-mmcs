<?php

namespace App\Http\Livewire\Ace;

use App\Http\Livewire\BaseLivewireComponent;
use App\Services\MaterialUseAce\MaterialUseAceService;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

class MaterialLadleTransfer extends BaseLivewireComponent
{
    public $products = [];
    public $date;
    public $shift;

    public $data = [];

    public function mount()
    {
        $this->mountBase();
        $this->loadData();
    }

    #[On('Date')]
    // #[Renderless]
    public function changeDate($data, $shift = null)
    {
        $this->date = $data;
        if (!is_null($shift)) {
            $this->shift = $shift;
        }
        if (!is_null($this->date) && !is_null($this->shift)) {
            $this->loadData();
        }
    }
    #[On('Shift')]
    // #[Renderless]
    public function changeShift($data, $date = null)
    {
        $this->shift = $data;
        if (!is_null($date)) {
            $this->date = $date;
        }
        if (!is_null($this->date) && !is_null($this->shift)) {
            $this->loadData();
        }
    }

    public function loadData()
    {
        $this->data = app(MaterialUseAceService::class)->getLadleTransfer($this->date, $this->shift) ?? collect();
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
