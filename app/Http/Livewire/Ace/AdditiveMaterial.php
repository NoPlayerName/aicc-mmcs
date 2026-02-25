<?php

namespace App\Http\Livewire\Ace;

use App\Enums\EnumTypeAdditive;
use App\Enums\EnumTypeMat;
use App\Services\Master\Material\MaterialService;
use App\Services\MaterialUseAce\MaterialUseAceService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class AdditiveMaterial extends Component
{
    public $dataAdditiveMat = [];
    public $material = '';
    public $materialText = '';
    public $weight = '';
    public $typeAddjust = '';
    public $totalWeight = 0;
    // #[Reactive]
    public $chargeId;
    // #[Reactive]
    public $edit;
    public $additiveSelect = [];
    public $is_trial = false;

    public function rules()
    {
        return [
            'material' => 'required',
            'weight' => 'required',
        ];
    }
    public function mount($chargeId = null)
    {
        // $this->chargeId = $chargeId;
        $this->loadSelectAdditive();
    }
    #[On('input-material-data')]
    public function inputData($id, $isEdit)
    {
        $this->reset('dataAdditiveMat');
        $this->chargeId = $id;
        $this->edit = $isEdit;
        // $this->loadData();

    }
    #[On('load-material-data')]
    public function triggerLoad($id, $isEdit)
    {
        // dd($id);
        $this->chargeId = $id;
        $this->edit = $isEdit;
        $this->loadData();
    }
    public function updatedIsTrial()
    {
        $this->reset(['weight', 'totalWeight', 'additiveSelect']);
        $this->loadSelectAdditive();
        $this->dispatch('loadAdditive');
    }

    public function loadSelectAdditive()
    {

        if (!$this->is_trial) {

            $data = app(MaterialService::class)->getAdditive();
            $this->additiveSelect = collect($data)->toArray();
        } else {

            $data = app(MaterialService::class)->getAdditiveMatTrial();
            $this->additiveSelect = collect($data)->toArray();
        }
    }

    public function loadData()
    {
        if ($this->edit) {
            // Ubah ke array agar bisa digabung dengan input manual
            $data = app(MaterialUseAceService::class)->getAdditiveMat($this->chargeId);
            // PAKSA JADI ARRAY DI SINI
            // dd($data);
            // Agar selanjutnya array_values() tidak error
            // dd($data);
            $this->dataAdditiveMat = collect($data)->toArray();
        }
    }

    #[On('AdditiveMat')]
    public function changeAdditiveMat($data, $name)
    {
        $this->material = $data;
        $this->materialText = $name;
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

        $typeAdditiveValue = is_numeric($this->typeAddjust)
            ? (int) $this->typeAddjust
            : null;

        $typeAdditive = $typeAdditiveValue !== null
            ? EnumTypeAdditive::tryFrom($typeAdditiveValue)
            : null;

        $this->dataAdditiveMat[] = [
            'charging_head_id' => $this->chargeId,
            'materialable_id' => $this->material,
            'material_name' => $this->materialText,
            'materialable_type' => $this->is_trial ? 'trial' : 'master',
            'weight' => $this->weight,
            'type' => EnumTypeMat::Additive->value,
            'type_additive' => $typeAdditiveValue,
            'type_additive_text' => $typeAdditive?->text(),
            'created_by' => $user,
            'created_at' => now(),
        ];

        // reset input
        // $this->material = null;
        $this->weight = null;
        // $this->typeAddjust = ;
    }

    public function remove($index)
    {
        unset($this->dataAdditiveMat[$index]);
        $this->dataAdditiveMat = array_values($this->dataAdditiveMat);
    }
    public function save()
    {
        if ($this->edit) {
            $save = app(MaterialUseAceService::class)->saveUpdateAdditiveMat($this->dataAdditiveMat);
            if ($save) {
                // $this->reset(['dataAdditiveMat', 'totalWeight']);
                // $this->dispatch('saved');
                $this->loadData();
                $this->dispatch('success', message: 'Data Additive berhasil diubah');
            } else {
                $this->dispatch('error', message: 'Data Additive gagal diubah');
            }
        } else {

            $save = app(MaterialUseAceService::class)->saveAdditiveMat($this->dataAdditiveMat);
            if ($save) {
                $this->reset(['dataAdditiveMat', 'totalWeight']);
                // $this->dispatch('saved');
                $this->dispatch('success', message: 'Data Additive berhasil disave');
            } else {
                $this->dispatch('error', message: 'Data Additive gagal save');
            }
        }
    }

    public function render()
    {
        return view('livewire.ace.additive-material');
    }
}
