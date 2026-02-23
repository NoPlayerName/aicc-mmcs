<div class="p-2">
    <div class="row align-items-end g-3 mb-4">
        <div class="col-md-3">
            @php $label = !$is_trial ? 'Material' : 'Material Trial'; @endphp
            <label class="form-label font-weight-bold">{{ $label }}</label>
            <div wire:ignore wire:key="container-select-{{ $is_trial ? 'trial' : 'reguler' }}">
                <select class="form-control form-control-lg @error('material') is-invalid @enderror"
                    id="rawMat-select2">
                    <option value="">Select Material</option>
                    @foreach ($rawMatSelect as $rw)
                    <option value="{{ $rw['material_code'] }}">{{ $rw['material_name'] }}</option>
                    @endforeach
                </select>
            </div>
            @error('material')
            <small class="text-danger position-absolute">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-md-2">
            <label class="form-label font-weight-bold">Weight (KG)</label>
            <div class="input-group input-group-lg">
                <input type="number" class="form-control @error('weight') is-invalid @enderror" placeholder="0.00"
                    wire:model="weight">
                <div class="input-group-append">
                    <span class="input-group-text bg-light">kg</span>
                </div>
            </div>
            @error('weight')
            <small class="text-danger position-absolute">{{ $message }}</small>
            @enderror
        </div>

        <div class="col-md-2">
            <label class="form-label font-weight-bold text-info">Total Material</label>
            <input class="form-control form-control-lg bg-soft-info border-info font-weight-bold" type="text" readonly
                wire:model='totalWeight'>
        </div>

        <div class="col-md-2 text-center">
            <label class="form-label font-weight-bold d-block">Trial Mode</label>
            <div class="pt-1">
                <input type="checkbox" id="switch2" switch="success" wire:model.live="is_trial" />
                <label for="switch2" data-on-label="Yes" data-off-label="No" class="mb-0"></label>
            </div>
        </div>

        <div class="col-md-2">
            <button class="btn btn-lg btn-primary btn-block shadow-sm" wire:click='addRawMat'
                wire:loading.attr="disabled">
                <i class="fas fa-plus mr-1" wire:loading.remove wire:target="addRawMat"></i>
                <span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true" wire:loading
                    wire:target="addRawMat"></span>
                Add
            </button>
        </div>
    </div>

    <div class="card border rounded shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover table-centered mb-0 mt-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3">Material Name</th>
                        <th class="text-center" style="width: 200px;">Weight (KG)</th>
                        <th class="text-center" style="width: 100px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dataRawMat as $index => $rW)
                    <tr>
                        <td class="px-4 font-weight-medium text-dark">{{ $rW['material_name'] }}</td>
                        <td class="text-center font-weight-bold text-primary">{{ number_format($rW['weight'], 2) }} kg
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
                        <td colspan="3" class="text-center py-5 text-muted bg-white">
                            <i class="fas fa-box-open fa-3x mb-3 d-block"></i>
                            Belum ada material yang ditambahkan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12 d-flex justify-content-between align-items-center bg-light p-3 rounded border">
            <span class="text-muted small italic">Pastikan data sudah benar sebelum menyimpan ke database.</span>
            <button type="button" class="btn btn-success btn-lg px-5 shadow" wire:click='save'
                wire:loading.attr="disabled">
                <i class="fas fa-save mr-2" wire:loading.remove wire:target="save"></i>
                <span class="spinner-border spinner-border-sm mr-2" wire:loading wire:target="save"></span>
                Save
            </button>
        </div>
    </div>
</div>