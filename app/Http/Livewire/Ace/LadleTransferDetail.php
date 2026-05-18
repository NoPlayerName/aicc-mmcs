<?php

namespace App\Http\Livewire\Ace;

use App\Models\Ace\MaterialUse\LadleTfHead;
use Livewire\Attributes\On;
use Livewire\Component;

class LadleTransferDetail extends Component
{
    public $ladleId;
    public $furnace = '-';
    public $product = '-';
    public $lot = '-';
    public $moltTmpt = 0;
    public $beratMolt = 0;

    public $weighingStatus = false;
    public $moltStatusConvy = false;
    public $moltLadleStatus = false;
    public $treatmentStatus = false;

    public $dataMat = [];

    #[On('showLadleTransferDetail')]
    public function loadDetail($id = null)
    {
        $recordId = is_array($id) ? ($id['id'] ?? null) : $id;
        $this->ladleId = (int) $recordId;

        $ladle = LadleTfHead::with(['furnace', 'product', 'inoculant.materialable'])
            ->find($this->ladleId);

        if (!$ladle) {
            return;
        }

        $this->furnace = $ladle->furnace?->furnace ?? '-';
        $this->product = $ladle->product?->alias ?? '-';
        $this->lot = $ladle->lot ?? '-';
        $this->moltTmpt = $ladle->ladle_molten_temp ?? 0;
        $this->beratMolt = $ladle->molten_weight ?? 0;

        $this->weighingStatus = (bool) $ladle->weighing_status;
        $this->moltStatusConvy = (bool) $ladle->conveyor_drop_status;
        $this->moltLadleStatus = (bool) $ladle->ladle_drop_status;
        $this->treatmentStatus = (bool) $ladle->treatment_duration_check;

        $this->dataMat = collect($ladle->inoculant ?? [])->map(function ($item) {
            return [
                'material_name' => $item->materialable?->material_name ?? $item->material_name ?? '-',
                'weight' => $item->weight,
            ];
        })->values()->toArray();

        $this->dispatch('showLadleTransferDetailModal');
    }

    public function render()
    {
        return view('livewire.ace.ladle-transfer-detail');
    }
}
