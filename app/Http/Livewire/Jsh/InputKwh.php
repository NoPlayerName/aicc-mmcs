<?php

namespace App\Http\Livewire\Jsh;

use App\Services\MaterialUseJsh\MaterialUseJshService;
use Livewire\Attributes\Reactive;
use Livewire\Attributes\On;
use Livewire\Component;

class InputKwh extends Component
{
    public $form = [
        'charge_time' => null,
        'kwh_start_charge' => null,
        'kwh_ok_charge' => null,
        'power' => null,
    ];
    // #[Reactive]
    public $chargeId;
    public $edit;


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
        // $this->chargeId = $chargeId;
        // dd($this->chargeId);
    }
    #[On('input-material-data')]
    public function inputData($id, $isEdit)
    {
        $this->reset('form');
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
    public function loadData()
    {
        if ($this->edit) {
            // Ubah ke array agar bisa digabung dengan input manual
            $data = app(MaterialUseJshService::class)->getKwh($this->chargeId);
            // dd($data);
            $this->form = [
                'charging_head_id' => $data->charging_head_id,
                'charge_time' => $data->charge_time,
                'kwh_start_charge' => $data->kwh_start_charge,
                'kwh_ok_charge' => $data->kwh_ok_charge,
                'power' => $data->power,
            ];
        }
    }


    public function save()
    {
        $this->validate();
        if ($this->edit) {

            $save = app(MaterialUseJshService::class)->UpdateKwh($this->form);
            if ($save) {
                $this->loadData();
                $this->dispatch('success', message: 'Data kwh berhasil diubah');
            } else {
                $this->dispatch('error', message: 'Data kwh gagal diubah');
            }
        } else {
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
    }

    public function render()
    {
        return view('livewire.jsh.input-kwh');
    }
}
