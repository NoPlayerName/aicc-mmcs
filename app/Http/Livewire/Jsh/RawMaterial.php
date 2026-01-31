<?php

namespace App\Http\Livewire\Jsh;

use App\Enums\EnumTypeMat;
use App\Services\MaterialUseJsh\MaterialUseJshService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;

class RawMaterial extends Component
{

    public $dataRawMat = [];
    public $material = '';
    public $weight = '';
    public $totalWeight = 0;
    #[Reactive]
    public $chargeId;
    #[Reactive]
    public $edit;


    public function rules()
    {
        return [
            'material' => 'required',
            'weight' => 'required|numeric',
        ];
    }
    public function mount($chargeId = null, $edit = false)
    {
        $this->chargeId = $chargeId;
        $this->edit = $edit;
    }

    public function loadData()
    {
        if ($this->edit && $this->chargeId) {
            // Ubah ke array agar bisa digabung dengan input manual
            $data = app(MaterialUseJshService::class)->getRawMat($this->chargeId);

            // PAKSA JADI ARRAY DI SINI
            // Agar selanjutnya array_values() tidak error
            $this->dataRawMat = collect($data)->toArray();
            $this->calculate();
        }
    }


    #[On('RawMat')]
    public function changeRawMat($data)
    {
        $this->material = $data;
    }

    public function addRawMat()
    {
        // dd($this->edit);
        $user = Auth::user()->usr;
        $this->validate();
        $this->dataRawMat[] = [
            'charging_head_id' => $this->chargeId,
            'material_id' => $this->material,
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
        $this->totalWeight = collect($this->dataRawMat)->sum('weight');
    }

    public function save()
    {

        $save = app(MaterialUseJshService::class)->saveRawMat($this->dataRawMat);
        // dd($save);
        if ($save) {
            $this->reset(['dataRawMat', 'totalWeight']);
            $this->dispatch('saved');
            $this->dispatch('success', message: 'Data raw material berhasil disave');
        } else {
            $this->dispatch('error', message: 'Data raw material gagal save');
        }
    }

    public function render()
    {
        $this->loadData();
        return view('livewire.jsh.raw-material');
    }
}
