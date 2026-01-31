<?php

namespace App\Http\Livewire\Jsh;

use App\Enums\EnumTypeAdditive;
use App\Enums\EnumTypeMat;
use App\Services\MaterialUseJsh\MaterialUseJshService;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class AdditiveMaterial extends Component
{


    public $dataAdditiveMat = [];
    public $material = '';
    public $weight = '';
    public $typeAddjust = '';
    public $totalWeight = 0;
    #[Reactive]
    public $chargeId;

    public function rules()
    {
        return [
            'material' => 'required',
            'weight' => 'required',
        ];
    }

    public function mount($chargeId = null)
    {
        $this->chargeId = $chargeId;
        // dd($this->chargeId);
    }

    #[On('AdditiveMat')]
    public function changeAdditiveMat($data)
    {
        $this->material = $data;
    }
    #[On('TypeAddjust')]
    public function TypeAddjust($data)
    {
        $this->typeAddjust = $data;
    }

    public function addMat()
    {
        $user = Auth::user()->usr;
        $this->validate();
        $this->dataAdditiveMat[] = [
            'charging_head_id' => $this->chargeId,
            'material_id' => $this->material,
            'weight' => $this->weight,
            'type' => EnumTypeMat::Additive->value,
            'type_additive' => $this->typeAddjust,
            'type_additive_text' =>  EnumTypeAdditive::tryFrom($this->typeAddjust)?->text(),
            'created_by' => $user,
            'created_at' => now(),
        ];

        // reset input
        $this->material = null;
        $this->typeAddjust = null;
        $this->weight = null;
    }

    public function remove($index)
    {
        unset($this->dataAdditiveMat[$index]);
        $this->dataAdditiveMat = array_values($this->dataAdditiveMat);
    }
    public function save()
    {
        $save = app(MaterialUseJshService::class)->saveAdditiveMat($this->dataAdditiveMat);
        if ($save) {
            $this->reset(['dataAdditiveMat', 'totalWeight']);
            // $this->dispatch('saved');
            $this->dispatch('success', message: 'Data Additive berhasil disave');
        } else {
            $this->dispatch('error', message: 'Data Additive gagal save');
        }
    }

    public function render()
    {
        return view('livewire.jsh.additive-material');
    }
}
