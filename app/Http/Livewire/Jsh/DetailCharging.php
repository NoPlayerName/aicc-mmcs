<?php

namespace App\Http\Livewire\Jsh;

use App\Services\MaterialUseJsh\MaterialUseJshService;
use Livewire\Attributes\On;
use Livewire\Component;

class DetailCharging extends Component
{
    public $lot;
    public $id;
    public $chargeId;
    public $product;
    public $charging;
    public $rawMaterial;
    public $additive;
    public $kwh;
    public $tapping;


    #[On('DetailCharging')]
    public function showForm($data)
    {
        $this->id = $data['production_plan_id'];
        $this->chargeId = $data['chargingHeadId'];
        $this->lot = $data['lot'] ?? '-';
        $this->charging = $data['charging'] ?? '-';
        $this->product = $data['model_id'] ?? "-";

        $data = app(MaterialUseJshService::class)->getDetail($this->chargeId, $this->id);
        $this->rawMaterial = $data->rawMat;
        $this->additive = $data->additive;
        $this->kwh = $data->kwh;
        $this->tapping = $data->tapping;

        // dd($this->kwh);

        $this->dispatch('showDetailCharge');
    }
    public function render()
    {
        return view('livewire.jsh.detail-charging');
    }
}
