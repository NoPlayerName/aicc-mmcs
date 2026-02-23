<?php

namespace App\Http\Livewire\Ace;

use App\Services\Master\ProductAce\ProductService;
use App\Services\PlanProductionAce\PlanProductionAceService;
use Livewire\Component;
use Livewire\Attributes\On;

class FormMaterialInput extends Component
{

    public $lot;
    public $id;
    public $chargeId;
    public $product;
    public $productSelect;
    public $charging;
    public $edit = false;
    public $lots = [];
    public $products = [];

    public function mount()
    {
        $this->loadProduct();
    }


    #[On('FormInputMat')]
    public function showForm($data)
    {


        // dd($data);
        // dd($id, $data);
        $this->edit = $data['is_edit'];
        $this->id = $data['plan_id_anchor'];
        $this->chargeId = $data['id'];
        $this->lot = $data['lot'] ?? null;
        $this->charging = $data['charging'] ?? '-';
        $this->product = $data['product']['name'] ?? null;
        $this->dispatch('input-material-data', id: $this->chargeId, isEdit: $this->edit);
        $this->dispatch('showFormInput');
    }
    #[On('LoadFormInputMat')]
    public function loadFormdata($data)
    {


        // dd($data);
        // dd($id, $data);
        $this->edit = $data['is_edit'];
        $this->id = $data['plan_id_anchor'];
        $this->chargeId = $data['id'];
        $this->lot = $data['lot'] ?? null;
        $this->charging = $data['charging'] ?? '-';
        $this->product = $data['product']['name'] ?? null;
        // $this->dispatch('input-material-data', id: $this->chargeId, isEdit: $this->edit);
        // $this->dispatch('showFormInput');
    }
    #[On('lotSelection')]
    public function selectLot($productId = '-', $lotIds = [])
    {
        // dd($productId, $lotIds);
        $this->productSelect = $productId;
        $this->lots = $lotIds;
        $this->saveSelection();
    }

    #[On('productSelect')]
    public function productSelect($productId = '-', $lotIds = [])
    {
        // dd($productId, $lotIds);
        $this->productSelect = $productId;
        $this->lots = $lotIds;
        $this->saveSelection();
    }

    #[On('FormUpdateMat')]
    public function showFormUpdate($data)
    {
        // dd($edit);
        // dd($data);
        // dd($id, $data);
        $this->edit = $data['is_edit'];
        $this->id = $data['plan_id_anchor'];
        $this->chargeId = $data['id'];
        $this->lot = $data['lot'] ?? '-';
        $this->charging = $data['charging'] ?? '-';
        $this->product = $data['product']['name'] ?? "-";

        $this->dispatch('load-material-data', id: $this->chargeId, isEdit: $this->edit);

        $this->dispatch('showFormEdit');
    }
    #[On('rawMat')]
    public function changeRawMat($rawMat, $name)
    {
        $this->dispatch('RawMat', data: $rawMat, name: $name)->to(RawMaterial::class);
    }

    public function saveSelection()
    {
        if (!$this->productSelect || empty($this->lots)) {
            $this->dispatch('notify-error', message: 'Product dan Lots harus dipilih');
            return;
        }
        $query = app(PlanProductionAceService::class)->saveSelection($this->chargeId, $this->productSelect, $this->lots);
        if ($query['status']) {
            $this->dispatch('success', message: 'berhasil menyimpan lot dan product');
            $this->dispatch('loadFurnaceHead')->to(MaterialInput::class);
            $this->dispatch('loadDataFormInputMat')->to(MaterialInput::class);
        } else {
            $this->dispatch('error', message: 'gagal menyimpan lot dan product');
        }
    }

    public function loadProduct()
    {
        $data = app(ProductService::class)->getAllProducts();
        // dd($data);
        $this->products = $data;
    }
    public function render()
    {
        return view('livewire.ace.form-material-input');
    }
}
