<?php

namespace App\Http\Livewire\Ace;

use App\Enums\EnumTypeMat;
use App\Services\Master\Material\MaterialService;
use App\Services\MaterialUseAce\MaterialUseAceService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class RawMaterial extends Component
{
    public $dataRawMat = [];
    public $material = '';
    public $materialText = '';
    public $weight = '';
    public $totalWeight = 0;
    // #[Reactive]
    public $chargeId;
    // #[Reactive]
    public $edit;
    public $rawMatSelect;
    public $is_trial = false;

    public function rules()
    {
        return [
            'material' => 'required',
            'weight' => 'required|numeric',
        ];
    }
    public function mount()
    {
        // $this->chargeId = $chargeId;
        // $this->edit = $edit;
        // $this->loadData();
    }
    #[On('input-material-data')]
    public function inputData($id, $isEdit)
    {
        // $id ? dd($id) : $this->chargeId = $id;
        $this->reset(['dataRawMat', 'totalWeight', 'is_trial', 'rawMatSelect']);
        $this->chargeId = $id;
        $this->edit = $isEdit;
    }
    #[On('load-material-data')]
    public function triggerLoad($id, $isEdit)
    {

        $this->chargeId = $id;
        $this->edit = $isEdit;
        $this->loadData();
    }

    public function updatedIsTrial()
    {
        $this->reset(['weight', 'totalWeight', 'rawMatSelect']);
        $this->loadSelectRawMaterial();
        $this->dispatch('loadMaterial');
    }

    public function loadSelectRawMaterial()
    {

        if (!$this->is_trial) {

            $data = app(MaterialService::class)->getRawMatAce();
            $this->rawMatSelect = $data;
        } else {

            $data = app(MaterialService::class)->getRawMatTrial();
            $this->rawMatSelect = $data;
            // dd($data);
        }
    }

    public function loadData()
    {
        if ($this->edit) {
            // Ubah ke array agar bisa digabung dengan input manual
            $data = app(MaterialUseAceService::class)->getRawMat($this->chargeId);

            // PAKSA JADI ARRAY DI SINI
            // Agar selanjutnya array_values() tidak error
            $this->dataRawMat = collect($data)->toArray();
            $this->calculate();
        }
    }


    #[On('RawMat')]
    public function changeRawMat($data, $name)
    {

        $this->material = $data;
        $this->materialText = $name;
    }

    public function addRawMat()
    {
        $user = Auth::user()->usr;
        $this->validate();
        $this->dataRawMat[] = [
            'charging_head_id' => $this->chargeId,
            'materialable_id' => $this->material,
            'material_name' => $this->materialText,
            'materialable_type' => $this->is_trial ? 'trial' : 'master',
            'weight' => $this->weight,
            'type' => EnumTypeMat::RawMaterial->value,
            'created_by' => $user,
            'created_at' => now(),
        ];

        $this->calculate();
        $this->weight = null;
    }

    public function remove($index)
    {
        $items = collect($this->dataRawMat);
        $items->forget($index);
        $this->dataRawMat = $items->values()->toArray();

        $this->calculate();
    }

    public function calculate()
    {
        $this->totalWeight = collect($this->dataRawMat)->sum('weight') . ' Kg';
    }

    public function save()
    {
        if ($this->edit) {
            $save = app(MaterialUseAceService::class)->saveUpdateRawMat($this->dataRawMat);
            if ($save) {
                // $this->reset(['dataRawMat', 'totalWeight']);
                $this->dispatch('saved');
                $this->dispatch('success', message: 'Data raw material berhasil diubah');
                // $this->loadData();
            } else {
                $this->dispatch('error', message: 'Data raw material gagal diubah');
            }
        } else {

            $save = app(MaterialUseAceService::class)->saveRawMat($this->dataRawMat);
            // dd($save);
            if ($save) {
                $this->reset(['dataRawMat', 'totalWeight']);
                $this->dispatch('saved');
                $this->dispatch('success', message: 'Data raw material berhasil disave');
            } else {
                $this->dispatch('error', message: 'Data raw material gagal save');
            }
        }
    }
    public function render()
    {
        $this->loadSelectRawMaterial();
        return view('livewire.ace.raw-material');
    }
}
