<?php

namespace App\Http\Livewire\Jsh;

use App\Enums\EnumTypeAdditive;
use App\Enums\EnumTypeMat;
use App\Services\Master\Material\MaterialService;
use App\Services\MaterialUseJsh\MaterialUseJshService;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

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
    public $additiveSelect;
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
            $this->additiveSelect = $data;
        } else {

            $data = app(MaterialService::class)->getAdditiveMatTrial();
            $this->additiveSelect = $data;
        }
    }

    public function loadData()
    {
        if ($this->edit) {
            // Ubah ke array agar bisa digabung dengan input manual
            $data = app(MaterialUseJshService::class)->getAdditiveMat($this->chargeId);
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
        $this->dataAdditiveMat[] = [
            'charging_head_id' => $this->chargeId,
            'materialable_id' => $this->material,
            'material_name' => $this->materialText,
            'materialable_type' => $this->is_trial ? 'trial' : 'master',
            'weight' => $this->weight,
            'type' => EnumTypeMat::Additive->value,
            'type_additive' => $this->typeAddjust,
            'type_additive_text' =>  EnumTypeAdditive::tryFrom($this->typeAddjust)?->text(),
            'created_by' => $user,
            'created_at' => now(),
        ];

        // reset input
        $this->material = null;
        $this->weight = null;
    }

    public function remove($index)
    {
        unset($this->dataAdditiveMat[$index]);
        $this->dataAdditiveMat = array_values($this->dataAdditiveMat);
    }
    public function save()
    {
        if ($this->edit) {
            $save = app(MaterialUseJshService::class)->saveUpdateAdditiveMat($this->dataAdditiveMat);
            if ($save) {
                // $this->reset(['dataAdditiveMat', 'totalWeight']);
                // $this->dispatch('saved');
                $this->loadData();
                $this->dispatch('success', message: 'Data Additive berhasil diubah');
            } else {
                $this->dispatch('error', message: 'Data Additive gagal diubah');
            }
        } else {

            $save = app(MaterialUseJshService::class)->saveAdditiveMat($this->dataAdditiveMat);
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
        $this->loadSelectAdditive();
        return view('livewire.jsh.additive-material');
    }
}
