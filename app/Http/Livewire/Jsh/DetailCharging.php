<?php

namespace App\Http\Livewire\Jsh;

use App\Services\MaterialUseJsh\MaterialUseJshService;
use Livewire\Attributes\On;
use Livewire\Component;

class DetailCharging extends Component
{
    public $lot = '-';
    public $id;
    public $chargeId;
    public $product = '-';
    public $charging = '-';
    public $description = '-';
    public $rawMaterial = [];
    public $additive = [];
    public $kwh = [];
    public $tapping = [];


    #[On('DetailCharging')]
    public function showForm($data)
    {
        $this->id = $data['plan_id_anchor'];
        $this->chargeId = $data['id'];
        $this->lot = $data['lot'] ?? '-';
        $this->charging = $data['charging'] ?? '-';
        $this->product = $data['model_id'] ?? "-";
        $this->description = $data['desc'] ?? "-";

        $data = app(MaterialUseJshService::class)->getDetail($this->chargeId, $this->id);
        $this->rawMaterial = $data->rawMat ?? [];
        $this->additive = $data->additive ?? [];
        $this->kwh = $data->kwh ?? [];
        $this->tapping = $data->tapping ?? [];

        // dd($this->kwh);

        $this->dispatch('showDetailCharge');
    }
    public function render()
    {
        return view('livewire.jsh.detail-charging');
    }
}
