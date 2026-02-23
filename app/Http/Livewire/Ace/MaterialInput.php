<?php

namespace App\Http\Livewire\Ace;

use App\Http\Livewire\BaseLivewireComponent;
use App\Services\PlanProductionAce\PlanProductionAceService;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Attributes\On;

class MaterialInput extends BaseLivewireComponent
{
    public $furnace = [];
    public $date;
    public $shift;
    public $openIndex = null;
    public $indexCharge = null;
    public function mount()
    {
        $this->mountBase();
        $this->loadFurnaceHead();
    }
    public function toggleAccordion($index)
    {
        if ($this->openIndex === $index) {
            $this->openIndex = null; // Tutup jika yang terbuka diklik lagi
        } else {
            $this->openIndex = $index; // Buka yang diklik
        }
    }
    public function Proccess($dataPlan, $indexCharge)
    {

        $this->openIndex = $dataPlan;
        $this->indexCharge = $indexCharge;
        $Data = $this->furnace[$dataPlan]['chargings'][$indexCharge];
        $Data['is_edit'] = false;
        $this->dispatch('FormInputMat', data: $Data)->to(FormMaterialInput::class);
    }
    public function Edit($dataPlan, $dataCharge)
    {
        $this->openIndex = $dataPlan;
        $Data = $this->furnace[$dataPlan]['chargings'][$dataCharge];
        $Data['is_edit'] = true;
        // $dataCharge = app(MaterialUseJshService::class)->getChargeById($id);
        $this->dispatch('FormUpdateMat', data: $Data)->to(FormMaterialInput::class);
    }
    public function Detail($dataPlan, $dataCharge)
    {
        $this->openIndex = $dataPlan;
        $Data = $this->furnace[$dataPlan]['chargings'][$dataCharge];
        // $dataCharge = app(MaterialUseJshService::class)->getChargeById($id);
        // $this->dispatch('DetailCharging', data: $Data)->to(DetailCharging::class);
    }
    #[On('loadDataFormInputMat')]
    public function load()
    {
        $Data = $this->furnace[$this->openIndex]['chargings'][$this->indexCharge];
        $Data['is_edit'] = false;
        $this->dispatch('LoadFormInputMat', data: $Data)->to(FormMaterialInput::class);
    }


    #[On('loadFurnaceHead')]
    public function loadFurnaceHead()
    {
        // $this->openIndex = $dataPlan;
        $data = app(PlanProductionAceService::class)->getFurnaceHead();
        $this->furnace = $data;
    }

    public function addFurnace()
    {

        $query = app(PlanProductionAceService::class)->generateFurnace();
        if ($query['status']) {
            $this->loadFurnaceHead();
            $this->dispatch('success', message: 'Berhasil menambahkan furnace head.');
        }
    }
    public function addCharge($furnaceId, $indexPlan)
    {
        $this->openIndex = $indexPlan;
        $query = app(PlanProductionAceService::class)->generateCharging($furnaceId);
        if ($query['status']) {
            $this->loadFurnaceHead();
            $this->dispatch('success', message: 'Berhasil menambahkan charging head.');
        }
    }

    #[Title('ACE Material Input')]
    public function render()
    {

        return view('livewire.ace.material-input');
    }
}
