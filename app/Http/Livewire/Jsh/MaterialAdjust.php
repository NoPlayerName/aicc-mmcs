<?php

namespace App\Http\Livewire\Jsh;

use App\Services\Master\Material\MaterialService;
use App\Services\MaterialUseJsh\MaterialAdjustJshService;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

class MaterialAdjust extends Component
{
    public $transaction_date;
    public $material_id = '';
    public $qty_adjust;
    public $note = '';
    public $draftAdjust = [];
    public $materials = [];

    public function mount()
    {
        $raw = collect(app(MaterialService::class)->getRawMat())->map(function ($item) {
            return [
                'material_code' => $item['material_code'] ?? null,
                'material_name' => $item['material_name'] ?? '-',
                'material_type' => 'RAW',
            ];
        });

        $additive = collect(app(MaterialService::class)->getAdditive())->map(function ($item) {
            return [
                'material_code' => $item['material_code'] ?? null,
                'material_name' => $item['material_name'] ?? '-',
                'material_type' => 'ADDITIVE',
            ];
        });

        $this->materials = $raw
            ->merge($additive)
            ->filter(fn($item) => !empty($item['material_code']))
            ->unique('material_code')
            ->sortBy('material_name')
            ->values()
            ->toArray();
    }

    public function rules()
    {
        return [
            'transaction_date' => 'required|date',
            'material_id' => 'required|string',
            'qty_adjust' => 'required|numeric|not_in:0',
            'note' => 'nullable|string|max:255',
        ];
    }

    public function addDraft()
    {
        $this->validate();

        $material = collect($this->materials)->firstWhere('material_code', $this->material_id);

        $this->draftAdjust[] = [
            'transaction_date' => $this->transaction_date,
            'material_id' => $this->material_id,
            'material_name' => $material['material_name'] ?? '-',
            'material_type' => $material['material_type'] ?? '-',
            'qty_adjust' => (float) $this->qty_adjust,
            'note' => $this->note,
        ];

        $this->reset(['material_id', 'qty_adjust', 'note']);
        $this->dispatch('resetJshAdjustMaterialSelect');
    }

    #[On('jshAdjustMaterialSelected')]
    public function setMaterial($materialId)
    {
        $this->material_id = $materialId;
    }

    public function removeDraft($index)
    {
        $items = collect($this->draftAdjust);
        $items->forget($index);
        $this->draftAdjust = $items->values()->toArray();
    }

    public function saveAdjust()
    {
        $result = app(MaterialAdjustJshService::class)->saveAdjust($this->draftAdjust);

        if (!$result['status']) {
            $this->dispatch('error', message: $result['message']);
            return;
        }

        $this->reset(['draftAdjust', 'material_id', 'qty_adjust', 'note']);
        $this->dispatch('resetJshAdjustMaterialSelect');
        $this->dispatch('success', message: $result['message']);
    }

    #[Title('JSH Material Adjust')]
    public function render()
    {
        return view('livewire.jsh.material-adjust');
    }
}
