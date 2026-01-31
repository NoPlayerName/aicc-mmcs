<?php

namespace App\Http\Livewire\Jsh;

use App\Enums\EnumTypeTapping;
use App\Services\MaterialUseJsh\MaterialUseJshService;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class InputTemptTapping extends Component
{

    public $dataTapping = [];
    public $temperature = '';
    public $typeTapping = '';
    public $typeTappingText = '';
    public $selectType;
    #[Reactive]
    public $chargeId;

    public function rules()
    {
        return [
            'temperature' => 'required',
            'typeTapping' => 'required',
        ];
    }

    public function mount($chargeId = null)
    {

        $this->chargeId = $chargeId;
        $this->selectType = EnumTypeTapping::all();
    }

    #[On('TypeTapping')]
    public function changeRawMat($data)
    {
        $this->typeTapping = $data;
    }

    public function addTapping()
    {
        $this->validate();
        $this->dataTapping[] = [
            'charging_head_id' => $this->chargeId,
            'temperatur' => $this->temperature,
            'type_tapping' => $this->typeTapping,
            'typeTappingText' => EnumTypeTapping::tryFrom($this->typeTapping)?->text(),

        ];
        $this->temperature = null;
    }

    public function remove($index)
    {
        unset($this->dataTapping[$index]);
        $this->dataTapping = array_values($this->dataTapping);
    }
    public function save()
    {
        $save =  app(MaterialUseJshService::class)->saveTemptTapping($this->dataTapping);
        if ($save) {
            $this->reset('dataTapping');
            // $this->dispatch('saved');
            $this->dispatch('success', message: 'Data Tapping berhasil disave');
        } else {
            $this->dispatch('error', message: 'Data Tapping gagal save');
        }
    }
    public function render()
    {
        return view('livewire.jsh.input-tempt-tapping');
    }
}
