<?php

namespace App\Http\Livewire\Jsh;

use App\Services\MaterialUseJsh\MaterialUseJshService;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FormMaterialInput extends Component
{

    public $lot;
    public $id;
    public $chargeId;
    public $description;
    public $product;
    public $charging;
    public $edit = false;

    #[On('FormInputMat')]
    public function showForm($data)
    {
        // dd($data);
        // dd($data);
        // dd($id, $data);
        $this->edit = $data['is_edit'];
        $this->id = $data['production_plan_id'];
        $this->chargeId = $data['chargingHeadId'];
        $this->lot = $data['lot'] ?? '-';
        $this->charging = $data['charging'] ?? null;
        $this->product = $data['model_id'] ?? "-";
        $this->description = $data['desc'] ?? null;
        $this->dispatch('input-material-data', id: $this->chargeId, isEdit: $this->edit);
        $this->dispatch('showFormInput');
    }
    #[On('FormUpdateMat')]
    public function showFormUpdate($data)
    {
        // dd($edit);
        // dd($data);
        // dd($id, $data);
        $this->edit = $data['is_edit'];
        $this->id = $data['production_plan_id'];
        $this->chargeId = $data['chargingHeadId'];
        $this->lot = $data['lot'] ?? '-';
        $this->charging = $data['charging'] ?? '-';
        $this->product = $data['model_id'] ?? "-";

        $this->dispatch('load-material-data', id: $this->chargeId, isEdit: $this->edit);

        $this->dispatch('showFormEdit');
    }
    #[On('rawMat')]
    public function changeRawMat($rawMat, $name)
    {
        $this->dispatch('RawMat', data: $rawMat, name: $name)->to(RawMaterial::class);
    }

    #[On('additMat')]
    public function changeAdditiveMat($data, $name)
    {
        $this->dispatch('AdditiveMat', data: $data, name: $name)->to(AdditiveMaterial::class);
    }
    #[On('typeAddjust')]
    public function changeTypeAddjust($data)
    {
        $this->dispatch('TypeAddjust', data: $data)->to(AdditiveMaterial::class);
    }
    #[On('typeTapping')]
    public function changeTypeTapping($data)
    {
        $this->dispatch('TypeTapping', data: $data)->to(InputTemptTapping::class);
    }

    public function saveCharge()
    {
        $data = [
            'plan_id_anchor' => $this->id,
            'charging' => $this->charging,
            'created_by' => Auth::user()->usr,
        ];

        if ($this->edit) {
            $data['id'] = $this->chargeId;
            $save = app(MaterialUseJshService::class)->updateChargingHead($data);
            if ($save) {
                $chargeId = is_array($save)
                    ? ($save['id'] ?? $this->chargeId)
                    : ($save->id ?? $this->chargeId);

                $this->dispatch('refreshData')->to(MaterialInput::class);
                $this->chargeId = $chargeId;
                $this->dispatch('input-material-data', id: $chargeId, isEdit: $this->edit);
                $this->dispatch('success', message: 'Data charging berhasil diperbarui!');
            } else {
                $this->dispatch('error', message: 'Data charging gagal diperbarui');
            }
        } else {
            $save = app(MaterialUseJshService::class)->saveChargingHead($data);
            if ($save) {
                $this->dispatch('refreshData')->to(MaterialInput::class);
                $this->chargeId = $save['id'];
                $this->dispatch('input-material-data', id: $save['id'], isEdit: $this->edit);
                $this->dispatch('success', message: 'Data charging berhasil ditambahkan!');
            } else {
                $this->dispatch('error', message: 'Data charging gagal ditambahkan');
            }
        }
    }

    public function addDesc()
    {
        $data = [
            'id' => $this->chargeId,
            'desc' => $this->description,
        ];
        if (is_null($this->chargeId)) {
            $this->dispatch('error', message: 'Silahkan simpan data charging terlebih dahulu sebelum menambahkan deskripsi');
            return;
        } else {
            $save = app(MaterialUseJshService::class)->saveDesc($data);
            if ($save) {
                $this->dispatch('refreshData')->to(MaterialInput::class);
                $this->dispatch('success', message: 'Deskripsi berhasil ditambahkan!');
            } else {
                $this->dispatch('error', message: 'Deskripsi gagal ditambahkan');
            }
        }
    }


    public function render()
    {
        return view('livewire.jsh.form-material-input');
    }
}
