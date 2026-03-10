<?php

namespace App\Http\Livewire\Ace;

use App\Services\Master\Material\MaterialService;
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
    }

    public function removeDraft($index)
    {
        $items = collect($this->draftAdjust);
        $items->forget($index);
        $this->draftAdjust = $items->values()->toArray();
    }

    public function saveAdjust()
    {
        if (empty($this->draftAdjust)) {
            $this->dispatch('error', message: 'Draft adjust masih kosong.');
            return;
        }

        $count = count($this->draftAdjust);
        $this->dispatch('success', message: "UI draft adjust siap ({$count} item). Backend simpan belum diaktifkan.");
    }

    #[Title('ACE Material Adjust')]
    public function render()
    {
        return view('livewire.ace.material-adjust');
    }
}
