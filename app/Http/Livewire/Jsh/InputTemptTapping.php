<?php

namespace App\Http\Livewire\Jsh;

use App\Enums\EnumTypeTapping;
use App\Services\MaterialUseJsh\MaterialUseJshService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class InputTemptTapping extends Component
{

    public $dataTapping = [];
    public $temperatur = '';
    public $type_tapping = '';
    public $type_tapping_text = '';
    public $selectType;
    // #[Reactive]
    public $chargeId;
    public $edit;

    public function rules()
    {
        return [
            'temperatur' => 'required',
            'type_tapping' => 'required',
        ];
    }

    public function mount($chargeId = null)
    {

        // $this->chargeId = $chargeId;
        $this->selectType = EnumTypeTapping::all();
    }
    #[On('input-material-data')]
    public function inputData($id, $isEdit)
    {
        $this->reset('dataTapping');
        $this->chargeId = $id;
        $this->edit = $isEdit;
    }
    #[On('load-material-data')]
    public function triggerLoad($id, $isEdit)
    {
        // dd($id);
        $this->chargeId = $id;
        $this->edit = $isEdit;
        $this->loadData();
    }

    public function loadData()
    {
        if ($this->edit) {
            // Ubah ke array agar bisa digabung dengan input manual
            $data = app(MaterialUseJshService::class)->getTempTapping($this->chargeId);
            // PAKSA JADI ARRAY DI SINI
            // Agar selanjutnya array_values() tidak error
            $this->dataTapping = collect($data)->toArray();
        }
    }

    #[On('TypeTapping')]
    public function changeRawMat($data)
    {
        $this->type_tapping = $data;
    }

    public function addTapping()
    {
        $user = Auth::user()->usr;
        $this->validate();
        $this->dataTapping[] = [
            'charging_head_id' => $this->chargeId,
            'temperatur' => $this->temperatur,
            'type_tapping' => $this->type_tapping,
            'type_tapping_text' => EnumTypeTapping::tryFrom($this->type_tapping)?->text(),
            'created_at' => now(),
            'created_by' => $user,

        ];
        $this->temperatur = null;
    }

    public function remove($index)
    {
        unset($this->dataTapping[$index]);
        $this->dataTapping = array_values($this->dataTapping);
    }
    public function save()
    {

        if ($this->edit) {
            $save =  app(MaterialUseJshService::class)->updateTemptTapping($this->dataTapping);
            if ($save) {
                $this->loadData();
                $this->dispatch('success', message: 'Data Tapping berhasil diubah');
            } else {
                $this->dispatch('error', message: 'Data Tapping gagal diubah');
            }
        } else {
            $save =  app(MaterialUseJshService::class)->saveTemptTapping($this->dataTapping);
            if ($save) {
                $this->reset('dataTapping');
                // $this->dispatch('saved');
                $this->dispatch('success', message: 'Data Tapping berhasil disave');
            } else {
                $this->dispatch('error', message: 'Data Tapping gagal save');
            }
        }
    }
    public function render()
    {
        return view('livewire.jsh.input-tempt-tapping');
    }
}
