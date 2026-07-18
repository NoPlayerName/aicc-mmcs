<?php

namespace App\Http\Livewire\Jsh;

use App\Http\Livewire\BaseLivewireComponent;
use App\Services\MaterialUseJsh\MaterialUseJshService;
use App\Services\PlanProductionJsh\PlanProductionService;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;

class MaterialInput extends BaseLivewireComponent
{

    public $data = [];
    public $date;
    public $shift;
    public $furnace = [];
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
        if (is_null($this->date) || is_null($this->shift)) {
            $this->loadFurnaceHead();
        } else {
            $this->data = app(PlanProductionService::class)
                ->getFurnaceHead($this->date, $this->shift) ?? collect();
        }
        // dd($this->data);
    }
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
        // dd($Data);
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

    // Fungsi untuk menambahkan manual furnace head baru
    #[On('loadFurnaceHead')]
    public function loadFurnaceHead()
    {
        // $this->openIndex = $dataPlan;
        $data = app(PlanProductionService::class)->getFurnaceHead();
        // dd($data);
        $this->data = $data;
    }

    public function addFurnace()
    {
        $query = app(PlanProductionService::class)->generateFurnace();
        if ($query['status']) {
            $this->changeFilter();
            $this->dispatch('success', message: $query['message']);
        } else {
            $this->dispatch('error', message: 'Gagal menambahkan furnace head.');
        }
    }

    public function addManualCharging($dataPlan, $furnace, $date, $shiftF)
    {
        $this->openIndex = $dataPlan;
        // dd($furnace, $date, $shiftF);

        $result = app(MaterialUseJshService::class)->createManualCharging($furnace, $date, $shiftF);
        // dd($result);
        // $date = $this->date ? \Carbon\Carbon::createFromFormat('d/m/Y', $this->date)->format('Y-m-d') : now()->format('Y-m-d');
        // $shift = $this->shift ?? (($this->date ? \Carbon\Carbon::createFromFormat('d/m/Y', $this->date) : now())->hour >= 7 && ($this->date ? \Carbon\Carbon::createFromFormat('d/m/Y', $this->date) : now())->hour < 20 ? 'D' : 'N');

        // $result = app(\App\Services\MaterialUseJsh\MaterialUseJshService::class)->createManualCharging($furnace, $date, $shift, 1); 
        // Start with charging 1

        if ($result['status']) {
            $this->dispatch('refreshData');
            $this->dispatch('success', message: $result['message']);
        } else {
            $this->dispatch('error', message: 'Gagal menambahkan charging manual');
        }
    }

    #[Title('JSH Material Input')]
    public function render()
    {

        return view('livewire.jsh.material-input');
    }
}
