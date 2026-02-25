<div class="p-2">
    <div class="row align-items-end g-3 mb-4">

        <div class="col-md-3">
            @php $label = !$is_trial ? 'Material' : 'Material Trial'; @endphp
            <label class="form-label font-weight-bold">{{ $label }}</label>
            <div wire:ignore wire:key="container-additive-{{ $is_trial ? 'trial' : 'reguler' }}">
                <select class="form-control form-control-lg @error('material') is-invalid @enderror"
                    id="Additive-select2">
                    <option value="">Select Additive</option>
                    @foreach ($additiveSelect as $rw)
                    <option value="{{ $rw['material_code'] }}">{{ $rw['material_name'] }}</option>
                    @endforeach
                </select>
            </div>
            @error('material')
            <small class="text-danger position-absolute">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-md-3">
            <label class="form-label font-weight-bold">Type Adjustment</label>
            <div wire:ignore>
                <select class="form-control form-control-lg select2" id="Type-Adjust-select2">
                    <option value="">Select Type</option>
                    <option value="1">Pra Adjust</option>
                    <option value="2">Adjustment</option>
                </select>
            </div>
        </div>

        <div class="col-md-2">
            <label class="form-label font-weight-bold">Weight (KG)</label>
            <div class="input-group input-group-lg">
                <input type="number" class="form-control @error('weight') is-invalid @enderror" placeholder="0.00"
                    min="0" wire:model="weight">
                <div class="input-group-append">
                    <span class="input-group-text bg-light font-weight-bold">kg</span>
                </div>
            </div>
            @error('weight')
            <small class="text-danger position-absolute">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-md-2 text-center">
            <label class="form-label font-weight-bold d-block">Trial Mode</label>
            <div class="pt-1">
                <input type="checkbox" id="switch2-additive" switch="success" wire:model.live="is_trial" />
                <label for="switch2-additive" data-on-label="Yes" data-off-label="No" class="mb-0"></label>
            </div>
        </div>

        <div class="col-md-2">
            <button class="btn btn-lg btn-primary btn-block shadow-sm" wire:click='addMat' wire:loading.attr="disabled">
                <i class="fas fa-plus mr-1" wire:loading.remove wire:target="addMat"></i>
                <span class="spinner-border spinner-border-sm mr-1" role="status" wire:loading
                    wire:target="addMat"></span>
                Add
            </button>
        </div>
    </div>

    <div class="card border rounded shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover table-centered mb-0 mt-0">
                <thead class="bg-light text-dark">
                    <tr>
                        <th class="px-4 py-3">Material Name</th>
                        <th class="text-center">Type Adjustment</th>
                        <th class="text-center" style="width: 180px;">Weight (KG)</th>
                        <th class="text-center" style="width: 100px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dataAdditiveMat as $index => $aW)
                    <tr>
                        <td class="px-4 font-weight-medium text-dark">{{ $aW['material_name'] }}</td>
                        <td class="text-center">
                            <span
                                class="badge {{ $aW['type_additive_text'] == 'Adjustment' ? 'badge-soft-warning' : 'badge-soft-info' }} px-3 py-2">
                                {{ $aW['type_additive_text'] }}
                            </span>
                        </td>
                        <td class="text-center font-weight-bold text-primary">{{ number_format($aW['weight'], 2) }} kg
                        </td>
                        <td class="text-center">
                            <button class="btn btn-outline-danger btn-sm rounded-circle waves-effect"
                                wire:click='remove({{ $index }})' data-toggle="tooltip" title="Remove">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted bg-white">
                            <i class="fas fa-vial fa-3x mb-3 d-block text-light"></i>
                            Belum ada material additive yang ditambahkan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12 d-flex justify-content-end align-items-center bg-light p-3 rounded border">
            <button type="button" class="btn btn-success btn-lg px-5 shadow font-weight-bold" wire:click='save'
                wire:loading.attr="disabled">
                <i class="fas fa-save mr-2" wire:loading.remove wire:target="save"></i>
                <span class="spinner-border spinner-border-sm mr-2" wire:loading wire:target="save"></span>
                Save
            </button>
        </div>
    </div>
</div>