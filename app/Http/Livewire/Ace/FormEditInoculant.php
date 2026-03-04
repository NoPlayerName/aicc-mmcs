<?php

namespace App\Http\Livewire\Ace;

use App\Models\Ace\MaterialUse\LadleTfHead;
use App\Services\Master\Material\MaterialService;
use App\Services\Master\ProductAce\ProductService;
use App\Services\MaterialUseAce\MaterialUseAceService;
use App\Services\PlanProductionAce\PlanProductionAceService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class FormEditInoculant extends Component
{
    public $products = [], $dataMat = [], $inoculant = [];
    public $product;

    public $material, $materialText;
    public $furnace;
    public $furnaces = [];
    public $lot, $moltTmpt, $beratMolt, $weight;

    public $weighingStatus = false, $moltStatusConvy = false, $moltLadleStatus = false, $treatmentStatus = false;
    public $ladleHeadId;

    public function rules()
    {
        return [
            'product' => 'required',
            'furnace' => 'required',
            'lot' => 'required',
            'moltTmpt' => 'required',
            'beratMolt' => 'required',
            'dataMat' => 'required|array|min:1',
            'dataMat.*.material_id' => 'required',
            'dataMat.*.weight' => 'required|numeric|gt:0',
        ];
    }

    public function messages()
    {
        return [
            'dataMat.required' => 'Material wajib ditambahkan minimal 1 data.',
            'dataMat.array' => 'Format data material tidak valid.',
            'dataMat.min' => 'Material wajib ditambahkan minimal 1 data.',
            'dataMat.*.material_id.required' => 'Material tidak valid.',
            'dataMat.*.weight.required' => 'Berat material wajib diisi.',
            'dataMat.*.weight.numeric' => 'Berat material harus berupa angka.',
            'dataMat.*.weight.gt' => 'Berat material harus lebih dari 0.',
        ];
    }

    public function addMaterialRules()
    {
        return [
            'material' => 'required',
            'weight' => 'required',
        ];
    }

    public function mount()
    {
        $this->loadProduct();
        $this->loadFurnace();
        $this->loadSelectAdditive();
    }

    private function resetFormState(): void
    {
        $this->reset([
            'ladleHeadId',
            'product',
            'furnace',
            'lot',
            'moltTmpt',
            'beratMolt',
            'material',
            'materialText',
            'weight',
            'dataMat',
        ]);

        $this->weighingStatus = false;
        $this->moltStatusConvy = false;
        $this->moltLadleStatus = false;
        $this->treatmentStatus = false;
    }

    private function extractEventValue($payload, string $key)
    {
        if (is_array($payload)) {
            return $payload[$key] ?? null;
        }

        return $payload;
    }

    #[On('productSelectEdit')]
    public function productSelectEdit($productId = null)
    {
        $value = $this->extractEventValue($productId, 'productId');
        $this->product = ($value !== null && $value !== '') ? (int) $value : null;
    }

    #[On('selectFurnaceEdit')]
    public function selectFurnaceEdit($furnace = null)
    {
        $value = $this->extractEventValue($furnace, 'furnace');
        $this->furnace = ($value !== null && $value !== '') ? (int) $value : null;
    }

    #[On('materialSelectEdit')]
    public function materialSelectEdit($data = null, $name = null)
    {
        if (is_array($data)) {
            $this->material = $data['data'] ?? null;
            $this->materialText = $data['name'] ?? null;
            return;
        }

        $this->material = $data;
        $this->materialText = $name;
    }

    #[On('showFormEdit')]
    #[On('loadFormEdit')]
    public function loadFormEdit($id = null)
    {
        $recordId = $this->extractEventValue($id, 'id');
        $ladle = LadleTfHead::with(['inoculant.materialable'])->find((int) $recordId);
        if (!$ladle) {
            $this->resetFormState();
            return;
        }

        $this->resetValidation();

        $this->ladleHeadId = $ladle->id;
        $this->product = $ladle->product_id;
        $this->furnace = $ladle->furnace_id;
        $this->lot = $ladle->lot;
        $this->moltTmpt = $ladle->ladle_molten_temp;
        $this->beratMolt = $ladle->molten_weight;
        $this->weighingStatus = (bool) $ladle->weighing_status;
        $this->moltStatusConvy = (bool) $ladle->conveyor_drop_status;
        $this->moltLadleStatus = (bool) $ladle->ladle_drop_status;
        $this->treatmentStatus = (bool) $ladle->treatment_duration_check;

        $inoculants = collect($ladle->inoculant ?? [])->map(function ($item) {
            return [
                'id' => $item->id,
                'leadle_head_id' => $item->leadle_head_id,
                'material_id' => $item->material_id,
                'material_name' => $item->materialable?->material_name ?? $item->material_name ?? '-',
                'weight' => $item->weight,
                'created_by' => $item->created_by,
                'created_at' => $item->created_at,
            ];
        })->values()->toArray();

        $this->dataMat = $inoculants;

        $this->dispatch('syncEditInoculantSelect', furnace: $this->furnace, product: $this->product);
    }

    public function loadProduct()
    {
        $data = app(ProductService::class)->getAllProducts();
        $this->products = $data;
    }

    public function loadFurnace()
    {
        $data = app(PlanProductionAceService::class)->getFurnace(null, null);
        $this->furnaces = $data;
    }

    public function loadSelectAdditive()
    {
        $data = app(MaterialService::class)->getAdditive();
        $this->inoculant = collect($data)->toArray();
    }

    public function addMaterial()
    {
        $user = Auth::user()->usr;
        // $this->validate();
        $this->validate($this->addMaterialRules());
        $this->dataMat[] = [
            'material_id' => $this->material,
            'material_name' => $this->materialText,
            'weight' => $this->weight,
            'created_by' => $user,
            'created_at' => now(),
        ];

        $this->resetValidation($this->addMaterialRules());
        $this->reset(['weight', 'material', 'materialText']);
        $this->dispatch('resetMaterialSelectUpdate');
    }

    public function remove($index)
    {
        $items = collect($this->dataMat);
        $items->forget($index);
        $this->dataMat = $items->values()->toArray();
    }

    public function save()
    {
        if (empty($this->ladleHeadId)) {
            $this->dispatch('error', message: 'Data edit tidak ditemukan');
            return;
        }

        $this->validate();

        $user = Auth::user()->usr;
        $now = now();

        $data = [
            'ladlehead' => [
                'furnace_id' => $this->furnace,
                'lot' => $this->lot,
                'product_id' => $this->product,
                'weighing_status' => (bool) $this->weighingStatus,
                'conveyor_drop_status' => (bool) $this->moltStatusConvy,
                'ladle_drop_status' => (bool) $this->moltLadleStatus,
                'treatment_duration_check' => (bool) $this->treatmentStatus,
                'molten_weight' => $this->beratMolt,
                'ladle_molten_temp' => $this->moltTmpt,
                'updated_by' => $user,
                'updated_at' => $now,
            ],
            'ladleMat' => collect($this->dataMat)->map(function ($item) use ($user, $now) {
                return [
                    'id' => $item['id'] ?? null,
                    'leadle_head_id' => $item['leadle_head_id'] ?? $this->ladleHeadId,
                    'material_id' => $item['material_id'] ?? null,
                    'material_name' => $item['material_name'] ?? null,
                    'weight' => $item['weight'] ?? null,
                    'created_by' => $item['created_by'] ?? $user,
                    'created_at' => $item['created_at'] ?? $now,
                    'updated_by' => $user,
                    'updated_at' => $now,
                ];
            })->values()->toArray(),
        ];

        $query = app(MaterialUseAceService::class)->updateLadleTransfer($this->ladleHeadId, $data);

        if (is_array($query) && ($query['status'] ?? false)) {
            $this->dispatch('success', message: 'Data inoculant berhasil diupdate');
            $this->dispatch('savedInoculant');
            $this->dispatch('refreshLadleTransferClient');
            $this->resetFormState();
            return;
        }

        $this->dispatch('error', message: 'Data inoculant gagal diupdate');
    }

    public function render()
    {
        return view('livewire.ace.form-edit-inoculant');
    }
}
