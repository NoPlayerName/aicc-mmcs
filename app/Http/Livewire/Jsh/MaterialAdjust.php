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
    public $usage_reference_qty = 0;
    public $stock_opname_qty;
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
            'stock_opname_qty' => 'required|numeric',
            'qty_adjust' => 'required|numeric|not_in:0',
            'note' => 'nullable|string|max:255',
        ];
    }

    public function updatedTransactionDate()
    {
        $this->recalculateAdjustValue();
    }

    public function updatedStockOpnameQty()
    {
        $this->recalculateAdjustValue();
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
            'usage_reference_qty' => (float) $this->usage_reference_qty,
            'stock_opname_qty' => (float) $this->stock_opname_qty,
            'qty_adjust' => (float) $this->qty_adjust,
            'note' => $this->note,
        ];

        $this->reset(['material_id', 'usage_reference_qty', 'stock_opname_qty', 'qty_adjust', 'note']);
        $this->dispatch('resetJshAdjustMaterialSelect');
    }

    #[On('jshAdjustMaterialSelected')]
    public function setMaterial($materialId)
    {
        $this->material_id = $materialId;
        $this->recalculateAdjustValue();
    }

    protected function recalculateAdjustValue(): void
    {
        if (empty($this->transaction_date) || empty($this->material_id)) {
            $this->usage_reference_qty = 0;
            $this->qty_adjust = null;
            return;
        }

        $usage = app(MaterialAdjustJshService::class)
            ->getUsageReference($this->transaction_date, $this->material_id);

        $this->usage_reference_qty = $usage;

        if ($this->stock_opname_qty === null || $this->stock_opname_qty === '') {
            $this->qty_adjust = null;
            return;
        }

        $this->qty_adjust = round(((float) $this->stock_opname_qty) - (float) $usage, 3);
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

        $this->reset(['draftAdjust', 'material_id', 'usage_reference_qty', 'stock_opname_qty', 'qty_adjust', 'note']);
        $this->dispatch('resetJshAdjustMaterialSelect');
        $this->dispatch('success', message: $result['message']);
    }

    #[Title('JSH Material Adjust')]
    public function render()
    {
        return view('livewire.jsh.material-adjust');
    }
}
