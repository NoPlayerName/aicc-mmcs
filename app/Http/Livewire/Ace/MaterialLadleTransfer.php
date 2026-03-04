<?php

namespace App\Http\Livewire\Ace;

use App\Http\Livewire\BaseLivewireComponent;
use App\Services\MaterialUseAce\MaterialUseAceService;
use Livewire\Attributes\On;
use Livewire\Attributes\Renderless;
use Livewire\Attributes\Title;
use Livewire\Component;

class MaterialLadleTransfer extends BaseLivewireComponent
{
    public $products = [];
    public $date;
    public $shift;

    public $dataLadle = [];

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

            // $this->dispatch('$refresh')->self();
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
            // $this->dispatch('$refresh')->self();
        }
    }

    #[On('refreshLadleTransfer')]
    public function refreshLadleTransfer()
    {
        $this->loadData();
    }

    #[Renderless]
    public function showFormEdit($id)
    {
        $this->dispatch('showFormEdit', id: (int) $id);
    }

    #[Renderless]
    public function showFormDetail($id)
    {
        $this->dispatch('showLadleTransferDetail', id: (int) $id);
    }

    #[On('loadInoculant')]
    public function loadData()
    {
        $this->dataLadle = app(MaterialUseAceService::class)
            ->getLadleTransfer($this->date, $this->shift) ?? collect();
    }

    #[Renderless]
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
