<?php

namespace App\Http\Livewire\Jsh;

use App\Http\Livewire\BaseLivewireComponent;
use App\Services\MaterialUseJsh\MaterialUseJshService;
use App\Services\PlanProductionJsh\PlanProductionService;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;

class MaterialInput extends BaseLivewireComponent
{

    public $data;
    public $date;
    public $shift;
    public function mount()
    {
        $this->mountBase();
    }
    public function updatedShift($value)
    {
        // Cek apakah shift masuk
        $this->ChangeFilter();
    }

    #[On('Date')]
    public function changeDate($data)
    {
        $this->date = $data;
        $this->changeFilter();
    }
    #[On('Shift')]
    public function changeShift($data)
    {
        $this->shift = $data;
        $this->changeFilter();
    }

    public function changeFilter()
    {
        $this->data = app(PlanProductionService::class)
            ->getPlanProd($this->date, $this->shift) ?? collect();
        // dd($this->data);
    }


    public function Proccess($dataPlan, $dataCharge)
    {
        $Data = $this->data[$dataPlan]['chargings'][$dataCharge];
        $Data['is_edit'] = false;
        // $dataCharge = app(MaterialUseJshService::class)->getChargeById($id);
        $this->dispatch('FormInputMat', data: $Data)->to(FormMaterialInput::class);
    }
    public function Detail($dataPlan, $dataCharge)
    {
        $Data = $this->data[$dataPlan]['chargings'][$dataCharge];
        // $dataCharge = app(MaterialUseJshService::class)->getChargeById($id);
        $this->dispatch('DetailCharging', data: $Data)->to(DetailCharging::class);
    }
    public function Edit($dataPlan, $dataCharge)
    {
        $Data = $this->data[$dataPlan]['chargings'][$dataCharge];
        $Data['is_edit'] = true;
        // $dataCharge = app(MaterialUseJshService::class)->getChargeById($id);
        $this->dispatch('FormUpdateMat', data: $Data)->to(FormMaterialInput::class);
    }

    #[Title('JSH Material Input')]
    public function render()
    {

        return view('livewire.jsh.material-input');
    }
}
