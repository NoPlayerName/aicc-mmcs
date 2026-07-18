<?php

namespace App\Http\Livewire\Ace;

use App\Http\Livewire\BaseLivewireComponent;
use App\Services\MaterialUseAce\MaterialUseAceService;
use App\Services\PlanProductionAce\PlanProductionAceService;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Renderless;

class MaterialInput extends BaseLivewireComponent
{
    public $furnace = [];
    public $date;
    public $shift;
    public $openIndex = null;
    public $indexCharge = null;
    public $isEditForm = false;
    public function mount()
    {
        $permissionAcces =  $this->mountBase();
        if (!$permissionAcces) {
            // $this->dispatch('error', message: 'You no have access to this menu!');
            session()->flash('error', 'You no have access to this menu!');
            return redirect()->route('dashboard');
        }
        $this->loadFurnaceHead();
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
            $this->changeFilter();
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
            $this->changeFilter();
        }
    }
    #[On('refreshData')]
    public function changeFilter()
    {
        // dd($this->date, $this->shift);
        if (is_null($this->date) || is_null($this->shift)) {
            $this->loadFurnaceHead();
        } else {
            $this->furnace = app(PlanProductionAceService::class)
                ->getFurnaceHead($this->date, $this->shift) ?? collect();
        }
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
        $this->isEditForm = false;
        $Data = $this->furnace[$dataPlan]['chargings'][$indexCharge];
        $Data['is_edit'] = false;
        $this->dispatch('FormInputMat', data: $Data)->to(FormMaterialInput::class);
    }
    public function Edit($dataPlan, $dataCharge)
    {
        $this->openIndex = $dataPlan;
        $this->indexCharge = $dataCharge;
        $this->isEditForm = true;
        $Data = $this->furnace[$dataPlan]['chargings'][$dataCharge];
        $Data['is_edit'] = true;
        // $dataCharge = app(MaterialUseJshService::class)->getChargeById($id);
        $this->dispatch('FormUpdateMat', data: $Data)->to(FormMaterialInput::class);
    }
    #[On('deleteConfirmed')]
    public function Delete($dataPlan, $dataCharge)
    {
        $this->openIndex = $dataPlan;
        $this->indexCharge = $dataCharge;
        $Data = $this->furnace[$dataPlan]['chargings'][$dataCharge];
        $dataCharge = app(MaterialUseAceService::class)->deleteCharging($Data['id']);
        if ($dataCharge) {
            $this->dispatch('deleteSuccess', message: 'Data charging berhasil dihapus.');
            $this->loadFurnaceHead();
        } else {
            $this->dispatch('error', message: 'Gagal menghapus data charging.');
            $this->loadFurnaceHead();
        }
    }
    public function Detail($dataPlan, $dataCharge)
    {
        $this->openIndex = $dataPlan;
        $Data = $this->furnace[$dataPlan]['chargings'][$dataCharge];
        // $dataCharge = app(MaterialUseJshService::class)->getChargeById($id);
        $this->dispatch('DetailCharging', data: $Data)->to(DetailCharging::class);
    }
    #[On('loadDataFormInputMat')]
    public function load()
    {
        if (is_null($this->openIndex) || is_null($this->indexCharge)) {
            return;
        }

        if (!isset($this->furnace[$this->openIndex]['chargings'][$this->indexCharge])) {
            return;
        }

        $Data = $this->furnace[$this->openIndex]['chargings'][$this->indexCharge];
        $Data['is_edit'] = $this->isEditForm;
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
            $this->dispatch('success', message: $query['message']);
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
