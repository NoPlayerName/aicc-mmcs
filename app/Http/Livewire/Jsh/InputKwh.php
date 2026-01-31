<?php

namespace App\Http\Livewire\Jsh;

use App\Services\MaterialUseJsh\MaterialUseJshService;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class InputKwh extends Component
{
    public $form = [
        'charge_time' => null,
        'kwh_start_charge' => null,
        'kwh_ok_charge' => null,
        'power' => null,
    ];
    #[Reactive]
    public $chargeId;


    public function rules()
    {
        return [
            'form.charge_time' => 'required',
            'form.kwh_start_charge' => 'required',
            'form.kwh_ok_charge' => 'required',
            'form.power' => 'required',
        ];
    }

    public function mount($chargeId = null)
    {
        $this->chargeId = $chargeId;
        // dd($this->chargeId);
    }

    public function save()
    {
        $this->validate();
        $data = array_merge($this->form, [
            'charging_head_id' => $this->chargeId,
        ]);
        $save = app(MaterialUseJshService::class)->saveKwh($data);

        if ($save) {
            $this->reset('form');
            $this->dispatch('success', message: 'Data kwh berhasil disave');
        } else {
            $this->dispatch('error', message: 'Data kwh gagal save');
        }
    }

    public function render()
    {
        return view('livewire.jsh.input-kwh');
    }
}
