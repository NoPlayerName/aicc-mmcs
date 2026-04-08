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
    public $openIndex = null;
    public function mount()
    {
        $permissionAcces =  $this->mountBase();
        if (!$permissionAcces) {
            // $this->dispatch('error', message: 'You no have access to this menu!');
            session()->flash('error', 'You no have access to this menu!');
            return redirect()->route('dashboard');
        }
        $this->changeFilter();
    }
    public function updatedShift($value)
    {
        // Cek apakah shift masuk
        $this->changeFilter();
    }
    public function updatedDate($value)
    {
        // Cek apakah shift masuk
        $this->changeFilter();
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
    #[On('refreshData')]
    public function changeFilter()
    {
        $this->data = app(PlanProductionService::class)
            ->getPlanProd($this->date, $this->shift) ?? collect();
    }
    // public function LoadData()
    // {
    //     $this->data = app(PlanProductionService::class)
    //         ->getPlanProd(null, null) ?? collect();
    // }

    // Fungsi untuk handle klik accordion
    public function toggleAccordion($index)
    {
        if ($this->openIndex === $index) {
            $this->openIndex = null; // Tutup jika yang terbuka diklik lagi
        } else {
            $this->openIndex = $index; // Buka yang diklik
        }
    }


    public function Proccess($dataPlan, $dataCharge)
    {
        $this->openIndex = $dataPlan;
        $Data = $this->data[$dataPlan]['chargings'][$dataCharge];
        $Data['is_edit'] = false;
        // $dataCharge = app(MaterialUseJshService::class)->getChargeById($id);
        $this->dispatch('FormInputMat', data: $Data)->to(FormMaterialInput::class);
    }
    public function Detail($dataPlan, $dataCharge)
    {
        $this->openIndex = $dataPlan;
        $Data = $this->data[$dataPlan]['chargings'][$dataCharge];
        // $dataCharge = app(MaterialUseJshService::class)->getChargeById($id);
        $this->dispatch('DetailCharging', data: $Data)->to(DetailCharging::class);
    }
    public function Edit($dataPlan, $dataCharge)
    {
        $this->openIndex = $dataPlan;
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
