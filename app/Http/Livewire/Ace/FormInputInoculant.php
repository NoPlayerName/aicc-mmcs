<?php

namespace App\Http\Livewire\Ace;

use App\Services\Master\Material\MaterialService;
use App\Services\Master\ProductAce\ProductService;
use App\Services\MaterialUseAce\MaterialUseAceService;
use App\Services\PlanProductionAce\PlanProductionAceService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class FormInputInoculant extends Component
{
    public $products = [], $dataMat = [], $inoculant = [];
    public $product;

    public $material, $materialText;
    public $furnace;
    public $furnaces = [];
    public $lot, $moltTmpt, $beratMolt, $weight;

    public $weighingStatus = false, $moltStatusConvy = false, $moltLadleStatus = false, $treatmentStatus = false;

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

    #[On('productSelect')]
    public function productSelect($productId)
    {
        $this->product = $productId;
    }
    #[On('selectFurnace')]
    public function selectFurnace($furnace)
    {
        $this->furnace = $furnace;
    }
    #[On('materialSelect')]
    public function materialSelect($data, $name)
    {
        $this->material = $data;
        $this->materialText = $name;
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
        $this->dispatch('resetMaterialSelect');
    }
    public function remove($index)
    {
        $items = collect($this->dataMat);
        $items->forget($index);
        $this->dataMat = $items->values()->toArray();
    }

    public function save()
    {
        $this->validate();

        $user = Auth::user()->usr;

        $data = [
            'ladlehead' => [
                'furnace_id' => $this->furnace,
                'lot' => $this->lot,
                'product_id' => $this->product,
                'weighing_status' => $this->weighingStatus,
                'conveyor_drop_status' => $this->moltStatusConvy,
                'ladle_drop_status' => $this->moltLadleStatus,
                'treatment_duration_check' => $this->treatmentStatus,
                'molten_weight' => $this->beratMolt,
                'ladle_molten_temp' => $this->moltTmpt,
                'created_by' => $user,
                'updated_by' => $user,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            'ladleMat' => $this->dataMat,
        ];

        $query =  app(MaterialUseAceService::class)->saveLadleTransfer($data);

        if (is_array($query) && ($query['status'] ?? false)) {
            $this->reset(['weight', 'moltTmpt', 'beratMolt', 'dataMat']);
            $this->dispatch('success', message: 'Data inoculant berhasil disimpan');
            $this->dispatch('resetProduct');
            $this->dispatch('resetFurnace');
            $this->dispatch('savedInoculant');
        } else {
            $this->dispatch('error', message: 'Data inoculant gagal disimpan');
        }



        // dd($data);
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
    public function render()
    {
        return view('livewire.ace.form-input-inoculant');
    }
}
